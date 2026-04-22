<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ConsultantSeeder::class,
            DepartmentSeeder::class,
            IvfPackageSeeder::class,
            MedicineSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
