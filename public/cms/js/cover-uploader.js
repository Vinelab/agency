/**
 * this script is specific to [resources/views/cms/pages/templates/_cover-picture.blade.php]
 * to manage the uploading and displaying of the cover picture in a form.
 */

    // uploader for cover photo
    cover = $("#cover").mrUploader({
        uploadUrl: Routes.cms_photos_store,
        crop: {
            aspectRatio: 3 / 2
        }
    });

    // listener to uploading cover with mrUploader
    cover.on('upload', function (event, data) {
        injectCoverPhoto(data.response);
    });


    /**
     * inject cover photo URI's received form the CDN in the form to be submit
     *
     * @param data
     */
    function injectCoverPhoto(data) {
        var type = 'cover';

        var input_template = "<input type='hidden' name='" + type + "[original]' value='" + data.photos.original + "' />" +
            "<input type='hidden' name='" + type + "[small]' value='" + data.photos.small + "' />" +
            "<input type='hidden' name='" + type + "[thumbnail]' value='" + data.photos.thumbnail + "' />" +
            "<input type='hidden' name='" + type + "[square]' value='" + data.photos.square + "' />";

        $('#cover_holder').html(input_template);

        displayCoverPhoto('#cover_holder_display', data.photos.thumbnail);
    }


    /**
     * display uplaoded photo in the form page
     *
     * @param place
     * @param thumbnail
     */
    function displayCoverPhoto(place, thumbnail) {
        var display_template =
                "<div class='col-xs-3 col-md-3 col-lg-3'>" +
                "<a class='thumbnail injected-photos'>" +
                "<img src='" + thumbnail + "'>" +
                "</div>";

        if (place == '#cover_holder_display') {
            $(place).html(display_template);
        }
        else if (place == '#photos_holder_display') {
            $(place).append(display_template);
        }
    }

