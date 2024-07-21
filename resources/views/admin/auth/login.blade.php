@extends('layouts.master')

@push('styles')
    <style>
        .bg-login{
            background: linear-gradient(310deg, #ddd,);
        }
    </style>
@endpush

@section('content')
<div class="w-100 o-hidden vh-100 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                <div class="card card-plain shadow p-2 bg-body border">
                    <div class="card-header pb-0 text-left bg-transparent">
                        <img src="{{ assets('img/logo-1.png') }}" width="200" height="auto" class="mb-2">
                        <p class="mb-0">Enter your account to sign in</p>
                    </div>
                    <div class="card-body">
                        {{-- <div class="alert alert-danger text-white" role="alert">
                            <strong>Failed!</strong> This is a danger alert—check it out!
                        </div> --}}
                        <form role="form">
                            <label>Username</label>
                            <div class="mb-3">
                                <input type="text" required class="form-control" placeholder="Username" aria-label="Username" aria-describedby="email-addon">
                            </div>
                            <label>Password</label>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <p class="mb-4 text-sm mx-auto">
                        Don't have an account?
                        <a href="javascript:;" class="text-info text-gradient font-weight-bold">Sign up</a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection