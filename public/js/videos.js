var Videos = {};

$(document).ready(function () {
    Videos.toggleFullToolbar();
    Videos.toggleSelectAllFromChannel();
    Videos.toggleVideoToolbar();
    Videos.toggleSingleVideoToolbar();
});

/**
 * In the Videos section, when checking a single
 * video checkbox, show the full toolbar.
 */
Videos.toggleSingleVideoToolbar = function() {
    $('.to_delete').change(function () {
        if (!this.checked)
            $('#video_toolbar').addClass('hidden');
        else
            $('#video_toolbar').removeClass('hidden');
    });
}

/**
 * In the Videos section, when checking the select all checkbox
 * select all the checkboxes related to the videos and show the
 * full toolbar.
 */
Videos.toggleFullToolbar = function() {
    $('#select_all').change(function () {
        if (!this.checked)
            $('#video_toolbar').addClass('hidden');
        else
            $('#video_toolbar').removeClass('hidden');
    });
    $('#select_all').click(function () {
        if (this.checked) {
            $('.to_delete').each(function () {
                this.checked = true;
            });
        } else {
            $('.to_delete').each(function () {
                this.checked = false;
            });
        }
    });
}

/**
 * In the Channel section, when checking a single
 * video checkbox, show the delete button form the toolbar
 * without the move to section part, because the channel video
 * cannot be moved to a sub-section, since it will exist there
 * by default.
 */
Videos.toggleVideoToolbar = function() {
    $('.channel_videos').change(function () {
        if (!this.checked) {
            $('#video_toolbar').addClass('hidden');
        } else {
            $('#video_toolbar').removeClass('hidden');
            $('#select_sections').addClass('hidden');
            $('#section_move').addClass('hidden');
        }
    });
}

/**
 * In the Channel section, when checking the select all
 * checkbox, show only the delete button, since you can't
 * move a video from the channel to a section, because the
 * channel exists in all sub-section.
 */
Videos.toggleSelectAllFromChannel = function() {
    $('#select_all_from_channel').change(function () {
        if (!this.checked) {
            $('#video_toolbar').addClass('hidden');
        } else {
            $('#video_toolbar').removeClass('hidden');
            $('#section_move').addClass('hidden');
            $('#select_sections').addClass('hidden');
        }
    });

    //select all videos in the channel section
    $('#select_all_from_channel').click(function() {
        if(this.checked) {
            $('.channel_videos').each(function() {
                this.checked = true;
            });
        }else{
            $('.channel_videos').each(function() {
                this.checked = false;
            });
        }
    });
}


/**
 * Submit the form used to move
 * the videos from one seciton to
 * the other.
 */
Videos.moveToSection = function()
{
    $("#change_video_section").submit();
}

/**
 * Post the id of the channel to the server
 * to call the sync method on it.
 * @param id
 */
Videos.channelSync = function(id)
{
    var obj = {id: id};

    $.ajax({
        url: URL.sync_channel,
        type: "POST",
        data: obj,
        success: function() {
            location.reload();
        },
        error: function(xhr) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });
}

/**
 * Add a confirmation box before deleting any video.
 * If the confirmation box returned true (yes clicked)
 * then post the value(s) of the videos to the server
 * and perform the delete method.
 */
Videos.deleteVideos = function()
{
    bootbox.confirm("Are you sure you want to remove the video(s)?", function(result) {
        if(result == true)
        {
            var values = $('input:checkbox:checked.select_checkbox').map(function () {
                return this.value;
            }).get();

            var obj = {ids: values};

            $.ajax({
                url: URL.bulk_delete,
                type: "POST",
                data: obj,
                success: function() {
                    location.reload();
                },
                error: function(xhr) {
                    if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
                    {
                        alert(xhr.statusText);
                    }
                }
            });

        } else {
            $(".bootbox").hide();
        }
    });
}

