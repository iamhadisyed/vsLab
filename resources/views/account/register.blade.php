@extends('layout.main')

@section('content')

<!-- Page Content -->
<div class="container">

	<!-- Page Heading/Breadcrumbs -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Sign Up
                <small>Subheading</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="./">Home</a>
                </li>
                <li class="active">Sign Up</li>
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
            <h3 class="text-center login-title">Register a new account</h3>
            <div align="center" class="account-wall">
                <div class="form-group">
                    <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt="">
                </div>
                <form class="form-signin" id="registration" method='post' action="{{ URL::route('account-register-post') }}">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" placeholder="User Name" name="username" required autofocus {{ (Input::old('username')) ? ' value="' . e(Input::old('username')) . '"' : '' }}>
                        </div>
                        <span class="text-danger">{{$errors->first('username')}}</span>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6 col-sm-6 col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" placeholder="First Name" name="firstname" required {{ (Input::old('firstname')) ? ' value="' . e(Input::old('firstname')) . '"' : '' }} >
                            </div>
                            <span class="text-danger">{{$errors->first('firstname')}}</span>
                        </div>
                        <div class="form-group col-xs-6 col-sm-6 col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" placeholder="Last Name" name="lastname" required {{ (Input::old('lastname')) ? ' value="' . e(Input::old('lastname')) . '"' : '' }} >
                            </div>
                            <span class="text-danger">{{$errors->first('lastname')}}</span>
                        </div>
                    </div>
                    <div class="form-group"> 
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input type="text" class="form-control" placeholder="Email" name="email" required {{ (Input::old('email')) ? ' value="' . e(Input::old('email')) . '"' : '' }} >
                        </div>
                        <span class="text-danger">{{$errors->first('email')}}</span>
                    </div>
                    <div class="form-group"> 
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" placeholder="Password" name="password" required>
                        </div>
                        <span class="text-danger">{{$errors->first('password')}}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirm" required>
                        </div>
                        <span class="text-danger">{{$errors->first('password_confirm')}}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            {{ HTML::image(Captcha::url()) }}
                            <a href="{{ URL::current() }}" class="btn btn-small btn-info margin-left-5"><i class="fa fa-refresh"></i></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-picture-o"></i></span>
                            <input type="text" class="form-control" placeholder="Fill in with the text of the image" name="captcha_text" required>
                        </div>
                        <span class="text-danger">{{$errors->first('captcha_text')}}</span>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
                    <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                    {{ Form::token() }}
                </form>
            </div>

<!--            {{ Form::open(array('url' => 'registration/save')) }}
            <div class="form-group">
                {{ Form::label('email', 'E-Mail Address') }}
                {{ Form::email('email', Input::old('email'), array('class' => 'form-control', 'placeholder' => 'Enter your email address')) }}
                <span class="error-display">{{$errors->first('email')}}</span>
            </div>
            <div class="form-group">
                {{ Form::label('firstname', 'First name') }}
                {{ Form::text('firstname', Input::old('firstname'), array('class' => 'form-control', 'placeholder' => 'Enter your First name')) }}
                <span class="error-display">{{$errors->first('firstname')}}</span>
            </div>
            <div class="form-group">
                {{ Form::label('lastname', 'Last name') }}
                {{ Form::text('lastname', Input::old('lastname'), array('class' => 'form-control', 'placeholder' => 'Enter your Last name')) }}
                <span class="error-display">{{$errors->first('lastname')}}</span>
            </div>
            <div class="form-group">
                {{ Form::label('password', 'Password') }}
                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter your password, min 6 characters')) }}
                <span class="error-display">{{$errors->first('password')}}</span>
            </div>
            <div class="form-group">
                {{ Form::label('cpassword', 'Confirm Password') }}
                {{ Form::password('cpassword', array('class' => 'form-control', 'placeholder' => 'Confirm your password')) }}
                <span class="error-display">{{$errors->first('cpassword')}}</span>
            </div>
     
            <input type="submit" name="save" value="Save" class="btn btn-success">
            {{ Form::token() }}
            {{ Form::close() }}
-->            
        </div>
    </div>
</div>
@stop