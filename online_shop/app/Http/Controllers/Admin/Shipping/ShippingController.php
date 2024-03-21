<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Validator;
use App\Models\ShippingCharge;

class ShippingController extends Controller
{
    public function country(){
        $countries  =   Country::orderBy('name','ASC')->get();
        return $countries;
    }
    public function create(){
        $countries = $this->country();
        $shippingCharges    =   ShippingCharge::select('shipping_charges.*','countries.name')
                                    ->leftJoin('countries','countries.id','shipping_charges.country_id')
                                    ->orderBy('id','DESC')
                                    ->take(5)
                                    ->get();
        $data['countries']= $countries;
        $data['shippingCharges']= $shippingCharges;
        return view('admin.shipping.create',$data);
    }
    public function validateRules($request){
        $rules= [
            'country'=>'required',
            'amount'=>'required|numeric',
        ];
        $messages = [
            'country.required' => 'Select The Country Name',
            'amount.required' => 'Enter Your Amount',
        ];
        return ['rules' => $rules, 'messages' => $messages];
    }
    public function store(Request $request){
        $validationData = $this->validateRules($request);
        $rules = $validationData['rules'];
        $messages = $validationData['messages'];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }else{
            $shipping   =   new ShippingCharge();
            $shipping->country_id   =   $request->country;  
            $shipping->amount   =   $request->amount;  
            $shipping->save();
            $message=  'Shipping Save  Successfully';
            session()->flash('success',$message);
            return response()->json([
                'status'=>true,
                'message'=> $message
            ]);
        }
    }
    public function edit($id){
        $shippingCharges    =   ShippingCharge::find($id);
        $countries = $this->country();
        $data['countries']= $countries;
        $data['shippingCharges']= $shippingCharges;
        return view('admin.shipping.edit',$data);
    }
    public function update($id,Request $request){
        $validationData = $this->validateRules($request);
        $rules = $validationData['rules'];
        $messages = $validationData['messages'];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }else{
            $count  =   ShippingCharge::where('country_id',$request->country)->count();
            if($count > 0){
                $message=  'Shipping Already Exist';
                session()->flash('error',$message);    
                return response()->json([
                    'status'=>true,
                    'message'=> $message
                    ]);
            }
            $shipping   =   ShippingCharge::find($id);
            $shipping->country_id   =   $request->country;  
            $shipping->amount   =   $request->amount;  
            $shipping->save();
            $message=  'Shipping Update  Successfully';
            session()->flash('success',$message);
            return response()->json([
                'status'=>true,
                'message'=> $message
            ]);
        }
    }
    public function delete($id,Request $request){
        $shippingCharge    =   ShippingCharge::find($id);
        if(empty($shippingCharge)){
            $request->session()->flash('error','Shipping Not  Found');
            return response()->json([
                'status'=> true,
                'message'=>'Shipping Not Found'
            ]);
            // return redirect()->route('categories.index');
        }        
         $shippingCharge->delete();
         $request->session()->flash('success','Shipping Delete  Successfully');
         return response()->json([
            'status'=> true,
            'message'=>'Shipping Delete Successfully'
        ]);
    }
}
