<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AppClock;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;


class MyAppointmentController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('user');
    }

    public function index(){
        return view('user.my-appointment');
    }

    public function getMyAppointments(Request $req){
        $sort = explode('.', $req->sort_by);

        $user =  Auth::user();

        $date = $req->appdate;

       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX

        if($req->appdate){
            $ndate = date("Y-m-d", strtotime($req->appdate));
        }else{
            $ndate = '';
        }

        $data = Appointment::with(['user', 'schedule', 'patients'])->where('user_id', $user->user_id)
            ->where('appointment_date', 'like',  $ndate . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function rescheduleView($id){

        $data = Appointment::find($id);

        return view('user.my-appointment-reschedule')
            ->with('data', $data);
    }

    public function rescheduleSubmit(Request $req, $id){
        $data = Appointment::find($id);
        
        $appdate = date("Y-m-d", strtotime($req->appointment_date));
        $user = Auth::user();


        $schedule = Schedule::find($req->schedule_id);
        $max_no = $schedule->max_no;

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
                       //wala bakante sa nakuha nga schdules, move 1 day
                       $availableSched = $sched;
                       break;
                   }
                   //wala bakante sa nakuha nga schdules, move 1 day
               }

               if($availableSched){
                   break;
               }

               $msgDate = 'Schedule date changed!';
               
           }

           return response()->json([
            'errors' => [
                'max' => ['Sorry. The schedule reach the maximum number of reservations.', 
                    $availableSched, 
                    $currentDate,
                    $msgDate
                ],
            ],'message' => "Thie given data was invalid."], 422);

           return response()->json([
               'errors' => [
                   'max' => ['Sorry. The schedule reach the maximum number of reservations.', 
                       $availableSched, 
                       $currentDate,
                       $msgDate
                   ],
               ],
               'message' => "Thie given data was invalid."
           ], 422);
       }


       $data->schedule_id = $req->schedule_id;
       $data->appointment_date = $req->appointment_date;
       $data->save();

        
        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function upcomingAppointment(){

        $dateNow = date('Y-m-d');
        $userId = Auth::user()->user_id;
        
        $data = Appointment::with(['schedule'])
            ->where('user_id', $userId)
            ->where('appointment_date', '>=', $dateNow)
            ->orderBy('appointment_date', 'asc')
            ->first();

        return $data;
    }

    public function store(Request $req){

    }


    public function cancelAppointment($id){
        $data = Appointment::find($id);
        $data->status = 2;
        $data->save();

        return response()->json([
            'status' => 'cancelled'
        ],200);
    }
}
