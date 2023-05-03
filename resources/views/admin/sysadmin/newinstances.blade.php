@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Instances
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Instances
@endsection

@section('contentheader_description')
    Manage Instances
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-head table-responsive" style="margin-top: 15px">
                <div class="form-group">
                    <label for="group_selector" class="col-xs-1 control-label">Group/Class: </label>
                    <div class="col-xs-3">
                        <select id="group_selector" class="form-control">
                            <option >... Select a group ...</option>
                            <option value="0">All</option>
                            @foreach($groups->all() as $group)
                                <option value={{ $group->id }} >
                                    {{ $group->site }}:{{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button  type="button" style="padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px" class="btn btn-danger" onclick="stop_machine($(this))" title="Shutdown selected machines">Shutdown Machines</button>
                    <button  type="button" style="padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px" class="btn btn-warning" onclick="suspend_machine($(this))" title="Suspend selected machines">Suspend Machines</button>
                    <button  type="button" style="padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px" class="btn btn-warning" onclick="reboot_machine($(this))" title="Reboot selected machines">Reboot Machines</button>
                    <button  type="button" style="padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px" class="btn btn-success" onclick="resume_machine($(this))" title="Resume selected suspended machines">Resume suspended Machines</button>
                    <button  type="button" style="padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px" class="btn btn-success" onclick="start_machine($(this))" title="Start selected stopped machines">Start stopped Machines</button>

                </div>
            </div>

        </div>
    </div>

    <!-- modal -->
    {{--<div class="modal modal-default fade" id="modal-update-labs" data-backdrop="static">--}}
        {{--<div class="modal-dialog" style="width: 370px;">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>--}}
                    {{--<h4 class="modal-title"><i class="fa fa-user"></i> Update Labs in <span class="groupname-in-title"></span>--}}
                        {{--<span class="update-labs-project" style="display: none"></span></h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<form id="form-update-labs" class="form-horizontal" method="POST" action="{{ route('labsdeploy.update') }}" enctype="multipart/form-data">--}}
                    {{--{{ csrf_field() }}--}}
                    {{--<div id="div_update-labs" class="container-fluid">--}}
                        {{--<div class="form-group">--}}
                            {{--<label><b>Notes:</b></label>--}}
                            {{--<textarea id="update_description" class="form-control" rows="3" style="resize:vertical; max-height: 250px;" placeholder="Enter description..."></textarea>--}}
                        {{--</div>--}}
                        {{--<div id="div_update-contents"></div>--}}
                        {{--<div class="form-group">--}}
                        {{--<label><b>Labs Start At:</b></label>--}}
                        {{--<input type="text" id="lab_start_time" class="form-control" />--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                        {{--<label><b>Labs Due At:</b></label>--}}
                        {{--<input type="text" id="lab_due_time" class="form-control" />--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>--}}
                        {{--<button type="button" class="btn btn-primary" onclick="update_labs()"><i class="fa fa-check"></i> Update</button>--}}
                    {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<!-- modal -->--}}
    {{--<div class="modal modal-default fade" id="modal-assign-contents" data-backdrop="static">--}}
        {{--<div class="modal-dialog" style="width: 900px;">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>--}}
                    {{--<h4 class="modal-title"><i class="fa fa-user"></i> Assign Lab Contents - <span class="groupname-in-title"></span></h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<form id="form-assign-contents" class="form-horizontal" method="POST" action="{{ route('contents.assign', ['groupId'=>$id]) }}" enctype="multipart/form-data">--}}
                        {{--{{ csrf_field() }}--}}
                        {{--<div id="div_assign_contents" class="container-fluid">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<label><b>Assign lab contents to the following labs:</b></label>--}}
                                    {{--<div>--}}
                                        {{--<ul id="selected_teams_labs"></ul>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-9" style="overflow-y: auto">--}}
                                    {{--<table id="assign_contents_table" class="table table-bordered table-striped table-condensed" width="100%">--}}
                                        {{--<thead>--}}
                                        {{--<tr>--}}
                                            {{--<th><input id="labs_check_all" type="checkbox" onchange="checkbox_check_all('assign_contents_table', $(this))"/></th>--}}
                                            {{--<th>Category</th>--}}
                                            {{--<th>Name</th>--}}
                                            {{--<th>Public</th>--}}
                                            {{--<th>Created By</th>--}}
                                            {{--<th>Update At</th>--}}
                                            {{--<th>Action</th>--}}
                                        {{--</tr>--}}
                                        {{--</thead>--}}
                                    {{--</table>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="modal-footer">--}}
                            {{--<button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>--}}
                            {{--<button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-check"></i> Assign</button>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<!-- modal view lab -->--}}
    {{--<div class="modal modal-default fade" id="modal-view-lab" data-backdrop="static">--}}
        {{--<div class="modal-dialog" style="width: 1000px;">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>--}}
                    {{--<h4 class="modal-title"><i class="fa fa-user"></i> Update Labs in <span class="view-lab-team-name"></span>---}}
                        {{--<span class="view-lab-name"></span></h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body row">--}}
                    {{--<div class="container-fluid" id="lab-environment">--}}

                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<!-- modal -->--}}
    {{--<div class="modal modal-default fade" id="modal-assign-labcontent-preview" data-backdrop="static">--}}
        {{--<div class="modal-dialog" style="width: 700px;">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>--}}
                    {{--<h4 class="modal-title"><i class="fa fa-user"></i> Lab Preview - <span class="preview-labcontent-name"></span></h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<ul class="nav nav-tabs" style="margin-bottom: 6px;">--}}

                        {{--<li class="active"><a href="#preview-lab-content" data-toggle="tab">Lab Content</a></li>--}}
                    {{--</ul>--}}
                    {{--<div class="tab-content">--}}

                        {{--<div class="active tab-pane" id="preview-lab-content">--}}

                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection



@section('javascript')
    {{--<script src="{{ URL::asset('packages/waitMe/waitMe.js') }}"></script>--}}
    <script src="{{ URL::asset('js/could-resources.js') }}"></script>

    {{--<script src="{{ URL::asset('js/team-management.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>--}}

    <script type="text/javascript">
        {{--$(document).ready(function(){--}}
            {{--$("[rel=tooltip-right]").tooltip({ placement: 'right'});--}}
            {{--$('#group_selector').val( '{{ $id }}' );--}}

            {{--$('#group_selector').change(function() {--}}
                {{--window.location = '/labsdeploy/' + $('#group_selector').val();--}}
            {{--});--}}

            {{--$('#assign_labs').click(function() {--}}
                {{--window.location = '/subgroups/' + $('#group_selector').val();--}}
            {{--});--}}


        $('#group_selector').change(function() {
            window.location = '/sysadmin/instances/' + $('#group_selector').val();
        });

        $('th.checkall').html('<input type="checkbox" id="checkAll" onchange="checkbox_check_all(\'dataTableBuilder\', $(this))"/>');

            {{--$('.buttons-reload').click(function () {--}}
                {{--update_labs_status();--}}
            {{--});--}}

            {{--update_labs_status();--}}

        {{--});--}}
    </script>
@endsection