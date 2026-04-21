<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiParseResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'parsed' => $this['parsed'] ?? null,
            'transaction' => isset($this['transaction']) ? new TransactionResource($this['transaction']) : null,
            'tasks' => isset($this['tasks']) ? TaskResource::collection($this['tasks']) : [],
            'interaction_id' => $this['interaction_id'] ?? null,
        ];
    }
}
