@extends('layouts.app')

@section('title', 'Add Transaction')
@section('topbar-title', 'Add Transaction')

@section('content')
<style>
    .transaction-form-page {
        max-width: 900px;
        margin: 0 auto;
        color: var(--ff-text, #172033);
    }

    .transaction-form-page *,
    .transaction-form-page *::before,
    .transaction-form-page *::after {
        box-sizing: border-box;
    }

    .transaction-form-page .page-heading {
        margin-bottom: 25px;
    }

    .transaction-form-page h1 {
        margin: 0 0 8px;
        color: var(--ff-heading, var(--ff-text, #172033));
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 750;
        letter-spacing: -1px;
    }

    .transaction-form-page .muted {
        color: var(--ff-muted, #64748b);
        margin: 0;
        line-height: 1.6;
    }

    .transaction-form-page .panel {
        padding: 28px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 18px;
        background: var(--ff-card-bg, var(--ff-surface, rgba(255,255,255,.88)));
        box-shadow: 0 8px 28px rgba(15, 23, 42, .04);
        backdrop-filter: blur(12px);
    }

    .transaction-form-page .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .transaction-form-page .field {
        min-width: 0;
    }

    .transaction-form-page .field.full-width {
        grid-column: 1 / -1;
    }

    .transaction-form-page label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 650;
        color: var(--ff-text, #172033);
    }

    .transaction-form-page .required-mark {
        color: var(--ff-accent, #16845b);
    }

    .transaction-form-page input,
    .transaction-form-page select,
    .transaction-form-page textarea {
        display: block;
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--ff-border, #dbe2ea);
        border-radius: 11px;
        background: var(--ff-input-bg, var(--ff-surface, #fff));
        color: var(--ff-text, #172033);
        font-family: inherit;
        font-size: 14px;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .transaction-form-page input,
    .transaction-form-page select {
        min-height: 46px;
    }

    .transaction-form-page textarea {
        min-height: 110px;
        resize: vertical;
        line-height: 1.6;
    }

    .transaction-form-page input::placeholder,
    .transaction-form-page textarea::placeholder {
        color: var(--ff-muted, #64748b);
        opacity: .75;
    }

    .transaction-form-page input:focus,
    .transaction-form-page select:focus,
    .transaction-form-page textarea:focus {
        outline: none;
        border-color: var(--ff-accent, #16845b);
        box-shadow: 0 0 0 3px color-mix(
            in srgb,
            var(--ff-accent, #16845b) 17%,
            transparent
        );
    }

    .transaction-form-page .error {
        margin-top: 7px;
        color: #dc2626;
        font-size: 12px;
        line-height: 1.5;
    }

    .transaction-form-page .alert {
        padding: 15px 17px;
        margin-bottom: 24px;
        border: 1px solid #fecaca;
        border-radius: 12px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 14px;
        line-height: 1.6;
    }

    .transaction-form-page .alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .transaction-form-page .alert li + li {
        margin-top: 4px;
    }

    .transaction-form-page .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid var(--ff-border, #e2e8f0);
    }

    .transaction-form-page .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 11px 18px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 10px;
        background: var(--ff-surface, #fff);
        color: var(--ff-text, #172033);
        text-decoration: none;
        font-family: inherit;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        transition: transform .18s ease, opacity .18s ease, background .18s ease;
    }

    .transaction-form-page .btn:hover {
        opacity: .88;
    }

    .transaction-form-page .btn:active {
        transform: translateY(1px);
    }

    .transaction-form-page .btn:focus-visible {
        outline: 2px solid var(--ff-accent, #16845b);
        outline-offset: 3px;
    }

    .transaction-form-page .btn-primary {
        background: var(--ff-accent, #16845b);
        color: #fff;
        border-color: var(--ff-accent, #16845b);
    }

    .transaction-form-page .btn-primary:hover {
        opacity: .92;
    }

    @media (max-width: 600px) {
        .transaction-form-page .page-heading {
            margin-bottom: 20px;
        }

        .transaction-form-page .panel {
            padding: 18px;
            border-radius: 14px;
        }

        .transaction-form-page .form-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .transaction-form-page .field.full-width {
            grid-column: auto;
        }

        .transaction-form-page .form-actions .btn {
            flex: 1;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .transaction-form-page *,
        .transaction-form-page *::before,
        .transaction-form-page *::after {
            transition: none !important;
        }
    }
</style>

<main class="transaction-form-page">
    <header class="page-heading">
        <h1>Add Transaction</h1>
        <p class="muted">
            Record income or expenses to keep your finances up to date.
        </p>
    </header>

    <section class="panel">
        @if ($errors->any())
            <div class="alert" role="alert" aria-live="polite">
                <strong>Please check the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="type">
                        Transaction type <span class="required-mark">*</span>
                    </label>

                    <select id="type" name="type" required
                        aria-describedby="@error('type') type-error @enderror">
                        <option value="expense"
                            {{ old('type', 'expense') === 'expense' ? 'selected' : '' }}>
                            Expense
                        </option>
                        <option value="income"
                            {{ old('type') === 'income' ? 'selected' : '' }}>
                            Income
                        </option>
                    </select>

                    @error('type')
                        <div class="error" id="type-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="amount">
                        Amount (Rs.) <span class="required-mark">*</span>
                    </label>

                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        min="0.01"
                        step="0.01"
                        placeholder="e.g. 2500.00"
                        value="{{ old('amount') }}"
                        required
                        inputmode="decimal"
                        @error('amount') aria-describedby="amount-error" @enderror
                    >

                    @error('amount')
                        <div class="error" id="amount-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="category">
                        Category <span class="required-mark">*</span>
                    </label>

                    <select
                        id="category"
                        name="category"
                        required
                        @error('category') aria-describedby="category-error" @enderror
                    >
                        <option value="">Choose a category</option>
                    </select>

                    @error('category')
                        <div class="error" id="category-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="transaction_date">
                        Transaction date <span class="required-mark">*</span>
                    </label>

                    <input
                        type="date"
                        id="transaction_date"
                        name="transaction_date"
                        value="{{ old('transaction_date', now()->toDateString()) }}"
                        required
                        @error('transaction_date') aria-describedby="transaction-date-error" @enderror
                    >

                    @error('transaction_date')
                        <div class="error" id="transaction-date-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field full-width">
                    <label for="description">Description (optional)</label>

                    <textarea
                        id="description"
                        name="description"
                        maxlength="255"
                        placeholder="Add a note about this transaction..."
                        @error('description') aria-describedby="description-error" @enderror
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <div class="error" id="description-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">
                    Save Transaction
                </button>

                <a class="btn" href="{{ route('transactions.index') }}">
                    Cancel
                </a>
            </div>
        </form>
    </section>
</main>

<script>
    (() => {
        const categoriesByType = {
            expense: [
                'Food & Dining',
                'Transport',
                'Shopping',
                'Bills & Utilities',
                'Housing',
                'Healthcare',
                'Education',
                'Entertainment',
                'Other'
            ],
            income: [
                'Salary',
                'Freelance',
                'Business',
                'Investment',
                'Other'
            ]
        };

        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category');

        if (!typeSelect || !categorySelect) {
            return;
        }

        const savedCategory = @json(old('category', ''));

        function updateCategories(selectedCategory = '') {
            const categories = categoriesByType[typeSelect.value] || [];

            categorySelect.replaceChildren();

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Choose a category';
            categorySelect.appendChild(placeholder);

            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                option.selected = category === selectedCategory;
                categorySelect.appendChild(option);
            });

            // Preserve an old value so validation feedback doesn't erase input.
            if (selectedCategory && !categories.includes(selectedCategory)) {
                const option = document.createElement('option');
                option.value = selectedCategory;
                option.textContent = selectedCategory + ' (previous value)';
                option.selected = true;
                categorySelect.appendChild(option);
            }
        }

        typeSelect.addEventListener('change', () => updateCategories());
        updateCategories(savedCategory);
    })();
</script>
@endsection
