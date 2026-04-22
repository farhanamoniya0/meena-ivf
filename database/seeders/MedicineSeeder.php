<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            ['name' => 'Progesterone 200mg',  'generic_name' => 'Progesterone',         'brand' => 'Utrogestan',   'category' => 'Hormone',   'unit' => 'cap', 'reorder_level' => 50],
            ['name' => 'Gonadotropin 75IU',   'generic_name' => 'Follitropin Alpha',     'brand' => 'Gonal-F',      'category' => 'Hormone',   'unit' => 'vial','reorder_level' => 20],
            ['name' => 'hCG 5000IU',          'generic_name' => 'Human Chorionic Gono.', 'brand' => 'Ovidrel',      'category' => 'Hormone',   'unit' => 'amp', 'reorder_level' => 15],
            ['name' => 'Estradiol 2mg',       'generic_name' => 'Estradiol Valerate',    'brand' => 'Progynova',    'category' => 'Hormone',   'unit' => 'tab', 'reorder_level' => 100],
            ['name' => 'Folic Acid 5mg',      'generic_name' => 'Folic Acid',            'brand' => 'Folicin',      'category' => 'Vitamin',   'unit' => 'tab', 'reorder_level' => 200],
            ['name' => 'Cefuroxime 500mg',    'generic_name' => 'Cefuroxime Axetil',     'brand' => 'Zinnat',       'category' => 'Antibiotic','unit' => 'tab', 'reorder_level' => 100],
            ['name' => 'Metformin 500mg',     'generic_name' => 'Metformin HCl',         'brand' => 'Glucophage',   'category' => 'Tablet',    'unit' => 'tab', 'reorder_level' => 150],
            ['name' => 'Aspirin 75mg',        'generic_name' => 'Acetylsalicylic Acid',  'brand' => 'Ecosprin',     'category' => 'Tablet',    'unit' => 'tab', 'reorder_level' => 100],
        ];

        foreach ($medicines as $data) {
            $med = Medicine::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['status' => 'active'])
            );

            if ($med->batches()->count() === 0) {
                MedicineBatch::create([
                    'medicine_id'    => $med->id,
                    'batch_number'   => 'BTH-2024-' . str_pad($med->id, 3, '0', STR_PAD_LEFT),
                    'expiry_date'    => now()->addMonths(18)->format('Y-m-d'),
                    'quantity'       => rand(100, 500),
                    'purchase_price' => rand(10, 200),
                    'sale_price'     => rand(15, 250),
                ]);
            }
        }
    }
}
