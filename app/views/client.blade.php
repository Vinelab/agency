<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/hmac-sha256.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

App id:
{{Form::text('app_id','',['id'=>'app_id'])}}
<br>
App secret:
{{Form::text('app_secret','',['id'=>'app_secret'])}}
<br>
Code:
{{Form::text('code','',['id'=>'code'])}}
<br>
<button onclick='getCode()' >Generate Code!</button>

<input type ='hidden' id='key' value={{URL::route('api.code.create')}}>

<script type="text/javascript">
	var code="";

	function getCode()
	{

		$(document).ready(function(){
			var app_id=$('#app_id').val();
			var app_secret=$('#app_secret').val();
			var value=app_id+app_secret
			var hash = CryptoJS.HmacSHA256(value, app_secret).toString();

			$.ajax({
				'url' : $('#key').val(),
				'type' : "POST",
				'data' : {key:app_id, hash:hash}
			}).done(function(msg){
				code=msg.code;
				console.log(msg.code);
				$("#code").val(msg.code);

			});
		});

	}


</script>
