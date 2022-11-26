<?php

namespace App\Models;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

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

    public static function orderItemsTotalByDistributor()
    {
        # code...
        // $users = User::query()
        //     ->cursor() //for collection lazy loading
        //     ->each(function (User $value) {
        //         $getUserCategoryName = UserCategory::getUserCategory($value->referred_by);

        //         if ($getUserCategoryName === 'Distributor') {
        //             # code...

        //             $distributorName = User::where('id', $value->referred_by)->first();

        //             if (!empty($distributorName)) {
        //                 # code...
        //                 $getDistributorDetails[] = [
        //                     'user_id' => $distributorName->id,
        //                 ];
        //             }
        //         }
        //     });

        $users = User::select('referred_by')->cursor();

        return $users->pluck('referred_by');
    }
    public static function orderItemsTotalDistributor()
    {
        # code...
        $sql = 'SELECT @a:=@a+1 serial_number, sales, distributor FROM (SELECT purchaser_id, SUM(price) AS sales, CONCAT_WS(" ", first_name, last_name) AS distributor FROM `order_items` INNER JOIN `products` ON `products`.id = `order_items`.`product_id` LEFT JOIN `orders` ON `orders`.id = `order_items`.order_id INNER JOIN `users` ON purchaser_id = `users`.id CROSS JOIN (SELECT @a:= 0) AS a GROUP BY purchaser_id, distributor ORDER BY `sales` DESC) b';

        $results = DB::select(DB::raw($sql));

        return $results;
    }
}
