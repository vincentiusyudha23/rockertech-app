<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Precense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('employe.dashboard.index', compact('total_precense','total_this_week', 'precense'));
    }

    public function myPrecense()
    {
        $precenses = Precense::where('employe_id', Auth::user()->employee->id)->latest()->get();

        return view('employe.precense.index', compact('precenses'));
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
