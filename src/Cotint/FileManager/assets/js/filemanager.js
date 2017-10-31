

$(function(){

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

    $(document).find('.modal .modal-content .files-list').load('/filemanager/index');
    $('.fileManager').attr('data-toggle','modal');
    $('.fileManager').attr('data-target','#fileManagerModal');

    $.each($('.fileManager'), function(index, item){
        $('<div class="images-list"></div>').insertAfter(this);
        $(this).click(function(){
            fileMangerButton = this;
            console.log(fileMangerButton);
        });
    });

    $(document).on('click','.select-image',function(){
        var selected_images = $('.dz-image img.selected');
        $.each(selected_images, function(key, value){
            var image_url = $(value).attr('src');
            var image_id =  $(value).attr('data-id');
            var alt = $('#filemanager-file-alt').val();
            var title = $('#filemanager-file-title').val();
            var description = $('#filemanager-file-desc').val();

            $(value).attr('data-alt', alt);
            $(value).attr('data-desc', description);
            $(value).attr('data-title', title);

            var field_name = $(fileMangerButton).attr('data-name') !== undefined ? $(fileMangerButton).attr('data-name'):'file';
            console.log('aaaaaaaaaaaaaaaaaaaa');
            console.log(field_name);
            if ($(fileMangerButton).attr('data-type') === 'multi'){
                var order = $(fileMangerButton).next('.images-list').find('img').length;
                $(fileMangerButton).next('.images-list').append('<div class="col-md-3"><img src="'+image_url+'" height="100px">' +
                    '<button type="button" class="btn btn-success filemanager-remove-image"><i class="glyphicon glyphicon-trash"></i> </button> ' +
                    '<input type="hidden" name="'+field_name+'['+image_id+'][url]" value="'+image_url+'"/> ' +
                    '<input type="hidden" name="'+field_name+'['+image_id+'][id]" value="'+image_id+'"/> ' +
                    '<input type="hidden" name="'+field_name+'['+image_id+'][name]" value="'+title+'"/> ' +
                    '<input type="hidden" name="'+field_name+'['+image_id+'][alt]" value="'+alt+'"/> ' +
                    '<input type="hidden" name="'+field_name+'['+image_id+'][description]" value="'+description+'"/> ' +
                    '<input type="text" name="'+field_name+'['+image_id+'][order]" value="'+order+'"/> ' +
                    '</div>'
                );
            } else {
                $(fileMangerButton).next('.images-list').html('<div class="col-md-3"><img src="'+image_url+'" height="100px">' +
                    '<button type="button" class="btn btn-success filemanager-remove-image"><i class="glyphicon glyphicon-trash"></i> </button> ' +
                    '<input type="hidden" name="'+field_name+'_url" value="'+image_url+'"/> ' +
                    '<input type="hidden" name="'+field_name+'_id" value="'+image_id+'"/> ' +
                    '<input type="hidden" name="'+field_name+'_name" value="'+title+'"/> ' +
                    '<input type="hidden" name="'+field_name+'_alt" value="'+alt+'"/> ' +
                    '<input type="hidden" name="'+field_name+'_description" value="'+description+'"/> ' +
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
    });

    $(document).on('click','.filemanager-remove-image',function(e){
        if (confirm('Are you sure?')){
            $(this).parent().remove();
        }
    })
});