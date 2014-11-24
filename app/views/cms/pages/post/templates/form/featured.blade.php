<div class="form-group">
	<label class="col-sm-3 control-label input-lg no-padding-right" for="form-featured">{{Lang::get('posts/form.featured')}}</label>

	<div class="col-sm-9">
		<label class="inline">
			@if($updating)
				<input id="featured" type="checkbox" {{$edit_post->featured == 'true'?'checked':''}} class="ace ace-switch ace-switch-5">

			@else
				<input id="featured" type="checkbox" class="ace ace-switch ace-switch-5">
			@endif
			<span class="lbl middle"></span>
		</label>
	</div>
</div>
