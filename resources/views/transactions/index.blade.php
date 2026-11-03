@extends('layouts.app')

@section('title', 'Transactions')
@section('topbar-title', 'Transactions')

@section('content')
<style>
    .transactions-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        color: var(--ff-text, #172033);
    }

    .transactions-page * {
        box-sizing: border-box;
    }

    .transactions-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 26px;
    }

    .transactions-page h1 {
        margin: 0 0 8px;
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 750;
        letter-spacing: -1px;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .transactions-page .muted {
        margin: 0;
        color: var(--ff-muted, #64748b);
    }

    .transactions-page .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 11px;
        background: var(--ff-surface, #ffffff);
        color: var(--ff-text, #172033);
        font: inherit;
        font-size: 14px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: transform .15s ease, opacity .15s ease;
    }

    .transactions-page .btn:hover {
        transform: translateY(-1px);
        opacity: .88;
    }

    .transactions-page .btn-primary {
        background: var(--ff-accent, #16845b);
        border-color: var(--ff-accent, #16845b);
        color: #ffffff;
    }

    .transactions-page .btn-danger {
        color: #dc2626;
        background: transparent;
        border-color: #fecaca;
    }

    .transactions-page .btn-small {
        padding: 7px 10px;
        font-size: 12px;
        border-radius: 8px;
    }

    .transactions-page .panel {
        margin-bottom: 24px;
        padding: 22px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 18px;
        background: var(--ff-card-bg, var(--ff-surface, #ffffff));
        box-shadow: 0 8px 28px rgba(15, 23, 42, .04);
        backdrop-filter: blur(12px);
    }

    .transactions-page .filters {
        display: grid;
        grid-template-columns: repeat(4, minmax(140px, 1fr));
        gap: 16px;
        align-items: end;
    }

    .transactions-page .field {
        min-width: 0;
    }

    .transactions-page label {
        display: block;
        margin-bottom: 7px;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        font-weight: 650;
    }

    .transactions-page input,
    .transactions-page select {
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid var(--ff-border, #dbe2ea);
        border-radius: 10px;
        background: var(--ff-input-bg, #ffffff);
        color: var(--ff-text, #172033);
        font: inherit;
        font-size: 14px;
    }

    .transactions-page input::placeholder {
        color: var(--ff-muted, #64748b);
        opacity: .8;
    }

    .transactions-page input:focus,
    .transactions-page select:focus {
        outline: 2px solid color-mix(
            in srgb,
            var(--ff-accent, #16845b) 28%,
            transparent
        );
        border-color: var(--ff-accent, #16845b);
    }

    .transactions-page .filter-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .transactions-page .table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .transactions-page h2 {
        margin: 0;
        font-size: 19px;
        font-weight: 750;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .transactions-page .table-scroll {
        width: 100%;
        overflow-x: auto;
    }

    .transactions-page table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    .transactions-page th {
        padding: 13px 14px;
        background: var(--ff-table-head, #f7f9fc);
        color: var(--ff-muted, #64748b);
        text-align: left;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .6px;
        border-bottom: 1px solid var(--ff-border, #e8edf3);
    }

    .transactions-page td {
        padding: 15px 14px;
        border-bottom: 1px solid var(--ff-border, #e8edf3);
        font-size: 13px;
        vertical-align: middle;
        color: var(--ff-text, #172033);
    }

    .transactions-page tbody tr:last-child td {
        border-bottom: 0;
    }

    .transactions-page tbody tr:hover {
        background: var(--ff-row-hover, #f8fafc);
    }

    .transactions-page .transaction-type {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 750;
        text-transform: capitalize;
    }

    .transactions-page .transaction-type.income {
        color: #15803d;
        background: #dcfce7;
    }

    .transactions-page .transaction-type.expense {
        color: #b91c1c;
        background: #fee2e2;
    }

    .transactions-page .amount {
        font-weight: 750;
        font-variant-numeric: tabular-nums;
    }

    .transactions-page .amount.income {
        color: #16a34a;
    }

    .transactions-page .amount.expense {
        color: #dc6262;
    }

    .transactions-page .description {
        max-width: 240px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--ff-muted, #64748b);
    }

    .transactions-page .row-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .transactions-page .row-actions form {
        margin: 0;
    }

    .transactions-page .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .transactions-page .empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin-bottom: 16px;
        border-radius: 18px;
        background: color-mix(
            in srgb,
            var(--ff-accent, #16845b) 12%,
            transparent
        );
        color: var(--ff-accent, #16845b);
        font-size: 27px;
    }

    .transactions-page .empty-state h2 {
        margin-bottom: 9px;
    }

    .transactions-page .empty-state p {
        margin: 0 0 20px;
        color: var(--ff-muted, #64748b);
    }

    .transactions-page .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
        color: var(--ff-text, #172033);
    }

    .transactions-page .pagination nav {
        width: 100%;
    }

    .transactions-page .pagination nav p {
        color: var(--ff-muted, #64748b);
        font-size: 13px;
    }

    .transactions-page .pagination nav a,
    .transactions-page .pagination nav span {
        border-radius: 8px;
    }

    .transactions-page .pagination nav a {
        color: var(--ff-text, #172033);
        border-color: var(--ff-border, #e8edf3);
    }

    .transactions-page .pagination nav [aria-current="page"] span {
        background: var(--ff-accent, #16845b);
        color: #ffffff;
        border-color: var(--ff-accent, #16845b);
    }

    @media (max-width: 950px) {
        .transactions-page .filters {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .transactions-page .panel {
            padding: 15px;
        }

        .transactions-page .filters {
            grid-template-columns: 1fr;
        }

        .transactions-page .page-heading {
            align-items: flex-start;
        }

        .transactions-page .page-heading .btn {
            width: 100%;
        }

        .transactions-page .filter-actions .btn {
            flex: 1;
        }

        .transactions-page .row-actions {
            flex-wrap: wrap;
        }
    }
</style>

<main class="transactions-page">
    <header class="page-heading">
        <div>
            <h1>All Transactions</h1>
            <p class="muted">Review and manage your income and expenses.</p>
        </div>

        <a class="btn btn-primary" href="{{ route('transactions.create') }}">
            + Add Transaction
        </a>
    </header>

    <section class="panel">
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="filters">
                <div class="field">
                    <label for="search">Search transactions</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Category or description..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="field">
                    <label for="from">From date</label>
                    <input
                        type="date"
                        id="from"
                        name="from"
                        value="{{ request('from') }}"
                    >
                </div>

                <div class="field">
                    <label for="to">To date</label>
                    <input
                        type="date"
                        id="to"
                        name="to"
                        value="{{ request('to') }}"
                    >
                </div>

                <div class="field">
                    <label for="type">Transaction type</label>
                    <select name="type" id="type">
                        <option value="">All transactions</option>
                        <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>
                            Income
                        </option>
                        <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>
                            Expense
                        </option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button class="btn btn-primary" type="submit">Apply filters</button>

                <a class="btn" href="{{ route('transactions.index') }}">
                    Clear filters
                </a>

                <a
                    class="btn"
                    href="{{ route('transactions.export', request()->only(['search', 'type', 'from', 'to'])) }}"
                >
                    ↓ Export CSV
                </a>
            </div>
        </form>
    </section>

    <section class="panel">
        @if ($transactions->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">↗</div>

                <h2>No transactions found</h2>

                <p>
                    @if (request()->hasAny(['search', 'type', 'from', 'to']))
                        Try changing your filters, or add a new transaction.
                    @else
                        Your transactions will appear here once you add one.
                    @endif
                </p>

                <a class="btn btn-primary" href="{{ route('transactions.create') }}">
                    + Add your first transaction
                </a>
            </div>
        @else
            <div class="table-heading">
                <h2>Transaction history</h2>
                <span class="muted">{{ $transactions->total() }} transaction(s)</span>
            </div>

            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>
                                    {{ $transaction->transaction_date->format('d M Y') }}
                                </td>

                                <td>
                                    <strong>{{ $transaction->category }}</strong>
                                </td>

                                <td>
                                    <span class="transaction-type {{ $transaction->type }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>

                                <td class="amount {{ $transaction->type }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}Rs.
                                    {{ number_format((float) $transaction->amount, 2) }}
                                </td>

                                <td class="description" title="{{ $transaction->description }}">
                                    {{ $transaction->description ?: '—' }}
                                </td>

                                <td>
                                    <div class="row-actions">
                                        <a
                                            class="btn btn-small"
                                            href="{{ route('transactions.edit', $transaction) }}"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('transactions.destroy', $transaction) }}"
                                            onsubmit="return window.confirm('Delete this transaction permanently?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-small btn-danger" type="submit">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $transactions->links() }}
            </div>
        @endif
    </section>
</main>
@endsection
