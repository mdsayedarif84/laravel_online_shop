@extends('front.home')
@section('title')
Reset Password Form
@endsection
@section('body')
<main style="background-image: url('{{asset('./adminAsset/img/photo2.png')}}');">
   <section class="section-5 pt-3 pb-3 mb-3 bg-white">
      <div class="container">
         <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
               <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
               <li class="breadcrumb-item">Reset Password Form</li>
            </ol>
         </div>
      </div>
   </section>

   <section class=" section-10">
      <div class="container">
         @include('front.message.message')
         <div class="login-form">
            <form action="{{route('account.process-reset-password')}}" method="post">
               @csrf
               <input type="hidden" name="token" value="{{ $token }}">
               <h4 class="modal-title text-info">Reset Password Form</h4>
               <div class="mb-3">
                  <label for="name">New Password</label>
                  <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control @error('new_password') is-invalid @enderror ">
                  @error('new_password')
                  <p class=" invalid-feedback">{{ $message}}</p>
                  @enderror
               </div>
               <div class="mb-3">
                  <label for="name">Confirm Password</label>
                  <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control @error('confirm_password') is-invalid @enderror ">
                  @error('confirm_password')
                  <p class=" invalid-feedback">{{ $message}}</p>
                  @enderror
               </div>
               <input type="submit" class="btn btn-dark btn-block btn-lg" value="Update Password">
            </form>
            <div class="text-center">
               <button class="btn btn-dark">
                  <a href="{{ route('login')}}" class="text-danger">Login</a>
               </button>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection