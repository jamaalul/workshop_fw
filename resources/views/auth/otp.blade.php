@extends('layouts.auth')

@section('title', 'OTP Verification')

@section('content')
    <h4>OTP Verification</h4>
    <h6 class="font-weight-light">Please enter the 6-digit code sent to your email.</h6>

    @if (session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form class="pt-3 forms-sample" method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <div class="form-group">
            <label for="otp">Verification Code</label>
            <input type="text" class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" 
                id="otp" name="otp" 
                placeholder="000000" maxlength="6" required autofocus
                style="font-size: 1.5rem; letter-spacing: 0.3rem;">
            @error('otp')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mt-3 d-grid gap-2">
            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                VERIFY OTP
            </button>
        </div>
        <div class="text-center mt-4 font-weight-light"> 
            Haven't received it? <a href="#" class="text-primary">Resend (Not Implemented)</a>
        </div>
    </form>
@endsection
