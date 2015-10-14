<body dir="rtl">
    <h2>{{Lang::get('email.welcome_to_cms')}}!</h2>
    <br /><br />
    {{Lang::get('email.visit_this_link')}}
    <a href="{{Config::get('app.cms_url')}}">{{ Config::get('app.cms_url') }}</a>
    <br /><br />
    {{Lang::get('email.use_this_password_to_login')}}: <br>
    <h3>{{$password}}</h3>
</body>
