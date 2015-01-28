{{-- @author Abed Halawi <abed.halawi@vinelab.com> --}}


@section('content')

    @include('cms.pages.administration.templates.form')
@stop

@section('scripts')
    @parent

    <script type="text/javascript" src="{{ Cdn::asset('/cms/js/administration.js') }}"></script>
@stop

@include('cms.layout.master')
