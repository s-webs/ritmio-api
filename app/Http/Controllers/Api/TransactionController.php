<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreTransactionRequest;
use App\Http\Requests\Finance\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\Finance\TransactionService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $transactions = Transaction::query()
            ->where('user_id', auth()->id())
            ->latest('transaction_date')
            ->paginate();

        return TransactionResource::collection($transactions);
    }

    public function store(StoreTransactionRequest $request): TransactionResource
    {
        $transaction = $this->transactionService->create($request->user(), $request->validated());
        return new TransactionResource($transaction);
    }

    public function show(Transaction $transaction): TransactionResource
    {
        $this->authorize('view', $transaction);
        return new TransactionResource($transaction->load(['category', 'items']));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): TransactionResource
    {
        $this->authorize('update', $transaction);
        $transaction->update($request->validated());
        return new TransactionResource($transaction->fresh(['category', 'items']));
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();
        return response()->noContent();
    }

    public function confirm(Transaction $transaction): TransactionResource
    {
        $this->authorize('update', $transaction);
        return new TransactionResource($this->transactionService->confirm($transaction));
    }
}
