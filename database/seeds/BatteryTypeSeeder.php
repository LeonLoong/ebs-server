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
        $batteryType_list = [
            'Low Maintenance',
            'Maintenance Free',
        ];

        BatteryType::create([
            'type_bm' => 'Sel Basah',
            'type_en' => 'Low Maintenance',
            'type_zh' => '低维护',
            'pros_bm' => '{}',
            'pros_en' => '{}',
            'pros_zh' => '{}',
            'cons_bm' => '{}',
            'cons_en' => '{}',
            'cons_zh' => '{}',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);

        BatteryType::create([
            'type_bm' => 'Sel Kering',
            'type_en' => 'Maintenance Free',
            'type_zh' => '免维护',
            'pros_bm' => '{}',
            'pros_en' => '{}',
            'pros_zh' => '{}',
            'cons_bm' => '{}',
            'cons_en' => '{}',
            'cons_zh' => '{}',
            'description_bm' => '',
            'description_en' => '',
            'description_zh' => '',
        ]);
    }
}
