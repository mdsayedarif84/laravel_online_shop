<?php

namespace App\Http\Controllers\Front\Home;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller
{
    public function index()
    {
        $products =    Product::where('is_featured', 'Yes')
            ->orderBy('id', 'DESC')
            ->where('status', 1)
            ->take(8)
            ->get();
        $data['featuresProducts'] = $products;
        $lastetProducts =    Product::orderBy('id', 'DESC')
            ->where('status', 1)
            ->take(8)
            ->get();
        $data['lastetProducts'] = $lastetProducts;
        return view('front.body.main-body', $data);
    }
    public function addToWishlist(Request $request)
    {
        if (Auth::check() == false) {
            Session::put('url.intended', URL::previous());
            // Session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }
        $product    =   Product::where('id', $request->id)->first();
        if ($product == null) {
            $message                =   '<div class="alert alert-danger">Product not found!</div>';
            session()->flash('danger', $message);
            return response()->json([
                'status'    => true,
                'message'   => $message
            ]);
        }
        Wishlist::updateOrCreate(
            [
                'user_id'       =>   Auth::user()->id,
                'product_id'    =>   $request->id,
            ],
            [
                'user_id'       =>   Auth::user()->id,
                'product_id'    =>   $request->id,
            ]
        );
        // $whishlist              =   new Wishlist();
        // $whishlist->user_id     =   Auth::user()->id;
        // $whishlist->product_id  =   $request->id;
        // $whishlist->save();
        $message                =   '<div class="alert alert-success"><strong>" ' . $product->title . ' "</strong> Added in Your Wishlist</div>';
        session()->flash('success', $message);
        return response()->json([
            'status'    => true,
            'message'   => $message
        ]);
    }
    public function page($slug)
    {
        $page   =   Page::where('slug', $slug)->first();
        if ($page == null) {
            abort(404);
        }
        return view('front.page.page', ['page' => $page]);
    }
    public function sendContactEmail(Request $request)
    {
        $validator  =   Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required|min:3',
            ],
            [
                'name.required'     => 'Enter Your Name!',
                'email.required'     => 'Enter Your Valid Email!',
                'subject.required' => 'Write Your Subject!',
            ]
        );
        if ($validator->passes()) {
            $mailData   =   [
                'name'  =>  $request->name,
                'email'  =>  $request->email,
                'subject'  =>  $request->subject,
                'message'  =>  $request->message,
                'mail_subject' => 'You Have Received A Contact Mail',
            ];
            $admin  =   User::where('id', 1)->first();
            Mail::to($admin->email)->send(new ContactMail($mailData));
            $message    =   "Thanks for Contacting us! we will get back to you soon";
            session()->flash('success', $message);
            return response()->json([
                'status'    =>   true,
                'message'   =>   $message
            ]);
        } else {
            return response()->json([
                'status'    =>   false,
                'errors'   =>   $validator->errors()
            ]);
        }
    }
}
