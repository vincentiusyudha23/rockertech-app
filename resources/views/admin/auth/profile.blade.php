@extends('layouts.master')

@section('title', 'Profile')

@section('content')
    <x-navbar title_page="Profile">
        <div class="container-fluid px-lg-6 py-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="text-secondary">Change Password</h5>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="p-2 text-white m-0">
                                @foreach ($errors->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route(route_prefix().'change_password') }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-group mb-3">
                            <label class="form-control-label">Username</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="username" value="{{ auth()->user()->username }}" readonly>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-control-label" for="update_password_current_password">Current Password<sup class="text-danger">*</sup></label>
                            <div class="input-group">
                                <input class="form-control input-password" name="current_password" type="password" id="update_password_current_password" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-control-label" for="password">Password<sup class="text-danger">*</sup></label>
                            <div class="input-group">
                                <input class="form-control input-password" name="password" type="password" id="password" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-control-label" for="confirm_password">Confirmation Password<sup class="text-danger">*</sup></label>
                            <div class="input-group">
                                <input class="form-control input-password" name="password_confirmation" autocomplete="new-password" type="password" id="confirm_password" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check form-switch ps-0">
                                <input class="form-check-input ms-auto" type="checkbox" id="show_password">
                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="show_password">Show Password</label>
                            </div>
                        </div>
                        <div class="w-100 d-flex justify-content-end">
                            <button class="btn bg-gradient-info">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#show_password').change(function(){
                var tag = $('.input-password');
                if($(this).is(':checked')){
                    tag.attr('type', 'text');
                }else{
                    tag.attr('type', 'password');
                }

            });
        });
    </script>
@endpush