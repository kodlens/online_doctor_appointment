<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;


class DashboardController extends Controller
{
    //

    public function index(){
        return view('administrator.dashboard');
    }

    public function loadTodaysAppointment(){

        $now = date('Y-m-d');

        return Appointment::where('appointment_date', $now)
            ->where('status', 1)
            ->count();
    }

}
