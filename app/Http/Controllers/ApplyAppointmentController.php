<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Schedule;
use App\Models\Appointment;


class ApplyAppointmentController extends Controller
{
    //


    public function applyAppointment(Request $req){

        $appdate = date("Y-m-d", strtotime($req->appointment_date));
        $user = Auth::user();

        //get Max no per schedule
        $schedule = Schedule::find($req->schedule_id);
        $max_no = $schedule->max_no;


        Appointment::create([
            'user_id' => $user->user_id,
            'schedule_id' => $req->schedule_id,
            'appointment_date' => $appdate
        ]);


        return $req;
    }


}
