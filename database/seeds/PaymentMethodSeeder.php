<?php

use Illuminate\Database\Seeder;
use App\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethod = ['Bank Transfer','Tunai','e-wallet(dana, ovo, gopay)','Debit/Kartu kredit'];
        $icon = ['fa-university','fa-wallet','fa-mobile-android-alt','fa-money-check'];
        for ($i=0; $i < count($paymentMethod); $i++) {
            PaymentMethod::create([
                'payment_method' => $paymentMethod[$i],
                'icon' => $icon[$i],

            ]);
        }


    }
}
