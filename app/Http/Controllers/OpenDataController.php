<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\MaxPatient;
use App\Models\Vacation;

class OpenDataController extends Controller
{
    //

    public function loadOpenScehdules(Request $req){

        $day = date("D", strtotime($req->appdate));
        //return $day;

        return Schedule::where($day, 1) //select * from table where wed = 1
            ->orderBy('schedule_id', 'asc')
            ->get();
    }

    public function loadMaxNo(){
        return MaxPatient::where('id', 1)->first();
    }

    public function loadVacations(Request $req){
        $date = date("Y-m-d", strtotime($req->appdate));
        //return $date;
        return Vacation::where('vacation_date','>=', $date)
            ->get();
    }

    public function loadDoctorVacations(Request $req){
        $month = date("m");
        $year = date("Y");
        
        $startDate = date('Y-m-d', strtotime("$year-$month-01"));
       

        //$endDate = clone $startDate;
        $endDate = date('Y-m-t', strtotime("$year-$month-01"));
        //return $endDate;

        //return $date;
        return Vacation::whereBetween('vacation_date',[$startDate, $endDate])
            ->get();
    }
}
