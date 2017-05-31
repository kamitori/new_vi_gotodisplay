@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="javascript:void(0)">User</a></li>
		<li><a href="{{ URL }}/user/profile">Shipping and tracking</a></li>
	</ul>
</div>		
<br/>

<form action="{{URL}}/cart" method="post" class="custom">
    <div class="">
        <div class="table-responsive" style="width: 100%;">
            <table width="100%" class="cart-table table table-striped">
                <thead>
                    <tr>
                        <th class="title">Code</th>
                        <th class="title">Order by</th>
                        <th class="title">Status</th>
                        <th class="title">Carrier</th>
                        <th class="title">Tracking number</th>
                        <th class="total">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arr_list as $arr)
                        <tr style="cursor: pointer;" onclick="window.location.href='{{url()}}/user/tracking/{{$arr['tracking_no']}}'">
                            <td class="title">
                                {{$arr['code']}}
                            </td>
                            <td class="total">{{$arr['contact_name']}}</td>
                            <td class="total">{{$arr['status']}}</td>
                            <td class="total">{{$arr['carrier_name']}}</td>
                            <td class="total">{{$arr['tracking_no']}}</td>
                            <td class="total">{{DEFAULT_CURRENCY}}{{number_format($arr['sum_sub_total'])}}</strong></td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>
    </div>
</form>

