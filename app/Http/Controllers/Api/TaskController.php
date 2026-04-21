<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\Tasks\TaskService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection(Task::query()->where('user_id', auth()->id())->latest()->paginate());
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        return new TaskResource($this->taskService->create($request->user(), $request->validated()));
    }

    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);
        $task->update($request->validated());
        return new TaskResource($task->fresh());
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->noContent();
    }

    public function complete(Task $task): TaskResource
    {
        $this->authorize('update', $task);
        return new TaskResource($this->taskService->complete($task));
    }

    public function cancel(Task $task): TaskResource
    {
        $this->authorize('update', $task);
        return new TaskResource($this->taskService->cancel($task));
    }
}
