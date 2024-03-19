@extends('admin.dashboard.dashboard')
@section('title')
Edit Product
@endsection
@section('body')
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid my-2">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
               <a href="{{ route('products.list') }}" class="btn btn-primary">Products List</a>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <!-- Default box -->
      <form action="" method="post" name="productForm" id="productForm">
         <div class="container-fluid">
            <div class="row">
               <div class="col-md-7">
                  <div class="card mb-3">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="mb-3">
                                 <label for="title">Title</label>
                                 <input type="text" value="{{ $product->title }}" name="title" id="title"
                                    class="form-control">
                                 <p class="error"></P>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="mb-3">
                                 <label for="slug">Slug</label>
                                 <input type="text" value="{{ $product->slug }}" readonly name="slug" id="slug"
                                    class="form-control">
                                 <p class="error"></P>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="mb-3">
                                 <label for="short_description">Short Description</label>
                                 <textarea name="short_description" id="short_description" cols="30" rows="5" class="summernote"
                                    >{{ $product->short_description }}</textarea>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="mb-3">
                                 <label for="description">Description</label>
                                 <textarea name="description" id="description" cols="30" rows="8" class="summernote"
                                    placeholder="Description">{{ $product->description }}</textarea>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card mb-3">
                     <div class="card-body">
                        <h2 class="h4 mb-3">Media</h2>
                        <div id="image" name="image" class="dropzone dz-clickable">
                           <div class="dz-message needsclick">
                              <br>Drop files here or click to upload.<br><br>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row" id="product-gallery">
                     @if($productImages->isNotEmpty())
                     @foreach($productImages as $image )
                     <div class="col-md-3" id="image-row-{{$image->id}}">
                        <div class="card">
                           <input type="hidden" name="image_array[]" value="{{$image->id}}">
                           <img src="{{ asset('uploads/product/small/'.$image->image) }}" class="card-img-top" alt="...">
                           <div class="card-body">
                              <a href="javascript:void(0)" onclick="deleteImage( {{$image->id}} )"
                                 class="btn btn-danger">Delete</a>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @endif
                  </div>

                  <div class="card mb-3">
                     <div class="card-body">
                        <h2 class="h4 mb-3">Inventory</h2>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="mb-3">
                                 <label for="sku">SKU (Stock Keeping Unit)</label>
                                 <input type="text" value="{{ $product->sku }}" name="sku" id="sku"
                                    class="form-control">
                                 <p class="error"></P>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="mb-3">
                                 <label for="barcode">Barcode</label>
                                 <input type="text" value="{{ $product->barcode }}" name="barcode" id="barcode"
                                    class="form-control">
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="mb-3">
                                 <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="track_qty" value="No">
                                    <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty"
                                       value="Yes" checked>
                                    <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                    <p class="error"></P>
                                 </div>
                              </div>
                              <div class="mb-3">
                                 <input value="{{ $product->qty }}" type="number" min="0" name="qty" id="qty"
                                    class="form-control">
                                 <p class="error"></P>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-5">
                  <div class="card mb-3">
                     <div class="card-body">
                        <h2 class="h4 mb-3">Product status</h2>
                        <div class="mb-3">
                           <select name="status" id="status" class="form-control">
                              <option {{ ($product->status == 1) ?'selected' : '' }} value="1">Active</option>
                              <option {{ ($product->status == 0) ?'selected' : '' }} value="0">Block</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <h2 class="h4  mb-3">Product category</h2>
                        <div class="mb-3">
                           <label for="category">Category</label>
                           <select name="category" id="category" class="form-control">
                              <option value="">Select Category</option>
                              @if($categories->isNotEmpty())
                              @foreach($categories as $category )
                              <option {{ ($category->id == $product->category_id) ?'selected' : '' }}
                                 value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                              @endif
                           </select>
                           <p class="error"></P>
                        </div>
                        <div class="mb-3">
                           <label for="category">Sub category</label>
                           <select name="sub_category" id="sub_category" class="form-control">
                              <option value="">Select Sub Category</option>
                              @if($subCategories->isNotEmpty())
                              @foreach($subCategories as $subCategory )
                              <option {{ ($subCategory->id == $product->sub_category_id) ?'selected' : '' }}
                                 value="{{$subCategory->id}}">{{$subCategory->name}}</option>
                              @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="card mb-3">
                     <div class="card-body">
                        <h2 class="h4 mb-3">Product brand</h2>
                        <div class="mb-3">
                           <select name="brand_id" id="brand_id" class="form-control">
                              <option value="">Select Brand</option>
                              @if($brands->isNotEmpty())
                              @foreach($brands as $brand )
                              <option {{ ($brand->id == $product->brand_id) ?'selected' : '' }} value="{{$brand->id}}">
                                 {{$brand->name}}</option>
                              @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="card mb-3">
                     <div class="card-body">
                        <h2 class="h4 mb-3">Featured product</h2>
                        <div class="mb-3">
                           <select name="is_featured" id="is_featured" class="form-control">
                              <option {{ ($product->is_featured == 'No') ?'selected' : '' }} value="No">No</option>
                              <option {{ ($product->is_featured == 'Yes') ?'selected' : '' }} value="Yes">Yes</option>
                           </select>
                           <p class="error"></P>
                        </div>
                     </div>
                  </div>
                  <div class="card mb-3">
                     <div class="card-body">
                        <h2 class="h4 mb-3">Related product</h2>
                        <div class="mb-3">
                           <select multiple name="related_products[]" id="related_products" class="form-control related-product w-100" >
                              @if(!empty($relatedProducts))
                                 @foreach($relatedProducts as $relProduct)
                                    <option selected value="{{ $relProduct->id }}">{{$relProduct->title}}</option>
                                 @endforeach
                              @endif
                           </select>
                           <p class="error"></P>
                        </div>
                     </div>
                  </div>
                  <div class="card mb-3">
                     <div class="card-body">
                        <h2 class="h4 mb-3">Pricing</h2>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="mb-3">
                                 <label for="price">Price</label>
                                 <input type="text" value="{{ $product->price }}" name="price" id="price"
                                    class="form-control">
                                 <p class="error"></P>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="mb-3">
                                 <label for="compare_price">Compare at Price</label>
                                 <input type="text" value="{{ $product->compare_price }}" name="compare_price"
                                    id="compare_price" class="form-control">
                                 <p class="text-muted mt-3">
                                    To show a reduced price, move the product’s original price into Compare at price.
                                    Enter
                                    a lower value into Price.
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card mb-3">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="mb-3">
                                 <label for="shipping_returns">Shipping & Returns</label>
                                 <input type="text" value="{{ $product->shipping_returns }}" name="shipping_returns" id="shipping_returns"
                                    class="form-control">
                                 <p class="error"></P>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="pb-5 pt-3">
               <button type="submit" class="btn btn-primary">Update</button>
               <a href="{{route('products.list')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
         </div>
      </form>
      <!-- /.card -->
   </section>
   <!-- /.content -->
