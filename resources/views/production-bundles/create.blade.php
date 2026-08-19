@extends('layouts.app')

@section('title', 'Create Production Bundle')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Create Production Bundle</h1>
        <a href="{{ route('production-bundles.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>

    <form action="{{ route('production-bundles.store') }}" method="POST" class="card shadow-sm">
        @csrf
        <div class="card-body">@include('production-bundles._form')</div>
        <div class="card-footer text-end"><button type="submit" class="btn btn-primary">Save Bundle</button></div>
    </form>
@endsection
