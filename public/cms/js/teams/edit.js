var cropped_images=[];
var path;
var photos;
var stored_files = [];
var photo_crop_obj;


var original_photo = {
    photo: $('#photo'),
    crop_x: $('#crop_x'),
    crop_y: $('#crop_y'),
    crop_width: $('#crop_width'),
    crop_height: $('#crop_height'),
    photo_width: $('#photo_width'),
    photo_height: $('#photo_height')
}




function filesUploadChange()
{
    var photo = $("#photo-file").prop('files')[0];
    form_data = new FormData();
    form_data.append('photo', photo);

    var photo_crop = $("#photo-crop");
    var path = StoreImage(form_data,photo_crop);
}

function StoreImage(form_data,photo){

    $("#loader-container").show();
    $.ajax({
        url: URL.photo_upload,
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false,
        success: function (res) {
            $("#loader-container").hide();
            displayCropView(res,photo);
        },
        error: function(xhr, status, error) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });

}

function displayCropView(path,photo)
{
    if($(photo).attr('id')=="photo-crop")
    {
        if(photo_crop_obj!==undefined)
        {
            photo_crop_obj.destroy();
        }


        photo.attr('src','/cms/images/uploads/'+path);
        photo.Jcrop({
            aspectRatio: 2/3,
            allowSelect: false,
            keySupport: false,
            setSelect: [0, 0, 400, 600],
            minSize: [155, 245],
            boxWidth: 457,
            onChange: function(crop){
                original_photo.crop_x.val(Math.round(crop.x));
                original_photo.crop_y.val(Math.round(crop.y));
                original_photo.crop_width.val(Math.round(crop.w));
                original_photo.crop_height.val(Math.round(crop.h));
                original_photo.photo_width.val($(photo).width());
                original_photo.photo_height.val($(photo).height());
                }
            },function(){
        photo_crop_obj=this;
        });

    }
}

function create()
{
    $("#loader-container").show();
    $("#submit-btn").attr('disabled','disabled');
    $("#team-info").submit();
}
