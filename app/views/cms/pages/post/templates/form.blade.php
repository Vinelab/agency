<?php
 $updating = isset($edit_post);
?>
	<div class='form-horizontal'>
		<div class="form-group">
			<div class="col-sm-9">
				<div class="clearfix">
					<div class="form-group">
						<input type="hidden" name="updating" value="{{$updating}}" id="updating">
						@if($updating)
							<input type="hidden" name="post_id" value="{{$edit_post->id}}" id="post_id">
						@endif
					</div>
	            </div>
			</div>
		</div>

		{{--Title Input--}}

		@include('cms.pages.post.templates.form.title')

		<div class="space-4"></div>

		{{--Body Input--}}
		<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
		{{HTML::script('/dist/js/mr-uploader.all.min.js')}}


		@if($updating)
			{{Editor::view($edit_post->body)}}
		@else
			{{Editor::view()}}
		@endif

		<div class="space-4"></div>

		{{--Section--}}

		@include('cms.pages.post.templates.form.section')


		<div class="space-4"></div><br>

		{{--Tags Input--}}

		@include('cms.pages.post.templates.form.tag')

		<div class="space-4"></div><br>

		{{--make post featured --}}

		@include('cms.pages.post.templates.form.featured')

	</div>

    <div class="space-4"></div><br>

	{{--Add cover photo --}}

	@include('cms.pages.post.templates.form.cover')

	{{--Add Images --}}

	@include('cms.pages.post.templates.form.images')

    <div class="space-12"></div>

	{{--display videos--}}

	@include('cms.pages.post.templates.form.videos')

    <div class="space-12"></div>

	{{--publish state--}}

	{{--@include('cms.pages.post.templates.form.status')--}}

	{{Publisher::display($updating, $edit_post)}}

	<div class="space-12"></div>

	@include('cms.pages.post.templates.form.controls')

	<div id="loader-container">
		<img id="img-loader" src="{{Cdn::asset('/cms/images/server.gif')}}">
	</div>


	@section('scripts')
	    @parent
	    @include('cms.pages.templates.post')
	    @include('cms.pages.post.templates.routes')
	    @include('cms.pages.post.templates.lang')

	    {{HTML::script(Cdn::asset('/js/jquery.Jcrop.min.js'))}}
	    {{HTML::script(Cdn::asset('/js/moment.min.js'))}}
		{{HTML::script(Cdn::asset('/js/bootstrap-datetimepicker.min.js'))}}
		{{HTML::script(Cdn::asset('/assets/js/bootstrap-tag.min.js'))}}
		{{HTML::script(Cdn::asset('/js/handlebars.js'))}}
		<script src="{{Cdn::asset('/assets/js/bootstrap-wysiwyg.min.js')}}"></script>
		{{HTML::script("/cms/js/post/create.js")}}



		<script src="{{Cdn::asset('/assets/js/jquery.colorbox-min.js')}}"></script>


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

			$('#wysiwyg-editor-value')
			.text( $('#editor')
			.html("{{ preg_replace("/\r|\n/", "", addslashes( nl2br(isset($edit_post->body)?$edit_post->body:'') ) ) }}"));

			/**$(window).on('resize.colorbox', function() {
				try {
					//this function has been changed in recent versions of colorbox, so it won't work
					$.fn.colorbox.load();//to redraw the current frame
				} catch(e){}
			});*/
		})
		</script>
	@stop


