@extends('layouts.app')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@section('content')
@php
    $metrics = [
        [
            'label' => 'Total income',
            'amount' => $totalIncome,
            'icon' => '↙',
            'tone' => 'income',
            'description' => 'All recorded income',
        ],
        [
            'label' => 'Total expenses',
            'amount' => $totalExpenses,
            'icon' => '↗',
            'tone' => 'expense',
            'description' => 'All recorded expenses',
        ],
        [
            'label' => 'Net balance',
            'amount' => $netBalance,
            'icon' => '💳',
            'tone' => 'balance',
            'description' => 'Income minus expenses',
        ],
    ];
@endphp

<style>
    .dashboard-page {
        width: 100%;
        color: var(--ff-text, #172033);
    }

    .dashboard-page *,
    .dashboard-page *::before,
    .dashboard-page *::after {
        box-sizing: border-box;
    }

    .dashboard-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 26px;
    }

    .dashboard-page .page-heading h1 {
        margin: 0 0 8px;
        color: var(--ff-heading, #172033);
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 750;
        letter-spacing: -1px;
        line-height: 1.2;
    }

    .dashboard-page .page-heading p {
        margin: 0;
        color: var(--ff-muted, #64748b);
        font-size: 14px;
        line-height: 1.6;
    }

    .dashboard-page .btn-primary {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        min-height: 43px;
        padding: 11px 17px;
        border: 1px solid var(--ff-accent, #16845b);
        border-radius: 10px;
        background: var(--ff-accent, #16845b);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: filter .18s ease, transform .18s ease;
    }

    .dashboard-page .btn-primary:hover {
        filter: brightness(.94);
        color: #fff;
    }

    .dashboard-page .btn-primary:active {
        transform: translateY(1px);
    }

    .dashboard-page .dashboard-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .dashboard-page .metric-card,
    .dashboard-page .dashboard-panel,
    .dashboard-page .monthly-card {
        background: var(--ff-card-bg, var(--ff-surface, rgba(255, 255, 255, .88)));
        border: 1px solid var(--ff-border, #e8edf3);
        border-radius: 15px;
        box-shadow: 0 3px 12px rgba(24, 39, 75, .035);
        min-width: 0;
    }

    .dashboard-page .metric-card {
        padding: 23px;
        transition: border-color .18s ease, transform .18s ease;
    }

    .dashboard-page .metric-card:hover {
        border-color: color-mix(
            in srgb,
            var(--ff-accent, #16845b) 35%,
            var(--ff-border, #e8edf3)
        );
        transform: translateY(-2px);
    }

    .dashboard-page .metric-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .dashboard-page .metric-label {
        color: var(--ff-muted, #7b879b);
        font-size: 13px;
        font-weight: 600;
    }

    .dashboard-page .metric-icon {
        width: 39px;
        height: 39px;
        flex-shrink: 0;
        border-radius: 11px;
        display: grid;
        place-items: center;
        font-size: 20px;
        font-weight: 700;
    }

    /* Semantic colors remain recognizable across custom themes. */
    .dashboard-page .metric-icon.income {
        background: #e8f6ef;
        color: #16845b;
    }

    .dashboard-page .metric-icon.expense {
        background: #fff0ed;
        color: #d45c48;
    }

    .dashboard-page .metric-icon.balance {
        background: color-mix(in srgb, var(--ff-accent, #526cc5) 12%, transparent);
        color: var(--ff-accent, #526cc5);
    }

    .dashboard-page .metric-value {
        margin: 23px 0 8px;
        color: var(--ff-heading, #172033);
        font-size: clamp(22px, 2.3vw, 29px);
        font-weight: 750;
        letter-spacing: -1px;
        line-height: 1.35;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-page .metric-description {
        color: var(--ff-muted, #8b96a8);
        font-size: 12px;
        line-height: 1.5;
    }

    .dashboard-page .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(280px, 1fr);
        gap: 22px;
        align-items: start;
    }

    .dashboard-page .dashboard-panel {
        overflow: hidden;
    }

    .dashboard-page .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 22px 23px;
        border-bottom: 1px solid var(--ff-border, #edf0f4);
    }

    .dashboard-page .panel-header h2,
    .dashboard-page .monthly-card-heading h2 {
        margin: 0;
        color: var(--ff-heading, #172033);
        font-size: 15px;
        font-weight: 750;
    }

    .dashboard-page .panel-link {
        color: var(--ff-accent, #16845b);
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .dashboard-page .panel-link:hover {
        text-decoration: underline;
    }

    .dashboard-page .transaction-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .dashboard-page .transaction-item {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 17px 23px;
        border-bottom: 1px solid var(--ff-border, #f0f2f5);
        transition: background .15s ease;
    }

    .dashboard-page .transaction-item:hover {
        background: var(--ff-row-hover, rgba(148, 163, 184, .06));
    }

    .dashboard-page .transaction-item:last-child {
        border-bottom: 0;
    }

    .dashboard-page .transaction-symbol {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        font-size: 17px;
        font-weight: 700;
    }

    .dashboard-page .transaction-symbol.income {
        color: #16845b;
        background: #e8f6ef;
    }

    .dashboard-page .transaction-symbol.expense {
        color: #d45c48;
        background: #fff0ed;
    }

    .dashboard-page .transaction-info {
        flex: 1;
        min-width: 0;
    }

    .dashboard-page .transaction-category {
        color: var(--ff-heading, #172033);
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .dashboard-page .transaction-date {
        margin-top: 5px;
        color: var(--ff-muted, #8b96a8);
        font-size: 11px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .dashboard-page .transaction-amount {
        color: var(--ff-text, #26364b);
        text-align: right;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-page .transaction-amount.income {
        color: #16845b;
    }

    .dashboard-page .transaction-amount.expense {
        color: #d45c48;
    }

    .dashboard-page .empty-state {
        padding: 38px 24px;
        text-align: center;
    }

    .dashboard-page .empty-state-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        background: color-mix(in srgb, var(--ff-accent, #16845b) 12%, transparent);
        color: var(--ff-accent, #16845b);
        display: grid;
        place-items: center;
        margin: 0 auto 15px;
        font-size: 25px;
    }

    .dashboard-page .empty-state h3 {
        margin: 0 0 8px;
        color: var(--ff-heading, #172033);
        font-size: 15px;
    }

    .dashboard-page .empty-state p {
        color: var(--ff-muted, #7b879b);
        font-size: 12px;
        line-height: 1.7;
        margin: 0 0 18px;
    }

    .dashboard-page .monthly-card {
        padding: 23px;
    }

    .dashboard-page .monthly-card-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 7px;
    }

    .dashboard-page .month-pill {
        background: color-mix(in srgb, var(--ff-accent, #16845b) 11%, transparent);
        color: var(--ff-accent, #16845b);
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .dashboard-page .monthly-subtitle {
        margin: 0 0 23px;
        color: var(--ff-muted, #8b96a8);
        font-size: 12px;
        line-height: 1.6;
    }

    .dashboard-page .monthly-row {
        padding: 15px 0;
        border-top: 1px solid var(--ff-border, #edf0f4);
    }

    .dashboard-page .monthly-row-label {
        color: var(--ff-muted, #7b879b);
        font-size: 12px;
        margin-bottom: 8px;
    }

    .dashboard-page .monthly-row-value {
        color: var(--ff-heading, #172033);
        font-size: 20px;
        font-weight: 750;
        letter-spacing: -.5px;
        line-height: 1.4;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-page .monthly-row-value.income {
        color: #16845b;
    }

    .dashboard-page .monthly-row-value.expense {
        color: #d45c48;
    }

    .dashboard-page .monthly-row-value.savings {
        color: var(--ff-accent, #16845b);
    }

    .dashboard-page .monthly-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-top: 18px;
        margin-top: 4px;
        border-top: 1px solid var(--ff-border, #edf0f4);
    }

    .dashboard-page .monthly-footer span {
        color: var(--ff-muted, #7b879b);
        font-size: 12px;
    }

    @media (max-width: 1150px) {
        .dashboard-page .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .dashboard-page .dashboard-metrics {
            grid-template-columns: 1fr;
            gap: 13px;
        }

        .dashboard-page .metric-card {
            padding: 19px;
        }

        .dashboard-page .metric-value {
            margin: 15px 0 6px;
        }

        .dashboard-page .monthly-card {
            padding: 19px;
        }
    }

    @media (max-width: 420px) {
        .dashboard-page .page-heading {
            align-items: stretch;
        }

        .dashboard-page .page-heading .btn-primary {
            width: 100%;
        }

        .dashboard-page .transaction-item {
            padding: 15px;
            gap: 10px;
        }

        .dashboard-page .transaction-amount {
            font-size: 12px;
        }

        .dashboard-page .panel-header {
            padding: 18px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .dashboard-page *,
        .dashboard-page *::before,
        .dashboard-page *::after {
            transition: none !important;
            animation: none !important;
        }
    }
</style>

<main class="dashboard-page">
    <header class="page-heading">
        <div>
            <h1>Good to see you, {{ $name }}.</h1>
            <p>Here's what's happening with your money.</p>
        </div>

        <a href="{{ route('transactions.create') }}" class="btn-primary">
            <span aria-hidden="true">＋</span>
            Add transaction
        </a>
    </header>

    <section class="dashboard-metrics" aria-label="Financial summary">
        @foreach ($metrics as $metric)
            <article class="metric-card">
                <div class="metric-top">
                    <span class="metric-label">{{ $metric['label'] }}</span>

                    <span class="metric-icon {{ $metric['tone'] }}" aria-hidden="true">
                        {{ $metric['icon'] }}
                    </span>
                </div>

                <div class="metric-value">
                    Rs. {{ number_format((float) $metric['amount'], 2) }}
                </div>

                <div class="metric-description">
                    {{ $metric['description'] }}
                </div>
            </article>
        @endforeach
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-panel">
            <div class="panel-header">
                <h2>Recent transactions</h2>

                <a href="{{ route('transactions.index') }}" class="panel-link">
                    View all →
                </a>
            </div>

            @if ($recentTransactions->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon" aria-hidden="true">↗</div>

                    <h3>No transactions yet</h3>

                    <p>
                        Your recent income and expenses will appear here once
                        you add your first transaction.
                    </p>

                    <a href="{{ route('transactions.create') }}" class="btn-primary">
                        ＋ Add your first transaction
                    </a>
                </div>
            @else
                <ul class="transaction-list">
                    @foreach ($recentTransactions as $transaction)
                        <li class="transaction-item">
                            <div class="transaction-symbol {{ $transaction->type }}" aria-hidden="true">
                                {{ $transaction->type === 'income' ? '↙' : '↗' }}
                            </div>

                            <div class="transaction-info">
                                <div class="transaction-category">
                                    {{ $transaction->category }}
                                </div>

                                <div class="transaction-date">
                                    {{ $transaction->transaction_date->format('d M Y') }}

                                    @if ($transaction->description)
                                        · {{ $transaction->description }}
                                    @endif
                                </div>
                            </div>

                            <div class="transaction-amount {{ $transaction->type }}">
                                {{ $transaction->type === 'income' ? '+' : '−' }}Rs.
                                {{ number_format((float) $transaction->amount, 2) }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <aside class="monthly-card">
            <div class="monthly-card-heading">
                <h2>Monthly overview</h2>

                <span class="month-pill">
                    {{ now()->format('M Y') }}
                </span>
            </div>

            <p class="monthly-subtitle">
                Your financial snapshot for this month.
            </p>

            <div class="monthly-row">
                <div class="monthly-row-label">Income this month</div>

                <div class="monthly-row-value income">
                    Rs. {{ number_format((float) $monthlyIncome, 2) }}
                </div>
            </div>

            <div class="monthly-row">
                <div class="monthly-row-label">Expenses this month</div>

                <div class="monthly-row-value expense">
                    Rs. {{ number_format((float) $monthlyExpenses, 2) }}
                </div>
            </div>

            <div class="monthly-row">
                <div class="monthly-row-label">Net savings this month</div>

                <div class="monthly-row-value savings">
                    Rs. {{ number_format((float) $monthlySavings, 2) }}
                </div>
            </div>

            <div class="monthly-footer">
                <span>Want the full picture?</span>

                <a href="{{ route('reports.index') }}" class="panel-link">
                    View reports →
                </a>
            </div>
        </aside>
    </section>
</main>
@endsection