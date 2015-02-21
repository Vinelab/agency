{{-- @author Ibrahim Fleifel <ibrahim@vinelab.com> --}}

@section('content')

    <a href="{{route('cms.teams.edit', $team->slug)}}" class="no-underline">
        <i class="ace-icon fa fa-pencil-square-o bigger-230"></i>
        {{Lang::get('teams.labels.edit')}}
    </a>

    <div class="space-10"></div>

    <div>
	    <img src="{{$team->image->thumbnail}}" class="team-img">
	    <h1>{{$team->title}}</h1>
	</div>

@stop

@include('cms.layout.master')
