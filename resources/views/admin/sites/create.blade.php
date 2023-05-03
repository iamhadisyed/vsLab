@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Site
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Site
@endsection

@section('contentheader_description')
    Add a New Site
@endsection

@section('main-content')

    <div class="row">
        <div class='col-lg-12'>
            {{--<h1><i class='fa fa-key'></i> Add Role</h1>--}}
            {{--<hr>--}}
            {{ Form::open(array('url' => 'sites')) }}
            <div class="form-group">
                {{ Form::label('name', 'Site Name') }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('admins', 'Administrators (email separated by ";")') }}
                {{ Form::text('admins', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 4, 'style' => 'resize: none')) }}
            </div>
            <div class="form-group">
                {{ Form::label('resources', 'Resources (JSON format)') }}
                {{ Form::textarea('resources', null, array('class' => 'form-control', 'rows' => 4, 'style' => 'resize: none')) }}
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group {{ $errors->has('labs') ? 'has-error' : '' }}">
                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Number of Labs:</label>
                        <div class="col-xs-4">
                            <input class="form-control" name="labs">
                            <span class="text-danger">{{ $errors->first('labs') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('vms') ? 'has-error' : '' }}">
                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Number of VM per Lab:</label>
                        <div class="col-xs-4">
                            <input class="form-control" name="vms">
                            <span class="text-danger">{{ $errors->first('vms') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('vcpus') ? 'has-error' : '' }}">
                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Number of vCPU per Lab:</label>
                        <div class="col-xs-4">
                            <input class="form-control" name="vcpus">
                            <span class="text-danger">{{ $errors->first('vcpus') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group {{ $errors->has('ram') ? 'has-error' : '' }}">
                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Memory size per Lab (MB):</label>
                        <div class="col-xs-4">
                            <input class="form-control" name="ram">
                            <span class="text-danger">{{ $errors->first('ram') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('storage') ? 'has-error' : '' }}">
                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Storage size per Lab (GB):</label>
                        <div class="col-xs-4">
                            <input class="form-control" name="storage">
                            <span class="text-danger">{{ $errors->first('storage') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('expiration') ? 'has-error' : '' }}">
                        <label class="col-xs-6 control-label"><span style="color: red">*</span>Use until:</label>
                        <div class="col-xs-6">
                            <input class="form-control" id="expiration" name="expiration">
                            <span class="text-danger">{{ $errors->first('expiration') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
        </div>
    </div>

@endsection