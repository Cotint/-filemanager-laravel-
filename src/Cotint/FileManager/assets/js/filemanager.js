

$(function(){

    $('#fileManager').click(function(e){
        e.preventDefault();

    });


    $(document).on('submit','form#filemanager-search-form', function(e){

        $.post($(this).attr('action'),{q: $(this).find('#filemanager-search-input')}, function(data){

        });
        return false;
    });

    var modal = '\
    <div class="images-list"></div> \
    <div class="modal fade bs-example-modal-lg" id="fileManagerModal" tabindex="-1" role="dialog" aria-labelledby="fileManagerModal"> \
        <div style="width: 1303px !important;" class="modal-dialog modal-lg" role="document"> \
            <div class="modal-content"> \
             <div  class="files-list"></div> \
            </div> \
             <div class="modal-footer"> \
                <button class="btn" data-dismiss="modal" aria-hidden="true">بستن</button> \
                <button class="btn btn-primary select-image"  data-dismiss="modal">ذخیره کردن</button> \
              </div>\
        </div> \
    </div> ';
    $(modal).insertAfter('.fileManager');
    $('.fileManager').attr('data-toggle','modal');
    $('.fileManager').attr('data-target','#fileManagerModal');
    $(this).find('.modal-content .files-list').load('/filemanager/index');

    $('#fileManagerModal').on('shown.bs.modal', function () {
        // $(this).find('.modal-content .files-list').load('/filemanager/index');
    });

    $(document).on('click','.select-image',function(){
        var selected_images = $('.dz-image img.selected');
        $.each(selected_images, function(key, value){
            console.log(value);


            var image_url = $(value).attr('src');
            var image_id =  $(value).attr('data-id');
            var alt = $('#filemanager-file-alt').val();
            var title = $('#filemanager-file-title').val();
            var description = $('#filemanager-file-desc').val();

            $(value).attr('data-alt', alt);
            $(value).attr('data-desc', description);
            $(value).attr('data-title', title);
            if ($('.fileManager').attr('data-type') === 'multi'){
                $('.images-list').append('<div class="col-md-3"><img src="'+image_url+'" height="100px">' +
                    '<button type="button" class="btn btn-success filemanager-remove-image"><i class="glyphicon glyphicon-trash"></i> </button> ' +
                    '<input type="hidden" name="files['+image_id+'][url]" value="'+image_url+'"/> ' +
                    '<input type="hidden" name="files['+image_id+'][id]" value="'+image_id+'"/> ' +
                    '<input type="hidden" name="files['+image_id+'][name]" value="'+title+'"/> ' +
                    '<input type="hidden" name="files['+image_id+'][alt]" value="'+alt+'"/> ' +
                    '<input type="hidden" name="files['+image_id+'][description]" value="'+description+'"/> ' +
                    '</div>'
                );
            } else {
                $('.images-list').html('<div class="col-md-3"><img src="'+image_url+'" height="100px">' +
                    '<button type="button" class="btn btn-success filemanager-remove-image"><i class="glyphicon glyphicon-trash"></i> </button> ' +
                    '<input type="hidden" name="file_url" value="'+image_url+'"/> ' +
                    '<input type="hidden" name="file_id" value="'+image_id+'"/> ' +
                    '<input type="hidden" name="file_name" value="'+title+'"/> ' +
                    '<input type="hidden" name="file_alt" value="'+alt+'"/> ' +
                    '<input type="hidden" name="file_description" value="'+description+'"/> ' +
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