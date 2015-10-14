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

    @include('cms.pages.administration.templates.edit-info', compact('edit_admin'))
    {{-- Access --}}

    <h3 class="row header smaller lighter blue">
        <span class="col-xs-12">Access</span>
    </h3>

    <div class="row">

        {{-- Agency Access --}}
        @include('cms.pages.administration.templates.edit-agency-access',
            compact('agency_sections', 'edit_admin_agency_roles'))
    </div>

    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-6">

            <button type="submit" class="btn btn-block btn-lg btn-primary">
                <i class="bigger-120 ace-icon  fa fa-ok"></i> Save
            </button>

            <a href="{{ URL::route('cms.administration') }}" role="button" class="btn btn-block btn-lg">
                <i class="bigger-120 icon-undo"></i> Cancel
            </a>

        </div>
    </div>

{{Form::close()}}

{{-- Add a Delete button when updating --}}
@if ($updating)

<div class="clearfix">
    <div class="col-md-offset-5 col-md-2">

        {{ Form::open([
            'url'    => URL::route('cms.administration.destroy', $edit_admin->id),
            'method' => 'DELETE',
            'role'   =>'form',
            'id' => 'admin-delete'
        ]) }}

            <button type="button" id="delete-admin-button" class="btn btn-sm btn-block btn-danger">
                <i class="ace-icon  fa fa-remove"></i> Delete
            </button>

        {{ Form::close() }}
    </div>
</div>

@endif


@section('scripts')
    @parent

    <script src="{{ Cdn::asset('/assets/js/jquery.validate.min.js') }}"></script>
@stop
