<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['service_code' => 'SVC-001', 'name' => 'Semen Analysis',              'category' => 'Lab',       'charge' => 800],
            ['service_code' => 'SVC-002', 'name' => 'AMH Test',                    'category' => 'Lab',       'charge' => 2500],
            ['service_code' => 'SVC-003', 'name' => 'Hormonal Panel (FSH/LH/E2)',  'category' => 'Lab',       'charge' => 1800],
            ['service_code' => 'SVC-004', 'name' => 'Prolactin Test',              'category' => 'Lab',       'charge' => 600],
            ['service_code' => 'SVC-005', 'name' => 'CBC (Complete Blood Count)',  'category' => 'Lab',       'charge' => 500],
            ['service_code' => 'SVC-006', 'name' => 'Blood Grouping',              'category' => 'Lab',       'charge' => 300],
            ['service_code' => 'SVC-007', 'name' => 'Transvaginal Ultrasound',     'category' => 'Imaging',   'charge' => 1200],
            ['service_code' => 'SVC-008', 'name' => 'Follicular Monitoring',       'category' => 'Imaging',   'charge' => 800],
            ['service_code' => 'SVC-009', 'name' => 'Hysterosalpingography (HSG)', 'category' => 'Procedure', 'charge' => 5000],
            ['service_code' => 'SVC-010', 'name' => 'IUI Procedure',               'category' => 'IVF',       'charge' => 8000],
            ['service_code' => 'SVC-011', 'name' => 'IVF Embryo Transfer',         'category' => 'IVF',       'charge' => 25000],
            ['service_code' => 'SVC-012', 'name' => 'Egg Retrieval (OPU)',         'category' => 'IVF',       'charge' => 15000],
            ['service_code' => 'SVC-013', 'name' => 'Embryo Freezing',             'category' => 'IVF',       'charge' => 10000],
            ['service_code' => 'SVC-014', 'name' => 'Sperm Freezing',              'category' => 'IVF',       'charge' => 5000],
            ['service_code' => 'SVC-015', 'name' => 'Laparoscopy',                 'category' => 'Procedure', 'charge' => 30000],
            ['service_code' => 'SVC-016', 'name' => 'Hysteroscopy',                'category' => 'Procedure', 'charge' => 20000],
            ['service_code' => 'SVC-017', 'name' => 'OPD Consultation',            'category' => 'OPD',       'charge' => 500],
            ['service_code' => 'SVC-018', 'name' => 'Follow-up Visit',             'category' => 'OPD',       'charge' => 300],
        ];

        foreach ($services as $svc) {
            Service::firstOrCreate(
                ['name' => $svc['name']],
                array_merge($svc, ['status' => 'active'])
            );
        }
    }
}
