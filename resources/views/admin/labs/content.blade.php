@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {!! $name !!}
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Lab Name: {!! $name !!}
@endsection

@section('contentheader_description')
    Lab Overview
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-body ">
                <div class="box box-solid">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#details" data-toggle="tab">Lab Detials</a></li>

                            <li><a href="#preparations" data-toggle="tab">Preparations</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="details">
                    <!-- /.box-header -->

                                        <p><p><b>Category:</b></p>

                                        <p>Computer
                                            Networking</p>

                                        <p><b>Objects</b>: </p>

                                        <p>1. &nbsp; &nbsp;
                                            Learn
                                            a given network's setup</p>

                                        <p>2. &nbsp; &nbsp;
                                            Check
                                            network connectivity and network service reachability.</p>

                                        <p><b>Estimated Time</b>: </p>

                                        <p> &nbsp; &nbsp; &nbsp; &nbsp;
                                            Expert:
                                            2 minutes</p>

                                        <p> &nbsp; &nbsp; &nbsp; &nbsp;
                                            Novice:
                                            10 minutes</p>

                                        <b>Difficulty</b>:&nbsp;</p>
                                        <img src="/img/lab3p1.png">
                                <div id="radarchart"></div>
                                        <p><b>Required OS</b>: </p>
                                        <p>Linux or Windows</p>

                            </div>

                            <div class="tab-pane" id="preparations">

                                        <p>1. &nbsp; &nbsp;  ThoTh Lab is used to run
                                            the project.  </p>
                                        <p>2. &nbsp; &nbsp;  Three virtual machines are assigned: Client (V1), Gateway (V2) and Server (V3).
                                            The initial setup is as follows: </p>

                                        &nbsp; &nbsp; &nbsp; &nbsp;Only the Gateway can access to the public Internet through External Router that cannot be configured; and no packet forwarding rules are setup on the Gateway.




                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            @if($done==1)
                            <button disabled  title="You've already finished this survey!" type="button" class="btn btn-default btn-info" data-toggle="modal" data-target="#quiz-0" id="startquiz"><i class="fa  fa-file-text"></i> Take Pre-Lab Survey</button>
                                @elseif($done==0)
                                <button type="button" class="btn btn-default btn-info" data-toggle="modal" data-target="#quiz-0" id="startquiz"><i class="fa fa-file-text"></i> Take Pre-Lab Survey</button>
                            @endif
                                <button type="button" class="btn btn-default btn-success" data-toggle="modal" data-target="#quiz-4" ><i class="fa fa-exclamation"></i> Take Pre-Lab Quiz</button>
                            @if($done==1)
                                <button id="gototask" type="button" class="btn btn-default" onclick="location.href='/lab/{!! $id !!}/task/1/env';"><i class="fa fa-share"></i> Go to Task 1</button>
                            @elseif($done==0)
                                    <button disabled title="Please take the survey first!" id="gototask" type="button" class="btn btn-default" onclick="location.href='/lab/{!! $id !!}/task/1/env';"><i class="fa fa-share"></i> Go to Task 1</button>
                            @endif
                        </div>


                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="quiz-4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Pre-lab Questions:</h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Concepts review quiz:</strong></p>

                        <p>1.Which of the following protocols uses port 80?</p>
                        <form>
                            <p><input name="7" id="0-13-a" type="radio" value="a" />A SMTP&nbsp;<input name="7" id="0-13-b" type="radio" value="b" /> B. HTTP&nbsp;<input name="7" id="0-13-c" type="radio" value="c" /> C. HTTPS&nbsp;<input name="7" id="0-13-d" type="radio" value="d" />D. FTP&nbsp;</p>
                            <p>&nbsp;</p>
                        </form>
                        <p>2.Which of the following commands verifies connectivity of between two hosts? (Select TWO)</p>
                        <form>
                            <p><input name="8" id="0-14-c-a" type="checkbox" value="a" /> A. ssh&nbsp;&nbsp;<input name="8" id="0-14-c-b" type="checkbox" value="b" /> B. route&nbsp;&nbsp;<input name="8" id="0-14-c-c" type="checkbox" value="c" /> C.traceroute&nbsp;&nbsp;<input name="8" id="0-14-c-d" type="checkbox" value="d" />D.ifconfig&nbsp;&nbsp;<input name="8" id="0-14-c-e" type="checkbox" value="e" /> E.ping</p>
                            <p>&nbsp;</p>
                        </form>
                        <p>3.What's Class B network's default mask?</p>
                        <form>
                            <p><input name="9" id="0-15-a" type="radio" value="a" />A. 245.0.0.1<input name="9" id="0-15-b" type="radio" value="b" /> B. 255.0.0.0<input name="9" id="0-15-c" type="radio" value="c" /> C. 255.255.0.0<input name="9" id="0-15-d" type="radio" value="d" />D. 255.255.255.0</p>
                            <p>&nbsp;</p>
                        </form>
                        <p>4.Is the address 172.16.1.0/24 subnetted?</p>
                        <form>
                            <p><input name="10" id="0-16-yes" type="radio" value="Yes" /> Yes&nbsp;&nbsp;<input name="10" id="0-16-no" type="radio" value="No" /> No</p>
                            <p>&nbsp;</p>
                        </form>
                        <p>5.What&rsquo;s the class C private IP address range?</p>
                        <form>
                            <p><input name="11" id="0-17-a" type="radio" value="a" />A. 192.168.0.0-192.168.255.255&nbsp;<input name="11" id="0-17-b" type="radio" value="b" /> B. 192.168.0.1-192.168.255.254</p>
                            <p><input name="11" id="0-17-c" type="radio" value="c" /> C. 192.168.0.1-192.168.255.255&nbsp;<input name="11" id="0-17-d" type="radio" value="d" />D. 192.168.0.0-192.168.255.254</p>
                            <p>&nbsp;</p>
                        </form>
                        <p>6. If you send a package to a broadcast IP, will the package be sent to every host on that network or subnet?</p>
                        <form>
                            <p><input name="12" id="0-18-yes" type="radio" value="Yes" /> Yes&nbsp;&nbsp;<input name="12" id="0-18-no" type="radio" value="No" /> No</p>
                            <p>&nbsp;</p>
                        </form>
                        <p>7.Kelly&rsquo;s ID address is 192.168.1.21 255.255.255.240. Chris&rsquo;s IP is 192.168.1.14/28. Their computer are connected to the same switch. Why can&rsquo;t they ping each other?</p>
                        <p><input name="13" id="0-19-a" type="radio" value="a" />A. The subnet masks are different.&nbsp;<input name="13" id="0-19-b" type="radio" value="b" /> B. They can.</p>
                        <p><input name="13" id="0-19-c" type="radio" value="c" /> C. Because they are in different subnets.&nbsp;<input name="13" id="0-19-d" type="radio" value="d" />D. Because the router does not support subnetting.</p>
                        <p>&nbsp;</p>
                        <p>8.Is it possible to subnet a subnet?</p>
                        <form><input name="14" id="0-20-yes" type="radio" value="Yes" /> Yes&nbsp;&nbsp;<input name="14" id="0-20-no" type="radio" value="No" /> No</form></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="quiz-submit4" onclick="postsubmission(4,12, {!! $id !!})">Check</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="quiz-0" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pre-lab Questions:</h4>
                </div>
                <div class="modal-body">
                    <p><strong>Educational background:</strong></p>
                    <p>1.Have you ever taken any courses in networking?</p>
                    <form><input name="1" id="0-1-yes" type="radio" value="Yes" /> Yes <input name="1" id="0-1-no" type="radio" value="No" /> No</form><p>&nbsp;</p>
                    <p>2.Do you have any formal or informal study plan in network security?</p>
                    <form><input name="2" id="0-2-yes" type="radio" value="Yes" /> Yes <input name="2" id="0-2-no" type="radio" value="No" /> No</form><p>&nbsp;</p>
                    <p><strong>Hands-on experience:</strong></p>
                    <p>1. Have you ever worked with routers and switches before taking this class?</p>
                    <form><input name="3" id="0-3-yes" type="radio" value="Yes" /> Yes <input name="3" id="0-3-no" type="radio" value="No" /> No</form><p>&nbsp;</p>
                    <p>2. Please identify the level of confidence in the skill areas that you have hands-on experience(between 1-5. where 5 means very confidence and 1means the least confidence. If you don&rsquo;t have any previous hands-on experience in the specific skill area, please select N/A):</p>
                    <p>basic configuration (IP address, passwords):</p>
                    <form><input name="4" id="0-4-0" type="radio" value="0" /> N/A &nbsp&nbsp<input name="4" id="0-4-1" type="radio" value="1" /> 1&nbsp&nbsp <input name="4" id="0-4-2" type="radio" value="2" /> 2 &nbsp&nbsp<input name="4" id="0-4-3" type="radio" value="3" /> 3&nbsp&nbsp <input name="4" id="0-4-4" type="radio" value="4" /> 4&nbsp&nbsp <input name="4" id="0-4-5" type="radio" value="5" /> 5&nbsp&nbsp </form>
                    <p>subnetting:</p>
                    <form><input name="4" id="0-5-0" type="radio" value="0" /> N/A &nbsp&nbsp<input name="4" id="0-5-1" type="radio" value="1" /> 1&nbsp&nbsp <input name="4" id="0-5-2" type="radio" value="2" /> 2 &nbsp&nbsp<input id="0-5-3" name="4" type="radio" value="3" /> 3&nbsp&nbsp <input id="0-5-4" name="4" type="radio" value="4" /> 4&nbsp&nbsp <input name="4" id="0-5-5" type="radio" value="5" /> 5&nbsp&nbsp </form>
                    <p>Dynamic routing protocol configuration:</p>
                    <form><input name="4" id="0-6-0" type="radio" value="0" /> N/A &nbsp&nbsp<input name="4" id="0-6-1" type="radio" value="1" /> 1&nbsp&nbsp <input name="4" id="0-6-2" type="radio" value="2" /> 2 &nbsp&nbsp<input name="4" id="0-6-3" type="radio" value="3" /> 3&nbsp&nbsp <input name="4" id="0-6-4" type="radio" value="4" /> 4&nbsp&nbsp <input name="4" id="0-6-5" type="radio" value="5" /> 5&nbsp&nbsp </form>
                    <p>NAT/PAT(Network/Port Address Translation):</p>
                    <form><input name="4" id="0-7-0" type="radio" value="0" /> N/A &nbsp&nbsp<input name="4" id="0-7-1" type="radio" value="1" /> 1&nbsp&nbsp <input name="4" id="0-7-2" type="radio" value="2" /> 2 &nbsp&nbsp<input name="4" id="0-7-3" type="radio" value="3" /> 3&nbsp&nbsp <input name="4" id="0-7-4" type="radio" value="4" /> 4&nbsp&nbsp <input name="4" id="0-7-5" type="radio" value="5" /> 5&nbsp&nbsp </form>
                    <p>Basic WAN protocols and configuration:</p>
                    <form><input name="4" id="0-8-0" type="radio" value="0" /> N/A &nbsp&nbsp<input name="4" id="0-8-1" type="radio" value="1" /> 1&nbsp&nbsp <input name="4" id="0-8-2" type="radio" value="2" /> 2 &nbsp&nbsp<input name="4" id="0-8-3" type="radio" value="3" /> 3&nbsp&nbsp <input name="4" id="0-8-4" type="radio" value="4" /> 4&nbsp&nbsp <input name="4" id="0-8-5" type="radio" value="5" /> 5&nbsp&nbsp </form>
                    <p>Switching, VLANs, VLAN Trunking Protocol, trunking:</p>
                    <form><input name="4" id="0-9-0" type="radio" value="0" /> N/A &nbsp&nbsp<input name="4" id="0-9-1" type="radio" value="1" /> 1&nbsp&nbsp <input name="4" id="0-9-2" type="radio" value="2" /> 2 &nbsp&nbsp<input name="4" id="0-9-3" type="radio" value="3" /> 3&nbsp&nbsp <input name="4" id="0-9-4" type="radio" value="4" /> 4&nbsp&nbsp <input name="4" id="0-9-5" type="radio" value="5" /> 5&nbsp&nbsp </form>
                    <p>IP access lists:</p>
                    <form><input name="4" id="0-10-0" type="radio" value="0" /> N/A &nbsp&nbsp<input name="4" id="0-10-1" type="radio" value="1" /> 1&nbsp&nbsp <input name="4" id="0-10-2" type="radio" value="2" /> 2 &nbsp&nbsp<input name="4" id="0-10-3" type="radio" value="3" /> 3&nbsp&nbsp <input name="4" id="0-10-4" type="radio" value="4" /> 4&nbsp&nbsp <input name="4" id="0-10-5" type="radio" value="5" /> 5&nbsp&nbsp </form>
                    <p>&nbsp;</p>
                    <p><strong>Learning pace:</strong></p>
                    <p>1.How do you think the topics are covered so far?</p>
                    <form><input name="5" id="0-11-0" type="radio" value="0" />Very clear<input name="5" id="0-11-1" type="radio" value="1" />clear<input name="5" id="0-11-2" type="radio" value="2" />understandable<input name="5" id="0-11-3" type="radio" value="3" />could be more clear<input name="5" id="0-11-4" type="radio" value="4" />not clear</form><p>&nbsp;</p>
                    <p>2.The learning goal for this class is :</p>
                    <form><input name="6" id="0-12-0" type="radio" value="0" />Very clearly defined<input name="6" id="0-12-1" type="radio" value="1" />clearly defined<input name="6" id="0-12-2" type="radio" value="2" />understandable<input name="6" id="0-12-3" type="radio" value="3" />not very clear<input name="6" id="0-12-4" type="radio" value="4" />not clear at all</form>
                    <p>&nbsp;</p>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="quiz-submit0" onclick="postsubmission(0,12, {!! $id !!})">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ URL::asset('js/RadarChart.js') }}"></script>
    <script src="{{ URL::asset('js/labsubmission.js') }}"></script>

    <script type="text/javascript">
        var dones = {!! json_encode($dones,JSON_HEX_TAG) !!};
        var labid = {!! $id !!};
        var taskcount = {!! $taskcount !!};

        $(document).ready(function() {
//            var data = [
//                { axis: "attribute 1", value:0.59 },
//                { axis: "attribute 2", value:0.11 },
//                { axis: "attribute 3", value:0.05 },
//                { axis: "attribute 4", value:0.89 },
//                { axis: "attribute 5", value:0.34 }
//                ];
            var data = [
                [//iPhone
                    {axis:"Battery Life",value:0.22},
                    {axis:"Brand",value:0.28},
                    {axis:"Contract Cost",value:0.29},
                    {axis:"Design And Quality",value:0.17},
                    {axis:"Have Internet Connectivity",value:0.22},
                    {axis:"Large Screen",value:0.02},
                    {axis:"Price Of Device",value:0.21},
                    {axis:"To Be A Smartphone",value:0.50}
                ],[//Samsung
                    {axis:"Battery Life",value:0.27},
                    {axis:"Brand",value:0.16},
                    {axis:"Contract Cost",value:0.35},
                    {axis:"Design And Quality",value:0.13},
                    {axis:"Have Internet Connectivity",value:0.20},
                    {axis:"Large Screen",value:0.13},
                    {axis:"Price Of Device",value:0.35},
                    {axis:"To Be A Smartphone",value:0.38}
                ],[//Nokia Smartphone
                    {axis:"Battery Life",value:0.26},
                    {axis:"Brand",value:0.10},
                    {axis:"Contract Cost",value:0.30},
                    {axis:"Design And Quality",value:0.14},
                    {axis:"Have Internet Connectivity",value:0.22},
                    {axis:"Large Screen",value:0.04},
                    {axis:"Price Of Device",value:0.41},
                    {axis:"To Be A Smartphone",value:0.30}
                ]
            ];
            var mycfg = { w: 300, h: 300, maxValue: 0.5, levels: 5, roundStrokes: true };
            RadarChart('#radarchart', data, mycfg );
        });

    </script>
@endsection


@section('javascript')

@endsection