<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class OpenAiClient
{
    public function parseText(string $prompt): array
    {
        $response = Http::baseUrl(config('services.openai.base_url'))
            ->withToken(config('services.openai.api_key'))
            ->acceptJson()
            ->post('/chat/completions', [
                'model' => config('services.openai.model'),
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => 'Return strict JSON only.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ])->throw()->json();

        $content = data_get($response, 'choices.0.message.content', '{}');

        return is_string($content) ? (json_decode($content, true) ?: []) : [];
    }
}
