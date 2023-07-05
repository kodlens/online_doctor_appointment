<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AppSetting;

class SMSController extends Controller
{
    //

    public function sendSMS($msg, $contact){
        //$otp = mt_rand(100000, 999999);

        $is_sms_enable = AppSetting::where('dword', 'ENABLE_SMS')->first(); //get ip sms setting
        $ip_sms = AppSetting::where('dword', 'IP_SMS_GATEWAY')->first(); //get ip sms setting


        if($is_sms_enable->value > 0){
            //return $ip_sms->value;

            //$msg = 'Your OTP for activation is: '. $otp .'. Please enter this code to activate the account. Thank you!';
            
            try{
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->post($ip_sms->value .'/services/api/messaging?Message='.$msg.'&To='.$contact.'&Slot=1', []);

                return response()->json([
                    'status' => 'sms_sent'
                ], 200);

            }catch(\Exception $e){
                return response()->json([
                    'status' => 'sms_error, ' . $e->getMessage()
                ], 422);
            } //just hide the error
        }
    }
}
