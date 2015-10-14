@section("head")
    @parent
	{{HTML::style(Cdn::asset("/css/content/posts.css"))}}

@stop

@section('content')

	<div>
        <ol class="dd-list">

		@if(!empty($posts))
			@foreach($posts as $post)
				@include('cms.pages.content.templates.post')
			@endforeach

            {{$posts->render()}}

		@endif


		</ol>
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script(Cdn::asset("/cms/js/content/index.js"))}}

@stop

@include('cms.layout.master')
