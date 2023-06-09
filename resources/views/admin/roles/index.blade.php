@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Role
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Role
@endsection

@section('contentheader_description')
    Add and Remove Roles
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">

            {{--<div class="panel-heading">--}}
                {{--@lang('global.app_list')--}}
            {{--</div>--}}

            {{--<p>--}}
                {{--<a href="{{ route('roles.create') }}" class="btn btn-success">Add New Role</a>--}}
            {{--</p>--}}

            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}

                {{--<table class="table table-bordered table-striped {{ count($roles) > 0 ? 'datatable' : '' }} dt-select display">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>--}}
                        {{--<th>Role</th>--}}
                        {{--<th>Description</th>--}}
                        {{--<th>Type</th>--}}
                        {{--<th>Permission</th>--}}
                        {{--<th>@lang('global.roles.fields.name')</th>--}}
                        {{--<th>@lang('global.roles.fields.permission')</th>--}}
                        {{--<th>&nbsp;</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}

                    {{--<tbody>--}}
                    {{--@if (count($roles) > 0)--}}
                        {{--@foreach ($roles as $role)--}}
                            {{--<tr data-entry-id="{{ $role->id }}">--}}
                                {{--<td></td>--}}
                                {{--<td>{{ $role->name }}</td>--}}
                                {{--<td>{{ $role->description }}</td>--}}
                                {{--<td>{{ $role->type }}</td>--}}
                                {{--<td>--}}
                                    {{--@foreach ($role->permissions()->pluck('name') as $permission)--}}
                                        {{--<span class="label label-info label-many">{{ $permission }}</span>--}}
                                    {{--@endforeach--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--<a href="{{ route('roles.edit', [$role->id]) }}" class="btn btn-xs btn-info">Edit</a>--}}
                                    {{--{!! Form::open(array(--}}
                                        {{--'style' => 'display: inline-block;',--}}
                                        {{--'method' => 'DELETE',--}}
                                        {{--'onsubmit' => "return confirm('".trans("message.app_are_you_sure")."');",--}}
                                        {{--'route' => ['roles.destroy', $role->id])) !!}--}}
                                    {{--{!! Form::submit('Delete', array('class' => 'btn btn-xs btn-danger')) !!}--}}
                                    {{--{!! Form::close() !!}--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                    {{--@else--}}
                        {{--<tr>--}}
                            {{--<td colspan="6">No Entries found in the table.</td>--}}
                            {{--<td colspan="6">@lang('global.app_no_entries_in_table')</td>--}}
                        {{--</tr>--}}
                    {{--@endif--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            </div>
        </div>
    </div>
@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script>
        {{--window.route_mass_crud_entries_destroy = '{{ route('admin.roles.mass_destroy') }}';--}}
    </script>
@endsection