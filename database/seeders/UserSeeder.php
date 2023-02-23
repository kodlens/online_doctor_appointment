<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'username' => 'admin',
                'lname' => 'RL',
                'fname' => 'Joyce',
                'mname' => 'P',
                'sex' => 'FEMALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                'email' => 'joyce@dev.com',
                'contact_no' => '09163789585',
                'role' => 'ADMINISTRATOR',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'doctor',
                'lname' => 'DELA CRUZ',
                'fname' => 'JUAN',
                'mname' => 'P',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                'email' => 'doctor@dev.com',
                'contact_no' => '09216548742',
                'role' => 'DOCTOR',
                'password' => Hash::make('a')
            ],
            
            
        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
