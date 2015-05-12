/**
 * this script is specific to [resources/views/cms/pages/templates/_images-uploader.blade.php]
 * to manage the uploading and displaying pictures in a form.
 */

    /****************************** VARIABLES & INITIALIZATION ******************************/

        // counter of the number of photos being injected in the form while user uploading new photos (excluding cover photo)
    window.photos_counter = 0;

    // uploader for photos
    upload = $("#uploader").mrUploader({
        uploadUrl: Routes.cms_photos_store,
        crop: {
            aspectRatio: 3 / 2
        }
    });

    /****************************** EVENT LISTENERS ******************************/


    // listener to loading photos with mrUploader
    upload.on('upload', function (event, data) {
        injectPhoto(data.response);
    });

    // listener to [photos delete] button (when user clicks on the delete icon in each photo)
    $('.remove-photo').on('click', function (e) {
        e.preventDefault();

        var album_slug = $(this).attr('slug');
        var photo_id = $(this).attr('photo');

        removePhoto(photo_id, album_slug, function () {
            // remove the photo from the view
            // note: the id of the photo wrapper is the combination of album_slug and photo_id
            $('#' + album_slug + photo_id).remove();
        });

    });

    /****************************** FUNCTIONS ******************************/


    /**
     * call to detach photo from the desired article news
     *
     * @param photo_id
     * @param album_slug
     */
    function removePhoto(photo_id, album_slug, callback) {

        $.ajax({
            type: "POST",
            url: Routes.cms_photos_destroy,
            data: {
                album_slug: album_slug,
                photo_id: photo_id
            }
        })
            .done(function (response) {
                if (response.status == 'success') {
                    callback();
                }
            })
            .fail(function (response) {
                console.log("Error: " + response);
            });
    }



    /**
     * inject photos URI's received form the CDN in the form for submit
     *
     * @param data
     */
    function injectPhoto(data) {
        var type = 'photos';

        var input_template = "<input type='hidden' name='" + type + "[" + window.photos_counter + "][original]' value='" + data.photos.original + "' />" +
            "<input type='hidden' name='" + type + "[" + window.photos_counter + "][small]' value='" + data.photos.small + "' />" +
            "<input type='hidden' name='" + type + "[" + window.photos_counter + "][thumbnail]' value='" + data.photos.thumbnail + "' />" +
            "<input type='hidden' name='" + type + "[" + window.photos_counter + "][square]' value='" + data.photos.square + "' />";

        $('#photos_holder').append(input_template);

        displayPhotos('#photos_holder_display', data.photos.thumbnail);

        window.photos_counter++;
    }



    /**
     * display uplaoded photo in the form page
     *
     * @param place
     * @param thumbnail
     */
    function displayPhotos(place, thumbnail) {
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

