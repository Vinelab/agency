	@section('head')
		@parent
		{{HTML::style("/css/post/create.css")}}
	@stop

	@section('content')
		<h2>{{$post->title}}</h2>
		


		@if(!empty($media_array))

			@if($media_array[0]->type()=="image")
				<img src="{{$media_array[0]->url}}">
			@elseif($media_array[0]->type()=="video")
				<iframe width="420" height="315" src="{{$media_array[0]->url}}" frameborder="0" allowfullscreen></iframe>
			@endif
			
		@endif


		<p>
			{{nl2br($post->body)}}
		</p>

		@if(sizeof($media_array)>1)
			@foreach($media_array as $media)
				@if($media->type()=="image")
					
					<img style="width:200px;height200px;" src="{{$media->url}}">
				@elseif($media->type()=="video")
					<iframe width="200" height="200" src="{{$media->url}}" frameborder="0" allowfullscreen></iframe>
				@endif
			@endforeach
		@endif
		
	@stop

	@section('scripts')
	    @parent
	    {{HTML::script("/js/jquery.Jcrop.min.js")}}
		{{HTML::script("/assets/js/bootstrap-tag.min.js")}}
		{{HTML::script("/cms/js/post/create.js")}}
	@stop

@include('cms.layout.master')
