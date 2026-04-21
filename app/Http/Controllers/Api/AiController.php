<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AI\ParseReceiptRequest;
use App\Http\Requests\AI\ParseTextRequest;
use App\Http\Requests\AI\ParseVoiceRequest;
use App\Http\Resources\AiParseResultResource;
use App\Models\AiInteraction;
use App\Models\Transaction;
use App\Models\UploadedFile;
use App\Services\AI\AiCommandParser;
use App\Services\Finance\TransactionService;
use App\Services\Tasks\TaskService;

class AiController extends Controller
{
    public function __construct(
        private readonly AiCommandParser $parser,
        private readonly TransactionService $transactionService,
        private readonly TaskService $taskService,
    ) {
    }

    public function parseText(ParseTextRequest $request): AiParseResultResource
    {
        $parsed = $this->parser->parse($request->string('text')->toString());
        return $this->handleParsed($parsed, $request->string('text')->toString(), null);
    }

    public function parseVoice(ParseVoiceRequest $request): AiParseResultResource
    {
        $path = $request->file('audio')->store('uploads/audio', 'public');
        $file = UploadedFile::query()->create([
            'user_id' => $request->user()->id,
            'type' => 'audio',
            'disk' => 'public',
            'path' => $path,
            'mime_type' => $request->file('audio')->getMimeType(),
            'size' => $request->file('audio')->getSize(),
        ]);
        $transcription = 'Voice transcription is not implemented yet';
        $parsed = $this->parser->parse($transcription);
        return $this->handleParsed($parsed, $transcription, $file);
    }

    public function parseReceipt(ParseReceiptRequest $request): AiParseResultResource
    {
        $path = $request->file('image')->store('uploads/receipts', 'public');
        $file = UploadedFile::query()->create([
            'user_id' => $request->user()->id,
            'type' => 'receipt',
            'disk' => 'public',
            'path' => $path,
            'mime_type' => $request->file('image')->getMimeType(),
            'size' => $request->file('image')->getSize(),
        ]);
        $stub = 'Analyze uploaded receipt';
        $parsed = $this->parser->parse($stub);
        return $this->handleParsed($parsed, $stub, $file);
    }

    private function handleParsed(array $parsed, string $rawText, ?UploadedFile $uploadedFile): AiParseResultResource
    {
        $user = auth()->user();
        $transaction = null;
        $tasks = [];
        if ($parsed['intent'] === 'create_expense' && ! empty($parsed['expense'])) {
            $transaction = $this->transactionService->create($user, [
                ...$parsed['expense'],
                'type' => Transaction::TYPE_EXPENSE,
                'raw_text' => $rawText,
            ], true);
        }
        if ($parsed['intent'] === 'create_income' && ! empty($parsed['income'])) {
            $transaction = $this->transactionService->create($user, [
                ...$parsed['income'],
                'type' => Transaction::TYPE_INCOME,
                'raw_text' => $rawText,
            ], true);
        }
        if (in_array($parsed['intent'], ['create_task', 'create_tasks'], true)) {
            foreach ($parsed['tasks'] ?? [] as $task) {
                $tasks[] = $this->taskService->create($user, [...$task, 'raw_text' => $rawText], true);
            }
        }

        $interaction = AiInteraction::query()->create([
            'user_id' => $user->id,
            'uploaded_file_id' => $uploadedFile?->id,
            'intent' => $parsed['intent'] ?? 'unknown',
            'language' => $parsed['language'] ?? null,
            'confidence' => $parsed['confidence'] ?? null,
            'raw_text' => $rawText,
            'model' => config('services.openai.model'),
            'response_payload' => $parsed,
        ]);

        return new AiParseResultResource([
            'parsed' => $parsed,
            'transaction' => $transaction,
            'tasks' => $tasks,
            'interaction_id' => $interaction->id,
        ]);
    }
}
