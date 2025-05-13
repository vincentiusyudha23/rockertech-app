@extends('layouts.master')

@section('title', 'Forget Password')

@push('styles')
    <style>
        .login-side{
            width: 50%;
        }
        .otp-input{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }
        .otp-input input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
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
<div class="w-100 vh-100" x-data="forgetPassword">
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
                    <h3 class="m-0" style="color: #2d5abf;">Reset Password</h3>
                    <p x-show="stepNumber == 1" class="m-0 text-sm font-weight-bold">Enter your email admin to reset password</p>
                    <p x-show="stepNumber == 2" class="m-0 text-sm font-weight-bold">Check your email for the OTP</p>
                    <p x-show="stepNumber == 3" class="m-0 text-sm font-weight-bold">Enter New Password</p>
                </div>
                
                <template x-if="stepNumber == 1">
                    <div>
                        <div class="form-group">
                            <label class="form-control-label" for="email">Email</label>
                            <div class="input-group">
                                <input type="email" x-model="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
        
                        <div class="text-center">
                            <button type="button" :disabled="!email || isLoading" x-on:click="resetPassword" class="btn w-100 mt-4 mb-0 text-white text-md" style="background-color: #2d5abf;">
                                <span x-show="!isLoading">Reset</span>
                                <template x-if="isLoading">
                                    <i class="fa-solid fa-spinner fa-spin text-sm"></i>
                                </template>
                            </button>
                        </div>
                    </div>
                </template>
                
                <template x-if="stepNumber == 2">
                    <div>
                        <div class="otp-input mb-2">
                            <input type="text" class="form-control" maxlength="1" pattern="[0-9]" x-model="inputOtp[0]" x-ref="input_1">
                            <input type="text" class="form-control" maxlength="1" pattern="[0-9]" x-model="inputOtp[1]" x-ref="input_2">
                            <input type="text" class="form-control" maxlength="1" pattern="[0-9]" x-model="inputOtp[2]" x-ref="input_3">
                            <input type="text" class="form-control" maxlength="1" pattern="[0-9]" x-model="inputOtp[3]" x-ref="input_4">
                        </div>

                        <div class="text-center">
                            <button type="button" :disabled="isLoading" x-on:click="verifyOtp" class="mb-2 btn w-100 mt-4 text-white text-md" style="background-color: #2d5abf;">Verify</button>
                            <a href="javascript:;" x-bind:class="totalSecond > 0 ? 'opacity-5' : ''" x-on:click="handleResendOTP" class="text-sm text-bold">Resend OTP ( <span x-text="formattedTime"></span> )</a>
                        </div>
                    </div>
                </template>

                <template x-if="stepNumber == 3">
                    <div>
                        <div class="form-group">
                            <label class="form-control-label" for="new-password">New Password</label>
                            <div class="input-group">
                                <input class="form-control" type="password" x-model="newPassword" id="new-password">
                            </div>
                            <template x-if="errorNewPassword && !newPassword">
                                <span class="text-danger text-xs">cannot be empty</span>
                            </template>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="confirm-new-password">Confirm New Password</label>
                            <div class="input-group">
                                <input class="form-control" type="password" x-model="confirmNewPassword" id="confirm-new-password">
                            </div>
                            <template x-if="confirmNewPassword && confirmNewPassword !== newPassword">
                                <span class="text-danger text-xs">Passwords do not match</span>
                            </template>
                            <template x-if="errorNewPassword && !confirmNewPassword">
                                <span class="text-danger text-xs">cannot be empty</span>
                            </template>
                        </div>

                        <div class="text-center">
                            <button type="button" :disabled="isLoading" x-on:click="handleSave" class="btn w-100 mt-2 mb-0 text-white text-md" style="background-color: #2d5abf;">
                                <span x-show="!isLoading">Save</span>
                                <template x-if="isLoading">
                                    <i class="fa-solid fa-spinner fa-spin text-sm"></i>
                                </template>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@php
    $canResendOTP = null;
    if(session()->has('__can_resend_otp')){
        $canResendOTP = \Carbon\Carbon::now()->diffInSeconds(\Carbon\Carbon::parse(session()->get('__can_resend_otp')));
        if(\Carbon\Carbon::parse(session()->get('__can_resend_otp'))->lt(\Carbon\Carbon::now())){
            $canResendOTP = 0;
        }    
    }
