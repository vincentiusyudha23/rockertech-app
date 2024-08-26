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
        $total_precense = Precense::whereMonth('created_at', Carbon::now()->month)->where('type', 1)->count();

        return view('employe.dashboard.index', compact('total_precense'));
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
}
