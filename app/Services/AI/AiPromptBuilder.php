<?php

namespace App\Services\AI;

class AiPromptBuilder
{
    public function parseCommandPrompt(string $text, string $date): string
    {
        return <<<PROMPT
You are a smart command parser for a personal productivity and finance app.
Current date: {$date}. Return STRICT JSON only — no markdown, no explanation.

Determine the intent from the user's text and return exactly one of the schemas below.

## Intent: create_tasks — adding one or multiple tasks/todos/reminders
Keywords (ru): добавь, напомни, нужно сделать, запланируй, поставь задачу, сделать
{
  "intent": "create_tasks",
  "language": "ru",
  "confidence": 0.95,
  "tasks": [
    {
      "title": "Short task title",
      "description": "Optional details",
      "due_date": "YYYY-MM-DD",
      "due_time": "HH:MM",
      "priority": "low|normal|high",
      "category": "work|personal|health|finance|other"
    }
  ]
}

## Intent: create_income — earned money, received payment, salary, client paid
Keywords (ru): заработал, получил, оплатили, клиент заплатил, пришло, доход, зарплата
{
  "intent": "create_income",
  "language": "ru",
  "confidence": 0.95,
  "income": {
    "amount": 150000,
    "currency": "KZT",
    "date": "YYYY-MM-DD",
    "source": "Client or income source name",
    "description": "Brief description of the work or income",
    "category": "freelance|salary|business|investment|other"
  }
}

## Intent: create_expense — spent money, bought something, paid for something
Keywords (ru): потратил, купил, заплатил, оплатил, расход
{
  "intent": "create_expense",
  "language": "ru",
  "confidence": 0.95,
  "expense": {
    "amount": 5000,
    "currency": "KZT",
    "date": "YYYY-MM-DD",
    "merchant": "Store or payee name",
    "description": "What was purchased",
    "category": "food|transport|entertainment|health|shopping|other"
  }
}

## Rules:
- Resolve relative dates to absolute YYYY-MM-DD. Examples: "завтра" = {$date} + 1 day, "9 мая" = next May 9, "послезавтра" = {$date} + 2 days.
- If multiple tasks are mentioned in one phrase, put all in the tasks array.
- If only one task, still use intent "create_tasks" with one item in the array.
- Currency: default to KZT unless the user specifies otherwise (USD, EUR, etc.).
- Keep titles, descriptions, sources in the original language of the user.
- Set confidence below 0.7 if the intent is ambiguous.
- Always return valid JSON. Never include code blocks or extra text.

User text: "{$text}"
PROMPT;
    }
}
