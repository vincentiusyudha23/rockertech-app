@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="w-100 overflow-hidden vh-100 d-flex justify-content-center align-items-center">
    <div class="w-100 position-absolute top-0 left-0 p-3" style="height: 60vh;">
        <div class="page-header w-100 h-100 rounded-3" style="background-image: url('{{ assets('img/curved-images/curved14.jpg') }}')">
            <span class="mask bg-gradient-dark opacity-5"></span>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                <div class="card card-plain shadow p-2 bg-body border">
                    <div class="card-header pb-0 text-left bg-transparent">
                        <img src="{{ assets('img/logo-1.png') }}" width="200" height="auto" class="mb-2">
                        <p class="mb-0">Enter your account to sign in</p>
                    </div>
                    <div class="card-body">
                        {{-- ALERT TAG --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger text-white" role="alert">
                                {{ $errors->get('username')[0] ?? null }}
                                {{ $errors->get('password')[0] ?? null }}
                            </div>
                        @endif
                        {{-- FORM LOGIN --}}
                        <form role="form" method="POST" action="{{ route('employe.login') }}">
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
                                <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection