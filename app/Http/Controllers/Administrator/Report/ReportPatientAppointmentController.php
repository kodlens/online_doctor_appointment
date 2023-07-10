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

        $data = Appointment::with(['user', 'schedule'])
            ->whereBetween('appointment_date', [$date_start, $date_end])
            ->where('is_archived', 0)
            ->get();

        return $data;
    }




    public function indexReportPatientByLocation(){
        return view('administrator.report.report-patient-by-location');
    }

    public function getDataReportPatientByLocation(Request $req){
        
        $date_start = date('Y-m-d', strtotime($req->start));
        $date_end = date('Y-m-d', strtotime($req->end));
        $data = '';

        //return $req->bykey;

        if($req->bykey == 'province'){

            $data = DB::select('select provDesc as keyplace, count(*) as no_patient from patients a
                join appointments b on a.appointment_id = b.appointment_id
                join provinces c on a.province = c.provCode
                join cities d on a.city = d.citymunCode
                join barangays e on a.barangay = brgyCode
                where b.appointment_date between ? and ?
                and b.is_archived = 0 group by province
            ', [$date_start, $date_end]);
        }

        if($req->bykey == 'city'){

            $data = DB::select('select citymunDesc as keyplace, count(*) as no_patient from patients a
                join appointments b on a.appointment_id = b.appointment_id
                join provinces c on a.province = c.provCode
                join cities d on a.city = d.citymunCode
                join barangays e on a.barangay = brgyCode
                where b.appointment_date between ? and ?
                and b.is_archived = 0 group by city
            ', [$date_start, $date_end]);
        }

        if($req->bykey == 'barangay'){

            $data = DB::select('select brgyDesc as keyplace, count(*) as no_patient from patients a
                join appointments b on a.appointment_id = b.appointment_id
                join provinces c on a.province = c.provCode
                join cities d on a.city = d.citymunCode
                join barangays e on a.barangay = brgyCode
                where b.appointment_date between ? and ?
                and b.is_archived = 0 group by barangay
            ', [$date_start, $date_end]);
        }
        
        return $data;
    }




    //print no show
    public function indexAppointmentNoShow(){
        return view('administrator.report.report-appointment-no-show');
    }

    public function getDataAppointmentNoShow(Request $req){
        $date_start = date('Y-m-d', strtotime($req->start));
        $date_end = date('Y-m-d', strtotime($req->end));

        $data = Appointment::with(['user', 'schedule'])
            ->where('status', 1)
            ->where('is_arrived', 0)
            ->where('is_served', 0)
            ->where('is_archived', 0)
            ->whereBetween('appointment_date', [$date_start, $date_end])
            ->get();

        return $data;
    }


    public function indexAppointmentIndian(Request $req){
        return view('administrator.report.report-appointment-indian');
    }
    
    public function getAppointmentIndian(Request $req){
        $date_start = date('Y-m-d', strtotime($req->start));
        $date_end = date('Y-m-d', strtotime($req->end));

        // $data = DB::select('SELECT * FROM (SELECT b.lname, b.fname, b.mname,a.user_id, COUNT(*) AS count_app 
        //     FROM appointments a
        //     JOIN users b ON a.user_id = b.user_id
        //     WHERE a.appointment_date BETWEEN ? AND ?
        //     AND a.is_arrived = 0 AND a.status = 1
        //     GROUP BY a.user_id) AS tbl1 WHERE tbl1.count_app > 3', [$date_start, $date_end]);
        
        $data = DB::select('select * from (select b.lname, b.fname, b.mname,
            a.user_id, count(*) as count_app from appointments a
            join users b on a.user_id = b.user_id
            where a.appointment_date between ? and curdate() and a.is_arrived = 0 and a.status = 1 group by a.user_id) as tbl1
            where tbl1.count_app > 3', [$date_start]);

        return $data;
    }


    public function indexLoyalCustomers(){
        return view('administrator.report.report-loyal-customers');
    }
    public function getLoyalCustomers(Request $req){
        $date_start = date('Y-m-d', strtotime($req->start));
        $date_end = date('Y-m-d', strtotime($req->end));

        // $data = DB::select('SELECT * FROM (SELECT b.lname, b.fname, b.mname,a.user_id, COUNT(*) AS count_app 
        //     FROM appointments a
        //     JOIN users b ON a.user_id = b.user_id
        //     WHERE a.appointment_date BETWEEN ? AND ?
        //     AND a.is_arrived = 0 AND a.status = 1
        //     GROUP BY a.user_id) AS tbl1 WHERE tbl1.count_app > 3', [$date_start, $date_end]);
        
        $data = DB::select('select * from (select b.lname, b.fname, b.mname,
            a.user_id, count(*) as count_app from appointments a
            join users b on a.user_id = b.user_id
            where a.appointment_date between ? and ? and a.is_served = 1 and a.status = 1 group by a.user_id) as tbl1
            where tbl1.count_app > 5', [$date_start, $date_end]);

        return $data;
    }
}

