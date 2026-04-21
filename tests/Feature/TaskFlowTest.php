<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_can_be_completed_and_cancelled(): void
    {
        $user = User::factory()->create();
        $task = Task::query()->create([
            'user_id' => $user->id,
            'title' => 'Buy groceries tonight',
            'description' => 'tonight',
            'status' => 'pending',
            'priority' => 'normal',
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/tasks/{$task->id}/complete")
            ->assertOk()
            ->assertJsonPath('data.status', 'completed');

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/tasks/{$task->id}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status', 'cancelled');
    }
}
