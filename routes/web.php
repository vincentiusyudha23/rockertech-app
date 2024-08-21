<?php

use App\Events\RegisterCardEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;

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

// Route::get('/', function () {
//     return view('admin.auth.login');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['web', 'role:admin'])->prefix('admin')->name('admin.')->group(function(){
    
    Route::controller(AdminController::class)->group(function(){
        Route::get('/dashboard', 'index')->name('dashboard');
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
    });

    Route::controller(MediaController::class)->group(function(){
        Route::post('/media-uploader', 'media_upload')->name('media-upload');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route::get('/testing', function(){
//     event(new RegisterCardEvent('2312199'));

//     echo 'done';
// });