@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Site
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Site
@endsection

@section('contentheader_description')
    Update a Site
@endsection

@section('main-content')

    <div class="row">
        <div class='col-lg-4 col-lg-offset-4'>
            {{--<h1><i class='fa fa-key'></i> Edit Role: {{$role->name}}</h1>--}}
            {{--<hr>--}}
            {{ Form::model($site, array('route' => array('sites.update', $site->id), 'method' => 'PUT')) }}
            <div class="form-group">
                {{ Form::label('name', 'Site Name') }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('admins', 'Administrators (email separated by ";")') }}
                {{ Form::text('admins', $site_admins, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 4, 'style' => 'resize: none')) }}
            </div>
            <div class="form-group">
                {{ Form::label('resources', 'Resources (JSON format)') }}
                {{ Form::textarea('resources', null, array('class' => 'form-control', 'rows' => 4, 'style' => 'resize: none')) }}
            </div>
            {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
        </div>
    </div>

@endsection