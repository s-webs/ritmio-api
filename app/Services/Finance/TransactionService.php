<?php

namespace App\Services\Finance;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Categories\CategoryResolver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(private readonly CategoryResolver $categoryResolver)
    {
    }

    public function create(User $user, array $data, bool $isAi = false): Transaction
    {
        return DB::transaction(function () use ($user, $data, $isAi): Transaction {
            $category = $this->categoryResolver->resolve($user, $data['type'], $data['category'] ?? 'other');

            $transaction = Transaction::query()->create([
                'user_id' => $user->id,
                'category_id' => $category?->id,
                'type' => $data['type'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? config('life_control.default_currency'),
                'transaction_date' => $data['date'] ?? Carbon::today()->toDateString(),
                'merchant' => $data['merchant'] ?? null,
                'source' => $data['source'] ?? null,
                'description' => $data['description'] ?? ($data['note'] ?? null),
                'raw_text' => $data['raw_text'] ?? null,
                'is_ai_generated' => $isAi,
                'needs_confirmation' => $isAi ? true : ($data['needs_confirmation'] ?? false),
            ]);

            foreach ($data['items'] ?? [] as $item) {
                $itemCategory = $this->categoryResolver->resolve($user, Transaction::TYPE_EXPENSE, $item['category'] ?? 'other');
                $transaction->items()->create([
                    'category_id' => $itemCategory?->id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'] ?? null,
                    'unit' => $item['unit'] ?? null,
                    'price' => $item['price'] ?? null,
                ]);
            }

            return $transaction->load(['category', 'items']);
        });
    }

    public function confirm(Transaction $transaction): Transaction
    {
        $transaction->update(['needs_confirmation' => false, 'confirmed_at' => now()]);

        return $transaction->fresh(['category', 'items']);
    }
}
