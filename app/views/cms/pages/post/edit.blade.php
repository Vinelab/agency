
	@section('head')
		@parent
		{{HTML::style("/css/post/create.css")}}

		<link rel="stylesheet" href="{{Cdn::asset('css/jquery.Jcrop.min.css')}}" />

		<link rel="stylesheet" href="{{Cdn::asset('assets/css/jquery-ui-1.10.3.full.min.css')}}" >
		<link rel="stylesheet" href="{{Cdn::asset('css/bootstrap-datetimepicker.min.css')}}">
		<link rel="stylesheet" href="{{Cdn::asset('css/jquery.Jcrop.min.css')}}" />
		<link rel="stylesheet" href="{{Cdn::asset('assets/css/colorbox.css')}}" />
		<link rel="stylesheet" href="{{Cdn::asset('css/style.css')}}">
		<script src="{{Cdn::asset('js/jquery_1_9_1.min.js')}}"></script>

	@stop

	@section('content')
		@include('cms.pages.post.templates.form')
	@stop


@include('cms.layout.master')
