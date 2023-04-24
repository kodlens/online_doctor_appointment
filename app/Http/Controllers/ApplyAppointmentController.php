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

        $appMax = Appointment::where('user_id', $user->user_id)
            ->where('schedule_id', $req->schedule_id)
            ->where('appointment_date', $appdate)
            ->count();

        if($appMax >= $max_no){
            return response()->json([
                'errors' => [
                    'max' => ['Sorry. The schedule reach the maximum number of reservation.']
                ],
                'message' => "Thie given data was invalid"
            ], 422);
        }


        Appointment::create([
            'user_id' => $user->user_id,
            'schedule_id' => $req->schedule_id,
            'appointment_date' => $appdate
        ]);


        return response()->json([
            'status' => 'saved'
        ], 200);;
    }


}
