<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //

    public function order()
    {
        # code...
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        # code...
        return $this->belongsTo(Product::class);
    }

    public static function orderItemsTotal($order_id)
    {
        # code...
        $orderTotal = Order::selectRaw('SUM(products.price  * order_items.qantity) as total_order')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.id', $order_id)
            ->get();

        foreach ($orderTotal as $total) {
            # code...
            $totalCost = $total->total_order;
        }
        return $totalCost;
    }
}
