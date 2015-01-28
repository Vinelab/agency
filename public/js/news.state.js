$(document).ready(function() {
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