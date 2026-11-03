
<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Transactions
Route::get('/transactions/create', [TransactionController::class, 'create'])
    ->middleware('auth')
    ->name('transactions.create');

Route::post('/transactions', [TransactionController::class, 'store'])
    ->middleware('auth')
    ->name('transactions.store');

Route::get('/transactions', [TransactionController::class, 'index'])
    ->middleware('auth')
    ->name('transactions.index');

Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
    ->middleware('auth')
    ->name('transactions.destroy');

Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])
    ->middleware('auth')
    ->name('transactions.edit');

Route::get('/transactions/export', [TransactionController::class, 'export'])
    ->middleware('auth')
    ->name('transactions.export');

Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])
    ->middleware('auth')
    ->name('transactions.update');

// Reports
Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('auth')
    ->name('reports.index');

// Settings
Route::view('/settings', 'settings')
    ->middleware('auth')
    ->name('settings');

// Budgets
Route::resource('budgets', BudgetController::class)
    ->only(['index', 'store', 'update', 'destroy']);

// Recurring Transactions
Route::patch(
    '/recurring-transactions/{recurringTransaction}/toggle',
    [RecurringTransactionController::class, 'toggle']
)
    ->middleware('auth')
    ->name('recurring-transactions.toggle');

Route::resource('recurring-transactions', RecurringTransactionController::class)
    ->only(['index', 'store', 'update', 'destroy']);
