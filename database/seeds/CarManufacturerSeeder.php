<?php

use App\Models\CarManufacturer;
use Illuminate\Database\Seeder;

class CarManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CarManufacturer::create([
            'manufacturer' => 'Honda',
            'image' => 'Honda_image.png',
            'image_size' => 10870,
        ]);

        CarManufacturer::create([
            'manufacturer' => 'Perodua',
            'image' => 'Perodua_image.png',
            'image_size' => 69998,
        ]);

        CarManufacturer::create([
            'manufacturer' => 'Proton',
            'image' => 'Proton_image.png',
            'image_size' => 34095,
        ]);

        CarManufacturer::create([
            'manufacturer' => 'Toyota',
            'image' => 'Toyota_image.png',
            'image_size' => 16721,
        ]);
    }
}
