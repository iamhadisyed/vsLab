@extends('adminlte::layouts.app')

@section('htmlheader_title')
    User
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    User
@endsection

@section('contentheader_description')
    Update User
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    {{ Form::model($user, array('route' => array('users.update', $role->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class='col-lg-4 col-lg-offset-4'>
            {{--<h1><i class='fa fa-key'></i> Edit Role: {{$role->name}}</h1>--}}
            {{--<hr>--}}

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('type', 'Type') }}
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
            <h5><b>Assign Permissions</b></h5>
            @foreach ($permissions as $permission)
                {{Form::checkbox('permissions[]',  $permission->id, $role->permissions ) }}
                {{Form::label($permission->name, ucfirst($permission->name)) }}
                <br>
            @endforeach
            <br>
            {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection