@extends('adminlte::layouts.app')

{{--@section('head_css')--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('packages/vis/dist/vis.css') }}" />--}}
{{--@endsection--}}

@section('htmlheader_title')
    Workspace
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Lab Environment Design
@endsection

@section('contentheader_description')

@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">

            <div id="labenv" class="col-md-6 resizable">
                <div class="box box-solid" id="lab-environment">

                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#lab-env-topology-tab" data-toggle="tab" >Design</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="lab-env-topology-tab" >
                            <div class="box-body" id="lab_designnew">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        <div class="form-group">
                            <label><b>Labs Starting Time:</b></label>
                            <input type="text" id="lab_start_time" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label><b>Description:</b></label>
                            <textarea id="description" class="form-control" rows="3" style="resize:vertical; max-height: 250px;" placeholder="Enter description..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="update_labs()"><i class="fa fa-check"></i> Update</button>
                    </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('js/labsubmission.js') }}"></script>
    <script src="{{ URL::asset('js/labenv.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-design.js') }}"></script>
    <script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>
    <script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>

    <script type="text/javascript">
        document.domain = "thothlab.com";



        $(document).ready(function() {

//            var labenv_spliter = Split(['#labcontent', '#labenv'],
//                {
//                    sizes: [50, 50],
//                    minSize: 300,
//                    //elementStyle: {'height': 'calc(50% - 5px'},
//                    //gutterStyle: function (dimension, gutterSize) { return {'flex-basis':  gutterSize + 'px'} },
//                    gutterSize: 6,
//                    cursor: 'col-resize',
//                    onDrag: function () {
//                        $('.gutter-horizontal').css('height', $('#labcontent').find('.panel').css('height'));
//                    }
//                });
//            $('#taskbox').css('height',$(window).innerHeight()-175);
//            $('.gutter').css('background-color', 'lavender');
//            $('.gutter-horizontal').css('height', $('#labcontent').find('.panel').css('height'));
//
//            if ($('.container-fluid').innerWidth() < 768) {
//                $('.gutter').hide();
//                $('#labcontent').removeAttr('style');
//                $('#labenv').removeAttr('style');
//            }
//
//            $(window).resize(function() {
//                if ($('#labcontent').css('display') !== 'none') {
//                    if (($('.container-fluid').innerWidth() < 768) && (labenv_spliter !== undefined)) {
//                        $('.gutter').hide();
//                        $('#labcontent').removeAttr('style');
//                        $('#labenv').removeAttr('style');
//                    } else {
//                        if ($('.gutter').css('display') === 'none') {
//                            $('.gutter').show();
//                            $('#labcontent').css('width', 'calc(50% - 3px)');
//                            $('#labenv').css('width', 'calc(50% - 3px)');
//                        }
//                        $('#taskbox').css('height',$(window).innerHeight()-175);
//                        $('.gutter-horizontal').css('height', $('#labcontent').find('.panel').css('height'));
//                    }
//                }
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

            //vis_canvas_new_network_topology();
            populate_new_lab_design();
        });
    </script>


@endsection


@section('javascript')

@endsection