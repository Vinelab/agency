@section('content')

	{{ Form::open([
	    'url'    => URL::route('cms.administration.updatePassword') ,
	    'class'  => 'form-horizontal',
	    'role'   =>'form',
	]) }}

 	<h3 class="row header smaller lighter blue">
        <span class="col-xs-12">{{Lang::get('resetPassword.change_password')}}</span>
    </h3>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
            {{Lang::get('resetPassword.old_password')}}
        </label>
        <div class="col-sm-9">
            <div class="clearfix">
                <input type="password" name="old_password" id="old_password" class="col-xs-10 col-sm-5">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
            {{Lang::get('resetPassword.new_password')}}
        </label>
        <div class="col-sm-9">
            <div class="clearfix">
                <input type="password" name="new_password" id="new_password" class="col-xs-10 col-sm-5">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
        	{{Lang::get('resetPassword.retype_new_password')}}
        </label>
        <div class="col-sm-9">
            <div class="clearfix">
                <input type="password" name="retype_new_password" id="retype_new_password" class="col-xs-10 col-sm-5">
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