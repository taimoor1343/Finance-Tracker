@extends('layouts.app')

@section('title', 'Recurring Transactions')
@section('topbar-title', 'Recurring Transactions')

@section('content')
@php
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

    $activeCount = $recurringTransactions->where('is_active', true)->count();
    $inactiveCount = $recurringTransactions->where('is_active', false)->count();
@endphp

<style>
    .recurring-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        color: var(--ff-text, #172033);
    }

    .recurring-page *,
    .recurring-page *::before,
    .recurring-page *::after {
        box-sizing: border-box;
    }

    .recurring-page h1,
    .recurring-page h2,
    .recurring-page h3,
    .recurring-page p {
        margin-top: 0;
    }

    .recurring-page h1 {
        margin-bottom: 8px;
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 750;
        letter-spacing: -1px;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .recurring-page h2 {
        margin-bottom: 0;
        font-size: 19px;
        font-weight: 750;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .recurring-page h3 {
        margin-bottom: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .recurring-page .muted {
        color: var(--ff-muted, #64748b);
    }

    .recurring-page .page-heading {
        margin-bottom: 26px;
    }

    .recurring-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .recurring-page .summary-card,
    .recurring-page .panel {
        min-width: 0;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 16px;
        background: var(--ff-card-bg, var(--ff-surface, #ffffff));
        box-shadow: 0 8px 28px rgba(15, 23, 42, .04);
    }

    .recurring-page .summary-card {
        padding: 21px;
    }

    .recurring-page .summary-label {
        margin-bottom: 12px;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        font-weight: 650;
    }

    .recurring-page .summary-value {
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -.7px;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .recurring-page .summary-note {
        margin: 7px 0 0;
        color: var(--ff-muted, #64748b);
        font-size: 12px;
    }

    .recurring-page .summary-card.active .summary-value {
        color: #16a34a;
    }

    .recurring-page .summary-card.inactive .summary-value {
        color: #d97706;
    }

    .recurring-page .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(280px, .85fr);
        gap: 24px;
        align-items: start;
    }

    .recurring-page .panel {
        padding: 22px;
        margin-bottom: 24px;
    }

    .recurring-page .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .recurring-page .section-count {
        padding: 5px 10px;
        border-radius: 20px;
        background: var(--ff-table-head, #f1f5f9);
        color: var(--ff-muted, #64748b);
        font-size: 12px;
        font-weight: 700;
    }

    .recurring-page .recurring-list {
        display: flex;
        flex-direction: column;
        gap: 13px;
    }

    .recurring-page .recurring-item {
        min-width: 0;
        padding: 17px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 13px;
        background: var(--ff-surface, #ffffff);
    }

    .recurring-page .item-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
    }

    .recurring-page .item-info {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .recurring-page .item-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: color-mix(in srgb, var(--ff-accent, #16845b) 12%, transparent);
        color: var(--ff-accent, #16845b);
        font-size: 19px;
    }

    .recurring-page .item-description {
        margin: 5px 0 0;
        color: var(--ff-muted, #64748b);
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .recurring-page .item-amount {
        font-size: 16px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    .recurring-page .item-amount.income {
        color: #16a34a;
    }

    .recurring-page .item-amount.expense {
        color: #dc6262;
    }

    .recurring-page .item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 16px;
        margin-top: 17px;
        padding-top: 14px;
        border-top: 1px solid var(--ff-border, #e2e8f0);
        color: var(--ff-muted, #64748b);
        font-size: 12px;
    }

    .recurring-page .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .recurring-page .status.active {
        background: #dcfce7;
        color: #166534;
    }

    .recurring-page .status.inactive {
        background: #fef3c7;
        color: #92400e;
    }

    .recurring-page .item-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
    }

    .recurring-page .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        min-height: 39px;
        padding: 9px 13px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 10px;
        background: var(--ff-surface, #ffffff);
        color: var(--ff-text, #172033);
        font: inherit;
        font-size: 12px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: opacity .15s ease, transform .15s ease;
    }

    .recurring-page .btn:hover {
        opacity: .85;
        transform: translateY(-1px);
    }

    .recurring-page .btn-primary {
        background: var(--ff-accent, #16845b);
        border-color: var(--ff-accent, #16845b);
        color: #fff;
    }

    .recurring-page .btn-danger {
        color: #dc2626;
        border-color: #fecaca;
        background: transparent;
    }

    .recurring-page .btn-warning {
        color: #b45309;
        border-color: #fcd34d;
        background: #fffbeb;
    }

    .recurring-page .btn-success {
        color: #15803d;
        border-color: #bbf7d0;
        background: #f0fdf4;
    }

    .recurring-page .edit-form {
        display: none;
        margin-top: 17px;
        padding-top: 17px;
        border-top: 1px solid var(--ff-border, #e2e8f0);
    }

    .recurring-page .edit-form.is-visible {
        display: block;
    }

    .recurring-page .form-field {
        margin-bottom: 16px;
    }

    .recurring-page label {
        display: block;
        margin-bottom: 7px;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        font-weight: 650;
    }

    .recurring-page input,
    .recurring-page select {
        display: block;
        width: 100%;
        min-width: 0;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid var(--ff-border, #dbe2ea);
        border-radius: 10px;
        background: var(--ff-input-bg, #ffffff);
        color: var(--ff-text, #172033);
        font: inherit;
        font-size: 14px;
    }

    .recurring-page input:focus,
    .recurring-page select:focus {
        outline: 2px solid color-mix(in srgb, var(--ff-accent, #16845b) 28%, transparent);
        border-color: var(--ff-accent, #16845b);
    }

    .recurring-page .form-help {
        margin: 7px 0 0;
        color: var(--ff-muted, #64748b);
        font-size: 12px;
        line-height: 1.5;
    }

    .recurring-page .form-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
    }

    .recurring-page .form-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    .recurring-page .empty-state {
        padding: 42px 18px;
        text-align: center;
    }

    .recurring-page .empty-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 58px;
        height: 58px;
        margin-bottom: 16px;
        border-radius: 18px;
        background: color-mix(in srgb, var(--ff-accent, #16845b) 12%, transparent);
        color: var(--ff-accent, #16845b);
        font-size: 26px;
    }

    .recurring-page .empty-state h3 {
        margin-bottom: 9px;
    }

    .recurring-page .empty-state p {
        margin-bottom: 0;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        line-height: 1.6;
    }

    @media (max-width: 1050px) {
        .recurring-page .content-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 750px) {
        .recurring-page .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .recurring-page .summary-card:last-child {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 600px) {
        .recurring-page .panel {
            padding: 15px;
        }

        .recurring-page .summary-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .recurring-page .summary-card:last-child {
            grid-column: auto;
        }

        .recurring-page .summary-card {
            padding: 17px;
        }

        .recurring-page .recurring-item {
            padding: 14px;
        }

        .recurring-page .item-amount {
            width: 100%;
            padding-left: 51px;
        }

        .recurring-page .item-actions .btn {
            flex: 1;
        }

        .recurring-page .form-actions .btn {
            flex: 1;
        }
    }
</style>

<main class="recurring-page">

    <header class="page-heading">
        <h1>Recurring Transactions</h1>
        <p class="muted">
            Manage automatic income and expenses such as salary, rent, and subscriptions.
        </p>
    </header>

    {{-- Summary --}}
    <section class="summary-grid">

        <div class="summary-card">
            <div class="summary-label">Total recurring rules</div>
            <div class="summary-value">{{ $recurringTransactions->total() }}</div>
            <p class="summary-note">All your recurring transactions</p>
        </div>

        <div class="summary-card active">
            <div class="summary-label">Active rules</div>
            <div class="summary-value">{{ $activeCount }}</div>
            <p class="summary-note">Currently enabled</p>
        </div>

        <div class="summary-card inactive">
            <div class="summary-label">Inactive rules</div>
            <div class="summary-value">{{ $inactiveCount }}</div>
            <p class="summary-note">Currently disabled</p>
        </div>

    </section>

    <div class="content-grid">

        {{-- Recurring transactions list --}}
        <section class="panel">

            <div class="section-heading">
                <h2>Your recurring transactions</h2>

                <span class="section-count">
                    {{ $recurringTransactions->total() }} total
                </span>
            </div>

            @if ($recurringTransactions->isEmpty())

                <div class="empty-state">
                    <div class="empty-icon">↻</div>
                    <h3>No recurring transactions yet</h3>
                    <p>
                        Add your first recurring transaction to keep track
                        of regular income and expenses.
                    </p>
                </div>

            @else

                <div class="recurring-list">

                    @foreach ($recurringTransactions as $recurring)
                        <article class="recurring-item">

                            <div class="item-top">

                                <div class="item-info">
                                    <div class="item-icon">
                                        {{ $recurring->type === 'income' ? '↗' : '↘' }}
                                    </div>

                                    <div>
                                        <h3>{{ $recurring->category }}</h3>

                                        <p class="item-description">
                                            {{ $recurring->description ?: 'No description' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="item-amount {{ $recurring->type }}">
                                    {{ $recurring->type === 'income' ? '+' : '-' }}
                                    Rs. {{ number_format((float) $recurring->amount, 2) }}
                                </div>

                            </div>

                            <div class="item-meta">
                                <span>
                                    Frequency:
                                    <strong>{{ ucfirst($recurring->frequency) }}</strong>
                                </span>

                                <span>
                                    Next due:
                                    <strong>{{ $recurring->next_due_date->format('d M Y') }}</strong>
                                </span>

                                <span>
                                    Start:
                                    <strong>{{ $recurring->start_date->format('d M Y') }}</strong>
                                </span>

                                <span>
                                    @if ($recurring->end_date)
                                        Ends:
                                        <strong>{{ $recurring->end_date->format('d M Y') }}</strong>
                                    @else
                                        <strong>No end date</strong>
                                    @endif
                                </span>

                                <span class="status {{ $recurring->is_active ? 'active' : 'inactive' }}">
                                    {{ $recurring->is_active ? 'Active' : 'Paused' }}
                                </span>
                            </div>

                            <div class="item-actions">

                                {{-- Edit --}}
                                <button
                                    type="button"
                                    class="btn"
                                    onclick="toggleRecurringEdit({{ $recurring->id }})"
                                >
                                    Edit
                                </button>

                                {{-- Pause / Resume --}}
                                <form
                                    method="POST"
                                    action="{{ route('recurring-transactions.toggle', $recurring) }}"
                                    onsubmit="return confirm('{{ $recurring->is_active ? 'Pause' : 'Resume' }} this recurring transaction?');"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn {{ $recurring->is_active ? 'btn-warning' : 'btn-success' }}"
                                    >
                                        {{ $recurring->is_active ? '⏸ Pause' : '▶ Resume' }}
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('recurring-transactions.destroy', $recurring) }}"
                                    onsubmit="return confirm('Delete this recurring transaction?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>

                            </div>

                            {{-- Edit form --}}
                            <div
                                class="edit-form"
                                id="recurring-edit-{{ $recurring->id }}"
                            >
                                <form
                                    method="POST"
                                    action="{{ route('recurring-transactions.update', $recurring) }}"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="form-field">
                                        <label for="edit-type-{{ $recurring->id }}">Type</label>

                                        <select
                                            id="edit-type-{{ $recurring->id }}"
                                            name="type"
                                            required
                                        >
                                            <option value="expense" @selected($recurring->type === 'expense')>
                                                Expense
                                            </option>
                                            <option value="income" @selected($recurring->type === 'income')>
                                                Income
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label for="edit-amount-{{ $recurring->id }}">Amount (Rs.)</label>

                                        <input
                                            type="number"
                                            id="edit-amount-{{ $recurring->id }}"
                                            name="amount"
                                            min="0.01"
                                            step="0.01"
                                            value="{{ $recurring->amount }}"
                                            required
                                        >
                                    </div>

                                    <div class="form-field">
                                        <label for="edit-category-{{ $recurring->id }}">Category</label>

                                        <select
                                            id="edit-category-{{ $recurring->id }}"
                                            name="category"
                                            required
                                        >
                                            @foreach ($categories as $type => $options)
                                                <optgroup label="{{ ucfirst($type) }}">
                                                    @foreach ($options as $category)
                                                        <option
                                                            value="{{ $category }}"
                                                            @selected($recurring->category === $category)
                                                        >
                                                            {{ $category }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label for="edit-description-{{ $recurring->id }}">Description</label>

                                        <input
                                            type="text"
                                            id="edit-description-{{ $recurring->id }}"
                                            name="description"
                                            maxlength="255"
                                            value="{{ $recurring->description }}"
                                        >
                                    </div>

                                    <div class="form-field">
                                        <label for="edit-frequency-{{ $recurring->id }}">Frequency</label>

                                        <select
                                            id="edit-frequency-{{ $recurring->id }}"
                                            name="frequency"
                                            required
                                        >
                                            <option value="weekly" @selected($recurring->frequency === 'weekly')>Weekly</option>
                                            <option value="monthly" @selected($recurring->frequency === 'monthly')>Monthly</option>
                                            <option value="yearly" @selected($recurring->frequency === 'yearly')>Yearly</option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label for="edit-start-{{ $recurring->id }}">Start date</label>

                                        <input
                                            type="date"
                                            id="edit-start-{{ $recurring->id }}"
                                            name="start_date"
                                            value="{{ $recurring->start_date->format('Y-m-d') }}"
                                            required
                                        >
                                    </div>

                                    <div class="form-field">
                                        <label for="edit-end-{{ $recurring->id }}">End date (optional)</label>

                                        <input
                                            type="date"
                                            id="edit-end-{{ $recurring->id }}"
                                            name="end_date"
                                            value="{{ $recurring->end_date?->format('Y-m-d') }}"
                                        >
                                    </div>

                                    <div class="form-actions">
                                        <button
                                            type="button"
                                            class="btn"
                                            onclick="toggleRecurringEdit({{ $recurring->id }})"
                                        >
                                            Cancel
                                        </button>

                                        <button type="submit" class="btn btn-primary">
                                            Save changes
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </article>
                    @endforeach

                </div>

                <div style="margin-top: 20px;">
                    {{ $recurringTransactions->links() }}
                </div>

            @endif

        </section>

        {{-- Create recurring transaction --}}
        <section class="panel">

            <div class="section-heading">
                <h2>Add recurring transaction</h2>
            </div>

            <p class="muted" style="font-size: 13px; line-height: 1.6; margin-bottom: 22px;">
                Set up a regular income or expense that should be recorded automatically.
            </p>

            <form
                method="POST"
                action="{{ route('recurring-transactions.store') }}"
            >
                @csrf

                <div class="form-field">
                    <label for="type">Transaction type</label>

                    <select id="type" name="type" required>
                        <option value="expense" @selected(old('type', 'expense') === 'expense')>
                            Expense
                        </option>
                        <option value="income" @selected(old('type') === 'income')>
                            Income
                        </option>
                    </select>

                    @error('type')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="amount">Amount (Rs.)</label>

                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        placeholder="e.g. 15000"
                        min="0.01"
                        step="0.01"
                        value="{{ old('amount') }}"
                        required
                    >

                    @error('amount')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="category">Category</label>

                    <select id="category" name="category" required>
                        <option value="">Select a category</option>

                        @foreach ($categories['expense'] as $category)
                            <option
                                value="{{ $category }}"
                                data-type="expense"
                                @selected(old('category') === $category)
                            >
                                {{ $category }}
                            </option>
                        @endforeach

                        @foreach ($categories['income'] as $category)
                            <option
                                value="{{ $category }}"
                                data-type="income"
                                @selected(old('category') === $category)
                            >
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>

                    @error('category')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="description">Description (optional)</label>

                    <input
                        type="text"
                        id="description"
                        name="description"
                        placeholder="e.g. Monthly Netflix subscription"
                        maxlength="255"
                        value="{{ old('description') }}"
                    >

                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="frequency">Frequency</label>

                    <select id="frequency" name="frequency" required>
                        <option value="weekly" @selected(old('frequency') === 'weekly')>Weekly</option>
                        <option value="monthly" @selected(old('frequency', 'monthly') === 'monthly')>Monthly</option>
                        <option value="yearly" @selected(old('frequency') === 'yearly')>Yearly</option>
                    </select>

                    @error('frequency')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="start_date">Start date</label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ old('start_date', now()->format('Y-m-d')) }}"
                        required
                    >

                    @error('start_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror

                    <p class="form-help">
                        The first date this recurring transaction should be recorded.
                    </p>
                </div>

                <div class="form-field">
                    <label for="end_date">End date (optional)</label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                    >

                    @error('end_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    + Create recurring transaction
                </button>

            </form>

        </section>

    </div>
</main>

<script>
    function toggleRecurringEdit(id) {
        const form = document.getElementById('recurring-edit-' + id);

        if (form) {
            form.classList.toggle('is-visible');
        }
    }

    // Only show categories matching the selected transaction type.
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category');

        if (!typeSelect || !categorySelect) {
            return;
        }

        function updateCategories() {
            const selectedType = typeSelect.value;
            const options = categorySelect.querySelectorAll('option[data-type]');

            options.forEach(function (option) {
                option.hidden = option.dataset.type !== selectedType;
            });

            const selectedOption = categorySelect.selectedOptions[0];

            if (
                selectedOption &&
                selectedOption.dataset.type &&
                selectedOption.dataset.type !== selectedType
            ) {
                categorySelect.value = '';
            }
        }

        typeSelect.addEventListener('change', updateCategories);
        updateCategories();
    });
</script>
@endsection
