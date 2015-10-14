<div class="form-group">
	{{Form::label("content",Lang::get("posts/form.content"),["class"=>"col-sm-3 input-lg control-label no-padding-right","for"=>"content"])}}

	<div class="col-sm-9">
		<select class="col-sm-5 input-lg" name="section" id="section">

			@foreach($contents as $content)
				<option {{(($updating) and ($content->id == $edit_post->section->id))? 'selected=selected' :''}}  value="{{$content->alias}}">{{$content->title}}</option>
			@endforeach
		</select>
	</div>
</div>
