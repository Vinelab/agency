<body>
    {{Lang::get('email.welcome')}} {{$admin->getName()}}!
    <br/><br />
    {{Lang::get('email.reset_password_body')}}<a href="{{URL::route('cms.password.reset',$admin->getCode())}}"> {{Lang::get('email.click_here')}}</a>
</body>
