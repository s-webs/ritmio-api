<?php

namespace App\Services\AI;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AiCommandParser
{
    public function __construct(
        private readonly OpenAiClient $client,
        private readonly AiPromptBuilder $promptBuilder,
    ) {
    }

    public function parse(string $text): array
    {
        $prompt = $this->promptBuilder->parseCommandPrompt($text, Carbon::now()->toDateString());
        $parsed = $this->client->parseText($prompt);

        $validator = Validator::make($parsed, [
            'intent' => 'required|in:create_expense,create_income,create_task,create_tasks,finance_analysis,task_query,unknown',
            'language' => 'nullable|in:ru,en',
            'confidence' => 'nullable|numeric|min:0|max:1',
            'needs_confirmation' => 'nullable|boolean',
            'expense' => 'nullable|array',
            'income' => 'nullable|array',
            'tasks' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        return $parsed;
    }
}
