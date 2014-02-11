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

    <div class="form-group">

		{{Form::label("title","Title",["class"=>"col-sm-3 control-label no-padding-right","for"=>"title"])}}

		<div class="col-sm-9">
			<div class="clearfix">
				@if($updating)
				    {{Form::input("text","info[title]","$edit_post->title",["id"=>"title","class"=>"col-xs-10 col-sm-5","placeholder"=>"Title"])}}
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

	{{--Add Images --}}

	<h3 class="row header smaller lighter blue">
        <span class="col-xs-12">Add Images</span>
    </h3>

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
	
	<div class="space-12"></div>
	
	<ul id="videos_list">
		
    </ul>


    <div class="space-12"></div>

    {{Form::button("Submit",["onclick"=>"submitForm()"])}}


