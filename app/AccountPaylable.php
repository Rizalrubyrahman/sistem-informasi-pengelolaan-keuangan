<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountPaylable extends Model
{
    protected $primaryKey = 'account_paylable_id';
    protected $fillable = [
        'debt',
        'pay',
        'customer_name',
        'customer_telp',
        'debt_date',
        'due_date',
        'note',
        'status'
    ];
}
