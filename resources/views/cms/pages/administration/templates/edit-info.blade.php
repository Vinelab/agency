<h3 class="row header smaller lighter blue">
    <span class="col-xs-12">Info</span>
</h3>

<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
        Name
    </label>
    <div class="col-sm-9">
        <div class="clearfix">
            <input type="text" placeholder="Name" name="info[name]" id="name" value="{{ $edit_admin->name or '' }}" class="col-xs-10 col-sm-5">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
        Email
    </label>
    <div class="col-sm-9">
        <div class="clearfix">
            <input type="email" placeholder="Email" name="info[email]" id="email" value="{{ $edit_admin->email or '' }}" class="col-xs-10 col-sm-5">
        </div>
    </div>
</div>
