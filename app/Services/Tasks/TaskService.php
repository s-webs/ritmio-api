<?php

namespace App\Services\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Services\Categories\CategoryResolver;

class TaskService
{
    public function __construct(private readonly CategoryResolver $categoryResolver)
    {
    }

    public function create(User $user, array $data, bool $isAi = false): Task
    {
        $category = $this->categoryResolver->resolve($user, 'task', $data['category'] ?? 'other');

        return Task::query()->create([
            'user_id' => $user->id,
            'category_id' => $category?->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'raw_text' => $data['raw_text'] ?? null,
            'status' => $data['status'] ?? Task::STATUS_PENDING,
            'priority' => $data['priority'] ?? 'normal',
            'due_date' => !empty($data['due_date']) ? $data['due_date'] : null,
            'due_time' => !empty($data['due_time']) ? $data['due_time'] : null,
            'is_ai_generated' => $isAi,
            'needs_confirmation' => $isAi ? (($data['needs_confirmation'] ?? false) || (($data['confidence'] ?? 1) < 0.7)) : false,
            'ai_confidence' => $data['confidence'] ?? null,
        ]);
    }

    public function complete(Task $task): Task
    {
        $task->update(['status' => Task::STATUS_COMPLETED]);
        return $task->fresh();
    }

    public function cancel(Task $task): Task
    {
        $task->update(['status' => Task::STATUS_CANCELLED]);
        return $task->fresh();
    }
}
