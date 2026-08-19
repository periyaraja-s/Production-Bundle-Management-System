@extends('layouts.app')

@section('title', 'Production Bundle Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Production Bundle Dashboard</h1>
            <p class="text-muted mb-0">Current production summary</p>
        </div>
        <a href="{{ route('production-bundles.create') }}" class="btn btn-primary">Create Bundle</a>
    </div>

    <div class="row g-3">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <p class="text-muted text-uppercase small mb-2">Total Bundles</p>
                <p class="display-6 mb-0">{{ number_format($metrics->total_bundles) }}</p>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <p class="text-muted text-uppercase small mb-2">Total Quantity</p>
                <p class="display-6 mb-0">{{ number_format($metrics->total_quantity) }}</p>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <p class="text-muted text-uppercase small mb-2">Total Completed</p>
                <p class="display-6 mb-0 text-success">{{ number_format($metrics->total_completed) }}</p>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <p class="text-muted text-uppercase small mb-2">Total Rejected</p>
                <p class="display-6 mb-0 text-danger">{{ number_format($metrics->total_rejected) }}</p>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <p class="text-muted text-uppercase small mb-2">Average Efficiency</p>
                <p class="display-6 mb-0">{{ number_format((float) $metrics->average_efficiency, 2) }}%</p>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <p class="text-muted text-uppercase small mb-2">Today's Production</p>
                <p class="display-6 mb-0">{{ number_format($metrics->today_production) }}</p>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <p class="text-muted text-uppercase small mb-2">Today's Rejection</p>
                <p class="display-6 mb-0 text-danger">{{ number_format($metrics->today_rejection) }}</p>
            </div></div>
        </div>
    </div>
@endsection
