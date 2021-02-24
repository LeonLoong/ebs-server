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
        $carManufacturer_list = [
            'Perodua',
            'Proton',
            'Toyota',
        ];

        CarManufacturer::create([
            'manufacturer' => 'Perodua',
            'logo' => '',
        ]);

        CarManufacturer::create([
            'manufacturer' => 'Proton',
            'logo' => '',
        ]);

        CarManufacturer::create([
            'manufacturer' => 'Toyota',
            'logo' => '',
        ]);
    }
}
