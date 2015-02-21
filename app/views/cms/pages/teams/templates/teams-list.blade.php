<div class="well">
	<div class="pull-right action-buttons">
            <a class="blue" href="{{URL::route('cms.teams.edit',$team->slug)}}">
                <i class="ace-icon fa fa-pencil-square-o bigger-230"></i>
            </a>
	</div>
	<div class="right">
		<a href="{{URL::route('cms.teams.show',$team->slug)}}">
			<img src="{{$team->image->thumbnail}}" class="team-img">
			<h1>{{$team->title}}</h1>
		</a>
	</div>
</div>

