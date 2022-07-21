<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleChannel extends Model
{
    protected $primaryKey = 'sale_channel_id';
    protected $fillable = [
        'sale_channel',
        'icon'
    ];

    public function saleTransaction()
    {
        return $this->hasOne('App\SaleTransaction', 'sale_channel_id','sale_channel_id');

    }
}
