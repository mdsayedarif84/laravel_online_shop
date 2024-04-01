@extends('front.home')
@section('title')
   Checkout 
@endsection
@section('body')
   <main>
      <section class="section-5 pt-3 pb-3 mb-3 bg-white">
         <div class="container">
            <div class="light-font">
               <ol class="breadcrumb primary-color mb-0">
                  <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                  <li class="breadcrumb-item"><a class="white-text" href="route('front.shop')">Shop</a></li>
                  <li class="breadcrumb-item">Checkout</li>
               </ol>
            </div>
         </div>
      </section>
      <section class="section-9 pt-4">
         <div class="container">
               <form name="orderForm" id="orderForm" action="" method="POST"> 
                  <div class="row">
                     <div class="col-md-8">
                        <div class="sub-title">
                              <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                              <div class="card-body checkout-form">
                                 <div class="row">
                                    <div class="col-md-12">
                                          <div class="mb-3">
                                             <input type="text" name="first_name" id="first_name" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : '' }}" class="form-control" placeholder="First Name">
                                             <p></p> 
                                          </div>
                                    </div>
                                    <div class="col-md-12">
                                          <div class="mb-3">
                                             <input type="text" name="last_name" id="last_name" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : '' }}" class="form-control" placeholder="Last Name">
                                             <p></p> 
                                          </div>            
                                    </div>
                                    <div class="col-md-12">
                                          <div class="mb-3">
                                             <input type="text" name="email" id="email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : '' }}" class="form-control" placeholder="Email">
                                             <p></p> 
                                          </div>            
                                    </div>
                                    <div class="col-md-12">
                                       <h2 class="h5 mb-3">Search a Country</h2>
                                       <div class="mb-3">
                                          <select name="country" id="country" class="form-control">
                                             <option value="">Select a Country</option>
                                             @if($countries->isNotEmpty())
                                                @foreach($countries as $country)
                                                   <option {{ (!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : '' }} value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                             @endif
                                          </select>
                                          <p></p> 
                                       </div>            
                                    </div>
                                    <div class="col-md-12">
                                          <div class="mb-3">
                                             <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">
                                                {{ (!empty($customerAddress)) ? $customerAddress->address : '' }}
                                             </textarea>
                                             <p></p> 
                                          </div>            
                                    </div>
                                    <div class="col-md-12">
                                          <div class="mb-3">
                                             <input type="text" name="apartment" id="apartment" value="{{ (!empty($customerAddress)) ? $customerAddress->apartment : '' }}" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)">
                                          </div>            
                                    </div>
                                    <div class="col-md-4">
                                          <div class="mb-3">
                                             <input type="text" name="city" id="city" value="{{ (!empty($customerAddress)) ? $customerAddress->city : '' }}" class="form-control" placeholder="City">
                                             <p></p> 
                                          </div>            
                                    </div>

                                    <div class="col-md-4">
                                          <div class="mb-3">
                                             <input type="text" name="state" id="state" value="{{ (!empty($customerAddress)) ? $customerAddress->state : '' }}" class="form-control" placeholder="State">
                                             <p></p> 
                                          </div>            
                                    </div>
                                    <div class="col-md-4">
                                          <div class="mb-3">
                                             <input type="text" name="zip" id="zip" value="{{ (!empty($customerAddress)) ? $customerAddress->zip : '' }}" class="form-control" placeholder="Zip">
                                             <p></p> 
                                          </div>            
                                    </div>
                                    <div class="col-md-12">
                                          <div class="mb-3">
                                             <input type="text" name="mobile" id="mobile" value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : '' }}" class="form-control" placeholder="Mobile No.">
                                             <p></p> 
                                          </div>            
                                    </div>
                                    <div class="col-md-12">
                                          <div class="mb-3">
                                             <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                                          </div>            
                                    </div>
                                 </div>
                              </div>
                        </div>    
                     </div>
                     <div class="col-md-4">
                        <div class="sub-title">
                              <h2>Order Summery</h3>
                        </div>                    
                        <div class="card cart-summery">
                              <div class="card-body">
                                 @foreach(Cart::content() as $item)
                                 <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{$item->name}} X {{ $item->qty}}</div>
                                    <div class="h6">${{ $item->price*$item->qty}}</div>
                                 </div>
                                 @endforeach
                                 <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>${{Cart::subtotal()}}</strong></div>
                                 </div>
                                 <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong id="ShippingAmount">${{(number_format($totalShippingCharge,2))}}</strong></div>
                                 </div>
                                 <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong id="grandTotal">${{(number_format($subtoltal,2))}}</strong></div>
                                 </div>                            
                              </div>
                        </div>   
                        
                        <div class="card payment-form ">
                              <h3 class="card-title h5 mb-3">Payment Method</h3>
                              <div class="form-group row">
                                 <div class="col-md-6">
                                    <input checked type="radio" name="payment_method" value="cod" id="payment_1">
                                    <label for="payment_1" class="form-check-label">COD</label>
                                 </div>
                                 <div class="col-md-6">
                                    <input type="radio" name="payment_method" value="cod" id="payment_2">
                                    <label for="payment_2" class="form-check-label">Stripe</label>
                                 </div>
                              </div>                        
                              <div class="card-body p-0 d-none" id="card_method_form">
                                 <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                                 </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                          <label for="expiry_date" class="mb-2">Expiry Date</label>
                                          <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                          <label for="expiry_date" class="mb-2">CVV Code</label>
                                          <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                    </div>
                                 </div>
                                 
                              </div>
                              <div class="pt-4">
                                 <!-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> -->
                                 <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                              </div>                        
                        </div>
                     </div>
                  </div>
               </form>
         </div>
      </section>
   </main>
   <div class=""></div>
