@extends('front.home')
@section('title')
   Thanks 
@endsection
@section('body')
   <div class="col-md-12">
      <div class="card">
         <div class="card-body">
            <h1 class="text-success text-center">
               <strong>Shop!</strong> {!! Session::get('success') !!}
            </h1>
         </div>
         <h2 class="text-center text-danger">
            <strong>Thank You!</strong> Your order Id is: {{ $id }}
         </h2>
      </div>
   </div>
 @endsection