<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $stats = [
            'total' => $user->tasks()->count(),
            'completed' => $user->tasks()->where('status', 'completed')->count(),
            'pending' => $user->tasks()->where('status', 'pending')->count(),
            'overdue' => $user->tasks()->where('status', 'pending')->whereNotNull('due_date')->where('due_date', '<', now()->startOfDay())->count(),
        ];
        return view('dashboard', compact('stats'));
    })->name('dashboard');

    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');
    Route::resource('tasks', TaskController::class);
});
