<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_edit_another_users_transaction(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $transaction = $owner->transactions()->create([
            'type' => 'expense',
            'amount' => 100,
            'category' => 'Food & Dining',
            'transaction_date' => now()->toDateString(),
            'description' => 'Private expense',
        ]);

        $response = $this->actingAs($otherUser)
            ->get(route('transactions.edit', $transaction));

        $response->assertForbidden();
    }

    public function test_user_cannot_delete_another_users_transaction(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $transaction = $owner->transactions()->create([
            'type' => 'expense',
            'amount' => 100,
            'category' => 'Food & Dining',
            'transaction_date' => now()->toDateString(),
            'description' => 'Private expense',
        ]);

        $response = $this->actingAs($otherUser)
            ->delete(route('transactions.destroy', $transaction));

        $response->assertForbidden();

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'user_id' => $owner->id,
        ]);
    }
    
public function test_transaction_rejects_category_not_allowed_for_type(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('transactions.store'), [
        'type' => 'expense',
        'amount' => '100.00',
        'category' => 'Salary',
        'transaction_date' => now()->toDateString(),
        'description' => 'Invalid category test',
    ]);

    $response->assertSessionHasErrors('category');

    $this->assertDatabaseCount('transactions', 0);
}

public function test_transaction_rejects_zero_amount(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('transactions.store'), [
        'type' => 'expense',
        'amount' => '0',
        'category' => 'Food & Dining',
        'transaction_date' => now()->toDateString(),
        'description' => 'Zero amount test',
    ]);

    $response->assertSessionHasErrors('amount');

    $this->assertDatabaseCount('transactions', 0);
}

public function test_valid_transaction_is_saved_for_authenticated_user(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('transactions.store'), [
        'type' => 'income',
        'amount' => '1500.00',
        'category' => 'Salary',
        'transaction_date' => now()->toDateString(),
        'description' => 'Monthly salary',
    ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('transactions', [
        'user_id' => $user->id,
        'type' => 'income',
        'amount' => '1500.00',
        'category' => 'Salary',
        'description' => 'Monthly salary',
    ]);
}


public function test_csv_export_contains_only_the_authenticated_users_transactions(): void
{
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->transactions()->create([
        'type' => 'income',
        'amount' => 1500,
        'category' => 'Salary',
        'transaction_date' => '2026-10-01',
        'description' => 'My salary',
    ]);

    $otherUser->transactions()->create([
        'type' => 'income',
        'amount' => 9000,
        'category' => 'Salary',
        'transaction_date' => '2026-10-02',
        'description' => 'Someone elses salary',
    ]);

    $response = $this->actingAs($user)
        ->get(route('transactions.export'));

    $response->assertOk();
    $response->assertDownload();

    $content = $response->streamedContent();

    $this->assertStringContainsString('My salary', $content);
    $this->assertStringNotContainsString('Someone elses salary', $content);
}

public function test_csv_export_respects_transaction_type_filter(): void
{
    $user = User::factory()->create();

    $user->transactions()->create([
        'type' => 'income',
        'amount' => 1500,
        'category' => 'Salary',
        'transaction_date' => '2026-10-01',
        'description' => 'Monthly salary',
    ]);

    $user->transactions()->create([
        'type' => 'expense',
        'amount' => 50,
        'category' => 'Food & Dining',
        'transaction_date' => '2026-10-02',
        'description' => 'Lunch',
    ]);

    $response = $this->actingAs($user)
        ->get(route('transactions.export', ['type' => 'expense']));

    $response->assertOk();

    $content = $response->streamedContent();

    $this->assertStringContainsString('Lunch', $content);
    $this->assertStringNotContainsString('Monthly salary', $content);
}


public function test_transaction_search_returns_matching_records_only(): void
{
    $user = User::factory()->create();

    $user->transactions()->create([
        'type' => 'expense',
        'amount' => 50,
        'category' => 'Food & Dining',
        'transaction_date' => '2026-10-01',
        'description' => 'Lunch at cafe',
    ]);

    $user->transactions()->create([
        'type' => 'expense',
        'amount' => 100,
        'category' => 'Transport',
        'transaction_date' => '2026-10-02',
        'description' => 'Bus ticket',
    ]);

    $response = $this->actingAs($user)
        ->get(route('transactions.index', ['search' => 'cafe']));

    $response->assertOk();
    $response->assertSeeText('Lunch at cafe');
    $response->assertDontSeeText('Bus ticket');
}

public function test_transaction_date_filter_excludes_records_outside_range(): void
{
    $user = User::factory()->create();

    $user->transactions()->create([
        'type' => 'expense',
        'amount' => 50,
        'category' => 'Food & Dining',
        'transaction_date' => '2026-10-05',
        'description' => 'Inside date range',
    ]);

    $user->transactions()->create([
        'type' => 'expense',
        'amount' => 100,
        'category' => 'Transport',
        'transaction_date' => '2026-10-20',
        'description' => 'Outside date range',
    ]);

    $response = $this->actingAs($user)->get(route('transactions.index', [
        'from' => '2026-10-01',
        'to' => '2026-10-10',
    ]));

    $response->assertOk();
    $response->assertSeeText('Inside date range');
    $response->assertDontSeeText('Outside date range');
}


}
