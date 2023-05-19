<?php

namespace App\Http\Controllers\Administrator\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;


class ReportPatientAppointmentController extends Controller
{
    //

    public function index(){
        return view('administrator.report.report-patient-appointment');
    }

    public function getDataReportPatientAppointment(Request $req){

        //return $req;

        $date_start = date('Y-m-d', strtotime($req->start));
        $date_end = date('Y-m-d', strtotime($req->end));

       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX


        // $data = Appointment::with(['user', 'schedule', 'patients'])
        //     ->whereBetween('appointment_date', [$date_start, $date_end])
        //     ->where('is_archived', 0)
        //     ->get();

        $data = DB::table('appointments as a')
            ->join('users as b', 'a.user_id', 'b.user_id')
            ->join('schedules as c', 'a.schedule_id', 'c.schedule_id')
            ->leftJoin('patients as d', 'a.appointment_id', 'd.appointment_id')
            ->whereBetween('a.appointment_date', [$date_start, $date_end])
            ->where('a.is_archived', 0)
            ->select('a.*', 'b.lname', 'b.fname', 'b.mname', 'b.sex',
                'c.time_from', 'c.time_end',
                'd.lname as patient_lname', 'd.fname as patient_fname', 'd.mname as patient_mname'
            )
            ->get();
        return $data;
    }



    public function indexNoServeUnserve(){
        return view('administrator.report.report-no-serve-unserve');
    }

    public function getDataReportServeUnserve(Request $req){
        $date_start = date('Y-m-d', strtotime($req->start));
        $date_end = date('Y-m-d', strtotime($req->end));

    }

}
