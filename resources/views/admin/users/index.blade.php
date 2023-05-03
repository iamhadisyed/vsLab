@extends('adminlte::layouts.app')

@section('htmlheader_title')
    User
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    User
@endsection

@section('contentheader_description')
    Add and Remove User
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-user-edit" data-backdrop="static">
        <div class="modal-dialog" style="width: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> Edit User - <span id="user-name"></span>
                        <span id="user-id" style="display: none"></span><span id="user-roles" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    <div id="user_management_config">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button id="btn-user-update" type="button" class="btn btn-primary" onclick="user_admin_dlg_user_update($(this))"><i class="fa fa-check"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('packages/waitMe/waitMe.js') }}"></script>
    <script src="{{ URL::asset('js/user-management.js') }}"></script>
@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script>
        {{--window.route_mass_crud_entries_destroy = '{{ route('admin.roles.mass_destroy') }}';--}}
    </script>
@endsection