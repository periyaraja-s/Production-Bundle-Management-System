@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Name</label>
        <input id="name" name="name" class="form-control" value="{{ old('name', $user?->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="email">Email</label>
        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user?->email) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="role">Role</label>
        <select id="role" name="role" class="form-select" required>
            @foreach (['admin', 'production', 'viewer'] as $role)
                <option value="{{ $role }}" @selected(old('role', $user?->role ?? 'viewer') === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="password">Password{{ $user ? ' (leave blank to keep current)' : '' }}</label>
        <input id="password" name="password" type="password" class="form-control" {{ $user ? '' : 'required' }} minlength="8">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" {{ $user ? '' : 'required' }} minlength="8">
    </div>
</div>
