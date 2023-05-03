@extends('adminlte::layouts.app')

{{--@section('head_css')--}}
{{--<link rel="stylesheet" href="{{ URL::asset('packages/vis/dist/vis.css') }}" />--}}
{{--@endsection--}}

@section('htmlheader_title')
    Workspace
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Workspace
@endsection

@section('contentheader_description')
    Task Detail
@endsection

@section('main-content')
        <div class="container-fluid spark-screen">
        <div class="row">
            <div id="labcontent" class="col-md-6 resizable">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="lab-name" style="display: none;">No Lab Content Yet</h3>
                        Content:
                        <select id="contentselector" name="contentselector">
                            <option value="0">... Select a Content ...</option>
                            @foreach($contents->all() as $content)
                                <option value={{ $content->id }} >
                                    {{ $content->labid }} {{ $content->name }}
                                </option>
                                {{--<option value={{ $reference->id }} >--}}
                                    {{--{{ $reference->id }} {{ $reference->name }}--}}
                                {{--</option>--}}
                            @endforeach
                            <option disabled>--------Reference Lab Content Below------(No Submission)
                            </option>
                            @foreach($references->all() as $reference)
                                <option value={{ $reference->id }} >
                                    {{ $reference->labid }} {{ $reference->name }}
                                </option>
                                {{--<option value={{ $reference->id }} >--}}
                                {{--{{ $reference->id }} {{ $reference->name }}--}}
                                {{--</option>--}}
                            @endforeach
                        </select>
                        <button style="float: right" onclick="close_content();">Close</button>
                    </div>
                    <!-- /.box-header -->

                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->

                    <div class="box-body" id="labbox" style="height: 1000px; overflow-y: scroll;">
                                        <span id="lab-content"></span>
                                        <span id="lab-tasks"></span>
                        <span id="lab-submission">

                        </span>

                    </div>
                </div>

            </div>
            <div id="labenv" class="col-md-6 resizable">
                <div class="box box-solid" id="lab-environment">
                    <div class="box-header with-border">
                        <h3 class="box-title">Environment</h3>
                        <button style="display: none;float: right;" id="showtask"  onclick="open_content();">Show Task</button>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#lab-env-topology-tab" data-toggle="tab" onclick="vis_canvas_redraw({{ $projectid }});">Topology</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="lab-env-topology-tab" >
                            <div class="box-body" >
                                <table width="100%">
                                    <thead><tr><th>
                                            <button id="btn-vis-refresh" title="Refresh Topology" disabled style="float: left" onclick="vis_canvas_load_network_topology('{{ $projectid }}')"><i class="fa fa-refresh"></i></button>
                                            <p id="vis-refresh-loading" style="float:left; display: none;"> &nbsp; &nbsp; Loading...</p>
                                            <button id="btn-vis-openallconsole" title="Open All Consoles" style="float: left; display: none; background-color: lightblue" onclick="vis_canvas_open_all_consoles('{{ $projectid }}')"><i class="fa fa-window-restore"></i></button>
                                            <p id="vis-topology-selection" style="float: right">Selection: None</p></th></tr></thead>
                                    <tbody id="lab-env-topology"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="float-feedback" style="position:absolute; height: 50px; width: 50px; bottom: 5vh; right: 20px;">

                <form id='float-feedback-form' onSubmit="return false;">
                    <input type="image" src="/img/circle-question-mark-512.png" alt="Feedback" width="48" height="48">
                </form>
            </div>
        </div>
    </div>



    <script src="{{ URL::asset('js/groupsubmission.js') }}"></script>
    <script src="{{ URL::asset('js/labenv.js') }}"></script>
        <script src="{{ URL::asset('js/labcontent.js') }}"></script>
        <script src="{{ URL::asset('js/RadarChart.js') }}"></script>
        <script src="https://d3js.org/d3.v3.min.js"></script>
    <script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>
    <script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>
    <script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>

    <script type="text/javascript">
        document.domain = "thothlab.com";

        {{--var dones = '{{ json_encode($dones,JSON_HEX_TAG) }}';--}}
        {{--var labid = '{{ $lab }}';--}}
        {{--var taskcount = '{{ $taskcount }}';--}}
        var countDownDate = new Date("Dec 2, 2019 23:59:59 GMT-07:00 ").getTime();
        var countDownDate2 = new Date("Dec 2, 2019 23:59:59 GMT-07:00 ").getTime();
        var countDownDate3 = new Date("Oct 26, 2019 23:59:59 GMT-07:00 ").getTime();

        // Update the count down every 1 second
