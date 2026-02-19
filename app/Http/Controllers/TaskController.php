<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Column $column)
    {
        $this->authorize('update', $column->board);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $order = $column->tasks()->count();

        $column->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'order' => $order,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task->column->board);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update($request->only('title', 'description'));

        return response()->json(['success' => true]);
    }

    public function destroy(Task $task)
    {
        $this->authorize('update', $task->column->board);

        $task->delete();

        return response()->json(['success' => true]);
    }

    public function move(Request $request, Task $task)
    {
        $this->authorize('update', $task->column->board);

        $request->validate([
            'column_id' => 'required|exists:columns,id',
            'order' => 'required|array',
        ]);

        $task->update(['column_id' => $request->column_id]);

        // Reordena todas as tasks da coluna destino
        foreach ($request->order as $index => $taskId) {
            Task::where('id', $taskId)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
