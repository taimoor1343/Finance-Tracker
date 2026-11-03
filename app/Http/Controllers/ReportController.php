<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'from' => ['nullable', 'date', 'required_with:to'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $startDate = !empty($validated['from'])
            ? \Illuminate\Support\Carbon::parse($validated['from'])->startOfDay()
            : now()->startOfMonth()->subMonths(5)->startOfMonth();

        $endDate = !empty($validated['to'])
            ? \Illuminate\Support\Carbon::parse($validated['to'])->endOfDay()
            : now()->endOfMonth()->endOfDay();

        $monthlyReport = collect();

        $month = $startDate->copy()->startOfMonth();
        $lastMonth = $endDate->copy()->startOfMonth();

        while ($month->lte($lastMonth)) {
            $monthStart = $month->copy()->startOfMonth()->toDateString();
            $monthEnd = $month->copy()->endOfMonth()->toDateString();

            $rangeStart = $startDate->toDateString();
            $rangeEnd = $endDate->toDateString();

            $effectiveStart = max($monthStart, $rangeStart);
            $effectiveEnd = min($monthEnd, $rangeEnd);

            $monthlyReport->push([
                'month' => $month->format('M Y'),
                'income' => (float) $user->transactions()
                    ->where('type', 'income')
                    ->whereBetween('transaction_date', [$effectiveStart, $effectiveEnd])
                    ->sum('amount'),
                'expenses' => (float) $user->transactions()
                    ->where('type', 'expense')
                    ->whereBetween('transaction_date', [$effectiveStart, $effectiveEnd])
                    ->sum('amount'),
            ]);

            $month->addMonth();
        }
        
$expensesByCategory = $user->transactions()
    ->where('type', 'expense')
    ->whereBetween('transaction_date', [
        $startDate->toDateString(),
        $endDate->toDateString(),
    ])
    ->selectRaw('category, SUM(amount) as total')
    ->groupBy('category')
    ->orderByDesc('total')
    ->get();


        $totalIncome = $monthlyReport->sum('income');
        $totalExpenses = $monthlyReport->sum('expenses');

        return view('reports.index', [
            'monthlyReport' => $monthlyReport,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netSavings' => $totalIncome - $totalExpenses,
            'expensesByCategory' => $expensesByCategory,
        ]);
    }
}
