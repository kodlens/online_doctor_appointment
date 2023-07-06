<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


use Auth;
use App\Models\User;
use App\Models\AppSetting;

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
        
        $is_sms_enable = AppSetting::where('dword', 'ENABLE_SMS')->first(); //get ip sms setting
        //$ip_sms = AppSetting::where('dword', 'IP_SMS_GATEWAY')->first(); //get ip sms setting
        $proxy = AppSetting::where('dword', 'PROXY_SMS')->first(); //get proxy gateway setting

        

        if($is_sms_enable->value > 0){
            //return $data->contact_no;
            $msg = 'Your OTP for activation is: '. $otp .'. Please enter this code to activate the account. Thank you!';
            
            // try{
            //     Http::withHeaders([
            //         'Content-Type' => 'text/plain'
            //     ])->post('http://'. env('IP_SMS_GATEWAY') .'/services/api/messaging?Message='.$msg.'&To='.$data->contact_no.'&Slot=1', []);
            // }catch(\Exception $e){} //just hide the error
            $is_sent = 0;
            try{
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->get($proxy->value . '/'.$msg.'/'. $data->contact_no);
                $is_sent = 1;
            }catch(\Exception $e){
                $is_sent = 0;
            } //just hide the error
        }

        $data->save();

        if($is_sent > 0){
            return response()->json([
                'status' => 'otp'
            ], 200);
        }else{
            return response()->json([
                'status' => 'sms_error'
            ], 422);
        }
        
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



    public function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg)  {           
        $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
        $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
        $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
        $url = "http://gateway.onewaysms.com.au:10001/".$query_string;       
        $fd = @implode ('', file ($url));      
        if ($fd)  {                       
            if ($fd > 0) {
                Print("MT ID : " . $fd);
                $ok = "success";
            }        
            else {
                print("Please refer to API on Error : " . $fd);
                $ok = "fail";
            }
        }           
        else {                       
            // no contact with gateway                      
            $ok = "fail";       
        }           
        return $ok;  
    }  
        //Print("Sending to one way sms " . gw_send_sms("apiusername", "apipassword", "senderid", "61412345678", "test message"));

}
