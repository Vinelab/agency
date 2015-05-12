{{-- @author Abed Halawi <abed.halawi@vinelab.com> --}}

@section('content')
    <div class="col-xs-12">

        @if (Auth::hasPermission('create'))
        <div class="row">
            <a href="{{ URL::route('cms.administration.create') }}" class="btn btn-primary">
                <span class="ace-icon  fa fa-plus"></span>
                New Admin
            </a>
        </div>
        @endif

        <div class="space-4"></div>

        <div class="row">

            <div class="table-responsive">
                <table id="administrators-table" class="table tabl-striped table-bordered table-hover">

                    <thead>
                        <tr>
                            @if (Auth::hasPermission('update'))
                            <th></th>
                            @endif
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Last updated</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                            @if (Auth::hasPermission('update'))
                                <td class="center">
                                    <div class="btn-group">
                                        <a href="{{ URL::route('cms.administration.edit', $admin->id) }}" class="btn btn-xs btn-info">
                                            <i class="ace-icon  fa fa-edit"></i>
                                            Edit
                                        </a>
                                    </div>

                                </td>
                            @endif
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->created_at }}</td>
                                <td>{{ $admin->updated_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@stop

@section('scripts')
    @parent

    <script type="text/javascript" src="{{ Cdn::asset('/cms/js/administration.js') }}"></script>
@stop

@include('cms.layout.master')
