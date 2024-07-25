@extends('admin.dashboard.dashboard')
@section('title')
Edit User
@endsection
@section('body')
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid my-2">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>New Users</h1>
            </div>
            <div class="col-sm-6 text-right">
               <a href="{{ route('users.list')}}" class="btn btn-primary">User List</a>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         @include('admin.message.message')
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header">
                     <h2 class="h5 mb-0 pt-2 pb-2">User Edit Page</h2>
                  </div>
                  <form action="" name="userUpdateForm" id="userUpdateForm" method="POST">
                     <div class="card-body p-4">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="mb-3">
                                 <label for="name">Name</label>
                                 <input type="text" value="{{$user->name}}" name="name" id="name" class="form-control">
                                 <p></p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="mb-3">
                                 <label for="email">Email</label>
                                 <input type="text" value="{{$user->emial}}" name="email" id="email" class="form-control">
                                 <p></p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="mb-3">
                                 <label for="phone">Phone</label>
                                 <input type="text" value="{{$user->phone}}" name="phone" id="phone" class="form-control">
                                 <p></p>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="mb-3">
                                 <label for="role">Role</label>
                                 <select name="role" id="role" class="form-control form-select">
                                    <option>Select Role</option>
                                    <option {{ ($user->role == 2) ?'selected' : '' }} value="2" value="2">Admin</option>
                                    <option {{ ($user->role == 1) ?'selected' : '' }} value="1" value="1">User</option>
                                 </select>
                                 <p></p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="mb-3">
                                 <label for="status">Status</label>
                                 <select name="status" id="status" class="form-control form-select">
                                    <option>Select Status</option>
                                    <option {{ ($user->status == 1) ?'selected' : '' }} value="1" value="1">Active</option>
                                    <option {{ ($user->status == 0) ?'selected' : '' }} value="0" value="0">Inactive</option>
                                 </select>
                                 <p></p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="mb-3">
                                 <label for="password">Password</label>
                                 <input type="password" name="password" id="password" class="form-control">
                                 <span class="text-danger">To change Password You have to enter value, Otherwise Leave Blank!</span>
                                 <p></p>
                              </div>
                           </div>
                        </div>
                        <div class="d-flex">
                           <button class="btn btn-primary">Save</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection
@section('customJs')
<script>
   $('#userUpdateForm').submit(function(event) {
      event.preventDefault();
      var data = $(this).serializeArray();
      $.ajax({
         url: '{{ route("user.update",$user->id)}}',
         type: 'put',
         data: data,
         dataType: 'json',
         success: function(response) {
            $("button[type=submit]").prop('disabled', false);
            if (response["status"] == true) {
               $(" #name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $(" #email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $(" #phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $(" #role").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $(" #status").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $(" #password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               window.location.href = "{{ route('users.create') }}";
            } else {
               var errors = response.errors;
               if (errors.name) {
                  $(" #name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
               } else {
                  $(" #name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.email) {
                  $(" #email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.email);
               } else {
                  $(" #email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.phone) {
                  $(" #phone").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.phone);
               } else {
                  $(" #phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.password) {
                  $(" #password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.password);
               } else {
                  $(" #password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.role) {
                  $(" #role").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.role);
               } else {
                  $(" #role").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.status) {
                  $(" #status").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.status);
               } else {
                  $(" #status").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
            }
         }
      });
   });
</script>
@endsection