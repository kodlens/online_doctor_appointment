<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        $data = [
            [
                'dword' => 'ENABLE_SMS',
                'value' => 1,
            ],
            [
                'dword' => 'IP_SMS_GATEWAY',
                'value' => 'http://192.168.43.21:1688',
            ],
            [
                'dword' => 'PROXY_SMS',
                'value' => 'https://ngrok-link/sms',
            ],
           

        ];

        \App\Models\AppSetting::insertOrIgnore($data);

    }
}
