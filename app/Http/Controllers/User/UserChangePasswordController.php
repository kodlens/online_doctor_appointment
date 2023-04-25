<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

class UserChangePasswordController extends Controller
{
    //

    public function index(){
        return view('user.user-change-password');
    }


    public function changePassword(Request $req){
        
        $user = Auth::user();
    }
}
