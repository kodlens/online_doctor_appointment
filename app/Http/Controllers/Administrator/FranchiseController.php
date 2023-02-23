<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Franchise;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FranchiseController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        return view('administrator.franchise');
    }

    public function getFranchises(Request $req){
        $sort = explode('.', $req->sort_by);

        $franchise = $req->franchise;
        $operator = $req->operator;


        $data = \DB::table('franchises as a')
            ->where('a.franchise_reference', 'like', $franchise . '%')
            ->where('a.operator_name', 'like', '%'. $operator . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function show($id){
        $data = \DB::table('franchises as a')
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

        /*$time = $req->app_time;
        $ntime = date('H:i:s',strtotime($time)); //convert to format time UNIX*/

        Franchise::create([
            'franchise_reference' => $req->franchise_reference,
            'date_acquired' => $ndate,
            'operator_name' => strtoupper($req->operator_name),
            'province' => strtoupper($req->province),
            'city' => strtoupper($req->city),
            'barangay' => strtoupper($req->barangay),
            'street' => strtoupper($req->street),

            'vehicle_reference' => strtoupper($req->vehicle_reference),
            'chassis_reference' => strtoupper($req->chassis_reference),
            'make' => strtoupper($req->make),
            'plate_no' => strtoupper($req->plate_no),

            'route_operation' => strtoupper($req->route_operation),
            'cab_no' => strtoupper($req->cab_no),
            'sysuser' => strtoupper($user->username),

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

    public function showQRPage($qr){
        return view('administrator.franchise-show-qr')
            ->with('qr', $qr);
    }

}
