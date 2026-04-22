<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'OPD',               'code' => 'OPD',      'description' => 'Out-Patient Department'],
            ['name' => 'IVF Lab',           'code' => 'IVF-LAB',  'description' => 'In-Vitro Fertilisation Laboratory'],
            ['name' => 'Embryology',        'code' => 'EMBRY',    'description' => 'Embryology Department'],
            ['name' => 'Pharmacy',          'code' => 'PHARMA',   'description' => 'Pharmacy & Dispensary'],
            ['name' => 'Accounts',          'code' => 'ACCTS',    'description' => 'Accounts & Finance'],
        ];

        foreach ($departments as $data) {
            Department::updateOrCreate(['code' => $data['code']], array_merge($data, ['status' => 'active']));
        }
    }
}
