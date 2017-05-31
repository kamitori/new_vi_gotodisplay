<script>
	$("#content_my_upload img").draggable({
		helper: "clone",
     		revert: "invalid"
	});
	$("#svg_main").droppable({
		accept:"#content_my_upload img",
		drop: function( event, ui ) {
			changeImage(ui.draggable.attr("src"));
		}
	});

	$(function() {
        if(objImg.url!=''){
            $img = new Image();
            $img.src = objImg.url;
            $img.onload = function(){
                var width = this.width;
                var height = this.height;
                check_list_quality(width,height);
            }
        }
    });
</script>
<style type="text/css">
	.quick_design .content .left_content .ui-draggable-dragging{
		width:auto !important;
		max-width:200px !important;
		max-height:200px !important;
		margin-top: -150px !important;
		margin-left: -50px !important;
		cursor: move;
		z-index: 30;
	}
	
</style>