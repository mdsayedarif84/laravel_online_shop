<?php

namespace App\Http\Controllers\Front\CustomerAuth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
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
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                if (session()->has('url.intended ')) {
                    return redirect(session()->get('url.intended'));
                }
                $user                   =   Auth::user()->id;
                if ($user) {
                    return redirect()->route('checkout');
                } else {
                    return redirect()->route('register');
                }
                // return redirect()->route('profile');
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
    public function profile()
    {
        return view('front.customer-account.profile');
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
}
