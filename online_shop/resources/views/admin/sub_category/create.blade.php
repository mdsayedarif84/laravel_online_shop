@extends('admin.dashboard.dashboard')
@section('title')
Create Category
@endsection
@section('body')
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid my-2">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Create Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
               <a href="subcategory.html" class="btn btn-primary">Back</a>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <!-- Default box -->
      <div class="container-fluid">
         <form action="" method="post" name="subCategoryForm" id="subCategoryForm">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label for="name">Category</label>
                           <select name="category" id="category" class="form-control">
                              <option value="">Select Category</option>
                              @if($categories->isNotEmpty())
                              @foreach($categories as $category )
                              <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                              @endif
                           </select>
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label for="name">Name</label>
                           <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label for="slug">Slug</label>
                           <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                           <p></p>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-3"></div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label for="status">Status</label>
                           <select name="status" id="status" class="form-control">
                              <option>Selet Status</option>
                              <option value="1">Active</option>
                              <option value="0">Block</option>
                           </select>
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-3"></div>
                  </div>
                  <div class="row">
                     <div class="col-md-2"></div>
                     <div class="col-md-8">
                        <div class="mb-3">
                           <input type="hidden" id="image_id" name="image_id" value="">
                           <label for="image">Image</label>
                           <div id="image" class="dropzone dz-clickable">
                              <div class="dz-message needsclick">
                                 <br>Drop files here or click to upload.<br><br>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-2"></div>
                  </div>
               </div>
            </div>
            <div class="pb-5 pt-3">
               <button type="submit" class="btn btn-primary">Create</button>
               <a href="subcategory.html" class="btn btn-outline-dark ml-3">Cancel</a>
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
$("#subCategoryForm").submit(function(event) {
   event.preventDefault();
   var element = $("#subCategoryForm");
   // $("button[type=submit)]").prop('disabled',true);
   $.ajax({
      url: '{{route("sub-categories.store")}}',
      type: 'POST',
      data: element.serializeArray(),
      dataType: 'json',
      success: function(response) {
         // $("button[type=submit)]").prop('disabled',false);
         if (response["status"] == true) {
            // window.location.href = '{{ route("categories.index") }}';
            // $("#name").removeClass('is-invalid')
            //    .siblings('p')
            //    .removeClass('invalid-feedback').html("");

            // $("#slug").removeClass('is-invalid')
            //    .siblings('p')
            //    .removeClass('invalid-feedback').html("");
            
         } else {
            var errors = response['errors'];
            if (errors['category']) {
               $("#category").addClass('is-invalid')
                  .siblings('p')
                  .addClass('invalid-feedback').html(errors['category']);
            } else {
               $("#category").removeClass('is-invalid')
                  .siblings('p')
                  .removeClass('invalid-feedback').html("");
            }
            if (errors['name']) {
               $("#name").addClass('is-invalid')
                  .siblings('p')
                  .addClass('invalid-feedback').html(errors['name']);
            } else {
               $("#name").removeClass('is-invalid')
                  .siblings('p')
                  .removeClass('invalid-feedback').html("");
            }
            if (errors['slug']) {
               $("#slug").addClass('is-invalid')
                  .siblings('p')
                  .addClass('invalid-feedback').html(errors['slug']);
            } else {
               $("#slug").removeClass('is-invalid')
                  .siblings('p')
                  .removeClass('invalid-feedback').html("");
            }
         }

      },
      error: function(jqXHR, exception) {
         console.log("Something Went Wrong!");
      }
   })
});
$("#name").change(function() {
   element = $(this);
   // $("button[type=submit)]").prop('disabled',true);
   $.ajax({
      url: '{{ route("getSlug") }}',
      type: 'get',
      data: {
         title: element.val()
      },
      dataType: 'json',
      success: function(response) {
         // $("button[type=submit)]").prop('disabled',false);
         if (response["status"] == true) {
            $("#slug").val(response["slug"]);
         }
      }
   });
});
</script>
@endsection