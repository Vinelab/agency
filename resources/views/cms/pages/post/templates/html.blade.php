@section('head')
	@parent
	{{HTML::style("/css/post/create.css")}}
	<link rel="stylesheet" href="{{ Cdn::asset('/vendor/mr-uploader/css/mr-uploader.min.css') }}" />
	<link rel="stylesheet" href="{{ Cdn::asset('/assets/css/bootstrap-datetimepicker.css') }}" />
    <link rel="stylesheet" href="{{ Cdn::asset('/assets/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ Cdn::asset('/css/sections.css') }}" />
	<link rel="stylesheet" href="{{ Cdn::asset('/vendor/image-picker/image-picker/css/image-picker.css') }}" />

	{{-- uploader js files must be uploaded at the top before the usage of the editor --}}
	<script src="{{Cdn::asset('/vendor/mr-uploader/js/mr-uploader.all.min.js')}}"></script>
	<script src="{{Cdn::asset('/assets/js/uncompressed/date-time/moment.js')}}"></script>
	<script src="{{Cdn::asset('/assets/js/uncompressed/date-time/bootstrap-datetimepicker.js')}}"></script>
@stop

@section('content')
    <div dir="{{Config::get('app.locale') == 'ar' ? 'rtl' : 'ltr'}}">
        @include('cms.pages.post.templates.routes')
        @include('cms.pages.post.templates.lang')
        @include('cms.pages.post.templates.form')
        @include('cms.pages.templates.photos-modal')
    </div>
@stop

@section('scripts')
	@parent
	<script src="{{Cdn::asset('/vendor/image-picker/image-picker/js/image-picker.min.js')}}"></script>
    <script src="{{Cdn::asset('/cms/js/content/images-picker.js')}}"></script>
	<script src="{{Cdn::asset('/assets/js/select2.min.js')}}"></script>
    <script src="{{Cdn::asset('/cms/js/cover-uploader.js')}}"></script>
    <script src="{{Cdn::asset('/cms/js/images-uploader.js')}}"></script>
@stop

@include('cms.layout.master')
