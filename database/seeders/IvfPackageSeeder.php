<?php

namespace Database\Seeders;

use App\Models\IvfPackage;
use Illuminate\Database\Seeder;

class IvfPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'              => 'Basic IVF Package',
                'description'       => 'Standard IVF cycle for first-time patients',
                'total_cost'        => 120000,
                'duration_days'     => 30,
                'included_services' => "- Initial consultation\n- Ovarian stimulation monitoring\n- Egg retrieval (OPU)\n- Fertilisation & embryo culture\n- Embryo transfer (ET)\n- Pregnancy test",
            ],
            [
                'name'              => 'Premium IVF Package',
                'description'       => 'Comprehensive IVF with ICSI and PGT',
                'total_cost'        => 180000,
                'duration_days'     => 45,
                'included_services' => "- All Basic Package services\n- ICSI (Intra-Cytoplasmic Sperm Injection)\n- Embryo freezing (up to 5)\n- Progesterone support\n- Andrology consultation\n- Nutritional counseling",
            ],
            [
                'name'              => 'IUI Package',
                'description'       => 'Intrauterine Insemination cycle',
                'total_cost'        => 25000,
                'duration_days'     => 14,
                'included_services' => "- Consultation\n- Follicle monitoring\n- Semen preparation\n- IUI procedure\n- Luteal phase support",
            ],
            [
                'name'              => 'FET Package',
                'description'       => 'Frozen Embryo Transfer cycle',
                'total_cost'        => 60000,
                'duration_days'     => 21,
                'included_services' => "- Endometrial preparation\n- Monitoring scans\n- Thawing & transfer\n- Luteal phase support\n- Pregnancy test",
            ],
        ];

        foreach ($packages as $data) {
            IvfPackage::updateOrCreate(['name' => $data['name']], array_merge($data, ['status' => 'active']));
        }
    }
}
