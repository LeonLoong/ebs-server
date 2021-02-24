<?php

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create([
            'battery_id' => 3,
            'car_id' => 1,
            'payment_method_id' => 2,
            'handphone_number' => '+60 12-222 0959',
            'total_visits' => '1',
            'total_transactions' => '1',
            'service_difficulty' => '2',
            'service_points' => 'Kampung Cherang',
            'discount' => 0,
            'description' => 'Hubungi kami pada jam 6.30 petang dan minta kami menghantar dan memasang bateri.',
            'first_visit_at' => '2021-02-17 17:34:37',
            'last_visit_at' => '2021-02-17 17:34:37',
        ]);
    }
}
