Dropzone.autoDiscover = false;
var url,type , prevType;
var config = {};
$(function(){
    kamaDatepicker('datepicker');
    var fileMangerButton;

    var modal = '\
        <div class="modal fade bs-example-modal-lg " id="fileManagerModal" tabindex="-1" role="dialog" aria-labelledby="fileManagerModal"> \
            <div style="width: 1303px !important;" class="modal-dialog modal-lg" role="document"> \
                <div class="modal-content"> \
                 <div  class="files-list"></div> \
                </div> \
                    <button class="btn btn-close" data-dismiss="modal" aria-hidden="true">بستن</button> \
                    <button class="btn btn-primary select-image btn-save"  data-dismiss="modal">ذخیره کردن</button> \
            </div> \
        </div> ';
    $(modal).appendTo('body');


    $('.fileManager').attr('data-toggle','modal');
    $('.fileManager').attr('data-target','#fileManagerModal');

    $.each($('.fileManager'), function(index, item){
        $(this).wrap('<div class="file-manager-wrapper'+index+'"></div>');
        if ($(this).parent().find('.images-list').length === 0){
            $('<div class="images-list"></div>').appendTo($(this).parent());
        }

        $(this).click(function(){
            fileMangerButton = this;

            type = $(fileMangerButton).attr('data-filetype');
            if (type === undefined){
                type = 'all';
            }

            /**
             * Prevent from loading previous files list
             */
            if (type === prevType) {
                return true;
            }

            loadModal(fileMangerButton);
        });
    });
});



function fillDropzone(data, dropzone){
    $.each(data, function(key,value){ //loop through it
        var mockFile = {
            name: value.name,
            size: value.size,
            accepted:true,
            id:value.id,
            title:value.title,
            desc:value.desc,
            alt:value.alt
        };
        dropzone.emit("addedfile", mockFile);
        dropzone.files.push(mockFile);
        dropzone.emit("thumbnail", mockFile, mockFile.name);
        dropzone.emit("complete", mockFile);
    });
}




function loadModal(fileMangerButton) {

    console.log($(fileMangerButton).attr('data-type'));
    $(document).find('.modal .modal-content .files-list').load('/filemanager/index', function () {
        prevType = type;

        dropzone = new Dropzone("div#dropzone", {
            url: "/filemanager/upload",
            clickable: '.upload-button'
        });

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
        $('.drop-col').append('<div class="loading" style="text-align: center;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>');
        $.getJSON('/filemanager/'+url, function(data) { // get the json response
            $('.drop-col').find('.loading').remove();
            fillDropzone(data, dropzone);
        });


        dropzone.on('init', function(){

        });
        dropzone.on("success", function (file, data) {
            $(file.previewElement).find('.dz-image img').attr('data-id',JSON.parse(data).id);
            $(file.previewElement).find('.dz-image img').attr('src',JSON.parse(data).filename);
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
                $.getJSON('/filemanager/all', function(data) { // get the json response
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


        $(fileMangerButton).on('click','.select-image',function(){
            var selected_images = $('.dz-image img.selected');

            console.log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
            console.log(selected_images);
            // console.log(fileMangerButton);
            showImages(selected_images, fileMangerButton);
        });



        $(document).on('click','.filemanager-remove-image',function(e){
            if (confirm('Are you sure?')){
                $(this).parent().remove();
            }
        });

        var prev_images = $('.prev-images');
        $('span.image').addClass('hidden');

        $.each(prev_images, function(index, item){
            var images = $('span.image', this);
            showImages(images, $('[data-name="'+$(this).attr("data-target")+'"]'));
        });


    });

}

function showImages(images, fileManagerButton){
    $.each(images, function(key, value){
        var image_url = $(value).attr('src');
        var image_id =  $(value).attr('data-id');

        var alt = $('#filemanager-file-alt').val();
        var title = $('#filemanager-file-title').val();
        var description = $('#filemanager-file-desc').val();
        console.log('wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww');
        console.log($(fileManagerButton).parent());

        var field_name = $(fileManagerButton).attr('data-name') !== undefined ? $(fileManagerButton).attr('data-name'):'file';
        if ($(fileManagerButton).attr('data-type') === 'multi'){
            var order = $(fileManagerButton).parent().find('.images-list').find('img').length;
            $(fileManagerButton).parent().find('.images-list').append('' +
                '<div class="col-md-3">' +
                '<img src="'+image_url+'" height="100px">' +
                '<button type="button" class="btn filemanager-remove-image"><i class="glyphicon glyphicon-trash"></i> </button> ' +
                '<input type="hidden" name="'+field_name+'['+image_id+'][id]" value="'+image_id+'"/> ' +
                '<input type="hidden" name="'+field_name+'['+image_id+'][src]" value="'+image_url+'"/> ' +
                '<input type="text" name="'+field_name+'['+image_id+'][order]" value="'+order+'"/> ' +
                '</div>'
            );
        } else {
            $(fileManagerButton).parent().find('.images-list').html('<div class="col-md-3"><img src="'+image_url+'" height="100px">' +
                '<button type="button" class="btn btn-success filemanager-remove-image"><i class="glyphicon glyphicon-trash"></i> </button> ' +
                '<input type="hidden" name="'+field_name+'_id" value="'+image_id+'"/> ' +
                '<input type="hidden" name="'+field_name+'_src" value="'+image_url+'"/> ' +
                '</div>'
            );
        }

        $.post('/filemanager/save-meta',{
            id:image_id,
            description:description,
            title:title,
            alt:alt
        },function(data){

        });
    });
}

