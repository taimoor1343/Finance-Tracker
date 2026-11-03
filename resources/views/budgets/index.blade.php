
@extends('layouts.app')

@section('title', 'Monthly Budgets')
@section('topbar-title', 'Monthly Budgets')

@section('content')
@php
    $totalBudget = $budgets->sum(fn ($budget) => (float) $budget->amount);

    $totalSpent = $budgets->sum(
        fn ($budget) => (float) ($budget->transactions_sum_amount ?? 0)
    );

    $remainingBudget = $totalBudget - $totalSpent;

    $overallProgress = $totalBudget > 0
        ? min(($totalSpent / $totalBudget) * 100, 100)
        : 0;

    $availableCategories = collect($categories)
        ->diff($budgets->pluck('category'))
        ->values();
@endphp

<style>
    .budgets-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        color: var(--ff-text, #172033);
    }

    .budgets-page *,
    .budgets-page *::before,
    .budgets-page *::after {
        box-sizing: border-box;
    }

    .budgets-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 26px;
    }

    .budgets-page h1 {
        margin: 0 0 8px;
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 750;
        letter-spacing: -1px;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .budgets-page h2 {
        margin: 0;
        font-size: 19px;
        font-weight: 750;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .budgets-page h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .budgets-page .muted {
        margin: 0;
        color: var(--ff-muted, #64748b);
    }

    .budgets-page .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        min-height: 40px;
        padding: 10px 15px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 11px;
        background: var(--ff-surface, #ffffff);
        color: var(--ff-text, #172033);
        font: inherit;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: transform .15s ease, opacity .15s ease;
    }

    .budgets-page .btn:hover {
        transform: translateY(-1px);
        opacity: .88;
    }

    .budgets-page .btn-primary {
        background: var(--ff-accent, #16845b);
        border-color: var(--ff-accent, #16845b);
        color: #ffffff;
    }

    .budgets-page .btn-danger {
        color: #dc2626;
        background: transparent;
        border-color: #fecaca;
    }

    .budgets-page .btn-small {
        min-height: 34px;
        padding: 7px 11px;
        font-size: 12px;
        border-radius: 8px;
    }

    .budgets-page .panel {
        min-width: 0;
        margin-bottom: 24px;
        padding: 22px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 18px;
        background: var(--ff-card-bg, var(--ff-surface, #ffffff));
        box-shadow: 0 8px 28px rgba(15, 23, 42, .04);
        backdrop-filter: blur(12px);
    }

    /* Summary cards */

    .budgets-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .budgets-page .summary-card {
        min-width: 0;
        padding: 21px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 16px;
        background: var(--ff-card-bg, var(--ff-surface, #ffffff));
        box-shadow: 0 8px 28px rgba(15, 23, 42, .04);
    }

    .budgets-page .summary-label {
        margin-bottom: 12px;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        font-weight: 650;
    }

    .budgets-page .summary-value {
        overflow-wrap: anywhere;
        font-size: clamp(21px, 2.3vw, 27px);
        font-weight: 800;
        letter-spacing: -.7px;
        font-variant-numeric: tabular-nums;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .budgets-page .summary-note {
        margin-top: 7px;
        color: var(--ff-muted, #64748b);
        font-size: 12px;
    }

    .budgets-page .summary-card.spent .summary-value {
        color: #dc6262;
    }

    .budgets-page .summary-card.remaining.positive .summary-value {
        color: #16a34a;
    }

    .budgets-page .summary-card.remaining.negative .summary-value {
        color: #dc2626;
    }

    /* Overall progress */

    .budgets-page .overview-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 20px;
    }

    .budgets-page .overview-heading p {
        margin: 6px 0 0;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
    }

    .budgets-page .progress-percentage {
        font-size: 20px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        color: var(--ff-heading, var(--ff-text, #172033));
    }

    .budgets-page .progress-track {
        width: 100%;
        height: 11px;
        overflow: hidden;
        border-radius: 20px;
        background: var(--ff-table-head, #edf1f5);
    }

    .budgets-page .progress-fill {
        height: 100%;
        border-radius: inherit;
        background: var(--ff-accent, #16845b);
        transition: width .25s ease;
    }

    .budgets-page .progress-fill.warning {
        background: #e59b28;
    }

    .budgets-page .progress-fill.danger {
        background: #dc6262;
    }

    .budgets-page .overview-footer {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 13px;
        color: var(--ff-muted, #64748b);
        font-size: 12px;
    }

    .budgets-page .overview-footer strong {
        color: var(--ff-text, #172033);
    }

    /* Main content */

    .budgets-page .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(280px, .85fr);
        gap: 24px;
        align-items: start;
    }

    .budgets-page .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .budgets-page .section-count {
        padding: 5px 10px;
        border-radius: 20px;
        background: var(--ff-table-head, #f1f5f9);
        color: var(--ff-muted, #64748b);
        font-size: 12px;
        font-weight: 700;
    }

    /* Category budget cards */

    .budgets-page .budget-list {
        display: flex;
        flex-direction: column;
        gap: 13px;
    }

    .budgets-page .budget-item {
        min-width: 0;
        padding: 17px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 13px;
        background: var(--ff-surface, #ffffff);
    }

    .budgets-page .budget-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }

    .budgets-page .budget-category {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .budgets-page .category-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: color-mix(in srgb, var(--ff-accent, #16845b) 12%, transparent);
        color: var(--ff-accent, #16845b);
        font-size: 17px;
    }

    .budgets-page .budget-amount {
        text-align: right;
        font-size: 13px;
        font-weight: 750;
        font-variant-numeric: tabular-nums;
    }

    .budgets-page .budget-amount span {
        display: block;
        margin-top: 4px;
        color: var(--ff-muted, #64748b);
        font-size: 11px;
        font-weight: 500;
    }

    .budgets-page .budget-progress-info {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 9px;
        color: var(--ff-muted, #64748b);
        font-size: 12px;
    }

    .budgets-page .budget-progress-info strong {
        color: var(--ff-text, #172033);
    }

    .budgets-page .budget-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .budgets-page .budget-edit-form {
        display: none;
        margin-top: 17px;
        padding-top: 17px;
        border-top: 1px solid var(--ff-border, #e2e8f0);
    }

    .budgets-page .budget-edit-form.is-visible {
        display: block;
    }

    /* Forms */

    .budgets-page .form-field {
        min-width: 0;
        margin-bottom: 17px;
    }

    .budgets-page label {
        display: block;
        margin-bottom: 7px;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        font-weight: 650;
    }

    .budgets-page input,
    .budgets-page select {
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

    .budgets-page input:focus,
    .budgets-page select:focus {
        outline: 2px solid color-mix(
            in srgb,
            var(--ff-accent, #16845b) 28%,
            transparent
        );
        border-color: var(--ff-accent, #16845b);
    }

    .budgets-page .form-help {
        margin-top: 7px;
        color: var(--ff-muted, #64748b);
        font-size: 12px;
        line-height: 1.5;
    }

    .budgets-page .form-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
    }

    .budgets-page .create-budget-btn {
        width: 100%;
        margin-top: 3px;
    }

    .budgets-page .empty-state {
        padding: 42px 18px;
        text-align: center;
    }

    .budgets-page .empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin-bottom: 16px;
        border-radius: 18px;
        background: color-mix(in srgb, var(--ff-accent, #16845b) 12%, transparent);
        color: var(--ff-accent, #16845b);
        font-size: 27px;
    }

    .budgets-page .empty-state h3 {
        margin-bottom: 9px;
    }

    .budgets-page .empty-state p {
        margin: 0;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        line-height: 1.6;
    }

    .budgets-page .form-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    @media (max-width: 1050px) {
        .budgets-page .content-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 750px) {
        .budgets-page .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .budgets-page .summary-card:last-child {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 600px) {
        .budgets-page .page-heading {
            align-items: flex-start;
        }

        .budgets-page .panel {
            padding: 15px;
        }

        .budgets-page .summary-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .budgets-page .summary-card:last-child {
            grid-column: auto;
        }

        .budgets-page .summary-card {
            padding: 17px;
        }

        .budgets-page .budget-item {
            padding: 14px;
        }

        .budgets-page .budget-top {
            align-items: flex-start;
        }

        .budgets-page .budget-amount {
            text-align: left;
            width: 100%;
            padding-left: 48px;
        }

        .budgets-page .budget-actions .btn {
            flex: 1;
        }

        .budgets-page .form-actions .btn {
            flex: 1;
        }
    }
</style>

<main class="budgets-page">

    {{-- Page heading --}}
    <header class="page-heading">
        <div>
            <h1>Monthly Budgets</h1>
            <p class="muted">
                Manage your spending limits for {{ $monthName }} {{ $year }}.
            </p>
        </div>
    </header>

    {{-- Summary cards --}}
    <section class="summary-grid">

        <div class="summary-card">
            <div class="summary-label">Total budget</div>

            <div class="summary-value">
                Rs. {{ number_format($totalBudget, 2) }}
            </div>

            <div class="summary-note">
                Across {{ $budgets->count() }} budget categories
            </div>
        </div>

        <div class="summary-card spent">
            <div class="summary-label">Total spent</div>

            <div class="summary-value">
                Rs. {{ number_format($totalSpent, 2) }}
            </div>

            <div class="summary-note">
                Expenses recorded this month
            </div>
        </div>

        <div class="summary-card remaining {{ $remainingBudget >= 0 ? 'positive' : 'negative' }}">
            <div class="summary-label">
                {{ $remainingBudget >= 0 ? 'Remaining budget' : 'Over budget' }}
            </div>

            <div class="summary-value">
                Rs. {{ number_format(abs($remainingBudget), 2) }}
            </div>

            <div class="summary-note">
                {{ $remainingBudget >= 0 ? 'Available to spend' : 'Above your planned budget' }}
            </div>
        </div>

    </section>

    {{-- Overall progress --}}
    <section class="panel">
        <div class="overview-heading">
            <div>
                <h2>Overall spending</h2>
                <p>Your spending compared with your monthly budget.</p>
            </div>

            <div class="progress-percentage">
                {{ number_format($totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0, 1) }}%
            </div>
        </div>

        @php
            $overallBarClass = $overallProgress >= 100
                ? 'danger'
                : ($overallProgress >= 80 ? 'warning' : '');
        @endphp

        <div class="progress-track">
            <div
                class="progress-fill {{ $overallBarClass }}"
                style="width: {{ $overallProgress }}%;"
            ></div>
        </div>

        <div class="overview-footer">
            <span>
                Spent:
                <strong>Rs. {{ number_format($totalSpent, 2) }}</strong>
            </span>

            <span>
                Budget:
                <strong>Rs. {{ number_format($totalBudget, 2) }}</strong>
            </span>
        </div>
    </section>

    {{-- Budgets and create form --}}
    <div class="content-grid">

        {{-- Existing category budgets --}}
        <section class="panel">
            <div class="section-heading">
                <h2>Category budgets</h2>

                <span class="section-count">
                    {{ $budgets->count() }} {{ Str::plural('category', $budgets->count()) }}
                </span>
            </div>

            @if ($budgets->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">₨</div>

                    <h3>No budgets yet</h3>

                    <p>
                        Create your first monthly budget to start tracking
                        your spending by category.
                    </p>
                </div>
            @else
                <div class="budget-list">

                    @foreach ($budgets as $budget)
                        @php
                            $spent = (float) ($budget->transactions_sum_amount ?? 0);
                            $limit = (float) $budget->amount;

                            $percentage = $limit > 0
                                ? ($spent / $limit) * 100
                                : 0;

                            $progressWidth = min($percentage, 100);

                            $progressClass = $percentage >= 100
                                ? 'danger'
                                : ($percentage >= 80 ? 'warning' : '');

                            $remaining = $limit - $spent;
                        @endphp

                        <article class="budget-item">

                            <div class="budget-top">
                                <div class="budget-category">
                                    <div class="category-icon">₨</div>

                                    <div>
                                        <h3>{{ $budget->category }}</h3>

                                        <p class="muted" style="font-size: 11px; margin-top: 4px;">
                                            Monthly spending limit
                                        </p>
                                    </div>
                                </div>

                                <div class="budget-amount">
                                    Rs. {{ number_format($spent, 2) }}
                                    <span>
                                        of Rs. {{ number_format($limit, 2) }}
                                    </span>
                                </div>
                            </div>

                            <div class="budget-progress-info">
                                <span>
                                    {{ number_format($percentage, 1) }}% used
                                </span>

                                <strong>
                                    @if ($remaining >= 0)
                                        Rs. {{ number_format($remaining, 2) }} left
                                    @else
                                        Rs. {{ number_format(abs($remaining), 2) }} over
                                    @endif
                                </strong>
                            </div>

                            <div class="progress-track">
                                <div
                                    class="progress-fill {{ $progressClass }}"
                                    style="width: {{ $progressWidth }}%;"
                                ></div>
                            </div>

                            <div class="budget-actions">
                                <button
                                    type="button"
                                    class="btn btn-small"
                                    onclick="toggleBudgetEdit({{ $budget->id }})"
                                >
                                    Edit
                                </button>

                                <form
                                    method="POST"
                                    action="{{ route('budgets.destroy', $budget) }}"
                                    onsubmit="return window.confirm('Delete this budget?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-small btn-danger"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>

                            {{-- Inline edit form --}}
                            <div
                                class="budget-edit-form"
                                id="budget-edit-{{ $budget->id }}"
                            >
                                <form
                                    method="POST"
                                    action="{{ route('budgets.update', $budget) }}"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="form-field">
                                        <label for="edit-category-{{ $budget->id }}">
                                            Category
                                        </label>

                                        <select
                                            id="edit-category-{{ $budget->id }}"
                                            name="category"
                                            required
                                        >
                                            @foreach ($categories as $category)
                                                <option
                                                    value="{{ $category }}"
                                                    {{ old('category') === $category && (string) old('_editing_budget') === (string) $budget->id
                                                        ? 'selected'
                                                        : ($budget->category === $category ? 'selected' : '') }}
                                                >
                                                    {{ $category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label for="edit-amount-{{ $budget->id }}">
                                            Monthly limit (Rs.)
                                        </label>

                                        <input
                                            type="number"
                                            id="edit-amount-{{ $budget->id }}"
                                            name="amount"
                                            min="0.01"
                                            max="9999999999.99"
                                            step="0.01"
                                            value="{{ old('_editing_budget') == $budget->id ? old('amount') : $budget->amount }}"
                                            required
                                        >
                                    </div>

                                    <input
                                        type="hidden"
                                        name="_editing_budget"
                                        value="{{ $budget->id }}"
                                    >

                                    <div class="form-actions">
                                        <button
                                            type="button"
                                            class="btn btn-small"
                                            onclick="toggleBudgetEdit({{ $budget->id }})"
                                        >
                                            Cancel
                                        </button>

                                        <button
                                            type="submit"
                                            class="btn btn-small btn-primary"
                                        >
                                            Save changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </article>
                    @endforeach

                </div>
            @endif
        </section>

        {{-- Create budget form --}}
        <section class="panel">
            <div class="section-heading">
                <h2>Create a budget</h2>
            </div>

            <p class="muted" style="font-size: 13px; line-height: 1.6; margin: -8px 0 22px;">
                Set a spending limit for a category. Your budget applies
                to {{ $monthName }} {{ $year }}.
            </p>

            @if ($availableCategories->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">✓</div>

                    <h3>All categories covered</h3>

                    <p>
                        You already have a budget for every available category.
                        You can edit an existing budget instead.
                    </p>
                </div>
            @else
                <form method="POST" action="{{ route('budgets.store') }}">
                    @csrf

                    <div class="form-field">
                        <label for="category">Budget category</label>

                        <select id="category" name="category" required>
                            <option value="">Select a category</option>

                            @foreach ($availableCategories as $category)
                                <option
                                    value="{{ $category }}"
                                    {{ old('category') === $category ? 'selected' : '' }}
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
                        <label for="amount">Monthly budget (Rs.)</label>

                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            placeholder="e.g. 15000"
                            min="0.01"
                            max="9999999999.99"
                            step="0.01"
                            value="{{ old('amount') }}"
                            required
                        >

                        @error('amount')
                            <div class="form-error">{{ $message }}</div>
                        @enderror

                        <p class="form-help">
                            Enter the maximum amount you want to spend
                            in this category during the month.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary create-budget-btn"
                    >
                        + Create budget
                    </button>
                </form>
            @endif
        </section>

    </div>
</main>

<script>
    function toggleBudgetEdit(budgetId) {
        const form = document.getElementById('budget-edit-' + budgetId);

        if (!form) {
            return;
        }

        form.classList.toggle('is-visible');
    }

    // Reopen the relevant edit form if validation fails.
    @if ($errors->any() && old('_editing_budget'))
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById(
                'budget-edit-' + @json(old('_editing_budget'))
            );

            if (form) {
                form.classList.add('is-visible');
            }
        });
    @endif
</script>
@endsection
