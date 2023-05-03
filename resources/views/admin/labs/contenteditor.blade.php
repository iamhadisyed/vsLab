@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Labs
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Lab
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    <div class="container-fluid spark-screen">
        <div class="row">
            <div id="labcontent" class=" resizable">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Content Editor</h3>
                        <button style="float: right;" id="showworkspace"  onclick="open_workspace();">Open Test Lab Env</button>

                    </div>
                    <!-- /.box-header -->

                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->

                    <div class="box-body" >

                        <div id="taskbox" class="box-body" style="height: 300px; overflow-y: auto">
                            <div  id="taskcount" data-value="2"></div>

                            <form>
                                <h2>Lab Intro Information</h2>
                                <p>Lab Name:</p>
                                <input id="labname" name="labname" type="text" value="" />

                                <p>Keywords(tags):</p>
                                <textarea name="tags" id="tags" style="width: 80%"></textarea>
                                <p>Objects:</p>
                                <textarea name="objects" id="objects" style="width: 80%">
                                </textarea>
                                <p>Estimated Time:</p>
                                <p>Expert: <input id="experttime" name="experttime" type="text" value="" /></p>
                                <p>Novice: <input id="time" name="time" type="text" value="" /></p>
                                <p>Difficulty:</p>
                                <p style="text-align: left; padding-left: 30px;">Time:</p>
                                <p style="text-align: left; padding-left: 30px;"><input id="difftime" name="difftime" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="difftime" name="difftime" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="difftime" name="difftime" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="difftime" name="difftime" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="difftime" name="difftime" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="difftime" name="difftime" type="radio" value="5" /> 5&nbsp;&nbsp;</p>
                                <p style="text-align: left; padding-left: 30px;">Design:</p>
                                <p style="text-align: left; padding-left: 30px;"><input id="diffdesign" name="diffdesign" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="diffdesign" name="diffdesign" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="diffdesign" name="diffdesign" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="diffdesign" name="diffdesign" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="diffdesign" name="diffdesign" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="diffdesign" name="diffdesign" type="radio" value="5" /> 5&nbsp;&nbsp;</p>
                                <p style="text-align: left; padding-left: 30px;">Implementation:</p>
                                <p style="text-align: left; padding-left: 30px;"><input id="diffimp" name="diffimp" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="diffimp" name="diffimp" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="diffimp" name="diffimp" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="diffimp" name="diffimp" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="diffimp" name="diffimp" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="diffimp" name="diffimp" type="radio" value="5" /> 5&nbsp;&nbsp;</p>
                                <p style="text-align: left; padding-left: 30px;">Configration:</p>
                                <p style="text-align: left; padding-left: 30px;"><input id="diffconf" name="diffconf" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="diffconf" name="diffconf" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="diffconf" name="diffconf" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="diffconf" name="diffconf" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="diffconf" name="diffconf" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="diffconf" name="diffconf" type="radio" value="5" /> 5&nbsp;&nbsp;</p>
                                <p style="text-align: left; padding-left: 30px;">Knowledge:</p>
                                <p style="text-align: left; padding-left: 30px;"><input id="diffknow" name="diffknow" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="diffknow" name="diffknow" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="diffknow" name="diffknow" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="diffknow" name="diffknow" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="diffknow" name="diffknow" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="0-10-5" name="4" type="radio" value="5" /> 5&nbsp;&nbsp;</p>
                                <p>Required OS:</p>
                                <input id="os" name="os" type="text" value="" />
                                <p>Preparations:</p>
                                <textarea name="preparations" id="preparations" style="width: 80%"></textarea></form>


                            <h3>Task editor</h3>
                            <div  id="taskbox1">
                            <h4>Task 1 </h4>
                                <h5>Task Name: <input id="taskname1" name="taskname1" type="text" value="" /></h5>

                                <div  id="task1submissioncount" data-value="1"></div>

                            <h5>Description :</h5>
                            <textarea name="taskdesceditor1" class="editor" id="taskdesceditor1">&lt;p&gt;This is some sample content.&lt;/p&gt;</textarea>
                            <h5>Submissions :</h5><br/>
                                <div id="submissionbox1"></div>
                            {{--Submission 1 Description:  <br/><textarea name="object" id="object" style="width: 80%">--}}
                                {{--</textarea><br/>--}}
                        {{--Submission 1 Type:<select>--}}
                                {{--<option value="Screenshot">Screenshot</option>--}}
                                {{--<option value="File">File</option>--}}
                                {{--<option value="Text">Text only</option>--}}

                            </select><br/><br/></div>
                            <button type="button"  class="btn btn-default btn-info" onclick="addsubmission(1)"><i class="fa fa-file-text"></i>Add Submission </button><br/><br/>
                            <button  type="button" class="btn btn-default btn-success   btn-addtask" onclick="addtask()"  ><i class="fa fa-share"></i>Add  Task</button><br/>
                        </div>
                            <button  type="button" class="btn btn-default pull-right  btn-task-112" onclick="savelabcontent(0)"><i class="fa fa-eye"></i>Save</button>




                        <!-- /.box-body -->
                    </div>
                </div>

            </div>
            <div id="labenv" class="col-md-6 resizable" style="display: none;">
                <div class="box box-solid" id="lab-environment">
                    <div class="box-header with-border">
                        <h3 class="box-title">Workspace</h3>
                        <button style=" float: right" onclick="close_workspace();">Close</button>

                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#lab-env-topology-tab" data-toggle="tab" onclick="vis_canvas_redraw('ae3e411b86c24a28a86ca531f91a9df0');">Topology</a></li>
                        <li><a href="#lab-env-topology-tab" id="demo" data-toggle="tab">Timer</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="lab-env-topology-tab" >
                            <div class="box-body" >
                                <table width="100%">
                                    <thead><tr><th>
                                            <button id="btn-vis-refresh" title="Refresh Topology" disabled style="float: left" onclick="vis_canvas_load_network_topology('ae3e411b86c24a28a86ca531f91a9df0')"><i class="fa fa-refresh"></i></button>
                                            <p id="vis-refresh-loading" style="float:left; display: none;"> &nbsp; &nbsp; Loading...</p>
                                            <button id="btn-vis-openallconsole" title="Open All Consoles" style="float: left; display: none; background-color: lightblue" onclick="vis_canvas_open_all_consoles('ae3e411b86c24a28a86ca531f91a9df0')"><i class="fa fa-window-restore"></i></button>
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



    <script src="{{ URL::asset('js/labsubmission.js') }}"></script>
    <script src="{{ URL::asset('js/labcontent.js') }}"></script>
    <script src="{{ URL::asset('js/labenv.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>
    <script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>
    <script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>

    <script type="text/javascript">
        document.domain = "thothlab.com";


        // Update the count down every 1 second



        $(document).ready(function() {
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

            vis_canvas_load_network_topology( 'ae3e411b86c24a28a86ca531f91a9df0' );
            $('#labcontent').removeAttr('style');
            var editor = ClassicEditor
                .create( document.querySelector( '#taskdesceditor1' ) )
//                .then( editor => {
//                    console.log( 'Editor was initialized', editor );
//                })
//                .catch( err => {
//                console.error( err.stack );
//                })
            ;

        });
    </script>




    <script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>

@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script>
        {{--window.route_mass_crud_entries_destroy = '{{ route('admin.roles.mass_destroy') }}';--}}
    </script>
@endsection