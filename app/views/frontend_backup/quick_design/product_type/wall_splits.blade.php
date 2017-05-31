<script type="text/javascript">
@if ($product['product_type']==6 && $product['number_img'])
    
            //$('#addimg{{$i}}').on("click", function(event){
            function addImage($i) {
                if (objImg.url.indexOf("upload/themes")==-1) {
                    alert("Please upload and choose image");
                    return false;
                } else {
                    //alert('1');
                    if (typeof $(".image" + $i) != 'undefined')
                        $(".image" + $i).remove();
                    var id_clippath;
                    svg_main = $('#svg_main')[0];
                    id_clippath = svg_main.getElementsByTagNameNS('*','clipPath')[$i-1].id;
                    window['image'+$i] = draw.image(objImg.url).loaded(function(loader){
                        var naturalImg_width = loader.width; // kich thuoc that
                        var naturalImg_height = loader.height;
                        mask_width  = $("#"+id_clippath)[0].getBoundingClientRect().width;
                        mask_height = $("#"+id_clippath)[0].getBoundingClientRect().height;
                        margin_top  = $("#"+id_clippath).offset().top - $("#svg_main > svg").offset().top;
                        margin_left = $("#"+id_clippath).offset().left - $("#svg_main > svg").offset().left ;

                        if(naturalImg_width > naturalImg_height){
                            img_h = mask_height;
                            img_w = naturalImg_width*(img_h/naturalImg_height);
                            maxX  = img_w+margin_left;
                            maxY  = img_h+margin_top;
                            minY  = margin_top;
                            minX  = mask_width-img_w+margin_left;
                            if(img_w<mask_width){
                                img_w = mask_width;
                                img_h = naturalImg_height*(img_w/naturalImg_width);
                                maxX  = img_w+margin_left;
                                maxY  = img_h+margin_top;
                                minY  = margin_top - (img_h - mask_height);
                                minX  = mask_width-img_w+margin_left;
                            }
                        }else{
                            img_w = mask_width;
                            img_h = naturalImg_height*(img_w/naturalImg_width);
                            maxX  = img_w+margin_left;
                            maxY  = img_h+margin_top;
                            minY  = margin_top - (img_h - mask_height);
                            minX  = mask_width-img_w+margin_left;
                            if(img_h < mask_height){
                                mg_h  = mask_height;
                                img_w = naturalImg_width*(img_h/naturalImg_height);
                                maxX  = img_w + margin_left;
                                maxY  = img_h + margin_top;
                                minY  = margin_top;
                                minX  = mask_width-img_w + margin_left;
                            }
                        }
                        if(objImg.resetXY){
                            objImg.x = -1*(img_w-draw.width())/2; //-1*Math.round((img_w-draw.width())/2);
                            objImg.y = (draw.height()/2 - img_h)/2;//-1*Math.round((img_h-draw.height())/2);
                            //Flip only when resetXY
                            if(objImg.flip_x == -1){
                                objImg.x = -1*(draw.width()-objImg.x);
                            }
                            if(objImg.flip_y==-1){
                                objImg.y = -1*(draw.height()-objImg.y);
                            }
                        }
                        // if (typeof $("#svg_main > svg > svg") != 'undefined')
                        //         $("#svg_main > svg > svg").remove();
                        this.x(margin_left);
                        this.y(margin_top);
                        this.size(img_w, img_h);
                        this.draggable({minX:minX, maxX:maxX, minY:minY, maxY:maxY, flipX: objImg.flip_x, flipY: objImg.flip_y});
                    });  
                    window['image'+$i].attr({
                        'clip-path':"url(#"+id_clippath+")"
                    })
                    window["image"+$i].addClass('image'+$i);
                }
            }//});
    
@endif


</script>