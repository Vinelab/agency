@section('content')
	<div class="col-xs-12">

        @if ($admin_permissions->has('create'))
        <div class="row">
            <a href="{{ URL::route('cms.post.create') }}" class="btn btn-primary">
                <span class="icon-plus"></span>
                New Post
            </a>
        </div>
        @endif

        <div class="space-4"></div>

        <div class="row">

   

        </div>

    </div>
@stop

@section('scripts')
    @parent

@stop

@include('cms.layout.master')