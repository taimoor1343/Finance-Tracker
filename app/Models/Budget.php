<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{

public function getSpentAmountAttribute(): float
{
    return (float) $this->transactions()->sum('amount');
}
 public function transactions(): HasMany
{
    return $this->hasMany(Transaction::class, 'user_id', 'user_id')
        ->where('transactions.type', 'expense')
        ->whereColumn('transactions.category', 'budgets.category')
        ->whereRaw('MONTH(transactions.transaction_date) = budgets.month')
        ->whereRaw('YEAR(transactions.transaction_date) = budgets.year');
}
    protected $fillable = [
        'category',
        'amount',
        'month',
        'year',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'month' => 'integer',
            'year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}