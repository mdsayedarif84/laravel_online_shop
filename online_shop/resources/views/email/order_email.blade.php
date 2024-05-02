<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order Email</title>
   @include('admin.link.css')
</head>

<body>

   <div class="container-fluid">
      <div class="row">
         <div class="alert alert-success">
            @if($mailData['userType'] == 'customer')
            <strong>
               <h1 class="test">Congratulations!</h1>
               <font> for your order!! </font>
            </strong> Your Order id is: #{{ $mailData['order']->id}}.
            @else
            <strong>
               <font> You have received an order!! </font>
            </strong> Order id: #{{ $mailData['order']->id}}.
            @endif

            <div class="card-header pt-3">
               <div class="row invoice-info">
                  <div class="col-sm-6 invoice-col">
                     <h1 class="h5 mb-3">Shipping Address</h1>
                     <address>
                        <strong>{{$mailData['order']->first_name .' '.$mailData['order']->last_name}}</strong><br>
                        {{$mailData['order']->address}}<br>
                        {{$mailData['order']->city}}, {{$mailData['order']->zip}}, {{ getCountryInfo($mailData['order']->country_id)->name}}<br>
                        Phone: (+88) {{$mailData['order']->mobile}}<br>
                        Email: {{$mailData['order']->email}}
                     </address>
                     <strong>Shipping Date: </strong>
                     @if(!empty($mailData['order']->shipped_date))
                     {{ \Carbon\Carbon::parse($mailData['order']->shipped_date)->format('d, M, Y')}}
                     @else
                     n/a
                     @endif
                  </div>
                  <div class="col-sm-6 invoice-col">
                     <b>Invoice #00-{{$mailData['order']->first_name.'-'. $mailData['order']->id}}</b><br>
                     <br>
                     <b>Order ID:</b> {{$mailData['order']->id}}<br>
                     <b>Total:</b> ${{number_format($mailData['order']->grand_total,2)}}<br>
                     <b>Status:</b>
                     @if($mailData['order']->status == 'pending')
                     <span class="badge bg-danger">Pending</span>
                     @elseif($mailData['order']->status == 'shipped')
                     <span class="badge bg-info">Shipped</span>
                     @elseif($mailData['order']->status == 'cancelled')
                     <span class="badge bg-danger">Cancelled</span>
                     @else
                     <span class="badge bg-success">Delivered</span>
                     @endif
                     <br>
                  </div>
               </div>
            </div>
            <div class="card-body table-responsive p-3">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($mailData['order']->items as $item)
                     <tr>
                        <td>{{$item->name}}</td>
                        <td>${{number_format($item->price,2)}}</td>
                        <td>{{ $item->qty}}</td>
                        <td>${{number_format($item->total,2)}}</td>
                     </tr>
                     @endforeach
                     <tr>
                        <th colspan="3" class="text-right">Subtotal:</th>
                        <td>${{number_format($mailData['order']->subtotal,2)}}</td>
                     </tr>
                     <tr>
                        <th colspan="3" class="text-right">discount: {{ (!empty($mailData['order']->coupon_code)) ? '('.$mailData['order']->coupon_code.')' : '' }}</th>
                        <td>${{number_format($mailData['order']->discount,2)}}</td>
                     </tr>
                     <tr>
                        <th colspan="3" class="text-right">Shipping:</th>
                        <td>${{number_format($mailData['order']->shipping,2)}}</td>
                     </tr>
                     <tr>
                        <th colspan="3" class="text-right">Grand Total:</th>
                        <td>${{number_format($mailData['order']->grand_total,2)}}</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   @include('admin.link.js')
</body>

</html>