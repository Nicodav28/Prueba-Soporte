<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{

    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * Get all the tasks with corresponding user
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->taskService->getAll(10);
    }

    /**
     * store a new task
     *
     * @param  StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        return $this->taskService->create($request->validated());
    }

    /**
     * update an existing task
     *
     * @param  int|string $id
     * @param  UpdateTaskRequest $request
     * @return JsonResponse
     */
    public function update(int|string $id, UpdateTaskRequest $request): JsonResponse
    {
        return $this->taskService->update($id, $request->validated());
    }

    /**
     * destroy an existing task
     *
     * @param  int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id): JsonResponse
    {
        return $this->taskService->delete($id);
    }
}
