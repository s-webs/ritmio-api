<?php

namespace App\Services\Finance;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;

class FinanceAnalysisService
{
    public function weeklySummary(User $user): array
    {
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        return $this->summaryByPeriod($user, $start, $end);
    }

    public function monthlySummary(User $user): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        return $this->summaryByPeriod($user, $start, $end);
    }

    private function summaryByPeriod(User $user, Carbon $start, Carbon $end): array
    {
        $base = Transaction::query()
            ->where('transactions.user_id', $user->id)
            ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()]);

        $income = (clone $base)->where('type', Transaction::TYPE_INCOME)->sum('amount');
        $expense = (clone $base)->where('type', Transaction::TYPE_EXPENSE)->sum('amount');

        $byCategory = (clone $base)
            ->where('transactions.type', Transaction::TYPE_EXPENSE)
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->groupBy('categories.slug')
            ->selectRaw('categories.slug as category, SUM(transactions.amount) as total')
            ->get();

        return [
            'period_start' => $start->toDateString(),
            'period_end' => $end->toDateString(),
            'income_total' => (float) $income,
            'expense_total' => (float) $expense,
            'balance' => (float) $income - (float) $expense,
            'expenses_by_category' => $byCategory,
        ];
    }
}
