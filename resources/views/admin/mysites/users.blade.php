@extends('adminlte::layouts.app')

@section('htmlheader_title')
    My Site's Users
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    My Site's Users
@endsection

@section('contentheader_description')
    User Management for My Site
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-head table-responsive" style="margin-top: 15px">
                <div class="form-group">
                    <label for="site_selector" class="col-xs-1 control-label">Sites: </label>
                    <div class="col-xs-3">
                        <select id="site_selector" class="form-control">
                            <option value="0">... Select a site ...</option>
                            @foreach($sites->all() as $site)
                                <option value={{ $site->id }} >
                                    {{ $site->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <br><br>
                </div>
            </div>
            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-site-add-members" data-backdrop="static">
        <div class="modal-dialog" style="width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Add Site Members to <span id="site-name-add-member"></span>
                        <span id="site-id-add-member" style="display: none"></span><span id="url" style="display: none">{{ url('mysites/all-users-table') }}</span></h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" style="margin-bottom: 6px;">
                        <li class="active"><a href="#add-members" data-toggle="tab">Add Members</a></li>
                        <li><a href="#batch-enroll" data-toggle="tab">Batch Enroll</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="add-members">
                            <table id="site_add_members_all_users" class="table table-bordered table-striped table-condensed" width="100%">
                                <thead>
                                <tr>
                                    <th><input id="members_check_all" type="checkbox" onchange="checkbox_check_all($(this))"/></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Institute</th>
                                    <th>Org ID</th>
                                    {{--<th>Role</th>--}}
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="batch-enroll">
                            <div id="tabs-group-admin-group-enroll" style="overflow: hidden;">
                                <strong>Please enter members' email address separated by ";" or upload .csv/.txt file: </strong>
                                <textarea class="form-control" id="mysite_batch_enroll_emails" style="resize: none; width: 670px; height: 300px; margin-bottom: 15px; overflow: auto"></textarea>
                                <input style="float: left" type="file" id="mysite_upload_file" accept=".txt, .csv" title="Upload .csv file" multiple onchange="mysite_upload_file($(this),1)" />
                                <button class="btn-success" style="float: right; margin-left:10px" title="Verify the format of the input data" onclick="mysite_upload_file($(this),2)">Verify</button>&nbsp;&nbsp;&nbsp;
                                <button class="btn-danger" style="float: right" title="Clear the input data" onclick="mysite_upload_file($(this),0)">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="mysite_add_users($(this))"><i class="fa fa-check"></i> Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-mysite-group-rss" data-backdrop="static">
        <div class="modal-dialog" style="width: 650px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Manage Resources for Group <span id="group-name-rss"></span>
                        <span id="group-id-rss" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Group Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Group Name:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="group-rss-name" type="text" readonly style="border: 0; background-color: transparent;" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Description:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="group-rss-description" readonly style="border: 0; background-color: transparent;" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Administrators:</strong></label>
                                <div class="col-md-8">
                                    <div class="form-control" id="group-rss-admins" style="border: 0; background-color: transparent;"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Resource Request:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="group-rss-requested" type="text" readonly style="border: 0; background-color: transparent;" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Status:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="group-rss-status" type="text" readonly style="border: 0; background-color: transparent;" />
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
                                            <input class="form-control" id="group-rss-labs">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of VMs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-vms">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of vCPU:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-vcpus">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Memory Size (MB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-ram">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Storage Size (GB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-storage">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-6 control-label"><strong>Use until:</strong></label>
                                        <div class="col-xs-6">
                                            <input class="form-control" id="group-rss-expiration" name="group-rss-expiration">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="mysite_group_resource($(this))"><i class="fa fa-check"></i> Allocate</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script src="{{ URL::asset('js/mysite-management.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#site_selector').val('{{ $id }}');

            $('#site_selector').change(function () {
                window.location = '/mysites/users/' + $('#site_selector').val();
            });

            $('div.main-toolbar').html('<span style="margin-left: 20px;">' +
                '<button type="button" class="btn btn-primary" style="margin-left: 5px" ' +
                'onclick="modal_mysite_add_users($(this))">Add New Users</button>');

            //$('#group-rss-expiration').datepicker();
        });
    </script>
@endsection