//        var x = setInterval(function() {
//
//            // Get todays date and time
//            var now = new Date().getTime();
//
//            // Find the distance between now and the count down date
//            var distance = countDownDate - now;
//            var distance2 = countDownDate2 - now;
//            var distance3 = countDownDate3 - now;
//            // Time calculations for days, hours, minutes and seconds
//            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
//            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
//            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
//            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
//
//            var days2 = Math.floor(distance2 / (1000 * 60 * 60 * 24));
//            var hours2 = Math.floor((distance2 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
//            var minutes2 = Math.floor((distance2 % (1000 * 60 * 60)) / (1000 * 60));
//            var seconds2 = Math.floor((distance2 % (1000 * 60)) / 1000);
////            var days3 = Math.floor(distance3 / (1000 * 60 * 60 * 24));
////            var hours3 = Math.floor((distance3 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
////            var minutes3 = Math.floor((distance3 % (1000 * 60 * 60)) / (1000 * 60));
////            var seconds3 = Math.floor((distance3 % (1000 * 60)) / 1000);
//
//            // Output the result in an element with id="demo"
//            if ( $( "#countdown" ).length ) {
//                document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
//                    + minutes + "m " + seconds + "s ";
//            }
//            if ( $( "#countdown2" ).length ) {
//                document.getElementById("countdown2").innerHTML = days2 + "d " + hours2 + "h "
//                    + minutes2 + "m " + seconds2 + "s ";
//            }
////            if ( $( "#countdown3" ).length ) {
////                document.getElementById("countdown3").innerHTML = days3 + "d " + hours3 + "h "
////                    + minutes3 + "m " + seconds3 + "s ";
////            }
//
//            // If the count down is over, write some text
//            if (distance < 0) {
//                if(document.getElementById("countdownall").innerHTML !="Submitted"){
//                    document.getElementById("countdownall").innerHTML = "EXPIRED";
//                }
//
//            }
//            if (distance2 < 0) {
//                if(document.getElementById("countdownall2").innerHTML !="Submitted"){
//                    document.getElementById("countdownall2").innerHTML = "EXPIRED";
//                }
//            }
////            if (distance3 < 0) {
////                if(document.getElementById("countdownall3").innerHTML !="Submitted"){
////                    document.getElementById("countdownall3").innerHTML = "EXPIRED";
////                }
////            }
//
//        }, 1000);


        $(document).ready(function() {
            $('#labbox').css('height',$(window).innerHeight()-175);
//            $.getJSON( "/checktaskfinished",{
//
//                "taskid": 111
//
//            }, function( data ) {
//                if(data[0].finished){
//                    $("#bs-wizard-step-111").addClass('complete').children().attr('title','Finished');
//                    document.getElementById("countdownall").innerHTML = "Submitted";
//                }
//            });
//            $.getJSON( "/checktaskfinished",{
//
//                "taskid": 112
//
//            }, function( data ) {
//                if(data[0].finished){
//                    $("#bs-wizard-step-112").addClass('complete').children().attr('title','Finished');
//                    document.getElementById("countdownall2").innerHTML = "Submitted";
//                }
//            });
//            $.getJSON( "/checktaskfinished",{
//
//                "taskid": 103
//
//            }, function( data ) {
//                if(data[0].finished){
//                    $("#bs-wizard-step-103").addClass('complete').children().attr('title','Finished');
//                    document.getElementById("countdownall3").innerHTML = "Submitted";
//                }
//            });
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
            if('{{ $labcontentid }}'!=0){
                $('#contentselector').val( '{{ $labcontentid }}' );
                loadlabcontent('{{$labcontentid}}','{{$userid}}','{{$subgroupid}}',{{$labid}});
            }
            $('#contentselector').change(function() {
                //window.location = '/grade/' + $('#contentselector').val()+'/0';
                if($('#contentselector').val()!=0){

                    loadlabcontent($('#contentselector').val(),'{{$userid}}','{{$subgroupid}}',{{$labid}});
                }
            });
            vis_canvas_load_network_topology( '{{ $projectid }}' );

        });
    </script>


@endsection


@section('javascript')

@endsection