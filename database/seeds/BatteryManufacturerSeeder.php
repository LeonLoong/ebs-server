<?php

use App\Models\BatteryManufacturer;
use Illuminate\Database\Seeder;

class BatteryManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $batteryManufacturer_list = [
            'Amaron',
            'Cworks',
            'Exide',
            'Fuji',
            'Hitachi',
            'Niko',
            'Oursun',
            'Sprinter',
            'Varta',
        ];

        BatteryManufacturer::create([
            'manufacturer' => 'Amaron',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Cworks',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Exide',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Fuji',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Hitachi',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Niko',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Oursun',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Sprinter',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Varta',
            'logo' => '',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);
    }
}
