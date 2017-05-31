@section('body')
<body class="page-header-fixed page-header-fixed-mobile page-quick-sidebar-over-content page-style-square pace-done page-sidebar-closed">
@stop
@section('sideMenu')
<ul id="sidebar-menu" class="page-sidebar-menu page-sidebar-menu-closed {{ isset($currentTheme['sidebar']) && $currentTheme['sidebar'] == 'fixed' ? 'page-sidebar-menu-fixed' : 'page-sidebar-menu-default' }}" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
@stop
<div class="portlet">
	{{ Form::open(array('url'=>URL.'/admin/layouts/update-layout', 'method'=>"POST", 'class'=>"form-horizontal", 'id' => 'layout-form' ) , array('files'=> true) ) }}
		<?php
			if(Session::has('_old_input')) {
				$layout = Session::get('_old_input');
			}
		?>
		<div class="portlet-title">
			<div class="actions btn-set text-right">
				<a class="btn default"  href="{{ URL.'/admin/layouts' }}"><i class="fa fa-angle-left"></i> Back</a>
				<button class="btn default" type="reset"><i class="fa fa-reply"></i> Reset</button>
				<button class="btn green" type="submit"><i class="fa fa-check"></i> Save</button>
				<button class="btn green" type="submit" name="continue" value="continue"><i class="fa fa-check-circle"></i> Save &amp; Continue Edit</button>
				@if( isset($layout['id']) )
				<div class="btn-group">
					<a class="btn yellow dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-share"></i> More <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu pull-right">
						<li>
							<a href="javascript:void(0)">
							Duplicate </a>
						</li>
						<li>
							<a href="javascript:void(0)" onclick="deleteRecord({ 'deleteUrl' : '{{ URL.'/admin/layouts/delete-product-layout/'.$layout['id'] }}', returnUrl : '{{ URL.'/admin/layouts' }}' })">
							Delete </a>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="javascript:void(0)">
							Print </a>
						</li>
					</ul>
				</div>
				@endif
			</div>
		</div>
		<div class="portlet-body form">
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#tab_info" data-toggle="tab">
						Main infomation </a>
					</li>
				</ul>
				<div class="tab-content no-space">
					<div class="tab-pane active" id="tab_info">
						<div class="form-body">
							@if( isset($layout['id']) )
							<input type="hidden" name="id" value="{{ $layout['id'] }}" />
							@endif
							<input type="hidden" id="svg" name="svg" value="" />
							<input type="hidden" id="svg_content" name="svg_content" value="" />
							<input type="hidden" id="dpi" name="dpi" value="" />
							<div class="form-group">
								<label class="col-md-2 control-label">Name<span class="required">
								* </span>
								</label>
								<div class="col-md-10">
									<input type="text" class="form-control" name="name" value="{{ $layout['name'] or '' }}" placeholder="" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Wall
								</label>
								<div class="col-md-10">
									<div class="row">
										<div class="col-md-6">
											<label class="col-md-2 control-label">Width
											</label>
											<div class="col-md-4">
												<input type="text" class="form-control number text-right spin_number" id="wall_size_w" name="wall_size_w" value="{{ $layout['wall_size_w'] or 10 }}" placeholder="" />
											</div>
											<label class="col-md-2 control-label"> inch
											</label>
										</div>
										<div class="col-md-6">
											<label class="col-md-2 control-label">Height
											</label>
											<div class="col-md-4">
												<input type="text" class="form-control number text-right spin_number" id="wall_size_h" name="wall_size_h" value="{{ $layout['wall_size_h'] or 10 }}" placeholder="" />
											</div>
											<label class="col-md-2 control-label"> inch
											</label>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<label class="col-md-2 control-label">Active
											</label>
											<div class="col-md-4" style="margin-top: 10px;">
												<input type="checkbox" class="form-control" name="active" @if( !isset($layout['active']) || $layout['active'] ) checked @endif value="1" placeholder="" />
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">&nbsp;</label>
								 <div class="col-md-10">
									<div class="row col-md-10">
									   <div class="form-group text-right">
									   		<div class="btn-group col-md-2" style="margin-right:10px;">
										   		<button class="btn default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Quick draw<i style="margin-left: 20px;" class="fa fa-angle-down"></i></button>
										   		<ul class="dropdown-menu" role="menu">
										   			@foreach([
										   					'parallelogram' => [
										   										'text' 	=> 'Parallelogram',
										   										'svg' 	=> '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-15 -15 330 330"><path d="m1.00038,249.33351l59.60001,-198.66668l238.40001,0l-59.60001,198.66668z"></path></svg></svg>'
										   									],
										   					'equilateral-triangle' => [
										   										'text' 	=> 'Equilateral triangle',
										   										'svg' 	=> '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-15 -15 330 330"><path  d="m1,280.375l149,-260.75l149,260.75z"></path></svg></svg>'
										   									],
										   					'right-triangle' => [
										   										'text' 	=> 'Right triangle',
										   										'svg'	=> '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-300 -15 330 330"><path  d="m1,299l0,-298l298,298z" transform="scale(-1, 1)"></path></svg></svg>'
										   									],
										   					'rectangle' => [
										   									'text' 	=> 'Rectangle',
										   									'svg'	=> '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-15 -15 330 330"><path d="m0,0l300,0l0,300l-300,0zm35,-265l0,230l230,0l0,-230z"></path></svg></svg>'
										   								],
										   					'rhombus' => [
										   								'text' 	=> 'Rhombus',
										   								'svg'	=> '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-15 -15 330 330"><path d="m0.99837,149.99953l148.79352,-102.86476l148.79387,102.86476l-148.79387,102.86476l-148.79352,-102.86476z"></path></svg></svg>'
										   							],
										   					'hexagon' => [
										   								'text' 	=> 'Hexagon',
										   								'svg'	=> '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><svg viewBox="-15 -15 330 330"><path d="m1,149.99944l63.85715,-127.71428l170.28572,0l63.85713,127.71428l-63.85713,127.71428l-170.28572,0l-63.85715,-127.71428z"></path></svg></svg>'
										   							]
										   					]
										   						as $shapeKey => $shape)
										   			<li><a href="javascript:void(0)" onclick="shapeLayout.addShape('{{ $shapeKey }}')">{{ $shape['svg'].' <span>'.$shape['text'].'</span>' }}</a></li>
										   			@endforeach
										   		</ul>
									   		</div>
											<button id="add-object" class="btn btn-primary col-md-1" type="button" style="margin-right:10px;" data-toggle="tooltip" title="Draw new shape"><i class="fa fa-pencil"></i></button>
											<button id="update-object" class="btn green-meadow col-md-1" type="button" style="margin-right:10px;" data-toggle="tooltip" disabled  title="Update current shape"><i class="fa fa-edit"></i></button>
											<button id="delete-object" class="btn red-sunglo col-md-1" type="button" style="margin-right:10px;" data-toggle="tooltip" disabled  title="Delete current shape"><i class="fa fa-times"></i></button>
											<button id="delete-all-object" class="btn red col-md-1" type="button" style="margin-right:10px;" data-toggle="tooltip" title="Delete All shapes"><i class="fa fa-times-circle"></i></button>
											<button id="clone-object" class="btn btn-primary col-md-1" type="button" style="margin-right:10px;" data-toggle="tooltip" disabled title="Clone shape"><i class="fa fa-life-bouy"></i></button>
											<button id="merge-object" class="btn btn-primary col-md-1" type="button" style="margin-right:10px;" data-toggle="tooltip" disabled title="Merge shapes"><i class="fa fa-chain-broken"></i></button>
											<button id="import-svg" class="btn yellow col-md-1" type="button" style="margin-right:10px;" data-toggle="tooltip" title="Import SVG (Path, Polygon, Rect)"><i class="fa fa-hdd-o"></i></button>
											<input id="import-file" type="file" accept="image/svg+xml" style="display: none;" />
										</div>
									</div>
									<div class="row col-md-10">
										<div class="panel panel-default" style="border: none;">
											<div class="panel-heading" style="border: none; width: 10%;">
												<a class="accordion-toggle collapsed" data-toggle="collapse" href="#helpers" aria-expanded="false">
													<span class="panel-title">
														Shortcut
													</span>
												</a>
											</div>
											<div id="helpers" class="panel-collapse collapse" aria-expanded="false">
												<div class="panel-body">
													<div class="col-md-4">
														<span class="help-block">Press <kbd>Enter</kbd> to complete drawing.</span>
														<span class="help-block">Press <kbd>Esc</kbd> to cancel drawing.</span>
														<span class="help-block">Hold <kbd>ctrl</kbd> when drawing to align at grid.</span>
													</div>
													<div class="col-md-4">
														<span class="help-block">Use <kbd>⇦</kbd> <kbd>⇧</kbd> <kbd>⇨</kbd> <kbd>⇩</kbd> to move current shape.</span>
														<span class="help-block">Use <kbd>delete</kbd> to delete current shape.</span>
														<span class="help-block">Use <kbd>w</kbd> or <kbd>alt</kbd>+<kbd>w</kbd> to change shape's width.</span>
														<span class="help-block">Use <kbd>h</kbd> or <kbd>alt</kbd>+<kbd>h</kbd> to change shape's height.</span>
													</div>
													<div class="col-md-4">
														<span class="help-block">Use <kbd>shift</kbd>+click or <kbd>ctrl</kbd>+click to select multi-shapes.</span>
														<span class="help-block">Double click on shape to free resize.</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">
									<div class="row" >
										<label class="col-md-2 control-label" data-toogle="tooltip" title="Width">W
										</label>
										<div class="col-md-10">
											<input type="text" id="w" class="form-control number text-right spin_number" value="0" placeholder="" />
										</div>
									</div>
									<div class="row" style="margin-top: 5px;" data-toogle="tooltip" title="Height" >
										<label class="col-md-2 control-label">H
										</label>
										<div class="col-md-10">
											<input type="text" id="h" class="form-control number text-right spin_number" value="0" placeholder="" />
										</div>
									</div>
									<div class="row" style="margin-top: 5px;" data-toogle="tooltip" title="Left">
										<label class="col-md-2 control-label">L
										</label>
										<div class="col-md-10">
											<input type="text" id="x" class="form-control number text-right " value="0" placeholder="" />
										</div>
									</div>
									<div class="row" style="margin-top: 5px;" data-toogle="tooltip" title="Top">
										<label class="col-md-2 control-label">T
										</label>
										<div class="col-md-10">
											<input type="text" id="y" class="form-control number text-right " value="0" placeholder="" />
										</div>
									</div>
									<div class="row" style="margin-top: 5px;">
										<label class="col-md-2 control-label">&nbsp;
										</label>
										<div class="col-md-10 text-left">
											<input type="checkbox" id="empty" class="form-control" data-toogle="tooltip" title="Empty" />
										</div>
									</div>
								</label>
								<div class="col-md-10">
									<div class="row">
										<div class="col-md-12">
											<div id="layout-builder" class="form-control height-auto" style="min-height: 50px;border: 1px solid; float: left; background: url(/assets/images/grid.png);">
											</div>
											<div id="temporary-svg" style="display: none"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	{{ Form::close() }}
