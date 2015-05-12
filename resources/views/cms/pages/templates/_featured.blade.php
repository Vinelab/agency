<div class="form-group">
    <label class="control-label"> {{Lang::get('posts/form.featured')}} </label>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <label>
            <input type="checkbox" name="featured" class="ace" {{ isset($model) ? $model->featured ? 'checked' : '' : ''}} />
            <span class="lbl"> {{Lang::get('posts/form.is_featured')}} </span>
        </label>
    </div>
</div>
