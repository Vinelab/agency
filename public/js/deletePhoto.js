function removePhoto(element, photo_id, article_id, is_cover)
{
    var data = {id: photo_id, article_id: article_id, is_cover: is_cover};

    element = $(element);

    // Make sure that the button is not disabled so that we only
    // send one request to the server to avoid confusing errors
    // on multiple clicks of the same button.
    if ( ! element.data('disabled'))
    {
        element.data('disabled', true);
        $.ajax({
            url: URL.remove_photo,
            type: "POST",
            data: data,
            success: function(res){

                if (res && res.success != null)
                {
                    element.parent().closest('li').fadeOut('fast');

                    // When it's a cover we remove the wrapper as well so it doesn't stay there dangling like a rainbow.
                    if (is_cover) $('#cover-photo-wrapper').fadeOut('fast');
                }

                element.data('disabled', false);
            },
            error: function(xhr, status, error) {
                if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
                {
                    alert(xhr.statusText);
                }

                element.data('disabled', false);
            }
        });
    }
}
