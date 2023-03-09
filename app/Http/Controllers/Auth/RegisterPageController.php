<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class RegisterPageController extends Controller
{
    //

    public function index(){
        return view('register-page');
    }
    public function store(Request $req){

        $validate = $req->validate([
            'username' => ['required', 'max:50', 'unique:users'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],
            'mname' => ['required', 'string', 'max:100'],
            'sex' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'confirmed'],
            'role' => ['required', 'string'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'barangay' => ['required', 'string'],
        ]);

        User::create([
            'username' => $req->username,
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => strtoupper($req->mname),
            'suffix' => strtoupper($req->suffix),
            'suffix' => $req->suffix,
            'province' => $req->province,
            'city' => $req->city,
            'barangay' => $req->barangay,
            'street' => $req->street,
            'contact_no' => $req->contact_no,
            'role' => $req->role,
            'password' => Hash::make($req->password),
        ]);

        return response()->json([
            'status' => 'saved'
        ]);
    }
}
