
	@section('head')
		@parent
		{{HTML::style("/css/jquery.Jcrop.min.css")}}
		{{HTML::style("/css/content/assign.css")}}
	@stop

	@section('content')
		@include('cms.pages.content.templates.assignForm')
	@stop

	@section('scripts')
	    @parent
		{{HTML::script("/cms/js/content/assign.js")}}
	@stop

@include('cms.layout.master')
