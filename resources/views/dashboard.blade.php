@extends('layouts.app')

@section('title', 'Dashboard')
@section('navbar-title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 text-white mb-0">Overview</h1>
</div>

<div class="row g-3">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card total h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-inbox"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card completed h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['completed'] ?? 0 }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card pending h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card overdue h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['overdue'] ?? 0 }}</div>
                    <div class="stat-label">Overdue</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
