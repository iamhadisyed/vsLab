@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Team
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Team
@endsection

@section('contentheader_description')
    Add and Remove Team
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-head table-responsive" style="margin-top: 15px">
                <div class="form-group">
                    <label for="group_selector" class="col-xs-1 control-label">Group/Class: </label>
                    <div class="col-xs-3">
                        <select id="group_selector" class="form-control">
                            <option value="0">... Select a group ...</option>
                            @foreach($groups->all() as $group)
                                <option value={{ $group->id }} >
                                    {{ $group->site }}:{{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button id="create_team" type="button" class="btn btn-info" onclick="modal_create_team($(this), '{{ url('groups/members-table') }}')" title="Create a team from group members">Create Team</button>
                    <button id="assign_labs" type="button" class="btn btn-primary" onclick="modal_assign_labs($(this), '{{ url('labsdeploy/labs-table') }}')" title="Assign labs to teams">Assign Labs</button>
                    <button id="delete_teams" type="button" class="btn btn-danger" onclick="delete_teams($(this))" title="Delete teams from the list">Delete Teams</button>
                    <button id="team_deploy_labs" type="button" class="btn btn-success" title="Deploy resources to the selected labs">Deploy Labs</button>
                </div>
            </div>
            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-assign-labs" data-backdrop="static">
        <div class="modal-dialog" style="width: 900px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> Assign Labs - <span class="groupname-in-title"></span></h4>
                </div>
                <div class="modal-body">
                    <form id="form-assign-labs" class="form-horizontal" method="POST" action="{{ route('labs.assign', ['groupId'=>$id]) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div id="div_assign_labs" class="container-fluid">
                            <div class="row">
                                <div class="col-md-3">
                                    <label><b>Assign lab environments to the following teams:</b></label>
                                    <div>
                                        <ul id="selected_teams"></ul>
                                    </div>
                                </div>
                                <div class="col-md-9" style="overflow-y: auto;">
                                    <table id="assign_labs_table" class="table table-bordered table-striped table-condensed" width="100%">
                                        <thead>
                                        <tr>
                                            <th><input id="labs_check_all" type="checkbox" onchange="checkbox_check_all('assign_labs_table', $(this))"/></th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Public</th>
                                            <th>Created By</th>
                                            <th>Update At</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                            <button id="btn-team-create" type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-check"></i> Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-team-create" data-backdrop="static">
        <div class="modal-dialog" style="width: 900px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> Create Team in <span class="groupname-in-title"></span></h4>
                </div>
                <div class="modal-body">
                    <form id="form-create-team" class="form-horizontal" method="POST" action="{{ route('subgroups.create', ['groupId'=>$id]) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div id="div_create_team" class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="team-type" id="team-type1" value="individual" checked>
                                                <b>Individual</b><br />Create individual team (project) for each selected member.
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="team-type" id="team-type2" value="group">
                                                <b>Group</b><br />Create a group team (project) for all selected members.
                                            </label>
                                        </div>
                                    </div>
                                    <div id="team_create_group_form" style="display: none;">
                                        <div class="form-group">
                                            <label><b>Name</b></label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter team name...">
                                        </div>
                                        <div class="form-group">
                                            <label><b>Description</b></label>
                                            <textarea name="description" id="description" class="form-control" rows="3" style="resize:vertical; max-height: 350px;" placeholder="Enter description..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8" style="overflow-y: auto">
                                    <table id="team_create_group_members" class="table table-bordered table-striped table-condensed"  width="100%">
                                        <thead>
                                            <tr>
                                                <th><input id="members_check_all" type="checkbox" onchange="checkbox_check_all('team_create_group_members', $(this))"/></th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Institute</th>
                                                <th>Org ID</th>
                                                <th>Role</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                            <button id="btn-team-create" type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-check"></i> Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-team-edit" data-backdrop="static">
        <div class="modal-dialog" style="width: 400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> Edit Team - <span class="groupname-in-title"></span>
                        <span id="team-edit-id" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    {{--<form id="form-team-edit" class="form-horizontal" method="POST" action="{{ route('subgroups.update', ['groupId'=>$id]) }}" enctype="multipart/form-data">--}}
                    {{--{{ csrf_field() }}--}}
                    <div id="div_team_edit" class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b>Name</b></label>
                                    <input type="text" id="team-edit-name" class="form-control" placeholder="Enter team name...">
                                </div>
                                <div class="form-group">
                                    <label><b>Description</b></label>
                                    <textarea id="team-edit-description" class="form-control" rows="3" style="resize:vertical; max-height: 250px;" placeholder="Enter description..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="team_edit_update()"><i class="fa fa-check"></i> Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-team-members" data-backdrop="static">
        <div class="modal-dialog" style="width: 900px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> Update Team Members - <span class="groupname-in-title"></span>
                        <span id="team-members-id" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    {{--<form id="form-team-edit" class="form-horizontal" method="POST" action="{{ route('subgroups.update', ['groupId'=>$id]) }}" enctype="multipart/form-data">--}}
                        {{--{{ csrf_field() }}--}}
                        <div id="div_team_members" class="container-fluid">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><b>Name:</b>&nbsp;&nbsp;<span id="team-members-name"></span></label>
                                        {{--<input type="text" id="" disabled class="form-control" placeholder="Enter team name...">--}}
                                    </div>
                                    {{--<div class="form-group">--}}
                                        {{--<label><b>Description</b></label>--}}
                                        {{--<textarea id="team-edit-description" class="form-control" rows="3" style="resize:vertical; max-height: 250px;" placeholder="Enter description..."></textarea>--}}
                                    {{--</div>--}}
                                    <div class="form-group">
                                        <label><b>Team Members: <span id="member_counts"></span></b></label>
                                        <select multiple class="form-control" id="final-members" style="resize:vertical; max-height: 450px; min-height: 300px;">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1" style="margin-top: 250px">
                                    <button type="button" class="btn btn-primary" title="Add Members" style=" margin-left: 10px" onclick="team_member_select(1)"><i class="fa fa-arrow-left"></i></button>
                                    <button type="button" class="btn btn-primary" title="Remove Members" style="margin-top: 10px; margin-left: 10px" onclick="team_member_select(0)"><i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="col-md-8" style="overflow-y: auto">>
                                    <table id="team_edit_group_members" class="table table-bordered table-striped table-condensed" width="100%">
                                        <thead>
                                            <tr>
                                                <th><input id="members_check_all" type="checkbox" onchange="checkbox_check_all('team_edit_group_members', $(this))"/></th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Institute</th>
                                                <th>Org ID</th>
                                                <th>Role</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="team_members_update()"><i class="fa fa-check"></i> Update</button>
                        </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-assign-lab-preview" data-backdrop="static">
        <div class="modal-dialog" style="width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> Lab Preview - <span class="preview-lab-name"></span></h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" style="margin-bottom: 6px;">
                        <li class="active"><a href="#preview-lab-env" data-toggle="tab">Lab Environment</a></li>
                        <li><a href="#preview-lab-content" data-toggle="tab">Lab Content</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="preview-lab-env">
                            Put lab environment's network topology here
                        </div>
                        <div class="tab-pane" id="preview-lab-content">
                            Put lab content's preview here
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
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
    <script src="{{ URL::asset('js/team-management.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#group_selector').val( '{{ $id }}' );

            @if(!empty(Session::get('show-create-team-modal')) && Session::get('show-create-team-modal') == 1)
            $('#create_team').click();
            @endif

            $('#group_selector').change(function() {
                window.location = '/subgroups/' + $('#group_selector').val();
            });

            $('#team_deploy_labs').click(function() {
                window.location = '/labsdeploy/' + $('#group_selector').val();
            });

            $('th.checkall').html('<input type="checkbox" id="checkAll" onchange="checkbox_check_all(\'dataTableBuilder\', $(this))"/>');

            $('.radio :radio').change(function() {
                if ($(this).val() == 'group') {
                    $('#team_create_group_form').show();
                } else {
                    $('#team_create_group_form').hide();
                }
            });

        });
    </script>
@endsection