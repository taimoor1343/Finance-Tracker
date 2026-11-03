<?php

namespace Tests\Feature;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RecurringTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_due_recurring_transaction_is_generated_once(): void
    {
        $user = User::factory()->create();

        $recurring = $user->recurringTransactions()->create([
            'type' => 'expense',
            'amount' => 1500,
            'category' => 'Food',
            'description' => 'Monthly groceries',
            'frequency' => 'monthly',
            'start_date' => now()->subMonth()->startOfMonth()->toDateString(),
            'next_due_date' => now()->subDay()->toDateString(),
            'end_date' => null,
            'is_active' => true,
        ]);

        Artisan::call('recurring:generate');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'recurring_transaction_id' => $recurring->id,
            'type' => 'expense',
            'category' => 'Food',
        ]);

        $this->assertSame(
            1,
            Transaction::where('recurring_transaction_id', $recurring->id)->count()
        );

        // Running the generator again must not create a duplicate.
        Artisan::call('recurring:generate');

        $this->assertSame(
            1,
            Transaction::where('recurring_transaction_id', $recurring->id)->count()
        );
    }
    
public function test_paused_recurring_transaction_is_not_generated(): void
{
    $user = User::factory()->create();

    $recurring = $user->recurringTransactions()->create([
        'type' => 'expense',
        'amount' => 2000,
        'category' => 'Food',
        'description' => 'Paused groceries',
        'frequency' => 'monthly',
        'start_date' => now()->subMonth()->startOfMonth()->toDateString(),
        'next_due_date' => now()->subDay()->toDateString(),
        'end_date' => null,
        'is_active' => false,
    ]);

    Artisan::call('recurring:generate');

    $this->assertDatabaseMissing('transactions', [
        'recurring_transaction_id' => $recurring->id,
    ]);
}

public function test_recurring_transaction_stops_after_end_date(): void
{
    $user = User::factory()->create();

    $recurring = $user->recurringTransactions()->create([
        'type' => 'expense',
        'amount' => 3000,
        'category' => 'Food',
        'description' => 'Expired groceries',
        'frequency' => 'monthly',
        'start_date' => now()->subMonths(2)->startOfMonth()->toDateString(),
        'next_due_date' => now()->subDay()->toDateString(),
        'end_date' => now()->subDays(2)->toDateString(),
        'is_active' => true,
    ]);

    Artisan::call('recurring:generate');

    $this->assertDatabaseMissing('transactions', [
        'recurring_transaction_id' => $recurring->id,
    ]);

    $this->assertDatabaseHas('recurring_transactions', [
        'id' => $recurring->id,
        'is_active' => false,
    ]);
}

}
