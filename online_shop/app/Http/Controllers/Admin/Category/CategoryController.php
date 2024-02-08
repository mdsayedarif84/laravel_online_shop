<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Category;
use App\Models\TempImage;
use File;
use Image;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories= Category::latest();
        if (!empty($request->get('keyword'))){
            $categories= $categories->where('name','like','%'.$request->get('keyword').'%');
        }
        $categories =   $categories->paginate(15);
        return view('admin.category.list',compact('categories'));
        
    }
    public function create(){
        return view('admin.category.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:categories',
        ],
        [
            'name.required' => 'Enter Your Name!',
            'slug.required' => 'Input Correct slug & unique!',
        ]);
        if($validator->passes()){
            $category          =   new Category();
            $category->name    =   $request->name;
            $category->slug    =   $request->slug;
            $category->status  =   $request->status;
            $category->save();
            
            // Save Image
            if( !empty($request->image_id)){
                $tempImage  =   TempImage::find($request->image_id);
                $extArray   =   explode('.',$tempImage->name);
                $ext        =   last($extArray);

                $newImageName=  $category->id.'.'.$ext;
                $sPath      =   public_path().'/temp/'.$tempImage->name;
                $dPath      =   public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                //generated Image Thumbnail
                $dPath      =   public_path().'/uploads/category/thumb/'.$newImageName;
                $img   =   Image::make($sPath );
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath );

                $category->image  =   $newImageName;
                $category->save();
            }
            $request->session()->flash('success','Category Added  Successfully');
            return response()->json([
                'status'=> true,
                'errors'=>"Category Added  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function edit($id, Request $request){
        $category   =   Category::find($id);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
    }
    public function update( $id, Request $request){
        $category   =   Category::find($id);
        if(empty($category)){
            $request->session()->flash('error','Category Not Found');
            return response()->json([
                'status'    => false,
                'notFound'    => true,
                'message'    => 'Category Not Found',
            ]);
        }
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:categories,slug,'.$category->id.',id',
        ],
        [
            'name.required' => 'Enter Your Name!',
            'slug.required' => 'Input Correct slug & unique!',
        ]);
        if($validator->passes()){
            $category->name    =   $request->name;
            $category->slug    =   $request->slug;
            $category->status  =   $request->status;
            $category->save();
            
            $oldImage           =   $category->image;

            // Save Image
            if( !empty($request->image_id)){
                $tempImage  =   TempImage::find($request->image_id);
                $extArray   =   explode('.',$tempImage->name);
                $ext        =   last($extArray);

                $newImageName=  $category->id.'-'.time().'.'.$ext;
                // $newImageName=  $category->id.'.'.$ext;
                $sPath      =   public_path().'/temp/'.$tempImage->name;
                $dPath      =   public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                //generated Image Thumbnail
                $dPath      =   public_path().'/uploads/category/thumb/'.$newImageName;
                $img   =   Image::make($sPath );
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath );

                $category->image  =   $newImageName;
                $category->save();

                //deleted old Image
                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);

            }
            $request->session()->flash('success','Category Update  Successfully');
            return response()->json([
                'status'=> true,
                'errors'=>"Category Update  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function destory($id, Request $request){
        $category   =   Category::find($id);
        $request->session()->flash('error','Category Not  Found');
        if(empty($category)){
            return response()->json([
                'status'=> true,
                'message'=>'Category Not Found'
            ]);
            // return redirect()->route('categories.index');
        }
         //deleted old Image
         File::delete(public_path().'/uploads/category/thumb/'.$category->image);
         File::delete(public_path().'/uploads/category/'.$category->image);
         $category->delete();
         $request->session()->flash('success','Category Delete  Successfully');
         return response()->json([
            'status'=> true,
            'message'=>'Category Delete Successfully'
        ]);
    }
}