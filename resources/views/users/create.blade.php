@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Add User</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<form method="POST" action="{{ route('users.store') }}" class="card shadow-sm">
    @csrf
    <div class="card-body">@include('users._form', ['user' => null])</div>
    <div class="card-footer text-end"><button class="btn btn-primary">Create User</button></div>
</form>
@endsection
