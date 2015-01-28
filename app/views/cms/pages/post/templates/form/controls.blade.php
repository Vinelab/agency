<div class="form-group" id="controls-container">
	    <button onclick="submitForm()" class="btn btn-success btn-lg pull-right" id="submitBtn">
	    	{{Lang::get("posts/form.submit")}} <i class="icon-spinner icon-spin orange bigger-125" id="spinner"></i>
	    </button>
	    <button onclick="cancel()" class="btn btn-default btn-lg pull-right" id="cancel-btn">
	    	{{Lang::get("posts/form.cancel")}} <i class="icon-spinner icon-spin orange bigger-125" id="spinner"></i>
	    </button>
</div>
@if($updating)
    <div class='form-group'>
    	<div class='align-left'>
	    	{{Form::open(['route' => ['cms.content.posts.destroy',$edit_post->slug],'id'=>'delete-post-form'])}}
	    		{{ Form::hidden('_method', 'DELETE') }}
				{{ Form::button(Lang::get('posts/form.delete'),['class' => 'btn btn-danger','onclick'=>"deletePost()"]) }}
			{{Form::close()}}
		</div>
	</div>
@endif
