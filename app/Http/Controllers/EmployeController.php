<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Precense;
use Illuminate\View\View;
use Illuminate\Http\Request;
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
        $total_this_week = Precense::whereBetween('created_at', [$startOfWeek, $endOfWeek])->whereIn('type', [1,3])->count();
        $total_precense = Precense::whereMonth('created_at', Carbon::now()->month)->whereIn('type', [1,3])->count();
        $precense = Precense::where('employe_id', auth()->user()->employee->id)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->get();

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
}
