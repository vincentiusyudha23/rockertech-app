<?php

use App\Events\RegisterCardEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodolistController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['web', 'auth','role:admin'])->prefix('admin')->name('admin.')->group(function(){
    
    Route::controller(AdminController::class)->group(function(){
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/employee', 'employee_acct')->name('employee');
        Route::post('/employee-store', 'employee_store')->name('employee.store');
        Route::get('/edit-page/{id}', 'edit_employe')->name('employee.edit');
        Route::post('/edit-card', 'requestUpdateCardEdit')->name('employee.edit.card');
        Route::post('/employee-update', 'update_employe')->name('employee.update');
        Route::get('/employee-delete/{id}', 'delete_employe')->name('employee.delete');
        Route::post('/register-card', 'updateCardId')->name('employee.regis-card');
        Route::get('/set-action-mode/{id}', 'set_action_mode')->name('set_action_mode');
        Route::get('/presence', 'presence')->name('presence');
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/update-static-option', 'update_static_uption')->name('update-static-option');
        Route::post('/update-time-precense', 'setTimePrecense')->name('update-time-precense');
        Route::get('/make-api-token', 'make_token_api')->name('make_api_token');
        Route::get('/file-report', 'file_report')->name('file_report');
        Route::post('/export-precense', 'export_precense')->name('export');
        Route::put('/change-password', 'change_password')->name('change_password');
        Route::get('/todolist-employe', 'todolist')->name('todolist');
        Route::post('/todolist/set-completed', 'set_completed_todolist')->name('todolist.completed');
        Route::get('/permit-submission-list', 'permit_submission_list')->name('permit');
        Route::put('/permit-submission/{id}/approved', 'set_approved_permit')->name('permit.set-approved');
        Route::put('/permit-submission/{id}/not-approved', 'set_not_approved_permit')->name('permit.set-not-approved');
        Route::delete('/permit-submission/{id}/delete', 'delete_permit')->name('permit.delete');
        Route::get('/key-indicator-performance', 'kpi_view')->name('kpi');
        Route::post('/kpi-settings', 'kpi_settings')->name('kpi.settings');
    });

    Route::controller(MediaController::class)->group(function(){
        Route::post('/media-uploader', 'media_upload')->name('media-upload');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create']);
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');
    Route::get('/forget-password', [AuthenticatedSessionController::class, 'forgetPassword'])->name('forget-password');
    Route::post('/forget-password/send-otp', [AuthenticatedSessionController::class, 'sendOtpToEmail'])->name('forget-password.send-otp');
    Route::post('/forget-password/verify-otp', [AuthenticatedSessionController::class, 'verifyOtp'])->name('forget-password.verify-otp');
    Route::post('/forget-password/resend-otp', [AuthenticatedSessionController::class, 'resendOTP'])->name('forget-password.resend-otp');
    Route::post('/forget-password/reset-password', [AuthenticatedSessionController::class, 'changePassword'])->name('forget-password.reset-password');
});


Route::middleware(['web','auth', 'role:employee'])->prefix('employe')->name('employe.')->group(function(){
    
    Route::controller(EmployeController::class)->group(function(){
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/my-precense', 'myPrecense')->name('myprecense');
        Route::get('/work-from-home', 'workFromHome')->name('wfh');
        Route::post('/_wfhPrecense', 'wfh_request')->name('whf-precense');
        Route::get('/profile', 'profile')->name('profile');
        Route::put('/change-password', 'change_password')->name('change_password');
        Route::get('/permit-submission', 'permitView')->name('permit');
        Route::post('/permit-submission/store', 'storePermit')->name('permit.store');
        Route::post('/permit-submission/update', 'updatePermit')->name('permit.update');
        Route::delete('/permit-submission/{id}/delete', 'deletePermit')->name('permit.delete');
        Route::get('/permit-submission/list', 'listPermit')->name('permit.list');
    });

    Route::controller(TodolistController::class)->group(function(){
        Route::get('/todo-list', 'index')->name('todolist');
        Route::post('/todo-list/store', 'store')->name('todolist.store');
        Route::post('/todo-list/update-index', 'updateTaskIndex')->name('todolist.updateindex');
        Route::post('/todo-list/update', 'update')->name('todolist.update');
        Route::post('/todo-list/delete', 'delete')->name('todolist.delete');
    });

    Route::controller(MediaController::class)->group(function(){
        Route::post('/media-uploader', 'media_upload')->name('media-upload');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy_employe'])->name('logout');
});

Route::middleware('guest')->name('employe.')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create_employe']);
    Route::post('login', [AuthenticatedSessionController::class, 'store_employe'])->name('login');
});

Route::get('/google-sheet', [AdminController::class, 'getPrecenseSheet']);