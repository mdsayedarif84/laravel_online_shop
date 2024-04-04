@extends('admin.dashboard.dashboard')
@section('title')
Cupon List
@endsection
@section('body')
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid my-2">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Cupons</h1>
            </div>
            <div class="col-sm-6 text-right">
               <a href="{{route('cupon.create')}}" class="btn btn-primary">New Cupon</a>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <!-- Default box -->
      <div class="container-fluid">
         @include('admin.message.message')
         <div class="card">
            <form action="" method="get">
               <div class="card-header">
                  <div class="card-title">
                     <button type="button" onclick="window.location.href='{{ route("cupon.index") }}'" class="btn btn-default btn-sm">Reset</button>
                  </div>
                  <div class="card-tools">
                     <div class="input-group input-group" style="width: 250px;">
                        <input value="{{ Request::get('keyword')}}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                           <button type="submit" class="btn btn-default">
                              <i class="fas fa-search"></i>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap">
                  <thead>
                     <tr>
                        <th width="60">ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Discount</th>
                        <th>Starts Date</th>
                        <th>End Date</th>
                        <th width="100">Status</th>
                        <th width="100">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if(!empty($cupons))
                     @foreach($cupons as $cupon)
                     <tr>
                        <td>{{ $cupon->id}}</td>
                        <td>{{ $cupon->code}}</td>
                        <td>{{ $cupon->name}}</td>
                        <td>
                           @if($cupon->type == 'percent')
                           {{ $cupon->discount_amount}}%
                           @else
                           ${{ $cupon->discount_amount}}
                           @endif
                        </td>
                        <td>{{ (!empty($cupon->starts_at)) ? \Carbon\Carbon::parse($cupon->starts_at)->format('Y-m-d / H:i:s') : '' }}</td>
                        <td>{{ (!empty($cupon->expires_at)) ? \Carbon\Carbon::parse($cupon->expires_at)->format('Y-m-d / H:i:s') : '' }}</td>
                        <td>
                           @if( $cupon->status==1)
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
                           <a href="{{route('cupon.edit',$cupon->id)}}" title="Edit">
                              <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                 <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                              </svg>
                           </a>
                           <a href="#" onclick="couponDel({{$cupon->id}})" class="text-danger w-4 h-4 mr-1" title="Delete">
                              <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                 <path ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                              </svg>
                           </a>
                        </td>
                     </tr>
                     @endforeach
                     @else
                     <tr>
                        <td colspan="5" class="text-center text-danger">
                           <h2>Records Not Found</h2>
                        </td>
                     </tr>
                     @endif
                  </tbody>
               </table>
            </div>
            <div class="card-footer clearfix">
               {{ $cupons->links()}}
            </div>
         </div>
      </div>
      <!-- /.card -->
   </section>
   <!-- /.content -->
</div>
@endsection
@section('customJs')
<script type="text/javascript">
   function couponDel(id) {
      var url = "{{ route('cupon.delete','ID') }}";
      var newUrl = url.replace("ID", id);
      if (confirm("Are Your Sure Want To Delete This!")) {
         $.ajax({
            url: newUrl,
            type: 'delete',
            data: {},
            dataType: 'json',
            success: function(response) {
               // $("button[type=submit)]").prop('disabled',false);
               if (response["status"]) {
                  window.location.href = '{{ route("cupon.index") }}';
               } else {

               }
            }
         });
      }
   }
</script>
@endsection