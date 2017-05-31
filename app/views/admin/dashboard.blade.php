<div class="clearfix">
</div>
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat blue-madison">
			<div class="visual">
				<i class="fa fa-users"></i>
			</div>
			<div class="details">
				<div class="number">
					 {{ number_format($notifications['users']) }}
				</div>
				<div class="desc">
					 New Users
				</div>
			</div>
			<a class="more" href="{{ URL.'/admin/users' }}">
			View more <i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat red-intense">
			<div class="visual">
				<i class="icon-bag"></i>
			</div>
			<div class="details">
				<div class="number">
					 {{ number_format($notifications['products']) }}
				</div>
				<div class="desc">
					 New Products
				</div>
			</div>
			<a class="more" href="{{ URL.'/admin/products' }}">
			View more <i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat green-haze">
			<div class="visual">
				<i class="fa fa-shopping-cart"></i>
			</div>
			<div class="details">
				<div class="number">
					 {{ number_format($notifications['orders']) }}
				</div>
				<div class="desc">
					 New Orders
				</div>
			</div>
			<a class="more" href="{{ URL.'/admin/orders' }}">
			View more <i class="m-icon-swapright m-icon-white"></i>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat purple-plum">
			<div class="visual">
				<i class="fa fa-clock-o"></i>
			</div>
			<div class="details">
				<div class="clock"></div>
			</div>
			<span class="more pull-right" style="width: 100%;">
				<div class="pull-right caption-subject bold uppercase">{{ $date['current_date']->format('F d, Y') }}</div>
			</span>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6 col-sm-6 portlet-holder">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-sharp">
					<i class="fa fa-calendar font-green-sharp"></i><span class="caption-subject bold uppercase"> Portlet</span>
				</div>
				<div class="tools">
					<a href="#" class="remove">
					</a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-4">
						<div class="easy-pie-chart">
							<div class="number transactions" data-percent="55">
								<span>
								+55 </span>
								%
							</div>
							<a class="title" href="#">
							Transactions <i class="icon-arrow-right"></i>
							</a>
						</div>
					</div>
					<div class="margin-bottom-10 visible-sm">
					</div>
					<div class="col-md-4">
						<div class="easy-pie-chart">
							<div class="number visits" data-percent="85">
								<span>
								+85 </span>
								%
							</div>
							<a class="title" href="#">
							New Visits <i class="icon-arrow-right"></i>
							</a>
						</div>
					</div>
					<div class="margin-bottom-10 visible-sm">
					</div>
					<div class="col-md-4">
						<div class="easy-pie-chart">
							<div class="number bounce" data-percent="46">
								<span>
								-46 </span>
								%
							</div>
							<a class="title" href="#">
							Bounce <i class="icon-arrow-right"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 portlet-holder">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="icon-settings font-red-sunglo"></i><span class="caption-subject bold uppercase"> Server Stats</span>
				</div>
				<div class="tools">
					<a href="#" class="remove">
					</a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-4">
						<div class="sparkline-chart">
							<div class="number">
								<div class="row">
									<span class="col-md-2" data-toggle="tooltip" title="Lasted update"><i class="fa fa-clock-o"></i></span>
									<span id="updated-at">{{ isset($sync->date_modified) ? date('d M, y H:i', $sync->date_modified->sec) : '' }}</span>
								</div>
								<div class="row" style="margin-top: 17px;">
									<span class="col-md-2" data-toggle="tooltip" title="Updated time"><i class="fa fa-sort-numeric-asc"></i></span>
									<span id="updated-time">{{ $sync->sync_time or '' }}</span>
								</div>
							</div>
							<a class="title" href="javascript:void(0)" onclick="syncData()">
							Sync now <i class="icon-arrow-right"></i>
							</a>
						</div>
					</div>
					<div class="margin-bottom-10 visible-sm">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Order Static -->
