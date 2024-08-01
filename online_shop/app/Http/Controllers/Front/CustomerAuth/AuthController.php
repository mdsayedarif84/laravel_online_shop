<?php

namespace App\Http\Controllers\Front\CustomerAuth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.customer_login');
    }
    public function register()
    {
        return view('front.account.customer_register');
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
        return view('front.account.profile', compact('user', 'countries', 'customerAdds'));
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

    public function orders()
    {
        $data   =   [];
        $user   =   Auth::user();
        // $orders     =   Order::latest()->where('user_id', $user->id)->get();
        $orders =   Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data['orders'] =   $orders;
        // $orders      =   $orders->paginate(5);
        return view('front.account.order', $data);
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
        return view('front.account.order_details', $data);
    }
    public function wishlist()
    {
        $user_id    =   Auth::user()->id;
        $whishlists =   Wishlist::where('user_id', $user_id)->with('product')->get();
        $data       =   [];
        $data['whishlists']       =   $whishlists;
        // return $data;
        return view('front.account.wishlist.wishlist', $data);
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
    //front page user account login password change function
    public function changePasswordForm()
    {
        return view('front.account.password.change_password');
    }
    public function changePassword(Request $request)
    {
        $validator      =   Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'new_password' => 'required|min:3',
                'confirm_password' => 'required|same:new_password',
            ],
            [
                'old_password.required'     => 'Enter Your Old Passowrd!',
                'new_password.required'     => 'Enter Your New Password!',
                'confirm_password.required' => 'Enter Retype new Password!',
            ]
        );
        if ($validator->passes()) {
            $user   =   User::select('id', 'password')->where('id', Auth::user()->id)->first();
            if (!Hash::check($request->old_password, $user->password)) {
                // $message                =   'Your Old Password is incorrect!, Please Try Again';
                session()->flash('error', 'Your Old Password is incorrect!, Please Try Again');
                return response()->json([
                    'status'    => true,
                    // 'message'   => $message
                    'message'   => $validator->errors()
                ]);
            }
            User::where('id', $user->id)->update([
                'password'  =>    Hash::make($request->new_password)
            ]);
            $message                =   'Password Update Successfully!';
            session()->flash('success', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
            // dd($user);
        } else {
            return response()->json([
                'status'    => false,
                'errors'    => $validator->errors(),
            ]);
        }
    }
    public function forgotPasswordForm()
    {
        return view('front.account.password.forgot_password');
    }
    public function ProcessforgotPassword(Request $request)
    {
        $validator      =   Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users,email',
            ],
            [
                'email.required'     => 'Email is not exist in database!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('account.forgot-password-form')->withInput()->withErrors($validator);
        }
        $token  =   Str::random(60);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        //send email
        $user   =   User::where('email', $request->email)->first();
        $formData   =   [
            'token' =>  $token,
            'user'  =>  $user,
            'mail_subject'  =>  'You Have Requested To Reset Your Password!'
        ];
        Mail::to($request->email)->send(new ResetPasswordMail($formData));
        return redirect()->route('account.forgot-password-form')->with('success', 'Please check your inbox to reset password');
    }
    public function resetPassword($token)
    {
        $tokenExist =   DB::table('password_reset_tokens')->where('token', $token)->first();
        // return $tokenExist;
        if ($tokenExist == null) {
            return redirect()->route('account.forgot-password-form')->with('error', 'Invalid Request!');
        }

        return view('front.account.password.reset_password_form', ['token' => $token]);
    }
    public function resetPasswordProcess(Request $request)
    {
        $token  =   $request->token;
        // return $token;
        $tokenObj =   DB::table('password_reset_tokens')->where('token', $token)->first();
        if ($tokenObj == null) {
            return redirect()->route('account.forgot-password-form')->with('error', 'Invalid Request!');
        }
        $user   =   User::where('email', $tokenObj->email)->first();
        $validator      =   Validator::make(
            $request->all(),
            [
                'new_password'      => 'required|min:5',
                'confirm_password'  => 'required|same:new_password',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('account.reset-password', $token)->withErrors($validator);
        }
        User::where('id', $user->id)->update([
            'password'  =>  Hash::make($request->new_password),
        ]);
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        return redirect()->route('login')->with('success', 'Reset password Successfully!');;
    }
}
