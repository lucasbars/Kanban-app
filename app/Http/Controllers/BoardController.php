<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = auth()->user()->boards()->latest()->get();
        return view('boards.index', compact('boards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        auth()->user()->boards()->create($request->only('name', 'description'));

        return response()->json(['success' => true]);
    }

    public function show(Board $board)
    {
        $this->authorize('view', $board);

        $columns = $board->columns()->with('tasks')->get();
        return view('boards.show', compact('board', 'columns'));
    }

    public function update(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $board->update($request->only('name', 'description'));

        return response()->json(['success' => true]);
    }

    public function destroy(Board $board)
    {
        $this->authorize('delete', $board);

        $board->delete();

        return response()->json(['success' => true]);
    }
}
