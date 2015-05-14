{{-- cover photo section --}}
<div class="form-group">
    <label class="control-label"> {{Lang::get('posts/form.cover_photo').'*'}} </label>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <button id="cover" class="btn btn-primary">{{Lang::get('posts/form.cover_photo')}}</button>
    </div>
    <div class="hidden" id="cover_holder">
        {{-- in case an error was returned then get back the previously sent cover and re-inject it in the form --}}
        @if(Input::old('cover'))
            <input type="hidden" name="cover[original]"    value="{{Input::old('cover')['original']}}">
            <input type="hidden" name="cover[small]"       value="{{Input::old('cover')['small']}}">
            <input type="hidden" name="cover[thumbnail]"   value="{{Input::old('cover')['thumbnail']}}">
            <input type="hidden" name="cover[square]"      value="{{Input::old('cover')['square']}}">
        @endif
    </div>
</div>
{{-- here is where the cover photo gets displayed --}}
<div class="form-group">
    <div id="cover_holder_display">
        @if($updating && ! is_null($model->coverImage) || Input::old('cover'))
            <div class='col-xs-3 col-md-3 col-lg-3'>
                <a class='thumbnail'>
                    <img src='{{ Input::old('cover') ? Input::old('cover')['thumbnail'] : $model->coverImage->thumbnail}}'>
                </a>
            </div>
        @endif
    </div>
</div>

