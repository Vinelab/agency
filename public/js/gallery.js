//show and hide the gallery editor
function showHideGalleryEditor()
{
    if($("#gallery_editor").hasClass('hide'))
    {
        $("#gallery_editor").removeClass('hide');
        $("#show-hide-icon").removeClass('icon-plus');
        $("#show-hide-icon").addClass('icon-minus');

    }
    else {
        $("#gallery_editor").addClass('hide');
        $("#show-hide-icon").removeClass('icon-minus');
        $("#show-hide-icon").addClass('icon-plus');

    }
}

// this is used to remove a photo
function removePhotos(id, album_id)
{
    var obj = {id: id, album_id: album_id};

    $.ajax({
        url: URL.remove_photo,
        type: "POST",
        data: obj,
        success: function(res) {
            if (res && res.success == true)
            {
                $('#' + id).fadeOut('fast');
            }
        },
        error: function(xhr) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });
}

//this is used to pick an image from the existing ones
$("select").imagepicker();

var label_text_val= '';
jQuery(function($) {
    label_text_val = $("#label_text").val();
});

//this is used to change the cover photo
function changeCoverPhoto(photo_id, album_id)
{
    var url = decodeURIComponent(URL.change_cover_photo).replace('{album_id}', album_id);
    var obj = { photo_id:photo_id, album_id:album_id };

    $.ajax({
        url: url,
        type: "POST",
        data: obj,
        success: function(res) {

            if (res && res.success == true)
            {
                $("#cover_photo_label").fadeOut('fast', function(){ $(this).remove(); });

                $("#"+photo_id).append(
                    '<div class="tags" id="cover_photo_label">' +
                        '<span class="label-holder">' +
                            '<span class="label label-success arrowed" id="label_text">'+label_text_val+'</span>' +
                        '</span>' +
                    '</div>');
            }

        },
        error: function(xhr) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });
}

function loadPhotos(current, last)
{
    var obj = {page: current+1};
    if(current+1 == last)
    {
        $("#load_photos").remove();
    }
    $.ajax({
        url: URL.fetch_paginated_photos,
        type: "GET",
        data: obj,
        success: function(res) {
            appendPhotos(res);
        },
        error: function(xhr) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });
}

function appendPhotos(res)
{
    var photos = res.data;
    for(var i = 0; i < photos.length; i++)
    {
        $("#existing_photos").append(
           '<option data-img-src="'+ photos[i].photo +'" value="'+ photos[i].id +'"></option>'
        );

        $("select").data('picker').destroy();
        $("select").imagepicker();
    }
}

//this is used for the colorbox
jQuery(function($) {
    var colorbox_params = {
        reposition:true,
        scalePhotos:true,
        scrolling:false,
        previous:'<i class="icon-arrow-left"></i>',
        next:'<i class="icon-arrow-right"></i>',
        close:'&times;',
        current:'{current} of {total}',
        maxWidth:'100%',
        maxHeight:'100%',
        onOpen:function(){
            document.body.style.overflow = 'hidden';
        },
        onClosed:function(){
            document.body.style.overflow = 'auto';
        },
        onComplete:function(){
            $.colorbox.resize();
        }
    };

    $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
    $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");
})
