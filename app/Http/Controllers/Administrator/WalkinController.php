<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class WalkinController extends Controller
{
    //


    public function index(){
        return view('administrator.walkin');
    }


    public function getWalkins(Request $req){
    
        $sort = explode('.', $req->sort_by);
        
        $date = $req->appdate;
        $todayDate = date('Y-m-d');
       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX

        $data = Appointment::with(['user', 'schedule', 'patients'])
            ->where('appointment_date', $todayDate)
            ->whereHas('user', function($q) use ($req){
                $q->where('lname', 'like', '%'. $req->name . '%');
            })
            ->where('is_archived', 0)
            ->where('is_walkin', 1)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function create(Request $req){
        return view('administrator.walkin-create-edit');
    }

    public function store(Request $req){
    
    }
}
