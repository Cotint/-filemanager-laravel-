Dropzone.autoDiscover = false;
var url,type , prevType;
var config = {};
var cnt = 1;
var fileMangerButton;

$(function(){
    kamaDatepicker('datepicker');

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

    $('.fileManager').click(function(){
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

        $(document).find('.modal .modal-content .files-list').load('/filemanager/index');
    });

    $.each($('.fileManager'), function(index, item){
        $(this).wrap('<div class="file-manager-wrapper'+index+'"></div>');
        if ($(this).parent().find('.images-list').length === 0){
            $('<div class="images-list"></div>').appendTo($(this).parent());
        }

    });

    var prev_images = $('.prev-images');
    $('span.image').addClass('hidden');

    $.each(prev_images, function(index, item){
        var images = $('span.image', this);
        showImages(images, $('[data-name="'+$(this).attr("data-target")+'"]'));
    });

});


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
