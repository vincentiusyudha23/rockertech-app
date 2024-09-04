@extends('layouts.master')

@section('title', 'Login')

@push('styles')
    <style>
        .login-side{
            width: 50%;
        }
        @media (max-width: 991.98px) {
            .login-side{
                width: 70%;
            }
        }
        @media (max-width: 575.98px) {
            .login-side{
                width: 100%;
                padding: 0 20px;
            }
        }
    </style>
@endpush

@section('content')
<div class="w-100 vh-100">
    <div class="row h-100">
        <div class="d-none col-lg-6 h-100 text-center d-lg-flex flex-column justify-content-center align-items-center" style="background-color: rgb(45, 90, 191);">
            <img src="{{ assets('img/login.png') }}" width="70%">
            <h5 class="text-white m-0">PT. Rocker Technology Innvovation</h5>
            <p class="text-white font-weight-bold m-0">- Work Hard, Pray Hard -</p>
        </div>
        <div class="col-12 col-lg-6 d-flex justify-content-center align-items-center">
            <div class="login-side">
                <div class="w-100 text-center mb-4">
                    <img src="{{ assets('img/logo-2.png') }}" style="width: 80px; height: auto;">
                    <h3 class="m-0" style="color: #2d5abf;">Welcome Back</h3>
                    <p class="m-0 text-sm font-weight-bold">Enter your account admin to sign in</p>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger text-white" role="alert">
                        {{ $errors->get('username')[0] ?? null }}
                        {{ $errors->get('password')[0] ?? null }}
                    </div>
                @endif
                <form role="form" method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <label>Username</label>
                    <div class="mb-3">
                        <input type="text" value="{{ old('username') }}" name="username" required class="form-control" placeholder="Username" aria-label="Username" aria-describedby="email-addon">
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="remember" type="checkbox" id="rememberMe" checked="">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn w-100 mt-4 mb-0 text-white text-md" style="background-color: #2d5abf;">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection