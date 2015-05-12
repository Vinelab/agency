<!-- basic scripts -->


<script type="text/javascript">
	if("ontouchend" in document) document.write("<script src='{{Cdn::asset('/assets/js/jquery.mobile.custom.min.js')}}>"+"<"+"/script>");
</script>
<script src="{{Cdn::asset('/assets/js/bootstrap.min.js')}}"></script>
<script src="{{Cdn::asset('/assets/js/typeahead-bs2.min.js')}}"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
  <script src="{{Cdn::asset('/assets/js/excanvas.min.js')}}"></script>
<![endif]-->

<script src="{{Cdn::asset('/assets/js/jquery-ui.custom.min.js')}}"></script>
<script src="{{Cdn::asset('/assets/js/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{Cdn::asset('/assets/js/bootbox.min.js')}}"></script>
<script src="{{Cdn::asset('/assets/js/jquery.slimscroll.min.js')}}"></script>
<script src="{{Cdn::asset('/assets/js/jquery.easy-pie-chart.min.js')}}"></script>
<script src="{{Cdn::asset('/assets/js/jquery.sparkline.min.js')}}"></script>



<!-- ace scripts -->

<script src="{{Cdn::asset('/assets/js/ace-elements.min.js')}}"></script>
<script src="{{Cdn::asset('/assets/js/ace.min.js')}}"></script>

<script src="{{ Cdn::asset('/assets/js/jqGrid/jquery.jqGrid.min.js') }}"></script>
<script src="{{ Cdn::asset('/assets/js/jqGrid/i18n/grid.locale-en.js') }}"></script>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '158507434339024',
      xfbml      : true,
      version    : 'v2.1'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
<script async src="//platform.instagram.com/en_US/embeds.js"></script>
