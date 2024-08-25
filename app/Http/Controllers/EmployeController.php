<?php

namespace App\Http\Controllers;

use App\Models\Precense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeController extends Controller
{
    public function index()
    {
        return view('employe.dashboard.index');
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
