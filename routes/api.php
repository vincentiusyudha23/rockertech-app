<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/send-card-id', [AdminController::class, 'requestEsp'])->name('send_card_id');
Route::middleware('auth:sanctum')->get('/get-status-alarm', [AdminController::class, 'get_status_alarm'])->name('get_status_alarm');
Route::middleware('auth:sanctum')->get('/alarm-status-zero', [AdminController::class, 'alarm_status_zero'])->name('alarm_status_zero');
