Welcome {{$name}},

Please verify your email by clicking on the link below:

{{URL::route('auth.verify')}}?code={{ $verification_code }}
