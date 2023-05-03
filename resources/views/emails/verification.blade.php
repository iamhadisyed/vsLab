@extends('adminlte::layouts.auth')

@section('htmlheader_title')
    Password recovery
@endsection

@section('content')

<body class="login-page">
    <div id="app">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ url('userhome') }}"><b>Admin</b>LTE</a>
            </div><!-- /.login-logo -->

        <h2>The verification email have been sent to your registrated. Please use the link in the email to active your account.</h2>

    </div><!-- /.login-box -->
    </div>

    @include('adminlte::layouts.partials.scripts_auth')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>

@endsection
