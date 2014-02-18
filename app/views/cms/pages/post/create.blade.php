	@section('head')
		@parent
		{{HTML::style("/css/post/create.css")}}
	@stop

	@section('content')
		@include('cms.pages.post.templates.form')
	@stop

	@section('scripts')
	    @parent
	    {{HTML::script("/js/jquery.Jcrop.min.js")}}
		{{HTML::script("/assets/js/bootstrap-tag.min.js")}}
		{{HTML::script("/cms/js/post/create.js")}}
	@stop

@include('cms.layout.master')
