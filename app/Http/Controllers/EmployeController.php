<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Precense;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MediaUploader;
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

        if(Carbon::parse($request->to_date)->lte(Carbon::parse($request->from_date))){
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
}
