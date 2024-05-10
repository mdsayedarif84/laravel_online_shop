@extends('front.home')
@section('title')
Customer Profile
@endsection
@section('body')
<main>
   <section class="section-5 pt-3 pb-3 mb-3 bg-white">
      <div class="container">
         <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
               <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
               <li class="breadcrumb-item">Settings</li>
            </ol>
         </div>
      </div>
   </section>
   <section class=" section-11 ">
      <div class="container  mt-5">
         @include('front.message.message')
         <div class="row">
            <div class="col-md-3">
               @include('front/customer-account/includes/profile-panel')
            </div>
            <div class="col-md-3">
               <div class="card">
                  <div class="card-header">
                     <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                  </div>
                  <form action="" name="profileForm" id="profileForm" method="">
                     <div class="card-body p-4">
                        <div class="row">
                           <div class="mb-3">
                              <label for="name">Name</label>
                              <input value="{{$user->name}}" type="text" name="name" id="name" class="form-control">
                              <p></p>
                           </div>
                           <div class="mb-3">
                              <label for="email">Email</label>
                              <input value="{{$user->email}}" type="text" name="email" id="email" class="form-control">
                              <p></p>
                           </div>
                           <div class="mb-3">
                              <label for="phone">Phone</label>
                              <input value="{{$user->phone}}" type="text" name="phone" id="phone" class="form-control">
                              <p></p>
                           </div>
                           <div class="d-flex">
                              <button class="btn btn-dark">Update</button>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="col-md-6">
               <div class="card">
                  <div class="card-header">
                     <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                  </div>
                  <form action="" name="addressForm" id="addressForm" method="">
                     <div class="card-body p-4">
                        <div class="row">
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="mb-3">
                                    <label for="name">FirstName</label>
                                    <input value="{{(!empty($customerAdds)) ? $customerAdds->first_name : '' }}" type="text" name="first_name" id="first_name" class="form-control">
                                    <p></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="mb-3">
                                    <label for="name">LastName</label>
                                    <input value="{{(!empty($customerAdds)) ? $customerAdds->last_name : '' }}" type="text" name="last_name" id="last_name" class="form-control">
                                    <p></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="mb-3">
                                    <label for="phone">Mobile</label>
                                    <input value="{{(!empty($customerAdds)) ? $customerAdds->mobile : '' }}" type="text" name="mobile" id="mobile" class="form-control">
                                    <p></p>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-5">
                                 <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input value="{{(!empty($customerAdds)) ? $customerAdds->email : '' }}" type="text" name="email" id="email" class="form-control">
                                    <p></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="mb-3">
                                    <label for="phone">Country</label>
                                    <select name="country_id" id="country_id" class="form-control form-select" aria-label="Default select example">
                                       <option>Select Country</option>
                                       @if($countries->isNotEmpty())
                                       @foreach($countries as $country)
                                       <option {{ (!empty($customerAdds) && $customerAdds->country_id == $country->id) ? 'selected' : '' }} value="{{$country->id}}">{{$country->name}}</option>
                                       @endforeach
                                       @endif
                                    </select>
                                    <p></p>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="mb-3">
                                    <label for="zip">Zip</label>
                                    <input value="{{(!empty($customerAdds)) ? $customerAdds->zip : '' }}" type="text" name="zip" id="zip" class="form-control">
                                    <p></p>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="mb-3">
                                    <label for="apartment">Apartment</label>
                                    <input value="{{(!empty($customerAdds)) ? $customerAdds->apartment : '' }}" type="text" name="apartment" id="apartment" class="form-control">
                                    <p></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="mb-3">
                                    <label for="city">City</label>
                                    <input value="{{(!empty($customerAdds)) ? $customerAdds->city : '' }}" type="text" name="city" id="city" class="form-control">
                                    <p></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="mb-3">
                                    <label for="state">State</label>
                                    <input value="{{(!empty($customerAdds)) ? $customerAdds->customerAdds : '' }}" type="text" name="state" id="state" class="form-control">
                                    <p></p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="mb-3">
                                 <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">
                                 {{ (!empty($customerAdds)) ? $customerAdds->address : '' }}
                                 </textarea>
                                 <p></p>
                              </div>
                           </div>
                           <div class="d-flex">
                              <button class="btn btn-dark">Update</button>
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
   $('#profileForm').submit(function(event) {
      event.preventDefault();
      var data = $(this).serializeArray();
      $.ajax({
         url: '{{ route("account.updateProfile")}}',
         type: 'post',
         data: data,
         dataType: 'json',
         success: function(response) {
            if (response.status == true) {
               $("#profileForm #name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#profileForm #email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#profileForm #phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               window.location.href = "{{ route('account.profile') }}";
            } else {
               var errors = response.errors;
               if (errors.name) {
                  $("#profileForm #name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
               } else {
                  $("#profileForm #name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.email) {
                  $("#profileForm #email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.email);
               } else {
                  $("#profileForm #email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.phone) {
                  $("#profileForm #phone").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.phone);
               } else {
                  $("#profileForm #phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
            }
         }
      });
   });

   //customer addressForm
   $('#addressForm').submit(function(event) {
      event.preventDefault();
      var data = $(this).serializeArray();
      $.ajax({
         url: '{{ route("account.updateAddress")}}',
         type: 'post',
         data: data,
         dataType: 'json',
         success: function(response) {
            if (response.status == true) {
               $("#first_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#last_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#addressForm #email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#addressForm #mobile").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#country_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#city").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#state").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#zip").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               window.location.href = "{{ route('account.profile') }}";
            } else {
               var errors = response.errors;
               if (errors.first_name) {
                  $("#first_name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.first_name);
               } else {
                  $("#first_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.last_name) {
                  $("#last_name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.last_name);
               } else {
                  $("#last_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.email) {
                  $("#addressForm #email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.email);
               } else {
                  $("#addressForm #email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.mobile) {
                  $(" #mobile").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.mobile);
               } else {
                  $(" #mobile").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.country_id) {
                  $(" #country_id").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.country_id);
               } else {
                  $(" #country_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.city) {
                  $(" #city").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.city);
               } else {
                  $(" #city").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.state) {
                  $(" #state").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.state);
               } else {
                  $(" #state").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (errors.zip) {
                  $(" #zip").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.zip);
               } else {
                  $(" #zip").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
            }
         }
      });
   });
</script>
@endsection