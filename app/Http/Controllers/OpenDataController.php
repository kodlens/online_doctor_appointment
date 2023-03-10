<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

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
}
