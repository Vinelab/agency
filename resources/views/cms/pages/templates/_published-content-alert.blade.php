<div class="form-group">
    <div class="col-sm-12">
        <div class="alert alert-block alert-warning">
            {{Lang::get('posts/form.content_is_published')}}
            <i class="ace-icon fa fa-check blue"></i>
            <strong class="blue">
                {{$model->publish_date}}
            </strong>
        </div>
    </div>
</div>
