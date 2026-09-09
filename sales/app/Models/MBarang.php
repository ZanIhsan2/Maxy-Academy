<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MBarang extends Model
{
    protected $table = 'm_barang';

    protected $fillable = [
        'sku',
        'nama_barang',
        'harga',
        'stok',
    ];
}
