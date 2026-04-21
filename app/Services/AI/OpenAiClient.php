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

    public function transcribeAudio(string $audioPath): string
    {
        $filename = basename($audioPath);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeMap = [
            'm4a' => 'audio/mp4',
            'mp4' => 'audio/mp4',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'webm' => 'audio/webm',
        ];
        $mime = $mimeMap[$extension] ?? 'audio/mp4';

        $response = Http::baseUrl(config('services.openai.base_url'))
            ->withToken(config('services.openai.api_key'))
            ->attach('file', file_get_contents($audioPath), $filename, ['Content-Type' => $mime])
            ->post('/audio/transcriptions', [
                'model' => 'whisper-1',
                'language' => 'ru',
                'response_format' => 'text',
            ])
            ->throw();

        return trim($response->body());
    }
}
