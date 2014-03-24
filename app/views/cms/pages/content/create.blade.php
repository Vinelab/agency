
	@section('head')
		@parent
		{{HTML::style("/css/jquery.Jcrop.min.css")}}
		{{HTML::style("/css/content/create.css")}}
	@stop

	@section('content')
		@include('cms.pages.content.templates.form')
	@stop

	@section('scripts')
	    @parent
		{{HTML::script("/cms/js/content/create.js")}}
		{{HTML::script("/js/jquery.Jcrop.min.js")}}
	@stop

@include('cms.layout.master')
