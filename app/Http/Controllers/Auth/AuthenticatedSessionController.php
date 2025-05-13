<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function forgetPassword(): View
    {
        return view('admin.auth.forget-password');
    }

    public function create_employe(): View
    {
        return view('employe.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    Public function store_employe(LoginRequest $request): RedirectResponse
    {
        $request->authenticate_employe();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::EMPLOYE);
    }

    /**
     * Handle Forget Password
     */

    public function sendOtpToEmail(Request $request)
    {
        $request->validate(['email' => 'required']);

        $email = $request->email;
        $user = User::where(['email' => $email, 'role' => 'admin'])->exists();

        if(!$user){
            return response()->json([
                'type' => 'error',
                'errors' => ['Email is invalid']
            ]);
        }

        $otp = rand(1000, 9999);
        
        sendToEmail([
            'to' => $email,
            'subject' => 'OTP for reset Password',
            'view' => 'otp',
            'viewData' => [
                'otp' => $otp
            ]
        ]);

        session()->put('__forget_password_otp', $otp);
        session()->put('__email_admin', $email);
        session()->put('__otp_expired_time', now()->addMinutes(5));
        session()->put('__can_resend_otp', now()->addSeconds(90));
        session()->put('__step_number', 2);

        return response()->json([
            'type' => 'success',
            'canResendOTP' => now()->diffInSeconds(session()->get('__can_resend_otp'))
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);
        
        $otpInput = implode('', $request->otp);

        $otp = session()->get('__forget_password_otp');
        $expiredTime = session()->get('__otp_expired_time');

        if($expiredTime < now()){
            return response()->json([
                'type' => 'error',
                'msg' => 'OTP has expired'
            ], 422);
        }

        if($otpInput == $otp){
            session()->put('__step_number', 3);

            return response()->json([
                'type' => 'success',
                'msg' => 'OTP Successfully verified'
            ], 200);
        }

        return response()->json([
            'type' => 'error',
            'msg' => 'OTP is Invalid'
        ], 422);
    }

    public function resendOTP(Request $request)
    {
        $otp = rand(1000, 9999);
        $email = session()->get('__email_admin');

        if(!session()->has('__forget_password_otp') || !session()->has('__can_resend_otp') || !session()->has('__email_admin')){
            return response()->json([
                'type' => 'error',
                'msg' => 'Not Valid!'
            ], 422);
        }

        sendToEmail([
            'to' => $email,
            'subject' => 'OTP for reset Password',
            'view' => 'otp',
            'viewData' => [
                'otp' => $otp
            ]
        ]);

        session()->put('__forget_password_otp', $otp);
        session()->put('__otp_expired_time', now()->addMinutes(5));
        session()->put('__can_resend_otp', now()->addSeconds(90));
        session()->put('__step_number', 2);

        return response()->json([
            'type' => 'success',
            'msg' => 'Resend OTP is Successfully',
            'canResendOTP' => now()->diffInSeconds(session()->get('__can_resend_otp'))
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required|min:8',
            'confirmNewPassword' => 'required|same:newPassword'
        ]);

        $email = session()->get('__email_admin');
        $admin = User::where(['email' => $email, 'role' => 'admin'])->first();

        if(empty($admin)){
            return response()->json([
                'type' => 'error',
                'errors' => ['Email is Invalid']
            ], 422);
        }

        $admin->update([
            'password' => Hash::make($request->newPassword)
        ]);
        
        session()->forget('__forget_password_otp');
        session()->forget('__email_admin');
        session()->forget('__otp_expired_time');
        session()->forget('__can_resend_otp');
        session()->forget('__step_number');

        return response()->json([
            'type' => 'success',
            'msg' => 'Reset Password is Successfully'
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin');
    }

    public function destroy_employe(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