<div class="row">
	<div class="col-md-12 col-sm-12 portlet-holder">
		<div id="order-statistics-container" class="portlet light bg-inverse bordered">
			<div class="portlet-title">
				<div class="caption col-md-2 font-yellow-casablanca">
					<i class="fa fa-bar-chart-o font-yellow-casablanca"></i><span class="caption-subject bold uppercase"> Order Statistics</span>
				</div>
				<div class="tools col-md-1">
					<a href="#" class="fullscreen">
					</a>
					<a href="#" class="remove">
					</a>
				</div>
				<div class="actions col-md-9">
					<div class="col-md-5">
						<div id="order-statistics-range" class="tooltips btn grey-salt col-md-12" data-placement="top" data-original-title="Change order statistics date range">
							<i class="icon-calendar"></i>&nbsp;
							<span class="thin uppercase visible-lg-inline-block">{{ $date['start_date']->format('F d, Y') }} - {{ $date['current_date']->format('F d, Y') }}</span>&nbsp;
							<i class="fa fa-angle-down"></i>
						</div>
					</div>
					<div class="col-md-2">
						<select data-style="yellow" class="bs-select form-control" title="Order status" name="order_status">
							<option value=""></option>
							@if( isset($filters['order_status']) )
							@foreach($filters['order_status'] as $status)
							<option value="{{ $status }}">{{ $status }}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="col-md-3">
						<select  data-style="btn-danger" class="bs-select form-control" title="Product category" name="product_category">
							@if( isset($filters['categories']) )
							@foreach($filters['categories'] as $category)
							<option value="{{ $category['value'] }}">{{ $category['text'] }}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="col-md-2">
						<select data-style="btn-primary" class="bs-select form-control" title="Group by" name="range_filter">
							<option value="day">Day</option>
							<option value="month">Moth</option>
							<option value="year">Year</option>
						</select>
					</div>
				</div>
			</div>
			<div class="portlet-body">
				<div id="order-statistics-loading">
					<img src="{{ URL }}/assets/admin/layout/img/loading.gif" alt="loading"/>
				</div>
				<div id="order-statistics-content" class="display-none">
					<div id="order-statistics" style="height: 228px; width: 100%;">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Visit Static -->
<div class="row">
	<div class="col-md-12 col-sm-12 portlet-holder">
		<div id="visit-statistics-container" class="portlet light bg-inverse bordered">
			<div class="portlet-title">
				<div class="caption col-md-2 font-yellow-casablanca">
					<i class="fa fa-bar-chart-o font-yellow-casablanca"></i><span class="caption-subject bold uppercase"> Visit Statistics</span>
				</div>
				<div class="tools col-md-1">
					<a href="#" class="fullscreen">
					</a>
					<a href="#" class="remove">
					</a>
				</div>
				<div class="actions col-md-9">
					<div class="col-md-5">
						<div id="visit-statistics-range" class="tooltips btn grey-salt col-md-12" data-placement="top" data-original-title="Change visit statistics date range">
							<i class="icon-calendar"></i>&nbsp;
							<span class="thin uppercase visible-lg-inline-block">&nbsp;</span>&nbsp;
							<i class="fa fa-angle-down"></i>
						</div>
					</div>
					<div class="col-md-2">
						<select data-style="btn-primary" class="bs-select form-control" title="Group by" name="visit_range_filter">
							<option value="day">Day</option>
							<option value="month">Moth</option>
							<option value="year">Year</option>
						</select>
					</div>
				</div>
			</div>
			<div class="portlet-body">
				<div id="visit-statistics-loading">
					<img src="{{ URL }}/assets/admin/layout/img/loading.gif" alt="loading"/>
				</div>
				<div id="visit-statistics-content" class="display-none">
					<div id="visit-statistics" style="height: 228px; width: 100%;">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/bootstrap-select/bootstrap-select.min.css' ) }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css' ) }}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/flipclock/css/flipclock.css' ) }}"/>
