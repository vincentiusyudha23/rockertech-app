<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Precense;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\PermitTypeEnum;
use App\Models\MediaUploader;
use App\Enums\PermitStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rules\Password;

class EmployeController extends Controller
{
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Senin
        $endOfWeek = Carbon::now()->endOfWeek(); // Minggu
        $total_this_week = Precense::where('employe_id', auth()->user()->employee->id)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->whereIn('type', [1,3])->count();
        $total_precense = Precense::where('employe_id', auth()->user()->employee->id)->whereMonth('created_at', Carbon::now()->month)->whereIn('type', [1,3])->count();
        $precense = Precense::where('employe_id', auth()->user()->employee->id)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->latest()->get();

        $now = Carbon::now();

        $totalDays = $now->daysInMonth;

        $days = 0;

        for ($day = 1; $day <= $totalDays; $day++) {
            // Buat tanggal dengan hari saat ini
            $currentDate = Carbon::create($now->year, $now->month, $day);

            // Cek apakah hari ini bukan Minggu
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
                $days++;
            }
        }

        $today_precense_wfh = Precense::where('employe_id', auth()->user()->employee->id)
                    ->todayPrecense()
                    ->where('type' , 3)
                    ->first() ? true : false;

        return view('employe.dashboard.index', compact('total_precense','total_this_week', 'precense', 'days', 'today_precense_wfh'));
    }

    public function myPrecense()
    {
        $precenses = Precense::where('employe_id', Auth::user()->employee->id)->latest()->get();

        return view('employe.precense.index', compact('precenses'));
    }

    public function profile(Request $request): View
    {
        return view('admin.auth.profile');
    }

    public function change_password(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $employe = Employee::find($request->user()->employee->id);
        $employe->enc_password = Crypt::encryptString($validated['password']);
        $employe->save();

        return back()->with('success', 'Password Update Successfully');
    }

    public function workFromHome()
    {
        return view('employe.work_from_home.index');
    }

    public function wfh_request(Request $request)
    {
        $this->validate($request, [
            'image' => 'required'
        ],[
            'image.required' => 'Image is Required'
        ]);

        try{
            $precense = Precense::where('employe_id', auth()->user()->employee->id)
                    ->where('type' , 3)
                    ->todayPrecense()
                    ->first();

            if(!$precense){
                Precense::create([
                    'employe_id' => auth()->user()->employee->id,
                    'type' => 3,
                    'status' => 1,
                    'image' => $request->image,
                    'time' => Carbon::now()->format('H:i')
                ]);

                return response()->json([
                    'type' => 'success',
                    'msg' => 'Precense Work From Home Success'
                ]);
            }else{
                return response()->json([
                    'type' => 'error',
                    'msg' => "You Have Today's WFH Presence"
                ]);
            }

        }catch(\Exception $e){
            \Log::error('An error occurred: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function permitView()
    {
        return view('employe.permit.index');
    }

    public function storePermit(Request $request)
    {
        $request->validate([
            'permitType' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'file' => 'nullable|mimes:jpg,jpeg,png,gif,webp,pdf|max:5000',
            'reason' => 'required'
        ]);

        if(Carbon::parse($request->to_date)->lt(Carbon::parse($request->from_date))){
            return response()->json([
                'type' => 'error',
                'errors' => ['To Date is Invalid']
            ], 422);
        }

        try {
            DB::beginTransaction();

            if($request->hasFile('file')){
                $file = $request->file;
                $file_extension = $file->extension();
                $file_name_with_ext = $file->getClientOriginalName();

                $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
                $file_name = strtolower(Str::slug($file_name));

                $file_db = $file_name.time().'.'.$file_extension;
                $folderPath = global_assets_path('assets/file_permit/employes');
                $file->move($folderPath, $file_db);

                if($file){
                    $mediaData = MediaUploader::create([
                        'title' => $file_name_with_ext,
                        'path' => $file_db,
                        'size' => null,
                        'user_id' => Auth::user()->id
                    ]);
                }
            }

            Auth::user()->employee->permit()->create([
                'type' => $request->permitType,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'reason' => $request->reason,
                'file_id' => isset($mediaData) && !empty($mediaData) ? $mediaData->id : null
            ]);
            
            DB::commit();
            return response()->json([
                'type' => 'success',
            ], 200);

        } catch (\Exception $e){
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'msg' => $e->getMessage()
            ], 422);
        }
    }

    public function listPermit()
    {
        $permits = Auth::user()->employee?->permit()?->latest()?->get()->map(function($item){
            $item->file = get_data_file($item->file_id);
            return $item;
        });

        return view('employe.permit.list_permit', compact('permits'));
    }

    public function updatePermit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'permitType' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'file' => 'nullable|mimes:jpg,jpeg,png,gif,webp,pdf|max:5000',
            'reason' => 'required'
        ]);

        if(Carbon::parse($request->to_date)->lte(Carbon::parse($request->from_date))){
            return redirect()->back()->with('errors', 'To Date is invalid!');
        }

        $oldPermit = Auth::user()->employee->permit()->findOrFail($request->id);

        if($oldPermit->status == PermitStatusEnum::APPROVED->value){
            return redirect()->back()->with('errors', 'You can no longer edit');
        }

        try {
            DB::beginTransaction();

            if($request->hasFile('file')){
                $file = $request->file;
                $file_extension = $file->extension();
                $file_name_with_ext = $file->getClientOriginalName();

                $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
                $file_name = strtolower(Str::slug($file_name));

                $file_db = $file_name.time().'.'.$file_extension;
                $folderPath = global_assets_path('assets/file_permit/employes');
                $file->move($folderPath, $file_db);

                if($file){
                    $mediaData = MediaUploader::create([
                        'title' => $file_name_with_ext,
                        'path' => $file_db,
                        'size' => null,
                        'user_id' => Auth::user()->id
                    ]);
                }
            }

            $oldPermit->update([
                'type' => $request->permitType,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'reason' => $request->reason,
                'file_id' => isset($mediaData) && !empty($mediaData) ? $mediaData->id : ($oldPermit?->file_id ?? null)
            ]);
            
            DB::commit();
            return redirect()->back()->with('success', 'Update Permit is Successfully');

        } catch (\Exception $e){
            DB::rollBack();

            if(env('APP_ENV') == 'local'){
                dd($e->getMessage());
            }

            return redirect()->back()->with('errors', 'Something Went Wrong!');
        }
    }

    public function deletePermit($id)
    {
        $permit = Auth::user()->employee->permit()->findOrFail($id);
        if($permit->status == PermitStatusEnum::APPROVED->value){
            return redirect()->back()->with('errors', 'You can no longer delete Permit Submissions');
        }

        return $permit->delete() ?
            redirect()->back()->with('success', 'Deleted Permit is Successfully') :
            redirect()->back()->with('errors', 'Deleted Permit is Failed');
    }

    public function forgetPassword()
    {
        return view('employe.auth.forget-password');
    }

    public function sendOtpToEmail(Request $request)
    {
        $request->validate(['email' => 'required']);

        $email = $request->email;
        $user = User::where(['email' => $email, 'role' => 'employee'])->exists();

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

        session()->put('__forget_password_otp_employe', $otp);
        session()->put('__email_admin_employe', $email);
        session()->put('__otp_expired_time_employe', now()->addMinutes(5));
        session()->put('__can_resend_otp_employe', now()->addSeconds(90));
        session()->put('__step_number_employe', 2);

        return response()->json([
            'type' => 'success',
            'canResendOTP' => now()->diffInSeconds(session()->get('__can_resend_otp_employe'))
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);
        
        $otpInput = implode('', $request->otp);

        $otp = session()->get('__forget_password_otp_employe');
        $expiredTime = session()->get('__otp_expired_time_employe');

        if($expiredTime < now()){
            return response()->json([
                'type' => 'error',
                'msg' => 'OTP has expired'
            ], 422);
        }

        if($otpInput == $otp){
            session()->put('__step_number_employe', 3);

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
        $email = session()->get('__email_admin_employe');

        if(!session()->has('__forget_password_otp_employe') || !session()->has('__can_resend_otp_employe') || !session()->has('__email_admin_employe')){
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

        session()->put('__forget_password_otp_employe', $otp);
        session()->put('__otp_expired_time_employe', now()->addMinutes(5));
        session()->put('__can_resend_otp_employe', now()->addSeconds(90));
        session()->put('__step_number_employe', 2);

        return response()->json([
            'type' => 'success',
            'msg' => 'Resend OTP is Successfully',
            'canResendOTP' => now()->diffInSeconds(session()->get('__can_resend_otp_employe'))
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required|min:8',
            'confirmNewPassword' => 'required|same:newPassword'
        ]);

        $email = session()->get('__email_admin_employe');
        $admin = User::where(['email' => $email, 'role' => 'employee'])->first();

        if(empty($admin)){
            return response()->json([
                'type' => 'error',
                'errors' => ['Email is Invalid']
            ], 422);
        }

        $admin->update([
            'password' => Hash::make($request->newPassword)
        ]);
        
        session()->forget('__forget_password_otp_employe');
        session()->forget('__email_admin_employe');
        session()->forget('__otp_expired_time_employe');
        session()->forget('__can_resend_otp_employe');
        session()->forget('__step_number_employe');

        return response()->json([
            'type' => 'success',
            'msg' => 'Reset Password is Successfully'
        ]);
    }
}
