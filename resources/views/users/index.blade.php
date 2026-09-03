@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">User Management</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
</div>

<form method="GET" class="card shadow-sm mb-3">
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label" for="search">Search</label>
            <input id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name or email">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="role">Role</label>
            <select id="role" name="role" class="form-select">
                <option value="">All roles</option>
                @foreach (['admin', 'production', 'viewer'] as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button class="btn btn-primary">Apply</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Clear</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge text-bg-secondary">{{ ucfirst($user->role) }}</span></td>
                        <td><span class="badge text-bg-{{ $user->is_active ? 'success' : 'danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            @unless ($user->is(auth()->user()))
                                <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-{{ $user->is_active ? 'danger' : 'success' }}" type="submit">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())
        <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>
@endsection
