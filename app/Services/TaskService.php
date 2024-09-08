<?php

namespace App\Services;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\Contracts\IStandardContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskService implements IStandardContract
{
    /**
     * Crear tarea
     *
     * @param  StoreTaskRequest $request
     * @return RedirectResponse
     */
    public function create(array $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Task could not be created, user not found.');
        }

        $user->tasks()->create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function update(string $id, array $data): RedirectResponse
    {
        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function delete(int|string $id): RedirectResponse
    {
        return redirect()->back()->with('success', 'Task created successfully.');
    }

}
