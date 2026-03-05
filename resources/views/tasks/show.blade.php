@extends('layouts.app')

@section('title','Task Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Task Details</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary btn-sm">Edit</a>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h4 class="card-title mb-2">{{ $task->title }}</h4>
            @if($task->description)
                <p class="text-muted mb-3">{{ $task->description }}</p>
            @endif

            <dl class="row mb-0">
                <dt class="col-sm-3">Category</dt>
                <dd class="col-sm-9">{{ $task->category ?: '—' }}</dd>

                <dt class="col-sm-3">Priority</dt>
                <dd class="col-sm-9 text-capitalize">{{ $task->priority }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9 text-capitalize">{{ $task->status }}</dd>

                <dt class="col-sm-3">Due date</dt>
                <dd class="col-sm-9">{{ $task->due_date?->format('M j, Y') ?: '—' }}</dd>

                <dt class="col-sm-3">Reminder</dt>
                <dd class="col-sm-9">{{ $task->reminder_time?->format('M j, Y g:i A') ?: '—' }}</dd>

                <dt class="col-sm-3">Created at</dt>
                <dd class="col-sm-9">{{ $task->created_at->format('M j, Y g:i A') }}</dd>

                <dt class="col-sm-3">Updated at</dt>
                <dd class="col-sm-9">{{ $task->updated_at->format('M j, Y g:i A') }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection

