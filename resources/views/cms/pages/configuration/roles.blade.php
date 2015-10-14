{{-- @author Abed Halawi <abed.halawi@vinelab.com> --}}
<table id="roles-table"></table>
<div id="roles-pager"></div>

@section('head')
    @parent

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.css') }}" />
@stop

@section('scripts')
    @parent

    <script type="text/javascript" src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
@stop