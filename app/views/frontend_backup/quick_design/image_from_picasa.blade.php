 <script type="text/javascript">
      var CLIENT_ID = "{{$arr_socail_id['googledrive']}}";

      $("#import_picasa").on("click",function(){
      	$("#loading_import").css("display","block");
            checkAuth();
      });
      var SCOPES = 'https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/drive.readonly https://www.googleapis.com/auth/drive.metadata.readonly https://www.googleapis.com/auth/drive.appdata https://www.googleapis.com/auth/drive.apps.readonly https://www.googleapis.com/auth/plus.me';

      /**
       * Check if the current user has authorized the application.
       */
      function checkAuth() {
        gapi.auth.authorize(
            {'client_id': CLIENT_ID, 'scope': SCOPES, 'immediate': true},
            handleAuthResult);
      }

      /**
       * Called when authorization server replies.
       *
       * @param {Object} authResult Authorization result.
       */
      function handleAuthResult(authResult) {
        if (authResult && !authResult.error) {
              get_list_album();
        } else {
              gapi.auth.authorize(
                  {'client_id': CLIENT_ID, 'scope': SCOPES, 'immediate': false},
                  handleAuthResult);
        }
      }


function get_list_album(){
	gapi.client.load('plus', 'v1', function() {
		var request = gapi.client.plus.people.get({
		  'userId' : 'me'
		});

		request.execute(function(resp) {
			$.ajax({
				url:'https://picasaweb.google.com/data/feed/api/user/'+resp.id+'?alt=json&kind=album&access=visible',
				type:"GET",
				success:function(resp){
					console.log();
					$.ajax({
						url:resp.feed.link[0].href,
						type:"GET",
						success:function(resp){
	                                                 		if(resp.feed.entry.length>0){
	                                                 			$("#list_album").html('');
	                                                 			$( "#dialog" ).dialog("open");
								for(i in resp.feed.entry){
								     album = resp.feed.entry[i];
								     cover_photo = album.media$group.media$thumbnail[0].url.replace('s160-c','s160');
								     data_source = album.link[0].href;
								     name_album = album.title.$t;

			                                                            html = '<div class="large-2 columns block_album"><div class="block_image" title="'+name_album+'"><img class="cover_album" data-source="'+data_source+'" src="'+cover_photo+'" onclick="ChooseAlbum_picasa(this)"/></div><div class="block_name">'+name_album+'</div></div>';
			                                                            $("#list_album").append(html);
								}
								$("#list_image").html('');
								$(".of_album").css("display","block");
								$("#list_image").css('min-height','180px');
								$("#loading_import").css("display","none");
							}
						}
					});
				}
			})
		});
	})
}

function ChooseAlbum_picasa(obj){
	var link_album  =  $(obj).attr("data-source");
	console.log(link_album)
	console.log(obj);
	$.ajax({
		url:link_album,
		type:"GET",
		success:function(resp){
			$("#list_image").html('');
			for(i in resp.feed.entry){
			     album = resp.feed.entry[i];
			     cover_photo = album.media$group.media$thumbnail[album.media$group.media$thumbnail.length-1].url;
			     source_photo = album.content.src.replace(album.title.$t,'s0/'+album.title.$t);
			     ext = album.content.type;
			     html = '<div class="large-2 columns block_album"><div class="block_image"><img class="cover_album" data-source="'+source_photo+'" src="'+cover_photo+'" onclick="ChooseImage_fb(this)" data-ext="'+ext+'"/></div></div>';
		                $("#list_image").append(html);
			}
		}
	})
}


     
    </script>
    <script type="text/javascript" src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>