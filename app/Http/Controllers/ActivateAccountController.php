<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


use Auth;
use App\Models\User;

class ActivateAccountController extends Controller
{
    //


    public function index(){
        return view('activate-account');
    }


    public function askForOTP(){

        //create random 6digit OTP
        $otp = mt_rand(100000, 999999);
        
        $userId = Auth::user()->user_id;

        $data = User::find($userId);
        $data->otp_activate = $otp;
        

        if(env('ENABLE_SMS') == 1){
    
            $msg = 'Your OTP for activation is: '. $otp .'. Please enter this code to activate the account. Thank you!';
            
            try{
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->post('http://'. env('IP_SMS_GATEWAY') .'/services/api/messaging?Message='.$msg.'&To='.$data->contact_no.'&Slot=1', []);
            }catch(\Exception $e){} //just hide the error
        }

        $data->save();

        return response()->json([
            'status' => 'otp'
        ], 200);
    }

    public function verifyOTPActivation(Request $req){


        $req->validate([
            'otp' => ['required']
        ]);

        $otp = $req->otp;
        $user = Auth::user();

        if($otp == $user->otp_activate){
            $data = User::find($user->user_id);
            $data->is_activate = 1;
            $data->save();
            return response()->json([
                'status' => 'activated'
            ], 200);
        }

        return response()->json([
            'status' => 'denied'
        ], 422);
        
    }
}
