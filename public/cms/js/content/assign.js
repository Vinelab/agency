$(document).ready(function(){
	var post_id=$("#post_id").val();
	var content_id=$("#content_id").val();

	if(post_id!=undefined)
	{
		var post=$("#opt-"+post_id).text();
		$("#post-select > [value='"+post_id+"']").attr("selected", "true");
	}

	if(content_id!=undefined)
	{
		var content=$("#opt-content-"+content_id).text();
		$("#content-select > [value='"+content_id+"']").attr("selected", "true");
	}

});