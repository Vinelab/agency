var cropped_images=[];
var path;
var photos;
var stored_files = [];

function filesUploadChange()
{
    var files = Array.prototype.slice.call($("#images").prop('files'));
    files.forEach(function(f) {
        if(!f.type.match("image.*")) {
            return;
        }
        stored_files.push(f);
    });

    formdata = new FormData();
    for(var i = 0; i < stored_files.length; i++)
    {
        formdata.append("images[]", stored_files[i]);
    }

    $.ajax({
        url: URL.upload_photos,
        type: "POST",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (res) {
            photos = res.photoname; console.log(res);
            path = res.path;
            displayCropView(photos, path);
        },
        error: function(xhr, status, error) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });
}

function displayCropView(photos, path)
{
    var cropped_images_ul=$("#cropped-images-list");
    cropped_images_ul.empty();
    var photos_lengh=photos.length;
    for(var i = 0; i < photos_lengh; i++)
    {
        var element='<li id="cropped-image-item-'+i+'"><img class="image_to_crop" src="'+path+'/'+photos[i]+'" id="cropped-img-'+i+'"></li>';
        cropped_images_ul.append(element);

        $("#cropped-img-"+i).Jcrop({
            aspectRatio: 3/2,
            allowSelect: false,
            keySupport: false,
            setSelect: [0, 0, 600, 400],
            minSize: [300, 200],
            boxWidth: 800
        });
    }
}

function submitForm()
{
    cropped_images = JSON.stringify( getCroppedImages() );
    $("#cropped_images").val(cropped_images);
    $(form_id).submit();
}

function getCroppedImages()
{
    jQuery('.image_to_crop').each(function() {
        image_to_crop = $(this);
        image_to_crop.Jcrop({
            onChange: function(coords){
                cropped_images.push(showPreview(image_to_crop, coords));
            },
        });
    });

    return cropped_images;
}

function showPreview(image_to_crop, coords) {
    var cropped_image_object={
        "name":image_to_crop.prop("src"),
        crop_x : Math.round(coords.x),
        crop_y : Math.round(coords.y),
        crop_width  : Math.round(coords.w),
        crop_height  : Math.round(coords.h),
        width : getImageWidth(),
        height : getImageHeight(),
    };
    return cropped_image_object;
}

function getImageWidth()
{
    return $('.jcrop-holder img:eq(0)').width();
}

function getImageHeight()
{
    return $('.jcrop-holder img:eq(0)').height();
}
