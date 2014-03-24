@section('content')

	{{ Form::open([
	    'url'    => URL::route('cms.dashboard.profile.udpate') ,
	    'class'  => 'form-horizontal',
	    'role'   =>'form',
	]) }}

    <h3 class="row header smaller lighter blue">
        <span class="col-xs-12">Info</span>
    </h3>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
            {{Lang::get('profile.name')}}
        </label>
        <div class="col-sm-9">
            <div class="clearfix">
                <input type="text" placeholder="Name" name="name" id="name" value="{{$admin->name}}" class="col-xs-10 col-sm-5">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
            {{Lang::get('profile.email')}}
        </label>
        <div class="col-sm-9">
            <div class="clearfix">
                <input type="email" placeholder="Email" name="email" id="email" value="{{$admin->email}}" class="col-xs-10 col-sm-5">
            </div>
        </div>
    </div>

    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-6">

            <button type="submit" class="btn btn-block btn-lg btn-primary">
                <i class="bigger-120 icon-ok"></i> {{Lang::get('resetPassword.save')}}
            </button>

            <a href="{{ URL::route('cms.administration') }}" role="button" class="btn btn-block btn-lg">
                <i class="bigger-120 icon-undo"></i> {{Lang::get('resetPassword.cancel')}}
            </a>

        </div>
    </div>

{{Form::close()}}



@stop

@section('scripts')
    @parent

@stop

@include('cms.layout.master')