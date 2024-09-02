@extends('layouts.master')

@section('title', 'Dashboard')


@section('content')
    <x-navbar title_page="Dashboard">
        <div class="py-4">
            <div class="row mb-3">
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
                                        <h5 class="font-weight-bolder mb-0" id="late">
                                            {{ $precense->where('status', 2)->count() }}
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
                                        <h5 class="font-weight-bolder mb-0" id="absen">
                                            {{ $precense->where('status', 3)->count() }}
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
                {{-- <livewire:chart-report lazy="on-load"/> --}}
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
        var list_presence = $('#table-recent tbody');
        
        var markup = `
            <tr>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div>
                            <img src="${data.precense.image}" class="avatar avatar-sm me-3" alt="image">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">${data.precense.name}</h6>
                            <p class="text-xs text-secondary mb-2">${data.precense.timeHuman}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">STAFF</p>
                    <p class="text-xs text-secondary mb-0">${data.precense.position}</p>
                </td>
                <td class="align-middle text-center text-sm"> 
                    ${data.precense.status}
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-sm font-weight-bold">${data.precense.time}</span>
                </td>
            </tr>
        `;

        list_presence.prepend(markup);
        $('#total_precense').text(data.precense.today_total);
        $('#late').text(data.precense.late);
        $('#absen').text(data.precense.absen);
    })
  </script>
@endpush