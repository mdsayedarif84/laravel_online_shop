@extends('admin.dashboard.dashboard')
@section('title')
Admin Dashboard
@endsection
@section('body')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Dashboard</h1>
				</div>
				<div class="col-sm-6">

				</div>
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{$totalOrders}}</h3>
							<p>Total Orders</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<a href="{{route('order.list')}}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{$totalCustomers}}</h3>
							<p>Total Customer</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="{{route('users.list')}}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>Tk. {{number_format($totalRevenue,2)}}</h3>
							<p>Total Revune</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{$totalProducts}}</h3>
							<p>Total Products</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<a href="{{route('products.list')}}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>TK. {{number_format($thisMnRevenue,2)}}</h3>
							<p>This Month Revenue</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>TK. {{number_format($lastMnRevenue,2)}}</h3>
							<p>Last Month Revenue ( {{$lastMnName}} ) </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
					</div>
				</div>
				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3> TK.{{number_format($lastThirtyDays,2)}}</h3>
							<p>Last 30 Days Sale</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
					</div>
				</div>
				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{$lastMnName}}</h3>
							<p>Last Month Name</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
					</div>
				</div>
				<div class="col-lg-4 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3></h3>
							<p>Last 30 Days Sale</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
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
	console.log('Hello');
</script>
@endsection