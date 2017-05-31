<script type="text/javascript">
var Main = function (){
	var previewZoom = 1;
	var backgroundImage = '{{ $background[0] }}';
	var objUpload = {};
	var InUpload = false;
	var CountUpload = 0;
	var uploading;
	function bind()
	{
		$("#dialog").on( "dialogclose", function( ) {
		    $("#dialog #search_dialog").hide();
		} );
		$('#fileup').filer({
			limit: 10
		});
		$('#bgfileup').filer({
			limit: 10
		});
		$('.jFiler-input').hide();

		function importPC(){
			$("#fileup").click();
		}
		$(".nicebt").on('click',function(){
			$("#bgfileup").click();
		});

		$(".dsbt").click(function(){
			var cont = $(this).attr('id');
			cont = cont.replace("dsbt_","content_");
			$(".ds_button").removeClass('ds_active');
			$(this).addClass('ds_active');
			$(".content_list").css("display","none");
			$("#"+cont).css("display","table");
		});

		$("#fileup").change(function(event){
			Main.uploadFiles(event,0,1);
		});
		$("#bgfileup").change(function(event){
			Main.uploadFiles(event,0,1);
		});

		$("#btnChooseColorFromImg").click(function(){
			ColorPicker.pick();
		});

		$('.paletteLabel').on('click',function(){
			$('.paletteContent').removeClass('active');
			$('.paletteLabel').removeClass('active');
			$(this).addClass('active');
			$("#"+$(this).attr('data-label-for')).addClass('active');
		});

		$("#getPicturesBtnLarge").on('click',function(){
			$("#dlg-container").show();
		});

		$("#dsbt_filter").on('click',function(){
			$('.paletteContent').removeClass('active');
			$('.paletteLabel').removeClass('active');
			$("#paletteContentFilters").addClass('active');
			$("#paletteLabelFilters").addClass('active');
		});

		$( "#slider-vertical" ).slider({
			orientation: "vertical",
			range: "max",
			step: 5,
			min: 0,
			max: 360,
			value: 0,
			slide: function( event, ui ) {
				$( "#amount" ).val( ui.value); //  + " º"
				WallCollage.rotate(ui.value);
			}
		});
		$( "#amount" ).val( $( "#slider-vertical" ).slider( "value" ) );
		$( "#amount" ).change(function(){
			var val = $( "#amount" ).val();
			$( "#slider-vertical" ).slider('value', val);
			WallCollage.rotate(val);
		});
		$("#zoom-slider").slider({
            orientation: "vertical",
            range: "max",
            step: 0.2,
            min: 1,
            max: 3.6,
            value: 1,
            slide: function( event, ui ) {
				WallCollage.zoom(ui.value);
			}
        });
		$("#amount").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) ||
				 // Allow: home, end, left, right
				(e.keyCode >= 35 && e.keyCode <= 39)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
		/*$("#editAreaWorkArea").click(function(e){
			if ($("#editAreaWorkArea")[0] == e.target) {
				$(".slider_bt").hide();
			}
		});*/

		$('#import_vi').click(function(){
		    getVIImages();
		});
		$('#searchlib_bt').click(function(){
		    var tags = $('#searchlib_text').val();
		    getVIImages(tags);
		});

		$('#background-upload').change(function(){
			var data = new FormData();
			data.append('background', $(this)[0].files[0]);
			$.ajax({
				url: '{{ URL }}/collections/save-background',
				type: 'POST',
				data: data,
				processData: false,
				contentType: false,
				success: function(result){
					if( result.url ) {
						$('.backgroundCategory.active').removeClass('active');
						var html = '<div class="backgroundCategory" onclick="Main.changeBackgound(this)" style="width:150px; height: auto !important;">' +
										'<div class="assetCategoryLabel"></div>' +
										'<img src="'+ result.url +'" class="paletteBgThumbnail" style="width:150px;height:auto;" />' +
									'</div>';
						$('#user-background').append(html);
						$('#user-background .backgroundCategory:last img').load(function(){
							$(this).parent().trigger('click');
						});
					}
				}
			})
		});
	}

	function getVIImages(tags)
	{
		var data = {};
	    if( tags ) {
	        data['tags'] = tags;
	    }
		 $.ajax({
			  url: "{{ URL.'/get-vi-images' }}",
			  type: 'POST',
			  data: data,
			  success: function(result) {
				  var html = '';
				  if( result.length ) {
					  for(var i in result) {
						  html += '<div class="large-2 columns block_album">' +
									  '<div class="block_image" id="block_image_'+ result[i].id +'">' +
										  '<img class="cover_album" data-check="0" data-source="'+ result[i].link +'" src="'+ result[i].thumb +'" data-store="'+ result[i].store +'" onclick="Main.choice('+ result[i].id +')" data-ext="'+ result[i].ext +'" />' +
										  '<div class="icon_close5" onclick="Main.removeChoice('+ result[i].id +')"  style="display:none;"></div>'+
									  '</div>' +
								  '</div>';
					  }
				  }
				  	$("#loading_import").hide();
				  	$(".of_album").hide();
				  	$("[text ='List Album']").hide();
				  	$("#loading_import").hide();
				  	$("#dialog").dialog({width: 1200,height: 600,modal: true}).dialog("open");
					$("#search_dialog").show();
				  	$("#list_image").css('max-height','500px').css('height','478px')
								  .html(html);
			  	}
		});
	}

	function getWH()
	{
		var tmpImage = new Image();
		tmpImage.src = backgroundImage;
		var width = tmpImage.width;
		var height = tmpImage.height;
		var ratio = width / height;
		if( width > 1000 ) {
			width = 1000;
			height = width / ratio;
		}
		if( height > 450 ) {
			height = 450;
			width = height * ratio;
		}
		return {'width': width, 'height': height};
	}

	function preview(callBack, afterRenderCallBack)
	{
		ColorPicker.close();
		Main.previewRenderFinished = false;
		$(".slider_bt").hide();
		previewZoom = 1;
		@if(!$isMobile)
		$('#editAreaWorkArea').css({
			'position': 'absolute',
			'opacity': 0,
			'z-index': '-1000'
		});
		@endif
		$('#svg-main')[0].instance.addClass('preview');
		if( $('#svg-main .shape-path.active').length ) {
			$('#svg-main .shape-path.active')[0].instance.removeClass('active');
		}
		$('#zoom_bt').hide();
		$('#preview_box').show();
		$('#preview_box #loading-image').show();
		$('#preview_content').html('').hide();
		callBack();
		var timeProcess = 0;
		var interval = setInterval(function() {
			console.log('--Rendering-- '+ (timeProcess/1000).toFixed(2));
			timeProcess+= 200;
			if( timeProcess > 60000 ) {
				clearInterval(interval);
				return false;
			}
			if(!Main.previewRenderFinished) {
				return false;
			}
			if( typeof afterRenderCallBack == 'function' ) {
				afterRenderCallBack();
			} else {
				$('#zoom_bt2').show();
				$('#preview_content').show();
				$('#preview_box #loading-image').hide();
			}
			clearInterval(interval);
		}, 200);
	}
	return {
		previewRenderFinished: false,
		bind : function() {
			bind();
			this.findWrap();
		},
		findWrap: function() {
			var wrapName = $('#paletteContentOptions input[type=radio][name=frame_style]:checked').attr('title');
			if( typeof wrapName != undefined ) {
				this.changeWrapName(wrapName);
			}
		},
		changeWrapName: function(wrapName) {
			$('span#name_wrap').text(wrapName);
		},
		changeWrap: function(wrapKey, wrapName) {
			if( wrapName == 'Spot Colour' ) {
				$('.paletteContent').removeClass('active');
				$('#pick_color').addClass('active');
			} else {
				WallCollage.wrap(wrapKey);
			}
			this.changeWrapName(wrapName);
		},
		filter: function(filterKey) {
			$('#paletteContentFilters input[type=radio][name=filter_type][value='+ filterKey +']').prop('checked', true);
		},
		rotate: function(rotate) {
			$('#amount').val(rotate);
			$( "#slider-vertical" ).slider('value', rotate);
		},
		zoom: function(zoom) {
			$('#zoom-slider').slider('value', zoom);
		},
		showSlider: function(show) {
			if( show == false ) {
				$('.slider_bt').hide();
			} else {
				$('.slider_bt').show();
			}
		},
		zoomAll: function(zoom) {
			if( zoom != 1 ) {
				this.showResetZoom();
				$('#reset_zoom').show();
			} else {
				this.showResetZoom(false);
			}
			if( $('#pick_color').is(':visible') ) {
				ColorPicker.pick();
			}
		},
		showResetZoom: function(show) {
			if( show == false ) {
				$('#reset_zoom').hide();
			} else {
				$('#reset_zoom').show();
			}
		},
		preview: function(show) {
			if( show === false ) {
				return this.closePreview();
			}

			preview(function(){
				if( $('#svg-main.preview.preview-bg').length ) {
					$('#svg-main')[0].instance.removeClass('preview')
												.removeClass('preview-bg');
					$('#svg-main.preview.preview-bg .shape-path').each(function(){
						this.instance.unfilter(true);
					});
				}
				$('#paletteLabelBackgrounds, #zoom_bt2').hide();
				$('#paletteLabelArrangements').trigger('click');
				$('#preview_content').mouseover(function(){
					return false;
				});
				var svgSetup = WallCollage.svgSetup();
				var previewAttribute = {
					'id': 'svg-preview',
					'width': svgSetup.main.width,
					'height': svgSetup.main.height,
					'viewBox': '0 0 '+ svgSetup.main.width +' '+ svgSetup.main.height
				};
				var useAttribute = {
					'id': 'use-preview',
					'x': 0,
					y: 0
				};
				WallCollage.resetZoom();
				SVG('preview_content')
					.attr(previewAttribute)
					.use( SVG.get('svg-main') )
					.attr(useAttribute);
				Main.previewRenderFinished = true;
			});
		},
		previewBG: function() {
			preview(function(){
				$('#paletteLabelBackgrounds').show().trigger('click');
				$('#preview_content').unbind('mouseover');
				$('#svg-main')[0].instance.addClass('preview')
											.addClass('preview-bg');
				var size = getWH();
				var svgSetup = WallCollage.svgSetup();
				WallCollage.resetZoom();
				var previewDraw = SVG('preview_content').attr({'id': 'svg-preview', 'width': 1000, 'height': 450});

				$('#svg-main .shape-path').each(function(){
					var defs = SVG.get('main-defs');
					var id = $(this).attr('id').replace('shape-path-', '');
					var shapePath = this;
					if( !$('#shape-clip-'+ id).length ) {
						defs.add( previewDraw.clip()
											.add(
												shapePath.instance.clone()
																	.removeClass('shape-path')
																	.attr('fill', null)
											).attr('id', 'shape-clip-'+ id)
								)
					}
					SVG.get('group-image-'+ id)
						.attr('clip-path', 'url("#shape-clip-'+ id +'")');
				});
				var image = previewDraw.image(backgroundImage)
										.attr({
											'width': size.width,
											'height': size.height,
											'x': 0,
											'y': 0,
											'id': 'preview-background-image'
										})
										.loaded(function(){
											this.draggable();
										});
				previewDraw.add(image)
				var nested = previewDraw.nested()
										.attr({
											'id': 'svg-use',
											'width': svgSetup.main.width/3,
											'height': svgSetup.main.height/3,
											'viewBox': '0 0 '+ svgSetup.main.width +' '+ svgSetup.main.height,
											'x': size.width - svgSetup.main.width/3,
											'y': (size.height - svgSetup.main.height/3)/2,
										});
				nested.draggable({
					minX: 0,
					maxX: size.width,
					minY: 0,
					maxY: size.height
				});
				nested.add(
						nested.use( SVG.get('svg-main') )
						.on('click', function() {
							return false;
						})
						// .filter(function(add) {
						//   	var blur = add.offset(10, 10).in(add.sourceAlpha).gaussianBlur(5)
  						// 		add.blend(add.source, blur)
						// })
					);
				Main.previewRenderFinished = true;
			});
		},
		closePreview: function() {
			$('#paletteLabelBackgrounds, #zoom_bt2').hide();
			$('#paletteLabelArrangements').trigger('click');
			if( $('#svg-main.preview-bg').length ) {
				$('#svg-main')[0].instance.removeClass('preview-bg');
				$('#svg-main.preview.preview-bg .shape-path').each(function(){
					this.instance.unfilter(true);
				});
			}
			if( $('#svg-main.preview').length ) {
				$('#svg-main')[0].instance.removeClass('preview');
			}
			$('#svg-main .main-image').each(function(){
				var id = $(this).attr('id').replace('image-', '');
				SVG.get('group-image-'+ id).attr('clip-path', 'url("#clip-'+ id +'")');
			});
			$('#editAreaWorkArea').css({
				'position': 'relative',
				'opacity': 1,
				'z-index': 0
			});
			$('#preview_box').hide();
			$('#zoom_bt').show();
		},
		zoomInPreview: function() {
			this.zoomPreview(previewZoom+0.2);
		},
		zoomOutPreview: function() {
			this.zoomPreview(previewZoom-0.2);
		},
		zoomPreview: function(zoom) {
			if( zoom == undefined
				|| zoom < 0.5
				|| zoom > 3.6 ) {
				return false;
			}
			previewZoom = zoom;
			if( $('#svg-preview #preview-background-image').length ) {
				SVG.get('svg-preview')
					.attr({
						'width': 1000*zoom,
						'height': 450*zoom,
						'viewBox': '0 0 1000 450'
					});
			} else {
				var svgSetup = WallCollage.svgSetup();
				var width = svgSetup.main.width;
				var height = svgSetup.main.height;
				SVG.get('svg-preview')
					.attr({
						'width': width*zoom,
						'height': height*zoom,
						'viewBox': '0 0 '+ width +' '+ height
					});
			}
		},
		changeBackgound: function(object) {
			$('.backgroundCategory.active').removeClass('active');
			$(object).addClass('active');
			backgroundImage = $('img', object).attr('src');
			var size = getWH();
			SVG.get('preview-background-image').attr({href: backgroundImage, x: 0, y: 0, 'width': size.width, 'height': size.height});
			var svgUse = SVG.get('svg-use');
			svgUse.attr({
				x: size.width - svgUse.width(),
				y: (size.height - svgUse.height())/2
			}).draggable({
				minX: 0,
				maxX: size.width,
				minY: 0,
				maxY: size.height
			});
		},
		choice: function(id) {
		    $("#block_image_"+ id +" .icon_close5").show();
		    $("#block_image_"+ id +" .cover_album").addClass("choice_image")
		    										.attr("data-check",1);

		},
		removeChoice: function(id) {
		    $("#block_image_"+ id +" .icon_close5").hide();
		    $("#block_image_"+ id +" .cover_album").removeClass("choice_image")
		    										.attr("data-check",0);
		},
		chooseImages: function() {
		    var html;
		    var d = new Date();
		    var arrImgs = [];
		    $.each($("[data-check=1]"),function( key, value ) {
		        var link = $( this ).attr("data-source");
		        var data = $(this).data();
		        if( data.store == 'google-drive' ) {
		            var ext = data.ext;
		            $.ajax({
		                url: '{{URL}}/socials/get-image',
		                type: 'POST',
		                async:false,
		                data:{
		                    link:link,
		                    ext:ext,
		                    data: data
		                },
		                async:false,
		                success: function(result){
		                    if(result.error==0){
		                        link = result.data;
		                    }else{
		                        link = false;
		                    }
		                }
		            })
		        }
		        if( !link ) {
		            return;
		        }
		        html = '<div class="image_content" id="img_upload_vi'+d.getTime()+'">'+
		               "<img class=\"photo\" src=\""+link+"\" alt=\"\" onclick=\"WallCollage.changeImage('"+link+"');\">"+
		               '</div>';
		        $(html).prependTo("#slider_image");
		        arrImgs.push(link);
		    });
		    //save session
		    $.ajax({
		        url:"{{URL}}/save-session-imgs",
		        type:"POST",
		        data:{'arrImgs':arrImgs},
		        success: function(ret){
		            console.log(ret);
		        }
		    });
		    $("#dialog" ).dialog({width: 1200}).dialog("close");

		},
		uploadFiles: function(event,items,add,mode){
		    var files = event.target.files;

		    var count = 0; var imgkey;
		    $.each(files, function(key, value){
		        var data = new FormData();
		        data.append(key, value);
		        imgkey = value.name;
		        imgkey = imgkey.split(".");
		        imgkey = imgkey[0];
		        imgkey = imgkey.toLocaleLowerCase();
		        for (var i = 0; i < imgkey.search(/\s/g); i++) {
		            imgkey = imgkey.replace(" ", "-");
		        };
		        objUpload[imgkey] = data;

		        $('<div class="image_content" id="img_upload_'+imgkey+'"><div class="img_icon"><div class="imgthumb_loading"><div class="imgthumb_progress" style="width: 0%;"></div></div><div class="img_icon_complete" style="display:none;"><img src="{{URL}}/assets/images/others/ajax-loader.gif" /></div></div></div>').prependTo("#slider_image");
		        count++;
		    });
		    this.runUpload(files);
		},
		runUpload: function(files, position){
		    var Sum = Object.keys(objUpload).length;
		    if(!InUpload){
		        timeStarted = new Date();
		        InUpload = true;
		        UploadKey = Object.keys(objUpload)[CountUpload];
		        var data = objUpload[UploadKey];
		        CountUpload++;
		        if( files != undefined ) {
		            if( position == undefined ) {
		                position = 0;
		            }
		            if( files[position] == undefined ) {
		                return false;
		            }
		            var f = files[position];
		            position++;
		            var reader = new FileReader();
		            reader.readAsDataURL(f);
		            reader.onload = function(e){
		                var src = e.target.result;
		                var img = new Image();
		                img.src = src;
		                img.onload = function(){
		            		f.name = f.name.replace(/ /g, '');
		                    InUpload = false;
		                    $("#img_upload_"+UploadKey+" .imgthumb_progress").css("width","100%");
		                    $("#img_upload_"+UploadKey+" .imgthumb_loading").hide();
		                    $("#img_upload_"+UploadKey+" .img_icon_complete").css("display","block");
		                    $('<img class="photo" src="'+ src +'" data-id="'+ f.name +'" onclick="WallCollage.changeImage(this, \''+ f.name + '\');"/><div class="icon_close5" onclick="Main.removeUpload(this)" style=""></div>').appendTo("#img_upload_"+UploadKey);
		                    $("#img_upload_"+UploadKey+" .img_icon").remove();
		                    $("#img_upload_"+UploadKey+' [data-id="'+ f.name +'"]').trigger('click');
		                };
		            };
		        }
		        $.ajax({
		            url: '/collections/gettheme/saveimg',
		            type: 'POST',
		            data: data,
		            cache: false,
		            dataType: 'json',
		            processData: false,
		            contentType: false,
		            xhr: function() {
		                var myXhr = $.ajaxSettings.xhr();
		                return myXhr;
		            },
		            success: function(result) {
		            	if(result.error==''){
		            		var _data = result.data;
		            	    for(var key in _data){
		            	        var src = '{{ URL }}'+_data[key].url+_data[key].files[0];
		            	        var img = new Image();
		                		img.src = src;
		                		img.name = _data[key].files[0];
		                		img.onload = function() {
		                			var src = this.src;
		                			$('[data-id="'+ this.name +'"]').each(function(){
		                				$(this).attr('src', src);
		                				$(this).attr('href', src);
		                			});
		                		};
		            	    }
		            	}
		            }
		        });
		    }
		    if(CountUpload<Sum){
		        uploading = setTimeout(function(){
		          Main.runUpload(files, position);
		        },1000);
		    }else{//end upload
		        clearInterval(uploading);
		        InUpload = false;
		        CountUpload = 0;
		        objUpload ={};
		    }
		},
		addCart: function() {
			this.preview3D(function() {
				$('#svg-main .shape-path').each(function(){
					var id = $(this).attr('id').replace('shape-path-', '');
					var bleedPath = SVG.get('bleed-'+ id);
					if( bleedPath.attr('fill-opacity') == 0.4 ) {
						opacity = true;
						bleedPath.attr('fill-opacity', 0);
					}
				});
				var name = '3d_' + (new Date()).getTime() +'.png';
				$.ajax({
					url : "{{ URL.'/capture3d' }}",
					type: "POST",
					data: {img: $("#preview_content canvas")[0].toDataURL(),name3d: name},
					success: function(imgLink){
						var svgSetup = WallCollage.svgSetup();
						$.ajax({
							url: '{{ URL.'/cart/add' }}',
							type: 'POST',
							data: {
								id: {{ $product['id'] }},
								img_link: imgLink,
								bleed: 0,
								url: "{{ URL.'/collections/'.$product['category']['short_name'].'/'.$product['short_name']  }}",
								wrap: svgSetup.main.wrap,
								wrap_title: $('input:radio[name=frame_style]:checked').attr('title'),
								quantity: {{ $product['quantity'] }},
								price: {{ $product['sell_price'] }},
								svg_info: WallCollage.svgSetup(),
								svg: $('#svg_div').html(),
								type: '{{ $product['design_type'] or 'wall-collage-design' }}',
								@if( isset($product['cart_id']) )
								cart_id: '{{ $product['cart_id'] }}'
								@endif
							},
							success: function(){
								window.location.href = '{{ URL.'/cart' }}';
							}
						});
					}
				});
			});

		},
		preview3D: function(afterRenderCallBack) {
			WallCollage.resetZoom();
			this.closePreview();
			preview(function(){
				var info = {};
				var svgSetup = WallCollage.svgSetup();
				var opacity = false;
				var draw = WallCollage.getDraw();
				var arrColor = ['red', 'green', 'yellow', 'gray', 'organe', 'blue'];
				$('#svg-main .shape-path').each(function(){
					var minX = null;
					var minY = null;
					var id = $(this).attr('id').replace('shape-path-', '');
					var pathArray = this.instance['_array'].value;
					var position = 0;
					for(var i in pathArray) {
						if( info[id+'.'+position] == undefined ) {
							info[id+'.'+position] = {
								'center': {
									'points': []
								}
							};
						}
						var array = pathArray[i];
						if( array.length != 3 ) {
							position++;
							var minX = null;
							var minY = null;
							continue;
						}
						var x = Number(array[1]);
						var y = Number(array[2]);
						if( minX == null || minX > x ) {
							minX = x;
						}
						if( minY == null || minY > y) {
							minY = y;
						}
						info[id+'.'+position].center.points.push({ x: x, y: y });
						info[id+'.'+position].center.minX = minX;
						info[id+'.'+position].center.minY = minY;
					}
					var bleedArray = SVG.get('bleed-'+ id).array.value;
					var j = 0;
					var position = 0;
					var minX = null;
					var minY = null;
					for(var i in bleedArray) {
						var array = bleedArray[i];
						if( info[id+'.'+position]['bleed_'+ j] == undefined ) {
							info[id+'.'+position]['bleed_'+ j] = {
								'points' : [],
								'angle': ''
							};
						}
						if( array.length != 3 ) {
							var point = svgSetup.elements[id].allPoints.points;
							var current = j;
							var next = current + 1;
							if( next > point['path_'+ position].length - 1 ) {
								next = next - point['path_'+ position].length;
							}
							prevPoint = {x: point['path_'+ position][current].x, y: point['path_'+ position][next].y};
							info[id+'.'+position]['bleed_'+ j].angle = -Pointer.angle(prevPoint, point['path_'+ position][current], point['path_'+ position][next]);
							/*draw.path(
								'M'+prevPoint.x+' '+prevPoint.y+
								'L'+point['path_'+ position][current].x+' '+point['path_'+ position][current].y+
								'L'+point['path_'+ position][next].x+' '+point['path_'+ position][next].y
								).attr({'stroke': arrColor[j], 'fill': 'none'});*/
							j++;
							if( j == point['path_'+ position].length ) {
								var minX = null;
								var minY = null;
								j = 0;
								position++;
							}
							continue;
						}
						var x = Number(array[1]);
						var y = Number(array[2]);
						if( minX == null || minX > x ) {
							minX = x;
						}
						if( minY == null || minY > y) {
							minY = y;
						}
						info[id+'.'+position]['bleed_'+ j].points.push({ x: x, y: y });
						info[id+'.'+position]['bleed_'+ j].minX = minX;
						info[id+'.'+position]['bleed_'+ j].minY = minY;
						var bleedPath = SVG.get('bleed-'+ id);
						if( bleedPath.attr('fill-opacity') == 0.4 ) {
							opacity = true;
							bleedPath.attr('fill-opacity', 0);
						}
					}
				});
				canvg('main-canvas', WallCollage.get(), {
					renderCallback: function(){
						var draw = WallCollage.getDraw();
						var mainCanvas = document.getElementById('main-canvas');
						var canvasCollection = $('#canvas-collection');
						canvasCollection.html('');
						var OBJECT = {
										'width' 	 : svgSetup.main.width,
										'height' 	 : svgSetup.main.height,
										'bleed' 	 : svgSetup.main.bleed,
										'imageTotal' : 0,
										'shapes'	 : {}
									};
						var imageWrap = $.inArray(svgSetup.main.wrap, ['natural', 'm_wrap']) != -1 ? true : false;

						if( !imageWrap ) {
							var color;
							if( svgSetup.main.wrap == 'white' ) {
								color = '#ffffff';
							} else if( svgSetup.main.wrap == 'black' ) {
								color = '#000000';
							} else if(  svgSetup.main.wrap.indexOf('#') !== -1 ) {
								color = svgSetup.main.wrap;
							} else {
								color = '#ffffff';
							}
							OBJECT.color = color;
						}
						for(var shapePosition in info) {
							var shapeInfo = info[ shapePosition ];
							for(var shapeName in shapeInfo) {
								var shape = shapeInfo[ shapeName ];
								if( OBJECT.shapes[shapePosition] == undefined ) {
									OBJECT.shapes[shapePosition] = {};
								}
								if( OBJECT.shapes[shapePosition][shapeName] == undefined ) {
									OBJECT.shapes[shapePosition][shapeName] = {};
								}
								OBJECT.shapes[shapePosition][shapeName].points = shape.points;
								var points = shape.points;
								if( shapeName != 'center' ) {
									continue;
								} else {
									var d = '';
									for( var p in points ) {
										if( p == 0 ) {
											d += 'M'+ points[p].x +' '+points[p].y;
										} else {
											d += 'L'+ points[p].x +' '+points[p].y;
										}
									}
									var path = draw.path(d +'Z');
									var minX = shape.minX;
									var minY = shape.minY;
									var w = path.width();
									var h = path.height();
									path.remove();
									canvasCollection.append('<canvas id="canvas-'+ shapePosition +'-'+ shapeName +'" width="'+ w +'" height="'+ h +'"></canvas>');

									var canvas = document.getElementById('canvas-'+ shapePosition +'-'+ shapeName);
									var ctx = canvas.getContext("2d");
									ctx.globalAlpha = 1.00;
								    ctx.drawImage(mainCanvas, minX, minY, w, h, 0, 0, w, h);
								    ctx.restore();
									OBJECT.shapes[shapePosition][shapeName].image = 'canvas-'+ shapePosition +'-'+ shapeName;
								}
							}
						}

						Preview3D.draw(OBJECT);
						Main.previewRenderFinished = true;
						if( opacity ) {
							$('#svg-main .group-bleed .bleed').each(function(){
								this.instance.attr('fill-opacity', 0.4);
							});
						}
					}
				});
				return false;
				var svg = $('#svg_div').html();
				if( opacity ) {
					$('#svg-main .group-bleed .bleed').each(function(){
						this.instance.attr('fill-opacity', 0.4);
					});
				}
				$.ajax({
					url: '{{ URL.'/wall-collage/preview-3d' }}',
					type: 'POST',
					data: {
						info: info,
						svg: svg,
						svg_setup: svgSetup.main,
						id: {{ $product['id'] }}
					},
					success: function(result) {
						Preview3D.draw(result);
					}
				});
			}, afterRenderCallBack);
		},
		removeUpload: function(object) {
			var src = $(object).prev().attr('src');
			if( src == undefined || src.indexOf('{{ URL }}') === -1 ) {
				return false;
			}
			$.ajax({
				url: '{{ URL.'/delete-session-imgs' }}',
				type: 'POST',
				data: {src: src},
				success: function(result) {
					if( result.status == 'ok' ) {
						$(object).parent().fadeOut();
					}
				}
			});
		},
		removeAllUpload: function() {
			$.ajax({
				url: '{{ URL.'/delete-session-imgs' }}',
				type: 'POST',
				data: {src: 'all'},
				success: function(result) {
					if( result.status == 'ok' ) {
						$('#slider_image').html('');
					}
				}
			});
		},
		resolution: function(){
			var selectedImage = WallCollage.getSelectedImage();
			if( selectedImage ) {
				var url = selectedImage.node.getAttribute('href');
				if( url ) {
					$("#dialog_resolution").dialog({width: 900,height: 600}).dialog("open");
					$.ajax({
					    url:"{{URL}}/collections/analyze_image",
					    type:"POST",
					    data:{img: url},
					    success: function(ret){
					        ret = JSON.parse(ret);
					        var html = '';
					        html += '<div id="content">';
					            html += '<div style="float:left; margin-right: 20px">';
					                html += '<img style="width: 385px " src="'+ ret.image +'" />';
					            html += '</div>';
					            html += '<div class="info">';
					            html += ' <ul >';
					                            html += '<li><h2>About your picture: </h2></li>';
					                            html += '<li>Your file size: <b>'+ret.size+'</b> MB </li>';
					                            html += '<li>Your file resolution: <b>'+ret.width+'</b> by <b>'+ret.height+'</b> pixels </li>';
					                            html += '<li><b>'+ret.mp+'</b> Megapixels</li>';
					                html += '</ul>';
					            html += '</div>';
					            html += '<div class="clear"></div>';
					            html += '<table id="result" border="0" cellpadding="0" cellspacing="0">';
					            for(var i in ret.dimensions){
					                html += '<tr>';
					                html += '<td width="220" valign="top" class="txmedium" style="padding:10px;spacing:5px">';
					                html += '<b>'+ret.dimensions[i][0]+'x</b><b>'+ret.dimensions[i][1]+' inches</b>';
					                html += '</td>';
					                html += '<td class="tx2" style="padding:10px;spacing:5px">';
					                html += ret.dimensions[i][3];
					                html += '</td>';
					                html += '</tr>';
					            }
					            html += '</table>';
					        html += '</div>'
					        $("#dialog_resolution").html(html);
					    }
					});
				}
			}
		}
	}
}();
</script>