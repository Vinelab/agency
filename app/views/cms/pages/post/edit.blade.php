@section('head')
	@parent
	{{HTML::style("/css/post/create.css")}}
	<link rel="stylesheet" href="{{asset('css/jquery.Jcrop.min.css')}}" />


	<link rel="stylesheet" href="{{asset('assets/css/jquery-ui-1.10.3.full.min.css')}}" >
	<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/jquery.Jcrop.min.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/css/colorbox.css')}}" />
	<link rel="stylesheet" href="{{asset('css/style.css')}}">
	<script src="{{asset('js/jquery_1_9_1.min.js')}}"></script>

@stop

@section('content')
	@include('cms.pages.post.templates.form')
@stop
@section('script')
	@parent
	<script>

	</script>

@stop

@include('cms.layout.master')
