{{-- @author Abed Halawi <abed.halawi@vinelab.com> --}}
<!DOCTYPE html>
<html lang="en">
    <head>
        @section('head')
            @include('cms.layout.templates.head')
        @show
    </head>

    <body>
        <div class="navbar navbar-default" id="navbar">

            <script type="text/javascript">
                try{ace.settings.check('navbar' , 'fixed')}catch(e){}
            </script>

            @include('cms.layout.templates.navbar.navbar')

        </div>

        <div class="main-container" id="main-container">

            <script type="text/javascript">
                try{ace.settings.check('main-container' , 'fixed')}catch(e){}
            </script>

            <div class="main-container-inner">
                <a class="menu-toggler" id="menu-toggler" href="#">
                    <span class="menu-text"></span>
                </a>

                <div class="sidebar" id="sidebar">
                    <script type="text/javascript">
                        try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
                    </script>

                    @include('cms.layout.templates.sidebar.menu')

                    <script type="text/javascript">
                        ace.data.set('settings', 'sidebar-collapsed', 1);
                        try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
                    </script>
                </div>

                <div class="main-content">
                    <div class="breadcrumbs" id="breadcrumbs">

                        <script type="text/javascript">
                            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                        </script>
                        @include('cms.layout.templates.header.sitemap')

                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                    <!-- Main Content -->
                                    @include('cms.layout.templates.alerts')

                                    @yield('content')
                            </div>
                        </div>
                    </div>
                </div>

                @include('cms.layout.templates.ace-setting')
            </div><!-- /.main-container-inner -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="icon-double-angle-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->

        @section('scripts')
            @include('cms.layout.templates.scripts')
        @show
    </body>
</html>
