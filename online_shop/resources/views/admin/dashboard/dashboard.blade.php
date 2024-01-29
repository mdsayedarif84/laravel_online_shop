<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laravel Shop :: Administrative Panel</title>
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		@include('admin.link.css')
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
		@include('admin.link.js')
	</body>
</html>