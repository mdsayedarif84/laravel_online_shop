<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function orderList(Request $request)
    {
        $orders     =   Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email', 'users.phone');
        $orders     =   $orders->leftJoin('users', 'users.id', 'orders.user_id');
        $keyword    =   $request->get('keyword');
        if (!empty($keyword)) {
            $orders  = $orders->where('users.name', 'like', '%' . $keyword . '%');
            $orders  = $orders->orWhere('users.email', 'like', '%' . $keyword . '%');
            $orders  = $orders->orWhere('orders.id', 'like', '%' . $keyword . '%');
            // $orders = Order::query()->whereAny(['name', 'email', 'phone', 'id'], 'like', "%keyword%")->get();
        }
        $orders      =   $orders->paginate(10);
        return view('admin.order.order_list', ['orders' => $orders]);
    }
    public function orderDetails($id)
    {
        // $order  =   Order::where('id', $id)->first();
        // $order =   Order::select('orders.*', 'countries.name as countryName')
        //     ->where('orders.id', $id)
        //     ->leftJoin('countries', 'countries.id', 'orders.country_id')
        //     ->first();

        $order      =   DB::table('orders')
            ->select('orders.*', 'countries.name as countryName')
            ->where('orders.id', '=', $id)
            ->leftJoin('countries', 'countries.id', 'orders.country_id')
            ->first();

        $orderItems  =   OrderItem::where('order_id', $id)->get();
        // return $orderItems;
        return view('admin.order.order_details', compact('order', 'orderItems'));
    }
    public function changeOrderStatus(Request $request, $id)
    {
        $orderId    =   Order::find($id);
        $orderId->status    =   $request->status;
        $orderId->shipped_date    =   $request->shipped_date;
        $orderId->save();
        $message =  'Status Changed Successfully';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'success' => $message
        ]);
    }
    public function sendInvoiceEmail(Request $request, $orderId)
    {
    }
}
