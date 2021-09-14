<?php

use App\Models\BatteryTradeIn;
use Illuminate\Database\Seeder;

class BatteryTradeInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $battery_tradeIn_price_list = [
            20,
            30,
            40,
            50,
            60,
            70,
            80,
            90,
            100,
        ];

        foreach ($battery_tradeIn_price_list as $battery_tradeIn_price) {
            BatteryTradeIn::create([
                'price' => $battery_tradeIn_price,
            ]);
        };
    }
}
