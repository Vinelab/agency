<div class="form-group">
	<label class="col-sm-3 control-label input-lg no-padding-right" for="form-field-tags">{{Lang::get('posts/form.tag_input')}}</label>

	<div class="col-sm-9">
		@if($updating and (sizeof($tags)>0))
			<div class="tags">
				<input type="text" name="tags" id="form-field-tags"  placeholder="Enter tags ..." style="display: none;">
				<div id="tags-container">
					@foreach($tags as $tag)
						<span class="tag">
							<span class="tag-value">{{$tag}}</span>

							<button type="button" class="close" onclick="removeTag(this)">×</button>
						</span>
					@endforeach
				</div>
			</div>

		@else
			<input type="text" name="tags" id="form-field-tags" style="display: none;">

		@endif
	</div>
</div>
