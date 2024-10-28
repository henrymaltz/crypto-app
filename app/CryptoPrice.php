<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CryptoPrice extends Model
{
    protected $fillable = ['coin', 'price'];
}
