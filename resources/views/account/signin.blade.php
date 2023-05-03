@extends('layout.main')

@section('content')

<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Sign In
                <small>Subheading</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a>
                </li>
                <li class="active">Sign In</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <!-- Intro Content -->
    @if (Notify::has('error'))
    <div class="alert alert-danger"><i class="fa fa-fw fa-times"></i>
        {{ Notify::first('error') }}
    </div>
    @endif
    @if (Notify::has('warning'))
    <div class="alert alert-warning"><i class="fa fa-fw fa-exclamation"></i>
        {{ Notify::first('warning') }}
    </div>
    @endif  
    @if (Notify::has('info'))
    <div class="alert alert-info"><i class="fa fa-fw fa-check"></i>
        {{ Notify::first('info') }}
    </div>
    @endif
    @if (Notify::has('success'))
    <div class="alert alert-success"><i class="fa fa-fw fa-thumbs-o-up"></i>
        {{ Notify::first('success') }}
    </div>
    @endif    
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h3 class="text-center login-title">Sign in with your Mobicloud account</h3>
            <div align="center" class="account-wall">
                <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt="">
                <form class="form-signin" id="login" method="post" action="{{ URL::route('account-signin-post') }}">
                    <input type="text" class="form-control" name="username" placeholder="Username" required autofocus
                    {{ (Input::old('username')) ? ' value="' . e(Input::old('username')) . '"' : '' }} >
                    <span class="text-danger">{{$errors->first('username')}}</span>
                    <!--
                    <input type="text" class="form-control" name="email" placeholder="Email" required
                    {{ (Input::old('email')) ? ' value="' . e(Input::old('email')) . '"' : '' }} >
                    @if($errors->has('email'))
                        {{ $errors->first('email') }}
                    @endif -->               
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="text-danger">{{$errors->first('password')}}</span>

                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                    <label class="checkbox pull-left">
                    <input type="checkbox" name="remember" value="remember-me">Remember me</label>
                    <a href="{{ URL::route('account-forgot-password') }}" class="pull-right">Forgot Password </a><span class="clearfix"></span>
                    {{ Form::token() }}
                </form>
            </div>
            <a href="{{ URL::route('account-register') }}" class="text-center new-account">Create an account </a>
        </div>
    </div>
</div>
@stop