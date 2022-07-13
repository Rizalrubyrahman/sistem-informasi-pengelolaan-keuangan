<?php

use Illuminate\Database\Seeder;
use App\SaleChannel;

class SaleChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $saleChannel = ['Non Online (Penjualan Offline)','Facebook','Instagram','Whatsapp','Line','GoFood','GrabFood'];
        $icon = ['toko.png','facebook.png','instagram.png','whatsapp.png','line.png','gojek.png','grab.png'];

        for ($i=0; $i < count($saleChannel) ; $i++) {
            SaleChannel::create([
                'sale_channel' => $saleChannel[$i],
                'icon' => $icon[$i],
            ]);
        }
    }
}
