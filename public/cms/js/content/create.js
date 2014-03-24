
function submitForm()
{
	var title=$("#section-title").val();
	if(title!="")
	{
		$("#content-form").submit();
	}else
	{
		alert("Please enter title");
	}
}