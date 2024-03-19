@extends('admin.dashboard.dashboard')
@section('title')
Create Shipping
@endsection
@section('body')
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid my-2">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Shipping Management</h1>
            </div>
            <div class="col-sm-6 text-right">
               <a href="{{route('shipping.create')}}" class="btn btn-primary">Shipping Create</a>
            </div>
         </div>
         @include('front.message.message')
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <!-- Default box -->
      <div class="container-fluid">
         <form action="" method="post" id="ShippingForm" name="ShippingForm">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-4">
                        <div class="mb-3">
                           <label for="name">Name</label>
                           <select  name="country" id="country" class="form-control searchCountry">
                              <option value="">Select a Country</option>
                                 @if($countries->isNotEmpty())
                                    @foreach($countries as $country)
                                       <option {{  ($shippingCharges->country_id == $country->id )? 'selected' : '' }} value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                    <option {{  ($shippingCharges->country_id == 'rest_of_world' )? 'selected' : '' }}  value="rest_of_world">Rest Of World</option>
                                 @endif
                           </select>
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="mb-3">
                           <label for="slug">Amount</label>
                           <input type="text" value="{{ $shippingCharges->amount}}"   name="amount" id="amount" class="form-control" >
                           <p></p>
                        </div>
                     </div>
                     <div class="pb-4 pt-4 col-md-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                     </div>
                  </div>
               </div>
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
$("#ShippingForm").submit(function(event) {
   event.preventDefault();
   var element = $(this);
   // $("button[type=submit)]").prop('disabled',true);
   $.ajax({
      url: '{{route("shipping.update",$shippingCharges->id)}}',
      type: 'put',
      data: element.serializeArray(),
      dataType: 'json',
      success: function(response) {
         // $("button[type=submit)]").prop('disabled',false);
         if (response["status"] == true) {
            window.location.href = '{{ route("shipping.create") }}';
            
         } else {
            var errors = response['errors'];
            if (errors['country']) {
               $("#country").addClass('is-invalid')
                  .siblings('p')
                  .addClass('invalid-feedback').html(errors['country']);
            } else {
               $("#country").removeClass('is-invalid')
                  .siblings('p')
                  .removeClass('invalid-feedback').html("");
            }
            if (errors['amount']) {
               $("#amount").addClass('is-invalid')
                  .siblings('p')
                  .addClass('invalid-feedback').html(errors['amount']);
            } else {
               $("#amount").removeClass('is-invalid')
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

</script>
@endsection