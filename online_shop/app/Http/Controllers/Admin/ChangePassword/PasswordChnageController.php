<?php

namespace App\Http\Controllers\Admin\ChangePassword;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordChnageController extends Controller
{
    public function adminPasswordChangeForm()
    {
        $admin  =   User::where('id', Auth::guard('admin')->user()->id)->first();
        return view('admin.password-change.password_change', compact('admin'));
    }
    public function adminChangePassword(Request $request)
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
            $admin  =   User::where('id', Auth::guard('admin')->user()->id)->first();
            if (!Hash::check($request->old_password, $admin->password)) {
                session()->flash('error', 'Your Old Password is incorrect!, Please Try Again');
                return response()->json([
                    'status'    => true,
                    'errors'   => $validator->errors()
                ]);
            }
            User::where('id', Auth::guard('admin')->user()->id)->update([
                'password'  =>    Hash::make($request->new_password)
            ]);
            $message                =   'Password Update Successfully!';
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
}
