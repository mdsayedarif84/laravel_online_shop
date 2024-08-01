@extends('front.home')
@section('title')
Login
@endsection
@section('body')
<main style="background-image: url('{{asset('./adminAsset/img/photo1.png')}}');">
   <section class="section-5 pt-3 pb-3 mb-3 bg-white">
      <div class="container">
         <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
               <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
               <li class="breadcrumb-item">Forgot Password</li>
            </ol>
         </div>
      </div>
   </section>

   <section class=" section-10">
      <div class="container">
         @include('front.message.message')
         <div class="login-form">
            <form action="{{route('account.process-forgot-password')}}" method="post">
               @csrf
               <h4 class="modal-title">Forgot Password</h4>
               <div class="form-group">
                  <input type="text" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                  @error('email')
                  <p class="invalid-feedback">{{ $message}}</p>
                  @enderror
               </div>
               <input type="submit" class="btn btn-dark btn-block btn-lg" value="Submit">
            </form>
            <div class="text-center small"><a href="{{ route('login')}}">Login</a></div>
         </div>
      </div>
   </section>
</main>
@endsection