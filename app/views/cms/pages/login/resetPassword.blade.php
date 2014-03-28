{{-- @author Abed Halawi <abed.halawi@vinelab.com> --}}

<!DOCTYPE html>
<html lang="en">
    <head>
        @include('cms.layout.templates.head')
    </head>

    <body class="login-layout">
        <div class="main-container">
            <div class="main-content">

                @include('cms.layout.templates.alerts')

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <i class="icon-leaf green"></i>
                                    <span class="red">Agency</span>
                                    <span class="white">CMS</span>
                                </h1>
                                <h4 class="blue">&copy;{{date('Y')}} Vinelab</h4>
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                           		<div class="widget-body">
    <div class="widget-main">
        <h4 class="header red lighter bigger">
            <i class="icon-key"></i>
            {{Lang::get('resetPassword.reset_password')}}
        </h4>

        <div class="space-6"></div>
        <p>
        	{{Lang::get('resetPassword.enter_your_new_password')}}
        </p>

        {{ Form::open(['url' => URL::route('cms.password.change'), 'method'=>'POST']) }}
            <fieldset>
                <label class="block clearfix">
                    <span class="block input-icon input-icon-right">
                        <input readonly type="email" name="email" class="form-control" value={{$user->email}} />
                        <i class="icon-envelope"></i>
                    </span>
                </label>
                {{Lang::get('resetPassword.new_password')}}
                <label class="block clearfix">
                    <span class="block input-icon input-icon-right">
						<input type="password" id="form-field-1" name="password"  class="form-control">
                    </span>
                </label>

                {{Lang::get('resetPassword.retype_new_password')}}
                <label class="block clearfix">
                    <span class="block input-icon input-icon-right">
						<input type="password" id="form-field-1" name="password-conf"  class="form-control">
                    </span>
                </label>
                <input type="hidden" name="code" value="{{$user->getCode()}}"


                <div class="clearfix">
                    
                    {{ Form::submit('Send Me',['class'=> 'width-35 pull-right btn btn-sm btn-danger']) }}

                </div>
            </fieldset>
        {{ Form::close() }}
    </div><!-- /widget-main -->

</div><!-- /widget-body -->

                            </div><!-- /position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->

        <script type="text/javascript">
            window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

        <script type="text/javascript">
            if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
        </script>

        <!-- inline scripts related to this page -->

        <script type="text/javascript">
            function show_box(id) {
             jQuery('.widget-box.visible').removeClass('visible');
             jQuery('#'+id).addClass('visible');
            }       
        </script>
        @section('scripts')
            @include('cms.layout.templates.scripts')
        @show
    </body>
</html>



