<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MaxPatientSeeder extends Seeder
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
                'max' => 3
            ],
            
        ];

        \App\Models\MaxPatient::insertOrIgnore($data);
    }
}
