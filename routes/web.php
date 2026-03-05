<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    $stats = [
        'total'     => $user?->tasks()->count() ?? 0,
        'completed' => $user?->tasks()->where('status', 'completed')->count() ?? 0,
        'pending'   => $user?->tasks()->where('status', 'pending')->count() ?? 0,
        'overdue'   => $user
            ? $user->tasks()
                ->where('status', 'pending')
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->toDateString())
                ->count()
            : 0,
    ];

    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');
    Route::resource('tasks', TaskController::class);
});
require __DIR__.'/auth.php';
