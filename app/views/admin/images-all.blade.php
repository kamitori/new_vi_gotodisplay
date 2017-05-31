@section('body')
<body class="page-header-fixed page-header-fixed-mobile page-quick-sidebar-over-content page-style-square pace-done page-sidebar-closed">
@stop
@section('sideMenu')
<ul id="sidebar-menu" class="page-sidebar-menu page-sidebar-menu-closed {{ isset($currentTheme['sidebar']) && $currentTheme['sidebar'] == 'fixed' ? 'page-sidebar-menu-fixed' : 'page-sidebar-menu-default' }}" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
@stop
@section('pageAction')
<div class="btn-group pull-right">
	<button id="add-image" type="button" class="btn btn-fit-height blue" >
	{{ 'Add Image' }}
	</button>
</div>
@stop
<div id="image-div" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Image</h4>
            </div>
            <div class="modal-body form">
                <form id="image-form" action="javascript:void(0)" method="POST" class="form-horizontal form-row-seperated">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <div id="content-message">
                            You have some form errors. Please check below.
                        </div>
                    </div>
                    {{ Form::token(); }}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Image</label>
						<div class="col-md-8">
                            <input name="id" type="hidden" value="0" />
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img id="preview-img" data-origin-src="{{ URL::asset( 'assets/images/noimage/247x185.gif' ) }}" src="{{ URL::asset( 'assets/images/noimage/247x185.gif' ) }}"/>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px; height: 150px;">
                                </div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">
                                        	Select Image
                                    	</span>
                                        <span class="fileinput-exists">
                                        	Change
                                    	</span>
                                        <input name="image" id="file" accept="image/*" type="file">
                                    </span>
                                    <a href="javascript:void(0)" class="btn red fileinput-exists" data-dismiss="fileinput">
                                    Remove </a>
                                </div>
                            </div>
						</div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Store at</label>
                        <div class="col-sm-8">
                            <select name="store" class="form-control input-medium">
                                @foreach($arrStores as $key => $store)
                                <option value="{{ $key }}">{{ $store }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tags</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                <i class="fa fa-key"></i>
                                </span>
                                <input type="text" name="tags" value="" class="form-control tags"/>
                            </div>
                            <span class="help-block">Each tag's length must be greater than or equals to 4 characters.</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="image-delete" class="btn btn-danger"><i class="fa fa-times"></i>Delete</button>
                <button type="button" id="image-submit" class="btn btn-primary"><i class="fa fa-check"></i>Save change</button>
            </div>
        </div>
    </div>
</div>
<div id="image-add-div" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Image</h4>
            </div>
            <div class="modal-body" style="min-height: 350px;">
                <div id="actions" class="row">
                    <div class="col-lg-12">
                        <div class="col-md-2">
                            <span class="btn btn-success fileinput-button dz-clickable">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>Add files...</span>
                            </span>
                        </div>
                        <div class="col-md-10">
                            <span>Store at </span>
                            <select id="store" class="form-control input-medium inline">
                                @foreach($arrStores as $key => $store)
                                <option value="{{ $key }}">{{ $store }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table table-striped files" id="previews">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
<select id="filter" class="form-control" multiple placeholder="image tags">
	@foreach($arrTags as $tag)
	<option value="{{ $tag }}">{{ $tag }}</option>
	@endforeach
</select>
<div class="pull-right">
    <div id="pagination-nav" class="inline"></div>
</div>
<div style="margin-top: 53px;">
    <ul class="mix-filter">
        <li class="filter active" data-filter="all">
             All
        </li>
        @foreach($arrStores as $key => $store)
        <li class="filter" data-filter="{{ $key }}">
            {{ $store }}
        </li>
        @endforeach
    </ul>
	<div class="row mix-grid">

	</div>
</div>
@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/fancybox/source/jquery.fancybox.css' ) }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/admin/pages/css/portfolio.css' ) }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/select2/select2.css' ) }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/jquery-tags-input/jquery.tagsinput.css' ) }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' ) }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/dropzone/dropzone.min.css' ) }}" />
<style type="text/css">
div.table {
  display: table;
}
div.table .file-row {
  display: table-row;
}
div.table .file-row > div {
  display: table-cell;
  vertical-align: top;
  border-top: 1px solid #ddd;
  padding: 8px;
}
div.table .file-row:nth-child(odd) {
  background: #f9f9f9;
}



