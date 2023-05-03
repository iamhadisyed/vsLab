@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {!! $labname !!} Task {!! $task !!}
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    {!! $labname !!} Task {!! $task !!}
@endsection

@section('contentheader_description')
    Task Detail
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-body ">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Checking Network Setup</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="panel box box-primary">


                                    <div class="box-body">
                                        @if ($task == 1)
                                        <p>Click on 'Access Lab Workspace' below, it will show your virtual lab environment. Move your mouse over a VM, it will show all network interfaces of that VM, their IP configurations, and running status.</p>

                                        <p>Notes:</p>

                                        <ol>
                                            <li>Usually system will assign one or two DHCP interfaces to each private network to allocate IP addresses to each VM.</li>
                                            <li>Changing IP addresses may cause network disconnection, thus change with cautious or DO NOT change IP addresses for each VM interface.</li>
                                            <li>In order to raise the privilege to run some commands (i.e., usually see &ldquo;&hellip;operation not permitted&rdquo;), then increase user&rsquo;s privilege is required:<br />
                                                <em>#sudo &ndash;i</em>&nbsp;&nbsp;&nbsp; # raise no matter what.</li>
                                        </ol>
                                        @elseif($task ==2)
                                            <p><em>Note that: All Linux commands are case sensitive</em>. Also make sure the firewall rules allowing remote access. A simple approach is to disable firewalls on every vm using:</p>

                                            <p># <em>ufw disable</em> # on Ubuntu 14.04 and above</p>

                                            <p># <em>service iptables stop</em> # on Ubuntu 12.04 and before.</p>

                                            <p>&nbsp;</p>

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

                                            <p>Reference:</p>

                                            <ol>
                                                <li>Network commands<br />
                                                    <a href="http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/c8319.htm">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/c8319.htm</a> &nbsp;</li>
                                            </ol>

                                        @elseif($task ==3)
                                            <p>Based on the network topology, learn how to use the following commands on Linux VMs:</p>

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
                                                <li>Internet commands<br />
                                                    <a href="http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm</a></li>
                                            </ol>

                                            <ol>
                                                <li>Remote access and downloading commands<br />
                                                    <a href="http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm</a></li>
                                            </ol>

                                        @endif
                                    </div>
                                </div>
                        </div>


                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            @if($done == 1)
                                <button disabled title="You've already finished this quiz!" type="button" class="btn btn-default btn-success " data-toggle="modal" data-target="#quiz-{!! $task !!}" id="startquiz"><i class="fa fa-exclamation"></i> Start Self-testing Quiz</button>
                            @elseif($done == 0)
                                <button type="button" class="btn btn-default btn-success " data-toggle="modal" data-target="#quiz-{!! $task !!}" id="startquiz"><i class="fa fa-exclamation"></i> Start Self-testing Quiz</button>
                            @endif
                            @if($task != 1)
                                <button type="button" class="btn btn-default btn-info" onclick="location.href='/lab/{!! $lab !!}/task/{!! $task-1 !!}';"><i class="fa fa-reply"></i>Back to Task {!! $task-1 !!}</button>
                            @endif
                            @if($done== 1)
                                @if($task < 3)
                                    <button id="gototask" type="button" class="btn btn-default btn-primary" onclick="location.href='/lab/{!! $lab !!}/task/{!! $task+1 !!}';"><i class="fa fa-share"></i> Go to Task {!! $task+1 !!}</button>
                                @else
                                    <button id="gototask" type="button" class="btn btn-default btn-success" onclick="location.href='/class';"><i class="fa fa-share"></i>Last task, Finish</button>
                                @endif
                            @elseif($done==0)
                                    @if($task < 3)
                                        <button disabled title="Please finish the quiz first!" id="gototask" type="button" class="btn btn-default btn-primary" onclick="location.href='/lab/{!! $lab !!}/task/{!! $task+1 !!}';"><i class="fa fa-share"></i> Go to Task {!! $task+1 !!}</button>
                                    @else
                                        <button disabled title="Please finish the quiz first!" id="gototask" type="button" class="btn btn-default btn-success" onclick="location.href='/class';"><i class="fa fa-share"></i>Last task, Finish</button>
                                    @endif
                                @endif
                        </div>
                        <button type="button" class="btn btn-default btn-info" onclick="location.href='/lab/{!! $lab !!}';"><i class="fa fa-reply"></i> Back to Lab Page</button>
                        <button type="button" class="btn btn-default btn-warning" onclick="location.href='/lab/{!! $lab !!}/task/{!! $task !!}/env';"><i class="fa fa-compass"></i> Access Lab Workspace</button>
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
                        <button type="button" class="btn btn-primary" id="quiz-submit1" onclick="postsubmission(1,3,{!! $lab !!})">Submit</button>
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
                        <p>1.Which VM can access internet?</p>
                        <form>
                            <p><input name="1" id="2-1-a" type="radio" value="a" />A Gateway&nbsp;<input name="1" id="2-1-b" type="radio" value="b" /> B. Server&nbsp;<input name="1" id="2-1-c" type="radio" value="c" /> C. Client&nbsp</p>
                            <p>&nbsp;</p>
                        </form>
                        <p>2. Can Server communicate with Client?</p>
                        <form>
                            <p><input name="2" id="2-2-yes" type="radio" value="Yes" /> Yes&nbsp;&nbsp;<input name="2" id="2-2-no" type="radio" value="No" /> No</p>
                            <p>&nbsp;</p>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="quiz-submit2" onclick="postsubmission(2,2,{!! $lab !!})">Submit</button>
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
                        <button type="button" class="btn btn-primary" id="quiz-submit3" onclick="postsubmission(3,2, {!! $lab !!})">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="{{ URL::asset('js/labsubmission.js') }}"></script>
@endsection


@section('javascript')

@endsection