</div>
@endsection
@section('customJs')
<script>
//Product Related Select2 option code 
$('.related-product').select2({
    ajax: {
        url: '{{ route("get.products") }}',
        dataType: 'json',
        tags: true,
        multiple: true,
        minimumInputLength: 3,
        processResults: function (data) {
            return {
                results: data.tags
            };
        }
    }
}); 

$("#productForm").submit(function(event) {
   event.preventDefault();
   var formArray = $(this).serializeArray();
   //  $("button[type=submit)]").prop('disabled',true);
   $.ajax({
      url: '{{route("product.update",$product->id)}}',
      type: 'put',
      data: formArray,
      dataType: 'json',
      success: function(response) {
         //  $("button[type=submit)]").prop('disabled',false);
         if (response["status"] == true) {
            window.location.href = "{{route('products.list')}}";
            $(".error").removeClass('invalid-feedback').html('');
            $("input[type='text'],select,input[type='number']").removeClass('is-invalid');
         } else {
            var errors = response['errors'];
            $(".error").removeClass('invalid-feedback').html('');
            $("input[type='text'],select,input[type='number']").removeClass('is-invalid');
            $.each(errors, function(key, value) {
               $(`#${key}`).addClass('is-invalid').siblings('p')
                  .addClass('invalid-feedback').html(value);
            });
         }

      },
      error: function(jqXHR, exception) {
         console.log("Something Went Wrong!");
      }
   })
});
//ProductSubCategoryController
$("#category").change(function() {
   var category_id = $(this).val();
   // $("button[type=submit)]").prop('disabled',true);
   $.ajax({
      url: '{{ route("product-subCategories.index") }}',
      type: 'get',
      data: {
         category_id: category_id
      },
      dataType: 'json',
      success: function(response) {
         //   console.log(response);
         $('#sub_category').find('option').not(':first').remove();
         $.each(response['subCategories'], function(key, item) {
            $('#sub_category').append(`<option value='${item.id}'>${item.name}</option>`)
         });
      },
      error: function() {
         console.log('Something Went Wrong');
      }
   });
});

Dropzone.autoDiscover = false;
const dropzone = $("#image").dropzone({
   url: "{{ route('product-image.update') }}",
   maxFiles: 10,
   paramName: 'image',
   params   :{'product_id':'{{$product->id}}'},
   addRemoveLinks: true,
   acceptedFiles: "image/jpeg,image/png,image/gif",
   headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
   success: function(file, response) {
      // $("#image_id").val(response.image_id);
      // console.log(response)
      var html = `<div class="col-md-3" id="image-row-${response.image_id}">
                     <div class="card" >
                     <input type="hidden" name="image_array[]" value="${response.image_id}">
                     <img src="${response.imagePath}" class="card-img-top" alt="...">
                     <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        
                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                     </div>
                  </div>
                  </div>`;
      $('#product-gallery').append(html);
   },
   complete: function(file) {
      this.removeFile(file);
   }
});

function deleteImage(id) {
   $('#image-row-' + id).remove();
   if(confirm('Are you Sure Delete This Image')){
      $.ajax({
         url:"{{route('product-image.destory')}}",
         type:'delete',
         data:{id:id},
         success: function(response){
            if (response.status== true){
               alert(response.message);
            }else{
               alert(response.message);
            }
         }
      });
   }
}
//getTitleSlug
$("#title").change(function() {
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