<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleTransaction extends Model
{
    protected $primaryKey = 'sale_transaction_id';

    public function saleChannel()
    {
        return $this->belongsTo('App\SaleChannel', 'sale_channel_id','sale_channel_id');
    }

}
