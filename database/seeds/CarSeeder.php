<?php

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Toyota
        Car::create([
            'manufacturer_id' => 4,
            'model' => 'Vellfire',
            'description' => NULL,
            'image' => NULL,
            'image_size' => NULL,
        ]);

        Car::create([
            'manufacturer_id' => 4,
            'model' => 'Vios 1st',
            'description' => '2002-2007',
            'image' => NULL,
            'image_size' => NULL,
        ]);

        Car::create([
            'manufacturer_id' => 4,
            'model' => 'Vios 2nd',
            'description' => '2007-2013',
            'image' => NULL,
            'image_size' => NULL,
        ]);
    }
}
