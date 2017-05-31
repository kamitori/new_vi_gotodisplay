@section('body')
<body class="page-header-fixed page-header-fixed-mobile page-quick-sidebar-over-content page-style-square pace-done page-sidebar-closed">
@stop
@section('sideMenu')
<ul id="sidebar-menu" class="page-sidebar-menu page-sidebar-menu-closed {{ isset($currentTheme['sidebar']) && $currentTheme['sidebar'] == 'fixed' ? 'page-sidebar-menu-fixed' : 'page-sidebar-menu-default' }}" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
@stop
<div class="portlet">
    {{ Form::open(array('url'=>URL.'/admin/frontend-home/update', 'method'=> 'POST', 'class'=> 'form-horizontal', 'id'=> 'page-form') ) }}
        <div class="portlet-title">
            <div class="actions btn-set text-right">
                <button class="btn green" type="submit"><i class="fa fa-check"></i> Save</button>
            </div>
            <br/>
        </div>
        <div class="portlet-body form">
                <div class="tab-content no-space">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <div id="content-message">
                            You have some form errors. Please check below.
                        </div>
                    </div>

                    <div class="form-group hidden">
                        <label class="col-md-2 control-label">Editor</label>
                        <div class="col-md-10">
                            <select class="form-control " id="editor">
                                <option value="ckeditor">CKEDITOR</option>
                                <option value="content-builder">ContentBuilder</option>
                            </select>
                        </div>
                    </div>
                    <div id="ckeditor-container">
                        <textarea class="form-control" id="page-content" name="content">{{ $content or '' }}</textarea>
                    </div>
                    <div id="content-builder-container" style="display: none;">
                        <iframe id="content-builder" src="{{ URL.'/admin/frontend-home/source/'}}" style="border: none; width: 100%; min-height: 500px;"></iframe>
                    </div>
                </div>
        </div>
    {{ Form::close() }}
</div>
@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/content-builder/css/contentbuilder.css' ) }}">
@stop
@section('pageJS')
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/jquery-validation/js/jquery.validate.min.js' ) }}"></script>
<script src="{{ URL::asset( 'assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' ) }}" type="text/javascript"></script>
<script src="{{ URL::asset( 'assets/content-builder/js/contentbuilder.js' ) }}"></script>
<script src="{{ URL::asset('assets/admin/js/plugin/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}'
    }
});
var contentBuilder = document.getElementById("content-builder").contentWindow;

var styles = ['{{ URL::asset( 'assets/content-builder/css/content.css' ) }}','{{ URL::asset( 'assets/global/plugins/bootstrap/css/bootstrap.min.css' ) }}', '{{ URL::asset( 'assets/vitheme/style.css' ) }}'];

$('#content-builder').load(function(){
    contentBuilder.setStyle(styles);
});

CKEDITOR.config.contentsCss = styles;
CKEDITOR.replace("page-content", {height: 450});
$('#editor').change(function(){
    if( $(this).val() == 'ckeditor' ) {
        CKEDITOR.instances['page-content'].setData(contentBuilder.getContent());
        $('#content-builder-container').hide();
        $('#ckeditor-container').show();
    } else {
        contentBuilder.setContent(CKEDITOR.instances['page-content'].getData())
        $('#ckeditor-container').hide();
        $('#content-builder-container').show();
    }
});
$("#page-form").validate({
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
        },
    },
    invalidHandler: function (event, validator) {
        $(".alert-danger","#page-form").show();
        Metronic.scrollTo($(".alert-danger","#page-form"), -200);
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
        $(".alert-danger","#page-form").hide();
        if( $('#content-builder-container').is(':visible') ) {
            CKEDITOR.instances['page-content'].setData(contentBuilder.getContent());
        }
        this.submit();
    }
});
$(".maxlength-handler").maxlength({
    limitReachedClass: "label label-danger",
    alwaysShow: true,
    threshold: 5
});
function autoResize()
{
    setInterval(function(){
        $("#content-builder").css("height", contentBuilder.getHeight() + "px");
    }, 500);
}
function getContent()
{
    return $("#content-area").data("contentbuilder").html();
}
</script>
@stop
