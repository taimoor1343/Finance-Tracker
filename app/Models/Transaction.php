<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\RecurringTransaction;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'category',
        'transaction_date',
        'description',
        'recurring_transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function recurringTransaction(): BelongsTo
{
    return $this->belongsTo(RecurringTransaction::class);
}
}