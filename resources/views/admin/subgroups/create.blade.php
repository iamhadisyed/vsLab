@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Add User
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Add User
@endsection

@section('contentheader_description')
    Add a New User
@endsection

@section('main-content')

    <div class="container-fluid spark-screen">
        {{ Form::open(array('url' => 'users')) }}
        <div class="row">
            <div class='col-md-3'>
                {{--<h1><i class='fa fa-user-plus'></i> Add User</h1>--}}
                {{--<hr>--}}
                <h4><b>Account Information</b></h4>
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::email('email', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group {{ $errors->has('alt_email') ? 'has-error' : '' }}">
                    {{ Form::label('alt_email', 'Alternative Email') }}
                    {{ Form::text('alt_email', '', array('class' => 'form-control')) }}
                </div>
                <h5><b>Assign System Roles</b></h5>
                <div class='form-group'>
                    @foreach ($roles as $role)
                        {{ Form::checkbox('roles[]',  $role->id ) }}
                        {{ Form::label($role->name, ucfirst($role->name)) }}
                        <br>
                    @endforeach
                </div>
                <div class="form-group">
                    {{ Form::label('password', 'Password') }}
                    <br>
                    {{ Form::password('password', array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('password', 'Confirm Password') }}
                    <br>
                    {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="col-md-3">
                <h4><b>User Information</b></h4>
                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                    {{ Form::label('first_name', 'First Name') }}
                    {{ Form::text('first_name', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                    {{ Form::label('last_name', 'Last Name') }}
                    {{ Form::text('last_name', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group {{ $errors->has('institute') ? 'has-error' : '' }}">
                    {{ Form::label('institute', 'Company/Institute') }}
                    {{ Form::text('institute', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                    {{ Form::label('phone', 'Phone') }}
                    {{ Form::text('phone', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                    {{ Form::label('address', 'Address') }}
                    {{ Form::text('address', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                    {{ Form::label('city', 'City') }}
                    {{ Form::text('city', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group {{ $errors->has('zip') ? 'has-error' : '' }}">
                    {{ Form::label('zip', 'Zip') }}
                    {{ Form::text('zip', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                    {{ Form::label('country', 'Country') }}
                    {{ Form::text('country', '', array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="col-md-3">
                <h4><b>Assign Group</b></h4>
                <div class='form-group'>
                    @foreach ($groups as $group)
                        {{ Form::checkbox('groups[]',  $group->id ) }}
                        {{ Form::label($group->name, $group->site->name . ': ' . $group->name) }}
                        <br>
                    @endforeach
                </div>
            </div>
        </div>
        {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
    </div>
@endsection