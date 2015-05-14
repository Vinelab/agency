
{{-- photos section --}}
<div class="form-group">
    <label class="control-label"> {{Lang::get('posts/form.add_photo')}} </label>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <button id="uploader" class="btn btn-primary">{{Lang::get('posts/form.add_new_photo')}}</button>
        <button id="choose_from_existing_photos" type="button" class="btn btn-primary" data-toggle="modal" data-target=".selectExistingPhotosModal">{{Lang::get('posts/form.choose_from_existing_photos')}}</button>
    </div>
    <div class="hidden" id="photos_holder">
        {{-- in case an error was returned then get back all the previously sent photos and re-inject them in the form --}}

        @if(Input::old('photos'))
            @foreach(Input::old('photos') as $key => $photo)
                <input type="hidden" name="photos[{{$key}}][original]"  value="{{$photo['original']}}">
                <input type="hidden" name="photos[{{$key}}][small]"     value="{{$photo['small']}}">
                <input type="hidden" name="photos[{{$key}}][thumbnail]" value="{{$photo['thumbnail']}}">
                <input type="hidden" name="photos[{{$key}}][square]"    value="{{$photo['square']}}">
            @endforeach
        @endif
    </div>
    <div class="hidden" id="existing_photos_holder"></div>
</div>

{{-- here is where the photos gets displayed for management --}}
<div class="form-group">
    <div id="photos_holder_display">

        {{-- if updating or returned from an error then do the following to display the photos --}}
        @if($updating && ! $model->images->isEmpty() || Input::old('photos'))
            @foreach((Input::old('photos') ? Input::old('photos') : $model->images) as $photo)

                <div class='col-xs-3 col-md-3 col-lg-3 photos-wrapper' id="{{$updating ? $model->slug . $photo->id : ''}}">
                    <a class='thumbnail injected-photos'>
                        <img src='{{$photo['thumbnail']}}'>
                    </a>
                    @if($updating)
                        <a class="remove-photo" slug="{{$model->slug}}" photo="{{$photo->id}}" href='#'>
                            <i class='fa fa-trash-o'></i>
                        </a>
                    @endif
                </div>

            @endforeach
        @endif
    </div>
    <div id="existing_photos_holder_display"></div>
</div>


