@extends('adminlte::layouts.app')

@section('htmlheader_title')
	Profile
	{{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
	Profile
@endsection

@section('contentheader_description')
	Update Profile
@endsection

@section('main-content')

<!-- Page Content -->
<div class="main-content">
	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('profiles.update', $user->id) }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<!-- left column -->
			<div class="col-md-3">
				<div class="text-center">
					{{--@if (is_null($avatar))--}}
						{{--<img src="//placehold.it/100" class="avatar img-circle" alt="avatar" style="width: 100px; height: 100px;">--}}
					{{--@else--}}
						{{--<img src="{{ $avatar }}" class="avatar img-circle" alt="avatar" style="width: 100px; height: 100px;">--}}
					{{--@endif--}}
					{{--<h6>Upload a different photo...</h6>--}}
					{{--<h6></h6>--}}
					{{--<input id="avatar" name="avatar" class="form-control" type="file" accept=".jpg,.jpeg,.gif,.png" style="height: auto" onchange="profile_preview_avatar($(this))">--}}
				</div>
				<br>
				<div class="alert alert-success">
					<ul>
						<li>Please fill the data in the required (<span style="color: red">*</span>) field.</li>
					</ul>
				</div>
				@if(count($errors))
					<div class="alert alert-danger">
						{{--<strong>Whoops!</strong> There were some problems with your input.--}}
						{{--<br/>--}}
						<ul>
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
			</div>

			<!-- edit form column -->
			<div class="col-md-9 personal-info">
				{{--<div class="alert alert-info alert-dismissable">--}}
					{{--<a class="panel-close close" data-dismiss="alert">×</a> --}}
					{{--<i class="fa fa-coffee"></i>--}}
					{{--This is an <strong>.alert</strong>. Use this to show important messages to the user.--}}
				{{--</div>--}}
				<h3>Account info</h3>

				{{--<div class="form-group">--}}
				{{--<label class="col-md-3 control-label">Username:</label>--}}
				{{--<div class="col-md-8">--}}
				{{--<input class="form-control" value="{{ $user->username }}" type="text">--}}
				{{--</div>--}}
				{{--</div>--}}
				<div class="form-group">
					<label class="col-md-3 control-label">Account's Email:</label>
					<div class="col-md-8">
					<input class="form-control" name="email" disabled value="{{ $user->email }}" type="text">
						<input name="userid" value="{{ Auth::user()->id  }}" type="hidden">
					</div>
				</div>
				<div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label"><span style="color: red">*</span>First name:</label>
					<div class="col-md-8">
						<input class="form-control" name="first_name" value="{{ $user->profile()->first()->first_name }}" type="text" required>
						<span class="text-danger">{{ $errors->first('first_name') }}</span>
					</div>
				</div>
				<div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label"><span style="color: red">*</span>Last name:</label>
					<div class="col-md-8">
						<input class="form-control" name="last_name" value="{{ $user->profile()->first()->last_name }}" type="text" required>
						<span class="text-danger">{{ $errors->first('last_name') }}</span>
					</div>
				</div>
				<div class="form-group {{ $errors->has('institute') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label"><span style="color: red">*</span>Company/Institute:</label>
					<div class="col-md-8">
						<input class="form-control" name="institute" value="{{ $user->institute }}" type="text">
						<span class="text-danger">{{ $errors->first('institute') }}</span>
					</div>
				</div>
				<div class="form-group {{ $errors->has('alt_email') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label"><span style="color: red">*</span>Alternative Email:</label>
					<div class="col-md-8">
						<input class="form-control" name="alt_email" value="{{ $user->alt_email }}" type="text">
						<span class="text-danger">{{ $errors->first('alt_email') }}</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label" for="change-password"></label>
					<div class="col-md-8 checkbox">
						<input type="checkbox" id="change-password" name="change-password" />
					</div>
				</div>
				<div id="form-change-password" style="display: none">
					{{--<div class="col-lg-8">--}}
						<div class="form-group {{ $errors->has('old_password') ? 'has-error' : '' }}">
							<label class="col-md-3 control-label"><span style="color: red">*</span>Old Password:</label>
							<div class="col-md-8">
								<input class="form-control" name="old_password" type="password">
								<span class="text-danger">{{ $errors->first('old_password') }}</span>
							</div>
						</div>
						<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
							<label class="col-md-3 control-label"><span style="color: red">*</span>Password:</label>
							<div class="col-md-8">
								<input class="form-control" name="password" type="password">
								<span class="text-danger">{{ $errors->first('password') }}</span>
							</div>
						</div>
						<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
							<label class="col-md-3 control-label"><span style="color: red">*</span>Confirm password:</label>
							<div class="col-md-8">
								<input class="form-control" name="password_confirmation" type="password">
								<span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
							</div>
						</div>
					{{--</div>--}}
				</div>


				<h3>Contact info</h3>
				<div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label">Phone:</label>
					<div class="col-md-8">
						<input class="form-control" name="phone" value="{{ $user->profile()->first()->phone}}" type="text">
						<span class="text-danger">{{ $errors->first('phone') }}</span>
					</div>
				</div>
				<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label">Address:</label>
					<div class="col-md-8">
						<input class="form-control" name="address" value="{{ $user->profile()->first()->address}}" type="text">
						<span class="text-danger">{{ $errors->first('address') }}</span>
					</div>
				</div>
				<div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label">City:</label>
					<div class="col-md-8">
						<input class="form-control" name="city" value="{{ $user->profile()->first()->city}}" type="text">
						<span class="text-danger">{{ $errors->first('city') }}</span>
					</div>
				</div>
				<div class="form-group {{ $errors->has('zip') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label">Zip:</label>
					<div class="col-md-8">
						<input class="form-control" name="zip" value="{{ $user->profile()->first()->zip}}" type="text">
						<span class="text-danger">{{ $errors->first('zip') }}</span>
					</div>
				</div>
				<div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
					<label class="col-md-3 control-label">Country:</label>
					<div class="col-md-8">
						<input class="form-control" name="country" value="{{ $user->profile()->first()->country}}" type="text">
						<span class="text-danger">{{ $errors->first('country') }}</span>
					</div>
				</div>
				{{--<div class="form-group">--}}
					{{--<label class="col-lg-3 control-label">Time Zone:</label>--}}
					{{--<div class="col-lg-8">--}}
						{{--<div class="ui-select">--}}
							{{--<select id="user_time_zone" class="form-control">--}}
							  {{--<option value="Hawaii">(GMT-10:00) Hawaii</option>--}}
							  {{--<option value="Alaska">(GMT-09:00) Alaska</option>--}}
							  {{--<option value="Pacific Time (US &amp; Canada)">(GMT-08:00) Pacific Time (US &amp; Canada)</option>--}}
							  {{--<option value="Arizona">(GMT-07:00) Arizona</option>--}}
							  {{--<option value="Mountain Time (US &amp; Canada)">(GMT-07:00) Mountain Time (US &amp; Canada)</option>--}}
							  {{--<option value="Central Time (US &amp; Canada)" selected="selected">(GMT-06:00) Central Time (US &amp; Canada)</option>--}}
							  {{--<option value="Eastern Time (US &amp; Canada)">(GMT-05:00) Eastern Time (US &amp; Canada)</option>--}}
							  {{--<option value="Indiana (East)">(GMT-05:00) Indiana (East)</option>--}}
							{{--</select>--}}
						{{--</div>--}}
					{{--</div>--}}
				{{--</div>--}}
				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-8">
						<input class="btn btn-primary" value="Save Changes" type="submit">
						<span></span>
						<input class="btn btn-default" value="Cancel" type="reset">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
	   $('#change-password').bootstrapSwitch({
		   state: false,
		   onColor: 'success',
		   onText: 'Yes',
		   offText: 'No',
		   labelText: 'Change Password',
		   labelWidth: 120,
		   onSwitchChange: function(event, state) {
//			       console.log('switch state:' + state);
			   if (state) {
                   $('#form-change-password').show();
			       $('.alert-success').find('ul').append('<li>The password must contain <ul><li>more than 6 characters</li>' +
					   '<li>at-least 1 Uppercase</li><li>at-least 1 Lowercase</li><li>at-least 1 Numeric</li>' +
					   '<li>at-least 1 special character</li></ul></li>');
			   } else {
				   $('#form-change-password').hide();
				   $('.alert-success').find('ul').find('li:last-child').remove();
			   }
//                   event.preventDefault();
		   }
	   });

//           $('#change-password').on('change.bootstrapSwitch', function (event, state) {
//               if (state) {
//                   $('form-change-password').show();
//               } else {
//                   $('form-change-password').hide();
//               }
//		   })

	});

	function profile_preview_avatar() {
		var file = $('#avatar')[0].files[0];
		var reader  = new FileReader();

		reader.onload = function (e) {
			$('.avatar').attr('src',e.target.result);
		};

		if (file) {
			reader.readAsDataURL(file); //reads the data as a URL
		}
	}
</script>

@endsection

