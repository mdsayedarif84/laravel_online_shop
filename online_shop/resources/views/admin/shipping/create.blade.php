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
            <div class="col-sm-12">
               <h1 class="text-center text-primary">Shipping Management</h1>
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
                                       <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                    <option value="rest_of_world">Rest Of World</option>
                                 @endif
                           </select>
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="mb-3">
                           <label for="slug">Amount</label>
                           <input type="text"  name="amount" id="amount" class="form-control" placeholder="Amount">
                           <p></p>
                        </div>
                     </div>
                     <div class="pb-4 pt-4 col-md-2">
                        <button type="submit" class="btn btn-primary">Create</button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
         <div class="card">
            <div class="card-body table-responsive table-striped p-0">
               <div class="row">
                  <div class="col-md-12">
                  <table class="table table-hover text-nowrap">
									<thead>
										<tr>
											<th width="60">ID</th>
											<th>Country</th>
											<th>Amount</th>
											<th width="100">Action</th>
										</tr>
									</thead>
									<tbody>
										@if($shippingCharges->isNotEmpty())
											@foreach($shippingCharges as $item)
												<tr>
													<td>{{ $item->id}}</td>
													<td>
                                          {{ ($item->country_id == 'rest_of_world') ? 'Rest of World' : $item->name }}
                                       </td>
													<td>${{ $item->amount}}</td>
													<td>
														@if( $item->status==1)
															<svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
																<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
															</svg>
														@else
															<svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
																<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
															</svg>
														@endif
													</td>
													<td>
														<a href="{{ route('shipping.edit',$item->id) }}">
															<svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
																<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
															</svg>
														</a>
														<a href="javascript:void(0)" onclick="catDel( {{$item->id}} )" class="text-danger w-4 h-4 mr-1">
															<svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
																<path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
															</svg>
														</a>
													</td>
												</tr>
											@endforeach
										@else
											<tr>
												<td colspan="5" class="text-center text-danger"><h2>Records Not Found</h2></td>
											</tr>
										@endif
									</tbody>
								</table>	
                  </div>
               </div>
            </div>
         </div>
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
         url: '{{route("shipping.store")}}',
         type: 'POST',
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
<script type="text/javascript">
   function catDel(id) {
      var url = "{{ route('shipping.delete','ID') }}";
      var newUrl = url.replace("ID", id);
      if (confirm("Are Your Sure Want To Delete This!")) {
         $.ajax({
            url: newUrl,
            type: 'delete',
            data: {},
            dataType: 'json',
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
               // window.location.href = '{{ route("sub-categories.list") }}';
               if (response["status"]) {
                  window.location.href = '{{ route("shipping.create") }}';
               } 
            }
         });
      }
   }
</script>
@endsection