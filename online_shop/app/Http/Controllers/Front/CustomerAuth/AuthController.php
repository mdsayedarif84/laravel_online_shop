<?php

namespace App\Http\Controllers\Front\CustomerAuth;

use App\Http\Controllers\Controller;
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
                // if(session()->has('url.intended ')){
                //     return redirect(session()->get('url.intended'));
                // }
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
}
