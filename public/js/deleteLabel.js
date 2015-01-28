function removeLabel(id)
{
    var obj = {id: id};
    $.ajax({
        url: URL.delete_label,
        type: "POST",
        data: obj,
        success: function(){
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
