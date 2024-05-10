<?php

namespace App\Http\Controllers\Front\CustomerAuth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.customer-account.customer_login');
    }
    public function register()
    {
        return view('front.customer-account.customer_register');
    }
    public function processRegister(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'phone' => 'required|max:11',
                'password' => 'required|min:5|confirmed',
            ],
            [
                'name.required' => 'Enter Your Name!',
                'email.required' => 'Input Valid Email!',
                'email.password' => 'At least use 5 digit !',
            ]
        );
        if ($validator->passes()) {
            $user           =   new User();
            $user->name     =   $request->name;
            $user->email    =   $request->email;
            $user->password =   Hash::make($request->password);
            $user->phone    =   $request->phone;
            $user->save();
            $message =  'You have Registerd Successfully & login with Your instant email';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'success' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function authenticate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Input Valid Email!',
                'password.required' => 'At least use 5 digit !',
            ]
        );
        if ($validator->passes()) {
            $credentials = $request->only('email', 'password');
            $remember = $request->filled('remember');
            if (Auth::attempt($credentials, $remember)) {
                // if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                if (session()->has('url.intended ')) {
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('checkout');
            } else {
                $message =  'Either Email/Password is invalid';
                return redirect()->route('login')
                    ->withInput($request->only('email'))
                    ->with('error', $message);
            }
        } else {
            return redirect()->route('login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
    public function profile(Request $request)
    {
        $userId         =   Auth::user()->id;
        $user           =   User::where('id', $userId)->first();
        $countries      =   Country::orderBY('name', 'ASC')->get();
        $customerAdds   =   CustomerAddress::where('user_id', $userId)->first();
        return view('front.customer-account.profile', compact('user', 'countries', 'customerAdds'));
    }
    public function updateProfile(Request $request)
    {
        $userId         =   Auth::user()->id;
        $validator      =   Validator::make(
            $request->all(),
            [
                'name'      => 'required',
                'email'      => 'required|email|unique:users,email,' . $userId . ',id',
                'phone'      => 'required',
            ]
        );
        if ($validator->passes()) {
            $user     =   User::find($userId);
            $user->name =   $request->name;
            $user->email =   $request->email;
            $user->phone =   $request->phone;
            $user->save();
            $message                =   'Profile Update Save Successfully';
            session()->flash('success', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
        } else {
            $message                =   'Something went Wrong!!';
            session()->flash('error', $message);
            return response()->json([
                'status'    => false,
                'message'   => $message,
                'errors'    => $validator->errors(),
            ]);
        }
    }
    public function updateAddress(Request $request)
    {
        $userId         =   Auth::user()->id;
        $validator      =   Validator::make(
            $request->all(),
            [
                'first_name'    =>  'required|min:3',
                'last_name'     =>  'required',
                'email'         =>  'required|email',
                'country_id'    =>  'required',
                'address'       =>  'required|min:10',
                'city'          =>  'required',
                'state'         =>  'required',
                'zip'           =>  'required',
                'mobile'        =>  'required',
            ],
            [
                'first_name.required'   => 'The first name is Needable.',
                'last_name.required'    => 'The last name is required.',
                'email.required'        => 'Take a Valid Email',
                'country_id.required'   => 'Please Select The Country Name',
                'address.required'      => 'The Addres Must Mandatory',
                'city.required'         => 'Please Input the city Name',
                'state.required'        => 'Please Input the state Name',
                'zip.required'          => 'Please Input the zip code',
                'mobile.required'       => 'Your Mobile is Very Neeedable',
            ]
        );
        if ($validator->passes()) {
            // $user     =   User::find($userId);
            // $user->name =   $request->name;
            // $user->email =   $request->email;
            // $user->phone =   $request->phone;
            // $user->save();
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id'       =>  $userId,
                    'first_name'    =>  $request->first_name,
                    'last_name'     =>  $request->last_name,
                    'email'         =>  $request->email,
                    'mobile'        =>  $request->mobile,
                    'country_id'    =>  $request->country_id,
                    'address'       =>  $request->address,
                    'apartment'     =>  $request->apartment,
                    'city'          =>  $request->city,
                    'state'         =>  $request->state,
                    'zip'           =>  $request->zip,
                ]
            );
            $message                =   'Address Update Save Successfully';
            session()->flash('success', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'errors'    => $validator->errors(),
            ]);
        }
    }
    public function logout()
    {
        $message =  'You Successfully Logout';
        Auth::logout();
        return redirect()->route('login')->with('success', $message);
    }
    public function userList(Request $request)
    {
        $users      =   User::latest();
        $keyword    =   $request->get('keyword');
        if (!empty($keyword)) {
            $users  = $users->where('name', 'like', '%' . $keyword . '%');
        }
        $users      =   $users->paginate(3);
        return view('admin.users.user_list', compact('users'));
    }
    public function orders()
    {
        $data   =   [];
        $user   =   Auth::user();
        // $orders     =   Order::latest()->where('user_id', $user->id)->get();
        $orders =   Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data['orders'] =   $orders;
        // $orders      =   $orders->paginate(5);
        return view('front.customer-account.order', $data);
    }
    public function orderDetails($id)
    {
        $data   =   [];
        $user   =   Auth::user();
        $order =   Order::where('user_id', $user->id)->where('id', $id)->first();
        $data['order'] =   $order;

        $orderitems =   OrderItem::where('order_id', $id)->get();
        $data['orderitems'] =   $orderitems;

        $orderitemsCount =   OrderItem::where('order_id', $id)->count();
        $data['orderitemsCount'] =   $orderitemsCount;
        return view('front.customer-account.order_details', $data);
    }
    public function wishlist()
    {
        $user_id    =   Auth::user()->id;
        $whishlists =   Wishlist::where('user_id', $user_id)->with('product')->get();
        $data       =   [];
        $data['whishlists']       =   $whishlists;
        // return $data;
        return view('front.customer-account.wishlist.wishlist', $data);
    }
    public function removeProductFromWishlist(Request $request)
    {
        $user_id    =   Auth::user()->id;
        $whishlist =   Wishlist::where('user_id', $user_id)->where('product_id', $request->id)->first();
        if ($whishlist == null) {
            $message                =   'Product Already Removed';
            session()->flash('error', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
        } else {
            Wishlist::where('user_id', $user_id)->where('product_id', $request->id)->delete();
            $message                =   'Product removed successfully!!';
            session()->flash('success', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
        }
    }
}
