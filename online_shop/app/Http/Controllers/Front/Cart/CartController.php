<?php

namespace App\Http\Controllers\Front\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
// use Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;

 


class CartController extends Controller
{
    public function addToCart(Request $request){
        $product    =   Product::with('productImage')->find($request->id);
        if($product == null){
            return response()->json([
                'status'=>false,
                'message'=>'Record not found'
            ]);
        }
        if(Cart::count() > 0){
            // echo "product Already in cart";
            $cartContent = Cart::content();
            $productAlreadyExist= false;
            foreach($cartContent as $item){
                if($item->id == $product->id ){
                    $productAlreadyExist= true;
                }
            }
            if($productAlreadyExist == false){
                Cart::add($product->id, $product->title, 1, $product->price,
                    ['productImage'=>(!empty($product->productImage)) ? $product->productImage->first() : '']);
                $status = true;
                $message= '<strong>'.$product->title."</strong> Added in cart ";
                session()->flash('success',$message);
            }else{
                $status = false;
                $message=$product->title." Already added in cart ";
                session()->flash('error',$message);  
            }
        }else{
            // echo "Cart is empty now";
            //Cart is empty
            Cart::add($product->id, $product->title, 1, $product->price,
                    ['productImage'=>(!empty($product->productImage)) ? $product->productImage->first() : '']);
            $status = true;
            $message= "<strong>".$product->title."</strong> product added in cart ";
            session()->flash('success',$message);
        }
        return response()->json([
            'status'=>$status,
            'message'=>$message,
        ]);
    }
    public function cart(){
        // dd(Cart::content());
        $cartContents = Cart::content();
        // dd($cartContents);
        $data['cartContents']= $cartContents;
        return view('front.cart.cart',$data);
    }
    public function updateCart(Request $request){
        $rowId  =   $request->rowId;
        $qty    =   $request->qty;
        $itemInfo   =   Cart::get($rowId);
        $product    =   Product::find($itemInfo->id);
                //check qty available in stock
                if($product->track_qty == 'Yes'){
                    if( $qty >= $product->qty){
                        Cart::update($rowId,$qty);
                        $message    =   'Cart Update Successfully';
                        $status     =   true;
                        session()->flash('success',$message);
                    }else{
                        $message    =   'Request Qty ('.$qty.') Not Availbale In Stock';
                        $status     =   false;
                        session()->flash('error',$message);
                    }
                }else{
                    Cart::update($rowId,$qty);
                    $message    =   'Cart Update Successfully';
                    $status     =   true;
                    session()->flash('success',$message);
                }
        return response()->json([
            "status" => $status,
            "message" => $message,
        ]);
    }
    public function removeItem(Request $request){
        $itemInfo   =   Cart::get($request->rowId);
        if($itemInfo== null){
            $message    =   'Item not found in cart';
            session()->flash('error',$message);
            return response()->json([
                "status" => false,
                "message" => $message,
            ]);
        }
        Cart::remove($request->rowId);
        $message    =   'Item remove successfully';
        session()->flash('success',$message);
            return response()->json([
                "status" => true,
                "message" => $message,
            ]);
    }
    public function checkout(){
        // -- ifcart is empty redirect in cart page
        if(Cart::count() == 0 ){
            return redirect()->route('cart');
        }
        // if user is not login then redirect to login page
        if(Auth::check() == false ){
            if(!session()->has('url.intended ')){
                session(['url.intended'=>url()->current()]);
            }
            return redirect()->route('login');  
        }
        session()->forget('url.intended ');

        $countries  =   Country::orderBy('name','ASC')->get();
        return view('front.cart.checkout',['countries' => $countries]);

    }
}
