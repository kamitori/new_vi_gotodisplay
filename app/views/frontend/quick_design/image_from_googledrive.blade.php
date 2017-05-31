 <script type="text/javascript">
      var CLIENT_ID = "{{$arr_socail_id['googledrive']}}";

      function importGoogledrive(){
        $("#loading_import").css("display","block");
        checkAuth_ggdrive();
      }
      var SCOPES = 'https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/drive.readonly https://www.googleapis.com/auth/drive.metadata.readonly https://www.googleapis.com/auth/drive.appdata https://www.googleapis.com/auth/drive.apps.readonly';

      /**
       * Check if the current user has authorized the application.
       */
      function checkAuth_ggdrive() {
        gapi.auth.authorize(
            {'client_id': CLIENT_ID, 'scope': SCOPES, 'immediate': true},
            handleAuthResult_ggdrive);
      }

      /**
       * Called when authorization server replies.
       *
       * @param {Object} authResult Authorization result.
       */
      function handleAuthResult_ggdrive(authResult) {
        if (authResult && !authResult.error) {
        	   get_list_image();
        } else {
              gapi.auth.authorize(
                  {'client_id': CLIENT_ID, 'scope': SCOPES, 'immediate': false},
                  handleAuthResult);
        }
      }
function get_list_image(){
	gapi.client.load('drive', 'v2', function() {
		var request  = gapi.client.drive.files.list({
			// q:"properties has {shared:true} and mimeType contains 'image'"
			q:"mimeType contains 'image'"
		});
		request.execute(function(list_image) {
			console.log
		     list_image = list_image.items;
		     $("#loading_import").css("display","none");
		     $(".of_album").css("display","none");
		     $("[text ='List Album']").css("display","none");
		     $("#loading_import").css("display","none");
		     $( "#dialog" ).dialog("open");
		     $("#list_image").html('');
		     $("#list_image").css('min-height','350px');
		     for(i=0;i<list_image.length;i++){
		     	if(list_image[i].shared){
			     	// source_photo = 'https://drive.google.com/uc?export=&confirm=no_antivirus&id='+list_image[i].id;
			     	source_photo = list_image[i].webContentLink;
			     	cover_photo = list_image[i].thumbnailLink;
			     	ext = list_image[i].mimeType;
			     	html = '<div class="large-2 columns block_album"><div class="block_image"><img class="cover_album" data-source="'+source_photo+'" src="'+cover_photo+'" onclick="ChooseImage_fb(this)" data-ext="'+ext+'"/></div></div>';
			     	$("#list_image").append(html);
			}
		     }
		});
	});
}


     
    </script>
    <script type="text/javascript" src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>