/* The total progress gets shown by event listeners */
#total-progress {
  opacity: 0;
  transition: opacity 0.3s linear;
}

/* Hide the progress bar when finished */
#previews .file-row.dz-success .progress {
  opacity: 0;
  transition: opacity 0.3s linear;
}

/* Hide the delete button initially */
#previews .file-row .delete {
  display: none;
}

/* Hide the start and cancel buttons and show the delete button */

#previews .file-row.dz-success .start,
#previews .file-row.dz-success .cancel {
  display: none;
}
#previews .file-row.dz-success .delete {
  display: block;
}

#image-add-div .modal-dialog {
    margin-top: 0px;
}
#image-add-div div.tagsinput input {
    width: 355px !important;
}
</style>
@stop
@section('pageJS')
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/fancybox/source/jquery.fancybox.pack.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/select2/select2.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/dropzone/dropzone.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL }}/assets/global/plugins/jquery-mixitup/jquery.mixitup.min.js"></script>
<script type="text/javascript">
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: '{{ URL.'/admin/images/update-image' }}', // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}'
    },
    paramName: 'image',
    previewTemplate: '<div class="file-row">' +
                        '<div>' +
                            '<span class="preview"><img data-dz-thumbnail /></span>' +
                        '</div>' +
                        '<div>' +
                            '<p class="name" data-dz-name></p>' +
                            '<strong class="error text-danger" data-dz-errormessage></strong>' +
                        '</div>' +
                        '<div>' +
                            '<p class="size" data-dz-size></p>' +
                            '<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' +
                                '<div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>' +
                            '</div>' +
                        '</div>' +
                        '<div>' +
                            '<input type="text" class="form-control" name="tags" />' +
                        '</div>' +
                        '<div>' +
                            '<button data-dz-remove class="btn btn-danger delete">' +
                            '<i class="glyphicon glyphicon-trash"></i>' +
                            '<span>Delete</span>' +
                            '</button>' +
                        '</div>' +
                    '</div>',
    autoQueue: true,
    previewsContainer: "#previews",
    clickable: ".fileinput-button"
});
var updateTags = function(id, tags){
    $.ajax({
        url: '{{ URL.'/admin/images/update-image' }}',
        type: 'POST',
        data: {
            id: id,
            tags: tags
        },
        success: function(result) {
            if( result.status == 'ok' ) {
                images[ result.data.id ] = result.data;
                reloadTags(result.data.option);
            }
        }
    });
}
myDropzone.on('complete', function(file) {
    var result = $.parseJSON(file.xhr.response);
    if( result.data ) {
        images[ result.data.id ] = result.data;
        $(file.previewElement).attr('data-id', result.data.id);
        var tags = $('input[name=tags]', file.previewElement).val();
        if( tags ) {
            updateTags(result.data.id, tags);
        }
        $('.mix-grid').append(createImage(result.data))
                        .mixitup();
    }
}).on('addedfile', function(file){
    $('input[name=tags]', file.previewElement).tagsInput({
        width: 375,
        minChars: 4,
        defaultText: 'Each tag\'s length must be greater than or equals to 4 chars.',
        onChange: function(){
            var id = $(file.previewElement).closest('.file-row').data('id');
            var tags = $(this).val();
            if( tags ) {
                updateTags(id, tags);
            }

        }
    });
    $('.delete', file.previewElement).click(function(){
        var id = $(file.previewElement).closest('.file-row').data('id');
        if( id ) {
            $.ajax({
                url: '{{ URL.'/admin/images/delete-image/' }}' + id,
                success: function(result) {
                    if( result.status =='ok' ) {
                        delete images[ id ];
                        $(file.previewElement).remove();
                        $('img[data-id='+ id +']').closest('.mix').remove();
                        toastr.success(result.message, 'Message');
                    } else {
                        toastr.error(result.message, 'Error');
                    }
                }
            })
        } else {
            parent.remove();
        }
    });
}).on("sending", function(file,xhr, formData){
    formData.append('store', $('#image-add-div #store').val());
});
//====================================
var size = '450x337';
var largerSize = '600x450';
var images = {};
var tags = {{ json_encode($arrTags) }};
$("#filter").select2({
	allowClear: true
}).change(function(){
    var value = $(this).val();
    loadImages(1, value);
}).trigger('change');
$(".fancybox-button").fancybox();
$('input.tags').tagsInput({
    width: 300,
    minChars: 4
});
$('#image-delete').click(function(){
    bootbox.confirm('Are you sure you want to delete this image?', function(result) {
        if( result ) {
            var id = $('#image-div input[name=id]').val();
            if( id ) {
                $.ajax({
                    url: '{{ URL.'/admin/images/delete-image/' }}' + id,
                    success: function(result) {
                        if( result.status =='ok' ) {
                            delete images[ id ];
                            $('img[data-id='+ id +']').closest('.mix').remove();
                            $('#image-div').modal('hide');
                            toastr.success(result.message, 'Message');
                        } else {
                            toastr.error(result.message, 'Error');
                        }
                    }
                })
            }
        }
    });
});
$('#image-submit').click(function(){
    updateImage();
});
$('#add-image').click(function(){
    $('#image-add-div #previews').html('');
    $('#image-add-div').modal('show');
});
function getImage(id)
{
    var path = '{{ URL.'/thumb/' }}' + images[id].id + '/'+ size +'.jpg'+ '?'+ new Date().getTime();
    if( images[id].outter_path ) {
        path = images[id].outter_path;
    }
	$('#image-form #preview-img').attr('src', path);
    var tags = images[id].option != null ? images[id].option : '';
	$('#image-form input.tags').importTags(tags);
    $('#image-form [name=id]').val(images[id].id);
	$('#image-form [name=store]').val(images[id].store);
	$('#image-div').modal('show');
}

