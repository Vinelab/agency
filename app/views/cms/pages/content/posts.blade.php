@section("head")
    @parent
	{{HTML::style(Cdn::asset("/css/content/posts.css"))}}

@stop

@section('content')
    @if (Auth::hasPermission('create'))
        <div class="row">
            <a href="{{ URL::route('cms.content.posts.create') }}" class="btn btn-primary">
                <span class="icon-plus"></span>
                {{Lang::get('posts/form.new_post')}}
            </a>
        </div>
    @endif
	<div>

        <ol class="dd-list">
        
		@if(!empty($posts))
			@foreach($posts as $post)
				@include('cms.pages.content.templates.post')
			@endforeach

            {{$posts->links()}}

		@endif


		</ol>
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script(Cdn::asset("/cms/js/content/index.js"))}}

        
@stop

@include('cms.layout.master')