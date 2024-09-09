<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Services\Contracts\IStandardContract;
use App\Traits\ResponseHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;


class TaskService implements IStandardContract
{
    use ResponseHandler;

    /**
     * Get all the tasks with corresponding user
     *
     * @param  mixed $paginate
     * @return JsonResponse
     */
    public function getAll(int $paginate, ?string $filter): JsonResponse
    {
        $query = Task::with('user');

        if ($filter === 'completed') {
            $query->where('completed', true);
        } elseif ($filter === 'pending') {
            $query->where('completed', false);
        }

        $tasks = $query->paginate($paginate);

        return $this->successResponse(__METHOD__, self::class, 'Tasks indexed successfully.', 200, $tasks);
    }

    /**
     * store a new task
     * 
     *
     * @param  array $validated
     * @return JsonResponse
     */
    public function create(array $validated): JsonResponse
    {
        try {
            $user = User::where('email', $validated['user'])->firstOrFail();

            $newTask = $user->tasks()->create([
                'title'       => $validated['title'],
                'description' => $validated['description'],
            ]);

            $newTask->load('user');

            return $this->successResponse(__METHOD__, self::class, 'Task created successfully.', 200, $newTask);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(__METHOD__, self::class, 'Task could not be created, user not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse(__METHOD__, self::class, 'Ups!, an error just happened, please notify this issue to the customer support team.');
        }
    }

    /**
     * update an existing task
     *
     * @param string|int $id
     * @param array $data
     * @return JsonResponse
     */
    public function update(string|int $id, array $data): JsonResponse
    {
        try {
            $task = Task::with('user')->findOrFail($id);

            if (array_key_exists('completed', $data) && $task->completed) {
                return $this->successResponse(__METHOD__, self::class, 'Task already completed.');
            }

            $task->update($data);

            return $this->successResponse(__METHOD__, self::class, 'Task updated successfully.', 200, $task);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(__METHOD__, self::class, 'Task not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse(__METHOD__, self::class, 'Ups!, an error just happened, please notify this issue to the customer support team.');
        }
    }

    /**
     * destroy an existing task
     *
     * @param string|int $id
     * @return JsonResponse
     */
    public function delete(int|string $id): JsonResponse
    {
        try {
            Task::findOrFail($id)->delete();

            return $this->successResponse(__METHOD__, self::class, 'Task deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(__METHOD__, self::class, 'Task not found.', 404);

        } catch (\Exception $e) {
            return $this->errorResponse(__METHOD__, self::class, 'Ups!, an error just happened, please notify this issue to the customer support team.');
        }
    }

}
