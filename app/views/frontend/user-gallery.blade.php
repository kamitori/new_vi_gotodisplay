@section('body')
<body class="page-wristwear template-collection">
@stop
<div class="breadcrumbs" style="margin-bottom: 10px;">
    <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
        <ul class="unstyled-list">
        <li>YOUR GALLERY</li>
    </ul>
</div>
<div class="col-md-12" style="margin: 0;padding: 0;">
    <div class="short_content">
        <div class="short_content_icon">
            <img src="/assets/vitheme/images/icon022.jpg" alt="" />
        </div>
        <div class="short_content_cont">
            <b style="font-size: 12px;">You only have {{$max_storage}}MB of your storage</b><br />
            <span style="font-size: 12px;">Your images will be display in ImageStylor Design Tools when you choose a product</span>
        </div>
        <div class="short_content_right">
            <form id="upload_file" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                    <label for="fileup" class="short_content_bt"></label>
                    <input class="short_content_file" type="file"  name="upload_file" id="fileup" accept="image/*" style="visibility: hidden;">
                <input type="submit" class="btn btn-4 btn-white" value="Upload" id="upload_file_bt">
                <br>
            </form>
        </div>
    </div>
</div>

<div class="col-md-12" style="margin: 0;padding: 0;">
    <div class="gallery-grid">
        @foreach($images as $img)
        <div class="gallery-item col-md-2 col-xs-2">
            <div class="gallery-image-wrapper" style="opacity: 1;">
                <a href="#">
                    <img src="{{ URL.$img }}" alt="" />
                </a>
            </div>
            <div class="caption">
                <p class="title">
                    <a class="remove_img" href="#" rel="{{ $img }}">
                        REMOVE
                    </a>
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>

@section('pageJS')
<script type="text/javascript">
    $("document").ready(function(){
        $("#fileup").change(function(event){
            uploadFiles(event,0,1);
        });

        // Remove image from gallery
        remove_image();

    });
    function remove_image(){
        $(".remove_img").click(function(){
            $.ajax({
                    url: '/user/remove-image-gallery',
                    type: 'POST',
                    data: {file:$(this).attr("rel")},
                    cache: false,
                    success: function(result){
                        location.reload();
                    }
                });
        });
    }
    function uploadFiles(event,items,add){
        var files = event.target.files;
        var check =  true;
        for( var i = 0; i < files.length; i++ ) {
            var f = files[i];
            if (!f.type.match('image.*')) {
                alert("please upload image file");
                continue;
            }
            $('#loading_wait_2').show();
            $("#loading_p_bar").css('width','50%');
            $("#loading_p_bar").html('50%');
            var reader = new FileReader();
            reader.readAsDataURL(f);
            $arr_remove = [];
            reader.onload = function(e){
                var src = e.target.result;
                var img = new Image();
                img.src = src;
                img.onload = function(){
                    var data = new FormData();
                    $.each(files, function(key, value){
                        data.append(key, value);
                    });
                    $.ajax({
                        url: '/collections/gettheme/saveimg',
                        type: 'POST',
                        data: data,
                        cache: false,
                        dataType: 'json',
                        processData: false, // Don't process the files
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        success: function(result){
                            // location.reload();                            
                            var temp_url = result.data[0].full_url;
                            var i = 50
                            var timeout = 45;
                            (function progressbar()
                            {
                                i++;
                                if(i <= 100)
                                {
                                    temp = i + '%';
                                    iterateProgressBar(temp);
                                    setTimeout(progressbar, timeout);
                                    if(i==100){
                                        $('#loading_wait_2').hide();
                                        var html = [
                                            '<div class="gallery-item col-md-2 col-xs-2">',
                                        '<div class="gallery-image-wrapper" style="opacity: 1;">',
                                            '<a href="#">',
                                                '<img src="'+ '{{url()}}' +temp_url+'" alt="">',
                                            '</a>',
                                        '</div>',
                                        '<div class="caption">',
                                            '<p class="title">',
                                            '<a class="remove_img" href="#" rel="'+temp_url+'">',
                                                    'REMOVE</a>',
                                        '</p></div></div>'];
                                        html = html.join("");
                                        $('.gallery-grid').append(html);
                                        remove_image();
                                    }
                                }
                            }
                            )();                   
                        }
                    });
                };
            };
        }
    }
     function iterateProgressBar(temp){
        $("#loading_p_bar").css('width',temp);
        $("#loading_p_bar").html(temp);
    }
</script>
<style type="text/css">
    .gallery-image-wrapper a img{
        max-height: 100px;
    }
</style>
@stop