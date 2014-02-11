function removePhotos(id, news_id)
{
    var obj = {id: id, news_id: news_id, cover:false};
    $.ajax({
        url: "/artists/news/remove/photo",
        type: "POST",
        data: obj,
        success: function(res){
            location.reload();
        },
        error: function(xhr, status, error) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });g
}

function removeCover(id, news_id)
{
    var obj = {id: id, news_id: news_id, cover:true};
    $.ajax({
        url: "/artists/news/remove/photo",
        type: "POST",
        data: obj,
        success: function(res){
            location.reload();
        },
        error: function(xhr, status, error) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });
}