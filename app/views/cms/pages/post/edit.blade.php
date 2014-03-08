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

	@section('scripts')
	    @parent
	    @include('cms.pages.templates.post')
	    @include('cms.pages.post.templates.routes')

	    {{HTML::script("/js/jquery.Jcrop.min.js")}}

		{{HTML::script("/assets/js/bootstrap-tag.min.js")}}

		{{HTML::script("/cms/js/post/create.js")}}
		{{HTML::script("/js/handlebars.js")}}
		<script src="{{asset('/assets/js/bootstrap-wysiwyg.min.js')}}"></script>


		<script src="{{asset('assets/js/jquery.colorbox-min.js')}}"></script>


			<script type="text/javascript">
			jQuery(function($) {
			var colorbox_params = {
				reposition:true,
				scalePhotos:true,
				scrolling:false,
				previous:'<i class="icon-arrow-left"></i>',
				next:'<i class="icon-arrow-right"></i>',
				close:'&times;',
				current:'{current} of {total}',
				maxWidth:'100%',
				maxHeight:'100%',
				onOpen:function(){
					document.body.style.overflow = 'hidden';
				},
				onClosed:function(){
					document.body.style.overflow = 'auto';
				},
				onComplete:function(){
					$.colorbox.resize();
				}
			};

			$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
			$("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon

			/**$(window).on('resize.colorbox', function() {
				try {
					//this function has been changed in recent versions of colorbox, so it won't work
					$.fn.colorbox.load();//to redraw the current frame
				} catch(e){}
			});*/
		})
		</script>

		<script>
		$('#wysiwyg-editor-value')
			.text( $('#editor')
			.html("{{ preg_replace("/\r|\n/", "", addslashes( nl2br($edit_post->body) ) ) }}"));
		</script>


	@stop

@include('cms.layout.master')
