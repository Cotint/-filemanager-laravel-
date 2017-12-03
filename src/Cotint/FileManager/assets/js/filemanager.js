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
