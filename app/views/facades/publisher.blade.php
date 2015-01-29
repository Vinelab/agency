
 <h3 class="row header smaller lighter blue">
        <span class="col-xs-12">{{Lang::get("posts/form.status")}}</span>
    </h3>

  
	<div class="control-group pull-right" id="status-container">
	    <div class="publish-date-margin">
	    	
	        @if($updating)

	            {{ Form::label('editing',Lang::get('posts/form.editing')) }}
	            {{ Form::radio('publishstate[]','editing', ( $edit_post->publish_state == 'editing' ) ? 'true' : '', ['id'=>'editing','class'=>'publish_state']) }}
	            @if (Auth::hasPermission('publish'))
	                {{ Form::label('published',Lang::get('posts/form.publish'), ['class'=>'increase-margin-left']) }}
	                {{ Form::radio('publishstate[]','published',( $edit_post->publish_state == 'published' ) ? 'true' : '' ,['id'=>'published','class'=>'publish_state']) }}

	                {{ Form::label('scheduled',Lang::get('posts/form.schedule'), ['class'=>'increase-margin-left']) }}
	                {{ Form::radio('publishstate[]','scheduled',( $edit_post->publish_state == 'scheduled' ) ? 'true' : '', ['id'=>'scheduled','class'=>'publish_state']) }}
	            @endif
	        @else


	            {{ Form::label('editing',Lang::get('posts/form.editing')) }}
	            {{ Form::radio('publishstate[]','editing','true', ['id'=>'editing','class'=>'publish_state']) }}
	            @if (Auth::hasPermission('publish'))
	                {{ Form::label('published',Lang::get('posts/form.publish'), ['class'=>'increase-margin-left']) }}
	                {{ Form::radio('publishstate[]','published','', ['id'=>'published','class'=>'publish_state']) }}

	                {{ Form::label('scheduled',Lang::get('posts/form.schedule'), ['class'=>'increase-margin-left']) }}
	                {{ Form::radio('publishstate[]','scheduled','', ['id'=>'scheduled','class'=>'publish_state']) }}
	            @endif
	        @endif
	    </div>

	    @if (Auth::hasPermission('publish'))
	        <div class="form-group">
	            <div class="input-group input-large ">
	                <div id="publish-date" class="input-append date">
	                    <span class="add-on">
	                    @if($updating)
	                        {{ Form::text(
	                            'publish_date',
	                            ($edit_post->publish_state == 'scheduled') ? $edit_post->publish_date : '',
	                            [
	                                'data-format'=>'yyyy-MM-dd hh:mm:ss',
	                                'id'=>'date-timepicker1',
	                                'class' => 'form-control',
	                                'placeholder' => Lang::get('posts/form.publish_date')
	                            ]
	                        )}}
	                    @else
							<div class="input-group">
								<input id="date-timepicker1"  type="text" class="form-control">
							</div>
	                    @endif
	                    </span>
	                </div>

	            </div>
	        </div>
	    @endif
	</div>
