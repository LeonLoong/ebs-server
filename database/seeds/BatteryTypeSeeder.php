<?php

use App\Models\BatteryType;
use Illuminate\Database\Seeder;

class BatteryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BatteryType::create([
            'type' => '',
            'pros_bm' => '{}',
            'pros_en' => '{}',
            'cons_bm' => '{}',
            'cons_en' => '{}',
            'description_bm' => 'Sel Basah',
            'description_en' => 'Low Maintenance',
        ]);

        BatteryType::create([
            'type' => 'MF',
            'pros_bm' => '{}',
            'pros_en' => '{}',
            'cons_bm' => '{}',
            'cons_en' => '{}',
            'description_bm' => 'Sel Kering',
            'description_en' => 'Maintenance Free',
        ]);

        BatteryType::create([
            'type' => 'EFB',
            'pros_bm' => '{}',
            'pros_en' => '{}',
            'cons_bm' => '{}',
            'cons_en' => '{}',
            'description_bm' => '',
            'description_en' => 'Enhanced Flooded Battery',
        ]);

        BatteryType::create([
            'type' => 'AGM',
            'pros_bm' => '{}',
            'pros_en' => '{}',
            'cons_bm' => '{}',
            'cons_en' => '{}',
            'description_bm' => '',
            'description_en' => 'Absorbent Glass Mat',
        ]);
    }
}