function updateImage()
{
	var data = new FormData();
    data.append('id', $('#image-form [name=id]').val());
	var files = $("#image-form #file")[0].files;
    data.append("image", files[0] != undefined ? files[0] : '');
    data.append('tags', $('#image-form [name=tags]').val());
    data.append('store', $('#image-form [name=store]').val());
	$.ajax({
		url: "{{ URL.'/admin/images/update-image' }}",
		type: "POST",
		data: data,
		contentType: false,
		processData: false,
		success: function(result) {
			if( result.status == 'ok' ) {
                images[ result.data.id ] = result.data;
                if( result.newImage != undefined ) {
                    $('.mix-grid').append(createImage(result.data))
                                    .mixitup();
                } else if( result.changeImage != undefined ) {
                    var img = $('img[data-id='+ result.data.id +']');
                    if( result.data.outter_path ) {
                        img.attr('src', result.data.outter_path);
                    } else {
                        img.attr('src', img.attr('src')  + '?' + new Date().getTime());
                    }
                }
				$('#image-div [data-dismiss="modal"]').trigger("click");
                $('#image-form [data-dismiss="fileinput"]').trigger('click');
				toastr.success(result.message, 'Message');
                reloadTags(result.data.option);
			} else {
				toastr.error(result.message, 'Error');
			}
		}
	});
}
function loadImages(page, tags)
{
    Metronic.blockUI({
        target: $('.mix-grid')
    });
    var page = page != undefined ? parseInt(page) : 1;
    var tags = tags != undefined ? tags : '';
    $.ajax({
        url: '{{ URL.'/admin/images/get-images' }}',
        type: 'POST',
        data: {
            page: page,
            tags: tags
        },
        success: function(result) {
            images = {};
            Metronic.unblockUI({
                target: $('.mix-grid')
            });
            var html = '';
            if( result.total ) {
                var time = '?'+ new Date().getTime();
                images = result.images;
                for( var i in result.images ) {
                    html += createImage(result.images[i], time);
                }
            }
            createPaginationNav({ currentPage: result.page, totalPage: result.total });
            $('.mix-grid').html(html)
                            .mixitup();
        }
    })
}

