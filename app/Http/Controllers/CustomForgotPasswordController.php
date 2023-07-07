<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AppSetting;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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
        
        $user = User::where('username', $req->username)
            ->where('contact_no', $req->contact_no);

        $is_sms_enable = AppSetting::where('dword', 'ENABLE_SMS')->first(); //get ip sms setting
        $proxy = AppSetting::where('dword', 'PROXY_SMS')->first(); //get proxy gateway setting


        if($user->exists()){
            $user = $user->first();

            if($is_sms_enable->value > 0){
    
                $msg = 'Your OTP is: '. $otp .'. Do not share this code with anyone. Thank you.';
                $is_sent = 0;
                $sms_error_msg = '';

                $url = $proxy->value . '/'.$msg.'/'. $user->contact_no;
                //return $url;

                try{
                    Http::withHeaders([
                        'Content-Type' => 'text/plain'
                    ])->get($url);
                    $is_sent = 1;
                }catch(\Exception $e){
                    $is_sent = 0;
                    $sms_error_msg = $e->getMessage();
                } //just hide the error
               
                // try{
                //     Http::withHeaders([
                //         'Content-Type' => 'text/plain'
                //     ])->post($ip_sms .'/services/api/messaging?Message='.$msg.'&To='.$user->contact_no.'&Slot=1', []);
                // }catch(\Exception $e){} //just hide the error
            }

            $user->otp = $otp;
            $user->otp_expiry = $expiry;
            $user->save();

            // $user = User::where('username', $req->username)
            //     ->where('contact_no', $req->contact_no)
            //     ->update([
            //         'otp' => $otp,
            //         'otp_expiry' => $expiry
            //     ]); 
        }else{
            return response()->json([
                'errors' => [
                    'otp' => [
                        'unknown' => ['Unknown username and contact no.']
                    ]
                ]
            ], 422);
        }


        if($is_sent > 0){
            return response()->json([
                'status' => 'otp_success' 
            ], 200);
        }else{
            return response()->json([
                'errors' => [
                    'otp' => [
                        'sms' => ['Error sending OTP. Please try again. ' . $sms_error_msg]
                    ]
                ]
            ], 422);
        }
        
    }


    public function otpEntry($username){
        return view('otp-entry')
            ->with('user', $username);
    }

    public function resetPasswordWithOTP(Request $req){

        //return $req;

        $req->validate([
            'otp' => ['required'],
            'password' => ['required', 'confirmed']
        ]);

        $now = date('Y-m-d H:i:s');
        
        $user = User::where('username', $req->username)
            ->where('otp', $req->otp)
            ->where('otp_expiry', '>=', $now);

        //return $user->exists();

        if($user->exists()){
            //$user = User::find($user->user_id);
            $user = $user->first();

            //return $user->first();

            //$obj = User::find($user->user_id);

            $user->password = Hash::make($req->password);
            $user->save();

            return response()->json([
                'status' => 'reset'
            ], 200);
        }

        return response()->json([
            'errors' => [
                'otp' => ['OTP not found or OTP is expired.']
            ]
        ], 422);

        
    }
}
