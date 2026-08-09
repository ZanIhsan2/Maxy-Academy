<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPurchaseOrder extends Model
{
    protected $table = 'm_detail_purchase_order';
    // protected $guarded = ['id'];

    protected $fillable = [
        'm_purchase_order_id',
        'm_barang_sku',
        'kuantitas',
        'harga_unit',
        'total_harga',
    ];
}