function createImage(data, time)
{
    if( time == undefined ) {
        time = '';
    }
    var smallPath = '';
    var largePath =  '';
    var extraClass = data.store;
    if( extraClass == '' ) {
        extraClass = 'local';
    }
    if( data.outter_path != undefined ) {
        smallPath = largePath = data.outter_path;
    } else {
        smallPath = '{{ URL }}/thumb/'+ data.id +'/'+ size +'.jpg';
        largePath = '{{ URL }}/thumb/'+ data.id +'/'+ largerSize +'.jpg';
    }
    var html = '';
    html = '<div class="col-md-1 col-sm-1 mix '+ extraClass +'">' +
                '<div class="mix-inner">' +
                    '<img data-id="'+ data.id +'" class="img-responsive" src="'+ smallPath +'" alt="">' +
                    '<div class="mix-details">' +
                        '<a class="mix-link" onclick="getImage('+ data.id +')">' +
                            '<i class="fa fa-pencil"></i>' +
                        '</a>' +
                        '<a class="mix-preview fancybox-button" href="'+ largePath +''+time+'" rel="fancybox-button">' +
                        '<i class="fa fa-search"></i>' +
                        '</a>' +
                    '</div>' +
                '</div>' +
            '</div>';
    return html;
}

function createPaginationNav(data)
{
    var totalPage = parseInt(data.totalPage);
    var currentPage = parseInt(data.currentPage);
    var html = '';
    var offset = 2;
    if( totalPage ) {
        var prev = currentPage - 1 > 0 ? currentPage - 1 : 1;
        var next = currentPage + 1 <= totalPage ? currentPage + 1 : totalPage;
        html = '<ul class="pagination pagination-sm">' +
                    '<li '+( currentPage == prev ? 'class="active"' : '' )+'>' +
                        '<a onclick="'+ ( currentPage == prev ? 'return false;' : 'loadImages('+ prev +')' )+'">' +
                            '<i class="fa fa-angle-left"></i>' +
                        '</a>' +
                    '</li>';
        var from = currentPage - offset > 0 ? currentPage - offset : 1;
        var to = currentPage + offset <= totalPage ? currentPage + offset : totalPage;
        while( to - from < offset*2 && to < totalPage ) {
            to++;
        }
        while( to - from < offset*2 && from > 1 ) {
            from--;
        }
        for( var i = from; i <= to; i++ ) {
            if( i == currentPage ) {
                html += '<li class="active">' +
                            '<a onclick="return false;">' +
                            i +
                            '</a>' +
                        '</li>';
            } else {
                html += '<li>' +
                            '<a onclick="loadImages('+ i +')">' +
                                i +
                            '</a>' +
                        '</li>';
            }
        }
        html += '<li '+( currentPage == next ? 'class="active"' : '' )+'>' +
                    '<a onclick="'+ ( currentPage == next ? 'return false;"' : 'loadImages('+ next +')' )+'">' +
                        '<i class="fa fa-angle-right"></i>' +
                    '</a>' +
                '</li>' +
            '</ul>';
    }
    $('#pagination-nav').html(html);
}

function reloadTags(data)
{
    if( data ) {
        var data = data.split(',');
        var filter = $('#filter').val();
        for(var i in data) {
            var tmp = data[i].trim();
            tags[tmp] = tmp;
        }
        var html = '';
        for( var i in tags ) {
            html += '<option value="'+ i +'">'+ i +'</option>';
        }
        $('#filter').html(html)
                        .val(filter);
    }
}
</script>
@stop