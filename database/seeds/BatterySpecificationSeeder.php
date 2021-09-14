<?php

use App\Models\BatterySpecification;
use Illuminate\Database\Seeder;

class BatterySpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BatterySpecification::create([
            'spec' => 'Height (mm)',
            'description' => '',
        ]);

        BatterySpecification::create([
            'spec' => 'Width (mm)',
            'description' => '',
        ]);

        BatterySpecification::create([
            'spec' => 'Length (mm)',
            'description' => '',
        ]);

        BatterySpecification::create([
            'spec' => 'Weight (kg)',
            'description' => '',
        ]);

        BatterySpecification::create([
            'spec' => 'Volt',
            'description' => '',
        ]);

        BatterySpecification::create([
            'spec' => 'Amp Hour / AH (5hr.)',
            'description' => '"AH" menunjukkan ukuran kapasiti simpanan kuasa. "AH" semakin tinggi semakin banyak tenaga disimpan di bateri ini. (Boleh dikatakan bahawa semakin besar nombornya semakin kuat.)',
        ]);

        BatterySpecification::create([
            'spec' => 'Amp Hour / AH (20hr.)',
            'description' => '"AH" menunjukkan ukuran kapasiti simpanan kuasa. "AH" semakin tinggi semakin banyak tenaga disimpan di bateri ini. (Boleh dikatakan bahawa semakin besar nombornya semakin kuat.)',
        ]);

        BatterySpecification::create([
            'spec' => 'Cold Cranking Amps / CCA',
            'description' => '"CCA" adalah bacaan ketika bateri dalam keadaan sejuk. Semakin tinggi nilainya, semakin baik. Tetapi bagi Malaysia, kerana suhu kita agak seragam, bacaan CCA dapat digunakan sebagai faktor rujukan kedua atau ketiga.',
        ]);

        BatterySpecification::create([
            'spec' => 'Reserve Capacity / RC (min.)',
            'description' => '"RC" adalah penunjuk yang menentukan berapa lama bateri mampu tahan sekiranya alternator rosak. Ia juga menunjukkan berapa lama bateri dapat digunakan jika anda lupa mematikan lampu kereta. Nombor yang dipaparkan adalah dalam minit. RC 60 = 60 minit. Semakin lama semakin baik.',
        ]);
    }
}
