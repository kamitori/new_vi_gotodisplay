function checkAddCart(){
    var msg = $("#check_quality").text();
    if(msg.indexOf('Poor')!=-1){
        var modal = $("#popupmsm");
        modal.find('.modal-title').text('Message');
        modal.find('.modal-body').html('This image quality is poor for printing .Do you want to continue adding to your cart ?');
        modal.find('.nobt').text('Cancel');
        modal.find('.yesbt').text('Proceed');
        // document.getElementById('yesbt').onclick = addCart();
        $("#yesbt").click(function() {
            addCart();
            modal.modal('hide');
          });
        modal.modal('show');
    }else{
        addCart();
    }
}
function addCart(){
    $("#editAreaWorkArea").hide();
    $("#preview_box").hide();
    @if( isset($multi_piece) && $multi_piece )
    resetSVG();
    reDrawWithoutPolygon(true);
    var length = $('#tmp_svg image').length;
    var i = 0;
    $('#tmp_svg image').each(function(){
        $(this).load(function(){
            if( ++i == length ) {
                $.ajax({
                    url: '{{ URL }}/buildsvg/fill-white-fast',
                    type: 'POST',
                    data: {svghtml:$("#tmp_svg").html(), product_id:'{{$product["id"]}}',width:100,height:100,mod:'preview',view_dpi:20,remove_poly:1},
                    success: function(result){
                        var url = result.replace('{{ URL }}', '', result);
                        addCartAjax(url);
                    }
                });
            }
        });

    });
    @else
    if ($("#img-link").val()!='') {
        addCartAjax($("#img-link").val());
    } else {
        Preview3D(true, function(imgLink) {
            addCartAjax(imgLink);
        });
    }
    @endif
}

function addCartCluster(){
        resetSVG();
       var attribute = {fill: 'none', stroke: 'black', 'stroke-width': 1};
        var minX, maxX, minY, maxY, img_w, img_h, imgsize, tmp, naturalImg_width, naturalImg_height, MaxBoxWidth,MaxBoxHeight,margin;
        var margin = 0;
        var k = 0;
        var key = 0;
        $("#tmp_svg").html('');
        draw = SVG('tmp_svg').size(objSize.boxWidth, objSize.boxHeight).attr('id','svg_preview_all_tmp');
        @if(isset($svg_setup['data']))
        @foreach($svg_setup['data'] as $key=>$value)
            key = {{$key}};
            var image_{{$key}} = drawSVGImageElement(key,objImg);
            //setup choice
            image_{{$key}}.attr({'id':'reimg_{{$key}}'});
            document.getElementById(image_{{$key}}.node.id).onclick = chooseImage;
            //group
            var group_{{$key}} = draw.group();
            group_{{$key}}.add(image_{{$key}}).attr('id','group_preimg_{{$key}}');
            //mask
            var mask_{{$key}} = draw.rect(objImg[key].boxWidth-2*objImg[key].bleed, objImg[key].boxHeight-2*objImg[key].bleed).move(objImg[key].boxX+objImg[key].bleed,objImg[key].boxY+objImg[key].bleed).attr('id','mask_reimg_{{$key}}');
            //Filter
            addFilter(group_{{$key}},objImg[key].filter);
            //ve clipmask
            group_{{$key}}.clipWith(mask_{{$key}});
        @endforeach
        @endif
        setTimeout(function(){
                            var svghtml = $("#tmp_svg").html();
                            var data = {svghtml:svghtml, product_id:'{{$product["id"]}}',width:400,height:400,mod:'preview',view_dpi:20,remove_poly:1};
                            $.ajax({
                            url: "{{URL}}/buildsvg/fill-white-fast",
                            type: 'POST',
                            data: data,
                            success: function(imgLink){
                                imgLink = imgLink.replace('{{ URL }}','');
                                addCartAjax(imgLink);
                            }
                            });
        },1000)



}


