<!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand topnav" href="{{ URL::asset('/') }}"><i class="glyphicon glyphicon-home"></i></a>
                {{--<a class="navbar-brand topnav" href="https://monitor.mobicloud.asu.edu/monitor" style="font-size: 14px">Status</a>--}}
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">

                    {{--<li>{{ HTML::smart_tag("mobile", 'Research' ) }}</li>--}}
                    {{--<li>{{ HTML::smart_tag("projects", 'Open Projects' ) }}</li>--}}

                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">Labs <b class="caret"></b></a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="http://learning.mobicloud.asu.edu">Open Labs</a></li>--}}
                            {{--<li>{{ HTML::smart_tag("projects", 'Open Projects' ) }}</li>--}}
                            {{--<li><a href="http://edxstudio.mobicloud.asu.edu/signin">Lab Studio</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    <li><a  href="#features">Features</a></li>
                    <li><a  href="#faq">FAQ</a></li>

                        <li><a  href="{{ URL::route('myworkspace') }}">Workspace</a></li>


                    {{--<li>{{ HTML::smart_tag("community", 'Community' ) }}</li>--}}

                    {{--<li>{{ HTML::smart_tag("about", 'About' ) }}</li>--}}
                    @if(\Cas::isAuthenticated())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                @include('laravel-authentication-acl::admin.layouts.partials.avatar', ['size' => 20])
                                <span>{{ \Cas::getCurrentUser() }}</span><b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ URL::route('user.logout') }}"><i class="fa fa-fw fa-power-off"></i> Sign Out</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sign In/Up <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ URL::route('user.login') }}">Sign In</a></li>
                                <li><a href="{{ URL::route('user-signup') }}">Sign Up</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>