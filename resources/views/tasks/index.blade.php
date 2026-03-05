@extends('layouts.app')

@section('title', 'Tasks')
@section('navbar-title', 'Tasks')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h4 text-white mb-0">Task List</h1>
    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#taskModal" data-mode="create">
        <i class="bi bi-plus-lg me-1"></i>New Task
    </button>
</div>

<div class="mb-3">
    <input type="search" class="form-control form-control-sm" id="taskSearch" placeholder="Search tasks..." autocomplete="off" style="max-width: 280px;">
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th class="d-none d-md-table-cell">Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th class="d-none d-lg-table-cell">Due date</th>
                        <th class="text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tasksTableBody">
                    @forelse($tasks as $task)
                    <tr data-task-id="{{ $task->id }}">
                        <td>
                            <span class="fw-medium">{{ $task->title }}</span>
                            @if($task->description)
                                <br><small class="text-muted text-truncate d-inline-block" style="max-width: 200px;">{{ Str::limit($task->description, 40) }}</small>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">{{ $task->category ?: '—' }}</td>
                        <td>
                            @php
                                $priorityBadge = match($task->priority) {
                                    'high' => 'danger',
                                    'medium' => 'warning',
                                    'low' => 'secondary',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $priorityBadge }} {{ $priorityBadge === 'warning' ? 'text-dark' : '' }}">{{ ucfirst($task->priority) }}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none task-toggle-status" data-task-id="{{ $task->id }}" data-task-status="{{ $task->status }}" title="Toggle status">
                                <span class="badge status-badge bg-{{ $task->status === 'completed' ? 'success' : 'warning text-dark' }}">{{ ucfirst($task->status) }}</span>
                            </button>
                        </td>
                        <td class="d-none d-lg-table-cell">{{ $task->due_date?->format('M j, Y') ?: '—' }}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-outline-primary btn-sm btn-edit-task" data-bs-toggle="modal" data-bs-target="#taskModal" data-mode="edit"
                                data-id="{{ $task->id }}"
                                data-title="{{ e($task->title) }}"
                                data-description="{{ e($task->description ?? '') }}"
                                data-priority="{{ $task->priority }}"
                                data-category="{{ e($task->category ?? '') }}"
                                data-status="{{ $task->status }}"
                                data-due-date="{{ $task->due_date?->format('Y-m-d') }}"
                                data-reminder-time="{{ $task->reminder_time?->format('Y-m-d\TH:i') }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline task-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="tasksEmptyRow">
                        <td colspan="6" class="text-center text-muted py-4">No tasks yet. <button type="button" class="btn btn-link p-0 align-baseline" data-bs-toggle="modal" data-bs-target="#taskModal" data-mode="create">Create one</button>.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tasks->hasPages())
        <div class="card-footer bg-transparent border-0" id="tasksPagination">
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Create / Edit modal --}}
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="taskForm" method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_method" id="taskFormMethod" value="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required maxlength="255">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" maxlength="5000">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority">
                                <option value="low" {{ old('priority', 'medium') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', 'medium') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" maxlength="100">
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-sm-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="due_date" class="form-label">Due date</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-0 mt-2">
                        <label for="reminder_time" class="form-label">Reminder</label>
                        <input type="datetime-local" class="form-control @error('reminder_time') is-invalid @enderror" id="reminder_time" name="reminder_time" value="{{ old('reminder_time') }}">
                        @error('reminder_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const tasksIndexUrl = '{{ route("tasks.index") }}';
    const taskToggleUrl = (id) => '{{ url("tasks") }}/' + id + '/toggle';
    const taskDestroyUrl = (id) => '{{ url("tasks") }}/' + id;
    const storeUrl = '{{ route("tasks.store") }}';

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function formatDueDate(iso) {
        if (!iso) return '—';
        const d = new Date(iso);
        return isNaN(d.getTime()) ? '—' : d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function priorityClass(p) {
        return { high: 'danger', medium: 'warning', low: 'secondary' }[p] || 'secondary';
    }

    function buildTaskRow(task) {
        const priorityCls = priorityClass(task.priority);
        const statusCls = task.status === 'completed' ? 'success' : 'warning text-dark';
        const desc = task.description ? (task.description.length > 40 ? task.description.slice(0, 40) + '...' : task.description) : '';
        const due = formatDueDate(task.due_date);
        return '<tr data-task-id="' + task.id + '">' +
            '<td><span class="fw-medium">' + escapeHtml(task.title) + '</span>' +
            (desc ? '<br><small class="text-muted text-truncate d-inline-block" style="max-width:200px">' + escapeHtml(desc) + '</small>' : '') + '</td>' +
            '<td class="d-none d-md-table-cell">' + escapeHtml(task.category || '—') + '</td>' +
            '<td><span class="badge bg-' + priorityCls + (priorityCls === 'warning' ? ' text-dark' : '') + '">' + (task.priority ? task.priority.charAt(0).toUpperCase() + task.priority.slice(1) : '') + '</span></td>' +
            '<td><button type="button" class="btn btn-link btn-sm p-0 text-decoration-none task-toggle-status" data-task-id="' + task.id + '" data-task-status="' + task.status + '" title="Toggle status">' +
            '<span class="badge status-badge bg-' + statusCls + (statusCls === 'warning' ? ' text-dark' : '') + '">' + (task.status ? task.status.charAt(0).toUpperCase() + task.status.slice(1) : '') + '</span></button></td>' +
            '<td class="d-none d-lg-table-cell">' + due + '</td>' +
            '<td class="text-end">' +
            '<button type="button" class="btn btn-outline-primary btn-sm btn-edit-task" data-bs-toggle="modal" data-bs-target="#taskModal" data-mode="edit" ' +
            'data-id="' + task.id + '" data-title="' + escapeHtml(task.title) + '" data-description="' + escapeHtml(task.description || '') + '" data-priority="' + (task.priority || 'medium') + '" ' +
            'data-category="' + escapeHtml(task.category || '') + '" data-status="' + (task.status || 'pending') + '" data-due-date="' + (task.due_date ? task.due_date.slice(0, 10) : '') + '" data-reminder-time="' + (task.reminder_time ? task.reminder_time.slice(0, 16) : '') + '"><i class="bi bi-pencil"></i></button> ' +
            '<form action="' + taskDestroyUrl(task.id) + '" method="POST" class="d-inline task-delete-form">' +
            '<input type="hidden" name="_token" value="' + csrf + '"><input type="hidden" name="_method" value="DELETE">' +
            '<button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button></form></td></tr>';
    }

    // ——— Modal (create/edit) ———
    const modal = document.getElementById('taskModal');
    const form = document.getElementById('taskForm');
    const formMethod = document.getElementById('taskFormMethod');
    const modalLabel = document.getElementById('taskModalLabel');
    if (modal && form) {
        modal.addEventListener('show.bs.modal', function (e) {
            const trigger = e.relatedTarget;
            const mode = trigger && trigger.getAttribute('data-mode');
            if (mode === 'edit') {
                modalLabel.textContent = 'Edit Task';
                form.action = '{{ url("tasks") }}/' + trigger.getAttribute('data-id');
                formMethod.value = 'PUT';
                document.getElementById('title').value = trigger.getAttribute('data-title') || '';
                document.getElementById('description').value = trigger.getAttribute('data-description') || '';
                document.getElementById('priority').value = trigger.getAttribute('data-priority') || 'medium';
                document.getElementById('category').value = trigger.getAttribute('data-category') || '';
                document.getElementById('status').value = trigger.getAttribute('data-status') || 'pending';
                document.getElementById('due_date').value = trigger.getAttribute('data-due-date') || '';
                document.getElementById('reminder_time').value = trigger.getAttribute('data-reminder-time') || '';
            } else {
                modalLabel.textContent = 'New Task';
                form.action = storeUrl;
                formMethod.value = 'POST';
                form.reset();
                document.getElementById('priority').value = 'medium';
                document.getElementById('status').value = 'pending';
            }
        });
    }

    // ——— Toggle status (AJAX) ———
    document.getElementById('tasksTableBody')?.addEventListener('click', function (e) {
        const btn = e.target.closest('.task-toggle-status');
        if (!btn) return;
        e.preventDefault();
        const id = btn.getAttribute('data-task-id');
        const badge = btn.querySelector('.status-badge');
        if (!id || !badge) return;
        fetch(taskToggleUrl(id), {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({})
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                badge.className = 'badge status-badge bg-' + (data.status === 'completed' ? 'success' : 'warning text-dark');
                badge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                btn.setAttribute('data-task-status', data.status);
            })
            .catch(function () { alert('Failed to update status'); });
    });

    // ——— Delete without reload (AJAX) ———
    document.getElementById('tasksTableBody')?.addEventListener('submit', function (e) {
        const f = e.target;
        if (!f.classList.contains('task-delete-form')) return;
        e.preventDefault();
        if (!confirm('Delete this task?')) return;
        const row = f.closest('tr');
        const id = row?.getAttribute('data-task-id');
        if (!id) return;
        fetch(taskDestroyUrl(id), {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) {
                if (!r.ok) throw new Error('Delete failed');
                row.remove();
            })
            .catch(function () { alert('Failed to delete'); });
    });

    // ——— Live search ———
    var searchTimer;
    var paginationEl = document.getElementById('tasksPagination');
    document.getElementById('taskSearch')?.addEventListener('input', function () {
        var q = this.value.trim();
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            if (!q) {
                window.location.href = tasksIndexUrl;
                return;
            }
            fetch(tasksIndexUrl + '?search=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var tbody = document.getElementById('tasksTableBody');
                    if (!tbody) return;
                    if (data.tasks.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No tasks match your search.</td></tr>';
                    } else {
                        tbody.innerHTML = data.tasks.map(buildTaskRow).join('');
                    }
                    if (paginationEl) paginationEl.style.display = 'none';
                })
                .catch(function () {});
        }, 300);
    });
})();
</script>
@endpush
