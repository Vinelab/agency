{{ Form::open([
    'url'    => URL::route('cms.content.assignForm'),
    'method' => 'POST',
    'class'  => 'form-horizontal',
    'role'   =>'form',
    'id'     => 'assign-form'
]) }}

	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="post"> Post </label>

		<div class="col-sm-9">
			<select name="post" id="post-select">
				@foreach($posts as $post)
					<option id="opt-{{$post->id}}" value="{{$post->id}}">{{$post->title}}</option>
				@endforeach
			</select>
			@if($post_id!="")
				<input type="hidden" id="post_id" value="{{$post_id}}">
			@endif
			@if($content_id!="")
				<input type="hidden" id="content_id" value="{{$content_id}}">
			@endif
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="content"> Content </label>

		<div class="col-sm-9">
			<select id="content-select" name="content">
				@foreach($contents as $content)
					<option id="opt-content-{{$content->id}}" value="{{$content->id}}">{{$content->title}}</option>
				@endforeach
			</select>
		</div>

		{{Form::submit("Assign")}}
	</div>
	
	

	{{Form::close()}}
		
		