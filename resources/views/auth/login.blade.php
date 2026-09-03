@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-1">Sign in</h1>
                <p class="text-muted mb-4">Production Bundle Management</p>
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input id="password" name="password" type="password" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input id="remember" name="remember" type="checkbox" class="form-check-input" value="1">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
