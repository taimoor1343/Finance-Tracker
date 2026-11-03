<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()
            ->transactions()
            ->latest('transaction_date')
            ->latest('id');

        if ($request->filled('from')) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $request->input('from')
            );
        }

        if ($request->filled('to')) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $request->input('to')
            );
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($subquery) use ($search) {
                $subquery->where('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (
            $request->filled('type') &&
            in_array($request->input('type'), ['income', 'expense'], true)
        ) {
            $query->where('type', $request->input('type'));
        }

        $transactions = $query
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $categories = [
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

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
                Rule::in(
                    $categories[$request->input('type')] ?? []
                ),
            ],

            'transaction_date' => ['required', 'date'],

            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->transactions()->create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Transaction added successfully.');
    }

    public function edit(Transaction $transaction)
    {
        abort_unless(
            $transaction->user_id === request()->user()->id,
            403
        );

        return view('transactions.edit', [
            'transaction' => $transaction,
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        abort_unless(
            $transaction->user_id === $request->user()->id,
            403
        );

        $categories = [
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

        $allowedCategories = $categories[$request->input('type')] ?? [];

        // Preserve an existing legacy category if it has not changed
        // and the transaction type remains the same.
        if (
            $request->input('type') === $transaction->type &&
            $request->input('category') === $transaction->category &&
            ! in_array($transaction->category, $allowedCategories, true)
        ) {
            $allowedCategories[] = $transaction->category;
        }

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
                Rule::in($allowedCategories),
            ],

            'transaction_date' => ['required', 'date'],

            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $transaction->update($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        abort_unless(
            $transaction->user_id === request()->user()->id,
            403
        );

        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = $request->user()->transactions();

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($subquery) use ($search) {
                $subquery->where('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (
            $request->filled('type') &&
            in_array($request->input('type'), ['income', 'expense'], true)
        ) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('from')) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $request->input('from')
            );
        }

        if ($request->filled('to')) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $request->input('to')
            );
        }

        $transactions = $query
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();

        $filename = 'financeflow-transactions-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // Make the CSV display correctly in Microsoft Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Date',
                'Type',
                'Category',
                'Amount',
                'Description',
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->transaction_date->format('Y-m-d'),
                    ucfirst($transaction->type),
                    $transaction->category,
                    $transaction->amount,
                    $transaction->description,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
