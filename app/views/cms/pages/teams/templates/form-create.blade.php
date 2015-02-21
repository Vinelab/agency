@section('head')

    @parent

    <link rel="stylesheet" href="{{Cdn::asset('/assets/css/jquery-ui-1.10.3.full.min.css')}}" >
    <link rel="stylesheet" href="{{Cdn::asset('/css/jquery.Jcrop.min.css')}}" />
    <link rel="stylesheet" href="{{Cdn::asset('/css/teams/create.css')}}">
    <link rel="stylesheet" type="text/css" href="/dist/css/mr-uploader.min.css">

@stop

<?php $updating = isset($updating_team); ?>


{{ Form::open([
    'url'    => ($updating) ? route('cms.teams.update', $updating_team->slug) : route('cms.teams.store') ,
    'method' => ($updating) ? 'PUT' : 'POST',
    'class'  => 'form-horizontal',
    'role'   =>'form',
    'id'     => 'team-info',
    'enctype' => 'multipart/form-data'

]) }}

    @if(isset($teams) and ! empty($team))
        {{ Form::hidden('team_id', $team->id) }}
    @endif




    <div class="form-group">
        <div class="row">
            <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> </label>
            <div class="col-sm-8">
                <button type='button' class='uploader'>Photo</button>
            </div

        </div>
    </div>

    <div class="space-10"></div>
    
    <div class="row">
        <div class="align-center" id="photo-crop-container">
            @if ($updating and $updating_team->image)
                <img id="photo-crop" src="{{$updating_team->image->thumbnail}}">
            @else
                <img id="photo-crop">
            @endif

            {{Form::hidden("photo[original]", ($updating ? $updating_team->image->original : '')  , ['id' => 'original'])}}
            {{Form::hidden("photo[thumbnail]", ($updating ? $updating_team->image->thumbnail : ''), ['id' => 'thumbnail'])}}
            {{Form::hidden("photo[square]", ($updating ? $updating_team->image->square : ''), ['id' => 'square'])}}
            {{Form::hidden("photo[small]", ($updating ? $updating_team->image->small : ''), ['id' => 'small'])}}
        </div>
    </div>

    <div class="space-10"></div>

    <div class="form-group">
        <div class="row">

            <label class="col-sm-2 control-label no-padding-right" for="title">
                {{Lang::get('teams.labels.title')}}
            </label>
            <div class="col-sm-8">
                <input type="text" id="title" name="title" value="{{$updating_team->title or ''}}" placeholder="{{Lang::get('teams.labels.title')}}" class="form-control">
            </div>

        </div>
    </div>


    <div class="form-group">
        <div class="align-center">
            <input type="submit" id="submit-btn" onclick="create()" class="btn btn-default" value="@if ($updating) {{Lang::get('teams.buttons.update')}} @else {{Lang::get('teams.buttons.create')}} @endif">
        </div>
    </div>
    
{{ Form::close() }}


    <div class='form-group'>
        <div class='align-right'>
            @if($updating)
                {{ Form::open([
                    'url'    => route('cms.teams.destroy', $updating_team->id),
                    'method' => 'DELETE',
                    'class'  => 'form-horizontal',
                    'role'   =>'form',
                ]) }}
                    {{Form::submit(Lang::get('teams.buttons.delete'),['class'=>'btn btn-danger'])}}
                {{ Form::close()}}
            @endif
        </div>
    </div>

    <div id="loader-container">
        <img id="img-loader" src="{{Cdn::asset('/cms/images/server.gif')}}">
    </div>



@include('cms.pages.post.templates.routes')

@section('scripts')
    @parent

    <script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/dist/js/mr-uploader.all.min.js"></script>

    <script type="text/javascript">
        upload = $(".uploader").mrUploader({uploadUrl: Routes.cms_team_photos_store});
        upload.on('upload', function(e, data) {
            console.log(data);
            $("#photo-crop").attr('src',data.response.photos.thumbnail);
            $("#original").val(data.response.photos.original);
            $("#thumbnail").val(data.response.photos.thumbnail);
            $("#square").val(data.response.photos.square);
            $("#small").val(data.response.photos.small);

        });
        
    </script>

    <script src="{{Cdn::asset('/js/jquery.Jcrop.min.js')}}"></script>
    <script type="text/javascript">
        var URL  = {
            photo_upload: "{{route('cms.teams.photos.store')}}",
            location: "{{Config::get('media.location')}}"
        };
    </script>
    <script src="{{Cdn::asset('/cms/js/teams/edit.js')}}"></script>

    <script type="text/javascript" src="/dist/js/mr-uploader.all.min.js"></script>

    <script type="text/javascript">
        console.log($.fn.mrUploader);
        upload = $(".uploader").mrUploader({uploadUrl: Routes.cms_post_tmp});
        console.log(upload)
    </script>
@stop
