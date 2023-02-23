<?php

namespace App\Http\Controllers\Administrator;

use App\Models\EntranceLog;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class QRScannerController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        return view('administrator.qr-scanner');
    }


    public function show($id){
        $data = DB::table('franchises as a')
            ->leftJoin('provinces as b', 'a.province', 'b.provCode')
            ->leftJoin('cities as c', 'a.city', 'c.citymunCode')
            ->leftJoin('barangays as d', 'a.barangay', 'd.brgyCode')
            ->first();
        return $data;
    }


    public function create(){
        return view('administrator.franchise-create-update')
            ->with('data', '')
            ->with('dataid', 0);
    }

    public function store(Request $req){
        //return $req;


        // $req->validate([
        //     'student_id' => ['required'],
        //     'student_name' => ['required'],
        // ]);

        $date =  $req->date_acquired;
        $ndate = date("Y-m-d", strtotime($date)); //convert to date format UNIX

        /*$time = $req->app_time;
        $ntime = date('H:i:s',strtotime($time)); //convert to format time UNIX*/

        EntranceLog::create([
            'student_id' => $req->student_id,
            'fullname' => strtoupper($req->fullname),
            'program' => strtoupper($req->program),
            'year_level' => strtoupper($req->year_level),
            'contact_no' => strtoupper($req->contact_no),
            'date_entry' => now(),
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
    }


    public function edit($id){

        $data = \DB::table('franchises as a')
            ->leftJoin('provinces as b', 'a.province', 'b.provCode')
            ->leftJoin('cities as c', 'a.city', 'c.citymunCode')
            ->leftJoin('barangays as d', 'a.barangay', 'd.brgyCode')
            ->first();


        return view('administrator.franchise-create-update')
            ->with('data', $data)
            ->with('dataid', $id);
    }

    public function update(Request $req, $id){
        $user = Auth::user();

        $req->validate([
            'franchise_reference' => ['required'],
            'date_acquired' => ['required'],
            'operator_name' => ['required'],
            'vehicle_reference' => ['required'],
            'chassis_reference' => ['required'],
            'plate_no' => ['required'],
        ]);

        $date =  $req->date_acquired;
        $ndate = date("Y-m-d", strtotime($date)); //convert to date format UNIX


        $data = Franchise::find($id);
        $data->franchise_reference = $req->franchise_reference;
        $data->date_acquired = $ndate;
        $data->operator_name = strtoupper($req->operator_name);

        $data->province = $req->province;
        $data->city = $req->city;
        $data->barangay = $req->barangay;
        $data->street = strtoupper($req->street);
        $data->vehicle_reference = strtoupper($req->vehicle_reference);
        $data->chassis_reference = strtoupper($req->chassis_reference);
        $data->make = strtoupper($req->make);
        $data->plate_no = strtoupper($req->plate_no);
        $data->route_operation = strtoupper($req->route_operation);
        $data->cab_no = strtoupper($req->cab_no);
        $data->remarks = strtoupper($req->remarks);
        $data->sysuser = strtoupper($user->username);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ],200);
    }

    public function destroy($id){
        Franchise::destroy($id);
    }

}
