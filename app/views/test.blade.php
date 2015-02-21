<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/hmac-sha256.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
User_id:
<br>
{{Form::text('id','',['id'=>'user_id'])}}
<br>
<br>
Score:
<br>
{{Form::textarea('result','',['id'=>'result'])}}
<br>

<button onclick='updateScore()' > Update Score</button>
<button onclick='joinTeam()' > Join Team </button>


<input type ='hidden' id='key' value={{URL::route('api.users.updatescore',["_guid_gigya_id_7"])}}>

<input type ='hidden' id='team' value={{URL::route('api.teams.join',["اليسا"])}}>

<script type="text/javascript">
	var code="";

	function updateScore()
	{
		$.ajax({
			'url' : 'http://api.xfactor.app:8000/users/'+ $('#user_id').val() +'/score',
			'type' : "POST",
			'data' : JSON.parse($('#result').val())

		}).done(function(msg){
			console.log(msg);
			$("result").val(msg);

		});
	}


	function joinTeam()
	{
		$.ajax({
				'url' : 'http://api.xfactor.app:8000/teams/'+ $('#user_id').val() +'/join',
				'type' : "POST",
				'data' : $('#result').val()

		}).done(function(msg){
			console.log(msg);
			$("result").val(msg);

		});
	}





</script>
