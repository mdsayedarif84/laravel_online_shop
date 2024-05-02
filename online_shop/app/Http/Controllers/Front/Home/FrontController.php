<?php

namespace App\Http\Controllers\Front\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class FrontController extends Controller
{
    public function index()
    {
        $products =    Product::where('is_featured', 'Yes')
            ->orderBy('id', 'DESC')
            ->where('status', 1)
            ->take(8)
            ->get();
        $data['featuresProducts'] = $products;
        $lastetProducts =    Product::orderBy('id', 'DESC')
            ->where('status', 1)
            ->take(8)
            ->get();
        $data['lastetProducts'] = $lastetProducts;
        return view('front.body.main-body', $data);
    }
    public function addToWishlist(Request $request)
    {
        if (Auth::check() == false) {
            Session::put('url.intended', URL::previous());
            // Session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }
        $product    =   Product::where('id', $request->id)->first();
        if ($product == null) {
            $message                =   '<div class="alert alert-danger">Product not found!</div>';
            session()->flash('danger', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
        }
        Wishlist::updateOrCreate(
            [
                'user_id'       =>   Auth::user()->id,
                'product_id'    =>   $request->id,
            ],
            [
                'user_id'       =>   Auth::user()->id,
                'product_id'    =>   $request->id,
            ]
        );
        // $whishlist              =   new Wishlist();
        // $whishlist->user_id     =   Auth::user()->id;
        // $whishlist->product_id  =   $request->id;
        // $whishlist->save();
        $message                =   '<div class="alert alert-success"><strong>" ' . $product->title . ' "</strong> Added in Your Wishlist</div>';
        session()->flash('success', $message);
        return response()->json([
            'status'    => true,
            'message'   => $message
        ]);
    }
}