function addCartAjax(img_link){

    objImg.rotateFrame = $('#rotateFrame').val();
    var price = $("#name_price").text();
        price = parseFloat(price.replace("$",""));
        
    var data = {
        id: {{$product['id']}},
        img_link:img_link,
        bleed:objSize.boxBleedInc,
        url:"{{URL}}/collections/{{$collection['short_name']}}/{{$product['short_name']}}",
        svg_info: objImg,
        price: price,
        type: '{{ $type or 'quick-design' }}',
        @if( isset($svgInfo['cart_id']) )
        cart_id: '{{ $svgInfo['cart_id'] }}'
        @endif
    };
    if( $('input[name=bleed_style]:checked').length) {
        data.bleed_title = $('input[name=bleed_style]:checked').val();
    }

    if( $('input[name=border_style]:checked').length) {
        var value = $('input[name=border_style]:checked').val();
        data.border_title = parseFloat(value);
        data.border = value;
    }
    var fields = {
                    'sizes' :   'input:radio[name=sizes]:checked',
                    'wrap':     'input:radio[name=frame_style]:checked',
                    'wrap_title':  'input:radio[name=frame_style]:checked',
                    'quantity': '#custom_quantity',
                    'price': 'custom',
                };
    for( var i in fields ) {
        if( i == 'price' ) {
            var w, h;
            if( $('input:radio[name=sizes]:checked').length ) {
                var size = $('input:radio[name=sizes]:checked').val().split('x');
                w = size[0] || 0;
                h = size[1] || 0;
            } else {
                w = h = 0;
            }
            data['price'] = caculationPrice(w, h);
        } else if( $(fields[i]).length ) {
            if( i == 'wrap_title' ) {
                data[i] = $(fields[i]).attr('title');
            } else {
                data[i] = $(fields[i]).val();
            }
        }
    }
    if($("#origin_image").val()=='') data['origin_image'] = $("#main_image").attr('href');
    else data['origin_image'] = $("#origin_image").val();
    $.ajax({
        url: '{{URL}}/cart/add',
        type: 'POST',
        data: data,
        success: function(result){
             window.location.assign("{{URL}}/cart");
             // here 2 , mo lai sau khi test
        }
    });
}
function caculationPrice(widthInch,heightInch,quantity, changeAll){
    var area, data = {};
    var price = 0;
    var maxW = {{$max_w}};
    var maxH = {{$max_h}};
    var checkedId = $("input[name=sizes]:checked").attr("id");
    var quantity = $("#custom_quantity").val() || 1;
    data["product_id"] = "{{ $product['id'] }}";
    data["bleed"] = parseFloat($("[name=bleed_style]:checked").val() || 0);
    data["width"] = parseFloat(widthInch)
    data["height"] = parseFloat(heightInch);
    //check Max Width and Height
    if(data["width"]>maxW){
       data["width"] = maxW;
       if( checkedId == "sizes_custom" ){
            $("#custom_width").val(maxW);
       }
    }
    if(data["height"]>maxH){
       data["height"] = maxH;
       if( checkedId == "sizes_custom" ){
            $("#custom_height").val(maxH);
       }
    }
    data["quantity"] = parseFloat(quantity);
    var options = {};
    if( $(".product_options:checked").length ) {
        data["options"] = [];
        var lenth = $(".product_options:checked").length;
        for( i =0 ; i < lenth; i++ ){
            data["options"][i] = $(".product_options:checked")[i].value;
        }
        options = data["options"];
    }
    $.ajax({
        url: "{{ URL }}/cal-price",
        type: "POST",
        data: data,
        // async: false,
        success: function(result) {
            price = result.amount;
            $("#name_price").html('$'+result.amount);
            console.log("kq:"+result.amount);
            $("td:nth-child(2)", $("input[name=sizes]:checked").closest("tr")).text('$'+result.unit_price);
            $("input[name=sizes]:checked").attr("rel", result.unit_price);
            if( changeAll ) {
                if( $("input[name=sizes]:not(:checked)").length ) {
                    var notChecked = $("input[name=sizes]:not(:checked)");
                    var data = {};
                    data['size'] = [];
                    var i = 0;
                    $.each(notChecked, function(){
                        var value = $(this).val();
                        value = value.split("x");
                        var w = value[0];
                        var h = value[1];
                        if(w>maxW){
                           w = maxW;
                        }
                        if(h>maxH){
                           h = maxH;
                        }
                        data['size'][i] = {w: w, h: h};
                        i++;
                    });
                    data["all"] = 1;
                    data["product_id"] = "{{ $product['id'] }}";
                    data["bleed"] = parseFloat($("[name=bleed_style]:checked").val() || 0);
                    data["quantity"] = parseFloat(quantity);
                    data["options"] = options;
                    $.ajax({
                        url: "{{ URL }}/cal-price",
                        type: "POST",
                        data: data,
                        success: function(rsl){
                            var i = 0;
                            $.each(notChecked, function(){
                                $("td:nth-child(2)", $(this).closest("tr")).text('$'+rsl.unit_prices[i]);
                                $(this).attr("rel", rsl.unit_prices[i]);
                                i++;
                            });
                        }
                    });
                }
            }
        }
    });
    return price;
}
function changeQuantity(){
    var size = $("[name=sizes]:checked").val();
    size = size.split("x");
    var widthInch = size[0];
    var heightInch = size[1];
    var quantity = $("#custom_quantity").val();
    var price = caculationPrice(widthInch,heightInch,quantity, true);

    // $("#name_price").html(' - $'+price);
    $("#img-link").val("");
}


$( "#dialog_resolution" ).dialog({
    resizable: false,
    draggable:false,
    modal: true,
    width: 900,
    minHeight:500,
    autoOpen: false,
});

$(".custom_size").keydown(function(e){
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
         // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
         // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
})