 <h3 class="row header smaller lighter blue">
        <span class="col-xs-12">{{Lang::get("posts/form.add_videos")}}</span>
    </h3>

    <div class="row">
		<div class="col-xs-12 col-sm-3">
			<div class="input-group">
				<input type="text" class="form-control search-query" placeholder="{{Lang::get('posts/form.yt_video_url')}}" id="yt_video_txt">
				<span class="input-group-btn">
					<button type="button" onclick="addYoutubeVideo()" class="btn btn-purple btn-sm">
						{{Lang::get("posts/form.add_yt_btn")}}
						<i class="icon-search icon-on-right bigger-110"></i>
					</button>
				</span>
			</div>
		</div>
	</div>

	<div id="videos-container">
		<ul id="videos_list">
			@if( isset($videos) && count($videos) > 0 )
	                @foreach($videos as $key => $video)

	                        <li class="video_item" id="video_item_{{$key}}">
	                        	<div class="video-container">
	                        		<div class="yt-img">
	                        			<img src="{{$video->thumbnail}}" class="yt-img-thumbnail">
	                        		</div>
	                        		<div class="yt-data">
	                        			<input type="text" id="yt-title-{{$key}}" class="yt-title form-control input-lg" value="{{$video->title}}">
	                        			<textarea id="yt-desc-{{$key}}" class="yt-desc">{{$video->description}}</textarea>
	                        			<input type="hidden" class="yt-url" id="yt-url-{{$key}}" value="{{$video->url}}">
	                        		</div>
	                        		@if (Auth::hasPermission('delete'))
		                        		<div class="yt-delete">
		                        			<button type="button" class="btn btn-xs btn-info yt-delete-btn" onclick="deleteYt({{$key}},{{$video->id}})"><i class="icon-trash"></i></button>
		                        		</div>
		                        	@endif
	                        	</div>
	                        </li>
	                @endforeach
	        @endif
	    </ul>
	</div>
