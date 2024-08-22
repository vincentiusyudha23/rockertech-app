@extends('layouts.master')

@section('title', 'Employee')

@push('styles')
    <style>
        .base-tap {
            position: relative;
        }

        .base-tap .tap-item {
            font-size: 2em;
        }

        .base-tap .tap-item-2 {
            position: absolute;
            right: -30px;
            top: 10px;
            z-index: 2;
        }

        .my-popup {
            width: 400px;
            height: 300px;
            padding: 20px;
        }

        .show-password {
            position: relative;
            cursor: pointer;
        }

        .show-password>span {
            display: inline-block;
            transition: 0.2s ease-out;
        }

        .show-password>span.active {
            display: inline-block;
            filter: blur(4px);
            transition: 0.2s ease-in;
        }

        .show-password i {
            position: absolute;
            top: 40%;
            left: 40%;
        }
    </style>
@endpush

@section('content')
    <x-navbar title_page="Employee">
        <div class="container-fluid py-4">
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
                            <button type="button" class="btn w-100 btn-lg bg-white py-5 regis-card-js">
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
    <template id="my-template">
        <swal-title>
            Tap Your ID Card
            <p>Time left: <span id="countdown">60</span> seconds</p>
        </swal-title>
        <swal-icon color="transparent">
            <div class="d-flex flex-column text-center base-tap">
                <i class="tap-item tap-item-1 fa-solid fa-address-card text-success"></i>
                <i class="tap-item tap-item-2 fa-solid text-secondary fa-arrow-down fa-bounce"></i>
            </div>
        </swal-icon>
        <swal-param name="allowEscapeKey" value="false" />
        <swal-param name="customClass" value='{ "popup": "my-popup" }' />
    </template>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-employee').DataTable();

            const employee = @json($select_employ);
            var channel = pusher.subscribe('my-channel');

            async function registerIDCard() {
                const {
                    value: employ
                } = await Swal.fire({
                    title: "Register ID Card",
                    input: "select",
                    inputOptions: employee,
                    inputPlaceholder: "Select an Employee",
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (!value) {
                                resolve("Please Select the Employee!");
                            } else {
                                resolve();
                            }
                        });
                    }
                });

                if (employ) {

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('admin.set_action_mode', ['id' => 2]) }}',
                        success: function(response) {
                            if (response.type === 'success') {
                                const tap = Swal.fire({
                                    template: "#my-template",
                                    showConfirmButton: false,
                                });

                                let timeLeft = 60;
                                let isReg = true;

                                let countdownTimer = setInterval(function() {
                                    // Kurangi waktu
                                    timeLeft--;

                                    // Update tampilan waktu di front-end
                                    $('#countdown').text(timeLeft);

                                    // Jika waktu habis, hentikan timer
                                    if (timeLeft <= 0) {
                                        clearInterval(countdownTimer);
                                        
                                        $.ajax({
                                            url: '{{ route('admin.set_action_mode', ['id' => 1]) }}',
                                            type: 'GET',
                                            success: function(response) {
                                                tap.close();
    
                                                Swal.fire({
                                                    title: 'Registration has ended...',
                                                    allowOutsideClick: true,
                                                    showConfirmButton: true,
                                                    icon: 'warning'
                                                });
    
                                                timeLeft = 60;
                                            },
                                        });
                                    }
                                }, 1000);

                                // setTimeout(function() {
                                //     if(isReg){
                                        
                                //     }
                                // }, 60000); // 60000 ms = 60 detik

                                channel.bind('register-card', function(data) {
                                    if (data.card_id) {
                                        tap.close();

                                        Swal.fire({
                                            title: 'Please Wait...',
                                            allowOutsideClick: false,
                                            showConfirmButton: false,
                                            willOpen: () => {
                                                Swal.showLoading();
                                            }
                                        });

                                        $.ajax({
                                            url: '{{ route('admin.employee.regis-card') }}',
                                            type: 'POST',
                                            data: {
                                                _token: '{{ csrf_token() }}',
                                                employee_id: employ,
                                                card_id: data.card_id
                                            },
                                            success: function(response) {
                                                Swal.hideLoading();
                                                if (response.type == 'success') {
                                                    Swal.fire({
                                                        title: response.msg,
                                                        icon: 'success',
                                                        showConfirmButton: false
                                                    });

                                                    location.reload();
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });
                }
            }

            $('.regis-card-js').on('click', function() {
                registerIDCard();
            });

            $('.show-password').on('click', function() {
                var el = $(this);
                var span_tag = el.find('span');
                var icon = el.find('i');
                if (span_tag.hasClass('active')) {
                    span_tag.removeClass('active');
                    icon.addClass('d-none');
                } else {
                    span_tag.addClass('active');
                    icon.removeClass('d-none');
                }
            });
        });
    </script>
@endpush
