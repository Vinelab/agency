	@section('head')
		@parent
		{{HTML::style("/css/post/create.css")}}
		{{HTML::style("/css/post/show.css")}}
	@stop

	@section('content')

	<div id="post-container">
		<div id="post-title">
			<h2>{{$post->title}}</h2>
		</div>
		<div id="post-cover">
			@if(!empty($media_array))

				@if($media_array[0]->type()=="image")
					<img id="cover-photo" src="{{$media_array[0]->url}}">
				@elseif($media_array[0]->type()=="video")
					<iframe width="420" height="315" src="{{$media_array[0]->url}}" frameborder="0" allowfullscreen></iframe>
				@endif
				
			@endif
		</div>
		<div id="post-body">
			<p>
				{{nl2br($post->body)}}
			</p>
		</div>
		<div id="media">
			@if(sizeof($media_array)>1)
				@foreach($media_array as $media)
					@if($media->type()=="image")
						
						<img style="width:200px;height200px;" src="{{$media->url}}">
					@elseif($media->type()=="video")
						<iframe width="200" height="200" src="{{$media->url}}" frameborder="0" allowfullscreen></iframe>
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
