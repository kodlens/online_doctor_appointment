<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class OpenDataController extends Controller
{
    //

    public function loadOpenScehdules(){
        return Schedule::orderBy('schedule_id', 'asc')
            ->get();
    }
}
