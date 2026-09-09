<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCustomer extends Model
{
    protected $table = 'm_customer';

    protected $fillable = [
        'nama_customer',
        'alamat',
        'no_telp',
    ];
}
