<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class AiInteraction extends Model
{
    protected $fillable = [
        'user_id', 'uploaded_file_id', 'intent', 'language', 'confidence', 'raw_text', 'model',
        'prompt_payload', 'response_payload', 'validation_errors',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'decimal:2',
            'prompt_payload' => 'array',
            'response_payload' => 'array',
            'validation_errors' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
