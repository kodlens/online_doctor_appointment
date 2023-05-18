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
                'contact_no' => '09163789585',
                'role' => 'ADMINISTRATOR',
                'password' => Hash::make('admin')
            ],
            

            [
                //1
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
                //2
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
                //3
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
                'contact_no' => '09706102876',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                //4
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
                //5
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
                //6
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




            [
                //7
                'username' => 'janjan',
                'lname' => 'CAGADAS',
                'fname' => 'JOHN MICHAEL',
                'mname' => 'O',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09706102876',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                //8
                'username' => 'roger',
                'lname' => 'LACAPAG',
                'fname' => 'ROGER',
                'mname' => 'O',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09706102876',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                //9
                'username' => 'charles',
                'lname' => 'VARG',
                'fname' => 'CHARLES',
                'mname' => 'O',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09706102876',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                //10
                'username' => 'ram',
                'lname' => 'CORDOVAN',
                'fname' => 'RAM',
                'mname' => 'O',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09706102876',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                //11
                'username' => 'quincy',
                'lname' => 'CORDOVAN',
                'fname' => 'QUINCY',
                'mname' => 'O',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09706102876',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],
            [
                //12
                'username' => 'jenny',
                'lname' => 'SIMBAJON',
                'fname' => 'JENNY',
                'mname' => 'O',
                'sex' => 'MALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                //'email' => 'doctor@dev.com',
                'contact_no' => '09706102876',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],



            //staff account default
            [
                'username' => 'staff',
                'lname' => 'ONG',
                'fname' => 'LENSEY',
                'mname' => 'P',
                'sex' => 'FEMALE',
                'province' => '1042',
                'city' => '104215',
                'barangay' => '104215027',
                'street' => 'P-6',
                'contact_no' => '09163789585',
                'role' => 'STAFF',
                'password' => Hash::make('staff')
            ],



            
            
        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