<style type="text/css">
.flip-clock-wrapper {
	margin: 20px 0px 0px 40px !important
}
.flip-clock-wrapper ul.flip {
	height: 40px !important;
	width: 40px !important;
	line-height: 40px !important;
}
.flip-clock-wrapper ul li {
	line-height: 40px !important;
}
.flip-clock-wrapper div.inn {
	font-size: 20px !important;
}
.flip-clock-wrapper ul.flip:nth-child(7), .flip-clock-wrapper ul[class~='flip']:last-of-type {
	display: none !important;
}
.flip-clock-wrapper span[class~='flip-clock-divider']:last-of-type  {
	display: none !important;
}
.flip-clock-wrapper span.flip-clock-divider {
	height: 55px !important;
}
.chart-tooltip span.label {
	display: inline !important;
}
</style>
@stop
@section('pageJS')
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/bootstrap-select/bootstrap-select.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/bootstrap-daterangepicker/moment.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/flot/jquery.flot.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/flot/jquery.flot.resize.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/flot/jquery.flot.categories.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/flipclock/js/flipclock.min.js' ) }}"></script>
<script type="text/javascript">
$('.clock').FlipClock({
	clockFace: 'TwentyFourHourClock'
});
$('#order-statistics-range').daterangepicker({
	    startDate: '{{ $date['start_date']->format('m/d/Y') }}',
	    endDate: '{{ $date['max_date'] }}',
	    minDate: '{{ $date['min_date'] }}',
	    maxDate: '{{ $date['max_date'] }}',
	    showWeekNumbers: true,
	    buttonClasses: ['btn btn-sm'],
	    applyClass: ' blue',
	    cancelClass: 'default',
	    format: 'MM/DD/YYYY',
	    separator: ' to ',
	    locale: {
	        applyLabel: 'Apply',
	        fromLabel: 'From',
	        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
	        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	        firstDay: 1
	    }
	},
	function (start, end) {
	    $('#order-statistics-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	    updateChart();
	}
);
$('#visit-statistics-range').daterangepicker({
	    startDate: '{{ $date['start_date']->format('m/d/Y') }}',
	    endDate: '{{ $date['max_date'] }}',
	    minDate: '{{ $date['min_date'] }}',
	    maxDate: '{{ $date['max_date'] }}',
	    showWeekNumbers: true,
	    buttonClasses: ['btn btn-sm'],
	    applyClass: ' blue',
	    cancelClass: 'default',
	    format: 'MM/DD/YYYY',
	    separator: ' to ',
	    locale: {
	        applyLabel: 'Apply',
	        fromLabel: 'From',
	        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
	        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	        firstDay: 1
	    }
	},
	function (start, end) {
	    $('#visit-statistics-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	    updateVisitChart(true,start.format('MMMM D, YYYY'),end.format('MMMM D, YYYY'));
	}
);
$("#order-statistics-container select").change(function(){
	updateChart();
});
//Run update visit chart first
updateVisitChart(true,'{{ $date['start_date']->format('m/d/Y') }}','{{ $date['max_date'] }}' );

// Change group visit chart
$("[name=visit_range_filter]").on("change",function(){
	range_time =  $('#visit-statistics-range span').html();
	range_time = range_time.split(" - ");
	start = range_time[0];
	end = range_time[1];
	updateVisitChart(true,start,end);
})

$("#order-statistics-container select:first").trigger("change");
$('#order-statistics-range').show();

$('#visit-statistics-range span').html('{{ $date['start_date']->format('F d, Y') }} - {{ $date['current_date']->format('F d, Y') }}');
$('#visit-statistics-range').show();
$('#visit-statistics-loading').hide();
$('#visit-statistics-content').show();

var points = {};
$("#order-statistics, #visit-statistics").bind("plothover", function (event, pos, item) {
	var id =$(this).attr("id");
	if( points[id] == undefined ) {
		points[id] = null;
	}
    $("#x").text(pos.x.toFixed(2));
    $("#y").text(pos.y.toFixed(2));
    if (item) {
        if (points[id] != item.dataIndex) {
            points[id] = item.dataIndex;

            $("#tooltip").remove();
            if( id == "order-statistics" ) {
            	var x = item.datapoint[0].toFixed(2),
            	    y = item.datapoint[1].toFixed(2);
            	    data = item.series.data[item.dataIndex][2];
            	showChartTooltip(item.pageX, item.pageY, x, '<b>' + y + ' $</b>' + data );
            } else {
            	var x = item.datapoint[0].toFixed(0),
            	    y = item.datapoint[1].toFixed(0);
            	showChartTooltip(item.pageX, item.pageY, x, '<b>' + y + ' <i class="fa fa-users"></i></b>' );
            }
        }
    } else {
        $("#tooltip").remove();
        points[id] = null;
    }
});
$("[data-toggle=tooltip]").tooltip();
$('.bs-select').selectpicker({
    iconBase: 'fa',
    tickIcon: 'fa-check'
});

function updateChart()
{
	var dataPost = {};
	$("select", "#order-statistics-container").each(function(){
		dataPost[ $(this).attr("name") ] = $(this).val();
	});
	dataPost["date_start"] = $("[name=daterangepicker_start]").val();
	dataPost["date_end"] = $("[name=daterangepicker_end]").val();
	$.ajax({
		url: "{{ URL.'/admin/dashboards/get-order-statistic' }}",
		data: dataPost,
		type: "POST",
		async: false,
		success: function(result){
			if( result.status == "ok" ) {
				data = result.data;
			}
		}
	});
	var newData = [{
				    data: data,
				    lines: {
				        fill: 0.2,
				        lineWidth: 0,
				    },
				    color: ['#BAD9F5']
				}, {
				    data: data,
				    points: {
				        show: true,
				        fill: true,
				        radius: 4,
				        fillColor: "#9ACAE6",
				        lineWidth: 2
				    },
				    color: '#9ACAE6',
				    shadowSize: 1
				}, {
				    data: data,
				    lines: {
				        show: true,
				        fill: false,
				        lineWidth: 3
				    },
				    color: '#9ACAE6',
				    shadowSize: 0
				}];
	var options = {
        		xaxis: {
		            tickLength: 0,
		            tickDecimals: 0,
		            mode: "categories",
		            min: 0,
		            font: {
		                lineHeight: 18,
		                style: "normal",
		                variant: "small-caps",
		                color: "#6F7B8A"
		            }
		        },
		        yaxis: {
		            ticks: 5,
		            tickDecimals: 0,
		            min: 0,
		            tickColor: "#eee",
		            font: {
		                lineHeight: 14,
		                style: "normal",
		                variant: "small-caps",
		                color: "#6F7B8A"
		            }
		        },
		        grid: {
		            hoverable: true,
		            clickable: true,
		            tickColor: "#eee",
		            borderColor: "#eee",
		            borderWidth: 1
		        }
		    };
	$.plot("#order-statistics", newData, options);
}

function updateVisitChart(data,start,end)
{
	$('#order-statistics-loading').show();
	if( data == true ) {
		var dataPost = {};
		dataPost["date_start"] = start;
		dataPost["date_end"] = end;
		dataPost['range_filter'] = $("[name=visit_range_filter]").val();
		$.ajax({
			url: "{{ URL.'/admin/dashboards/get-visit-statistic' }}",
			data: dataPost,
			type: "POST",
			async: false,
			success: function(result){
				if( result.status == "ok" ) {
					data = result.data;
				}
			}
		});
	}
	var newData = [{
				    data: data,
				    lines: {
				        fill: 0.2,
				        lineWidth: 0,
				    },
				    color: ['#BAD9F5']
				}, {
				    data: data,
				    points: {
				        show: true,
				        fill: true,
				        radius: 4,
				        fillColor: "#9ACAE6",
				        lineWidth: 2
				    },
				    color: '#9ACAE6',
				    shadowSize: 1
				}, {
				    data: data,
				    lines: {
				        show: true,
				        fill: false,
				        lineWidth: 3
				    },
				    color: '#9ACAE6',
				    shadowSize: 0
				}];
	var options = {
        		xaxis: {
		            tickLength: 0,
		            tickDecimals: 0,
		            mode: "categories",
		            min: 0,
		            font: {
		                lineHeight: 18,
		                style: "normal",
		                variant: "small-caps",
		                color: "#6F7B8A"
		            }
		        },
		        yaxis: {
		            ticks: 5,
		            tickDecimals: 0,
		            min: 0,
		            tickColor: "#eee",
		            font: {
		                lineHeight: 14,
		                style: "normal",
		                variant: "small-caps",
		                color: "#6F7B8A"
		            }
		        },
		        grid: {
		            hoverable: true,
		            clickable: true,
		            tickColor: "#eee",
		            borderColor: "#eee",
		            borderWidth: 1
		        }
		    };
	$.plot("#visit-statistics", newData, options);
	$('#order-statistics-loading').hide();
	$('#order-statistics-content').show();
}


function showChartTooltip(x, y, xValue, yValue)
{
    $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 40,
        left: x - 40,
        border: '0px solid #ccc',
        padding: '2px 6px',
        'background-color': '#fff'
    }).appendTo("body").fadeIn(200);
}
function syncData()
{
	var synchronize = localStorage.getItem("synchronize");
	var callBack = function(repeat){
    	toastr.options.timeOut = 60000;
    	toastr.clear();
    	if( repeat ) {
			toastr.info("The data synchronization has been actived. <br />An alert will be shown whenever this process is done.", "Info");
    	} else {
			toastr.info("Data will be synchronized in background.<br />An alert will be shown whenever this process is done.", "Info");
    	}
	}
	if( synchronize == undefined ) {
		localStorage.setItem("synchronize", true);
		$.ajax({
			url : "{{ URL.'/admin/synchronize' }}",
			success: function(){
				callBack();
			}
		});
	} else {
		callBack(true);
	}
}
</script>
@append