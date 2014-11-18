$(function() {
    $('#event_start_date').datetimepicker({
        pickTime: true
    });
    $('#event_end_date').datetimepicker({
        pickTime: true
    });

    $('#datepicker').hide();
    $('#editing').click(function() {
        $('#datepicker').hide();
    });
    $('#published').click(function() {
        $('#datepicker').hide();
    });
    $('#scheduled').click(function() {
        $('#datepicker').show();
    });
    if($('#scheduled').is(':checked'))
    {
        $('#datepicker').show();
    }
});


$(function() {
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
    $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon

});
