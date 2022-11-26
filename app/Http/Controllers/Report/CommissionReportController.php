<?php

namespace App\Http\Controllers\Report;

use App\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommissionReportController extends Controller
{
    //
    public function index()
    {
        # code...
        $paginateValue = 10;
        $orders = Order::select('id', 'invoice_number', 'order_date', 'purchaser_id')->OrderBy('id', 'DESC')->paginate($paginateValue);
        return view('commission.index', compact('orders'));
    }


    public function getViewItemsModal(int $id)
    {
        $order = Order::where('id', $id)->first();
        $orders = $order->items;

        foreach ($orders as $order) {
            $invoice_number = Order::find($order->order_id)->invoice_number;
            $query[] = array(
                'order_id' => $order->order_id,
                'product_id' => $order->product_id,
                'product_name' => Product::find($order->product_id)->name,
                'qantity' => $order->qantity,
                'product_price' => Product::find($order->product_id)->price,
                'total' => $order->qantity * Product::find($order->product_id)->price,
            );
        }
        $response =  [
            'data' => $query,
            'success' => true,
            'invoice_number' => $invoice_number,
        ];

        return response()->json([
            'responseMessage' => $response,
            'responseStatus'  => 403,
        ]);
    }

    public function commissionReportFilter(Request $request)
    {
        $noOfRecords = 10;
        $from = $request->from;
        $to = $request->to;
        $user_id = $request->user_id;
        $query = Order::select('orders.*')
            ->join('users', 'users.id', '=', 'orders.purchaser_id');

        if ($from) {
            $query = $query->whereDate('orders.order_date', '>=', $from);
        }
        if ($to) {
            $query = $query->whereDate('orders.order_date', '<=', $to);
        }
        if ($user_id) {
            $query = $query->where('users.id', $user_id);
        }
        $query = $query->orderBy('orders.id', 'desc')
            ->paginate($noOfRecords);

        $queryData = [];
        $i = $query->perPage() * ($query->currentPage() - 1);
        foreach ($query as $singleData) {
            $i++;
            $queryData[] = array(
                'id' => $singleData->id,
                'invoice' => $singleData->invoice_number,
                'purchaser' => $singleData->purchaser->first_name . ' ' . $singleData->purchaser->last_name,
                'distributor' => User::getReferralName($singleData->id),
                'referred_distributor' => User::getNumberOfReferredDistributor($singleData->id),
                'order_date' => $singleData->order_date,
                'order_total' => number_format(OrderItem::orderItemsTotal($singleData->id), 2),
                'percentage' => User::getcommissionPercentage($singleData->id, User::getNumberOfReferredDistributor($singleData->id)) . '%',
                'commission'  => number_format(getCommission(OrderItem::orderItemsTotal($singleData->id), User::getcommissionPercentage($singleData->id, User::getNumberOfReferredDistributor($singleData->id))), 2)

            );
        }

        $response = [
            'success' => true,
            'totalRecords' => $query->total(),
            'data' => $queryData,
            'pagination' => str_replace("\r\n", "", $query->links('commission.pagination')),
            'count' => $query->count()
        ];
        return $response;
    }
}
