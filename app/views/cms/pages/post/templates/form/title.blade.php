<div class="form-group">
	<div class="col-sm-12">
			{{ Form::text('title', ( $updating ) ? $edit_post->title : '' , [
            'class'=>'form-control input-lg',
            'id'=>'title',
            'placeholder'=> Lang::get('posts/form.title').'*'
        	]) }}<br>
	</div>
</div>
