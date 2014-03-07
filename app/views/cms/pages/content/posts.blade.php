@section("head")
    @parent
	{{HTML::style("/css/content/posts.css")}}

@stop
@section('content')
	<div class="col-sm-6">

        <ol class="dd-list">
        
		@if(!empty($posts))
			@foreach($posts as $post)
				 <li class="posts-container">
                    <div class="dd-handle">
                    	<div class="pull-right action-buttons">
                            @if ($admin_permissions->has('update'))
                                <a class="blue" href="{{URL::route('cms.post.edit',$post['data']->id)}}">
                                    <i class="icon-pencil bigger-130"></i>
                                </a>
                            @endif
                        </div>
                        <img src="{{$post['thumbnail']}}">
                        {{HTML::link(URL::route('cms.post.show',$post['data']->slug),$post['data']->title)}}
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