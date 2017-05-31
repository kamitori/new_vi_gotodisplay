<script src="{{URL}}/assets/js/svgjs/svg.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.draggable/svg.draggable.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.filter/svg.filter.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.parser/svg.parser.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.importer/svg.import.min.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
    var redraw = false;
    var current_image = '';
    var current_polygon = '';
    var current_position = 0;
    var after_position = 0;
    var first_run = 0;
    var sysTimeOut;
    var objImg = {};
    var objSize = {};
    var objOrientation = {};
    var objEdgeColor = {};
    //Image
    objImg.url = '{{ isset($svgInfo['url']) ? $svgInfo['url'] : (isset($arr_img[0])?URL.$arr_img[0]:'') }}';
    objImg.zoom = {{ $svgInfo['zoom'] or 1 }};
    objImg.rotate = {{ $svgInfo['rotate'] or 0 }};
    objImg.filter = '{{ $svgInfo['filter'] or 'original' }}';
    @if( isset($svgInfo['x']) )
    objImg.oldX = {{ $svgInfo['x'] }};
    @endif
    @if( isset($svgInfo['x']) )
    objImg.oldY = {{ $svgInfo['y'] }};
    @endif
    objImg.resetXY = {{ $svgInfo['resetXY'] or true }};
    objImg.zoomX = {{ isset($svgInfo['zoomX'] ) && is_numeric($svgInfo['zoomX'] ) ? $svgInfo['zoomX'] :0 }};
    objImg.zoomY = {{ isset($svgInfo['zoomY'] ) && is_numeric($svgInfo['zoomY'] ) ? $svgInfo['zoomY'] :0 }};
    objImg.width = {{ isset($svgInfo['width'] ) && is_numeric($svgInfo['width'] ) ? $svgInfo['width'] :0 }};
    objImg.height = {{ isset($svgInfo['height'] ) && is_numeric($svgInfo['height']) ? $svgInfo['height']: 0 }};
    objImg.flip_x = {{ isset($svgInfo['flip_x'] ) && is_numeric($svgInfo['flip_x']) ? $svgInfo['flip_x']: 1 }};
    objImg.flip_y = {{ isset($svgInfo['flip_y'] ) && is_numeric($svgInfo['flip_y']) ? $svgInfo['flip_y']: 1 }};
    objImg.rotateFrame = {{ isset($svgInfo['rotateFrame'] ) && is_numeric($svgInfo['rotateFrame']) ? $svgInfo['rotateFrame']: 0 }};
    //Size
    objSize.boxWidth = {{$svg_setup['svg']['width']}};
    objSize.boxHeight = {{$svg_setup['svg']['height']}};
    objSize.boxBleed = {{$svg_setup['svg']['bleed']}};
    objSize.boxBleedInc = {{isset($product['bleed'])?$product['bleed']:0}};
    objSize.boxBorder = {{$svg_setup['svg']['border']}};
    objSize.boxBorderInc = {{((float)$product['border_in']>0)?(float)$product['border_in']:0}};
    objSize.viewZoom = {{$svg_setup['svg']['view_dpi']}};

    //objEdgeColor
    objEdgeColor.points = '{{$svg_setup["polygon"]["points"]}}';
    objEdgeColor.borders = '{{$svg_setup["border"]["points"]}}';
    objEdgeColor.rect = JSON.parse('{{json_encode($svg_setup["rect"])}}');
    objEdgeColor.path = JSON.parse('{{json_encode($svg_setup["path"])}}');
    objEdgeColor.ptype = {{(int)$product['product_type']}};
</script>