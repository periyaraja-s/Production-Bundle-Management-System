@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit User</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<form method="POST" action="{{ route('users.update', $user) }}" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">@include('users._form', ['user' => $user])</div>
    <div class="card-footer text-end"><button class="btn btn-primary">Save Changes</button></div>
</form>
@endsection
