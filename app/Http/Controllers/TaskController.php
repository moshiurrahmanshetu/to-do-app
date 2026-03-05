<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = $request->user()->tasks()->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qry) use ($q) {
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
            });
        }

        $tasks = $query->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['tasks' => $tasks->items(), 'total' => $tasks->total()]);
        }

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function show(Task $task): View
    {
        $this->authorizeTask($task);

        return view('tasks.show', compact('task'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', 'in:low,medium,high'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,completed'],
            'due_date' => ['nullable', 'date'],
            'reminder_time' => ['nullable', 'date'],
        ]);

        $request->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    public function edit(Task $task): View
    {
        $this->authorizeTask($task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeTask($task);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', 'in:low,medium,high'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,completed'],
            'due_date' => ['nullable', 'date'],
            'reminder_time' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse|JsonResponse
    {
        $this->authorizeTask($task);
        $task->delete();
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Task deleted.']);
        }
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function toggleStatus(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTask($task);

        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed',
        ]);

        return response()->json([
            'status' => $task->status,
            'message' => 'Status updated.',
        ]);
    }

    private function authorizeTask(Task $task): void
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
