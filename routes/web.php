<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('boards', BoardController::class);
    Route::resource('boards.columns', ColumnController::class)->shallow();
    Route::resource('columns.tasks', TaskController::class)->shallow();

    // Rotas para mover tasks (drag and drop)
    Route::post('/tasks/{task}/move', [TaskController::class, 'move'])->name('tasks.move');
});
require __DIR__ . '/auth.php';
