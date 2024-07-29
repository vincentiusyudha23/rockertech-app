@extends('layouts.master')

@section('title', 'Employee')

@push('styles')
    <style>
        .base-tap{
            position: relative;
        }
        .base-tap .tap-item{
            font-size: 10em;
        }
        .base-tap .tap-item-2{
            position: absolute;
            right: -30px;
            top: 10px;
            z-index: 2;
        }
    </style>
@endpush

@section('content')
    <x-navbar title_page="Employee">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 col-lg-9 order-last order-lg-first">
                    <div class="card p-2">
                        <div class="card-body px-0 pt-0 pb-2">
                            @include('admin.employee.partials.table')
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 mt-3 order-first order-lg-last">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-12">
                            <button type="button" class="btn w-100 btn-lg bg-white py-5" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="fa-solid fa-user fs-1 mb-2"></i>
                                <p class="font-weight-bold">Create Account</p>
                            </button>
                        </div>
                        <div class="col-12 col-md-6 col-lg-12">
                            <button type="button" class="btn w-100 btn-lg bg-white py-5" data-bs-toggle="modal"
                                data-bs-target="#exampleModal2">
                                <i class="fa-solid fa-address-card fs-1 mb-2"></i>
                                <p class="font-weight-bold">Register ID Card</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.employee.partials.form-create')
    </x-navbar>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-employee').DataTable();

        });
        
        let myDropzone = new Dropzone('#media-image', {
            url: '{{ route('login') }}'
        })
    </script>
@endpush
