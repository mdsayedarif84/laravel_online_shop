<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>@yield('title')</title>

		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		    <!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{asset('adminAsset/plugins/fontawesome-free/css/all.min.css')}}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{asset('adminAsset/css/adminlte.min.css')}}">
	<link rel="stylesheet" href="{{asset('adminAsset/plugins/dropzone/min/dropzone.min.css')}}">

	<link rel="stylesheet" href="{{asset('adminAsset/css/custom.css')}}">
	<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
	</head>
	<body class="hold-transition sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<!-- Navbar -->
			@include('admin.dashboard.includes.header')
			<!-- /.navbar -->
			<!-- Main Sidebar Container -->
			@include('admin.dashboard.includes.sidebar')
			<!-- Content Wrapper. Contains page content -->
                @yield('body')
			<!-- /.content-wrapper -->
			<footer class="main-footer">
				
				<strong>Copyright &copy; 2014-2022 AmazingShop All rights reserved.
			</footer>
			
		</div>
		<!-- ./wrapper -->
		<!-- jQuery -->
		<!-- <script src="plugins/jquery/jquery.min.js"></script>-->
		<!-- Bootstrap 4  -->
		<!-- jQuery -->
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script> -->
		<script src="{{asset('adminAsset/plugins/jquery/jquery.min.js')}}"></script>
		
		<!-- Bootstrap 4 -->
		<script src="{{asset('adminAsset/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<!-- AdminLTE App -->
		<script src="{{asset('adminAsset/js/adminlte.min.js')}}"></script>
		<script src="{{asset('adminAsset/plugins/dropzone/min/dropzone.min.js')}}"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="{{asset('adminAsset/js/demo.js')}}"></script>
		<script type="text/javascript">
			$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
			});
		</script>
		@yield('customJs')
	</body>
</html>