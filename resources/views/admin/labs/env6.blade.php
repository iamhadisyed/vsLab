@extends('adminlte::layouts.app')

{{--@section('head_css')--}}
{{--<link rel="stylesheet" href="{{ URL::asset('packages/vis/dist/vis.css') }}" />--}}
{{--@endsection--}}

@section('htmlheader_title')
    Workspace
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    CS4663 Workspace
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
                        {{--<h3 class="box-title">Iptables Firewall Setup</h3>--}}
                        <div class="bs-wizard" style="border-bottom:0;">

                            <div id="bs-wizard-step-210" class="col-xs-3 bs-wizard-step ">
                                <div class="text-center bs-wizard-stepnum">Intro</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_210" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>


                            <div id="bs-wizard-step-211" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 1</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_211" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>

                            <div id="bs-wizard-step-212" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 2</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_212" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>





                        </div>
                        <button style="float: right" onclick="close_content();">Close</button>
                    </div>
                    <!-- /.box-header -->

                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->

                    <div class="box-body" >

                    <div id="taskbox" class="box-body" style="height: 300px; overflow-y: auto">
                        <h1 id="task_210">Project 2: JavaScript Mischief</h1>


                        Browsers provide a rich set of features that enable
                        interactive, compelling web applications. Unfortunately,
                        these features are also open to abuse.
                        This project demonstrates how web sites
                        can spy on the user, steal sensitive
                        information, and render the browser inoperable.

                        <ul>
                            <li>
                                Use the Firefox browser for this project.
                            </li>
                            <li>
                                You're pretending to be an attacker for this project.
                            </li>
                            <li>
                                Unless noted otherwise,
                                avoid the use of external scripts and stylesheets. Everything
                                should be included in the HTML file.
                            </li>
                        </ul>

                        <h2 id="task_211">Task 1. Denial of service</h2>

                        <h3>1a. Endless alert</h3>

                        <p>
                            Create a HTML document that, when opened, displays a JavaScript alert
                            dialog box.  Each time the user dismisses the dialog box, a new,
                            identical dialog box should appear. As a result, the user will be
                            unable to interact with the browser window.
                        </p>

                        <ul>
                            <li>You can put whatever text you want in the alert.
                                (Be creative!)
                            <li>Note that some browsers, such as
                                Opera and Google Chrome, provide mitigations for this attack.
                                Firefox does not.
                        </ul>

                        <h3>1b. Whack-a-mole</h3>

                        <p>Create an HTML page that contains a single button,
                            which has the text "Click here" on it.
                            When the button is clicked, the browser should open an
                            infinite number of popup windows.</p>

                        <ul>
                            <li>You can put whatever content you want in the popup windows.
                            <li>Use a <a
                                        href="https://developer.mozilla.org/en/The_data_URL_scheme">data:
                                    URL</a>
                                to give the popup window some content without making a network
                                request.
                            </li>
                            <li>Do not wait for the first window to be closed before opening
                                the next one.</li>
                            <li>You need to open windows, not tabs.</li>
                        </ul>

                        <h3>1c. Sticky page</h3>

                        <p>
                            Create a HTML document that the user cannot navigate away from.
                            If the user tries to enter a URL into
                            the address bar, click a bookmark, or use the search box,
                            the browser should remain
                            at the same location. The browser should stay at this location
                            no matter how many times the user tries to navigate away.
                        </p>

                        <ul>
                            <li>You can put whatever content you want in the page.</li>
                            <li>The attack should work regardless of the URL where the page is located.
                            </li>
                            <li>Hint #1: Try navigating the page in an onunload or onbeforeunload handler. Be sure to remove your handler to avoid creating an infinite loop.</li>

                            <li>Hint #2: Try using setTimeout to delay your navigation by a few milliseconds.</li>

                            <li>It is acceptable (but not required) if the
                                browser's "throbber" (progress indicator)
                                spins for a brief moment before stopping.
                                It should not keep spinning forever, however.</li>
                        </ul>

                        <h2 id="task_212">Task 2. Privacy attacks</h2>

                        <p>For this part of the project, you will query the browser's history.


                        <h3>2a. Sniffing around</h3>

                        <p>Create a page that checks whether the following pages
                            have been visited:</p>

                        <ul>
                            <li>If (and only if) the user has been to <a
                                        href="http://www.google.com">http://www.google.com</a>, the page
                                should include the text "Oh, you like Google!".
                            <li>If (and only if) the user has been to <a
                                        href="https://www.bankofamerica.com/index.jsp">https://www.bankofamerica.com/index.jsp</a>, the page should include the text "Our rates are better!".
                            <li>If (and only if) the user has been to <a
                                        href="http://www.wellsfargo.com.phisher.com/">http://www.wellsfargo.com.phisher.com/</a>,
                                the page should include the text "Warning! You may have been
                                phished!". (Note that this site doesn't exist any more, but you still
                                need to check if it's in the browser history.)
                            </li>
                            <li>Use <code>document.write</code> in an inline script tag to emit the text into the
                                page. All text should be present by the time the document is finished loading.</li>
                        </ul>


                        <h3>2b. Chameleon phishing page</h3>

                        <p>Create a page that checks whether the following pages
                            are in the browser history:</p>

                        <ul>
                            <li>https://www.thothlab.com</li>
                        </ul>

                        <p>
                            If the user has been to https://www.thothlab.com, they should be shown a
                            phishing page that looks like the <a
                                    href="https://cas.thothlab.org/login">https://cas.thothlab.org/login</a> page.
                            Otherwise, the page should be blank.
                        </p>

                        <ul>
                            <li>For part 2b, you must use CSS only.
                                We will disable JavaScript during grading.
                            </li>
                            <li>The phishing page doesn't have to be the exactly the same as https://cas.thothlab.org/login page. However,
                                make sure all the form fields are present.
                            <li>It doesn't matter where the phishing form submits to.
                                We won't test this.</li>
                            <li>
                                It's ok to load external images, stylesheets, etc. from
                                www.zoobar.org and www.kanjiquizzer.com.
                            </li>

                            <li>
                                We own these sites and give you permission to "phish" them.
                                In the future, be sure not to set up phishing pages unless
                                you have the permission of the site owner.
                            </li>
                        </ul>

                        <h2>Deliverables</h2>

                        <p>Create files named
                            <code>1a.html</code>, <code>1b.html</code>, <code>1c.html</code>,
                            <code>2a.html</code>, <code>2b.html</code>.
                            You may also include a separate <code>README</code> file
                            that includes any feedback you have on the assignment.
                        </p>

                        <p>
                            We are asking you to craft attacks to
                            further your understanding of web application security.
                            Do not send your malicious code to unwitting recipients.
                            Please do not post your HTML files publicly.
                        </p>
                    </div>
                    </div>
                </div>

            </div>
            <div id="labenv" class="col-md-6 resizable">
                <div class="box box-solid" id="lab-environment">
                    <div class="box-header with-border">
                        <h3 class="box-title">Workspace</h3>
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
        </div>
    </div>
   

    <script src="{{ URL::asset('js/labsubmission.js') }}"></script>
    <script src="{{ URL::asset('js/labenv.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>
    <script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>
    <script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>

    <script type="text/javascript">
        document.domain = "thothlab.com";

        var dones = '{{ json_encode($dones,JSON_HEX_TAG) }}';
        var labid = '{{ $lab }}';
        var taskcount = '{{ $taskcount }}';
        


        $(document).ready(function() {

            $.getJSON( "/checktaskfinished",{

                "taskid": 104

            }, function( data ) {
                if(data[0].finished){
                    $("#bs-wizard-step-104").addClass('complete').children().attr('title','Finished');
                    document.getElementById("countdownall").innerHTML = "Submitted";
                }
            });
            $.getJSON( "/checktaskfinished",{

                "taskid": 105

            }, function( data ) {
                if(data[0].finished){
                    $("#bs-wizard-step-105").addClass('complete').children().attr('title','Finished');
                    document.getElementById("countdownall2").innerHTML = "Submitted";
                }
            });
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

            vis_canvas_load_network_topology( '{{ $projectid }}' );

        });
    </script>


@endsection


@section('javascript')

@endsection