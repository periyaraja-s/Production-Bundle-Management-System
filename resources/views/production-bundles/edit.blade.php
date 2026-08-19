@extends('layouts.app')

@section('title', 'Edit Production Bundle')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Production Bundle</h1>
        <a href="{{ route('production-bundles.show', $productionBundle) }}" class="btn btn-outline-secondary">Cancel</a>
    </div>

    <form id="production-bundle-form" action="{{ route('production-bundles.update', $productionBundle) }}" method="POST" class="card shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">@include('production-bundles._form')</div>
        <div class="card-footer text-end"><button type="submit" class="btn btn-primary">Update Bundle</button></div>
    </form>
@endsection
