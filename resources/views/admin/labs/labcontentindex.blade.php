@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Lab Content
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')

@endsection

@section('contentheader_description')

@endsection

@section('main-content')
    {{--<div class="container-fluid spark-screen">--}}
        {{--<div class="panel panel-default">--}}
            {{--<div class="panel-body table-responsive">--}}
                {{--{!! $dataTable->table() !!}--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="container-fluid spark-screen">
    <div class="box box-primary  box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Lab Content List</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            {!! $dataTable->table() !!}
        </div>
        <!-- /.box-body -->
    </div>
    <div id="lowerpage">
        <div class="row">

            <div id="labcontent" class="col-md-6 resizable" style="display: none;">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 id="editorboxtitle" class="box-title">Editor</h3>

                        <div class="box-tools pull-right">
                            <button  id="showworkspace"  onclick="open_workspace();">Open Test Lab Env</button>
                            <button type="button" class="btn btn-box-tool" onclick="close_workspace_and_content();"><i class="fa fa-times"></i></button>

                        </div>

                    </div>
                    <!-- /.box-header -->

                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->

                    <div class="box-body editor-box" >


                    </div>
                </div>

            </div>
            <div id="workspace" class="col-md-6" style="display: none;">
                <div id="lanenvlist" >
                <div class="box box-primary  box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lab Env List</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <span id="url" style="display: none">{{ url('labenv/deployed-labs-table') }}</span>
                        <table id="deployed_lab_env" class="table table-bordered table-striped table-condensed" width="100%">
                            <thead>
                            <tr>
                                <th>ProjectID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>

                                {{--<th>Role</th>--}}
                            </tr>
                            </thead>
                        </table>

                    </div>
                    <!-- /.box-body -->
                </div>
                </div>
<div class="row">
                <div id="labenv" >
                    <div class="box box-solid" id="lab-environment">
                        <div class="box-header with-border">
                            <h3 id="workspacelabname" class="box-title">Please open one lab in table above.</h3>
                            <button style=" float: right" onclick="close_workspace();">Close Workspace</button>
                            <button id="hideeditorbtn" style=" float: right" onclick="close_contenteditor();">Hide Editor</button>
                            <button id="showeditorbtn" style=" float: right; display:none;" onclick="open_contenteditor();">Show Editor</button>

                        </div>
                        <ul id="labenvnav" class="nav nav-tabs">
                            <li class="active"><a id="vis-reload" href="#lab-env-topology-tab" data-toggle="tab" onclick="vis_canvas_redraw();">Topology</a></li>
                            <li><a href="#lab-env-topology-tab" id="demo" data-toggle="tab">Timer</a></li>
                        </ul>
                        <div id="labenvtab" class="tab-content">
                            <div class="active tab-pane" id="lab-env-topology-tab" >
                                <div class="box-body" >
                                    <table width="100%">
                                        <thead><tr><th>
                                                <button id="btn-vis-refresh" title="Refresh Topology" disabled style="float: left" onclick="vis_canvas_load_network_topology('')"><i class="fa fa-refresh"></i></button>
                                                <p id="vis-refresh-loading" style="float:left; display: none;"> &nbsp; &nbsp; Loading...</p>
                                                <button id="btn-vis-openallconsole" title="Open All Consoles" style="float: left; display: none; background-color: lightblue" onclick="vis_canvas_open_all_consoles('')"><i class="fa fa-window-restore"></i></button>
                                                <p id="vis-topology-selection" style="float: right">Selection: None</p></th></tr></thead>
                                        <tbody id="lab-env-topology"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>



    <!-- modal -->
    <div class="modal modal-default fade" id="modal-preview-lab" data-backdrop="static">
        <div class="modal-dialog" style="width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Preview of Lab:  <span id="lab-name"></span></h4>
                </div>
                <div class="modal-body">
                    <span id="lab-content"></span>
                    <span id="lab-tasks"></span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                </div>
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
    <script src="{{ URL::asset('js/labcontent.js') }}"></script>
    <script src="{{ URL::asset('js/RadarChart.js') }}"></script>
    <script src="https://d3js.org/d3.v3.min.js"></script>
    <script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>
    <script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>
    <script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>

    <script type="text/javascript">
        document.domain = "thothlab.com";


        // Update the count down every 1 second



        $(document).ready(function() {
            $('div.main-toolbar').html('<span style="margin-left: 20px;">' +
                '<button type="button" class="btn btn-primary" style="margin-left: 5px" ' +
                'onclick="showlabeditor(0)">Create New Lab Content</button>');

            $('#dataTableBuilder tbody').on('click', 'td.details-control', function() {
                loadtasks($(this));
            });

            $('#dataTableBuilder tbody').on('click', 'td.submission-details-control', function() {
                loadsubmission($(this));
            });

            var starttime=new Date().getTime();

            var x = setInterval(function() {

                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance =  now-starttime;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result in the element with id="demo"
                document.getElementById("demo").innerHTML =  hours + "h "
                    + minutes + "m " + seconds + "s ";

                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("demo").innerHTML = "EXPIRED";
                }
            }, 1000);


            var labenv_spliter = Split(['#labcontent', '#labenv'],
                {
                    sizes: [50, 50],
                    minSize: 300,
                    //elementStyle: {'height': 'calc(50% - 5px'},
                    //gutterStyle: function (dimension, gutterSize) { return {'flex-basis':  gutterSize + 'px'} },
                    gutterSize: 6,
                    cursor: 'col-resize',
                    onDrag: function () {
                        $('.gutter-horizontal').css('height', $('#labcontent').find('.panel').css('height'));
                    }
                });
            $('#taskbox').css('height',$(window).innerHeight()-175);
            $('.gutter').css('background-color', 'lavender');
            $('.gutter-horizontal').css('height', $('#labcontent').find('.panel').css('height'));

            if ($('.container-fluid').innerWidth() < 768) {
                $('.gutter').hide();
                $('#labcontent').removeAttr('style');
                $('#labenv').removeAttr('style');
            }

            $(window).resize(function() {
                if ($('#labcontent').css('display') !== 'none') {
                    if (($('.container-fluid').innerWidth() < 768) && (labenv_spliter !== undefined)) {
                        $('.gutter').hide();
                        $('#labcontent').removeAttr('style');
                        $('#labenv').removeAttr('style');
                    } else {
                        if ($('.gutter').css('display') === 'none') {
                            $('.gutter').show();
                            $('#labcontent').css('width', 'calc(50% - 3px)');
                            $('#labenv').css('width', 'calc(50% - 3px)');
                        }
                        $('#taskbox').css('height',$(window).innerHeight()-175);
                        $('.gutter-horizontal').css('height', $('#labcontent').find('.panel').css('height'));
                    }
                }
            });

//            $('.tooltip').tooltipster({
//                animation: 'fade',
//                delay: 200,
//                theme: 'tooltipster-punk',
//                trigger: 'click',
//                contentAsHTML: true,
//                interactive: true,
//            });

            //            var maxWidth = $('#labcontent').innerWidth();
//            $('#labcontent').resizable({handles: "e", maxWidth: maxWidth});

//            $('#labcontent').resize(function () {
//                if ($('.container-fluid').innerWidth() <= 768) {
//                    //$('.col-md-6').css('width', '100%');
//                    $('#labcontent').resizable({ disabled: true});
//                } else {
//                    if ($('#labcontent').innerWidth() === $('.container-fluid').innerWidth()) {
//                        $('.col-md-6').css('width', '');
//                    }
//                    $('#labcontent').resizable({
//                        disabled: false,
//                        maxWidth: maxWidth
//                    });
            //$('#labenv').css('width', Math.floor($('.container-fluid').innerWidth() - $('#labcontent').innerWidth() - 2));

//                }
//            });


            //$('#labcontent').removeAttr('style');
//            var editor = ClassicEditor
//                .create( document.querySelector( '#taskdesceditor1' ) )
////                .then( editor => {
////                    console.log( 'Editor was initialized', editor );
////                })
////                .catch( err => {
////                console.error( err.stack );
////                })
//            ;

        });
    </script>



    {{--<script src="//cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>--}}
    <script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>
    {{--<script src="https://cdn.ckeditor.com/ckeditor5/11.1.1/classic/ckeditor.js"></script>--}}
@endsection