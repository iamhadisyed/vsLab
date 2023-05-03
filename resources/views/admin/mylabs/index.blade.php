@extends('adminlte::layouts.app')

@section('htmlheader_title')
    My Class
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    My Class
@endsection

@section('contentheader_description')
    Your Assigned Tasks and Labs
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Class Information</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <strong>Name:</strong>&nbsp;&nbsp;<span> {{ $class->name }} </span><br />
                    <strong>Description:</strong>&nbsp;&nbsp;<span>{{ $class->description }}</span><br />
                    {{--<strong>Group Owner:</strong><span></span><br />--}}
                    <strong>Instructors:</strong>&nbsp;&nbsp;<span>
                        @foreach($class->instructors as $instructor)
                            {{ $instructor->name }} ({{ $instructor->email }}),
                        @endforeach
                    </span><br />
                    <strong>Teaching Assistants:</strong>&nbsp;&nbsp;<span>
                        @foreach($class->TAs as $TA)
                            <span>
                                {{ $TA->name }} ({{ $TA->email }}),
                            </span>
                        @endforeach
                    </span>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-8">
            <div class="box box-warning box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Announcements</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <span class="info-box-text">Announcements</span>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        {{--<div class="col-md-4">--}}
            {{--<div class="box box-success box-solid">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">Your Team Members</h3>--}}
                    {{--<div class="box-tools pull-right">--}}
                        {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                    {{--<!-- /.box-tools -->--}}
                {{--</div>--}}
                {{--<!-- /.box-header -->--}}
                {{--<div class="box-body">--}}
                    {{--<span class="info-box-text">Announcements</span>--}}
                {{--</div>--}}
                {{--<!-- /.box-body -->--}}
            {{--</div>--}}
            {{--<!-- /.box -->--}}
        {{--</div>--}}
    </div>
    <h4>Assigned Labs</h4>
    {{--<div class="container-fluid spark-screen">--}}
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    {{--</div>--}}
    {{--<div id="lowerpage">--}}

        {{--<section class="col-lg-9 connectedSortable ui-sortable">--}}

            {{--<div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox">--}}
                {{--<div class="box-header ui-sortable-handle" >--}}
                    {{--<h2 class="box-title">Submission:</h2>--}}

                {{--</div>--}}
                {{--<!-- /.box-header -->--}}
                {{--<div class="box-body" id="tobegrading">--}}
                    {{--<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->--}}

                {{--</div>--}}
                {{--<!-- /.box-body -->--}}

            {{--</div>--}}
        {{--</section>--}}
        {{--<section class="col-lg-3 connectedSortable ui-sortable">--}}
            {{--<div  id="gradingbox" style="display:none" class="box box-primary" style="position: relative; left: 0px; top: 0px;">--}}
                {{--<div class="box-header ui-sortable-handle" >--}}


                    {{--<h2 class="box-title">Lab Grades</h2>--}}


                {{--</div>--}}
                {{--<!-- /.box-header -->--}}
                {{--<div class="box-body">--}}
                    {{--<h4>Username: <a id="gradingusername"></a></h4>--}}
                    {{--<h3>Lab Name: <a id="labname"></a></h3>--}}
                    {{--<h3>Lab Grade:<a id="labgivenpoints"></a> out of <a id="totalpointoflab"></a></h3><br/>--}}
                    {{--<div id="taskgradearea"></div>--}}

                {{--</div>--}}
                {{--<!-- /.box-body -->--}}

            {{--</div>--}}

            {{--<!-- /.box-header -->--}}

            {{--<!-- /.box-body -->--}}



        {{--</section>--}}

    {{--</div>--}}
    <!-- modal -->
    <div class="modal modal-default fade" id="modal-update-labs" data-backdrop="static">
        <div class="modal-dialog" style="width: 370px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> Update Labs in <span class="groupname-in-title"></span>
                        <span class="update-labs-project" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    {{--<form id="form-update-labs" class="form-horizontal" method="POST" action="{{ route('labsdeploy.update') }}" enctype="multipart/form-data">--}}
                    {{--{{ csrf_field() }}--}}
                    <div id="div_update-labs" class="container-fluid">
                        {{--<div class="form-group">--}}
                            {{--<label><b>Notes:</b></label>--}}
                            {{--<textarea id="update_description" class="form-control" rows="3" style="resize:vertical; max-height: 250px;" placeholder="Enter description..."></textarea>--}}
                        {{--</div>--}}
                        <div id="div_update-contents"></div>
                        {{--<div class="form-group">--}}
                        {{--<label><b>Labs Start At:</b></label>--}}
                        {{--<input type="text" id="lab_start_time" class="form-control" />--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                        {{--<label><b>Labs Due At:</b></label>--}}
                        {{--<input type="text" id="lab_due_time" class="form-control" />--}}
                        {{--</div>--}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="update_due_date()"><i class="fa fa-check"></i> Update</button>
                    </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-default fade" id="modal-user-activity" data-backdrop="static">
        <div class="modal-dialog" style="width: 850px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> Update Labs in <span class="groupname-in-title"></span>
                        <span class="update-labs-project" style="display: none"></span></h4>
                </div>
                <div class="modal-body">

                    <div>
                        <h3 id="startandend"></h3>
                        <div class="form-group">From <input type="date" id="timelinestarttime"> To <input type="date"  id="timelineendtime"> <button id="timelinebutton" onclick="loadusertimeline(0);">Reload</button></div>
                        <div id="timeline3"></div>
                        <div id="hoverRes">
                            <div class="coloredDiv"></div>
                            <div id="name"></div>
                            <div id="scrolled_date"></div>
                            <br/>
                            <H4 id="totaltime"></H4>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script src="{{ URL::asset('js/labgrading.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-design.js') }}"></script>
    <script src="{{ URL::asset('js/team-management.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.16/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-tip/0.6.7/d3-tip.min.js"></script>
    <script src="{{ URL::asset('js/d3-timeline.js') }}"></script>
    <script src="{{ URL::asset('js/timeline.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){

        });

    </script>
    <style type="text/css">
        .axis path,
        .axis line {
            fill: none;
            stroke: black;
            shape-rendering: crispEdges;
        }

        .axis text {
            font-family: sans-serif;
            font-size: 10px;
        }

        .timeline-label {
            font-family: sans-serif;
            font-size: 12px;
        }

        #timeline2 .axis {
            transform: translate(0px,40px);
            -ms-transform: translate(0px,40px); /* IE 9 */
            -webkit-transform: translate(0px,40px); /* Safari and Chrome */
            -o-transform: translate(0px,40px); /* Opera */
            -moz-transform: translate(0px,40px); /* Firefox */
        }

        .coloredDiv {
            height:20px; width:20px; float:left;
        }
    </style>
    {{--<script type="text/javascript">--}}
        {{--window.onload = function() {--}}
            {{--loadusertimeline({{ $userid }});--}}
        {{--}--}}

    {{--</script>--}}
@endsection