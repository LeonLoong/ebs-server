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
        $car_list = [
            "Vellfire",
            "Vellfire (Auto Start-Stop)",
        ];

        // Toyota
        Car::create([
            'car_manufacturer_id' => 3,
            'model' => 'Vellfire',
        ]);
    }
}
