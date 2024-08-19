@extends('layouts.master')

@section('title', 'Dashboard')


@section('content')
    <x-navbar title_page="Dashboard">
        <div class="container-fluid py-4">
            <div class="row mb-5">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Employee</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $employe }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow d-flex justify-content-center align-items-center border-radius-md">
                                        <i class="fa-solid fa-users text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Today's Presence</p>
                                        <h5 class="font-weight-bolder mb-0" id="total_precense">
                                            {{ $precense->count() }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow d-flex justify-content-center align-items-center border-radius-md">
                                        <i class="fa-solid fa-user-check text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Late Employee</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            0
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow d-flex justify-content-center align-items-center border-radius-md">
                                        <i class="fa-solid fa-user-clock text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Today's Absence</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            0
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow d-flex justify-content-center align-items-center border-radius-md">
                                        <i class="fa-solid fa-user-slash text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row h-100">
                <livewire:chart-report lazy="on-load"/>
                @include('admin.dashboard.partials.recent-precense')
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    var channel = pusher.subscribe('precense-event');
    channel.bind('precense-channel', function(data){
        var list_presence = $('#precense_list');
        
        var markup = `
            <div class="py-3 d-flex justify-content-between px-4">
                <div class="d-flex align-items-center">
                    <div>
                        <img src="${data.precense.image}" alt="avatar" class="avatar avatar-sm me-3 rounded-circle"/>
                    </div>
                    ${data.precense.name}
                </div>
                <div class="d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                    <span class="text-xs font-weight-bold">
                        Time
                    </span>
                    <span class="text-lg">${data.precense.time}</span>
                </div>
            </div>
        `;

        list_presence.append(markup);
        $('#total_precense').text(data.precense.today_total);
    })
  </script>
@endpush