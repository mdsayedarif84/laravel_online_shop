<?php

namespace App\Http\Controllers\Front\CustomerAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;


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
            'password'=>'required|min:5',
        ],
        [
            'name.required' => 'Enter Your Name!',
            'email.required' => 'Input Correct slug & unique!',
        ]);
        if($validator->passes()){
            
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
}
