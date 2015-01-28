<h3 class="row header smaller lighter blue">
        <span class="col-xs-12">{{Lang::get("posts/form.add_images")}}</span>
    </h3>

	{{--when updating display images--}}
	@if($updating)
		<!-- show all photos -->
		<div class='form-group well'>
		    <div class="row">
		        <ul class="ace-thumbnails">

		            @if( count($images) > 0 )
		                @foreach($images as $image)

			                    <li id="img-{{$image->id}}">
			                        <a href="{{$image->original}}" data-rel="colorbox">
			                            <img alt="150x150" src="{{$image->thumbnail}}" width="200px" height="200px;"/>
			                            <div class="text">
			                                <div class="inner">{{Lang::get("posts/form.preview")}}</div>
			                            </div>
			                        </a>

			                        @if (Auth::hasPermission('delete'))
				                        <div class="tools tools-bottom">
				                            <a href="javascript:void(0)" id="delete_cover_photos" onClick="removePhotos('{{$image->id}}', {{$edit_post->id}})">
				                                <i class="icon-remove red"></i>
				                            </a>
				                        </div>
                       				 @endif
			                    </li>
		                @endforeach
		            @endif
		        </ul>
		    </div>
		</div>
	@endif



    <div class="col-sm-9">
    	<input multiple="" type="file" id="images" onChange="filesUploadChange()" />
    </div>

    <div class="space-12"></div>
	<div>
	    <ul id="croped-images-list">
	    </ul>
	</div>
