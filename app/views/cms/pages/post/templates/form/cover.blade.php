<h3 class="row header smaller lighter blue">
        <span class="col-xs-12">{{Lang::get("posts/form.cover_photo")}}*</span>
    </h3>

    @if($updating)
		display cover image
    @endif

    <div class="col-sm-9">
    	{{ Form::file('cover', [
                        'id'        =>  'cover',
                        'onChange'  =>  'coverUploadChange()',
                        'class'     =>  'form-control choose-photo-width'
                    ]
                )}}
    </div>

    <div class="space-12"></div>
    <div class="space-4"></div><br>

	 <div class="row">
        <div class="align-center" id="photo-crop-container">
            @if ($updating and isset($edit_post->coverImage()->first()->thumbnail))
                <img id="cover-crop" src="{{$edit_post->coverImage->thumbnail}}">
            @else
                <img id="cover-crop" src="">
            @endif
        </div>
    </div>
