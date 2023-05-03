@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Permission
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Permission
@endsection

@section('contentheader_description')
    Add and Remove Permissions
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>

    {{--<div class="col-lg-10 col-lg-offset-1">--}}
        {{--<h1><i class="fa fa-key"></i>Available Permissions--}}
            {{--<a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>--}}
            {{--<a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a></h1>--}}
        {{--<div class="table-responsive">--}}
            {{--<table class="table table-bordered table-striped">--}}
                {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th>Permissions</th>--}}
                    {{--<th>Operation</th>--}}
                {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}
                {{--@foreach ($permissions as $permission)--}}
                    {{--<tr>--}}
                        {{--<td>{{ $permission->name }}</td>--}}
                        {{--<td>--}}
                            {{--<a href="{{ URL::to('permissions/'.$permission->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>--}}
                            {{--{!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}--}}
                            {{--{!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}--}}
                            {{--{!! Form::close() !!}--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
                {{--</tbody>--}}
            {{--</table>--}}
        {{--</div>--}}
        {{--<a href="{{ URL::to('permissions/create') }}" class="btn btn-success">Add Permission</a>--}}
    {{--</div>--}}
@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script>
        {{--window.route_mass_crud_entries_destroy = '{{ route('admin.roles.mass_destroy') }}';--}}
    </script>
@endsection