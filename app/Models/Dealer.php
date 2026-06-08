<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $primaryKey = 'dealer_id';

    protected $fillable = [
        'dealer_code',
        'dealer_name',
        'address',
        'phone',
        'status',
    ];
}
