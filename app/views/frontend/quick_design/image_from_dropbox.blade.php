<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="{{$arr_socail_id['dropbox']}}"></script>
<script type="text/javascript" charset="utf-8">
	var options = {
		// Required. Called when a user selects an item in the Chooser.
		success: function(files) {
			$("#loading_wait").show();
			link_img = files[0].link;
			link_img = link_img.replace('dl=0','dl=1');
			$.ajax({
				url: '{{URL}}/socials/get-image',
	                		type: 'POST',
	                		data:{
	                			link:link_img
	                		},
	                		async:false,
	                		dataType:'json',
	                		success: function(result){
	                			if(result.error==0){
	                				changeImage(result.data);
	                				update_img_upload(result.data);
	                			}else{
	                				alert("Error save image");
	                			}
	                		}
			})
			$("#loading_wait").hide();
		},

		// Optional. Called when the user closes the dialog without selecting a file
		// and does not include any parameters.
		cancel: function() {

		},

		// Optional. "preview" (default) is a preview link to the document for sharing,
		// "direct" is an expiring link to download the contents of the file. For more
		// information about link types, see Link types below.
		linkType: "preview", // or "direct"

		// Optional. A value of false (default) limits selection to a single file, while
		// true enables multiple file selection.
		multiselect: false, // or true

		// Optional. This is a list of file extensions. If specified, the user will
		// only be able to select files with these extensions. You may also specify
		// file types, such as "video" or "images" in the list. For more information,
		// see File types below. By default, all extensions are allowed.
		extensions: ['.jpeg','.jpg','.png','.bmp','.gif','.svg'],
	};
	function importDropbox(){
		$("#loading_import").css("display","none");
		Dropbox.choose(options);
	}

</script>