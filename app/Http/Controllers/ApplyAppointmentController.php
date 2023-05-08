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


        //para sure isa lang ka schedule per day.
        $existSched = Appointment::where('user_id', $user->user_id)
            ->where('appointment_date', $appdate)
            ->exists();
        if($existSched){
            return response()->json([
                'errors' => [
                    'exists' => ['Sorry. You already have a reservation.']
                ],
                'message' => "Thie given data was invalid"
            ], 422);
        }
        
        //get the max no of the schedule
        $appMax = Appointment::where('schedule_id', $req->schedule_id)
            ->where('appointment_date', $appdate)
            ->count();

        //para sure dili mulapas sa na set up nga max sa schedule
        if($appMax >= $max_no){

             //$startDate = '2023-05-01'; // start date
             $endDate = date('Y-m-d', strtotime($appdate . ' +15 day'));

             $currentDate = $appdate; // set the current date to the start date

             

            for ($i = 0; strtotime($appdate) <= strtotime($endDate); $i++) {
                $currentDate = date('Y-m-d', strtotime($appdate . ' +'. $i .' day')); // increment the current date by one day
                $day = date('D', strtotime($currentDate));
                $availableSched = [];
                
                //get the schedules by day using the date picked from user
                $schedules = Schedule::where($day, 1)->get();

                foreach($schedules as $sched){
                    $schedule_id = $sched['schedule_id']; //get ID
                    $schedMax = $sched['max_no'];

                    $appointment = Appointment::where('schedule_id', $schedule_id)
                        ->whereDate('appointment_date', $currentDate);

                    
                    if($appointment->count() < $schedMax){
                        //logic
                        $availableSched = $sched;
                        break;
                    }
                }

                if($availableSched){
                    break;
                }
            }

            
            return response()->json([
                'errors' => [
                    'max' => ['Sorry. The schedule reach the maximum number of reservation.', 
                            $availableSched, 
                            $currentDate
                        ],
                ],
                'message' => "Thie given data was invalid."
            ], 422);
        }

        //insert into database
        Appointment::create([
            'user_id' => $user->user_id,
            'schedule_id' => $req->schedule_id,
            'appointment_date' => $appdate,
            'illness_history' => $req->illness_history,

            
        ]);


        return response()->json([
            'status' => 'saved'
        ], 200);
    }


}
