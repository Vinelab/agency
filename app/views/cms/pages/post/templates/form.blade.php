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
	
    <div class="form-group">

		{{Form::label("title","Title",["class"=>"col-sm-3 control-label no-padding-right","for"=>"title"])}}

		<div class="col-sm-9">
			<div class="clearfix">
				@if($updating)
				    {{Form::input("text","info[title]","$edit_post->title",["id"=>"title","class"=>"col-xs-10 col-sm-5","placeholder"=>"Title"])}}
				    	<input type="hidden" name="post_id" value="{{$edit_post->id}}" id="post_id">

				@else
					{{Form::input("text","info[title]","",["id"=>"title","class"=>"col-xs-10 col-sm-5","value"=>"","placeholder"=>"Title"])}}
				@endif
            </div>
		</div>
	</div>

	<div class="form-group">

		{{Form::label("body","Body",["class"=>"col-sm-3 control-label no-padding-right","for"=>"body"])}}

		<div class="col-sm-9">
			<div class="clearfix">
				@if($updating)
				    {{Form::textarea("info[title]","$edit_post->body",["id"=>"body","class"=>"col-xs-10 col-sm-5","placeholder"=>"Body"])}}
				@else
					 {{Form::textarea("info[title]","",["id"=>"body","class"=>"col-xs-10 col-sm-5","placeholder"=>"Body"])}}
				@endif

            </div>
		</div>
	</div>	

	<div class="form-group">

		{{Form::label("content","Content",["class"=>"col-sm-3 control-label no-padding-right","for"=>"content"])}}

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

	<div class="form-group">
		
		<label class="col-sm-3 control-label no-padding-right" for="form-field-tags">Tag input</label>

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
        <span class="col-xs-12">Add Images</span>
    </h3>

	@if($updating)
		<!-- show all photos -->
		<div class='form-group well'>
		    <div class="row">
		        <ul class="ace-thumbnails">
		            <?php 
		            ?>
		            @if( count($media) > 0 )
		                @foreach($media as $media_element)
		                	@if($media_element->type()=="image")
		                		<?php
		                		?>
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
        <span class="col-xs-12">Add Videos</span>
    </h3>

    <div class="row">
		<div class="col-xs-12 col-sm-3">
			<div class="input-group">
				<input type="text" class="form-control search-query" placeholder="Youtbe video url" id="yt_video_txt">
				<span class="input-group-btn">
					<button type="button" onclick="addYoutubeVideo()" class="btn btn-purple btn-sm">
						Add
						<i class="icon-search icon-on-right bigger-110"></i>
					</button>
				</span>
			</div>
		</div>
	</div>
	
	<ul id="videos_list">
		
    </ul>


    <div class="space-12"></div>

    {{Form::button("Submit",["onclick"=>"submitForm()"])}}


