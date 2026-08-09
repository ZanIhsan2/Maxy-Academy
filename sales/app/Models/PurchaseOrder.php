<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'm_purchase_order';
    protected $guarded = ['id'];

    // Relasi ke Vendor
    public function vendor()
    {
        return $this->belongsTo(MVendor::class, 'm_vendor_id1');
    }

    // Relasi ke Detail Purchase Order
    public function details()
    {
        return $this->hasMany(DetailPurchaseOrder::class, 'm_purchase_order_id');
    }
}
