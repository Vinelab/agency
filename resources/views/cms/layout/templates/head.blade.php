<meta charset="utf-8" />
<title>{{Config::get('agency.title')}}</title>

<meta name="description" content="Agency Platform Management" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- basic styles -->

<link href="{{Cdn::asset('/assets/css/bootstrap.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ Cdn::asset('/assets/css/font-awesome.min.css') }}" />

<!--[if IE 7]>
  <link rel="stylesheet" href="{{Cdn::asset('/assets/css/font-awesome-ie7.min.css')}}" />
<![endif]-->

<!-- page specific plugin styles -->

<!-- fonts -->

<link rel="stylesheet" href="{{Cdn::asset('/assets/css/ace-fonts.css')}}" />

<!-- ace styles -->

<link rel="stylesheet" href="{{ Cdn::asset('/assets/css/jquery-ui.min.css') }}" />
<link rel="stylesheet" href="{{ Cdn::asset('/assets/css/ui.jqgrid.css') }}" />

<link rel="stylesheet" href="{{ Cdn::asset('/assets/css/ace.min.css') }}" />
<link rel="stylesheet" href="{{ Cdn::asset('/assets/css/ace-rtl.min.css') }}" />
<link rel="stylesheet" href="{{ Cdn::asset('/assets/css/ace-skins.min.css') }}" />

<!--[if lte IE 8]>
    <link rel="stylesheet" href="{{Cdn::asset('/assets/css/ace-ie.min.css')}}" />
<![endif]-->

<!--[if lte IE 9>
    <link rel="stylesheet" href="{{Cdn::asset('/assets/css/ace-ie.min.css')}}" />
<![endif]-->

<!-- inline styles related to this page -->



<!--[if !IE]> -->
<script src="{{Cdn::asset('/assets/js/jquery.min.js')}}">

    <script type="text/javascript">
    window.jQuery || document.write("<script src='{{Cdn::asset('/assets/js/jquery.min.js')}}'>"+"<"+"/script>");
</script>
<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='{{Cdn::asset('/assets/js/jquery1x.min.js')}}>"+"<"+"/script>");
</script>
<![endif]-->


<!-- ace settings handler -->
<script src="{{Cdn::asset('/assets/js/ace-extra.min.js')}}"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!--[if lt IE 9]>
<script src="{{Cdn::asset('/assets/js/html5shiv.js')}}"></script>
<script src="{{Cdn::asset('/assets/js/respond.min.js')}}"></script>
<![endif]-->

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