@endsection
@section('customJs')
   <script>
      $('#payment_1').click(function(){
         if($(this).is(':checked') == true){
            $('#card_method_form').addClass('d-none');
         }
      });
      $('#payment_2').click(function(){
         if($(this).is(':checked') == true){
            $('#card_method_form').removeClass('d-none');
         }
      });

      $("#orderForm").submit(function(e){
         e.preventDefault();
         $('button[type="submit"]').prop('disabled',true);
         $.ajax({
            url:'{{ route("front.processCheckout") }}',
            type:'post',
            data:$(this).serializeArray(),
            dataType:'json',
            success: function(response){
               var errors   =   response.errors;
               $('button[type="submit"]').prop('disabled',false);
               if(response.status == false){
                  if(errors.first_name){
                     $("#first_name").siblings('p').addClass('invalid-feedback').html(errors.first_name);
                     $("#first_name").addClass('is-invalid');
                  }else{
                     $("#first_name").siblings('p').removeClass('is-invalid').html('');
                     $("#first_name").removeClass('is-invalid');
                  }
                  if(errors.last_name){
                     $("#last_name").siblings('p').addClass('invalid-feedback').html(errors.last_name);
                     $("#last_name").addClass('is-invalid');
                  }else{
                     $("#last_name").siblings('p').removeClass('is-invalid').html('');
                     $("#last_name").removeClass('is-invalid');
                  }
                  if(errors.email){
                     $("#email").siblings('p').addClass('invalid-feedback').html(errors.email);
                     $("#email").addClass('is-invalid');
                  }else{
                     $("#email").siblings('p').removeClass('is-invalid').html('');
                     $("#email").removeClass('is-invalid');
                  }
                  if(errors.country){
                     $("#country").siblings('p').addClass('invalid-feedback').html(errors.country);
                     $("#country").addClass('is-invalid');
                  }else{
                     $("#country").siblings('p').removeClass('is-invalid').html('');
                     $("#country").removeClass('is-invalid');
                  }
                  if(errors.address){
                     $("#address").siblings('p').addClass('invalid-feedback').html(errors.address);
                     $("#address").addClass('is-invalid');
                  }else{
                     $("#address").siblings('p').removeClass('is-invalid').html('');
                     $("#address").removeClass('is-invalid');
                  }
                  if(errors.city){
                     $("#city").siblings('p').addClass('invalid-feedback').html(errors.city);
                     $("#city").addClass('is-invalid');
                  }else{
                     $("#city").siblings('p').removeClass('is-invalid').html('');
                     $("#city").removeClass('is-invalid');
                  }
                  if(errors.state){
                     $("#state").siblings('p').addClass('invalid-feedback').html(errors.state);
                     $("#state").addClass('is-invalid');
                  }else{
                     $("#state").siblings('p').removeClass('is-invalid').html('');
                     $("#state").removeClass('is-invalid');
                  }
                  if(errors.zip){
                     $("#zip").siblings('p').addClass('invalid-feedback').html(errors.zip);
                     $("#zip").addClass('is-invalid');
                  }else{
                     $("#zip").siblings('p').removeClass('is-invalid').html('');
                     $("#zip").removeClass('is-invalid');
                  }
                  if(errors.mobile){
                     $("#mobile").siblings('p').addClass('invalid-feedback').html(errors.mobile);
                     $("#mobile").addClass('is-invalid');
                  }else{
                     $("#mobile").siblings('p').removeClass('is-invalid').html('');
                     $("#mobile").removeClass('is-invalid');
                  }
               }else{
                  $("#first_name").siblings('p').removeClass('is-invalid').html('');
                  $("#first_name").removeClass('is-invalid');

                  $("#last_name").siblings('p').removeClass('is-invalid').html('');
                  $("#last_name").removeClass('is-invalid');

                  $("#email").siblings('p').removeClass('is-invalid').html('');
                  $("#email").removeClass('is-invalid');

                  $("#country").siblings('p').removeClass('is-invalid').html('');
                  $("#country").removeClass('is-invalid');

                  $("#address").siblings('p').removeClass('is-invalid').html('');
                  $("#address").removeClass('is-invalid');

                  $("#city").siblings('p').removeClass('is-invalid').html('');
                  $("#city").removeClass('is-invalid');

                  $("#state").siblings('p').removeClass('is-invalid').html('');
                  $("#state").removeClass('is-invalid');

                  $("#zip").siblings('p').removeClass('is-invalid').html('');
                  $("#zip").removeClass('is-invalid');

                  $("#mobile").siblings('p').removeClass('is-invalid').html('');
                  $("#mobile").removeClass('is-invalid');

                  window.location.href="{{ url('/thanks/')}}/"+response.orderId;
               }
            },
         });
      });
      $("#country").change(function(){
         $.ajax({
            url      :  '{{ route("get.orderSummary") }}',
            type     :  'post',
            data     :  { country_id: $(this).val()},
            dataType :  'json',
            success  :  function(response){
               if(response.status == true){
                  $("#ShippingAmount").html('$'+response.ShippingCharge);
                  $("#grandTotal").html('$'+response.grandTotal);
               }
            }
         });
      });
      // $('.searchCountry').select2({
      //    ajax: {
      //       url: '{{ route("get.countries") }}',
      //       dataType: 'json',
      //       tags: true,
      //       multiple: true,
      //       minimumInputLength: 1,
      //       processResults: function (data) {
      //             return {
      //                results: data.tags
      //             };
      //       }
      //    }
      // });
      
   </script>
@endsection