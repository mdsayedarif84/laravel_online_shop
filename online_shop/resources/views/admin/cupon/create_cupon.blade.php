@extends('admin.dashboard.dashboard')
@section('title')
Create Cupon
@endsection
@section('body')
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid my-2">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Create Cupon Code</h1>
            </div>
            <div class="col-sm-6 text-right">
               <a href="{{route('categories.index')}}" class="btn btn-primary">Cupon List</a>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <!-- Default box -->
      <div class="container-fluid">
         <form action="" method="post" id="cuponForm" name="cuponForm">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="code">Code</label>
                           <input type="text" name="code" id="code" class="form-control" placeholder="code">
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="name">Name</label>
                           <input type="text" name="name" id="name" class="form-control" placeholder="name">
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="max_uses">Max Uses</label>
                           <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="max_uses">
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="max_uses_user">Max Uses User</label>
                           <input type="text" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="max_uses_user">
                           <p></p>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-1"></div>
                     <div class="col-md-5">
                        <div class="mb-3">
                           <label for="status">Status</label>
                           <select name="status" id="status" class="form-control">
                              <option value="1">Active</option>
                              <option value="0">Block</option>
                           </select>
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-5">
                        <div class="mb-3">
                           <label for="type">Type</label>
                           <select name="type" id="type" class="form-control">
                              <option value="percent">Percent</option>
                              <option value="fixed">Fixed</option>
                           </select>
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-1"></div>
                  </div>
                  <div class="row">
                     <div class="col-md-2"></div>
                     <div class="col-md-8">
                        <div class="mb-3">
                           <label for="description">Description</label>
                           <textarea type="text" name="description" id="description" class="form-control" cols="30" rows="5"></textarea>
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-2"></div>
                  </div>
                  <div class="row">
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="discount_amount">Discount Amount </label>
                           <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="discount_amount">
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="min_amount">Min Amount</label>
                           <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="min_amount">
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="starts_at">Starts At</label>
                           <input type="text" name="starts_at" id="starts_at" class="form-control" placeholder="starts_at">
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="expires_at">Expire At</label>
                           <input type="text" name="expires_at" id="expires_at" class="form-control" placeholder="expires_at">
                           <p></p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="pb-5 pt-3">
               <button type="submit" class="btn btn-primary">Create</button>
               <a href="{{route('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
         </form>
      </div>
      <!-- /.card -->
   </section>
   <!-- /.content -->
</div>
@endsection
@section('customJs')
<script>
   $(document).ready(function() {
      $('#starts_at').datetimepicker({
         // options here
         format: 'Y-m-d H:i:s',
      });
      $('#expires_at').datetimepicker({
         // options here
         format: 'Y-m-d H:i:s',
      });
   });
   $("#cuponForm").submit(function(event) {
      event.preventDefault();
      var element = $(this);
      // $("button[type=submit)]").prop('disabled', true);
      $.ajax({
         url: '{{route("cupon.store")}}',
         type: 'POST',
         data: element.serializeArray(),
         dataType: 'json',
         success: function(response) {
            // $("button[type=submit)]").prop('disabled', false);
            if (response["status"] == true) {
               // window.location.href = '{{ route("categories.index") }}';
               $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#starts_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#expires_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
            } else {
               var errors = response['errors'];
               if (errors['name']) {
                  $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
               } else {
                  $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors['code']) {
                  $("#code").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['code']);
               } else {
                  $("#code").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors['discount_amount']) {
                  $("#discount_amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['discount_amount']);
               } else {
                  $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors['starts_at']) {
                  $("#starts_at").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['starts_at']);
               } else {
                  $("#starts_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors['expires_at']) {
                  $("#expires_at").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['expires_at']);
               } else {
                  $("#expires_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
            }
         },
         error: function(jqXHR, exception) {
            console.log("Something Went Wrong!");
         }
      })
   });
</script>
@endsection