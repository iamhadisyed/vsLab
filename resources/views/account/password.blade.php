@extends('layout.main')

@section('content')

<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Password Change
                <small>Subheading</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a>
                </li>
                <li class="active">Password Change</li>
            </ol>
        </div>
    </div>

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
            <h3 class="text-center login-title">Change Your Password</h3>
            <div align="center" class="account-wall">
                <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt="">
                <form class="form-signin" method="post" action="{{ URL::route('account-change-password-post') }}">
                    <input type="text" class="form-control" name="old_password" placeholder="Old password" required autofocus>
                    <span class="text-danger">{{$errors->first('old_password')}}</span>
                    <input type="password" class="form-control" name="password" placeholder="New Password" required>
                    <span class="text-danger">{{$errors->first('password')}}</span>            
                    <input type="password" class="form-control" name="password_again" placeholder="Confirm New Password" required>
                    <span class="text-danger">{{$errors->first('password_again')}}</span>

                    <button class="btn btn-lg btn-primary btn-block" type="submit">Save Change</button>
                    {{ Form::token() }}
                </form>
            </div>
        </div>
    </div>

<!--
	<div class="row">
		<form action="{{ URL::route('account-change-password-post') }}" method="post">
			<div class="field">
				Old Password: <input type="password" name="old_password">
				@if ($errors->has('old_password'))
					{{ $errors->first('old_password') }}
				@endif
			</div>
			<div class="field">
				New Password: <input type="password" name="password">
				@if ($errors->has('password'))
					{{ $errors->first('password') }}
				@endif			
			</div>
			<div class="field">
				Confirm New Password: <input type="password" name="password_again">
				@if ($errors->has('password_again'))
					{{ $errors->first('password_again') }}
				@endif
			</div>

			<input type="submit" value="Change Password">
			{{ Form::token() }}
		</form>
	</div>
-->
</div>
@stop