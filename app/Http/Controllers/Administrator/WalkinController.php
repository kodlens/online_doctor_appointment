<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use Auth;

class WalkinController extends Controller
{
    //


    public function index(){
        return view('administrator.walkin');
    }


    public function getWalkins(Request $req){
        $sort = explode('.', $req->sort_by);
        
       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX

        $data = Appointment::with(['user', 'schedule', 'patients'])
            ->whereBetween('appointment_date', [$req->start, $req->end])
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
        
        //return $req;

        $req->validate([
            'patients' => ['required'],
            'patients.*.lname' => ['required'],
            'patients.*.fname' => ['required'],
            'patients.*.age' => ['required'],
            'patients.*.sex' => ['required'],
            'patients.*.province' => ['required'],
            'patients.*.city' => ['required'],
            'patients.*.barangay' => ['required'],
            'formatted_appointment_date' => ['required'],
            'schedule_id' => ['required']

        ]);


        $appdate = date("Y-m-d", strtotime($req->formatted_appointment_date));

        //return $req;
        $user = Auth::user();

        $appointment = Appointment::create([
            'user_id' => $user->user_id,
            'schedule_id' => $req->schedule_id,
            'appointment_date' => $appdate,
            'is_walkin' => 1
        ]);

        $patient = [];
        foreach($req->patients as $item){
            array_push($patient, [
                'appointment_id' => $appointment->appointment_id,
                'lname' => strtoupper($item['lname']),
                'fname' => strtoupper($item['fname']),
                'mname' => strtoupper($item['mname']),
                'sex' => strtoupper($item['sex']),
                'age' => $item['age'],
                'illness' => $item['illness'],
                'province' => $item['province'],
                'city' => $item['city'],
                'barangay' => $item['barangay'],
                'street' => $item['street'],
            ]);
        }

        Patient::insert($patient);

        return response()->json([
            'status' => 'saved'
        ], 200);

    }
}
