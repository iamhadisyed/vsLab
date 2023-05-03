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
    {{ HTML::style('css/bootstrap.min.css') }}

    <!-- Custom CSS -->
    {{ HTML::style('css/modern-business.css') }}
    {{ HTML::style('css/navbar.css') }}

    <!-- Custom Fonts -->
    {{ HTML::style('font-awesome-4.2.0/css/font-awesome.min.css') }}

    @yield('head_css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head_script')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-74455054-1', 'auto');
        ga('send', 'pageview');

    </script>
    {{--<script type="text/javascript">--}}
        {{--var clevertap = {event:[], profile:[], account:[]};--}}
        {{--clevertap.account.push({"id": "Your CleverTap Account ID"});--}}
        {{--(function () {--}}
            {{--var wzrk = document.createElement('script');--}}
            {{--wzrk.type = 'text/javascript';--}}
            {{--wzrk.async = true;--}}
            {{--wzrk.src = ('https:' == document.location.protocol ? 'https://d2r1yp2w7bby2u.cloudfront.net' : 'http://static.clevertap.com') + '/js/a.js?v=0';--}}
            {{--var s = document.getElementsByTagName('script')[0];--}}
            {{--s.parentNode.insertBefore(wzrk, s);--}}
        {{--})();--}}
    {{--</script>--}}
</head>

<body>

@if(Session::has('global'))
    <p>{{ Session::get('global') }}</p>
@endif

@yield('content')
</body>
</html>