@section("head")
    @parent
	{{HTML::style("/css/content/posts.css")}}

@stop
<?php
?>
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
                        <div class="author-status-container">
                            <div class="status-container">
                                @if($post->publish_state == 'published')
                                    <span class="label label-success arrowed-right arrowed-in pull-right">
                                        {{Lang::get('posts/form.published')}}: {{$post->publish_date}}
                                    </span>
                                @elseif ($post->publish_state == 'editing')
                                    <span class="label label-info arrowed-right arrowed-in pull-right">
                                        {{Lang::get('posts/form.editing')}} 
                                    </span>
                                @else
                                    <span class="label label-danger arrowed-right arrowed-in pull-right">
                                        {{Lang::get('posts/posts.scheduled_at')}} : 
                                        {{$post->publish_date}}
                                    </span>
                                @endif
                            </div>
                            <div class="publisher-container">
                                <h5 class="text-right"><i>{{Lang::get('posts/form.author')}}: {{ Str::title($post->admin()->first()->name)}}</i></h5>
                            </div>
                        </div>
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