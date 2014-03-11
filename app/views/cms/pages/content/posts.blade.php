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

				 <li class="posts-container">
                    <div class="dd-handle">
                    	<div class="pull-left action-buttons">
                            @if ($admin_permissions->has('update'))
                                <a class="blue" href="{{URL::route('cms.post.edit',$post->slug)}}">
                                    <i class="icon-pencil bigger-130"></i>
                                </a>
                            @endif
                        </div>
                        <img src="{{$post->thumbnail()}}">
                        {{HTML::link(URL::route('cms.post.show',$post->slug),$post->title)}}
                    </div>
                </li>
			@endforeach
		@endif

		</ol>
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script("/cms/js/content/index.js")}}

        
@stop

@include('cms.layout.master')