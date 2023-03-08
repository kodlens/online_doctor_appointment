<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
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
                'time_from' => '14:00:00',
                'time_end' => '15:00:00',
                'max_no' => 3,
            ],
            [
                'time_from' => '15:00:01',
                'time_end' => '16:00:00',
                'max_no' => 3,
            ],
            [
                'time_from' => '16:00:01',
                'time_end' => '17:00:00',
                'max_no' => 3,
            ],
            [
                'time_from' => '17:00:01',
                'time_end' => '18:00:00',
                'max_no' => 3,
            ],

        ];

        \App\Models\Schedule::insertOrIgnore($data);
    }
}
