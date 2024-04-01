@extends('front.home')
@section('title')
   Thanks 
@endsection
@section('body')
   <section class="section-5 pt-3 pb-3 mb-3 bg-white">
         <div class="container">
            <div class="light-font">
               <ol class="breadcrumb primary-color mb-0">
                  <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                  <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Shop</a></li>
                  <li class="breadcrumb-item">Thank You</li>
               </ol>
            </div>
         </div>
      </section>
   <div class="col-md-12">
      <div class="card">
         <div class="card-body">
            <h1 class="text-success text-center">
               <strong>Shop!</strong> {!! Session::get('success') !!}
            </h1>
         </div>
         <h2 class="text-center text-danger">
            <strong>Thank You!</strong> Your order Id is : {{ $id }}
         </h2>
      </div>
   </div>
 @endsection