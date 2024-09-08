<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{

    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * store a new task
     *
     * @param  StoreTaskRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        return $this->taskService->create($request->validated());
    }

    /**
     * update an existing task
     *
     * @param  int|string $id
     * @param  UpdateTaskRequest $request
     * @return RedirectResponse
     */
    public function update(int|string $id, UpdateTaskRequest $request): RedirectResponse
    {
        return $this->taskService->update($id, $request->validated());
    }

    /**
     * destroy an existing task
     *
     * @param  int|string $id
     * @return RedirectResponse
     */
    public function destroy(int|string $id): RedirectResponse
    {
        return $this->taskService->delete($id);
    }
}
