<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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
    public function userCreate()
    {
        return view('admin.users.user_create');
    }
    public function store(Request $request)
    {
        $validator      =   Validator::make(
            $request->all(),
            [
                'name'      =>  'required|min:3',
                'email'     =>  'required|email|unique:users',
                'phone'     =>  'required',
                'role'      =>  'required',
                'status'    =>  'required',
                'password'  =>  'required|min:5',
            ],
            [
                'name.required'     => 'Your Name is Needable.',
                'email.required'    => 'Take a Valid Email',
                'phone.required'    => 'Give Your Real Phone Number',
                'role.required'     => 'Select Your Role',
                'status.required'   => 'Select Your Status',
                'password.required' => 'Make Your Unique Password',
            ]
        );
        if ($validator->passes()) {
            $user               =   new User();
            $user->name         =   $request->name;
            $user->email        =   $request->email;
            $user->password     =   Hash::make($request->password);
            $user->phone        =   $request->phone;
            $user->role         =   $request->role;
            $user->status       =   $request->status;
            $user->save();

            $message                =   'New User Save Successfully';
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
    public function userEdit($id, Request $request)
    {
        $user   =   User::find($id);
        // return $user;
        if (empty($user)) {
            $request->session()->flash('error', 'Records Not Found!');
            return redirect()->route('users.list');
        }
        return view('admin.users.user_edit', ['user' => $user]);
    }
    public function userUpdate($id, Request $request)
    {
        $user   =   User::find($id);
        // data empty check
        if (empty($user)) {
            $message    =   'Records Not Found!';
            $request->session()->flash('error', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
        }
        //validate part
        $validator      =   Validator::make(
            $request->all(),
            [
                'name'      =>  'required|min:3',
                'email'     =>  'required|email|unique:users,email,' . $id . ',id',
                // 'email'     =>  'required|email|unique:users',
                'phone'     =>  'required',
                'role'      =>  'required',
                'status'    =>  'required',
            ],
            [
                'name.required'     => 'Your Name is Needable.',
                'email.required'    => 'Take a Valid Email',
                'phone.required'    => 'Give Your Real Phone Number',
                'role.required'     => 'Select Your Role',
                'status.required'   => 'Select Your Status',
            ]
        );
        if ($validator->passes()) {
            $user->name         =   $request->name;
            $user->email        =   $request->email;
            if ($request->password != '') {
                $user->password     =   Hash::make($request->password);
            }
            $user->phone        =   $request->phone;
            $user->role         =   $request->role;
            $user->status       =   $request->status;
            $user->save();

            $message                =   'User Info Update Successfully';
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
    public function userDelete($id)
    {
        $user   =   User::find($id);
        if (empty($user)) {
            $message    =   'Record Not  Found';
            session()->flash('error', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
            // return redirect()->route('categories.index');
        }
        $user->delete();
        $message    =   "User info Delete Successfully";
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
