<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public const TYPE_EXPENSE = 'expense';
    public const TYPE_INCOME = 'income';

    protected $fillable = [
        'user_id', 'category_id', 'type', 'amount', 'currency', 'transaction_date', 'merchant', 'source',
        'description', 'raw_text', 'is_ai_generated', 'needs_confirmation', 'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
            'is_ai_generated' => 'boolean',
            'needs_confirmation' => 'boolean',
            'confirmed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
