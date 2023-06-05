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

Route::get('/forgot-password', [App\Http\Controllers\CustomForgotPasswordController::class, 'index']);
Route::post('/request-otp', [App\Http\Controllers\CustomForgotPasswordController::class, 'requestOtp']);
Route::get('/otp-entry/{user}', [App\Http\Controllers\CustomForgotPasswordController::class, 'otpEntry']);
Route::post('/reset-password-otp', [App\Http\Controllers\CustomForgotPasswordController::class, 'resetPasswordWithOTP']);


Route::get('/sample',[App\Http\Controllers\SampleController::class,'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);
Route::get('/load-open-schedules', [App\Http\Controllers\OpenDataController::class, 'loadOpenScehdules']);

//open
Route::get('/load-max-no', [App\Http\Controllers\OpenDataController::class, 'loadMaxNo']);


Route::middleware(['auth'])->group(function(){

    Route::resource('/my-appointment', App\Http\Controllers\User\MyAppointmentController::class);
    Route::get('/get-my-appointments', [App\Http\Controllers\User\MyAppointmentController::class, 'getMyAppointments']);
    Route::post('/cancel-my-appointment/{id}', [App\Http\Controllers\User\MyAppointmentController::class, 'cancelAppointment']);

    Route::post('/apply-appointment', [App\Http\Controllers\ApplyAppointmentController::class, 'applyAppointment']);

    Route::resource('/user-change-password', App\Http\Controllers\User\UserChangePasswordController::class);

    Route::resource('/my-profile', App\Http\Controllers\User\MyProfileController::class);
    Route::get('/get-my-profile', [App\Http\Controllers\User\MyProfileController::class, 'getMyProfile']);

});



/*ADMINSITRATOR*/

Route::middleware(['auth', 'staff'])->group(function(){

    Route::resource('/dashboard', App\Http\Controllers\Administrator\DashboardController::class);
    Route::get('/load-today-appointments', [App\Http\Controllers\Administrator\DashboardController::class, 'loadTodaysAppointment']);

    Route::resource('/appointments', App\Http\Controllers\Administrator\AppointmentController::class);
    Route::get('/get-appointments', [App\Http\Controllers\Administrator\AppointmentController::class, 'getAppointments']);
    Route::post('/appointment-approve/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'approveAppointment']);
    Route::post('/appointment-cancel/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'cancelAppointment']);
    Route::post('/appointment-pending/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'pendingAppointment']);
    Route::post('/appointment-archive/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'archiveAppointment']);
    Route::post('/appointment-set-arrived/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'setArrived']);
    Route::post('/appointment-set-served/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'setServed']);


    
    //REPORT
    Route::get('/report-appointments', [App\Http\Controllers\Administrator\Report\ReportPatientAppointmentController::class, 'index']);
    Route::get('/get-report-patients-appointments', [App\Http\Controllers\Administrator\Report\ReportPatientAppointmentController::class, 'getDataReportPatientAppointment']);
    
    Route::get('/report-no-serve-unserve', [App\Http\Controllers\Administrator\Report\ReportPatientAppointmentController::class, 'indexNoServeUnserve']);
    Route::get('/get-report-no-serve-unserve', [App\Http\Controllers\Administrator\Report\ReportPatientAppointmentController::class, 'getDataReportServeUnserve']);


    Route::get('/report-patient-by-location', [App\Http\Controllers\Administrator\Report\ReportPatientAppointmentController::class, 'indexReportPatientByLocation']);
    Route::get('/get-report-patient-by-location', [App\Http\Controllers\Administrator\Report\ReportPatientAppointmentController::class, 'getDataReportPatientByLocation']);

    
});

Route::middleware(['auth', 'admin'])->group(function(){

    Route::resource('/schedules', App\Http\Controllers\Administrator\ScheduleController::class);
    Route::get('/get-schedules', [App\Http\Controllers\Administrator\ScheduleController::class, 'getSchedules']);

    Route::resource('/vacations', App\Http\Controllers\Administrator\VacationController::class);
    Route::get('/get-vacations', [App\Http\Controllers\Administrator\VacationController::class, 'getVacations']);


    Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
    Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);
    Route::get('/get-browse-users', [App\Http\Controllers\Administrator\UserController::class, 'getBrowseUser']);

    Route::resource('/max-patients', App\Http\Controllers\Administrator\MaxPatientController::class);
    Route::post('/update-max-patient', [App\Http\Controllers\Administrator\MaxPatientController::class, 'updateMaxPatient']);
    Route::get('/get-max-patients', [App\Http\Controllers\Administrator\MaxPatientController::class, 'getMaxPatients']);
    
    Route::resource('/archive-appointments', App\Http\Controllers\Administrator\ArchiveAppointmentController::class);
    Route::get('/get-archive-appointments', [App\Http\Controllers\Administrator\ArchiveAppointmentController::class, 'getArchiveAppointments']);
    Route::post('/archive-appointment-restore/{id}', [App\Http\Controllers\Administrator\ArchiveAppointmentController::class, 'restoreArchiveAppointment']);
    Route::post('/archives-appointments-restore', [App\Http\Controllers\Administrator\ArchiveAppointmentController::class, 'restoreArchivesAppointments']);


    Route::post('/reset-password/{id}', [App\Http\Controllers\Administrator\UserController::class, 'resetPassword']);
});

/*ADMINSITRATOR*/


Route::get('/session', function(){
    return Session::all();
});

Route::get('/notif', function(){
    
    $beforeDay = date('Y-m-d H:i', strtotime('+24 hour', strtotime(date('Y-m-d H:i')))); 

    $data = \DB::table('appointments as a')
        ->join('schedules as b', 'a.schedule_id', 'b.schedule_id')
        ->join('users as c', 'a.user_id', 'c.user_id')
        ->where('a.appointment_date', date('Y-m-d', strtotime($beforeDay)))
        ->where('b.time_from', date('H:i', strtotime($beforeDay)))
        ->where('a.is_notify', 0)
        ->get();

        //TIWASONUN

    foreach($data as $i){

        //$user = User::find($i->user_id);

        $msg = 'The '.$i->lname . ', ' . $i->fname . ', this is just a reminder that you have an appointment tomorrow at exactly '. date('h:i A', $i->time_from) .'. Your ref no. is: ' . $i->appointment_id . '.';
        
        if(env('ENABLE_SMS') == 1){
            try{ 
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->post('http://'. env('IP_SMS_GATEWAY') .'/services/api/messaging?Message='.$msg.'&To='.$user->contact_no.'&Slot=1', []);
            }catch(Exception $e){} //just hide the error
    
            $appoint = Appointment::find($i->appointment_id);
            $appoint->is_notify = 1;
            $appoint->save();
        }
    }

});




Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();
});
