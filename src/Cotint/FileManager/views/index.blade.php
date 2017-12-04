@extends('fileManager::layout')

@section('content')
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-3 col-xs-3 form-design">
            <div class="meta-data margin-top">
                <form>
                    <div class="form-group">
                        <label>نام فایل</label>
                        <input disabled class="form-control" type="text" name="name" id="filemanager-file-name" placeholder="نام">
                    </div>
                    <div class="form-group">
                        <label>عنوان</label>
                        <input class="form-control" type="text" name="title" id="filemanager-file-title" placeholder="عنوان">
                    </div>
                    <div class="form-group">
                        <label>متن جایگزین :</label>
                        <input class="form-control"  type="text" name="alt" id="filemanager-file-alt" placeholder="متن جایگزین">
                    </div>
                    <div class="form-group">
                        <label> توضیحات :</label>
                        <textarea  class="form-control"  type="text" name="description" id="filemanager-file-desc" placeholder="توضیحات"></textarea>
                    </div>
                    <button type="button" class="btn btn-default filemanager-remove-image"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
        <div class="col-md-9 col-xs-9">
            <div class="row">
                <div class="col-md-3 col-xs-3">
                    <div class="form-group margin-top-20">
                        <select class="form-control" id="media-type">
                            <option value="all">همه موارد رسانه ها</option>
                            <option value="videos">فیلم</option>
                            <option value="images">عکس</option>
                            <option value="archives">فایلهای فشره</option>
                            <option value="docs">مستندات</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-3">
                    <input class="input-date" type="text" id="test-date-id">

                </div>
                <div class="col-md-3 col-xs-3">
                <form class="margin-top-20" id="filemanager-search-form" method="post" action="/filemanager/search">
                    <input class="form-control" type="text" id="filemanager-search-input" placeholder="جستجو..." />
                </form>
                </div>
                <div class="col-md-3 col-xs-3">
                    <button class="btn btn-default btn-class upload-button" type="button"> <i class="fa fa-plus" aria-hidden="true"></i> لطفا عکس خود را اپلود کنید </button>
                </div>
                <div class="col-md-12 col-xs-4">
                    <div class="message text-center">

                    </div>
                    <div class="drop-col" id="dropzone">

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
<script>

    $(function(){

        prevType = type;

        var fileTypes = ['image','video','document','archive'];


        if (type !== undefined && fileTypes.indexOf(type) !== -1){
            if (type === 'image') {
                url = 'images';
            } else if (type === 'video') {
                url = 'videos';
            }else if (type === 'document') {
                url = 'docs';
            }else if (type === 'archive') {
                url = 'archives';
            }
            $(document).find('#media-type').val(url);
            $(document).find('#media-type').attr('disabled',true);
        } else {
            url = 'all';
        }


        dropzone = new Dropzone("div#dropzone", {
            url: "/filemanager/upload/"+ type,
            clickable: '.upload-button'
        });


        $('.drop-col').append('<div class="loading" style="text-align: center;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>');
        $.getJSON('/filemanager/'+url, function(data) { // get the json response
            $('.drop-col').find('.loading').remove();
            fillDropzone(data, dropzone);
        });



        dropzone.on('init', function(){

        });
        dropzone.on("success", function (file, data) {
            $('.message').hide();//.css('color','red').html(response.message);
            console.log('aaaaaaaaaaaa');
            console.log(data);
            $(file.previewElement).find('.dz-image img').attr('data-id',JSON.parse(data).id);
            $(file.previewElement).find('.dz-image img').attr('src',JSON.parse(data).filename);
        });
        dropzone.on("error", function (file, response) {
            console.log(file);
            $(file.previewElement).remove();
            $('.message').css('color','red').html(response.message);
            return false;
        });


        dropzone.on("addedfile", function (file) {
            var _this = this;

            var removeButton = Dropzone.createElement("<button data-dz-remove " +
                "class='del_thumbnail btn div-class hidden'><i class='fa fa-trash check-id'></i></button>");



            removeButton.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                var server_file = $(file.previewTemplate).children('.server_file').text();
                if (confirm("Are you sure?")){
                    var server_file = $('#filemanager-file-name').val();
                    $.post("/filemanager/delete", { file: server_file }, function(){
                        _this.removeFile(file);
                    });
                }

            });
            $(file.previewElement).addClass('col-md-2');
            file.previewElement.appendChild(removeButton);
            $(file.previewElement).find('.dz-image img').css('width','50px');
            $(file.previewElement).find('.dz-image img').attr('data-id',file.id);
            $(file.previewElement).find('.dz-image img').attr('data-name',file.name);
            $(file.previewElement).find('.dz-image img').attr('data-title',file.title);
            $(file.previewElement).find('.dz-image img').attr('data-desc',file.desc);
            $(file.previewElement).find('.dz-image img').attr('data-alt',file.alt);
        });


        $(document).on('click','.dz-image', function(){

            console.log('type')
            console.log($(fileMangerButton).attr('data-type'));
            $(".dz-image").css("border","1px solid #ccc");
            $(this).css("border","3px solid #000");
            $('.div-class').addClass('hidden');
            $('img.selected').removeClass('selected');
            $('.dz-image.dz-selected').removeClass('dz-selected');

            $(this).parent().find('.div-class').removeClass('hidden');
            $(this).addClass('dz-selected');
            $(this).find('img').addClass('selected');
            $('#filemanager-file-name').val($(this).find('img').attr('data-name'));
            $('#filemanager-file-title').val($(this).find('img').attr('data-title'));
            $('#filemanager-file-desc').val($(this).find('img').attr('data-desc'));
            $('#filemanager-file-alt').val($(this).find('img').attr('data-alt'));
        });

        $(document).on('change', '#media-type',function(){
            var type = $(this).find('option:selected').val();
            dropzone.removeAllFiles(true);
            $('.drop-col').append('<div class="loading" style="text-align: center;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>');

            $.getJSON('/filemanager/'+type, function(data) { // get the json response
                $('.drop-col').find('.loading').remove();
                fillDropzone(data, dropzone);
            });
        })

        $(document).on('keyup', 'input#filemanager-search-input',function(){
            var query = $(this).val();

            if (query.length < 3 && query.length >0){
                return;
            }
            dropzone.removeAllFiles(true);
            $('.drop-col').append('<div class="loading" style="text-align: center;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>');


            if (query.length === 0){
                $.getJSON('/filemanager/'+url, function(data) { // get the json response
                    $('.drop-col').find('.loading').remove();
                    fillDropzone(data, dropzone);
                });
                return;
            }

            $.getJSON('/filemanager/search', {query: query}, function(data) { // get the json response
                $('.drop-col').find('.loading').remove();
                fillDropzone(data, dropzone);
            });
        });



        $('.select-image').on('click',function(){
            var selected_images = $('.dz-image img.selected');
            cnt += 1;
            console.log(cnt);
            // console.log(fileMangerButton);
            showImages(selected_images, fileMangerButton);
        });


    });


</script>
@stop