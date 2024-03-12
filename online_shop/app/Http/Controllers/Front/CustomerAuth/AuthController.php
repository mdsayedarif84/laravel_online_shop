<?php

namespace App\Http\Controllers\Front\CustomerAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Hash;


class AuthController extends Controller
{
    public function login(){
        return view('front.customer-account.customer_login');
    }
    public function register(){
        return view('front.customer-account.customer_register');

    }
    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users',
            'phone'=>'required|max:11',
            'password'=>'required|min:5|confirmed',
        ],
        [
            'name.required' => 'Enter Your Name!',
            'email.required' => 'Input Valid Email!',
            'email.password' => 'At least use 5 digit !',
        ]);
        if($validator->passes()){
            $user           =   new User();
            $user->name     =   $request->name;
            $user->email    =   $request->email;
            $user->password =   Hash::make($request->password);
            $user->phone    =   $request->phone;
            $user->save();
            $message=  'You have Registerd Successfully';
            session()->flash('success',$msg);
            return response()->json([
                'status'=> true,
                'success'=>$message
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
}
