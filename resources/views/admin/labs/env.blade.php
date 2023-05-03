@extends('adminlte::layouts.app')

{{--@section('head_css')--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('packages/vis/dist/vis.css') }}" />--}}
{{--@endsection--}}

@section('htmlheader_title')
    Workspace
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    CSE468 Workspace
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
                        <h3 class="box-title">Checking Network Setup</h3>
                        <button style="float: right" onclick="close_content();">Close</button>
                    </div>
                    <!-- /.box-header -->

                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->



                    <div id="taskbox" class="box-body" style="height: 300px; overflow-y: auto">
                        <div id="task_100">
                            <h2 class="box-title">Intro:</h2>
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
                            <p><b>Required OS</b>: </p>
                            <p>Linux or Windows</p>
                            <p><b>Preparations</b>: </p>
                            <p>1. &nbsp; &nbsp;  ThoTh Lab is used to run
                                the project.  </p>
                            <p>2. &nbsp; &nbsp;  Three virtual machines are assigned: Client (V1), Gateway (V2) and Server (V3).
                                The initial setup is as follows: </p>

                            &nbsp; &nbsp; &nbsp; &nbsp;Only the Gateway can access to the public Internet through External Router that cannot be configured; and no packet forwarding rules are setup on the Gateway.

                            <div class="text-center">
                                <button type="button" class="btn btn-default btn-info" data-toggle="modal" data-target="#quiz-0" id="startquiz"><i class="fa fa-file-text"></i> Take Pre-Lab Survey</button>
                                <button type="button" class="btn btn-default btn-warning" data-toggle="modal" data-target="#quiz-4" id="startquiz"><i class="fa fa-warning"></i>Take Pre-Lab Quiz</button>
                            </div>
                        </div>
                        <div id="task_101">
                            <h3 class="box-title">Task 1:</h3>
                            <p>Click on 'Access Lab Workspace' below, it will show your virtual lab environment. Move your mouse over a VM, it will show all network interfaces of that VM, their IP configurations, and running status.</p>

                            <p>Notes:</p>

                            <ol>
                                <li>Usually system will assign one or two DHCP interfaces to each private network to allocate IP addresses to each VM.</li>
                                <li>Changing IP addresses may cause network disconnection, thus change with cautious or DO NOT change IP addresses for each VM interface.</li>
                                <li>In order to raise the privilege to run some commands (i.e., usually see &ldquo;&hellip;operation not permitted&rdquo;), then increase user&rsquo;s privilege is required:<br />
                                    <em>#sudo &ndash;i</em>&nbsp;&nbsp;&nbsp; # raise no matter what.</li>
                            </ol>
                            <p><em>Note that: All Linux commands are case sensitive</em></p>
                            <div class="text-center">
                                 <button type="button" class="btn btn-default btn-warning" data-toggle="modal" data-target="#quiz-1" id="startquiz"><i class="fa fa-warning"></i>Take Task Quiz</button>
                            </div>

                        </div>
                        <br />
                        <div id="task_102">
                            <h3 class="box-title">Task 2:</h3>
                            <p>2.Setup IPtables packet forwarding policies on Gateway to allow:</p>
                            <ul>
                                <li><em>Ping from the client to the server and vis versa</em></li>
                                <li><em>Ping from the client/server to the public domain such as google server 8.8.8.8</em></li>
                            </ul>


                            <div class="text-center">
                                <button type="button" class="btn btn-default btn-warning" data-toggle="modal" data-target="#quiz-2" id="startquiz"><i class="fa fa-warning"></i>Take Task Quiz</button>
                            </div>
                        </div>
                        <br />
                        <div id="task_103">
                            <h3 class="box-title">Task 3:</h3>
                            <p>Based on the network topology, learn how to use the following commands on Linux VMs:</p>

                            <p><em>Network interface configurations:</em></p>

                            <ul>
                                <li><em>ifup</em></li>
                                <li><em>ifdown</em></li>
                                <li><em>ifconfig</em></li>
                            </ul>

                            <p><em>Network reachability:</em></p>

                            <ul>
                                <li><em>ping</em></li>
                                <li><em>traceroute</em></li>
                            </ul>

                            <p><em>Network interconnection and setup</em></p>

                            <ul>
                                <li><em>netstat</em></li>
                                <li><em>route</em></li>
                            </ul>

                            <p><em>Capturing network traffic</em></p>

                            <ul>
                                <li><em>tcpdump</em></li>
                            </ul>

                            <p><em>Remote access and name services</em></p>

                            <ul>
                                <li><em>telnet/ssh</em></li>
                                <li><em>hostname/host</em></li>
                                <li><em>&hellip;</em></li>
                            </ul>

                            <p><em>Others</em></p>
                            <ul>
                                <li><em>nslookup&nbsp; # DNS</em></li>
                                <li><em>dig&nbsp;&nbsp;&nbsp; # DNS</em></li>
                                <li><em>arp&nbsp;&nbsp;&nbsp; #MAC and IP mapping</em></li>
                                <li><em>nmap # port scanning</em></li>
                                <li><em>whois&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; # Domain name</em></li>
                                <li><em>dhclient&nbsp;&nbsp;&nbsp; #DHCP client</em></li>
                                <li><em>&hellip;</em></li>
                            </ul>

                            <p>Reference:</p>

                            <ol>
                                <li>Network commands<br />
                                    <a href="http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/c8319.htm">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/c8319.htm</a> &nbsp;</li>
                            </ol>
                            <ol>
                                <li>Internet commands<br />
                                    <a href="http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm</a></li>
                            </ol>

                            <ol>
                                <li>Remote access and downloading commands<br />
                                    <a href="http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm</a></li>
                            </ol>


                            <div class="text-center">
                                <button type="button" class="btn btn-default btn-info" data-toggle="modal" data-target="#quiz-3" id="startsubmission"><i class="fa fa-warning"></i>Review Lab Submission</button>
                                <button id="finaltask" type="button" class="btn btn-default btn-success pull-right" onclick="location.href='/class';"><i class="fa fa-share"></i>Finish</button>
                            </div>
                        </div>


                    </div>
                    <!-- /.box-body -->

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
                    <button type="button" class="btn btn-primary" id="quiz-submit4" onclick="postsubmission(4,12, {!! $lab !!})">Check</button>
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
                        <button type="button" class="btn btn-primary" id="quiz-submit0" onclick="postsubmission(0,12, {!! $lab !!})">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="submission-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Task 1</h4>
                </div>
                <div class="modal-body">
                    <form>
                        1.Please upload your copy of rc.firewall
                        <input type="file" id="10-1-file" class="btn btn-default" >
                        2. Take a screenshot on your client to show that you can ping from Client to Server<br>
                        <button type="button" class="btn btn-default" onclick="takescreenshot(11,613,'6c8ae962-8a2b-4bd2-97a0-bc90bfce10d1')">Take Screenshot</button><br>
                        3. Submit your youtube video url:
                        <input type="text" id="10-2" name="youtube" value="">



                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="quiz-submit1" onclick="postsubmission(10,2,'{{ $lab }}')">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="quiz-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Quiz1 Question</h4>
                </div>
                <div class="modal-body">
                    <form>
                        How many network interface do each of the VMs in your virtual environment have?<br><br>
                        Gateway:
                        <input type="text" id="1-1" name="gateway" value="">
                        <br>
                        Server:
                        <input type="text" id="1-2" name="server" value="">
                        <br>
                        Client:
                        <input type="text" id="1-3" name="client" value="">
                        <br><br>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="quiz-submit1" onclick="postsubmission(1,3,'{{ $lab }}')">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="quiz-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Quiz2 Question</h4>
                </div>
                <div class="modal-body">
                    <p>1.Which VM can access internet at beginning?</p>
                    <form>
                        <p><input name="1" id="2-1-a" type="radio" value="a" />A Gateway&nbsp;<input name="1" id="2-1-b" type="radio" value="b" /> B. Server&nbsp;<input name="1" id="2-1-c" type="radio" value="c" /> C. Client&nbsp</p>
                        <p>&nbsp;</p>
                    </form>
                    <p>2. Will 'iptables -F' flush all chains?</p>
                    <form>
                        <p><input name="2" id="2-2-yes" type="radio" value="Yes" /> Yes&nbsp;&nbsp;<input name="2" id="2-2-no" type="radio" value="No" /> No</p>
                        <p>&nbsp;</p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="quiz-submit2" onclick="postsubmission(2,2,'{{ $lab }}')">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="quiz-3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Quiz3 Question</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <p>1.Is 'nslookup' working on your Client VM?</p>
                        <p><input name="1" id="3-1-yes" type="radio" value="Yes" /> Yes&nbsp;&nbsp;<input name="1" id="3-1-no" type="radio" value="No" /> No</p>
                        <p>&nbsp;</p>
                        <p>2. Use 'nmap' on your Gateway to scan your Server, is there any port open on your Server?<p>
                        <p><input name="2" id="3-2-yes" type="radio" value="Yes" /> Yes&nbsp;&nbsp;<input name="2" id="3-2-no" type="radio" value="No" /> No</p>
                        <p>&nbsp;</p>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="quiz-submit3" onclick="postsubmission(3,2,'{{ $lab }}')">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

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