@endphp
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('forgetPassword', () => ({
                email: null,
                isLoading: false,
                stepNumber: '{{ session()->has('__step_number') }}' ? '{{ session()->get('__step_number') }}' : 1,
                inputOtp: [],
                totalSecond: null,
                canResendOTP: '{{ $canResendOTP }}',
                newPassword: null,
                confirmNewPassword: null,
                errorNewPassword: false,
                async handleSave(){
                    if(!this.newPassword || !this.confirmNewPassword){
                        this.errorNewPassword = true;
                        return;
                    }

                    if(this.confirmNewPassword && this.confirmNewPassword !== this.newPassword) return;

                    this.isLoading = true;

                    const response = await fetch('{{ route("admin.forget-password.reset-password") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            newPassword: this.newPassword,
                            confirmNewPassword: this.confirmNewPassword
                        })
                    });

                    const res = await response.json();

                    if(!response.ok){
                        this.isLoading = false;
                        const errors = res.errors;
                        Object.values(errors).forEach(error => {
                            toastr.error(error);
                        })

                        return;
                    }

                    if(response.ok && res.type == 'success'){
                        toastr.success(res.msg);

                        setTimeout(() => {
                            location.href = '{{ url("/admin") }}';
                        }, 1000);
                    }
                },
                async resetPassword(){
                    this.isLoading = true;

                    try {
                        const response = await fetch('{{ route("admin.forget-password.send-otp") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                email: this.email
                            })
                        });

                        const res = await response.json();

                        if(response.ok && res.type == 'success'){
                            this.isLoading = false;
                            this.stepNumber = 2;
                            this.totalSecond = res.canResendOTP;
                            this.setCountDownTimer();
                        } else {
                            const errors = res.errors;
                            Object.values(errors).forEach(error => {
                                toastr.error(error);
                            });
                        }

                    } catch (err){

                    }
                },
                async verifyOtp(){
                    if(!this.inputOtp[0] || !this.inputOtp[1] || !this.inputOtp[2] || !this.inputOtp[3]){
                        toastr.error('OTP is Invalid!');
                        return;
                    }

                    this.isLoading = true;
                    
                    try {
                        const response = await fetch('{{ route("admin.forget-password.verify-otp") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                otp: this.inputOtp
                            })
                        });

                        const res = await response.json();

                        if(!response.ok){
                            toastr.error(res.msg);
                            this.isLoading = false;
                            return;
                        }

                        if(response.ok && res.type == 'success'){
                            this.isLoading = false;
                            this.stepNumber = 3;
                            toastr.success(res.msg)
                        }
                    } catch (err){
                        this.isLoading = false;
                    }
                },
                async handleResendOTP(){
                    if(this.totalSecond > 0) return;

                    try{
                        const response = await fetch('{{ route("admin.forget-password.resend-otp") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify()
                        });

                        const res = await response.json();

                        if(response.ok && res.type == 'success'){
                            toastr.success(res.msg);
                            this.stepNumber = 2;
                            this.totalSecond = res.canResendOTP;
                            this.setCountDownTimer();
                        } else {
                            toastr.error(res.msg);
                        }
                    } catch(err){}
                },
                setCountDownTimer(){
                    this.timer = setInterval(() => {
                        if(this.totalSecond > 0){
                            this.totalSecond--;
                        } else {
                            clearInterval(this.timer);
                        }
                    }, 1000);
                },
                get minutes(){
                    return Math.floor(this.totalSecond / 60);
                },
                get seconds(){
                    return this.totalSecond % 60;
                },
                get formattedTime(){
                    return `${this.minutes.toString().padStart(2, '0')} : ${this.seconds.toString().padStart(2, '0')}`;
                },
                init(){
                    if(this.canResendOTP != null && this.canResendOTP > 0){
                        this.totalSecond = this.canResendOTP;
                        this.setCountDownTimer();
                    };

                    this.$watch('newPassword', val => {
                        if(val){
                            this.errorNewPassword = false;
                        }
                    })
                    this.$watch('confirmNewPassword', val => {
                        if(val){
                            this.errorNewPassword = false;
                        }
                    })

                    this.$watch('inputOtp[0]', (newVal, oldVal) => {
                        if (newVal && newVal.length === 1) {
                            this.$refs.input_2.focus();
                        }
                    });

                    this.$watch('inputOtp[1]', (newVal, oldVal) => {
                        if (newVal && newVal.length === 1) {
                            this.$refs.input_3.focus();
                        }
                        if (oldVal && newVal.length < oldVal.length && newVal.length === 0) {
                            this.$refs.input_1.focus();
                        }
                    });

                    this.$watch('inputOtp[2]', (newVal, oldVal) => {
                        if (newVal && newVal.length === 1) {
                            this.$refs.input_4.focus();
                        }
                        if (oldVal && newVal.length < oldVal.length && newVal.length === 0) {
                            this.$refs.input_2.focus();
                        }
                    });

                    this.$watch('inputOtp[3]', (newVal, oldVal) => {
                        if (newVal && newVal.length === 1) {
                            this.verifyOtp();
                        }
                        if (oldVal && newVal.length < oldVal.length && newVal.length === 0) {
                            this.$refs.input_3.focus();
                        }
                    });
                }
            }));
        })
    </script>
@endpush