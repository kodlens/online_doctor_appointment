<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomForgotPasswordController extends Controller
{
    //

    public function index(){
        return view('forgot-password');
    }


    public function requestOtp(Request $req){

        $req->validate([
            'contact_no' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
        ],[
            'contact_no.regex' => 'Please enter a valid Philippines mobile phone number.',
        ]);

        // Generate a random 6-digit OTP
        $otp = mt_rand(100000, 999999);

        // Set the OTP expiry time (e.g., 5 minutes from now)
        $expiry = date('Y-m-d H:i', strtotime('+5 minutes'));
        
        $exist = User::where('username', $req->username)
            ->where('contact_no', $req->contact_no)
            ->exists();

        if($exist){
            $user = User::where('username', $req->username)
                ->where('contact_no', $req->contact_no)
                ->update([
                    'otp' => $otp,
                    'otp_expiry' => $expiry
                ]); 
        }else{
            return response()->json([
                'errors' => [
                    'otp' => [
                        'unknown' => ['Unknown username and contact no.']
                    ]
                ]
            ], 422);
        }

        return response()->json([
            'status' => 'otp_success' 
        ], 200);;
    }


    public function otpEntry($username){
        return view('otp-entry')
            ->with('user', $username);
    }

    public function resetPasswordWithOTP(Request $req){

        //return $req;

        $req->validate([
            'password' => ['required', 'confirmed']
        ]);

        $now = date('Y-m-d H:i');
        
        $user = User::where('username', $req->username)
            ->where('otp', $req->otp)
            ->where('otp_expiry', '<=', $now)
            ->first();
        
        if($user){
            //$user = User::find($user->user_id);
            $user->password = Hash::make($req->password);
            $user->save();

            return response()->json([
                'errors' => [
                    'otp' => ['OTP not found or OTP is expired.']
                ]
            ], 422);
        }

        return response()->json([
            'status' => 'reset'
        ], 200);
    }
}
