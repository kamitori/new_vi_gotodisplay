<script>

$("#slider-bright").slider({
        range: "max",
        step: 1,
        min: -100,
        max: 500, 
        value: 0,
        slide: function( event, ui ) {
                $("#val-bright").val(ui.value);
                $("#svg_main").html('');
                drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    });

$("#val-bright").on('change',function(){
        $("#slider-bright").slider({
            value: $("#val-bright").val(),
        });
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
})
$("#val-bright").keypress(function(){
        if($(this).val()>500){
            $(this).val(500);
        }
        if($(this).val()<-100){
            $(this).val(-100);
        }
})
///////////////////////////////////
$("#slider-contrast").slider({
        range: "max",
        step: 1,
        min: -100,
        max: 100, 
        value: 0,
        slide: function( event, ui ) {
                $("#val-contrast").val(ui.value);
                $("#svg_main").html('');
                drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    })

$("#val-contrast").on('change',function(){
        $("#slider-contrast").slider({
            value: $("#val-contrast").val(),
        });
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
})

$("#val-contrast").keypress(function(){
        if($(this).val()>100){
            $(this).val(100);
        }
        if($(this).val()<-100){
            $(this).val(-100);
        }
})
///////////////////////////////////
$("#slider-hue").slider({
        range: "max",
        step: 1,
        min: 0,
        max: 360, 
        value: 0,
        slide: function( event, ui ) {
                $("#val-hue").val(ui.value);
                $("#svg_main").html('');
                drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    })

$("#val-hue").on('change',function(){
        $("#slider-hue").slider({
            value: $("#val-hue").val(),
        });
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
})

$("#val-hue").keypress(function(){
        if($(this).val()>360){
            $(this).val(360);
        }
        if($(this).val()<0){
            $(this).val(0);
        }
})
///////////////////////////////////
$("#slider-saturate").slider({
        range: "max",
        step: 0.1,
        min: 0,
        max: 10, 
        value: 1,
        slide: function( event, ui ) {
                $("#val-saturate").val(ui.value);
                $("#svg_main").html('');
                drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    })

$("#val-saturate").on('change',function(){
        $("#slider-saturate").slider({
            value: $("#val-saturate").val(),
        });
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
})

$("#val-saturate").keypress(function(){
        if($(this).val()>10){
            $(this).val(10);
        }
        if($(this).val()<0){
            $(this).val(0);
        }
})
///////////////////////////////////
$("#slider-gamma").slider({
        range: "max",
        step: 0.1,
        min: 0,
        max: 10, 
        value: 1,
        slide: function( event, ui ) {
                $("#val-gamma").val(ui.value);
                $("#svg_main").html('');
                drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    })

$("#val-gamma").on('change',function(){
        $("#slider-gamma").slider({
            value: $("#val-gamma").val(),
        });
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
})

$("#val-gamma").keypress(function(){
        if($(this).val()>10){
            $(this).val(10);
        }
        if($(this).val()<0){
            $(this).val(0);
        }
})
///////////////////////////////////
$("#slider-blur").slider({
        range: "max",
        step: 0.1,
        min: 0,
        max: 20, 
        value: 0,
        slide: function( event, ui ) {
                $("#val-blur").val(ui.value);
                $("#svg_main").html('');
                drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    })

$("#val-blur").on('change',function(){
        $("#slider-blur").slider({
            value: $("#val-blur").val(),
        });
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
})

$("#val-blur").keypress(function(){
        if($(this).val()>20){
            $(this).val(20);
        }
        if($(this).val()<0){
            $(this).val(0);
        }
})
///////////////////////////////////
$("#slider-sharpen").slider({
        range: "max",
        step: 0.1,
        min: 0,
        max: 20, 
        value: 0,
        slide: function( event, ui ) {
                $("#val-sharpen").val(ui.value);
                $("#svg_main").html('');
                drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    })

$("#val-sharpen").on('change',function(){
        $("#slider-sharpen").slider({
            value: $("#val-sharpen").val(),
        });
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
})

$("#val-sharpen").keypress(function(){
        if($(this).val()>20){
            $(this).val(20);
        }
        if($(this).val()<0){
            $(this).val(0);
        }
})


$("#btn_reset_filter").on("click",function(){
    $("#val-bright").val(0);
    $("#val-contrast").val(0);
    $("#val-hue").val(0);
    $("#val-saturate").val(1);
    $("#val-gamma").val(1);
    $("#val-blur").val(0);
    $("#val-sharpen").val(0);

    
    $("#slider-bright").slider({
            value: $("#val-bright").val(),
    });
    $("#slider-contrast").slider({
            value: $("#val-contrast").val(),
    });
    $("#slider-hue").slider({
            value: $("#val-hue").val(),
    });
    $("#slider-saturate").slider({
            value: $("#val-saturate").val(),
    });
    $("#slider-gamma").slider({
            value: $("#val-gamma").val(),
    });
    $("#slider-blur").slider({
            value: $("#val-blur").val(),
    });
    $("#slider-sharpen").slider({
            value: $("#val-sharpen").val(),
    });
    $("#svg_main").html('');
    drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
})

</script>