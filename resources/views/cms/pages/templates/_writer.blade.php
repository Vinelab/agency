{{-- writer drop down --}}

<div class="form-group">
    <label class="control-label"> {{Lang::get("news/news.author")}}* </label>
</div>
<div class="form-group">
    <div class="col-xs-8"></div>
    <div class="col-xs-4">
        <select class="form-control input-lg" name="writer" id="writer">
            @foreach($writers as $writer)
                <option {{(($updating) and ($writer->id == $model->id))? 'selected=selected' :''}} value="{{$writer->id}}">{{$writer->name}}</option>
            @endforeach
        </select>
    </div>
</div>
