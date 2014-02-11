{{-- @author Abed Halawi <abed.halawi@vinelab.com --}}

<?php $updating = isset($edit_admin); ?>

{{ Form::open([
    'url'    => $updating ?
                    URL::route('cms.administration.update', $edit_admin->id) :
                    URL::route('cms.administration.store'),
    'method' => $updating ? 'PUT' : 'POST',
    'class'  => 'form-horizontal',
    'role'   =>'form',
    'id'     => 'admin-info'
]) }}

    {{-- Info --}}

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

    {{-- Access --}}

    <h3 class="row header smaller lighter blue">
        <span class="col-xs-12">Access</span>
    </h3>

    <div class="row">

        {{-- Najem Access --}}
        <div class="col-sm-6 widget-container-span">
            <div class="widget-box">

                <div class="widget-header header-color-blue">
                    <h4 class="lighter">Stara Academy CMS</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">

                        <table class="table table-striped table-bordered table-hover">

                            <thead class="thin-border-bottom">
                                <tr>
                                    <th>
                                        <i class="icon-circle-blank"></i>
                                        Section
                                    </th>
                                    <th>
                                        <i class="icon-bookmark"></i>
                                        Role
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($Agency_sections as $section)

                                <tr>
                                    <td>{{ $section->title }}</td>
                                    <td>
                                        <?php $selected_role = isset($edit_admin_agency_roles[$section->id]) ?
                                                                    $edit_admin_agency_roles[$section->id] : 0; ?>
                                        {{ Form::select("Agency_sections[$section->alias]",
                                            $roles,
                                            $selected_role) }}
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-6">

            <button type="submit" class="btn btn-block btn-lg btn-primary">
                <i class="bigger-120 icon-ok"></i> Save
            </button>

            <a href="{{ URL::route('cms.administration') }}" role="button" class="btn btn-block btn-lg">
                <i class="bigger-120 icon-undo"></i> Cancel
            </a>

        </div>
    </div>

{{Form::close()}}


 @if ($updating)


<div class="clearfix form-actions">
    <div class="col-md-offset-5 col-md-2">

        {{ Form::open([
            'url'    => URL::route('cms.administration.destroy', $edit_admin->id),
            'method' => 'DELETE',
            'role'   =>'form',
            'id' => 'admin-delete'
        ]) }}

            <button type="button" id="delete-admin-button" class="btn btn-sm btn-block btn-danger">
                <i class="icon-remove"></i> Delete
            </button>

        {{ Form::close() }}
    </div>
</div>

@endif


@section('scripts')
    @parent

    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@stop