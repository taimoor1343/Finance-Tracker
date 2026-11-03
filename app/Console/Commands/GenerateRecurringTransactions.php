<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateRecurringTransactions extends Command
{
    protected $signature = 'recurring:generate';

    protected $description = 'Generate transactions from due recurring transactions';

    public function handle(): int
    {
        $today = Carbon::today();

        $recurringIds = RecurringTransaction::query()
            ->where('is_active', true)
            ->whereDate('next_due_date', '<=', $today)
            ->pluck('id');

        $generatedCount = 0;

        foreach ($recurringIds as $recurringId) {
            DB::transaction(function () use (
                $recurringId,
                $today,
                &$generatedCount
            ) {
                $recurring = RecurringTransaction::query()
                    ->whereKey($recurringId)
                    ->lockForUpdate()
                    ->first();

                if (! $recurring || ! $recurring->is_active) {
                    return;
                }

                $dueDate = Carbon::parse($recurring->next_due_date);
                $startDate = Carbon::parse($recurring->start_date);

                $endDate = $recurring->end_date
                    ? Carbon::parse($recurring->end_date)
                    : null;

                while ($dueDate->lte($today)) {
                    if ($endDate && $dueDate->gt($endDate)) {
                        $recurring->update(['is_active' => false]);

                        break;
                    }

                    $inserted = DB::table('transactions')->insertOrIgnore([
                        'user_id' => $recurring->user_id,
                        'recurring_transaction_id' => $recurring->id,
                        'type' => $recurring->type,
                        'amount' => $recurring->amount,
                        'category' => $recurring->category,
                        'description' => $recurring->description,
                        'transaction_date' => $dueDate->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $generatedCount += $inserted;

                     

                    $dueDate = $this->getNextDueDate(
                        $dueDate,
                        $startDate,
                        $recurring->frequency
                    );

                    $recurring->update([
                        'next_due_date' => $dueDate->toDateString(),
                    ]);
                }

                if ($endDate && $dueDate->gt($endDate)) {
                    $recurring->update(['is_active' => false]);
                }
            });
        }

        $this->info("Recurring transaction processing completed. {$generatedCount} occurrence(s) processed.");

        return self::SUCCESS;
    }

    private function getNextDueDate(
        Carbon $currentDate,
        Carbon $startDate,
        string $frequency
    ): Carbon {
        $nextDate = $currentDate->copy();

        if ($frequency === 'weekly') {
            return $nextDate->addWeek();
        }

        if ($frequency === 'monthly') {
            $nextDate->addMonthNoOverflow();

            $daysInMonth = $nextDate->daysInMonth;

            $nextDate->day = min($startDate->day, $daysInMonth);

            return $nextDate;
        }

        if ($frequency === 'yearly') {
            $nextDate->addYearNoOverflow();

            $nextDate->month = $startDate->month;

            $daysInMonth = $nextDate->daysInMonth;

            $nextDate->day = min($startDate->day, $daysInMonth);

            return $nextDate;
        }

        throw new \InvalidArgumentException(
            "Unsupported recurring frequency: {$frequency}"
        );
    }
}
