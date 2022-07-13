<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $primaryKey = 'payment_method_id';
    protected $table = 'payment_methods';
    protected $fillable = [
        'payment_method',
        'icon'
    ];
}
