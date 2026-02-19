<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ColumnController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $order = $board->columns()->count();

        $board->columns()->create([
            'name' => $request->name,
            'order' => $order,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Column $column)
    {
        $this->authorize('update', $column->board);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $column->update($request->only('name'));

        return response()->json(['success' => true]);
    }

    public function destroy(Column $column)
    {
        $this->authorize('update', $column->board);

        $column->delete();

        return response()->json(['success' => true]);
    }
}
