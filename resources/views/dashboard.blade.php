@extends('layouts.app')

@section('title', 'Dashboard')
@section('navbar-title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 text-white mb-0">Overview</h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Task
    </a>
</div>

<div class="row g-3">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card total h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex">
                    <span class="stat-label">Total Tasks</span>
                    <span class="stat-value ml-5">{{ $stats['total'] ?? 0 }}</span>
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
                <div class="d-flex">
                    <span class="stat-label">Completed</span>
                    <span class="stat-value ml-5">{{ $stats['completed'] ?? 0 }}</span>
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
                <div class="d-flex">
                    <span class="stat-label">Pending</span>
                    <span class="stat-value ml-5">{{ $stats['pending'] ?? 0 }}</span>
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
                <div class="d-flex">
                    <span class="stat-label">Overdue</span>
                    <span class="stat-value ml-5">{{ $stats['overdue'] ?? 0 }}</span>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .ml-5{
        margin-left: 1.25rem;
    }
</style>