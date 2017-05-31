<script type="text/javascript" charset="utf-8">
	var flickrToken = undefined;
	var flickrVerifier = undefined;

	function importFlickr(){
		$("#loading_import").show();
		if( flickrToken && flickrVerifier ) {
			flickrgetuserid(flickrToken, flickrVerifier);
		} else {
			flickrauth();
		}
	}
	function flickrauth(){
		var url = undefined;
		$.ajax({
			async: false,
			type: "POST",
			url: '{{URL}}/socials/flickr-auth',
			data:{
				url_callback: '{{URL}}/socials/auth-from/flickr'
			},
			success: function(result){
				if(result.oauth_callback_confirmed == "true"){
					url = "http://www.flickr.com/services/oauth/authorize?oauth_token="+result.oauth_token+"&perms=read";
				} else {
					flickrauth();
				}
			}
		});
		if( url ) {
			window.open(url, 'Flickr Authenticate', 'width=550,height=650');
		}
	}
	function flickrgetuserid(oauth_token,oauth_verifier){
		flickrToken = oauth_token;
		flickrVerifier = oauth_verifier;
		$.ajax({
			type: "POST",
			url: '{{URL}}/socials/flickr-get-user-id',
			data:{
				oauth_token: oauth_token,
				oauth_verifier: oauth_verifier,
			},
			success: function(oResult){
				if(oResult){
					if(oResult.oauth_problem == "signature_invalid" ){
						flickrgetuserid(oauth_token, oauth_verifier);
					}else{
						if(oResult.oauth_problem == "token_used"){
							flickrauth();
						}else{
							$.ajax({
								url: 'https://www.flickr.com/services/rest/?method=flickr.people.getPhotos&api_key={{  Session::get("flickr_app_id")}}&user_id='+oResult.user_nsid+'&format=json&nojsoncallback=1',
								type:"GET",
								success: function(result2){
									$("#loading_import").hide();
									$(".of_album").hide();
									$("[text='List Album']").hide();
									$( "#dialog" ).dialog("open");
									$("#list_image").html('');
									$("#list_image").css('min-height','350px');
									list_photos = result2.photos.photo;
									html = '';
									for(var i=0; i<list_photos.length;i++){
										cover_photo = 'http://farm'+list_photos[i].farm+'.static.flickr.com/'+list_photos[i].server+'/'+list_photos[i].id+'_'+list_photos[i].secret+'_t.jpg';
										source_photo = 'http://farm'+list_photos[i].farm+'.static.flickr.com/'+list_photos[i].server+'/'+list_photos[i].id+'_'+list_photos[i].secret+'.jpg';
										html += '<div class="large-2 columns block_album"><div class="block_image"><img class="cover_album" data-source="'+source_photo+'" src="'+cover_photo+'" onclick="ChooseImage_fb(this)"/></div></div>';
									}
							       	$("#list_image").append(html);
								}
							});
						}
					}
				}else{
					flickrauth();
				}
			}
		});
	}
</script>