<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    public function orderItems()
    {
        # code...
        return $this->hasMany(OrderItem::class);
    }
}
