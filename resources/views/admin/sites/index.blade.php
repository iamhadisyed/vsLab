@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Site
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Site
@endsection

@section('contentheader_description')
    Add and Remove Site
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
    <div class="modal modal-default fade" id="modal-site-create" data-backdrop="static">
        <div class="modal-dialog" style="width: 650px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Create A New Site</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Site Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><span style="color: red">*</span><strong>Site Name:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="new-site-name" type="text" placeholder="ASU Computer Science 1" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><span style="color: red">*</span><strong>Administrators:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="new-site-admins" type="text" placeholder="(Email addresses separated by ';')" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><span style="color: red">*</span><strong>Description:</strong></label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="new-site-description" rows="3" style="resize: none;" placeholder="The description of the new site." required></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Resource Allocation</legend>
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span><strong>Number of Labs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="labs">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span><strong>Number of VMs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="vms">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span><strong>Number of vCPU:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="vcpus">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span><strong>Memory Size (MB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="ram">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span><strong>Storage Size (GB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="storage">
                                        </div>
                                    </div>
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-xs-6 control-label"><span style="color: red">*</span><strong>Use until:</strong></label>--}}
                                        {{--<div class="col-xs-6">--}}
                                            {{--<input class="form-control" id="expiration" name="expiration">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="site_create($(this))"><i class="fa fa-check"></i> Create</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-site-edit" data-backdrop="static">
        <div class="modal-dialog" style="width: 650px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Edit Site  - <span id="site-name-edit"></span>
                        <span id="site-id-edit" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Site Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Site Name:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="site-edit-name" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Administrators:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="site-edit-admins" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Description:</strong></label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="site-edit-description" rows="3" style="resize: none;"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Resource Allocation</legend>
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of Labs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="site-edit-labs">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of VMs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="site-edit-vms">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of vCPU:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="site-edit-vcpus">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Memory Size (MB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="site-edit-ram">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Storage Size (GB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="site-edit-storage">
                                        </div>
                                    </div>
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-xs-6 control-label"><strong>Use until:</strong></label>--}}
                                        {{--<div class="col-xs-6">--}}
                                            {{--<input class="form-control" id="site-edit-expiration" name="site-edit-expiration">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="site_edit($(this))"><i class="fa fa-check"></i> Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script src="{{ URL::asset('js/site-management.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('div.main-toolbar').html('<span style="margin-left: 20px;">' +
                '<button type="button" class="btn btn-primary" style="margin-left: 5px" ' +
                'onclick="modal_site_create($(this))">Create New Site</button>');

            // $('#expiration').datepicker();
        })
    </script>
@endsection