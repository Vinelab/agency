{{-- @author Abed Halawi <abed.halawi@vinelab.com> --}}

@section('content')

    @include('cms.pages.configuration.sections')

    @include('cms.pages.configuration.roles')

    @include('cms.pages.configuration.permissions')

@stop

@section('scripts')
    @parent

    <script type="text/javascript" src="{{ asset('cms/js/configuration.js') }}"></script>
@stop

@include('cms.layout.master')