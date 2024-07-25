<?php

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function pageList(Request $request)
    {
        $pages      =   Page::latest();
        $keyword    =   $request->get('keyword');
        if (!empty($keyword)) {
            $pages  = $pages->where('name', 'like', '%' . $keyword . '%');
        }
        $pages      =   $pages->paginate(3);
        return view('admin.page.page_list', compact('pages'));
    }
    public function pageCreate()
    {
        return view('admin.page.page_create');
    }
    public function pageStore(Request $request)
    {
        $validator      =   Validator::make(
            $request->all(),
            [
                'name'      =>  'required|min:3',
                'slug'     =>  'required',
            ],
            [
                'name.required'     => 'Input Your Name!',
                'slug.required'     => 'Fill Up Name First!',
            ]
        );
        if ($validator->passes()) {
            $page               =   new Page();
            $page->name         =   $request->name;
            $page->slug        =   $request->slug;
            $page->content       =   $request->content;
            $page->save();

            $message                =   'Page Save Successfully';
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
    public function pageEdit($id)
    {
        $page   =   Page::find($id);
        if (empty($page)) {
            $message        =   'Record not found!';
            session()->flash('error', $message);
            return redirect()->route('page.list');
            // return response()->json([
            //     'status'    =>  true,
            //     'message'   =>  $message
            // ]);
        }
        return view('admin.page.page_edit', ['page' => $page]);
    }
    public function pageUpdate($id, Request $request)
    {
        $page   =   Page::find($id);
        if (empty($page)) {
            $message        =   'Record not found!';
            $request->session()->flash('error', $message);
            return response()->json([
                'status'    =>  true,
                'message'   =>  $message
            ]);
        }
        //validate part
        $validator      =   Validator::make(
            $request->all(),
            [
                'name'      =>  'required|min:3',
                'slug'     =>  'required',
            ],
            [
                'name.required'     => 'Input Your Name!',
                'slug.required'     => 'Fill Up Name First!',
            ]
        );
        if ($validator->passes()) {
            $page->name         =   $request->name;
            $page->slug        =   $request->slug;
            $page->content       =   $request->content;
            $page->save();

            $message                =   'Page Update Successfully';
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
    public function pageDelete($id)
    {
        $page   =   Page::find($id);
        if (empty($page)) {
            $message        =   'Record not found!';
            session()->flash('error', $message);
            return response()->json([
                'status'    =>  true,
                'message'   =>  $message
            ]);
        }
        $page->delete();
        $message    =   "Page info Delete Successfully";
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
