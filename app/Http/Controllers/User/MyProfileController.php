<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    //


    public function index()
    {
        return view('user.my-profile');
    }

    public function getMyProfile(){
        return User::where('user_id', Auth::user()->user_id)
            ->with(['province', 'city', 'barangay'])
            ->first();
    }

    public function store(Request $req){

        $req->validate([
            'lname' => ['required'],
            'fname' => ['required'],
            'sex' => ['required'],
            'contact_no' => ['required'],
            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
        ]);

        $id = Auth::user()->user_id;

        $data = User::find($id);
        $data->lname = strtoupper($req->lname);
        $data->fname = strtoupper($req->fname);
        $data->mname = strtoupper($req->mname);
        $data->extension = strtoupper($req->extension);
        $data->sex = strtoupper($req->sex);
        $data->contact_no = $req->contact_no;
        $data->province = $req->province;
        $data->city = $req->city;
        $data->barangay = $req->barangay;
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);




    }





}
