<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Bootstrap Core CSS -->
    {{--{{ HTML::style('css/bootstrap.min.css') }}--}}

    <!-- Custom CSS -->
    {{--{{ HTML::style('packages/landing-page/css/landing-page.css') }}--}}
    {{ HTML::style('css/navbar.css') }}

    <!-- Custom Fonts -->
    {{--{{ HTML::style('font-awesome-4.2.0/css/font-awesome.min.css') }}--}}

    @yield('head_css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>-->
    <!--<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->
    {{--<![endif]-->--}}


    @yield('head_script')

</head>

<body>

@if(Session::has('global'))
    <p>{{ Session::get('global') }}</p>
@endif

@include('layout.landing-navigation')

@yield('content')
</body>
</html>