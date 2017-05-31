<script>
	var instagram_id = '{{$arr_socail_id["instagram"]}}';
	var instagramToken = undefined;
	$("#import_instagram").on("click",function(){
		$("#loading_import").css("display","block");
		checkAuth_insta();
	});

	function checkAuth_insta(token){
		if( token ) {
			instagramToken = token;
		}
		if( instagramToken == undefined ) {
			window.open('https://instagram.com/oauth/authorize/?client_id='+instagram_id+'&redirect_uri={{ URL }}/socials/auth-from/instagram&response_type=token', 'Instagram Authenticate', 'width=550,height=650');
		} else {
			$.ajax({
				url:'https://api.instagram.com/v1/users/self/media/recent?access_token='+instagramToken,
				type:'GET',
				 dataType: "jsonp",
				success:function(resp){
					$("#loading_import").hide();
					$(".of_album").hide();
					$("[text='List Album']").hide();
					$( "#dialog" ).dialog("open");
					$("#list_image").html('');
					$("#list_image").css('min-height','350px');
					list_image = resp.data;
					for (i in list_image){
						image = list_image[i];
						if(image.type ='image' && typeof image.videos == 'undefined'){
							cover_photo = image.images.thumbnail.url;
							source_photo =  image.images.standard_resolution.url;
							html = '<div class="large-2 columns block_album"><div class="block_image"><img class="cover_album" data-source="'+source_photo+'" src="'+cover_photo+'" onclick="ChooseImage_fb(this)"/></div></div>';
				   			$("#list_image").append(html);
						}
					}
				}
			});
		}
	}
</script>