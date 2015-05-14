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
