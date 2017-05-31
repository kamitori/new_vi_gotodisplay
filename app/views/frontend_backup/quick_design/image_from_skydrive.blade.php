<script src="//js.live.net/v5.0/wl.js"></script>
<script>
	<?php
		$arr_socail_id['skydrive'] = Configure::where('ckey','=','skydrive_app_id')->pluck("cvalue");
	?>
	var client_id_skydrive = "{{isset($arr_socail_id['skydrive'])?$arr_socail_id['skydrive']:''}}";
	var scope = ["wl.signin","wl.offline_access","onedrive.readonly","onedrive.readwrite"];
	var response_type="token";
	var redirect_uri = '{{ URL }}/socials/import-skydrive';


	$('#import_skydrive').on("click",function(){
		$("#loading_import").css("display","block");
		WL.init({
		    client_id: client_id_skydrive,
		    redirect_uri: redirect_uri,
		    scope:scope,
		    response_type: response_type
		});
		skydriveauth();
	})
	var access_token =''
	function skydriveauth(){
		WL.login({
		        scope : ["wl.signin","wl.offline_access","onedrive.readonly","onedrive.readwrite"]
		    }).then(
		        function(response){
		        		access_token = response.session.access_token;
		        		$.ajax({
		        			url:'https://api.onedrive.com/v1.0/drive/root/children?access_token='+access_token,
		        			type:'GET',
		        			success:function(list_items){
		        				$("#loading_import").css("display","none");
						$(".of_album").css("display","none");
						$("[text='List Album']").css("display","none");
						$( "#dialog" ).dialog("open");
						$("#list_image").html('');
						$("#list_image").css('min-height','350px');
		        				list_items = list_items.value;
		        				for(i in list_items){
		        					item = list_items[i];
		        					if(item.folder){
		        						name =item.name;
		        						html = '<div class="large-2 columns block_album" onclick="Choosefolder_skydrive(this)" data-parent="'+item.parentReference.id+'" data-id="'+item.id+'" title="'+name+'"><div class="block_image" ></div><div class="block_name" style="background:#33e;height:150px;padding:10px;color:#fff;">'+name+'</div></div>';
		        						 $("#list_image").append(html);
		        					}else{
			        					if(item.file && item.file.mimeType.indexOf("image")>-1){
			        						cover_photo =  item["@content.downloadUrl"];
									source_photo = item['@content.downloadUrl'];
									html = '<div class="large-2 columns block_album"><div class="block_image"><img class="cover_album" data-source="'+source_photo+'" src="'+cover_photo+'" onclick="ChooseImage_fb(this)"/></div></div>';
						                           	$("#list_image").append(html);
			        					}
			        				}
		        				}
		        			}
		        		})
			}
		    );
	}

	function Choosefolder_skydrive(obj){
		var id = $(obj).attr("data-id");
		var id_parent =  $(obj).attr("data-parent");
		$.ajax({
			url:'https://api.onedrive.com/v1.0/drive/items/'+id+'/children?access_token='+access_token,
			data:'GET',
			success:function(list_items){
				$("#list_image").html('');
				if(id_parent !=''){
					html = '<div class="large-2 columns block_album" onclick="Choosefolder_skydrive(this)" data-parent="" data-id="'+id_parent+'" title="Backward"><div class="block_image" ></div><div class="block_name" style="background:#33e;height:150px;padding:10px;color:#fff;"><span style="font-size:200%; font-weight:bold;">&crarr;</span><br/>Back</div></div>';
			        		$("#list_image").append(html);
				}
				list_items = list_items.value;
				console.log(list_items);
				for(i in list_items){
        					item = list_items[i];
        					if(item.folder){
        						console.log(item);
        						name =item.name;
        						html = '<div class="large-2 columns block_album" onclick="Choosefolder_skydrive(this)" data-parent="'+item.parentReference.id+'" data-id="'+item.id+'"  title="'+name+'"><div class="block_image"></div><div class="block_name" style="background:#33e;height:150px;padding:10px;color:#fff;">'+name+'</div></div>';
		        				$("#list_image").append(html);
        					}else{
	        					if(item.file && item.file.mimeType.indexOf("image")>-1){
	        						cover_photo =  item["@content.downloadUrl"];
							source_photo = item['@content.downloadUrl'];
							html = '<div class="large-2 columns block_album"><div class="block_image"><img class="cover_album" data-source="'+source_photo+'" src="'+cover_photo+'" onclick="ChooseImage_fb(this)"/></div></div>';
				                           	$("#list_image").append(html);
	        					}
	        				}
        				}
			}
		});
	}

</script>