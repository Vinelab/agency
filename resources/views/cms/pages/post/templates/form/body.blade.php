<div class="form-group">
	<label class="control-label"> {{Lang::get('posts/form.body')}} </label>
</div>
<div class="form-group">
	<div class="col-sm-12">
		{{ Editor::view(($updating) ? $edit_post->body : (Input::old('vinelab-editor-text') ? Input::old('vinelab-editor-text') : '' )) }}
	</div>
</div>