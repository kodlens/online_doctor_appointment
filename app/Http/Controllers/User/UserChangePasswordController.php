<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Auth;
use App\Models\User;


class UserChangePasswordController extends Controller
{
    //

    public function index(){
        return view('user.user-change-password');
    }


    public function store(Request $req){
        
        $req->validate([
            'old_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:2'],

        ]);

        $user = Auth::user();

        if(Hash::check($req->password, $user->password)){
            return response()->json([
                'errors' => [
                    'message' => "Something is error.",
                    'old_password' => ['Password error.']
                ]
            ], 422);
        }

        $data = User::find($user->user_id);
        $data->password = Hash::make($req->password);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }
}
