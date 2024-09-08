<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function __construct(private TaskService $taskService)
    {
    }


    public function store(StoreTaskRequest $request): RedirectResponse
    {
        return $this->taskService->create($request->validated());
    }

    // Actualizar tarea
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required|max:500',
        ]);

        $task = Task::find($id);

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }

        // Corrección: Se actualiza la tarea con datos validados.
        $task->update($validated);
        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    // Eliminar tarea
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }

        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
