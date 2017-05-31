@section('content')
<?php
	//pr($arr_order);die;
?>
<div class="breadcrumbs">
    <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
    <ul class="unstyled-list">
        <li><a href="{{ URL }}">Home</a></li>
        <li><a href="javascript:void(0)">User</a></li>
        <li><a href="{{ URL }}/oders">Orders</a></li>
    </ul>
</div>
<form action="{{URL}}/cart" method="post" class="custom">
    <div class="">
        <div class="table-responsive" style="width: 100%;">
            <!-- <h2>My Order of {{$user->firstname}} {{$user->lastname}}</h2> -->
            <table width="100%" class="cart-table table table-striped">
                <thead>
                    <tr>
                        <th class="title">Order ID</th>
                        <th class="title">Order by</th>
                        <th class="title">Status</th>
                        <th class="title">Date created</th>
                        <th class="total">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arr_order as $rowId=>$order)
                    <tr data-id='{{$order->id}}'>
                        <td class="title">
                            {{$order->id}}
                        </td>
                        
                        <td class="total">{{$order->first_name.' '.$order->last_name}}</td>
                        <td class="total">{{$order->status}}</td>
                        <td class="total">{{$order->created_at}}</td>
                        <td class="total"><strong>$ {{number_format($order->sum_amount,2)}}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@stop

@section('pageCSS')
<style type="text/css" media="screen">
        .cart-table tbody tr{
                cursor: pointer;
        }
         .cart-table tbody tr:hover{
                border-bottom:1px solid #999;
                border-top:1px solid #999;
         }
</style>
@stop

@section('pageJS')
<script>
    $(function(){
            $(".cart-table tbody tr").on('click',function(){
                    id = $(this).attr('data-id');
                    window.location = '{{URL}}/view-order/'+id;
            })
    })
</script>
@stop
