	@section('head')
		@parent
		{{HTML::style(Cdn::asset('/css/post/show.css'))}}



		<link rel="stylesheet" href="{{Cdn::asset('/assets/css/jquery-ui-1.10.3.full.min.css')}}" >
		<link rel="stylesheet" href="{{Cdn::asset('/assets/css/colorbox.css')}}" />
		<link rel="stylesheet" href="{{Cdn::asset('/css/style.css')}}">
		<script src="{{Cdn::asset('/js/jquery_1_9_1.min.js')}}"></script>

	@stop

	@section('content')

	<div id="post-container">
		@if (Auth::hasPermission('update'))
			<div id="edit-post-container">
				<a href="{{URL::route('cms.content.posts.edit',$post->slug)}}" class="btn btn-primary no-radius">
					<i class="icon-edit"></i>
					{{Lang::get('posts/form.edit')}}
				</a>
			</div>
		@endif
		<div id="post-title">
			<h2 id="title">{{$post->title}}</h2>
		</div>



		<div id="post-cover">

				<img id="cover-photo" src="{{$post->thumbnailUrl()}}">
		</div>

		<div id="post-body">
			<p>
				{{nl2br($post->body)}}
			</p>
		</div>
		@if(!empty($tags))
			<div id="tags-container">
				<ul id="tag-list">

					@foreach($tags as $tag)
						<li class="tag-item">
							<span class="label label-info arrowed-right arrowed-in">{{$tag->text}}</span>
						</li>
					@endforeach
				</ul>
			</div>
		@endif


		<div id="media">
			<ul class="ace-thumbnails">
				@foreach($images as $image)
					<li id="img-{{$image->id}}">
						<a href="{{$image->original}}" data-rel="colorbox">
                            <img alt="post-thumbnail" src="{{$image->thumbnail}}" width="200px"/>
                            <div class="text">
                                <div class="inner">{{Lang::get("posts/form.preview")}}</div>
                            </div>
                        </a>
			        </li>
			    @endforeach

				@foreach($videos as $video)
					<iframe width="200" src="{{$video->url}}" frameborder="0" allowfullscreen></iframe>
				@endforeach

			</ul>
		</div>
	</div>


	@stop

	@section('scripts')
	    @parent

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
		@stop

@include('cms.layout.master')
