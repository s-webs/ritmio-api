<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 'raw_text', 'status', 'priority',
        'due_date', 'due_time', 'is_ai_generated', 'needs_confirmation', 'ai_confidence',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'is_ai_generated' => 'boolean',
            'needs_confirmation' => 'boolean',
            'ai_confidence' => 'decimal:2',
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
}
