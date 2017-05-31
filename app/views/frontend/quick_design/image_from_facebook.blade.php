<script type="text/javascript">
	// ================== Plugin add image from Facebook ======================//
         window.fbAsyncInit = function() {
	        FB.init({
	          appId      : '{{$arr_socail_id["facebook"]}}',
	          xfbml      : true,
	          version    : 'v2.1'
	        });
      };

      (function(d, s, id){
	         var js, fjs = d.getElementsByTagName(s)[0];
	         if (d.getElementById(id)) {return;}
	         js = d.createElement(s); js.id = id;
	         js.src = "//connect.facebook.net/en_US/sdk.js";
	         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));


      	function importFB(){
      		$("#loading_import").css("display","block");
                FB.getLoginStatus(function(response) {
                	  $("#loading_import").css("display","none");
	                  if (response.status === 'connected') {
	                        FB.api("/me/albums",function (list_album) {
	                           if (list_album && !list_album.error) {

	                                $("#list_album").html('');
	                                 $( "#dialog" ).dialog("open");
	                                var list_album = list_album.data;
	                                var i;
	                                for(i=0;i<list_album.length;i++){
	                                	// if(list_album[i].length != undefined && list_album[i].length != 0 ){
	                                        cover_photo_link = 'https://graph.facebook.com/v2.2/'+list_album[i].id+'/picture?redirect=false&access_token='+FB.getAuthResponse()['accessToken'];
	                                        $.ajax({
	                                            url: cover_photo_link,
	                                            type:"GET",
	                                            dataType:"json",
	                                            async:false,
	                                            success:function(data){
	                                                cover_photo = data.data.url;
	                                                html = '<div class="large-2 columns block_album"><div class="block_image" title="'+list_album[i].name+'"><img class="cover_album" data-id="'+list_album[i].id+'" src="'+cover_photo+'" onclick="ChooseAlbum_fb(this)"/></div><div class="block_name">'+list_album[i].name+'</div></div>';
	                                                $("#list_album").append(html);
	                                            }
	                                        });
											$("#list_image").html('');
											$(".of_album").css("display","block");
											$("#list_image").css('min-height','180px');
											$("#loading_import").css("display","none");
	                               		// }
	                                }
	                            }
	                        });
	                  }
	                  else {
	                        FB.login(function(){
	                            FB.api("/me/albums",function (list_album) {
	                               if (list_album && !list_album.error) {
	                                    $("#list_album").html('');
	                                     $( "#dialog" ).dialog("open");
	                                    var list_album = list_album.data;
	                                    var i;
	                                    for(i=0;i<list_album.length;i++){
	                                        // if(list_album[i].count != undefined && list_album[i].count != 0 ){
	                                        	cover_photo_link = 'https://graph.facebook.com/v2.2/'+list_album[i].id+'/picture?redirect=false&access_token='+FB.getAuthResponse()['accessToken'];
	                                            $.ajax({
	                                                url: cover_photo_link,
	                                                type:"GET",
	                                                dataType:"json",
	                                                //async:false,
	                                                success:function(data){
	                                                    cover_photo = data.data.url;
	                                                    html = '<div class="large-2 columns block_album"><div class="block_image" title="'+list_album[i].name+'"><img class="cover_album" data-id="'+list_album[i].id+'" src="'+cover_photo+'" onclick="ChooseAlbum_fb(this)"/></div><div class="block_name">'+list_album[i].name+'</div></div>';
	                                                    $("#list_album").append(html);
	                                                }
	                                            });
												$("#list_image").html('');
												$(".of_album").css("display","block");
												$("#list_image").css('min-height','180px');
												$("#loading_import").css("display","none");
	                                		// }
	                                    }
	                                }
	                            });
	                        }, {scope: 'user_photos'});
	                  }
            	});
      	}
        function ChooseAlbum_fb(obj){
		var id_album = $(obj).attr("data-id");
		FB.api("/"+id_album+"/photos",function (list_photos) {
			if (list_photos && !list_photos.error && list_photos.data.length>0) {
				$("#list_image").html('');
				list_photos = list_photos.data;
				console.log("list_photos: ",list_photos);
				for(i=0;i<list_photos.length;i++){
					cover_photo_link = 'https://graph.facebook.com/v2.2/'+list_photos[i].id+'?fields=source&redirect=false&access_token='+FB.getAuthResponse()['accessToken'];
                    $.ajax({
                        url: cover_photo_link,
                        type:"GET",
                        dataType:"json",
                        //async:false,
                        success:function(data){
                            cover_photo = data.source;
							source_photo = data.source;
							html = '<div class="large-2 columns block_album"><div class="block_image"><img class="cover_album" data-source="'+source_photo+'" src="'+cover_photo+'" onclick="ChooseImage_fb(this)"/></div></div>';
				                           	$("#list_image").append(html);
                        }
                    });
		        }
			}
		});
	}
	
</script>