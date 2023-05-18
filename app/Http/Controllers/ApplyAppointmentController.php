<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Patient;


class ApplyAppointmentController extends Controller
{
    //
    public function applyAppointment(Request $req){

        //return $req;

        $req->validate([
            'patients' => ['required'],
            'patients.*.lname' => ['required'],
            'patients.*.fname' => ['required'],
            'patients.*.age' => ['required'],
            'patients.*.sex' => ['required']
        ]);
 
        $appdate = date("Y-m-d", strtotime($req->appointment_date));
        $user = Auth::user();

        //get Max no per schedule
        $schedule = Schedule::find($req->schedule_id);
        $max_no = $schedule->max_no;


        //para sure isa lang ka schedule per day.
        $existSched = Appointment::where('user_id', $user->user_id)
            ->where('appointment_date', $appdate)
            ->where('status', '!=', 2)
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

            $msgDate = '';

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
                        // wala bakante sa nakuha nga schdules, move 1 day
                        $availableSched = $sched;
                        break;
                    }
                    // wala bakante sa nakuha nga schdules, move 1 day
                }

                if($availableSched){
                    break;
                }

                $msgDate = 'Schedule date changed!';
            }

            
            return response()->json([
                'errors' => [
                    'max' => ['Sorry. The schedule reach the maximum number of reservation.', 
                        $availableSched, 
                        $currentDate,
                        $msgDate
                    ],
                ],
                'message' => "Thie given data was invalid."
            ], 422);
        }

        //insert into database
        $appointment = Appointment::create([
            'user_id' => $user->user_id,
            'schedule_id' => $req->schedule_id,
            'appointment_date' => $appdate,
        ]);

        $patient = [];
        foreach($req->patients as $item){
            array_push($patient, [
                'appointment_id' => $appointment->appointment_id,
                'lname' => strtoupper($item['lname']),
                'fname' => strtoupper($item['fname']),
                'mname' => strtoupper($item['mname']),
                'sex' => strtoupper($item['sex']),
                'age' => $item['age'],
                'illness' => $item['illness'],

            ]);
        }

        Patient::insert($patient);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


}
