<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        // Lifetime financial totals: one database query.
        $allTimeTotals = $user->transactions()
            ->selectRaw("
                COALESCE(SUM(CASE
                    WHEN type = 'income' THEN amount
                    ELSE 0
                END), 0) AS total_income,

                COALESCE(SUM(CASE
                    WHEN type = 'expense' THEN amount
                    ELSE 0
                END), 0) AS total_expenses,

                COALESCE(SUM(CASE
                    WHEN type = 'income' THEN amount
                    WHEN type = 'expense' THEN -amount
                    ELSE 0
                END), 0) AS net_balance
            ")
            ->first();

        // Current month's totals: one database query.
        $monthlyTotals = $user->transactions()
            ->whereBetween('transaction_date', [$monthStart, $monthEnd])
            ->selectRaw("
                COALESCE(SUM(CASE
                    WHEN type = 'income' THEN amount
                    ELSE 0
                END), 0) AS monthly_income,

                COALESCE(SUM(CASE
                    WHEN type = 'expense' THEN amount
                    ELSE 0
                END), 0) AS monthly_expenses,

                COALESCE(SUM(CASE
                    WHEN type = 'income' THEN amount
                    WHEN type = 'expense' THEN -amount
                    ELSE 0
                END), 0) AS monthly_savings
            ")
            ->first();

        // Most recent transactions.
        $recentTransactions = $user->transactions()
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'name' => $user->name,

            'totalIncome' => $allTimeTotals->total_income,
            'totalExpenses' => $allTimeTotals->total_expenses,
            'netBalance' => $allTimeTotals->net_balance,

            'monthlyIncome' => $monthlyTotals->monthly_income,
            'monthlyExpenses' => $monthlyTotals->monthly_expenses,
            'monthlySavings' => $monthlyTotals->monthly_savings,

            'recentTransactions' => $recentTransactions,
        ]);
    }
}
