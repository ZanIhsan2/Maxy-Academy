<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MVendor extends Model
{
    protected $table = 'm_vendor';

    protected $fillable = [
        'nama_vendor',
        'alamat',
        'no_telp',
    ];
}
