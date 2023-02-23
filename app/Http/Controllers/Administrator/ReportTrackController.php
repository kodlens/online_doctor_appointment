<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportTrackController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('administrator.report.report-track');
    }

    public function getReportTrack(Request $req){
        $data = DB::table('appointment_tracks as a')
            ->join('appointments as b', 'a.appointment_id', 'b.appointment_id')
            ->join('offices as c', 'a.office_id', 'c.office_id')
            ->join('appointment_types as d', 'b.appointment_type_id', 'd.appointment_type_id')
            ->join('users as e', 'b.appointment_user_id', 'e.user_id')
            ->orderBy('appointment_track_id', 'desc')
            ->select('a.appointment_track_id', 'a.appointment_id', 'a.time_out', 'b.appointment_user_id', 'b.app_date', 'b.app_time_from',
                'b.app_time_to', 'c.office_id', 'c.office_name', 'd.appointment_type', 'e.lname', 'e.fname', 'e.mname',
                'a.remark')
            ->get();
        return $data;
    }



}
