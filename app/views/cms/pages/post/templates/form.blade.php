<?php
 $updating = isset($edit_post);
?>

{{ Form::open([
    'url'    => $updating ?
                    URL::route('cms.post.update', $edit_post->id) :
                    URL::route('cms.post.store'),
    'method' => $updating ? 'PUT' : 'POST',
    'class'  => 'form-horizontal',
    'role'   =>'form',
    'id'     => 'admin-info'
]) }}
	
	
	<input type="hidden" name="updating" value="{{$updating}}" id="updating">

	{{--Title Input--}} 
    <div class="form-group">
		<div class="col-sm-9">
			<div class="clearfix">
				@if($updating)
				    {{Form::input("text","info[title]","$edit_post->title",["id"=>"title","class"=>"col-xs-10 col-sm-5"])}}
				    <input type="hidden" name="post_id" value="{{$edit_post->id}}" id="post_id">

				@else
					{{Form::input("text","info[title]","",["id"=>"title","class"=>"col-xs-10 col-sm-5","value"=>"","placeholder"=>Lang::get('posts/form.title')])}}
				@endif
            </div>
		</div>
	</div>
	
	{{--Body Input--}}
	<div class="form-group">
		<div class="col-sm-9">
			<div class="clearfix">
				
				<div class="form-group">
					<div id="editor" class="wysiwyg-editor"></div>
				</div>
            </div>
		</div>
	</div>

	{{--Content Input--}}
	<div class="form-group">

		{{Form::label("content",Lang::get("posts/form.content"),["class"=>"col-sm-1 control-label no-padding-right","for"=>"content"])}}

		<div class="col-sm-9">
			<div class="clearfix">
				<select name="section" id="section">

					@if($updating)
						@foreach($contents as $content)
							@if($content->id==$edit_post->section_id)
								<option value="{{$content->id}}" selected>{{$content->title}}</option>
							@else
								<option value="{{$content->id}}">{{$content->title}}</option>
							@endif
						@endforeach
					@else 

						@foreach($contents as $content)
							<option value="{{$content->id}}">{{$content->title}}</option>
						@endforeach

					@endif
					
				</select>

	        </div>
		</div>
	</div>

	{{--Tags Input--}}
	<div class="form-group">
		
		<label class="col-sm-1 control-label no-padding-right" for="form-field-tags">Tag input</label>

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
					<input type="text" name="tags" id="form-field-tags"  placeholder="Enter tags ..." style="display: none;">

				@endif

		</div>
	</div>

	{{--Add Images --}}
	<h3 class="row header smaller lighter blue">
        <span class="col-xs-12">{{Lang::get("posts/form.add_images")}}</span>
    </h3>

	{{--when updating display images--}}
	@if($updating)
		<!-- show all photos -->
		<div class='form-group well'>
		    <div class="row">
		        <ul class="ace-thumbnails">
		            
		            @if( count($media) > 0 )
		                @foreach($media as $media_element)
		                	@if($media_element->type()=="image")
		                		
			                    <li>
			                        <a href="{{$media_element->url}}" data-rel="colorbox">
			                            <img alt="150x150" src="{{$media_element->url}}" width="200px" height="200px;"/>
			                            <div class="text">
			                                <div class="inner">Preview</div>
			                            </div>
			                        </a>
			                        @if ($admin_permissions->has('delete'))
				                        <div class="tools tools-bottom">
				                            <a href="javascript:void(0)" id="delete_cover_photos" onClick="removePhotos({{$media_element->id}}, {{$edit_post->id}})">
				                                <i class="icon-remove red"></i>
				                            </a>
				                        </div>
                       				 @endif
			                    </li>
			                @endif
		                @endforeach
		            @endif
		        </ul>
		    </div>
		</div>
	@endif

    <div class="col-sm-9">
    	<input multiple="" type="file" id="images" onChange="FilesUploadChange()" />
    </div>

    <div class="space-12"></div>

    <ul id="croped-images-list">
    </ul>

    <div class="space-12"></div>

    <h3 class="row header smaller lighter blue">
        <span class="col-xs-12">{{Lang::get("posts/form.add_videos")}}</span>
    </h3>

    <div class="row">
		<div class="col-xs-12 col-sm-3">
			<div class="input-group">
				<input type="text" class="form-control search-query" placeholder="{{Lang::get('posts/form.yt_video_url')}}" id="yt_video_txt">
				<span class="input-group-btn">
					<button type="button" onclick="addYoutubeVideo()" class="btn btn-purple btn-sm">
						{{Lang::get("posts/form.add_yt_btn")}}
						<i class="icon-search icon-on-right bigger-110"></i>
					</button>
				</span>
			</div>
		</div>
	</div>

	{{--display videos--}}
	<ul id="videos_list">
		
		@if( isset($media) && count($media) > 0 )
                @foreach($media as $key => $media_element)

                	@if($media_element->type()=="video")
                		<?php
                		?>
                        <li class="video_item" id="video_item_{{$key}}">
                        	<div class="video-container">
                        		<div class="yt-img">
                        			<img src="{{$media_element->thumbnail}}" class="yt-img-thumbnail">
                        		</div>
                        		<div class="yt-data">
                        			<input type="text" id="yt-title-{{$key}}" class="yt-title" value="{{$media_element->title}}">
                        			<textarea id="yt-desc-{{$key}}" class="yt-desc">{{$media_element->description}}</textarea>
                        			<input type="hidden" class="yt-url" id="yt-url-{{$key}}" value="{{$media_element->url}}">
                        		</div>
                        		@if ($admin_permissions->has('delete'))
	                        		<div class="yt-delete">
	                        			<button type="button" class="btn btn-xs btn-info yt-delete-btn" onclick="delete_yt({{$key}})"><i class="icon-trash"></i></button>
	                        		</div>
	                        	@endif
                        	</div>                       
                        </li>
	                @endif
                @endforeach
        @endif
    </ul>


    <div class="space-12"></div>


	<div class="control-group">
	    <div class="pull-left publish-date-margin">
	        @if($updating)
	            {{ Form::label('editing',Lang::get('posts/form.editing')) }}
	            {{ Form::radio('publishstate[]','editing', ( $edit_post->publish_state == 'editing' ) ? 'true' : '', ['id'=>'editing','class'=>'publish_state']) }}
	            @if ($admin_permissions->has('publish'))
	                {{ Form::label('published',Lang::get('posts/form.publish'), ['class'=>'increase-margin-left']) }}
	                {{ Form::radio('publishstate[]','published',( $edit_post->publish_state == 'published' ) ? 'true' : '' ,['id'=>'published','class'=>'publish_state']) }}

	                {{ Form::label('scheduled',Lang::get('posts/form.schedule'), ['class'=>'increase-margin-left']) }}
	                {{ Form::radio('publishstate[]','scheduled',( $edit_post->publish_state == 'scheduled' ) ? 'true' : '', ['id'=>'scheduled','class'=>'publish_state']) }}
	            @endif
	        @else
	            {{ Form::label('editing',Lang::get('posts/form.editing')) }}
	            {{ Form::radio('publishstate[]','editing','true', ['id'=>'editing','class'=>'publish_state']) }}
	            @if ($admin_permissions->has('publish'))
	                {{ Form::label('published',Lang::get('posts/form.publish'), ['class'=>'increase-margin-left']) }}
	                {{ Form::radio('publishstate[]','published','', ['id'=>'published','class'=>'publish_state']) }}

	                {{ Form::label('scheduled',Lang::get('posts/form.schedule'), ['class'=>'increase-margin-left']) }}
	                {{ Form::radio('publishstate[]','scheduled','', ['id'=>'scheduled','class'=>'publish_state']) }}
	            @endif
	        @endif
	    </div>

	    @if ($admin_permissions->has('publish'))

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
	                                'id'=>'datepicker',
	                                'class' => 'form-control',
	                                'placeholder' => Lang::get('artists/article.publish_date') 
	                            ] 
	                        )}}
	                    @else
	                        {{ Form::text(
	                                'publish_date', 
	                                null, 
	                                [
	                                    'data-format'=>'yyyy-MM-dd hh:mm:ss',
	                                    'id'=>'datepicker', 
	                                    'placeholder' => Lang::get('artists/article.publish_date') 
	                                ] 
	                        )}}
	                    @endif
	                    </span>
	                </div>
	                
	            </div>
	        </div>
	    @endif
	</div>

    <button onclick="submitForm()" class="btn btn-success btn-lg" id="submitBtn">
    	{{Lang::get("posts/form.submit")}} <i class="icon-spinner icon-spin orange bigger-125" id="spinner"></i>
    </button>
		
