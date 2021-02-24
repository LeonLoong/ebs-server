<?php

use Illuminate\Database\Seeder;
use App\Models\Battery;

class BatterySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $battery_list = [
            "38B20L",
            "40B20L",
            "42B20L",
            "46B24L",
            "48B19L",
            "50B24L",
            "55B24L",
            "55D23L",
            "55D26L",
            "65B24L",
            "65D26L",
            "75D23L",
            "75D26L",
            "80D23L",
            "85D23L",
            "95D26L",
            "95D31L",
            "125D31L",
            "125D36L",
            "D55L",
            "D65L",
            "D66L",
            "D74L",
            "D75L",
            "D80L",
            "D100L",
            "LN3 EFB",
            "M42",
            "Q85",
            "S95",
        ];

        // Amaron
        Battery::create([
            'battery_manufacturer_id' => 1,
            'battery_series_id' => 1,
            'battery_type_id' => 2,
            'battery_trade_in_id' => 2,
            'model' => '38B20L',
            'model_reference' => 'NS40ZL',
            'price' => 220,
            'warranty' => 0,
            'stock' => 1,
            'image' => '',
            'description' => '',
            'specifications' => '{}',
        ]);

        Battery::create([
            'battery_manufacturer_id' => 1,
            'battery_series_id' => 2,
            'battery_type_id' => 2,
            'battery_trade_in_id' => 2,
            'model' => '46B19L',
            'model_reference' => 'NS40ZL',
            'price' => 0,
            'warranty' => 0,
            'stock' => 1,
            'image' => '',
            'description' => '',
            'specifications' => '{}',
        ]);

        Battery::create([
            'battery_manufacturer_id' => 1,
            'battery_series_id' => 1,
            'battery_type_id' => 2,
            'battery_trade_in_id' => 2,
            'model' => '46B24L',
            'model_reference' => 'NS60L',
            'price' => 250,
            'warranty' => 0,
            'stock' => 1,
            'image' => '',
            'description' => '',
            'specifications' => '{}',
        ]);

        // Hitachi
        Battery::create([
            'battery_manufacturer_id' => 5,
            'battery_series_id' => 4,
            'battery_type_id' => 1,
            'battery_trade_in_id' => 1,
            'model' => '38B20L',
            'model_reference' => 'NS40ZL',
            'price' => 160,
            'warranty' => 12,
            'stock' => 1,
            'image' => '',
            'description' => '',
            'specifications' => '{}',
        ]);

        Battery::create([
            'battery_manufacturer_id' => 5,
            'battery_series_id' => 4,
            'battery_type_id' => 1,
            'battery_trade_in_id' => 2,
            'model' => '46B24L',
            'model_reference' => 'NS60L',
            'price' => 180,
            'warranty' => 12,
            'stock' => 1,
            'image' => '',
            'description' => '',
            'specifications' => '{}',
        ]);

        Battery::create([
            'battery_manufacturer_id' => 5,
            'battery_series_id' => 4,
            'battery_type_id' => 1,
            'battery_trade_in_id' => 3,
            'model' => '65D26L',
            'model_reference' => 'NS70L',
            'price' => 280,
            'warranty' => 12,
            'stock' => 1,
            'image' => '',
            'description' => '',
            'specifications' => '{}',
        ]);

        // Cworks
        Battery::create([
            'battery_manufacturer_id' => 2,
            'battery_series_id' => 0,
            'battery_type_id' => 2,
            'battery_trade_in_id' => 2,
            'model' => '50B24L',
            'model_reference' => 'NS60L',
            'price' => 180,
            'warranty' => 12,
            'stock' => 1,
            'image' => '',
            'description' => '',
            'specifications' => '{}',
        ]);
    }
}
