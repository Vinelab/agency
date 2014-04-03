<script type="text/x-handlebars-template" id="image_item" />
	<li id="croped-image-item-{{index}}" class="croped-image-item">
		<span  onclick="deleteImage('{{data}}',this)">
			<i class="icon-trash bigger-130"></i>
		</span>
		<img class="image_to_crop" src="/tmp/{{data}}" id="croped-img-{{index}}">
	</li>

</script>

<script type="text/x-handlebars-template" id="video_item_template" />
	<li class="video_item" id="video_item_{{index}}">
		<div class="video-container">
			<div class="yt-img">
				<img src="{{img}}" class="yt-img-thumbnail new-thumbnail">
			</div>
			<div class="yt-data">
				<input type="text" id="yt-title-{{index}}" class="yt-title new-title form-control input-lg" value="{{title}}">
				<textarea id="yt-desc-{{index}}" class="yt-desc new-desc">{{description}}</textarea>
				<input type="hidden" class="yt-url new-url" id="yt-url-{{index}}" value="{{url}}">
			</div>
			<div class="yt-delete">
				<button type="button" class="btn btn-xs btn-info yt-delete-btn" onclick="deleteYt({{index}},'')">
					<i class="icon-trash"></i>
				</button>
			</div>
		</div>
	</li>
</script>



