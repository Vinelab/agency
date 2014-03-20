	@section('head')
		@parent
		{{HTML::style("/css/post/show.css")}}


		<link rel="stylesheet" href="{{asset('assets/css/jquery-ui-1.10.3.full.min.css')}}" >
		<link rel="stylesheet" href="{{asset('assets/css/colorbox.css')}}" />
		<link rel="stylesheet" href="{{asset('css/style.css')}}">
		<script src="{{asset('js/jquery_1_9_1.min.js')}}"></script>

	@stop

	@section('content')

	<div id="post-container">
		@if ($admin_permissions->has('update'))
			<div id="edit-post-container">
				<a href="{{URL::route('cms.post.edit',$post->slug)}}" class="btn btn-primary no-radius">
					<i class="icon-edit bigger-230"></i>
					{{Lang::get('posts/form.edit')}}
				</a>
			</div>
		@endif
		<div id="post-title">
			<h2 id="title">{{$post->title}}</h2>
		</div>
		@if(!empty($media))
			<div id="post-cover">
				@if($media[0]->type()=="image")
					<img id="cover-photo" src="{{$media[0]->url}}">
				@elseif($media[0]->type()=="video")
					<iframe width="420" height="315" src="{{$media[0]->url}}" frameborder="0" allowfullscreen></iframe>
				@endif
			</div>
		@endif
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
							<span class="label label-info arrowed-in-left arrowed">{{$tag->text}}</span>
						</li>
					@endforeach
				</ul>
			</div>
		@endif
		

		<div id="media">
			@if(sizeof($media)>1)
			<ul class="ace-thumbnails">
				@foreach($media as $m)
					@if($m->type()=="image")

					<li id="img-{{$m->id}}">
						<a href="{{$m->url}}" data-rel="colorbox">
                            <img alt="150x150" src="{{$m->presetURL('thumbnail')}}" width="200px"/>
                            <div class="text">
                                <div class="inner">Preview</div>
                            </div>
			            </a>
			        </li>
					@elseif($m->type()=="video")
						<iframe width="200" height="200" src="{{$m->url}}" frameborder="0" allowfullscreen></iframe>
					@endif
				@endforeach
			</ul>
			@endif
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
