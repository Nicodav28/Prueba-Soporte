<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Services\Contracts\IStandardContract;
use App\Traits\ResponseHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;


class TaskService implements IStandardContract
{
    use ResponseHandler;

    /**
     * Get all the tasks with corresponding user
     *
     * @param  mixed $paginate
     * @return LengthAwarePaginator
     */
    public function getAll(int $paginate): LengthAwarePaginator
    {
        return Task::with('user')->paginate($paginate);
    }

    /**
     * store a new task
     * 
     *
     * @param  array $request
     * @return RedirectResponse
     */
    public function create(array $validated): RedirectResponse
    {
        try {
            $user = User::where('email', $validated['user'])->firstOrFail();

            $user->tasks()->create([
                'title'       => $validated['title'],
                'description' => $validated['description'],
            ]);

            return $this->redirectWithSuccess('Task created successfully.', __METHOD__, self::class);
        } catch (ModelNotFoundException $e) {
            return $this->redirectWithError('Task could not be created, user not found.', __METHOD__, self::class);
        } catch (\Exception $e) {
            return $this->redirectWithError('Ups!, an error just happened, please notify this issue to the customer support team.', __METHOD__, self::class);
        }
    }

    /**
     * update an existing task
     *
     * @param string|int $id
     * @param array $data
     * @return RedirectResponse
     */
    public function update(string|int $id, array $data): RedirectResponse
    {
        try {
            $task = Task::findOrFail($id);

            $task->update($data);

            return $this->redirectWithSuccess('Task updated successfully.', __METHOD__, self::class, 201);
        } catch (ModelNotFoundException $e) {
            return $this->redirectWithError('Task not found.', __METHOD__, self::class);
        } catch (\Exception $e) {
            return $this->redirectWithError('Ups!, an error just happened, please notify this issue to the customer support team.', __METHOD__, self::class);
        }
    }

    /**
     * destroy an existing task
     *
     * @param string|int $id
     * @return RedirectResponse
     */
    public function delete(int|string $id): RedirectResponse
    {
        try {
            Task::findOrFail($id)->delete();

            return $this->redirectWithSuccess('Task deleted successfully.', __METHOD__, self::class);
        } catch (ModelNotFoundException $e) {
            return $this->redirectWithError('Task not found.', __METHOD__, self::class);
        } catch (\Exception $e) {
            return $this->redirectWithError('Ups!, an error just happened, please notify this issue to the customer support team.', __METHOD__, self::class);
        }
    }

}
