<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'currency' => $this->currency,
            'transaction_date' => $this->transaction_date?->toDateString(),
            'merchant' => $this->merchant,
            'source' => $this->source,
            'description' => $this->description,
            'raw_text' => $this->raw_text,
            'needs_confirmation' => $this->needs_confirmation,
            'category' => $this->category?->slug,
            'items' => $this->items ?? [],
        ];
    }
}
