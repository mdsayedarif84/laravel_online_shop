<?php

namespace App\Http\Controllers\Admin\Cupon;

use App\Http\Controllers\Controller;
use App\Models\DiscountCupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function validateRules($request)
    {
        $rules = [
            'code'              =>  'required',
            'name'              =>  'required',
            'type'              =>  'required',
            'discount_amount'   =>  'required|numeric',
            'status'            =>  'required',
        ];
        $messages = [
            'code.required'     => 'The Code is Needable.',
            'name.required'     => 'The name is required.',
            'discount_amount.required' => 'Fill Up the Discount Amount',
        ];
        return ['rules' => $rules, 'messages' => $messages];
    }
    public function index(Request $request)
    {
        $cupons     =   DiscountCupon::latest();
        if (!empty($request->get('keyword'))) {
            $cupons = $cupons->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $cupons =   $cupons->paginate(4);
        return view('admin.cupon.cupon_list', ['cupons' => $cupons]);
    }
    public function create()
    {
        return view('admin.cupon.create_cupon');
    }
    public function store(Request $request)
    {
        $validationData = $this->validateRules($request);
        $rules          = $validationData['rules'];
        $messages       = $validationData['messages'];
        $validator      = Validator::make($request->all(), $rules, $messages);
        if ($validator->passes()) {
            // Starting Date Must be greater than current date
            $startsTime     = $request->starts_at;
            if (!empty($startsTime)) {
                $now        =   Carbon::now();
                $startsAt   =   Carbon::createFromFormat('Y-m-d H:i:s', $startsTime);
                if ($startsAt->lte($now) == true) {
                    return response()->json([
                        'status'    =>  false,
                        'errors'    =>  ['starts_at' => 'Start At Can not be less Than current date']
                    ]);
                }
            }
            // expire date must be greater than starting date
            $startsTime     = $request->starts_at;
            $expiresTime    = $request->expires_at;
            if (!empty($startsTime) && !empty($expiresTime)) {
                $expiresAt  =   Carbon::createFromFormat('Y-m-d H:i:s', $expiresTime);
                $startsAt   =   Carbon::createFromFormat('Y-m-d H:i:s', $startsTime);
                if ($expiresAt->gt($startsAt) == false) {
                    return response()->json([
                        'status'    =>  false,
                        'errors'    =>  ['expires_at' => 'Expire date must be greater than Starts date']
                    ]);
                }
            }

            $discountCode                   =   new DiscountCupon();
            $discountCode->code             =   $request->code;
            $discountCode->name             =   $request->name;
            $discountCode->description      =   $request->description;
            $discountCode->max_uses         =   $request->max_uses;
            $discountCode->max_uses_user    =   $request->max_uses_user;
            $discountCode->type             =   $request->type;
            $discountCode->discount_amount  =   $request->discount_amount;
            $discountCode->min_amount       =   $request->min_amount;
            $discountCode->status           =   $request->status;
            $discountCode->starts_at        =   $request->starts_at;
            $discountCode->expires_at       =   $request->expires_at;
            $discountCode->save();

            $message =  'Discount Cupon Save  Successfully';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status'    =>  false,
                'errors'    =>  $validator->errors()
            ]);
        }
    }
    public function edit($id, Request $request)
    {
        $cupon  =   DiscountCupon::find($id);
        if (empty($cupon)) {
            $request->session()->flash('error', 'Records Not Found!');
            return redirect()->route('cupon.index');
        }
        return view('admin.cupon.cupon_edit', ['cupon' => $cupon]);
    }
    public function update($id, Request $request)
    {
        $cupon  =   DiscountCupon::find($id);
        if (empty($cupon)) {
            $request->session()->flash('error', 'Cupon Not Found');
            return response()->json([
                'status'        => false,
                'notFound'      => true,
                'message'       => 'Cupon Not Found',
            ]);
        }
        $validationData     = $this->validateRules($request);
        $rules              = $validationData['rules'];
        $messages           = $validationData['messages'];
        $validator          = Validator::make($request->all(), $rules, $messages);
        if ($validator->passes()) {
            // expire date must be greater than starting date
            $startsTime     = $request->starts_at;
            $expiresTime    = $request->expires_at;
            if (!empty($startsTime) && !empty($expiresTime)) {
                $expiresAt  =   Carbon::createFromFormat('Y-m-d H:i:s', $expiresTime);
                $startsAt   =   Carbon::createFromFormat('Y-m-d H:i:s', $startsTime);
                if ($expiresAt->gt($startsAt) == false) {
                    return response()->json([
                        'status'    =>  false,
                        'errors'    =>  ['expires_at' => 'Expire date must be greater than Starts date']
                    ]);
                }
            }

            $cupon->code            =   $request->code;
            $cupon->name            =   $request->name;
            $cupon->description     =   $request->description;
            $cupon->max_uses        =   $request->max_uses;
            $cupon->max_uses_user   =   $request->max_uses_user;
            $cupon->type            =   $request->type;
            $cupon->discount_amount =   $request->discount_amount;
            $cupon->min_amount      =   $request->min_amount;
            $cupon->status          =   $request->status;
            $cupon->starts_at       =   $request->starts_at;
            $cupon->expires_at      =   $request->expires_at;
            $cupon->save();

            $message =  'Discount Cupon Update  Successfully';
            session()->flash('success', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
        } else {
            return response()->json([
                'status'    =>  false,
                'errors'    =>  $validator->errors()
            ]);
        }
    }
    public function delete(Request $request, $id)
    {
        $cupon  =   DiscountCupon::find($id);
        if (empty($cupon)) {
            $message =  'Cupon Not Found';
            session()->flash('error', $message);
            return response()->json([
                'status'        => false,
                'message'       => $message,
            ]);
        }
        $cupon->delete();
        $message    =  'Coupun Delete  Successfully';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
