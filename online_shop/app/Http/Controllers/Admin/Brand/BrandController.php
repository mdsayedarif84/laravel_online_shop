<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Validator;

class BrandController extends Controller
{
    public function index(Request $request){
        $brands     =   Brand::latest();
        if (!empty($request->get('keyword'))){
            $brands= $brands->where('brands.name','like','%'.$request->get('keyword').'%');
        }
        $brands =   $brands->paginate(15);
        return view('admin.brand.list',compact('brands'));
        
    }
    public function create(){
        return view('admin.brand.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:brands',
            'status'=>'required',
        ],
        [
            'name.required' => 'Enter Your Name!',
            'slug.required' => 'Input Correct slug & unique!',
            'status.required' => 'Select Status !',
        ]);
        if($validator->passes()){
            $brand    =   new Brand();
            $brand->name =    $request->name;
            $brand->slug =    $request->slug;
            $brand->status =    $request->status;
            $brand->save();

            $request->session()->flash('success',' Brand Added  Successfully');
            return response()->json([
                'status'=> true,
                'errors'=>" Brand Added  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function edit($id, Request $request){
        $brand   =   Brand::find($id);
        if(empty($brand)){
            $request->session()->flash('error','Records Not Found!');
            return redirect()->route('brand.list');
        }
        return view('admin.brand.edit',compact('brand'));
    }
    public function update($id,Request $request){
        $brand   =   Brand::find($id);
        if(empty($brand)){
            $request->session()->flash('error','Records Not Found!');
            return response()->json([
                'status'    => false,
                'notFound'    => true,
            ]);
        }
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:brands,slug,'.$brand->id.',id',
            'status'=>'required',
        ],
        [
            'name.required' => 'Enter Your Name!',
            'slug.required' => 'Input Correct slug & unique!',
            'status.required' => 'Select Status !',
        ]);
        if($validator->passes()){
            $brand->name =    $request->name;
            $brand->slug =    $request->slug;
            $brand->status =    $request->status;
            $brand->save();

            $request->session()->flash('success',' Brand Update  Successfully');
            return response()->json([
                'status'=> true,
                'errors'=>" Brand Update  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function destory($id, Request $request){
        $brand   =   Brand::find($id);
        if(empty($brand)){
            $request->session()->flash('error',' Brand Not  Found');
            return response()->json([
                'status'=> true,
                'message'=>' Brand Not Found'
            ]);
        }        
         $brand->delete();
         $request->session()->flash('success','Brand Delete  Successfully');
         return response()->json([
            'status'=> false,
            'message'=>' Brand Delete Successfully'
        ]);
    }
}