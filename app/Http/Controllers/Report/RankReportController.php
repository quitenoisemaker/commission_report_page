<?php

namespace App\Http\Controllers\Report;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RankReportController extends Controller
{
    //
    public function index()
    {
        # code...
        $paginateValue = 10;
        $orders = Order::select('id', 'invoice_number', 'order_date', 'purchaser_id')->OrderBy('id', 'DESC')->paginate($paginateValue);
        return view('rank.index', compact('orders'));
    }
}
