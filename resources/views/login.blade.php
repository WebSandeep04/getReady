@extends('layouts.app-auth')

@section('title', 'Login - Get Ready')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="css/login.css">
@endsection

@section('content')
<div class="login-page">
  <div class="overlay"></div>
  <div class="login-overlay">
    <div class="login-form">
      <h2 class="login-title">LOGIN</h2>

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group position-relative mb-3">
          <input type="email" name="email" class="form-control" placeholder="Enter Your E-Mail" value="{{ old('email') }}">
          <i class="bi bi-envelope icon"></i>
          @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="form-group position-relative mb-3">
          <input type="password" name="password" class="form-control" placeholder="Enter your Password">
          <i class="bi bi-lock icon"></i>
          @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="rememberMe">Remember me</label>
          </div>
          <a href="#" class="forgot">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-login w-100 text-white">Login with Email</button>
        <p class="text-center mt-3 text-white">Don't have an account? <a href="{{ route('register') }}" class="text-white">Register</a></p>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="login.js"></script>
@endsection
