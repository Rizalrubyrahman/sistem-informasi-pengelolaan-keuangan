<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleTransactionProduct extends Model
{
    protected $table = 'sale_transaction_product';
    protected $primaryKey = 'sale_transaction_product_id';

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id','product_id');
    }
}
