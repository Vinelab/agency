/**
 * this script is responsible for the 'select from existing photos' feature
 * it opens the popup allows users to select and load more photos
 * then inject the photos in your form.
 */

$(document).ready(function () {

    /**
     * How To Use Me:
     * --------------
     *
     * 1. include this in your form:
     *      @include('cms.pages.templates.photos-modal')
     * 2. add the following html elements to your form:
     *      <div class="hidden" id="existing_photos_holder"></div>
     *      <div id="existing_photos_holder_display"></div>
     *      <button id="choose_from_existing_photos" type="button" class="btn btn-primary" data-toggle="modal" data-target=".selectExistingPhotosModal">Load From Existing</button>
     *
     */


    // number of pages for paginating photos [while loading more existing photos]
    window.current_page = 0;


    // listener to the [select from existing photos] button
    $('#photosModal').on('shown.bs.modal', function () {

        if (!window.opened) {
            // get the first page of photos (paginated)
            getPhotos(function (photosObj) {
                displayPhotos(photosObj);
            });
        }

        window.opened = true;
    });


    // listener to the [load more] button
    $('#load-more-photos').on('click', function (e) {
        e.preventDefault();

        var button = $(this);
        button.hide();

        getPhotos(function (photosObj) {
            displayPhotos(photosObj);
            button.fadeIn();
        });

    });


    // listener to the [done selecting existing photos] button
    $('#done-selecting-photos').on('click', function (e) {
        e.preventDefault();

        var selected_photos = $("#photos-selector").val();

        if (selected_photos) {
            injectExistingPhotos(selected_photos);
            displayExistingPhotos(selected_photos);
        }

        $('#photosModal').modal('toggle');
    });


    /**
     * inject selected photo (form already exist) in the form for submit
     *
     * @param photos id's
     */
    function injectExistingPhotos(photos) {

        var type = 'existing_photos';
        var counter = 0;
        var input_template = '';

        $.each(photos, function (key, value) {
            input_template = input_template + "<input type='hidden' name='" + type + "[" + counter + "]' value='" + value + "' />";
            counter++;
        });

        $('#existing_photos_holder').html(input_template);
    }


    /**
     * display selected existing photos directly in the form
     *
     * @param photos_ids
     */
    function displayExistingPhotos(photos_ids) {

        var display = '';

        $.each(photos_ids, function (key, value) {

            var img = $('#photo-' + value).attr('data-img-src');

            display = display +
            "<div class='col-xs-3 col-md-3 col-lg-3 photos-wrapper'>" +
                "<a class='thumbnail injected-photos'>" +
                    " <img src='" + img + "'>" +
                "</a>" +
            "</div>";
        });

        $('#existing_photos_holder_display').html(display);
    }

    /**
     *  get the photos paginated, and display them in the modal of selecting from existing photos
     *
     * @param callback
     */
    function getPhotos(callback) {
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: Routes.cms_photos,
            data: {
                'page': window.current_page + 1
            }
        })
            .done(function (response) {
                var photosObj = $.parseJSON(response.photos);


                window.current_page = photosObj.current_page;

                // hide the [load more] button when all pages are loaded
                if (window.current_page == photosObj.last_page) {
                    $('#load-more-photos').remove();
                }

                callback(photosObj);
            })
            .fail(function (response) {
                console.log("Error: " + response);
            });

    }

    /**
     * display the photos from the server
     *
     * @param photosObj
     */
    function displayPhotos(photosObj) {
        var options = '';

        $.each(photosObj.data, function (key, value) {
            options = options + "<option data-img-src='" + value.thumbnail + "' value='" + value.id + "' id='photo-" + value.id + "'></option>";
        });

        $('#photos-selector').append(options);

        $('#photos-selector').imagepicker();
    }


});
