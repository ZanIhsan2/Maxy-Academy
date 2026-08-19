<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'xendit_id',
        'status',
        'amount',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function event()
    { 
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
