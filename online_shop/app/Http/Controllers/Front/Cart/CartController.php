<?php

namespace App\Http\Controllers\Front\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use App\Models\CustomerAddress;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\DiscountCupon;
use App\Models\ShippingCharge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product    =   Product::with('productImage')->find($request->id);
        if ($product == null) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Record not found'
            ]);
        }
        if (Cart::count() > 0) {
            // echo "product Already in cart";
            $cartContent = Cart::content();
            $productAlreadyExist    = false;
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist    = true;
                }
            }
            if ($productAlreadyExist == false) {
                Cart::add(
                    $product->id,
                    $product->title,
                    1,
                    $product->price,
                    ['productImage  ' => (!empty($product->productImage)) ? $product->productImage->first() : '']
                );
                $status     = true;
                $message    = '<strong>' . $product->title . "</strong> Added in cart ";
                session()->flash('success', $message);
            } else {
                $status     =   false;
                $message    =   $product->title . " Already added in cart ";
                session()->flash('error', $message);
            }
        } else {
            // echo "Cart is empty now";
            //Cart is empty
            Cart::add(
                $product->id,
                $product->title,
                1,
                $product->price,
                ['productImage' => (!empty($product->productImage)) ? $product->productImage->first() : '']
            );
            $status     = true;
            $message    = "<strong>" . $product->title . "</strong> product added in cart ";
            session()->flash('success', $message);
        }
        return response()->json([
            'status'    =>  $status,
            'message'   =>  $message,
        ]);
    }
    public function cart()
    {
        // dd(Cart::content());
        $cartContents           = Cart::content();
        // dd($cartContents);
        $data['cartContents']   = $cartContents;
        return view('front.cart.cart', $data);
    }
    public function updateCart(Request $request)
    {
        $rowId      =   $request->rowId;
        $qty        =   $request->qty;
        $itemInfo   =   Cart::get($rowId);
        $product    =   Product::find($itemInfo->id);
        //check qty available in stock
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message    =   'Cart Update Successfully';
                $status     =   true;
                session()->flash('success', $message);
            } else {
                $message    =   'Request Qty (' . $qty . ') Not Availbale In Stock';
                $status     =   false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message        =   'Cart Update Successfully';
            $status         =   true;
            session()->flash('success', $message);
        }
        return response()->json([
            "status"    => $status,
            "message"   => $message,
        ]);
    }
    public function removeItem(Request $request)
    {
        $itemInfo       =   Cart::get($request->rowId);
        if ($itemInfo    == null) {
            $message    =   'Item not found in cart';
            session()->flash('error', $message);
            return response()->json([
                "status"    => false,
                "message"   => $message,
            ]);
        }
        Cart::remove($request->rowId);
        $message    =   'Item remove successfully';
        session()->flash('success', $message);
        return response()->json([
            "status"    => true,
            "message"   => $message,
        ]);
    }
    public function checkout()
    {
        $discount   =   0;
        // -- if cart is empty redirect in cart page
        if (Cart::count() == 0) {
            return redirect()->route('cart');
        }
        // if user is not login then redirect to login page
        if (Auth::check() == false) {
            if (!session()->has('url.intended ')) {
                session(['url.intended' =>  url()->current()]);
            }
            return redirect()->route('login');
        }
        $user                   =   Auth::user()->id;
        $customerAddress        =   CustomerAddress::where('user_id', $user)->first();
        session()->forget('url.intended ');
        $countries              =   Country::orderBy('name', 'ASC')->get();
        $subtotal   =   Cart::subtotal(2, '.', '');
        //apply discount coupon
        if (session()->has('code')) {
            $getCode = session()->get('code');
            if ($getCode->type == 'percent') {
                $discount   =   ($getCode->discount_amount / 100) * $subtotal;
            } else {
                $discount   =   $getCode->discount_amount;
            }
        }
        // Calculate Shipping here
        if (!empty($customerAddress)) {
            $countryId              =   $customerAddress->country_id;
            $shippingInfo           =   ShippingCharge::where('country_id', $countryId)->first();
            $totalQty               =   0;
            $totalShippingCharge    =   0;
            $cartContents           =   Cart::content();
            foreach ($cartContents as $cartContent) {
                $totalQty   += $cartContent->qty;
            }
            $totalShippingCharge    =   $totalQty * $shippingInfo->amount;
            $grandtotal             =   ($subtotal - $discount) + $totalShippingCharge;
        } else {
            $grandtotal             =   ($subtotal - $discount);
            $totalShippingCharge    =   0;
        }
        return view('front.cart.checkout', [
            'countries'             => $countries,
            'customerAddress'       => $customerAddress,
            'discount'              => $discount,
            'totalShippingCharge'   => $totalShippingCharge,
            'grandtotal'            => $grandtotal,
        ]);
    }
    public function validateRules($request)
    {
        $rules = [
            'first_name'    =>  'required|min:3',
            'last_name'     =>  'required',
            'email'         =>  'required|email',
            'country'       =>  'required',
            'address'       =>  'required|min:10',
            'city'          =>  'required',
            'state'         =>  'required',
            'zip'           =>  'required',
            'mobile'        =>  'required',
        ];
        $messages = [
            'first_name.required'   => 'The first name is Needable.',
            'last_name.required'    => 'The last name is required.',
            'email.required'        => 'Take a Valid Email',
            'country.required'      => 'Please Select The Country Name',
            'address.required'      => 'The Addres Must Mandatory',
            'city.required'         => 'Please Input the city Name',
            'state.required'        => 'Please Input the state Name',
            'zip.required'          => 'Please Input the zip code',
            'mobile.required'       => 'Your Mobile is Very Neeedable',
        ];
        return ['rules' => $rules, 'messages' => $messages];
    }
    public function processCheckout(Request $request)
    {
        $validationData = $this->validateRules($request);
        $rules          = $validationData['rules'];
        $messages       = $validationData['messages'];
        $validator      = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'message'   =>  'Please fix The Error!',
                'status'    =>  false,
                'errors'    =>  $validator->errors()
            ]);
        }
        // step-2 save user address
        $user       = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id'       =>  $user->id,
                'first_name'    =>  $request->first_name,
                'last_name'     =>  $request->last_name,
                'email'         =>  $request->email,
                'mobile'        =>  $request->mobile,
                'country_id'    =>  $request->country,
                'address'       =>  $request->address,
                'apartment'     =>  $request->apartment,
                'city'          =>  $request->city,
                'state'         =>  $request->state,
                'zip'           =>  $request->zip,
            ]
        );
        //setep -3 store data in order table
        if ($request->payment_method == 'cod') {
            $discountCodeId =   NULL;
            $promoCode      =   '';
            $shipping       =   0;
            $discount       =   0;
            $subtotal       =   Cart::subtotal(2, '.', '');

            //apply discount coupon
            if (session()->has('code')) {
                $getCode = session()->get('code');
                if ($getCode->type == 'percent') {
                    $discount   =   ($getCode->discount_amount / 100) * $subtotal;
                } else {
                    $discount   =   $getCode->discount_amount;
                }
                $discountCodeId =   $getCode->id;
                $promoCode      =   $getCode->code;
            }
            // Shipping Calculate
            $shippingInfo       =   ShippingCharge::where('country_id', $request->country)->first();
            $totalQty           =   0;
            $cartContents       =   Cart::content();
            foreach ($cartContents as $item) {
                $totalQty       += $item->qty;
            }
            if ($shippingInfo != null) {
                $shipping       =   $totalQty * $shippingInfo->amount;
                $grandtotal     =   ($subtotal - $discount) + $shipping;
            } else {
                $shippingInfo   =   ShippingCharge::where('country_id', 'rest_of_world')->first();
                $shipping       =   $totalQty * $shippingInfo->amount;
                $grandtotal     =   ($subtotal - $discount) + $shipping;
            }

            $order              =   new Order();
            $order->user_id     =   $user->id;
            $order->subtotal    =  $subtotal;
            $order->shipping    =  $shipping;
            $order->grand_total =  $grandtotal;
            $order->discount    =  $discount;
            $order->coupon_code_id    =  $discountCodeId;
            $order->coupon_code       =  $promoCode;
            $order->payment_status    =  'not paid';
            $order->status      =  'pending';
            $order->first_name  =   $request->first_name;
            $order->last_name   =   $request->last_name;
            $order->email       =   $request->email;
            $order->mobile      =   $request->mobile;
            $order->country_id  =   $request->country;
            $order->address     =   $request->address;
            $order->apartment   =   $request->apartment;
            $order->city        =   $request->city;
            $order->state       =   $request->state;
            $order->zip         =   $request->zip;
            $order->notes       =   $request->notes;
            $order->save();

            //step-4 data store in order item
            foreach (Cart::content() as $item) {
                $orderItem              =   new OrderItem();
                $orderItem->product_id  =   $item->id;
                $orderItem->order_id    =   $order->id;
                $orderItem->name        =   $item->name;
                $orderItem->qty         =   $item->qty;
                $orderItem->price       =   $item->price;
                $orderItem->total       =   $item->price * $item->qty;
                $orderItem->save();
                //update product stock 
                $productData            =   Product::find($item->id);
                if ($productData->track_qty     == 'Yes') {
                    $currentQty             =   $productData->qty;
                    $updateQty              =   $currentQty - $item->qty;
                    $productData->qty       =   $updateQty;
                    $productData->save();
                }
            }
            //send order email
            orderEmail($order->id, 'customer');

            $message =  'Order Save Successfully';
            session()->flash('success', $message);
            Cart::destroy();
            session()->forget('code');
            return response()->json([
                'success'   => $message,
                'orderId'   => $order->id,
                'status'    => true,
            ]);
        } else {
            //
        }
    }
    public function thankYou($id)
    {
        return view('front.cart.thank_you', ['id' => $id]);
    }
    public function getOrderSummary(Request $request)
    {
        $subtotal       =   Cart::subtotal(2, '.', '');
        $grandtotal     =   0;
        $discount       =   0;
        $discountString =   '';

        //apply discount coupon
        if (session()->has('code')) {
            $getCode = session()->get('code');
            if ($getCode->type == 'percent') {
                $discount   =   ($getCode->discount_amount / 100) * $subtotal;
            } else {
                $discount   =   $getCode->discount_amount;
            }
            $discountString =   '<div class="pt-4" id="discount_response">
                                    <a class="btn-danger btn btn-block w-100" id="removeDiscount">
                                        <strong class="mx-auto">' . session()->get('code')->code . '</strong>
                                        <button class="btn btn-outline-dark" type="button" id="apply_discount">Click For Remove <i class=" fa fa-times"></i></button>
                                    </a>
                                </div>';
        }
        if ($request->country_id > 0) {
            $shippingInfo   =   ShippingCharge::where('country_id', $request->country_id)->first();
            $totalQty       =   0;
            $cartContents   =   Cart::content();
            foreach ($cartContents as $item) {
                $totalQty   += $item->qty;
            }
            if ($shippingInfo != null) {
                $ShippingCharge         =   $totalQty * $shippingInfo->amount;
                $grandtotal             =   ($subtotal - $discount) + $ShippingCharge;
                return response()->json([
                    "status"            => true,
                    "discount"          => number_format($discount, 2),
                    "discountString"    => $discountString,
                    "grandTotal"        => number_format($grandtotal, 2),
                    "ShippingCharge"    => number_format($ShippingCharge, 2),
                ]);
            } else {
                $shippingInfo           =   ShippingCharge::where('country_id', 'rest_of_world')->first();
                $ShippingCharge         =   $totalQty * $shippingInfo->amount;
                $grandtotal             =   ($subtotal - $discount) + $ShippingCharge;
                return response()->json([
                    "status"            => true,
                    "grandTotal"        => number_format($grandtotal, 2),
                    "discount"          => number_format($discount, 2),
                    "discountString"    => $discountString,
                    "ShippingCharge"    => number_format($ShippingCharge, 2),
                ]);
            }
        } else {
            return response()->json([
                "status"         => true,
                "grandTotal"     => number_format($subtotal - $discount, 2),
                "discount"       => number_format($discount, 2),
                "discountString" => $discountString,
                "ShippingCharge" => number_format(0, 2)
            ]);
        }
    }
    public function applyDiscount(Request $request)
    {
        // dd($request->code);
        $code   =   DiscountCupon::where('code', $request->code)->first();
        if ($code == null) {
            $message        =  'Invalid Discount Coupon';
            session()->flash('success', $message);
            return response()->json([
                'status'    => false,
                'message'   => $message
            ]);
        }
        // Check if coupun start date valid or not
        $now    =   Carbon::now();
        // echo $now->format('Y-m-d H:i:s');
        if ($code->starts_at    != '') {
            $startDate          =   Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);
            if ($now->lt($startDate)) {
                $message        =  'Invalid Discount Coupon1';
                session()->flash('success', $message);
                return response()->json([
                    'status'    => false,
                    'message'   => $message
                ]);
            }
        }
        if ($code->expires_at != '') {
            $endDate  =   Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);
            if ($now->gt($endDate)) {
                $message        =  'Invalid Discount Coupon2';
                session()->flash('success', $message);
                return response()->json([
                    'status'    => false,
                    'message'   => $message
                ]);
            }
        }
        //Max uses checked
        $maxUses        =   $code->max_uses;
        if ($maxUses > 0) {
            $couponUsed =   Order::where('coupon_code_id', $code->id)->count();
            if ($couponUsed >= $maxUses) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Coupon Max Uses Limit ' . $maxUses . ' Over!!'
                ]);
            }
        }

        //max_uses_user checked
        $maxUsesUser    =   $code->max_uses_user;
        if ($maxUsesUser > 0) {
            $userId     =   Auth::user()->id;
            $couponUsedByUser =   Order::where(['coupon_code_id' => $code->id, 'user_id' => $userId])->count();
            if ($couponUsedByUser >= $maxUsesUser) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Coupon Max Uses User Limit ' . $maxUsesUser
                ]);
            }
        }
        //Check min Amount 
        $subtotal       =   Cart::subtotal(2, '.', '');
        $minAamount     =  $code->min_amount;
        if ($minAamount > 0) {
            if ($subtotal < $minAamount) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Your Min Amount Must Be ' . $minAamount . '!.'
                ]);
            }
        }
        session()->put('code', $code);
        return $this->getOrderSummary($request);
    }
    public function removeCoupon(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummary($request);
    }
    // public function getCountries(Request $request){
    //     $temCountry=[];
    //     if($request->term !=""){
    //         $countries = Country::where('name','like','%'.$request->term.'%')->get();
    //         if($countries !=null){
    //             foreach($countries as $key){
    //                 $temCountry[]=array('id'=>$key->id, 'text'=>$key->name);

    //             }
    //         }
    //     }
    //     return response()->json([
    //         'tags'=>    $temCountry,
    //         'status'=>  true
    //     ]);
    // }
}
