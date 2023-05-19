<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AppClock;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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


    public function upcomingAppointment(){
        $data = DB::table('appointments as a')
            ->join('appointment_types as b', 'a.appointment_type_id', 'b.appointment_type_id')
            ->join('users as c', 'a.appointment_user_id', 'c.user_id')
            ->orderBy('appointment_id', 'desc')
            ->where('c.user_id', Auth::user()->user_id)
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
