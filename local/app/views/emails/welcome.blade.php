Hey {{$first_name}} {{ $last_name }}, Welcome to Lawyer Friend. <br>
Please click <a href="{{ URL::route('verify', ['confirmation'=>$activation_code]) }}"> here</a> to activate your account