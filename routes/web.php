<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'login' => false,
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showForm']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);


Route::get('/register-page', [App\Http\Controllers\RegisterController::class, 'index']);
Route::post('/register-page', [App\Http\Controllers\RegisterController::class, 'store']);




Route::get('/sample',[App\Http\Controllers\SampleController::class,'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);
Route::get('/load-open-schedules', [App\Http\Controllers\OpenDataController::class, 'loadOpenScehdules']);


Route::middleware(['auth'])->group(function(){

    Route::resource('/my-appointment', App\Http\Controllers\User\MyAppointmentController::class);
    Route::get('/get-my-appointments', [App\Http\Controllers\User\MyAppointmentController::class, 'getMyAppointments']);
    Route::post('/cancel-my-appointment/{id}', [App\Http\Controllers\User\MyAppointmentController::class, 'cancelAppointment']);

    Route::post('/apply-appointment', [App\Http\Controllers\ApplyAppointmentController::class, 'applyAppointment']);

    Route::resource('/user-change-password', App\Http\Controllers\User\UserChangePasswordController::class);

    Route::resource('/my-profile', App\Http\Controllers\User\MyProfileController::class);
    Route::get('/get-my-profile', [App\Http\Controllers\User\MyProfileController::class, 'getMyProfile']);

});



/*     ADMINSITRATOR          */

Route::middleware(['auth', 'admin'])->group(function(){

    Route::resource('/dashboard', App\Http\Controllers\Administrator\DashboardController::class);

    Route::resource('/appointments', App\Http\Controllers\Administrator\AppointmentController::class);
    Route::get('/get-appointments', [App\Http\Controllers\Administrator\AppointmentController::class, 'getAppointments']);
    Route::post('/appointment-approve/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'approveAppointment']);
    Route::post('/appointment-cancel/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'cancelAppointment']);
    Route::post('/appointment-pending/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'pendingAppointment']);

   


    Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
    Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);


    Route::resource('/schedules', App\Http\Controllers\Administrator\ScheduleController::class);
    Route::get('/get-schedules', [App\Http\Controllers\Administrator\ScheduleController::class, 'getSchedules']);
    
    Route::post('/reset-password/{id}', [App\Http\Controllers\Administrator\UserController::class, 'resetPassword']);
});




Route::get('/session', function(){
    return Session::all();
});


Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();
});
