	@section('head')
		@parent
		{{HTML::style("/css/post/create.css")}}
		{{HTML::style("/css/post/show.css")}}
	@stop

	@section('content')

	<div id="post-container">
		@if ($admin_permissions->has('update'))
			<div id="edit-post-container">
				<a href="{{URL::route('cms.post.edit',$post->slug)}}" class="btn btn-app btn-primary no-radius">
					<i class="icon-edit bigger-230"></i>
					{{Lang::get('posts/form.edit')}}
				</a>
			</div>
		@endif
		<div id="post-title">
			<h2>{{$post->title}}</h2>
		</div>
		<div id="post-cover">
			@if(!empty($media))

				@if($media[0]->type()=="image")
					<img id="cover-photo" src="{{$media[0]->url}}">
				@elseif($media[0]->type()=="video")
					<iframe width="420" height="315" src="{{$media[0]->url}}" frameborder="0" allowfullscreen></iframe>
				@endif
				
			@endif
		</div>
		<div id="post-body">
			<p>
				{{nl2br($post->body)}}
			</p>
		</div>
		<div id="media">
			@if(sizeof($media)>1)
				@foreach($media as $m)
					@if($m->type()=="image")
						
						<img style="width:200px;height200px;" src="{{$m->url}}">
					@elseif($m->type()=="video")
						<iframe width="200" height="200" src="{{$m->url}}" frameborder="0" allowfullscreen></iframe>
					@endif
				@endforeach
			@endif
		</div>
	</div>
		





		


		
	@stop

	@section('scripts')
	    @parent
	    {{HTML::script("/js/jquery.Jcrop.min.js")}}
		{{HTML::script("/assets/js/bootstrap-tag.min.js")}}
		{{HTML::script("/cms/js/post/create.js")}}
	@stop

@include('cms.layout.master')
