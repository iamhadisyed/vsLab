@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Cloud Platform Configuration
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Cloud Platform Configuration
@endsection

@section('contentheader_description')
    Update Cloud Configuration
@endsection

@section('main-content')

    <!-- Page Content -->
    <div class="main-content">
        <div class="row">
            {{--<form class="form-horizontal" method="POST" action="{{ route('profiles.update', $user->id) }}" enctype="multipart/form-data">--}}
            <form class="form-horizontal" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            {{--<!-- left column -->--}}
                {{--<div class="col-md-3">--}}
                    {{--<div class="text-center">--}}
                        {{--@if (is_null($avatar))--}}
                            {{--<img src="//placehold.it/100" class="avatar img-circle" alt="avatar" style="width: 100px; height: 100px;">--}}
                        {{--@else--}}
                            {{--<img src="{{ $avatar }}" class="avatar img-circle" alt="avatar" style="width: 100px; height: 100px;">--}}
                        {{--@endif--}}
                        {{--<h6>Upload a different photo...</h6>--}}
                        {{--<h6></h6>--}}
                        {{--<input id="avatar" name="avatar" class="form-control" type="file" style="height: auto" onchange="profile_preview_avatar($(this))">--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<div class="alert alert-success">--}}
                        {{--<ul>--}}
                            {{--<li>Please fill the data in the required (<span style="color: red">*</span>) field.</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                    {{--@if(count($errors))--}}
                        {{--<div class="alert alert-danger">--}}
                            {{--<strong>Whoops!</strong> There were some problems with your input.--}}
                            {{--<br/>--}}
                            {{--<ul>--}}
                                {{--@foreach($errors->all() as $error)--}}
                                    {{--<li>{{ $error }}</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                {{--</div>--}}

                <!-- edit form column -->
                <div class="col-md-9 personal-info">
                    <h3>Cloud Configuration</h3>

                     {{--<div class="form-group">--}}
                        {{--<label class="col-md-3 control-label">Authentication URL</label>--}}
                        {{--<div class="col-md-8">--}}
                            {{--<input class="form-control" name="email" disabled value="{{ $config["auth_url"] }}" type="text">--}}
                            {{--<input name="userid" value="{{ Auth::user()->id  }}" type="hidden">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group {{ $errors->has('auth_url') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Authentication URL:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="first_name" value="{{ $config["auth_url"] }}" type="text" required>
                            <span class="text-danger">{{ $errors->first('auth_url') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Region:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="region" value="{{ $config["region"] }}" type="text" required>
                            <span class="text-danger">{{ $errors->first('region') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('users_admin_name') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Users Admin Name:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="users_admin_name" value="{{ $config["users_admin_name"] }}" type="text">
                            <span class="text-danger">{{ $errors->first('users_admin_name') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('users_admin_id') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Users Admin ID:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="users_admin_id" value="{{ $config["users_admin_id"] }}" type="text">
                            <span class="text-danger">{{ $errors->first('users_admin_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('user_domain_name') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Users Domain Name:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="user_domain_name" value="{{ $config["user_domain_name"] }}" type="text">
                            <span class="text-danger">{{ $errors->first('user_domain_name') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('user_domain_id') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Users Domain ID:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="user_domain_id" value="{{ $config["user_domain_id"] }}" type="text">
                            <span class="text-danger">{{ $errors->first('user_domain_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('user_role_id') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Users Role ID:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="user_role_id" value="{{ $config["user_role_id"] }}" type="text">
                            <span class="text-danger">{{ $errors->first('user_role_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('dummy_project_id') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Dummy Project ID:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="dummy_project_id" value="{{ $config["dummy_project_id"] }}" type="text">
                            <span class="text-danger">{{ $errors->first('dummy_project_id') }}</span>
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-3 control-label" for="change-password"></label>--}}
                        {{--<div class="col-md-8 checkbox">--}}
                            {{--<input type="checkbox" id="change-password" name="change-password" />--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div id="form-change-password" style="display: none">--}}
                        {{--<div class="col-lg-8">--}}
                        {{--<div class="form-group {{ $errors->has('old_password') ? 'has-error' : '' }}">--}}
                            {{--<label class="col-md-3 control-label"><span style="color: red">*</span>Old Password:</label>--}}
                            {{--<div class="col-md-8">--}}
                                {{--<input class="form-control" name="old_password" type="password">--}}
                                {{--<span class="text-danger">{{ $errors->first('old_password') }}</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">--}}
                            {{--<label class="col-md-3 control-label"><span style="color: red">*</span>Password:</label>--}}
                            {{--<div class="col-md-8">--}}
                                {{--<input class="form-control" name="password" type="password">--}}
                                {{--<span class="text-danger">{{ $errors->first('password') }}</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">--}}
                            {{--<label class="col-md-3 control-label"><span style="color: red">*</span>Confirm password:</label>--}}
                            {{--<div class="col-md-8">--}}
                                {{--<input class="form-control" name="password_confirmation" type="password">--}}
                                {{--<span class="text-danger">{{ $errors->first('password_confirmation') }}</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-8">
                            {{--<input class="btn btn-primary" value="Save Changes" type="submit">--}}
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
//            $('#change-password').bootstrapSwitch({
//                state: false,
//                onColor: 'success',
//                onText: 'Yes',
//                offText: 'No',
//                labelText: 'Change Password',
//                labelWidth: 120,
//                onSwitchChange: function (event, state) {
////			       console.log('switch state:' + state);
//                    if (state) {
//                        $('#form-change-password').show();
//                        $('.alert-success').find('ul').append('<li>The password must contain <ul><li>more than 6 characters</li>' +
//                            '<li>at-least 1 Uppercase</li><li>at-least 1 Lowercase</li><li>at-least 1 Numeric</li>' +
//                            '<li>at-least 1 special character</li></ul></li>');
//                    } else {
//                        $('#form-change-password').hide();
//                        $('.alert-success').find('ul').find('li:last-child').remove();
//                    }
////                   event.preventDefault();
//                }
//            });
        });
    </script>

@endsection
