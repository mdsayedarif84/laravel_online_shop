<!DOCTYPE html>
<html class="no-js" lang="en_AU" />

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <title>@yield('title')</title>
   <meta name="description" content="" />
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

   <meta name="HandheldFriendly" content="True" />
   <meta name="pinterest" content="nopin" />

   <meta property="og:locale" content="en_AU" />
   <meta property="og:type" content="website" />
   <meta property="fb:admins" content="" />
   <meta property="fb:app_id" content="" />
   <meta property="og:site_name" content="" />
   <meta property="og:title" content="" />
   <meta property="og:description" content="" />
   <meta property="og:url" content="" />
   <meta property="og:image" content="" />
   <meta property="og:image:type" content="image/jpeg" />
   <meta property="og:image:width" content="" />
   <meta property="og:image:height" content="" />
   <meta property="og:image:alt" content="" />

   <meta name="twitter:title" content="" />
   <meta name="twitter:site" content="" />
   <meta name="twitter:description" content="" />
   <meta name="twitter:image" content="" />
   <meta name="twitter:image:alt" content="" />
   <meta name="twitter:card" content="summary_large_image" />
   <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

   <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/slick.css')}}" />
   <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/slick-theme.css')}}" />
   <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/video-js.css')}}" />
   <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/ion.rangeSlider.min.css')}}" />
   <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/style.css')}}" />
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font.awesome.css')}}" /> -->

   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

   <!-- Fav Icon -->
   <link rel="shortcut icon" type="image/x-icon" href="#" />
   <!--This link from admin site css-->

   <link rel="stylesheet" href="{{asset('../adminAsset/plugins/select2/css/select2.min.css')}}">

</head>

<body data-instant-intensity="mousedown">

   <!-- header start -->
   @include('front.includes.header')
   <!-- header end -->
   <!-- main start  -->
   @yield('body')
   <!-- end main  -->
   <!-- start footer -->
   @include('front.includes.footer')
   <!-- end footer -->

   <!-- Wishlist Modal -->
   <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Success!</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
   <script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
   <!-- <script src="{{asset('front-assets/js/jquery-3.7.1.min.js')}}"></script> -->
   <script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
   <script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>
   <script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
   <script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
   <script src="{{ asset('front-assets/js/custom.js') }}"></script>
   <script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>
   <script src="{{asset('../adminAsset/plugins/select2/js/select2.min.js') }}"></script>
   <script type="text/javascript">
      $.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
   </script>
   <script>
      window.onscroll = function() {
         myFunction()
      };

      var navbar = document.getElementById("navbar");
      var sticky = navbar.offsetTop;

      function myFunction() {
         if (window.pageYOffset >= sticky) {
            navbar.classList.add("sticky")
         } else {
            navbar.classList.remove("sticky");
         }
      }
      //This cdoe addToCart
      function addToCart(id) {
         // alert(id);
         $.ajax({
            url: '{{ route("front.addToCart") }}',
            type: 'post',
            data: {
               id: id
            },
            dataType: 'json',
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
               if (response.status == true) {
                  window.location.href = " {{ route('cart') }} "
               } else {
                  alert(response.message);
               }
            },
         });
      }
      //Wishlist
      function addToWishlist(id) {
         $.ajax({
            url: '{{ route("front.addToWishlist") }}',
            type: 'post',
            data: {
               id: id
            },
            dataType: 'json',
            success: function(response) {
               if (response.status == true) {
                  $('#wishlistModal .modal-body').html(response.message)
                  $('#wishlistModal').modal('show');
               } else {
                  window.location.href = " {{ route('login') }} ";
                  // alert(response.message);
               }
            },
         });
      }
   </script>
   @yield('customJs')
</body>

</html>