<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class ArchiveAppointmentController extends Controller
{
    //

    public function index(){
        return view('administrator.archive-appointment');
    }

    public function getArchiveAppointments(Request $req){
        //return $req;

        $sort = explode('.', $req->sort_by);

        $date = $req->appdate;

       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX

        if($req->appdate){
            $ndate = date("Y-m-d", strtotime($req->appdate));
        }else{
            $ndate = '';
        }

        $data = Appointment::with(['user', 'schedule', 'patients'])
            ->where('appointment_date', 'like',  $ndate . '%')
            ->whereHas('user', function($q) use ($req){
                $q->where('lname', 'like', '%'. $req->name . '%');
            })
            ->where('is_archived', 0)
            ->where('status', 1)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function create(){
        return view('administrator.archive-appointment-create');
    }


    public function store(Request $req){

        $appointment = Appointment::whereBetween('appointment_date', [$req->start_date, $req->end_date])
            ->orWhere(function ($q){
                $q->where('is_arrived', 0)
                    ->where('is_served', 1); 
            })
            ->update([
                'is_archived' => 1
            ]);

        return response()->json([
            'status' => 'archived'
        ], 200);
    }

    public function restoreArchivesAppointments(Request $req){

       // return $req;

        $appointment = Appointment::whereBetween('appointment_date', [$req->start_date, $req->end_date])
            ->update([
                'is_archived' => 0
            ]);

        return response()->json([
            'status' => 'restored'
        ], 200);
    }

    public function restoreArchiveAppointment($id){

        $appointment = Appointment::where('appointment_id', $id)
            ->update([
                'is_archived' => 0
            ]);

        return response()->json([
            'status' => 'restored'
        ], 200);
    }

}
