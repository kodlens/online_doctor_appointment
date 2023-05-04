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
                'lname' => 'LYONS',
                'fname' => 'BELLA',
                'mname' => 'P',
                'sex' => 'FEMALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'joyce@dev.com',
                'contact_no' => '09163789585',
                'role' => 'ADMINISTRATOR',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'vinz',
                'lname' => 'SENTIMENT',
                'fname' => 'VINCENT',
                'mname' => 'P',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09216548742',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'joel',
                'lname' => 'LACAPAG',
                'fname' => 'JOEL',
                'mname' => 'P',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09216548722',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'lensey',
                'lname' => 'ONG',
                'fname' => 'LENSEY',
                'mname' => 'B',
                'sex' => 'FEMALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09216548123',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                'username' => 'richalle',
                'lname' => 'PLAZA',
                'fname' => 'RICHALLE MAE',
                'mname' => 'L',
                'sex' => 'FEMALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09216548223',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                'username' => 'jessa',
                'lname' => 'SARAGINA',
                'fname' => 'JESSA MAE',
                'mname' => 'M',
                'sex' => 'FEMALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09216548441',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                'username' => 'john',
                'lname' => 'BARINAN',
                'fname' => 'JOHN RIVIN',
                'mname' => 'O',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09216548444',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],

            
            
        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
