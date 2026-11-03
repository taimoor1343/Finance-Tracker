<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    private function categories(): array
    {
        return [
            'Food & Dining',
            'Transport',
            'Shopping',
            'Bills & Utilities',
            'Housing',
            'Healthcare',
            'Education',
            'Entertainment',
            'Other',
        ];
    }

    /**
     * Display the authenticated user's budgets for the current month.
     */
    public function index(Request $request)
    {
        $month = now()->month;
        $year = now()->year;

        $budgets = $request->user()
            ->budgets()
            ->where('month', $month)
            ->where('year', $year)
            ->withSum('transactions', 'amount')
            ->orderBy('category')
            ->get();

        return view('budgets.index', [
            'budgets' => $budgets,
            'categories' => $this->categories(),
            'month' => $month,
            'year' => $year,
            'monthName' => now()->format('F'),
        ]);
    }

    /**
     * Create a budget or update the existing category budget.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                Rule::in($this->categories()),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],
        ]);

        $request->user()->budgets()->updateOrCreate(
            [
                'category' => $validated['category'],
                'month' => now()->month,
                'year' => now()->year,
            ],
            [
                'amount' => $validated['amount'],
            ]
        );

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Budget saved successfully.');
    }

    /**
     * Update an existing budget belonging to the authenticated user.
     */
    public function update(Request $request, Budget $budget)
    {
        $ownedBudget = $request->user()
            ->budgets()
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->findOrFail($budget->id);

        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                Rule::in($this->categories()),
                Rule::unique('budgets', 'category')
                    ->where('user_id', $request->user()->id)
                    ->where('month', $ownedBudget->month)
                    ->where('year', $ownedBudget->year)
                    ->ignore($ownedBudget->id),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],
        ]);

        $ownedBudget->update([
            'category' => $validated['category'],
            'amount' => $validated['amount'],
        ]);

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    /**
     * Delete an existing budget belonging to the authenticated user.
     */
    public function destroy(Request $request, Budget $budget)
    {
        $ownedBudget = $request->user()
            ->budgets()
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->findOrFail($budget->id);

        $ownedBudget->delete();

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }
}