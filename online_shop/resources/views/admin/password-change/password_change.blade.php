@extends('admin.dashboard.dashboard')
@section('title')
Password Change
@endsection
@section('body')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid my-2">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Change Password</h1>
            </div>
            <!-- <div class="col-sm-6 text-right">
               <a href="users.html" class="btn btn-primary">Back</a>
            </div> -->
         </div>
      </div>
      @include('admin.message.message')
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid" style="background-image: url(asset('/uploads/img/birds.jpg'));">
         <div class="card">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-4">
                     <div class="col-md-6 mt-3 pe-lg-5">
                        <address class="text-xl-center">
                           {{$admin->name}} <br>
                           @if($admin->role ==2 )
                           <span class="text-info">Admin</span> <br>
                           @else
                           <span class="text-danger">Role is Empty Entry!</span><br>
                           @endif
                           <a href="tel:+xxxxxxxx">{{$admin->phone}}</a><br>
                           <a href="mailto:jim@rock.com">{{$admin->email}}</a><br>
                           {{$admin->created_at}}
                        </address>
                     </div>
                  </div>
                  <div class="col-md-8">
                     <form action="" method="post" id="passwordChangeForm" name="passwordChangeForm">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="col-md-12">
                                 <div class="mb-3">
                                    <label for="name">Old_Password</label>
                                    <input type="text" name="old_password" id="old_password" class="form-control" placeholder="old_password">
                                    <p></p>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="mb-3">
                                    <label for="email">New_Password</label>
                                    <input type="text" name="new_password" id="new_password" class="form-control" placeholder="new_password">
                                    <p></p>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="mb-3">
                                    <label for="phone">Confirm_Password</label>
                                    <input type="text" name="confirm_password" id="confirm_password" class="form-control" placeholder="confirm_password">
                                    <p></p>
                                 </div>
                              </div>
                              <div class="pb-5 pt-3 ">
                                 <button name="submit" id="submit" class="btn btn-primary">Update</button>
                                 <a href="users.html" class="btn btn-outline-dark ml-3">Cancel</a>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </section>
</div>
<!-- /.content-wrapper -->
@endsection
@section('customJs')
<script>
   $('#passwordChangeForm').submit(function(e) {
      e.preventDefault();
      $("#submit").prop('disabled', true);
      var data = $(this).serializeArray();
      $.ajax({
         url: '{{route("admin.password-change")}}',
         type: 'post',
         data: data,
         dataType: 'json',
         success: function(response) {
            $("#submit").prop('disabled', false);
            if (response["status"] == true) {

               window.location.href = "{{ route('admin.password-change-form') }}";
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