	@section('head')
		@parent
		{{HTML::style("/css/post/create.css")}}
		<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
		<link rel="stylesheet" href="{{asset('assets/css/jquery-ui-1.10.3.full.min.css')}}" >

		<link rel="stylesheet" href="{{asset('css/jquery.Jcrop.min.css')}}" />

	@stop

	@section('content')
		@include('cms.pages.post.templates.form')
	@stop

	@section('scripts')
	    @parent
	    @include('cms.pages.templates.post')
	    {{HTML::script("/js/jquery.Jcrop.min.js")}}
		{{HTML::script("/assets/js/bootstrap-tag.min.js")}}
		{{HTML::script("/assets/js/jquery-ui-1.10.3.full.min.js")}}
		{{HTML::script("/js/bootstrap-datetimepicker.min.js")}}
		<script src="/assets/js/bootbox.min.js"></script>

		{{HTML::script("/cms/js/post/create.js")}}
		{{HTML::script("/js/handlebars.js")}}
		<script src="{{asset('/assets/js/bootstrap-wysiwyg.min.js')}}"></script>

	@stop

@include('cms.layout.master')