</div>
@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' ) }}">
<link rel="stylesheet" type="text/css" href="{{URL::asset( 'assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.css' ) }}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/svgjs/svg.select/svg.select.css' ) }}"/>
<style type="text/css" media="screen">
svg *{
	cursor:pointer;
}
svg .focus {
	fill: #33bbee !important;
}
</style>
@stop
@section('pageJS')
<script src="{{ URL::asset( 'assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js ') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/jquery-validation/js/jquery.validate.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.draw/svg.draw.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.select/svg.select.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.resize/svg.resize.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.draggable/svg.draggable.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.import/svg.import.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.parser/svg.parser.js' ) }}"></script>
<script type="text/javascript">
var shapeLayout = function() {
	var config = {
		empty		: '#ffffff',
		color   	: '#88eeff',
		dpi         : {{ $data['dpi'] }},
		view_dpi    : {{ $data['view_dpi'] }},
		box_size_w  : {{ $layout['box_size_w_pt'] or $data['max_w'] }},
		box_size_h  : {{ $layout['box_size_h_pt'] or $data['max_h'] }},
		move_step   : 50,
		max_w       : {{ $data['max_w'] }},
		max_h       : {{ $data['max_h'] }},
	};
	var draw, group;
	var layoutForm = $("#layout-form");
	var layout = $("#layout-builder");
	var addButton = $("#add-object");
	var updateButton = $("#update-object");
	var deleteButton = $("#delete-object");
	var cloneButton = $("#clone-object");
	var mergeButton = $("#merge-object");
	var focusObject;
	var focusObjects = {};
	var shapes = {};

	function addDefinedShape(shapeType)
	{
		var d;
		var width = config.box_size_w / 2;
		var height = config.box_size_h / 2;
		var x = width /2;
		var y = height/2;
		switch( shapeType ) {
			case 'equilateral-triangle':
				var x1 = x;
				var y1 = y + height;
				var x2 = x + width;
				var y2 = y1;
				var x3 = x + width/2;
				var y3 = y;
				d = 'M'+ x1 +' '+ y1 +'L'+ x2+' '+ y2 +'L'+ x3 +' '+ y3 +'Z';
				break;
			case 'right-triangle':
				var x1 = x;
				var y1 = y + height;
				var x2 = x + width;
				var y2 = y1;
				var x3 = x2;
				var y3 = y;
				d = 'M'+ x1 +' '+ y1 +'L'+ x2+' '+ y2 +'L'+ x3 +' '+ y3 +'Z';
				break;
			case 'rhombus':
				var x1 = x;
				var y1 = height;
				var x2 = width;
				var y2 = y1 + y;
				var x3 = x1 + width;
				var y3 = y1;
				var x4 = x2;
				var y4 = y;
				d = 'M'+ x1 +' '+ y1 +'L'+ x2 +' '+ y2 +'L'+ x3 +' '+ y3 +'L'+ x4 +' '+ y4 +'Z';
				break;
			case 'hexagon':
				var x1 = x;
				var y1 = height;
				var x2 = x1 + width / 4;
				var y2 = y1 + y;
				var x3 = width + width/4;
				var y3 = y2;
				var x4 = x1 + width;
				var y4 = y1;
				var x5 = x3;
				var y5 = y;
				var x6 = x2;
				var y6 = y5;
				d = 'M'+ x1 +' '+ y1 +'L'+ x2 +' '+ y2 +'L'+ x3 +' '+ y3 +'L'+ x4 +' '+ y4 +'L'+ x5 +' '+ y5 +'L'+ x6 +' '+ y6 +'Z';
				break;
			case 'parallelogram':
				height /= 2;
				y = height*1.5;
				var x1 = x + width/3;
				var y1 = y;
				var x2 = x + width;
				var y2 = y1;
				var x3 = x1 + width/3;
				var y3 = y + height;
				var x4 = x;
				var y4 = y3;
				d = 'M'+ x1 +' '+ y1 +'L'+ x2 +' '+ y2 +'L'+ x3 +' '+ y3 +'L'+ x4 +' '+ y4 +'Z';
				break;

			case 'rectangle':
			default:
				var x1 = x;
				var y1 = y + height;
				var x2 = x + width;
				var y2 = y1;
				var x3 = x2;
				var y3 = y;
				var x4 = x1;
				var y4 = y3;
				d = 'M'+ x1 +' '+ y1 +'L'+ x2 +' '+ y2 +'L'+ x3 +' '+ y3 +'L'+ x4 +' '+ y4 +'Z';
				break;
		}
		addShape(d);
	}

	function addShape(d, id, w, h, x, y, empty)
	{
		var path = draw.path(d).attr({
									'fill': config.color,
									'stroke'        : '#000000',
									'stroke-width'  : 1,
								});
		if( id ) {
			path.attr('id', id);
		}
		shapeBind(
			path,
			{ width: w, height: h, x: x, y: y, empty: empty }
		);
	}

	function shapeBind(shape, attribute)
	{
		shape.draggable(false)
				.draggable()
				.on('dragend', function() {
					// unsetMultiSelect();
					updateShapeInput(this);
				})
				.mousedown(function(event){
					if( event.shiftKey || event.ctrlKey ) {
						resetInput();
						focusObjects[ this.id() ] = this;
						if( Object.keys(focusObjects).length >= 2 ) {
							setDisabled(mergeButton, false);
						}
						this.attr({ 'class' : 'focus' });
						setDisabled([updateButton, deleteButton, cloneButton]);
					} else {
						updateShapeInput(this);
						if ( focusObject != undefined ) {
							focusObject.attr({ 'class' : null });
						}
						unsetMultiSelect();
						this.attr({ 'class' : 'focus' });
						focusObject = this;
						setDisabled([updateButton, deleteButton, cloneButton], false);
					}
				})
				.dblclick(function(){
					var select = $('[data-selected=on]', layout);
					console.log(select.length)
					if( select.length ) {
						select.each(function() {
							this.instance.fire('deactive');
						});
					}
					this.attr('data-selected', 'on');
					this.select({deepSelect:true, rotationPoint: false})
						.resize();
				})
				.on('resizedone', function(){
					this.fire('mousedown');
				})
				.on('deactive', function(){
					this.select(false, {deepSelect:true, rotationPoint: false})
						.resize('stop');

					this.attr('data-selected', null);
				})
				.on('empty', function(e){
					if( e.detail.empty ) {
						this.fill(config.empty);
					} else {
						this.fill(config.color);
					}
				})
				.attr('class', null)
				.fire('mousedown');
		updateShapeInput(shape, attribute);
	}

	function updateShapeInput(shape, attribute)
	{
		if( attribute == undefined ) {
			attribute = {};
		}
		var x = shape.x();
		var y = shape.y();
		var w = shape.width();
		var h = shape.height();

		x = x*config.view_dpi/config.dpi;
		y = y*config.view_dpi/config.dpi;

		w = w * config.view_dpi/config.dpi;
		h = h * config.view_dpi/config.dpi;

		$("#x").val(x.toFixed(2));
		$("#y").val(y.toFixed(2));
		$("#w").val(w.toFixed(2));
		$("#h").val(h.toFixed(2));
		var id = shape.id();
		if( shapes[id] == undefined ) {
			shapes[id] = {};
		}
		shapes[id].id = id;
		shapes[id].x = $("#x").val();
		shapes[id].y = $("#y").val();
		shapes[id].width = $("#w").val();
		shapes[id].height = $("#h").val();
		if( attribute.empty != undefined ) {
			shapes[id].empty = attribute.empty;
		}
		if( shapes[id].empty == 1) {
			$("#empty").prop('checked', true);
			$("#empty").parent().addClass('checked');
		} else {
			$("#empty").prop('checked', false);
			$("#empty").parent().removeClass('checked');
		}
		shape.fire('empty', { empty: shapes[ id ].empty  });
		if( shape.type == 'path' ) {
			shapes[id].d = shape.array().toString();
		} else {
			shapes[id].d = 'M'+ shape.array().toString() + 'Z';
		}
		updateInputSVG();
	}


	function layoutBuilder()
	{
		var dpi = config.dpi / config.view_dpi;
		var reverse_dpi = 1 / dpi;
		$("#dpi").val(dpi);

		if( $("svg#layout-builder-svg").length ) {
			$("svg#layout-builder-svg").remove();
		}
		layout.css({ width: config.box_size_w +"px", height: config.box_size_h +"px", padding: 0 });
		draw = SVG("layout-builder").attr({
			"width" : config.box_size_w,
			"height": config.box_size_h,
			"id"    : "layout-builder-svg"
		});
		drawBleed();
		group   = draw.group();

		@if( isset($layout['shapes']) && !empty($layout['shapes']) )
		var x, y, w, h, id, d, empty;
			@foreach($layout['shapes'] as $shape)
				id = {{ $shape['id'] }};
				w = {{ $shape['width']  }};
				h = {{ $shape['height'] }};
				x = {{ $shape['coor_x'] }};
				y = {{ $shape['coor_y'] }};
				d = '{{ $shape['d'] or '' }}';
				empty = {{ $shape['empty'] or 0 }};
				addShape(d, id, w, h, x, y, empty);
			@endforeach
		@endif
	}

	function updatePanel(build)
	{
		if( build == undefined ) {
			build = true;
		}
		var wall_size_w = $("#wall_size_w").val()* config.dpi ;
		var wall_size_h = $("#wall_size_h").val()* config.dpi ;

		if( parseFloat(wall_size_w/config.max_w) > parseFloat(wall_size_h/config.max_h) ) {
		   config.box_size_w    = config.max_w;
		   config.view_dpi      = parseFloat(wall_size_w/ config.box_size_w);
		   config.box_size_h    = parseFloat(wall_size_h  / config.view_dpi);
		} else {
		   config.box_size_h    = config.max_h;
		   config.view_dpi      = parseFloat(wall_size_h  / config.box_size_h);
		   config.box_size_w    = parseFloat(wall_size_w  / config.view_dpi);
		}
		if( build ) {
			layoutBuilder();
		}
	}

	function eventBind()
	{
		layout.click(function(e){
			var tag = e.target.innerHTML;
			if ( tag.indexOf("path") >= 0) {
				if (focusObject!=undefined ) {
					focusObject.fire('deactive')
								.attr({ 'class' : null });
				}
				resetInput();

				setDisabled(addButton, false);
				setDisabled([updateButton, deleteButton, cloneButton]);
			}
			if( !e.shiftKey && !e.ctrlKey ) {
				unsetMultiSelect();
			}
		});

		addButton.click(function(e){
			if( temporaryShapes = $('.temporary-shape', layout), temporaryShapes.length ) {
				temporaryShapes.each(function(){
					this.instance.draw('cancel');
				});
			}
			draw.off('mousedown')
        		.off('mouseup')
        		.off('mouseover');
			resetInput();
			var polygon = draw.polygon()
								.attr({
									'fill': config.color,
									'stroke'        : '#000000',
									'stroke-width'  : 1,
									'class'			: 'temporary-shape'
								}).on('drawstart', function(e){
									var _polygon = this;
						            $(document).keydown(function(e){
						            	switch( e.keyCode ) {
						            		case 13:
							            		shapeBind(_polygon);
							            		_polygon.fire('drawstop')
							            				.off('drawstart')
							            				.off('drawstop')
							            				.draw('done');
												layout.css('cursor', 'default');
							                    break;
							                case 17:
						                    	_polygon.draw('param', 'snapToGrid', 20);
						                    	break;
						                    case 27:
						                    		_polygon.fire('drawstop')
					                    					.draw('cancel');
						                    	break;
						            	}
						            }).keyup(function(e){
						            	_polygon.draw('param', 'snapToGrid', 1);
						            });
						        }).on('drawstop', function(){
						        	$(document).unbind('keydown');
						        	$(document).unbind('keyup');
						            draw.off('mousedown')
						            		.off('mouseup')
						            		.off('mouseover');
									layout.css('cursor', 'default');
						        });
			draw.on('mouseover', function(){
				layout.css('cursor', 'crosshair');
			}).on('mousedown', function(){
				layout.css('cursor', 'crosshair');
			    polygon.draw();
			}).on('mouseup', function(){
				layout.css('cursor', 'crosshair');
			    polygon.draw();
			});
		});

		updateButton.click(function(e){
			e.preventDefault();
			var dpi = config.dpi / config.view_dpi;
			var reverse_dpi = 1 / dpi;
			var x, y, w, h;
			w = $("#w").val();
			h = $("#h").val();
			x = $("#x").val();
			y = $("#y").val();

			w = w * dpi;
			h = h * dpi;

			x = x * dpi;
			y = y * dpi;

			if( w > config.box_size_w ){
				toastr.warning("Object is oversize, it will be auto resize.", "Warning");
				w = config.box_size_w;
				$("#w").val( (w * reverse_dpi).toFixed(2) ).focus();
			}
			if( h > config.box_size_h){
				toastr.warning("Object is oversize, it will be auto resize.", "Warning");
				h = config.box_size_w;
				$("#h").val( (h * reverse_dpi).toFixed(2)  ).focus();
			}

			var x = $("#x").val() * dpi;
			var y = $("#y").val() * dpi;

			if(x < 0 ) {
				x = 0;
			} else if( x + w >config.box_size_w ) {
				x = config.box_size_w - w;
			}

			if( y < 0 ) {
				y = 0;
			} else if( y + h > config.box_size_h ) {
				y = config.box_size_h - h;
			}

			$("#x").val( (x*reverse_dpi).toFixed(2) );
			$("#y").val( (y*reverse_dpi).toFixed(2) );

			focusObject.move(x, y);
			focusObject.size(w, h);
			var id = focusObject.id();
			var empty;
			if( $('#empty').is(':checked') ) {
				empty = 1;
			} else {
				empty = 0;
			}
			updateShapeInput(focusObject, {'empty' : empty});
			updateInputSVG();
			focusObject.fire("mousedown");
		});

		cloneButton.click(function(){
			var shape = focusObject.clone().dx(50);
			resetInput();
			shapeBind(shape);
			shape.fire('mousedown');
		});

		mergeButton.click(function(){
			if(Object.keys(focusObjects).length >= 2) {
				var newPath = '';
				for(var i in focusObjects) {
					var object = focusObjects[i];
					var objectId = object.id();
					newPath += shapes[objectId].d;
					if( String(objectId).indexOf('Svgjs') == -1 ) {
						shapes[objectId]["deleted"] = 1;
					} else {
						delete shapes[objectId];
					}
					if( object.type == 'path' ) {
						newPath += object.array().toString();
					} else {
						newPath += 'M'+ object.array().toString() + 'Z';
					}
					object.remove();
				}
				focusObjects = {};
				addShape(newPath);
			}
		});

		deleteButton.click(function(){
			var id = focusObject.id();
			if( String(id).indexOf("Svgjs") == -1 ) {
				shapes[id]["deleted"] = 1;
				updateInputSVG();
			} else {
				delete shapes[id];
			}
			focusObject.remove();
			focusObject = undefined;
			layout.trigger("click");
			event.preventDefault();
		});

		$("#delete-all-object").click(function(e){
			layout.trigger("click");
			bootbox.confirm("Are you sure you want to delete all objects?", function(result){
				if( result ) {
					for(var id in shapes) {
						if( String(id).indexOf("Svgjs") == -1 ) {
							shapes[id]["deleted"] = 1;
							updateInputSVG();
						} else {
							delete shapes[id];
						}
						$("svg#layout-builder-svg #"+ id, layout).remove();
						focusObject = undefined;
					}
					updateInputSVG();
					toastr.success("All objects were deleted successful.", "Message");
				}
			});
			event.preventDefault();
		});

		$('#import-svg').click(function(){
			$('#import-file').trigger('click');
		});

		$('#import-file').change(function(){
			var file = this.files[0];
			var reader = new FileReader();
			reader.readAsText(file);
			reader.onload = function(e) {
				var svg = e.target.result;
				$('#temporary-svg').html('');
				var temporary = SVG('temporary-svg');
				var read = false;
				try {
					temporary.svg(svg);
					read = true;
				} catch(err) {
					toastr.error('File cannot read. Please upload a valid SVG file.', 'Error');
				}
				if( read ) {
					if( $('#temporary-svg def').length ) {
						$('#temporary-svg def').remove();
					}
					var totalImport = 0;
					if( rects = $('#temporary-svg rect'), rects.length ) {
						rects.each(function() {
							var d = '';
							var rect = this.instance;
							var x = rect.x();
							var y = rect.y();
							var w = rect.width();
							var h = rect.height();
							var d = 'M '+ x +' '+ (y+h) +'L'+ (x+w) +' '+ (y+h) +'L'+ (x+w) +' '+ y +'L'+ x +' '+ y +'Z';
							rect.remove();
							addShape(d);
							totalImport++;
						});
					}
					if( polygons = $('#temporary-svg polygon'), polygons.length ) {
						polygons.each(function() {
							var polygon = this.instance;
							var d = 'M'+ polygon.array().toString() +'Z';
							polygon.remove();
							addShape(d);
							totalImport++;
						});
					}
					if( paths = $('#temporary-svg paths'), paths.length ) {
						paths.each(function() {
							var path = this.instance;
							var d = path.array().toString();
							path.remove();
							addShape(d);
							totalImport++;
						});
					}
					toastr.success(totalImport+ ' shape(s) imported.', 'Message');
				}
			};
		});

		$("#w, #h, #x, #y, #empty").change(function(){
			if( focusObject != undefined && updateButton.attr("disabled") == undefined  ) {
				updateButton.trigger("click");
			}
		});

		$("#wall_size_w, #wall_size_h").change(function(){
			updatePanel(false);
			layout.css({ width: config.box_size_w +"px", height: config.box_size_h +"px", padding: 0 });
			SVG.get("layout-builder-svg").attr({
				"width" : config.box_size_w,
				"height": config.box_size_h,
			});
			drawBleed();
		});
	}

	function unsetMultiSelect()
	{
		for(var i in focusObjects) {
			focusObjects[i].fill({ color : config.color });
		}
		focusObjects = {};
		setDisabled(mergeButton);
	}

	function resetInput()
	{
		if( focusObject != undefined ) {
			focusObject.attr({ 'class' : null })
		}
		focusObject = undefined;
		$('#w, #h, #x, #y').val(0);
		$('#empty').prop('checked', false);
		$('#empty').parent().removeClass('checked');
	}

	function setDisabled(buttons, set)
	{
		var set = (set == false ? false : true);
		if( buttons.length > 1 ) {
			for(var i in buttons) {
				$(buttons[i]).prop('disabled', set);
			}
		} else {
			$(buttons).prop('disabled', set);
		}
	}

	function updateInputSVG()
	{
		$("input#svg").val(JSON.stringify(shapes));
	}

	function htmlEntities(str)
	{
		return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}

	function formValidate()
	{
		layoutForm.validate({
			errorElement: 'span',
			errorClass: 'help-block help-block-error',
			focusInvalid: false,
			ignore: "",
			messages: {
				select_multi: {
					maxlength: $.validator.format("Max {0} items allowed for selection"),
					minlength: $.validator.format("At least {0} items must be selected")
				}
			},
			rules: {
				name: {
					required: true,
					minlength: 3
				},
				wall_size_w: {
					min: 5
				},
				wall_size_h: {
					min: 5
				},
			},
			invalidHandler: function (event, validator) {
				$(".alert-danger",layoutForm).show();
				Metronic.scrollTo($(".alert-danger",layoutForm), -200);
			},
			highlight: function (element) {
				$(element)
					.closest('.form-group').addClass('has-error');
			},
			unhighlight: function (element) {
				$(element)
					.closest('.form-group').removeClass('has-error');
			},
			success: function (label) {
				label
					.closest('.form-group').removeClass('has-error');
			},
			submitHandler: function (form) {
				$(".alert-danger",layoutForm).hide();
				layout.trigger("click");
				$("#svg_content").val(htmlEntities(layout.html()));
				this.submit();
			}
		});
	}

	function inputBind()
	{
		$("#wall_size_w, #wall_size_h, #x, #x, #y, #w, #h").keypress(function(event){
			if(event.which < 46  || event.which > 59) {
				event.preventDefault();
			}
			if(event.which == 46
				&& $(this).val().indexOf('.') != -1) {
				 event.preventDefault();
			}
		});

		$(".spin_number").TouchSpin({
			buttonup_class: 'btn blue',
			min: 0,
			max: 200,
			step: 0.1,
			decimals: 2,
			forcestepdivisibility: 'none'
		});

		$("#x, #y").TouchSpin({
			buttonup_class: 'btn blue',
			max: 1000,
			step: 0.1,
			decimals: 2,
			forcestepdivisibility: 'none'
		});

		$("[data-toggle=tooltip]", layoutForm).tooltip({
			container: 'body'
		});

		$('input[name="name"]').click(function(){
			layout.trigger('click');
		}).focus(function(){
			layout.trigger('click');
		});

		document.onkeydown = function(e) {
			if( focusObject != undefined
					&& focusObject != null) {
				if( $.inArray(e.keyCode, [37, 38, 39, 40, 46]) != -1 ) {
					switch( e.keyCode ) {
						case 37: //LEFT
							$('#x').trigger('touchspin.downonce').trigger('change');
							break;
						case 38: //UP
							$('#y').trigger('touchspin.downonce').trigger('change');
							break;
						case 39: //RIGHT
							$('#x').trigger('touchspin.uponce').trigger('change');
							break;
						case 40: //DOWN
							$('#y').trigger('touchspin.uponce').trigger('change');
							break;
						case 46: //DELETE
							deleteButton.trigger('click');
							break;
					}
					e.preventDefault();
				} else if( $.inArray(e.keyCode, [87, 72]) != -1 ) {
					if( e.altKey && e.keyCode == 87 ) {
						$('#w').trigger('touchspin.downonce').trigger('change');
					} else if( e.keyCode == 87 ) {
						$('#w').trigger('touchspin.uponce').trigger('change');
					} if( e.altKey && e.keyCode == 72 ) {
						$('#h').trigger('touchspin.downonce').trigger('change');
					} else if( e.keyCode == 72 ) {
						$('#h').trigger('touchspin.uponce').trigger('change');
					}
					e.preventDefault();
				}
			}
		};
	}

	function drawBleed()
	{
		var bleed = 0;
		var w = (Number($('#wall_size_w').val()) + 2) * config.dpi;
		var h = (Number($('#wall_size_h').val()) + 2) * config.dpi;
		if(w/config.max_w > h/config.max_h) {
			bleed = config.max_w/w * config.dpi;
		} else {
			bleed = config.max_h/h * config.dpi;
		}
		var attribute = {
			id: 'main-bleed',
			stroke: 'red',
			fill: 'none'
		};
		if( $('#main-bleed').length ) {
			SVG.get('main-bleed').remove();
		}
		var path = 'M'+ bleed +' '+ bleed
			 		+'L'+ (config.box_size_w - bleed) +' '+ bleed
			 		+'L'+ (config.box_size_w - bleed) +' '+(config.box_size_h - bleed)
			 		+'L'+ bleed +' '+(config.box_size_h - bleed)
			 		+'Z'
		draw.path(path).attr(attribute);
	}

	return {
		init: function() {
			layoutBuilder();
			inputBind();
			eventBind();
			formValidate();
			@if( !isset($layout['id']) )
			updatePanel();
			@endif
		},
		addShape: function(shapeType) {
			addDefinedShape(shapeType);
		},
		getShape: function() {
			return shapes;
		}
	};
}();
shapeLayout.init();
</script>
@stop
