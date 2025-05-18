@extends('layouts.master')

@section('title', 'Employee')

@push('styles')
    <style>
        .password.active{
            filter: blur(4px);
            transition: 0.2s ease-in;
        }
        .password{
            filter: blur(0px);
            transition: 0.2s ease-in;
        }
        .eye-password{
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <x-navbar title_page="Employee">
        <div class="p-2">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="p-0 m-0">
                        @foreach ($errors->all() as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between w-100 align-items-center">
                    <h5 class="text-bold opacity-7">Employe Account List</h5>
                    <button class="btn btn-sm bg-gradient-info" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fa-solid fa-user text-sm"></i> Add New User
                    </button>
                </div>
                <div class="card-body">
                    @include('admin.employee.partials.table')
                </div>
            </div>
        </div>
        @include('admin.employee.partials.form-create')
    </x-navbar>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-employee').DataTable({
                scrollX: true,
                responsive: false
            });

            $('.eye-password').on('click', function() {
                var el = $(this);
                var password = el.prev().toggleClass('active');
                el.toggleClass('opacity-0');
            });
        });
    </script>
@endpush
