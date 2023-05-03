@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Permission
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Permission
@endsection

@section('contentheader_description')
    Add Permission
@endsection

@section('main-content')

    <div class="row">
        <div class='col-lg-4 col-lg-offset-4'>
            {{--<h1><i class='fa fa-key'></i> Add Permission</h1>--}}
            {{--<br>--}}
            {{ Form::open(array('url' => 'permissions')) }}
            <div class="form-group">
                {{ Form::label('name', 'Permission Name') }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('type', 'Permission type') }}
                {{ Form::text('type', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('guard_name', 'Guard Name') }}
                {{ Form::text('guard_name', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 4, 'style' => 'resize: none')) }}
            </div>
            <br>
            @if(!$roles->isEmpty())
            <h4>Assign Permission to Roles</h4>
            @foreach ($roles as $role)
                {{ Form::checkbox('roles[]',  $role->id ) }}
                {{ Form::label($role->name, ucfirst($role->name)) }}
                <br>
            @endforeach
            @endif
            <br>
            {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
        </div>
    </div>

@endsection