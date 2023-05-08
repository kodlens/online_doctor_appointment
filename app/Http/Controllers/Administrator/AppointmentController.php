<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Schedule;

class AppointmentController extends Controller
{
    //

    public function index(){
        return view('administrator.appointment');
    }

    public function getAppointments(Request $req){
        $sort = explode('.', $req->sort_by);

        $date = $req->appdate;

       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX

        if($req->appdate){
            $ndate = date("Y-m-d", strtotime($req->appdate));
        }else{
            $ndate = '';
        }

        $data = Appointment::with(['user', 'schedule'])
            ->where('appointment_date', 'like',  $ndate . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function create(){
        return view('administrator.appointment-create')
            ->with('id', 0);
    }


    public function store(Request $req){

        $appdate = date("Y-m-d", strtotime($req->appointment_date));

        //get Max no per schedule
        $schedule = Schedule::find($req->schedule_id);
        $max_no = $schedule->max_no;


        //para sure isa lang ka schedule per day.
        $existSched = Appointment::where('user_id', $req->user_id)
            ->where('appointment_date', $appdate)
            ->exists();
        if($existSched){
            return response()->json([
                'errors' => [
                    'exists' => ['Sorry. User already have a reservation.']
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

            return response()->json([
                'errors' => [
                    'max' => ['Sorry. The schedule reach the maximum number of reservation.']
                ],
                'message' => "The given data was invalid"
            ], 422);
        }

        //insert into database
        Appointment::create([
            'user_id' => $req->user_id,
            'schedule_id' => $req->schedule_id,
            'appointment_date' => $appdate,
            'illness_history' => $req->illness_history,
            
        ]);


        return response()->json([
            'status' => 'saved'
        ], 200);

    } //store

    public function edit($id){
        $appointment = Appointment::find($id);

        return view('administrator.appointment-create')
            ->with('appointment', $appointment)
            ->with('id', $id);
    }

    public function update(Request $req, $id){
    
    }



    
    public function destroy(){

    }




    //appoved appointment
    public function approveAppointment($id){

        $data = Appointment::find($id);
        $data->status = 1;
        $data->save();

        $user = User::where('user_id', $data->user_id)->first();
        $schedule = Schedule::where('schedule_id', $data->schedule_id)->first();

        if(env('ENABLE_SMS') == 1){
            $timeFrom = date('h:i A', strtotime($schedule->time_start));
            $timeEnd = date('h:i A', strtotime($schedule->time_end));

            $msg = 'Hi '.$user->lname . ', ' . $user->fname . ', your schedule ('. $timeStart .' - ' . $timeEnd. ', ' . $data->appointment_date . ') was approved.';
            try{
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->post('http://'. env('IP_SMS_GATEWAY') .'/services/api/messaging?Message='.$msg.'&To='.$user->contact_no.'&Slot=1', []);
            }catch(\Exception $e){} //just hide the error
        }

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function cancelAppointment($id){
        $data = Appointment::find($id);
        $data->status = 2;
        $data->save();

        $user = User::where('user_id', $data->user_id)->first();
        $schedule = Schedule::where('schedule_id', $data->schedule_id)->first();

        if(env('ENABLE_SMS') == 1){
            $timeFrom = date('h:i A', strtotime($schedule->time_start));
            $timeEnd = date('h:i A', strtotime($schedule->time_end));

            $msg = 'Hi '.$user->lname . ', ' . $user->fname . ', your schedule ('. $timeStart .' - ' . $timeEnd. ', ' . $data->appointment_date . ') was cancelled.';
            try{
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->post('http://'. env('IP_SMS_GATEWAY') .'/services/api/messaging?Message='.$msg.'&To='.$user->contact_no.'&Slot=1', []);
            }catch(\Exception $e){} //just hide the error
        }

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function pendingAppointment($id){
        $data = Appointment::find($id);
        $data->status = 0;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }



}
