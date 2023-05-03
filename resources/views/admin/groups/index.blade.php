@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Group
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Group
@endsection

@section('contentheader_description')
    Group Management
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
    <div class="modal modal-default fade" id="modal-group-create" data-backdrop="static">
        <div class="modal-dialog" style="width: 650px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Create New Group</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><span style="color: red">*</span><strong>Group Name:</strong></label>
                            <div class="col-md-8">
                                <input class="form-control" id="new-group-name" type="text" placeholder="Alphanumeric without space, no space and special characters" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><span style="color: red">*</span><strong>Description:</strong></label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="new-group-description" rows="3" style="resize: none;" placeholder="The purpose of your group." required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="isPublic"></label>
                            <div class="col-md-8 checkbox">
                                <input type="checkbox" id="isPublic" name="isPublic" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="request-resources"></label>
                            <div class="col-md-8 checkbox">
                                <input type="checkbox" id="resource_requested" name="resource_requested" />
                            </div>
                        </div>
                        <div id="form-request-resources" style="display: none">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><span style="color: red">*</span><strong>Site:</strong></label>
                                <div class="col-md-4">
                                    <select class="form-control" id="site_select" style="width: 150px">
                                        <option value="-1">...Select a site...</option>
                                        {{--@if (count($sites) > 0)--}}
                                            {{--@foreach ($sites as $site)--}}
                                                {{--<option value="{{ $site->id }}" data-desc="{{ $site->description }}" > {{ $site->name }}</option>--}}
                                            {{--@endforeach--}}
                                        {{--@endif--}}
                                    </select>
                                </div>
                                <span id="site-description"></span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span><strong>Number of Labs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="labs">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span><strong>Number of VM:</strong></label>
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
                                    <div class="form-group">
                                        <label class="col-xs-6 control-label"><span style="color: red">*</span><strong>Use until:</strong></label>
                                        <div class="col-xs-6">
                                            <input class="form-control" id="expiration" name="expiration">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="create_group($(this))"><i class="fa fa-check"></i> Create</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-group-edit" data-backdrop="static">
        <div class="modal-dialog" style="width: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Edit Group - <span id="group-name-edit"></span>
                        <span id="group-id-edit" style="display: none"></span><span id="group-status-edit" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    <form id="group_info_form" class="form-horizontal">
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Basic Information</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_name"><strong>Name:</strong></label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="group_info_name" type="text" readonly style="border: 0; background-color: transparent;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_desc"><strong>Description:</strong></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="group_info_desc" rows="2" style="resize: none;"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_status"><strong>Status:</strong></label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="group_info_status" type="text"  readonly style="border: 0; background-color: transparent;">
                                </div>
                            </div>
                            <div class="form-check">
                                <label class="col-sm-2 form-check-label"><strong>Private:</strong></label>
                                <input class="form-check-input" id="group_info_private" type="checkbox">
                            </div>
                        </fieldset>
                        <fieldset id="resource_request" class="form-border" style="display: none;">
                            <legend class="form-border" style="margin-bottom: 5px;">Requested Resources</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_requested_rss"><strong>Resources:</strong></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="group_info_requested_rss" rows="3" disabled style="resize : none;"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_requested_exp"><strong>Expiration:</strong></label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" id="group_info_requested_exp" disabled value="">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset id="resource_approved" class="form-border" style="display: none;">
                            <legend class="form-border" style="margin-bottom: 5px;">Approved Resources</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_approved_at"><strong>Approved at:</strong></label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="group_info_approved_at" disabled value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_approved_rss"><strong>Resources:</strong></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="group_info_approved_rss" rows="3" disabled style="resize : none;"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_approved_exp"><strong>Expiration:</strong></label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" id="group_info_approved_exp" disabled value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_reason"><strong>Reason:</strong></label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="group_info_reason" disabled value="">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset id="group_info_others" class="form-border" style="display: none;">
                            <legend class="form-border" style="margin-bottom: 5px;">Others</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_others_at"><strong>Date:</strong></label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="group_info_others_at" disabled value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="group_info_others_reason"><strong>Reason:</strong></label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="group_info_others_reason" disabled value="">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="group_info_update($(this))"><i class="fa fa-check"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-group-members-add" data-backdrop="static">
        <div class="modal-dialog" style="width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Add Group Members to <span id="group-name-add-member"></span>
                        <span id="group-id-add-member" style="display: none"></span><span id="url" style="display: none">{{ url('usermanagement/all-users-table') }}</span></h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" style="margin-bottom: 6px;">
                        <li class="active"><a href="#add-members" data-toggle="tab">Add Members</a></li>
                        <li><a href="#batch-enroll" data-toggle="tab">Batch Enroll</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="add-members">
                            <table id="group_add_members_all_users" class="table table-bordered table-striped table-condensed" width="100%">
                                <thead>
                                <tr>
                                    <th><input id="members_check_all" type="checkbox" onchange="checkbox_check_all($(this))"/></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Institute</th>
                                    {{--<th>Org ID</th>--}}
                                    {{--<th>Role</th>--}}
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="batch-enroll">
                            <div id="tabs-group-admin-group-enroll" style="overflow: hidden;">
                                <strong>Please enter members' email address separated by ";" or upload .csv/.txt file: </strong>
                                <textarea class="form-control" id="group_admin_dlg_batch_enroll_emails" style="resize: none; width: 670px; height: 300px; margin-bottom: 15px; overflow: auto"></textarea>
                                <input style="float: left" type="file" id="group_admin_dlg_upload_file" accept=".txt, .csv" title="Upload .csv file" multiple onchange="group_admin_dlg_upload_file($(this),1)" />
                                <button class="btn-success" style="float: right; margin-left:10px" title="Verify the format of the input data" onclick="group_admin_dlg_upload_file($(this),2)">Verify</button>&nbsp;&nbsp;&nbsp;
                                <button class="btn-danger" style="float: right" title="Clear the input data" onclick="group_admin_dlg_upload_file($(this),0)">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="group_members_add($(this))"><i class="fa fa-check"></i> Add</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script src="{{ URL::asset('packages/waitMe/waitMe.js') }}"></script>
    <script src="{{ URL::asset('js/group-management.js') }}"></script>
    <script nonce="4AEemasdGb0xJptoIGFP3Nd">
        $(document).ready(function() {
            $('div.main-toolbar').html('<span style="margin-left: 20px;">' +
                '<button type="button" class="btn btn-primary" style="margin-left: 5px" ' +
                'onclick="modal_create_group($(this))">Create New Group</button>');

            $('#dataTableBuilder tbody').on('click', 'td.details-control', function() {
                group_members($(this));
            });

            $('#site_select').change(function() {
                $('#site-description').text($(this).children("option:selected").attr('data-desc'));
            });

            $('#expiration').datepicker();

            $('#isPublic').bootstrapSwitch({
                state: true,
                onColor: 'success',
                onText: 'Yes',
                offText: 'No',
                labelText: 'Public Group?',
                labelWidth: 120
            });

            $('#resource_requested').bootstrapSwitch({
                state: false,
                onColor: 'success',
                onText: 'Yes',
                offText: 'No',
                labelText: 'Request Resources?',
                labelWidth: 120,
                onSwitchChange: function (event, state) {
//			       console.log('switch state:' + state);
                    if (state) {
                        $('#form-request-resources').show();
//                    $('.alert-success').find('ul').append('<li>The password must contain <ul><li>more than 6 characters</li>' +
//                        '<li>at-least 1 Uppercase</li><li>at-least 1 Lowercase</li><li>at-least 1 Numeric</li>' +
//                        '<li>at-least 1 special character</li></ul></li>');
                    } else {
                        $('#form-request-resources').hide();
//                    $('.alert-success').find('ul').find('li:last-child').remove();
                    }
//                   event.preventDefault();
                }
            });
        });

    </script>
@endsection