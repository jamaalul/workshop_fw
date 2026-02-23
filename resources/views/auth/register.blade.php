@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <h4>New here?</h4>
    <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
    <form class="pt-3 forms-sample" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                id="name" name="name" value="{{ old('name') }}" 
                placeholder="Name" required autocomplete="name" autofocus>
            @error('name')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                id="email" name="email" value="{{ old('email') }}" 
                placeholder="Email" required autocomplete="email">
            @error('email')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                id="password" name="password" 
                placeholder="Password" required autocomplete="new-password">
            @error('password')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password-confirm">Confirm Password</label>
            <input type="password" class="form-control form-control-lg" 
                id="password-confirm" name="password_confirmation" 
                placeholder="Confirm Password" required autocomplete="new-password">
        </div>
        <div class="mt-3 d-grid gap-2">
            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                SIGN UP
            </button>
        </div>
        <div class="text-center mt-4 font-weight-light"> Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
        </div>
    </form>
@endsection
