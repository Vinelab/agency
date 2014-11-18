<!-- basic scripts -->
<!--[if !IE]> -->
<script src="{{Cdn::asset('assets/js/jquery.min.js')}}">

<script type="text/javascript">
	window.jQuery || document.write("<script src='{{Cdn::asset('assets/js/jquery.min.js')}}'>"+"<"+"/script>");
</script>
<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='{{Cdn::asset('assets/js/jquery1x.min.js')}}>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
	if("ontouchend" in document) document.write("<script src='{{Cdn::asset('assets/js/jquery.mobile.custom.min.js')}}>"+"<"+"/script>");
</script>
<script src="{{Cdn::asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{Cdn::asset('assets/js/typeahead-bs2.min.js')}}"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
  <script src="{{Cdn::asset('assets/js/excanvas.min.js')}}"></script>
<![endif]-->

<script src="{{Cdn::asset('assets/js/jquery-ui.custom.min.js')}}"></script>
<script src="{{Cdn::asset('assets/js/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{Cdn::asset('assets/js/bootbox.min.js')}}"></script>
<script src="{{Cdn::asset('assets/js/jquery.slimscroll.min.js')}}"></script>
<script src="{{Cdn::asset('assets/js/jquery.easy-pie-chart.min.js')}}"></script>
<script src="{{Cdn::asset('assets/js/jquery.sparkline.min.js')}}"></script>



<!-- ace scripts -->

<script src="{{Cdn::asset('assets/js/ace-elements.min.js')}}"></script>
<script src="{{Cdn::asset('assets/js/ace.min.js')}}"></script>

<script src="{{ Cdn::asset('assets/js/jqGrid/jquery.jqGrid.min.js') }}"></script>
<script src="{{ Cdn::asset('assets/js/jqGrid/i18n/grid.locale-en.js') }}"></script>
