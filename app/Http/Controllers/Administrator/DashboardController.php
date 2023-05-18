<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AppointmentType;


class DashboardController extends Controller
{
    //

    public function index(){
        return view('administrator.dashboard');
    }

}
