<?php

namespace App\Models;

use App\User;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //

    public function purchaser()
    {
        # code...
        return $this->belongsTo(User::class, 'purchaser_id');
    }


    public function distributor()
    {
        # code...
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function items()
    {
        # code...
        return $this->hasMany(OrderItem::class);
    }
}
