@extends('layouts.master')

@section('title', 'KPI')

@push('styles')
    <style>
        .flip-card {
            background-color: transparent;
            width: 120px;
            height: 120px;
            perspective: 1000px;
            margin: 10px;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            border-radius: 50%;
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .flip-card-front img.avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        .flip-card-back {
            background-color: #ffffff;
            color: #333;
            transform: rotateY(180deg);
            flex-direction: column;
            font-size: 14px;
            padding: 10px;
            box-sizing: border-box;
            border: 3px solid #eee;
        }
    </style>
@endpush

@section('content')
    <x-navbar title_page="Key Indicator Performance (KPI)">
        @if (session('success'))
            <div class="p-2">
                <div class="alert alert-success m-0" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
        @endif
        <div class="w-100 px-2 pt-1 d-flex justify-content-between">
            <button class="btn btn-sm bg-gradient-info btn-icon m-0 d-flex justify-content-center align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#kpi-settings">
                <i class="fa-solid fa-gear text-sm"></i> KPI Settings
            </button>
            <div class="modal fade" id="kpi-settings">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">KPI Settings</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin.kpi.settings') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="target-client">Target Client</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="target_client" id="target-client" value="{{ get_static_option('target_client', 0) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="target-design">Target Design</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="target_design" id="target-design" value="{{ get_static_option('target_design', 0) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="target-content">Target Content</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="target_content" id="target-content" value="{{ get_static_option('target_content', 0) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="target-closing">Target Closing</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="target_closing" id="target-closing" value="{{ get_static_option('target_closing', 0) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <button type="submit" class="btn bg-gradient-info w-100 m-0 mt-2 text-sm">
                                    Save
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-2 row">
            <div class="col-12 col-md-7 mb-3 align-items-stretch">
                @include('admin.kpi.partials.chart')
            </div>
            <div class="col-12 col-md-5 mb-3 align-items-stretch">
                @include('admin.kpi.partials.top-employe')
            </div>
        </div>
        <div class="row p-2">
            <div class="col-12 col-md-8 mb-3 align-items-stretch">
                @include('admin.kpi.partials.table-left')
            </div>
            <div class="col-12 col-md-4 mb-3 align-items-stretch">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <span class="text-sm">Target Achievment (Client, Design, Content, Closing)</span>
                    </div>
                    <div class="card-body">
                        <div class="w-100 mb-3 d-flex gap-2 align-items-center">
                            <div class="bg-gradient-info rounded text-white d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-users text-lg"></i>
                            </div>
                            <div class="d-flex flex-column justify-content-between w-75">
                                <span class="text-bold text-sm m-0 p-0">Target Client</span>
                                <div class="progress-wrapper w-100 m-0 p-0">
                                    <div class="progress-info">
                                        <div class="progress-percentage">
                                            <span class="text-xs font-weight-bold">{{ $targetData['client']['value'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress w-100">
                                        <div class="progress-bar bg-gradient-{{ $targetData['client']['color'] }}" role="progressbar" style="width: {{ $targetData['client']['percentage'] }}%" aria-valuenow="{{ $targetData['client']['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 mb-3 d-flex gap-2 align-items-center">
                            <div class="bg-gradient-info rounded text-white d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-images text-lg"></i>
                            </div>
                            <div class="d-flex flex-column justify-content-between w-75">
                                <span class="text-bold text-sm m-0 p-0">Target Design</span>
                                <div class="progress-wrapper w-100 m-0 p-0">
                                    <div class="progress-info">
                                        <div class="progress-percentage">
                                            <span class="text-xs font-weight-bold">{{ $targetData['design']['value'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress w-100">
                                        <div class="progress-bar bg-gradient-{{ $targetData['design']['color'] }}" role="progressbar" style="width: {{ $targetData['design']['percentage'] }}%" aria-valuenow="{{ $targetData['design']['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 mb-3 d-flex gap-2 align-items-center">
                            <div class="bg-gradient-info rounded text-white d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-tv text-lg"></i>
                            </div>
                            <div class="d-flex flex-column justify-content-between w-75">
                                <span class="text-bold text-sm m-0 p-0">Target Content</span>
                                <div class="progress-wrapper w-100 m-0 p-0">
                                    <div class="progress-info">
                                        <div class="progress-percentage">
                                            <span class="text-xs font-weight-bold">{{ $targetData['content']['value'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress w-100">
                                        <div class="progress-bar bg-gradient-{{ $targetData['content']['color'] }}" role="progressbar" style="width: {{ $targetData['content']['percentage'] }}%" aria-valuenow="{{ $targetData['content']['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 mb-3 d-flex gap-2 align-items-center">
                            <div class="bg-gradient-info rounded text-white d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-circle-check text-lg"></i>
                            </div>
                            <div class="d-flex flex-column justify-content-between w-75">
                                <span class="text-bold text-sm m-0 p-0">Target Closing</span>
                                <div class="progress-wrapper w-100 m-0 p-0">
                                    <div class="progress-info">
                                        <div class="progress-percentage">
                                            <span class="text-xs font-weight-bold">{{ $targetData['closing']['value'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress w-100">
                                        <div class="progress-bar bg-gradient-{{ $targetData['closing']['color'] }}" role="progressbar" style="width: {{ $targetData['closing']['percentage'] }}%" aria-valuenow="{{ $targetData['closing']['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection