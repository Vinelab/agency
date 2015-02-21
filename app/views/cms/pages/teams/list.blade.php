{{-- @author Ibrahim Fleifel <ibrahim@vinelab.com> --}}

@section('content')

    {{-- list all teams --}}

    <a href="{{route('cms.teams.create')}}" class="btn btn-primary">
        <i class="icon-plus"></i>
        {{Lang::get('common.create')}}
    </a>

    <div class="space-10"></div>

    @if(isset($teams) and count($teams) > 0)
        @foreach($teams as $team)
            @include('cms.pages.teams.templates.teams-list', ['team' => $team])
        @endforeach
    @else
        <h1><small>{{Lang::get('teams.labels.no_teams')}}</small></h1>
    @endif

    {{ $teams->links() }}

@stop


@include('cms.layout.master')
