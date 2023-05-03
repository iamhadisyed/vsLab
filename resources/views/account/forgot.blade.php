@extends('layout.main')

@section('content')

<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Account Recover
                <small>Subheading</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a>
                </li>
                <li class="active">Account Recover</li>
            </ol>
        </div>
    </div>

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
            <h4 class="text-center login-title">Enter the email of your Mobicloud account</h4>
            <div align="center" class="account-wall">
				<form action="{{ URL::route('account-forgot-password-post') }}" method="post">
					<div class="form-group">
						<input type="text" class="form-control" name="email" placeholder="Email" required {{ (Input::old('email')) ? ' value="' . e(Input::old('email')) . '"' : '' }} >
						<span class="text-danger">{{$errors->first('password')}}</span>
					</div>
					<button class="btn btn-lg btn-primary btn-block" type="submit">Recover</button>
					{{ Form::token() }}
				</form>
			</div>
		</div>
	</div>
@stop