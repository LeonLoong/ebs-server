<?php

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethod_list = [
            "Credit Cards",
            "Bank Transfers",
            "E-Wallets",
            "Cash",
        ];

        foreach ($paymentMethod_list as $paymentMethod) {
            PaymentMethod::create([
                'method' => $paymentMethod,
            ]);
        };
    }
}
