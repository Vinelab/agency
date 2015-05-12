jQuery(function($){

    $('[data-rel="tooltip"]').tooltip();


    /**
     * listen to the delete content in order to show the confirmation dialogue
     */
    $('.delete-content-btn').on('click', function(e){
        e.preventDefault();

        var url = $(this).attr('href');

        var row = $(this).closest('tr');

        // ask to confirm
        bootbox.confirm("Are you sure you want to delete this content?", function(result) {

            if(result){
                deleteContent(url, function(){
                    // hide form the view
                    $(row).fadeOut();
                });
            }

        });
    });

    /**
     * delete a content from the server
     *
     * @param url
     * @param callback
     */
    function deleteContent(url, callback)
    {
        $.ajax({
            type: "POST",
            url: url
        })
            .done(function( response ) {
                if(response.status == 'success'){
                    callback();
                }else{
                    console.log( "Error: " + response );
                }
            })
            .fail(function( response ) {
                console.log( "Error: " + response );
            });
    }


});
