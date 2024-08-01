@extends('front.home')
@section('title')
Change Password
@endsection
@section('body')
<main>
   <section class="section-5 pt-3 pb-3 mb-3 bg-white">
      <div class="container">
         <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
               <li class="breadcrumb-item"><a class="white-text" href="{{route('account.profile')}}">My Account</a></li>
               <li class="breadcrumb-item">Settings</li>
            </ol>
         </div>
      </div>
   </section>
   @include('front.message.message')
   <section class=" section-11 ">
      <div class="container  mt-5">
         <div class="row">
            <div class="col-md-3">
               @include('front/account/includes/profile-panel')
            </div>
            <div class="col-md-9">
               <div class="card">
                  <div class="card-header">
                     <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                  </div>
                  <form method="post" action="" id="changePasswordForm" name="changePasswordForm">
                     <div class="card-body p-4">
                        <div class="row">
                           <div class="mb-3">
                              <label for="name">Old Password</label>
                              <input type="password" value="{{old('old_password')}}" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                              <p></p>
                           </div>
                           <div class="mb-3">
                              <label for="name">New Password</label>
                              <input type="password" value="{{old('new_password')}}" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                              <p></p>
                           </div>
                           <div class="mb-3">
                              <label for="name">Confirm Password</label>
                              <input type="password" value="{{old('confirm_password')}}" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                              <p></p>
                           </div>
                           <div class="d-flex">
                              <button id="submit" name="submit" type="submit" class="btn btn-dark">Save</button>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection
@section('customJs')
<script>
   $('#changePasswordForm').submit(function(e) {
      e.preventDefault();
      $("#submit").prop('disabled', true);
      var data = $(this).serializeArray();
      $.ajax({
         url: '{{route("account.change-password")}}',
         type: 'post',
         data: data,
         dataType: 'json',
         success: function(response) {
            $("#submit").prop('disabled', false);
            if (response["status"] == true) {
               $(" #old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $(" #new_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $(" #confirm_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               window.location.href = "{{ route('account.change-password-form') }}";
            } else {
               var errors = response.errors;
               if (errors.old_password) {
                  $(" #old_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.old_password);
               } else {
                  $(" #old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.new_password) {
                  $(" #new_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.new_password);
               } else {
                  $(" #new_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.confirm_password) {
                  $(" #confirm_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.confirm_password);
               } else {
                  $(" #confirm_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
            }
         }
      });
   });
</script>
@endsection