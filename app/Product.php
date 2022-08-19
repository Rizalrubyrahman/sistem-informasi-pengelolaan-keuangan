<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    public function saleTransactionProduct()
    {
        return $this->hasMany('App\SaleTransactionProduct', 'product_id','product_id');

    }
}
