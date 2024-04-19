<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;


class OrderController extends Controller
{
    public function orderList()
    {
        $orders =   Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email', 'users.phone');
        $orders =   $orders->leftJoin('users', 'users.id', 'orders.user_id');
        if (!empty($search)) {
            $orders  = $orders->where('name', 'like', '%' . $search . '%');
            // $orders = Order::query()->whereAny(['name', 'email', 'phone', 'id'], 'like', "%search%")->get();
        }
        $orders      =   $orders->paginate(10);
        return view('admin.order.order_list', compact('orders'));
    }
    public function orderDetails($id)
    {
        $order =   Order::where('id', $id)->first();
        return view('admin.order.order_details', compact('order'));
    }
}
