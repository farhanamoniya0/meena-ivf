<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User',      'email' => 'admin@meenaivf.com',      'role' => 'admin',       'phone' => '01711000001'],
            ['name' => 'Dr. Rahman',                  'email' => 'doctor@meenaivf.com',          'role' => 'doctor',      'phone' => '01711000002'],
            ['name' => 'Dr. Lubna Yasmin',            'email' => 'lubna.yasmin@meenaivf.com',    'role' => 'consultant',  'phone' => '01711000003'],
            ['name' => 'Dr. Natasha Tiluttoma Aleem', 'email' => 'natasha.aleem@meenaivf.com',   'role' => 'consultant',  'phone' => '01711000009'],
            ['name' => 'Billing Staff',    'email' => 'billing@meenaivf.com',    'role' => 'billing',     'phone' => '01711000004'],
            ['name' => 'Accountant',       'email' => 'accounts@meenaivf.com',   'role' => 'accountant',  'phone' => '01711000005'],
            ['name' => 'Pharmacy Staff',   'email' => 'pharmacy@meenaivf.com',   'role' => 'pharmacy',    'phone' => '01711000006'],
            ['name' => 'Lab Technician',   'email' => 'lab@meenaivf.com',        'role' => 'lab',         'phone' => '01711000007'],
            ['name' => 'Reception Staff',  'email' => 'reception@meenaivf.com',  'role' => 'reception',   'phone' => '01711000008'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, ['password' => Hash::make('password123'), 'is_active' => true])
            );
        }
    }
}
