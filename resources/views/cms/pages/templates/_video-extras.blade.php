<div class="form-group">
    <div class="col-sm-4">
        {{ Form::text('title', '' , [
                 'class'=>'form-control input-lg',
                 'id'=>'end-input',
                 'name' => $type.'-end[]',
                 'placeholder'=> Lang::get('episode/episode.set-end-time')
             ]) }}
    </div>
    <div class="col-sm-8">
        {{ Form::text('title', '' , [
                 'class'=>'form-control input-lg',
                 'id'=>'end-input',
                 'name' => $type.'-title[]',
                 'placeholder'=> Lang::get('episode/episode.override-default-title')
             ]) }}
    </div>
</div>
