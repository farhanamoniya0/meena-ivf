<?php

namespace Database\Seeders;

use App\Models\Consultant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultantSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Consultant::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $consultants = [
            [
                'name'             => 'Dr. Lubna Yasmin',
                'specialty'        => 'Chief Consultant',
                'qualifications'   => '',
                'phone'            => '',
                'email'            => 'lubna.yasmin@meenaivf.com',
                'consultation_fee' => 1200,
                'status'           => 'active',
            ],
            [
                'name'             => 'Dr. Natasha Tiluttoma Aleem',
                'specialty'        => 'Senior Consultant',
                'qualifications'   => '',
                'phone'            => '',
                'email'            => 'natasha.aleem@meenaivf.com',
                'consultation_fee' => 1500,
                'status'           => 'active',
            ],
        ];

        foreach ($consultants as $data) {
            Consultant::create($data);
        }
    }
}
