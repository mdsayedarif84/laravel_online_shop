<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        $totalOrders        =   Order::where('status', '!=', 'cancelled')->count();
        $totalProducts      =   Product::count();
        $totalCustomers     =   User::where('status', 1)->count();
        $totalRevenue       =   Order::where('status', '!=', 'cancelled')->sum('grand_total');

        //This month revenue
        $startOfMonth       =   Carbon::now()->startOfMonth()->format('Y - m - d');
        $currentDate        =   Carbon::now()->format('Y - m - d');
        $thisMnRevenue      =   Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('grand_total');

        // last mont revenue 
        $lastMnStartDate    =   Carbon::now()->subMonth()->startOfMonth()->format('Y - m - d');
        $lastMnEndDate      =   Carbon::now()->subMonth()->endOfMonth()->format('Y - m - d');
        $lastMnName    =   Carbon::now()->subMonth()->startOfMonth()->format('M');

        $lastMnRevenue      =   Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastMnStartDate)
            ->whereDate('created_at', '<=', $lastMnEndDate)
            ->sum('grand_total');

        //last 30 day sale
        $lastThirtyStartDays    =   Carbon::now()->subDays(30)->format('Y - m - d');
        $lastThirtyDays      =   Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastThirtyStartDays)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('grand_total');

        // return $totalProducts;
        // $admin =    Auth::guard('admin')->user();
        // echo "Welcome ".$admin->name.' <a href= "'.route('admin.logout').'">Logout</a>';
        return view('admin.body.body', [
            'totalOrders'       =>  $totalOrders,
            'totalProducts'     =>  $totalProducts,
            'totalCustomers'    =>  $totalCustomers,
            'totalRevenue'      =>  $totalRevenue,
            'thisMnRevenue'     =>  $thisMnRevenue,
            'lastMnRevenue'     =>  $lastMnRevenue,
            'lastThirtyDays'     =>  $lastThirtyDays,
            'lastMnName'     =>  $lastMnName,
        ]);
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
