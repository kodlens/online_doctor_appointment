<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaxPatient;

class MaxPatientController extends Controller
{
    //

    public function index(){
        $max = MaxPatient::where('id', 1)->first();
        return view('administrator.max-patients')
            ->with('max', $max->max);
    }



    public function store(Request $req){
        
    }
    

    public function updateMaxPatient(Request $req){
        $req->validate([
            'max' => ['required', 'numeric']
        ]);


        $data = MaxPatient::where('id', 1)
            ->update([
                'max' => $req->max
            ]);

        return response()->json([
            'status' => 'update'
        ], 200);

    }

    public function getMaxPatients(Request $req){
        
        return MaxPatient::where('id', 1)
            ->first();
    }

}
