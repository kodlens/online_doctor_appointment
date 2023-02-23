<?php

namespace App\Http\Controllers;

use App\Models\AppointmentType;
use Illuminate\Http\Request;

class AppointmentTypeOpenController extends Controller
{
    //

    public function getAppointmentTypes(){
        return AppointmentType::where('is_active', 1) //only active appointment type return
        ->orderBy('appointment_type', 'asc')->get();
    }

}
