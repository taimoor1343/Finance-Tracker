 @extends('layouts.app')

@section('title', 'Financial Reports')
@section('topbar-title', 'Financial Reports')

@section('content')
<style>
    .reports-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        color: var(--ff-text, #172033);
    }

    .reports-page *,
    .reports-page *::before,
    .reports-page *::after {
        box-sizing: border-box;
    }

    .reports-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 26px;
    }

    .reports-page h1 {
        margin: 0 0 8px;
        color: var(--ff-heading, #172033);
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 750;
        letter-spacing: -1px;
        line-height: 1.2;
    }

    .reports-page h2 {
        margin: 0 0 18px;
        color: var(--ff-heading, #172033);
        font-size: 18px;
        font-weight: 750;
        letter-spacing: -.3px;
    }

    .reports-page p {
        color: var(--ff-muted, #64748b);
    }

    .reports-page .page-description {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }

    .reports-page .muted {
        color: var(--ff-muted, #64748b);
    }

    .reports-page .panel {
        margin-bottom: 24px;
        padding: 24px;
        border: 1px solid var(--ff-border, rgba(226, 232, 240, .9));
        border-radius: 18px;
        background: var(--ff-card-bg, var(--ff-surface, rgba(255, 255, 255, .88)));
        box-shadow: 0 8px 28px rgba(15, 23, 42, .04);
        backdrop-filter: blur(12px);
        min-width: 0;
    }

    .reports-page .panel-description {
        margin: -8px 0 20px;
        font-size: 13px;
        line-height: 1.6;
    }

    .reports-page .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        min-height: 43px;
        padding: 10px 16px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 10px;
        background: var(--ff-surface, rgba(255, 255, 255, .85));
        color: var(--ff-text, #172033);
        font: inherit;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease, transform .18s ease;
    }

    .reports-page .btn:hover {
        border-color: var(--ff-accent, #16845b);
        background: var(--ff-row-hover, rgba(148, 163, 184, .08));
    }

    .reports-page .btn:active {
        transform: translateY(1px);
    }

    .reports-page .btn-primary {
        color: #fff;
        background: var(--ff-accent, #16845b);
        border-color: var(--ff-accent, #16845b);
    }

    .reports-page .btn-primary:hover {
        color: #fff;
        filter: brightness(.95);
        background: var(--ff-accent, #16845b);
    }

    .reports-page .date-filters {
        display: flex;
        align-items: end;
        flex-wrap: wrap;
        gap: 16px;
    }

    .reports-page .field {
        flex: 1 1 180px;
        min-width: 0;
    }

    .reports-page label {
        display: block;
        margin-bottom: 7px;
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        font-weight: 650;
    }

    .reports-page input[type="date"] {
        display: block;
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid var(--ff-border, #dbe2ea);
        border-radius: 10px;
        background: var(--ff-input-bg, rgba(255, 255, 255, .92));
        color: var(--ff-text, #172033);
        font: inherit;
        font-size: 14px;
        color-scheme: light;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .reports-page input[type="date"]:focus {
        outline: none;
        border-color: var(--ff-accent, #16845b);
        box-shadow: 0 0 0 3px color-mix(
            in srgb,
            var(--ff-accent, #16845b) 18%,
            transparent
        );
    }

    .reports-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .reports-page .summary-card {
        padding: 21px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 15px;
        background: var(--ff-surface, rgba(255, 255, 255, .55));
        min-width: 0;
        transition: border-color .18s ease, transform .18s ease;
    }

    .reports-page .summary-card:hover {
        border-color: color-mix(
            in srgb,
            var(--ff-accent, #16845b) 35%,
            var(--ff-border, #e2e8f0)
        );
        transform: translateY(-2px);
    }

    .reports-page .summary-card p {
        margin: 0 0 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .reports-page .summary-card h3 {
        margin: 0;
        color: var(--ff-heading, #172033);
        font-size: clamp(19px, 2.4vw, 27px);
        font-weight: 800;
        line-height: 1.35;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    /* Income and expenses retain semantic colors for quick recognition. */
    .reports-page .income-amount {
        color: #16a34a;
    }

    .reports-page .expense-amount {
        color: #dc6262;
    }

    .reports-page .savings-amount {
        color: var(--ff-accent, #16845b);
    }

    .reports-page .chart-container {
        position: relative;
        width: 100%;
        height: 350px;
    }

    .reports-page .report-table-wrap {
        overflow-x: auto;
        width: 100%;
        border: 1px solid var(--ff-border, #e5e7eb);
        border-radius: 12px;
    }

    .reports-page .report-table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    .reports-page .report-table th {
        padding: 13px 14px;
        background: var(--ff-table-head, rgba(148, 163, 184, .09));
        color: var(--ff-muted, #64748b);
        text-align: left;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .reports-page .report-table td {
        padding: 15px 14px;
        border-bottom: 1px solid var(--ff-border, #e5e7eb);
        color: var(--ff-text, #172033);
        font-size: 13px;
    }

    .reports-page .report-table td.income-amount {
        color: #16a34a;
    }

    .reports-page .report-table td.expense-amount {
        color: #dc6262;
    }

    .reports-page .report-table td.savings-amount {
        color: var(--ff-accent, #16845b);
    }

    .reports-page .report-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .reports-page .report-table tbody tr:hover {
        background: var(--ff-row-hover, rgba(148, 163, 184, .06));
    }

    .reports-page .empty-chart {
        display: grid;
        min-height: 200px;
        place-items: center;
        padding: 24px;
        border: 1px dashed var(--ff-border, #e5e7eb);
        border-radius: 12px;
        background: var(--ff-surface, rgba(255, 255, 255, .35));
        text-align: center;
    }

    .reports-page .empty-chart p {
        margin: 0;
        font-size: 14px;
    }

    .reports-page .empty-table {
        color: var(--ff-muted, #64748b) !important;
        text-align: center;
        white-space: normal;
    }

    @media (max-width: 760px) {
        .reports-page .summary-grid {
            grid-template-columns: 1fr;
        }

        .reports-page .panel {
            padding: 17px;
            border-radius: 14px;
        }

        .reports-page .chart-container {
            height: 290px;
        }

        .reports-page .date-filters .field {
            flex-basis: 100%;
        }

        .reports-page .date-filters .btn {
            flex: 1;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .reports-page *,
        .reports-page *::before,
        .reports-page *::after {
            transition: none !important;
            animation: none !important;
        }
    }
</style>

<main class="reports-page">
    <header class="page-heading">
        <div>
            <h1>Financial Reports</h1>
            <p class="page-description">
                Understand your income, expenses, and savings.
            </p>
        </div>
    </header>

    <section class="panel">
        <h2>Choose a date range</h2>

        <form method="GET" action="{{ route('reports.index') }}">
            <div class="date-filters">
                <div class="field">
                    <label for="from">From</label>
                    <input
                        type="date"
                        id="from"
                        name="from"
                        value="{{ request('from', $startDate) }}"
                        max="{{ request('to', $endDate) }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="to">To</label>
                    <input
                        type="date"
                        id="to"
                        name="to"
                        value="{{ request('to', $endDate) }}"
                        min="{{ request('from', $startDate) }}"
                        required
                    >
                </div>

                <button class="btn btn-primary" type="submit">
                    Apply filters
                </button>

                <a class="btn" href="{{ route('reports.index') }}">
                    Reset
                </a>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>Selected period summary</h2>

        <div class="summary-grid">
            <article class="summary-card">
                <p>Total income</p>
                <h3 class="income-amount">
                    Rs. {{ number_format($totalIncome, 2) }}
                </h3>
            </article>

            <article class="summary-card">
                <p>Total expenses</p>
                <h3 class="expense-amount">
                    Rs. {{ number_format($totalExpenses, 2) }}
                </h3>
            </article>

            <article class="summary-card">
                <p>Net savings</p>
                <h3 class="savings-amount">
                    Rs. {{ number_format($netSavings, 2) }}
                </h3>
            </article>
        </div>
    </section>

    <section class="panel">
        <h2>Income vs. expenses</h2>
        <p class="panel-description">
            Compare your monthly income and spending for the selected period.
        </p>

        <div class="chart-container">
            <canvas id="monthlyChart"></canvas>
        </div>
    </section>

    <section class="panel">
        <h2>Expenses by category</h2>
        <p class="panel-description">
            See how your spending is distributed across categories.
        </p>

        @if ($expensesByCategory->isNotEmpty())
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        @else
            <div class="empty-chart">
                <p>No expenses found for this date range.</p>
            </div>
        @endif
    </section>

    <section class="panel">
        <h2>Monthly breakdown</h2>

        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Income</th>
                        <th>Expenses</th>
                        <th>Net savings</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($monthlyReport as $month)
                        @php
                            $monthlyNet = $month['income'] - $month['expenses'];
                        @endphp

                        <tr>
                            <td>
                                <strong>{{ $month['month'] }}</strong>
                            </td>

                            <td class="income-amount">
                                Rs. {{ number_format($month['income'], 2) }}
                            </td>

                            <td class="expense-amount">
                                Rs. {{ number_format($month['expenses'], 2) }}
                            </td>

                            <td class="{{ $monthlyNet < 0 ? 'expense-amount' : 'savings-amount' }}">
                                Rs. {{ number_format($monthlyNet, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-table">
                                No monthly data is available for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    (() => {
        // Read the saved theme variables so the charts match the active theme.
        const styles = getComputedStyle(document.documentElement);

        const themeColor = (variable, fallback) => {
            const value = styles.getPropertyValue(variable).trim();
            return value || fallback;
        };

        const accent = themeColor('--ff-accent', '#16845b');
        const textColor = themeColor('--ff-text', '#172033');
        const mutedColor = themeColor('--ff-muted', '#64748b');
        const borderColor = themeColor('--ff-border', '#e2e8f0');
        const tableHeadColor = themeColor('--ff-table-head', 'rgba(148,163,184,.09)');

        const incomeColor = '#16a34a';
        const expenseColor = '#dc6262';

        const categoryColors = [
            accent,
            '#6478d3',
            '#d6a34d',
            '#0d9488',
            '#9333ea',
            '#ea580c',
            '#64748b',
            '#db2777',
            '#65a30d',
            '#0891b2'
        ];

        const money = value => Number(value).toLocaleString('en-PK', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Chart.js global defaults for consistent theme-aware labels and grids.
        if (typeof Chart !== 'undefined') {
            Chart.defaults.color = mutedColor;
            Chart.defaults.borderColor = borderColor;
            Chart.defaults.font.family = getComputedStyle(document.body).fontFamily || 'sans-serif';
        }

        // Monthly income versus expenses.
        const monthLabels = @json($monthlyReport->pluck('month')->values());
        const incomeValues = @json($monthlyReport->pluck('income')->values());
        const expenseValues = @json($monthlyReport->pluck('expenses')->values());

        const monthlyCanvas = document.getElementById('monthlyChart');

        if (monthlyCanvas && typeof Chart !== 'undefined') {
            new Chart(monthlyCanvas, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [
                        {
                            label: 'Income',
                            data: incomeValues,
                            backgroundColor: incomeColor,
                            borderRadius: 5,
                            maxBarThickness: 48
                        },
                        {
                            label: 'Expenses',
                            data: expenseValues,
                            backgroundColor: expenseColor,
                            borderRadius: 5,
                            maxBarThickness: 48
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 18,
                                color: textColor
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    return context.dataset.label + ': Rs. ' + money(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: mutedColor
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: tableHeadColor
                            },
                            ticks: {
                                color: mutedColor,
                                callback(value) {
                                    return 'Rs. ' + Number(value).toLocaleString('en-PK');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Expense breakdown by category.
        const categoryLabels = @json($expensesByCategory->pluck('category')->values());
        const categoryValues = @json($expensesByCategory->pluck('total')->values());

        const categoryCanvas = document.getElementById('categoryChart');

        if (categoryCanvas && typeof Chart !== 'undefined') {
            new Chart(categoryCanvas, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        label: 'Expenses',
                        data: categoryValues,
                        backgroundColor: categoryLabels.map(
                            (_, index) => categoryColors[index % categoryColors.length]
                        ),
                        borderColor: themeColor('--ff-card-bg', '#ffffff'),
                        borderWidth: 2,
                        hoverOffset: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 18,
                                color: textColor
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    return context.label + ': Rs. ' + money(context.raw);
                                }
                            }
                        }
                    }
                }
            });
        }
    })();
</script>
@endsection