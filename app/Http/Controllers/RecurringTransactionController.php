<?php

namespace App\Http\Controllers;

use App\Models\RecurringTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class RecurringTransactionController extends Controller
{
    private function categories(): array
    {
        return [
            'expense' => [
                'Food & Dining',
                'Transport',
                'Shopping',
                'Bills & Utilities',
                'Housing',
                'Healthcare',
                'Education',
                'Entertainment',
                'Other',
            ],
            'income' => [
                'Salary',
                'Freelance',
                'Business',
                'Investment',
                'Other',
            ],
        ];
    }

    public function index(Request $request)
    {
        $recurringTransactions = $request->user()
            ->recurringTransactions()
            ->latest()
            ->paginate(10);

        return view('recurring-transactions.index', [
            'recurringTransactions' => $recurringTransactions,
        ]);
    }

    public function store(Request $request)
    {
        $categories = $this->categories();

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'category' => [
                'required',
                'string',
                'max:100',
                Rule::in($categories[$request->input('type')] ?? []),
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'frequency' => [
                'required',
                Rule::in(['weekly', 'monthly', 'yearly']),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ]);

        $validated['next_due_date'] = $validated['start_date'];
        $validated['is_active'] = true;

        $request->user()
            ->recurringTransactions()
            ->create($validated);

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Recurring transaction created successfully.');
    }

    public function update(
        Request $request,
        RecurringTransaction $recurringTransaction
    ) {
        abort_unless(
            $recurringTransaction->user_id === $request->user()->id,
            403
        );

        $categories = $this->categories();
        $allowedCategories = $categories[$request->input('type')] ?? [];

        // Preserve an existing category if it has not changed.
        if (
            $request->input('type') === $recurringTransaction->type &&
            $request->input('category') === $recurringTransaction->category &&
            ! in_array(
                $recurringTransaction->category,
                $allowedCategories,
                true
            )
        ) {
            $allowedCategories[] = $recurringTransaction->category;
        }

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'category' => [
                'required',
                'string',
                'max:100',
                Rule::in($allowedCategories),
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'frequency' => [
                'required',
                Rule::in(['weekly', 'monthly', 'yearly']),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ]);

        $recurringTransaction->update($validated);

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Recurring transaction updated successfully.');
    }

    public function toggle(Request $request, RecurringTransaction $recurringTransaction)
{
    abort_unless(
        $recurringTransaction->user_id === auth()->id(),
        403
    );

    // If resuming, skip all dates that were missed while paused.
    if (! $recurringTransaction->is_active) {
        $nextDueDate = Carbon::parse($recurringTransaction->next_due_date);
        $startDate = Carbon::parse($recurringTransaction->start_date);
        $today = Carbon::today();

        while ($nextDueDate->lte($today)) {
            $nextDueDate = $this->getNextDueDate(
                $nextDueDate,
                $startDate,
                $recurringTransaction->frequency
            );
        }

        $recurringTransaction->next_due_date = $nextDueDate->toDateString();
        $recurringTransaction->is_active = true;
    } else {
        $recurringTransaction->is_active = false;
    }

    $recurringTransaction->save();

    return redirect()
        ->route('recurring-transactions.index')
        ->with(
            'success',
            $recurringTransaction->is_active
                ? 'Recurring transaction resumed.'
                : 'Recurring transaction paused.'
        );
}
    public function destroy(
        Request $request,
        RecurringTransaction $recurringTransaction
    ) {
        abort_unless(
            $recurringTransaction->user_id === $request->user()->id,
            403
        );

        $recurringTransaction->delete();

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Recurring transaction deleted successfully.');
    }
    private function getNextDueDate(
    Carbon $currentDate,
    Carbon $startDate,
    string $frequency
): Carbon {
    $nextDate = $currentDate->copy();

    if ($frequency === 'weekly') {
        return $nextDate->addWeek();
    }

    if ($frequency === 'monthly') {
        $nextDate->addMonthNoOverflow();

        $nextDate->day = min($startDate->day, $nextDate->daysInMonth);

        return $nextDate;
    }

    if ($frequency === 'yearly') {
        $nextDate->addYearNoOverflow();

        $nextDate->month = $startDate->month;
        $nextDate->day = min($startDate->day, $nextDate->daysInMonth);

        return $nextDate;
    }

    throw new \InvalidArgumentException(
        "Unsupported recurring frequency: {$frequency}"
    );
}
}
