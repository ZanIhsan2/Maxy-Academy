<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
        'price' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
