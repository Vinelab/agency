@section("head")
    @parent
	{{HTML::style("/css/content/posts.css")}}

@stop

@section('content')
    @if ($admin_permissions->has('create'))
        <div class="row">
            <a href="{{ URL::route('cms.post.create') }}" class="btn btn-primary">
                <span class="icon-plus"></span>
                {{Lang::get('posts/form.new_post')}}
            </a>
        </div>
    @endif
	<div class="col-sm-6">

        <ol class="dd-list">
        
		@if(!empty($posts))
			@foreach($posts as $post)
				@include('cms.pages.content.templates.post',compact('admin_permissions','post'))
			@endforeach

            {{$posts->links()}}

		@endif


		</ol>
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script("/cms/js/content/index.js")}}

        
@stop

@include('cms.layout.master')