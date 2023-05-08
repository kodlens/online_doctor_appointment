<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
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
                'schedule_id' => 2,
                'user_id' => 2,
                'appointment_date' => '2023-05-10',
                'illness_history' => 'sakit tiyan',
                'status' => 0,
            ],
            [
                'schedule_id' => 2,
                'user_id' => 3,
                'appointment_date' => '2023-05-10',
                'illness_history' => 'sakit tiyan',
                'status' => 0,
            ],
            [
                'schedule_id' => 2,
                'user_id' => 4,
                'appointment_date' => '2023-05-10',
                'illness_history' => 'sakit tiyan',
                'status' => 0,
            ],


            [
                'schedule_id' => 2,
                'user_id' => 2,
                'appointment_date' => '2023-05-11',
                'illness_history' => 'sakit tiyan',
                'status' => 2,
            ],

        ];

        \App\Models\Appointment::insertOrIgnore($data);

    }
}
