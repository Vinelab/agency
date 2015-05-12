<div dir="rtl">
    <div>{{Lang::get('email.name')}}: </div>
    <div><span>{{$name}}</span></div>
    <br>
    <div>{{Lang::get('email.email')}}: </div>
    <div><span>{{$email}}</span></div>
    <br>
    <div>{{Lang::get('email.phone')}}: </div>
    <div><span>{{$phone}}</span></div>
    <br>
    <div>{{Lang::get('email.country')}}: </div>
    <div><span>{{$country}}</span></div>
    <br>
    <div>{{Lang::get('email.reasons')}}: </div>
    <div><span>{{nl2br($message_body)}}</span></div>
    <br>
</div>
