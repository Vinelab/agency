<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/hmac-sha256.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script type="text/javascript">
	var code="";

	$(document).ready(function(){
		var app_id="2UJc5wPjTZWWl1EAmbt5xtbfWd2Ejz";
		var app_secret="XWuzg5yivVlbMc8NePulvQiYCWnIWC6VD94DosfaklygWkxrzbVkZv3BvUqn";
		var value=app_id+app_secret
		var hash = CryptoJS.HmacSHA256(value, app_secret).toString();
		$.ajax({
			'url' : "/api/code",
			'type' : "POST",
			'data' : {key:app_id, hash:hash}
		}).done(function(msg){
			code=msg.code;
			console.log(msg.code);
		});
	});

	function submit()
	{
		var video = $("#video").prop("files")[0];
		var title = $("#title").val();
		var description = $("#description").val();
		var tags = $("#tags").val();

		var user_name = $("#user_name").val();
		var email = $("#email").val();
		var country = $("#country").val();
		var city = $("#city").val();
		var age = $("#age").val();
		var gender = $("#gender").val();
		var phone = $("#phone").val();

		formdata = new FormData();
		formdata.append("video",video);
		formdata.append("title",title);
		formdata.append("description",description);
		formdata.append("tags",tags);
		formdata.append("user_name",user_name);
		formdata.append("email",email);
		formdata.append("country",country);
		formdata.append("city",city);
		formdata.append("age",age);
		formdata.append("gender",gender);
		formdata.append("phone",phone);

		formdata.append("code",code);

		$.ajax({
		url: "/upload",
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			console.log(res);
		}
	});



		console.log(video);
	}





		

</script>