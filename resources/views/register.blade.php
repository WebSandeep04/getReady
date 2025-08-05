@extends('layouts.app-auth')

@section('title', 'Sign Up - Get Ready')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="css/register.css">
@endsection

@section('content')
<div class="signup-page">
  <div class="overlay"></div>
<div class="container h-100">
  <div class="row h-100 align-items-center">
    <div class="col-lg-6"></div> <!-- Left side empty -->
    <div class="col-lg-6 d-flex justify-content-center">
      <div class="signup-form text-center">
        <h2 class="text-warning mb-4">SIGN UP</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group mb-3 position-relative">
                <input type="text" name="name" class="form-control" placeholder="Enter your Name" value="{{ old('name') }}">
                <i class="bi bi-person icon"></i>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3 position-relative">
                <input type="email" name="email" class="form-control" placeholder="Enter your E-Mail" value="{{ old('email') }}">
                <i class="bi bi-envelope icon"></i>
                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3 position-relative">
                <input type="tel" name="phone" class="form-control" placeholder="Enter your Mobile No" value="{{ old('phone') }}">
                <i class="bi bi-telephone icon"></i>
                @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3 position-relative">
                <input type="text" name="address" class="form-control" placeholder="Enter your Address" value="{{ old('address') }}">
                <i class="bi bi-geo-alt icon"></i>
                @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <!-- Gender selection: Male & Female only, no label -->
            <div class="form-group mb-3 text-start">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline ms-5">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                    <label class="form-check-label" for="female">Female</label>
                </div>
                @error('gender')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3 position-relative">
                <input type="password" name="password" class="form-control" placeholder="Create your password">
                <i class="bi bi-lock icon"></i>
                @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3 position-relative">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter your password">
                <i class="bi bi-lock icon"></i>
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-bold">Create Your Account</button>
        </form>
      </div>
    </div>
  </div>
</div>

</div>
@endsection
