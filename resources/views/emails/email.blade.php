<!DOCTYPE html>
<html lang="en">
<body>
<h2><i class="fa fa-pencil"></i> Registration request on: ThoThLab</h2>
<div>
    {{--<h3>Dear: {!!$username!!}</h3>--}}
    <strong>You account has been created. However, before you can use it you need to confirm your email address first by clicking the
        <a href="{{ url('/verifyemail/'.$email_token) }}">Following Link</a></strong>
    <br/>
</div>
</body>
</html>