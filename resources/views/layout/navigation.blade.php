<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {{--<a class="navbar-brand" href="{{ URL::asset('/') }}"><img style="max-width:100px; margin-top: -29px;" src="{{ URL::asset('/') }}img/MobiCloud.png"></a>--}}
            <a class="navbar-brand" href="{{ URL::asset('/') }}"><i class="glyphicon glyphicon-home"></i></a>
            {{--<a class="navbar-brand" href="{{ URL::asset('/about') }}">About</a>--}}
            {{--<a class="navbar-brand" href="https://monitor.mobicloud.asu.edu/monitor" style="font-size: 14px">Status</a>--}}
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">

                {{--<li><a href="http://mobile.mobicloud.asu.edu">Research</a></li>--}}

                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">Labs <b class="caret"></b></a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li><a href="http://learning.mobicloud.asu.edu">Open Labs</a></li>--}}
                        {{--<li>{{ HTML::smart_tag("projects", 'Open Projects' ) }}</li>--}}
                        {{--<li><a href="http://edxstudio.mobicloud.asu.edu/signin">Lab Studio</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                @if(\Cas::isAuthenticated())
                {{--<li><a href="/workspace/index.html">Workspace</a></li>--}}
                <li><a target="_blank" href="{{ URL::route('myworkspace') }}">Workspace</a></li>
                {{--<li>{{ HTML::smart_tag("workspace", "Workspace") }}</li>--}}

                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">Research <b class="caret"></b></a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li>{{ HTML::smart_tag("experiment", 'Experiment' ) }}</li>--}}
                        {{--<li>{{ HTML::smart_tag("myworkspace", "Workspace") }} </li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                @endif
                {{--<li><a href="http://support.mobicloud.asu.edu">Community</a></li>--}}

                {{--<li>{{ HTML::smart_tag("community", 'Community' ) }}</li>--}}

                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">Community <b class="caret"></b></a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li><a href="https://social.mobicloud.asu.edu/blog/owner/huijun">Blog</a></li>--}}
                        {{--<li>{{ HTML::smart_tag("news", 'News' ) }}</li>--}}
                        {{--<li><a href="https://social.mobicloud.asu.edu/saml_login?saml_login=true">Personal</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--<li>{{ HTML::smart_tag("about", 'About' ) }}</li>--}}
                @if(\Cas::isAuthenticated())
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @include('laravel-authentication-acl::admin.layouts.partials.avatar', ['size' => 20])
                        <span>{{ \Cas::getCurrentUser()}}</span><b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                    <!-- <li><a href=""><i class="fa fa-fw fa-user"></i> Profile</a></li>
                    <li><a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a></li>
                    <li><a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a></li> -->
                    {{--<li><a href="{{ URL::route('users.selfprofile.edit') }}"><i class="fa fa-fw fa-gear"></i> User Dashboard</a></li>--}}
                    {{--<li><a href="https://social.mobicloud.asu.edu/saml_login?saml_login=true&dest=https://social.mobicloud.asu.edu/profile/"><i class="fa fa-fw fa-gear"></i>User Profile</a></li>--}}
                    {{--<li class="divider"></li>--}}
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
<!--                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Login<b class="caret"></b></a>
                    <ul class="dropdown-menu" style="padding: 15px; padding-bottom: 10px;">
                        <form action="login.php" method="post">
                            Username:<br />
                            <input type="text" name="username" value="" />
                            <br /><br />
                            Password:<br />
                            <input type="password" name="password" value="" />
                            <br /><br />
                            <input type="submit" class="btn btn-info" value="Login" />
                        </form>
                    </ul>
                </li> -->                   
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>