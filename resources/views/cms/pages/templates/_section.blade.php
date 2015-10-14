{{-- category (subsection) drop down --}}
<div class="form-group">
    {{Form::label("content",Lang::get("news/news.content"),["class"=>"col-sm-3 input-lg control-label no-padding-right","for"=>"content"])}}

    <div class="col-sm-9">
        <select class="col-sm-5 input-lg" name="section" id="section">
            @foreach(Which::children() as $content)
                <option {{($content->id == (($updating) ? $model->section->id : Which::category()->id )) ? 'selected=selected' :''}} value="{{$content->alias}}">{{$content->title}}</option>
            @endforeach
        </select>
    </div>
</div>
