<?php

namespace App\Services\AI;

class AiPromptBuilder
{
    public function parseCommandPrompt(string $text, string $date): string
    {
        return <<<PROMPT
You are an API parser for life-control commands. Return strict JSON only.
Supported intents: create_expense, create_income, create_task, create_tasks, finance_analysis, task_query, unknown.
Languages: ru, en.
Current date: {$date}.
Do not calculate totals.
Keep original language for note/title/description.
User text: {$text}
PROMPT;
    }
}
