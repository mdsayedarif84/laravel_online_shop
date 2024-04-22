<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Order;
use App\Models\Product_Image;
use Illuminate\Support\Facades\Mail;

function getCategories()
{
   return Category::orderBy('Name', 'ASC')
      ->with('sub_category')
      ->orderBy('id', 'DESC')
      ->where('status', 1)
      ->where('showHome', 'Yes')
      ->get();
}
function getProductImage($id)
{
   return  Product_Image::where('product_id', $id)->first();
}
function orderEmail($orderId)
{
   $order    =  Order::where('id', $orderId)->with('items')->first();
   $mailData   =  [
      'subject'   => 'Thanks for your order',
      'order'     => $order
   ];
   Mail::to($order->email)->send(new OrderEmail($mailData));
   // dd($order);
}
function getCountryInfo($id)
{
   return Country::where('id', $id)->first();
}
