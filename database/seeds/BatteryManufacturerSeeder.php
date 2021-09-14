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
        BatteryManufacturer::create([
            'manufacturer' => 'Amaron',
            'image' => 'Amaron_image.svg',
            'image_size' => 9157,
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Cworks',
            'image' => NULL,
            'image_size' => NULL,
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Exide',
            'image' => NULL,
            'image_size' => NULL,
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Fuji',
            'image' => NULL,
            'image_size' => NULL,
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Hitachi',
            'image' => 'Hitachi_image.svg',
            'image_size' => 1682,
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Niko',
            'image' => NULL,
            'image_size' => NULL,
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Oursun',
            'image' => NULL,
            'image_size' => NULL,
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Sprinter',
            'image' => NULL,
            'image_size' => NULL,
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Tuflong',
            'image' => '',
            'image_size' => NULL,
            'description_bm' => NULL,
            'description_en' => '',
        ]);

        BatteryManufacturer::create([
            'manufacturer' => 'Varta',
            'image' => 'Varta_image.svg',
            'image_size' => 1499,
            'description_bm' => '',
            'description_en' => '',
        ]);
    }
}
