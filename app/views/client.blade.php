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






		

</script>