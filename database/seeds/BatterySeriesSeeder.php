<?php

use App\Models\BatterySeries;
use Illuminate\Database\Seeder;

class BatterySeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Amaron
        BatterySeries::create([
            'manufacturer_id' => 1,
            'series' => 'Go',
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatterySeries::create([
            'manufacturer_id' => 1,
            'series' => 'Hi Life',
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatterySeries::create([
            'manufacturer_id' => 1,
            'series' => 'Hi Life Pro',
            'description_bm' => '',
            'description_en' => '',
        ]);

        // Hitachi
        BatterySeries::create([
            'manufacturer_id' => 5,
            'series' => 'Conventional',
            'description_bm' => 'Bateri Hitachi "Conventional Series" dihasilkan dengan "lead-antimony plates" berkualiti tinggi. Ia menggunakan pemisah tikar kaca yang terbukti dapat meningkatkan jangka hayat bateri. Di samping itu, bateri ini dirancang untuk menahan keadaan yang kompleks. Ia hanya memerlukan pemeriksaan penyelenggaraan secara berkala untuk memastikan tahap air suling yang betul di dalam bateri dan diisi dengan air suling bila perlu.',
            'description_en' => 'Hitachi battery "Conventional Series" are manufactured with high-quality lead-antimony plates. It uses a glass mat separator that has proven to increase the life span of the battery. In addition, the battery is designed to endure tough terrain, harsh and vibration environment. It requires only regular maintenance examination to ensure the right level of distilled water in the battery and refilled with distilled water when necessary.',
        ]);

        BatterySeries::create([
            'manufacturer_id' => 5,
            'series' => 'HS',
            'description_bm' => 'Bateri Hitachi "HS Series" bateri bebas penyelenggaraan tertutup sesuai untuk kereta penumpang dan sistem kawalan pengecasan (CCS) - sistem deria yang telah dipasang di dalam kenderaan Jepun sejak tahun 2005. Oleh kerana sistem mengedarkan arus dari bateri dan bukannya alternator ketika kereta memecut, ia dapat menjimatkan petrol.',
            'description_en' => 'Hitachi battery "HS Series" sealed maintenance free battery are suitable for passenger cars and charging control system (CCS) - a sensory system that has been set up within Japanese vehicles from 2005. Since the system distributes the current from the battery instead of the alternator when the car accelerates, it is able to save gasoline. Therefore, this battery requires a quick response from the current distribution along with a higher stability.',
        ]);

        // Varta
        BatterySeries::create([
            'manufacturer_id' => 9,
            'series' => 'Black Dynamic',
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatterySeries::create([
            'manufacturer_id' => 9,
            'series' => 'Blue Dynamic',
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatterySeries::create([
            'manufacturer_id' => 9,
            'series' => 'Silver Dynamic EFB',
            'description_bm' => '',
            'description_en' => '',
        ]);

        BatterySeries::create([
            'manufacturer_id' => 9,
            'series' => 'Silver Dynamic AGM',
            'description_bm' => '',
            'description_en' => '',
        ]);
    }
}
