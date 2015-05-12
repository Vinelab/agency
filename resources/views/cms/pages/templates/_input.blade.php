
{{ Form::text($placeholder, isset($value) ? $value : '' , [
    'class'=>'form-control input-lg video-url-input',
    'name' => $name,
    'placeholder'=> $placeholder,
    'required',
    isset($state) ? $state : ''
])}}
<br>
