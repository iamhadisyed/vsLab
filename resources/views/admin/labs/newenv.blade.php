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
                        {{--<h3 class="box-title">Iptables Firewall Setup</h3>--}}
                        <div class="bs-wizard" style="border-bottom:0;">

                            <div id="bs-wizard-step-100" class="col-xs-3 bs-wizard-step ">
                                <div class="text-center bs-wizard-stepnum">Intro</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_100" class="bs-wizard-dot" onclick="active_bar_step(this)" title="Submission 1"></a>
                            </div>


                            <div id="bs-wizard-step-101" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 1</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_101" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>

                            <div id="bs-wizard-step-102" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 2</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_102" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>

                            <div id="bs-wizard-step-103" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 3</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_103" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>


                        </div>
                        <button style="float: right" onclick="close_content();">Close</button>
                    </div>
                    <!-- /.box-header -->

                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->

                    <div class="box-body" >

                    <div id="taskbox" class="box-body" style="height: 300px; overflow-y: auto">
                        <div id="task_100">
                            <h2 class="box-title">Intro:</h2>
                            <p><p><b>Category:</b></p>

                            <p>Computer
                                Networking</p>

                            <p><b>Objects</b>: </p>

                            <p>1. &nbsp; &nbsp;
                                Setup the iptables firewall to block specific traffic </p>

                            <p>2. &nbsp; &nbsp;Learn stateful firewall</p>

                            <p><b>Estimated Time</b>: </p>

                            <p> &nbsp; &nbsp; &nbsp; &nbsp;
                                Expert:
                                60 minutes</p>

                            <p> &nbsp; &nbsp; &nbsp; &nbsp;
                                Novice:
                                240 minutes</p>

                            <b>Difficulty</b>:&nbsp;</p>
                            <img src="/img/lab2.png" style="width: 450px;">
                            <p><b>Required OS</b>: </p>
                            <p>Linux or Windows</p>
                            <p><b>Preparations</b>: </p>
                            <p>1. &nbsp; &nbsp;  ThoTh Lab is used to run
                                the project.  </p>
                            <p>2. &nbsp; &nbsp;  Three virtual machines are assigned: Client (V1), Gateway (V2) and Server (V3).</p>
                            <p>
                                We are re-using the same environment in your first lab. </p>

                            &nbsp; &nbsp; &nbsp; &nbsp;
                            {{--<div class="text-center">--}}
                                {{--<button type="button" class="btn btn-default btn-info" data-toggle="modal" data-target="#quiz-0" id="startquiz"><i class="fa fa-file-text"></i> Take Pre-Lab Survey</button>--}}
                                {{--<button type="button" class="btn btn-default btn-warning" data-toggle="modal" data-target="#quiz-4" id="startquiz"><i class="fa fa-warning"></i>Take Pre-Lab Quiz</button>--}}
                            {{--</div>--}}
                        </div>

                        <div id="task_101">
                            <h3 class="box-title">Task 1: Service Setup</h3>

                            <p>Please follow the following procedures:</p>
                            <ol style="list-style-type: none;">
                                <li>1.1 In the project, iptables need to be setup properly to allow the client and the server to install applications (note that after installing the required applications, the iptables rules should follow the requirements to enable and disable required network traffic, which will be specified in the next task).</li>
                                <li>1.2 On the server, install the following server applications and test them to show that they are working properly:</li>
                                <ol style="list-style-type: lower-alpha;">
                                    <li>Apache server (establish a hello world demo page on the server)</li>
                                    <li>FTP server (i.e., vsftp setup in passive mode)</li>
                                    <li>DNS server (BIND9), create DNS asu-securitylab.com domain with entries for the following URLs to the server IP address mappings:
                                        <ol>
                                            <li>www.asu-securitylab.com</li>
                                            <li><a href="ftp://ftp.asu-securitylab.com">ftp.asu-securitylab.com</a></li>
                                        </ol>
                                </ol>
                                <li>1.3 On the client, install the following applications</li>
                                <ol style="list-style-type: lower-alpha;">
                                    <li>hping3</li>
                                    <li>traceroute</li>
                                </ol>
                            </ol>


                            <button type="button" class="btn btn-default btn-info btn-task-101"  id="startsubmission_101" onclick="opensubmission(101)"><i class="fa fa-plus"></i> Task 1 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall">You've got <span id="countdown"></span> left!</span><br/><b style="color: red;">Task 1 Due on Sep 28th, 2018 23:59:59 AZ Time!</b>

                            <div id='submission_101' class="modal-body" style="display: none">
                                <form>
                                    1.1. Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow internet access for your Client and Server.<br>
                                    <button id='sub_101_1' type="button" class="btn btn-default btn-add-screenshot btn-task-101" onclick="takescreenshot(101,613,1,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task101-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.2a Take screenshot(s) on your server to show your hello world demo page.<br>
                                    <button id='sub_101_2' type="button" class="btn btn-default btn-add-screenshot btn-task-101" onclick="takescreenshot(101,613,2,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task101-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.2b Take screenshot(s) of your vsftpd.conf on your server and explain what you changed.<br>
                                    <button id='sub_101_3' type="button" class="btn btn-default btn-add-screenshot btn-task-101" onclick="takescreenshot(101,613,3,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task101-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.2c Take screenshot(s) of zone file(s) you added, explain what you did. Also take screenshots on your server to show nslookup results of added URLs.<br>
                                    <button id='sub_101_4' type="button" class="btn btn-default btn-add-screenshot btn-task-101" onclick="takescreenshot(101,613,4,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task101-4" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.3 Take screenshot(s) on your Client to show your installation command line output.(It's ok if your already install them, just run the installation command again and show the result.)<br>
                                    <button id='sub_101_5' type="button" class="btn btn-default btn-add-screenshot btn-task-101" onclick="takescreenshot(101,613,5,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task101-5" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    <button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-101" onclick="finish_task(101)"><i class="fa fa-share"></i>Complete Task 1</button>
                                    <button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-101" onclick="view_report(101)"><i class="fa fa-eye"></i>Pre-view Report</button>




                                </form>
                            </div>
                            <br/>

                        </div>
                        <br />
                        <div id="task_102">
                            <h3 class="box-title">Task 2: Iptables Firewall Setup</h3>

                            <p>On the gateway specify the following packet filtering policies. For each requirement, please provide both configuration setup and demos to show the configuration is successful.</p>
                            <ol style="list-style-type: none;">
                                <li>2.1 Set the default iptables policies to DROP for INPUT, OUPUT, and FORWARD chains, which means whitelist policy: only allowing required traffic policies and disable all other traffic.</li>
                                <li>2.2 Consider the server-side network is a private and protected network, configure NAT service properly on the gateway to change source and destination IP addresses form/to the server-side network in the following requirements.</li>
                                <li>2.3 Allow the client to send DNS request to the server for the following URLs, and they all match with the server&rsquo;s IP address. Note that, due to the NAT, the client will not know the server&rsquo;s IP, but the gateway&rsquo;s IP on the client network. Required URLs are:
                                    <ul>
                                        <li>asu-securitylab.com</li>
                                        <li>www.asu-securitylab.com (this is the default web link of the Apache)</li>
                                        <li>ftp.asu-securitylab.com</li>
                                        <li>ssh.asu-securitylab.com</li>
                                        <li>www (a short alias for www.asu-securitylab.com)</li>
                                        <li>ftp (a short alias for ftp.asu-securitylab.com)</li>
                                        <li>ssh (a short alias for ssh.asu-securitylab.com)</li>
                                        <li>demo.asu-securitylab.com (create a demo virtual host on Apache)</li>
                                    </ul>

                                    <p>(Hint: URLs 1-8 should resolve to the same IP address. Please take nslookup screenshots of any three of URLs on the client.)&nbsp;</p>

                                <li>2.4 Allow the client to access the web page (http) on the server (please create two different pages on the following URLs).
                                    <ul>
                                        <li>www.asu-securitylab.com</li>
                                        <li>demo.asu-securitylab.com</li>
                                        <li>(Challenging Question - Bonus Question) What if you want to open these two URLs from the server-side network? For example, when you browse the URLs in the browser on the server? If it does not work, can you change iptables rules to make it work? Why or why not? If it does not work, what is the most effective solution to address this issue considering that you have a large numbers of hosts on the server-side network and they can also access to the web server? Please describe your solution and if you make configuration changes, please take screenshots and explain your solution.</li>
                                    </ul>
                                </li>
                                <li>2.5 Allow the client to access the ftp in the passive mode (use <em>ftp -p</em> option to start the ftp in passive mode) to access to the server (please set up the passive data ports opening from the server in the range of [30000,30099]).
                                    <ul>
                                        <li>Create a demo downloading file on the server</li>
                                        <li>Show how to access the ftp service from the client and download the demo file successfully</li>
                                        <li>Note that you can use either anonymous or normal mode for file transfer.</li>
                                    </ul>
                                </li>
                                <li>2.6 Allow the server to ping the client&rsquo;s IP address and gateway&rsquo;s IP addresses.</li>
                            </ol>

                            <p>Note that</p>
                            <ol>
                                <li>You should adjust the configurations of bind9, apache2, ssh, and vsftp according to requirements provided by this task.</li>
                                <li>You can enable the gateway to allow client/server for downloading applications from Internet. Once downloading files is finished, you must disable all types of traffic between client/server and the Internet.</li>
                            </ol>


                            <button type="button" class="btn btn-default btn-info btn-task-102"  id="startsubmission_102" onclick="opensubmission(102)"><i class="fa fa-plus"></i> Task 2 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall2">You've got <span id="countdown2"></span> left!</span><br/><b style="color: red;">Task 2 Due on Oct 13th, 2018 17:59:59 AZ Time!</b>

                            <div id='submission_102' class="modal-body" style="display: none">
                                <form>
                                    2.1 Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to drop all traffic in all chain by default.<br>
                                    <button id='sub_102_1' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,1,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.2 None submission.<br/>
                                    2.3a Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow DNS traffic from Client to Server <br>
                                    <button id='sub_102_2' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,2,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.3b Take screenshot(s) of nslookup result of any three URLs in the list on the Client.  <br>
                                    <button id='sub_102_3' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,3,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.4a Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow Client to access the web page (http) on Server <br>
                                    <button id='sub_102_4' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,4,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-4" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.4b Take screenshot(s) on your Client to show that you can access those two webpages on Server.<br>
                                    <button id='sub_102_5' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,5,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-5" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.4c (Challenging Question - Bonus Question) What if you want to open these two URLs from the server-side network? For example, when you browse the URLs in the browser on the server? If it does not work, can you change iptables rules to make it work? Why or why not? If it does not work, what is the most effective solution to address this issue considering that you have a large numbers of hosts on the server-side network and they can also access to the web server? Please describe your solution and if you make configuration changes, please take screenshots and explain your solution.<br>
                                    <button id='sub_102_6' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,6,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-6" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.5a Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow Client to access the ftp server on Server <br>
                                    <button id='sub_102_7' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,7,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-7" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.5b Take screenshot(s) on your Client to show that how to access the ftp service from the client and download the demo file successfully.<br>
                                    <button id='sub_102_8' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,8,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-8" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.6 Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow Server to ping Client and GW.<br>
                                    <button id='sub_102_9' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,9,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task102-9" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    <button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-102" onclick="finish_task(102)"><i class="fa fa-share"></i>Complete Task 2</button>
                                    <button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-102" onclick="view_report(102)"><i class="fa fa-eye"></i>Pre-view Report</button>




                                </form>
                            </div>

                        </div>
                        <br/>
                        <div id="task_103">
                            <h3 class="box-title">Task 3: Firewall Testing</h3>

                            <p>Firewall plays a central role in network protection and in many cases building a critical line of defense against attackers. Systematic firewall testing is a critical task to ensure strong network security. In general, there are three general approaches for firewall testing:</p>
                            <ol>
                                <li>penetration testing,</li>
                                <li>testing of the firewall implementation, and</li>
                                <li>testing of the firewall rules</li>
                            </ol>
                            <p>In this task, our focus is &ldquo;Testing of the firewall rules&rdquo;, in which testing of the firewall rules is to verify whether the security policy is correctly implemented by a set of firewall rules, and check what ports can be accessed through the firewall.&nbsp;</p>

                            <p>In this task, the security policy is the set of firewall setup requirements in the previous task (task 2). You need to develop an automatic tool by using script or program languages, e.g., perl, python, awk, java, etc., or you choose, to run nmap and/or hping3 in a batch fashion, i.e., automate a set of scanning styles and options to verify your firewall rule setup according the requirement from the task 2.</p>

                            <p>You can perform the testing on the client and the server VMs given in the system to run the script to test your firewall setup. During the testing, considering this is a whitebox testing of iptables firewall, in which you will know the details of the firewall implementation and applied filtering rules.</p>
                            <p>For the testing report, you need to:</p>
                            <ol style="list-style-type: none;">
                                <li>3.1. Issue your testing commands through the script/program that you built for this project. Your script development goal is to minimize the manual inputs and overhead to issue the scanning request with the least number of commands as possible and with shortest scanning time period to test your firewall and validate the policies.</li>
                                <li>3.2. Program your developed tools to satisfy the following requirements:</li>
                            </ol>
                            <table style="width: 570px;" border="1">
                                <thead>
                                <tr>
                                    <td style="width: 550px;" colspan="3">
                                        <p>Nmap/hping3 Programming Requirements:</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 233px;">
                                        <p>Type</p>
                                    </td>
                                    <td style="width: 137px;">
                                        <p>Exp., nmap command/options</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>Description</p>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="width: 233px;" rowspan="4">
                                        <p>1. Scanning the firewall with four different types (executed one-by-one)</p>
                                    </td>
                                    <td style="width: 137px;">
                                        <p>-sS</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>TCP SYN Scan</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 137px;">
                                        <p>-sF</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>FIN Scan</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 137px;">
                                        <p>-sA</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>ACK Scan</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 137px;">
                                        <p>-sP</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>Ping sweep</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 233px;" rowspan="2">
                                        <p>2. For each type, perform a port scanning for both TCP and UDP protocol in the range from 10 to 40000</p>
                                    </td>
                                    <td style="width: 137px;">
                                        <p>-p</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>Scan for TCP ports</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 137px;">
                                        <p>-sU</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>Scan for UDP ports</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 233px;" rowspan="2">
                                        <p>3. Perform an OS scan using two options</p>
                                    </td>
                                    <td style="width: 137px;">
                                        <p>-O</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>Detect operating system</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 137px;">
                                        <p>-sV</p>
                                    </td>
                                    <td style="width: 179px;">
                                        <p>Version detection</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <ol start="3" style="list-style-type: none;">
                                <li>3.3. Provide screenshots to illustrate your scanning results for each type/option and provide output to show the evidence that you firewall was established properly based on the rule setup requirements from Task 2. If your results demonstrate unnecessary open ports/services, then you will get point deductions.</li>
                                <li>3.4. Submit your script/program as a file with good marked comments/illustrations in the script/program to explain function modules. (Note that do not use existing library to hide details on how to deploy each scanning type and options. There are many existing scripts such as python, NSE that hide the details on how to deploy the scanning actions.)</li>
                                <li>3.5. Submit a readme.txt file to illustrate required running environment and how to use the script.</li>
                            </ol>


                            <button type="button" class="btn btn-default btn-info btn-task-103"  id="startsubmission_103" onclick="opensubmission(103)"><i class="fa fa-plus"></i> Task 3 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall3">You've got <span id="countdown3"></span> left!</span><br/><b style="color: red;">Task 3 Due on Oct 26th, 2018 23:59:59 AZ Time!</b>

                            <div id='submission_103' class="modal-body" style="display: none">
                                <form>
                                    3.1 None submission.<br/>
                                    3.2 None submission.<br/>
                                    3.3. Provide screenshots to illustrate your scanning results for each type/option and provide output to show the evidence that you firewall was established properly based on the rule setup requirements from Task 2. If your results demonstrate unnecessary open ports/services, then you will get point deductions.<br>
                                    <button id='sub_103_1' type="button" class="btn btn-default btn-add-screenshot btn-task-103" onclick="takescreenshot(103,613,1,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task103-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    3.4. Submit your script/program as a file with good marked comments/illustrations in the script/program to explain function modules.<br>
                                    <button id='sub_103_2' type="button" class="btn btn-default btn-add-screenshot btn-task-103" onclick="addfile(103,613,2)"><i class="fa fa-plus"></i>Upload File</button><br>
                                    <div id="submission-task103-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    3.5. Submit a readme.txt file to illustrate required running environment and how to use the script.<br>
                                    <button id='sub_103_3' type="button" class="btn btn-default btn-add-screenshot btn-task-103" onclick="addfile(103,613,3)"><i class="fa fa-plus"></i>Upload File</button><br>
                                    <div id="submission-task103-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>


                                    <button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-103" onclick="finish_task(103)"><i class="fa fa-share"></i>Complete Task 3</button>
                                    <button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-103" onclick="view_report(103)"><i class="fa fa-eye"></i>Pre-view Report</button>




                                </form>
                            </div>
                            <br/>

                        </div>
                        <br/>

                        <h4>References:</h4>
                        <ol>
                            <li>1.	Whitebox, blackbox, and greyboax testing, <a href="https://www.nbs-system.com/en/blog/black-box-grey-box-white-box-testing-what-differences">https://www.nbs-system.com/en/blog/black-box-grey-box-white-box-testing-what-differences</a></li>
                            <li>DNS Installation and setup on Ubuntu, <a href="https://help.ubuntu.com/lts/serverguide/dns.html.en">https://help.ubuntu.com/lts/serverguide/dns.html.en</a></li>
                            <li>Ubuntu Tutorials including Apache2 installation and setup tutorials, <a href="https://tutorials.ubuntu.com/">https://tutorials.ubuntu.com/</a></li>
                            <li>Configure IPTables to support [FTP] passive transfer mode, <a href="http://blog.hakzone.info/posts-and-articles/ftp/configure-iptables-to-support-ftp-passive-transfer-mode/">http://blog.hakzone.info/posts-and-articles/ftp/configure-iptables-to-support-ftp-passive-transfer-mode/</a></li>
                            <li>vsftp anonymous mode setup, <a href="https://www.digitalocean.com/community/tutorials/how-to-set-up-vsftpd-for-anonymous-downloads-on-ubuntu-16-04">https://www.digitalocean.com/community/tutorials/how-to-set-up-vsftpd-for-anonymous-downloads-on-ubuntu-16-04</a></li>
                            <li>Security testing with Hping, <a href="http://www.linux-magazine.com/Issues/2009/99/Hping">http://www.linux-magazine.com/Issues/2009/99/Hping</a></li>
                            <li>Testing Firewalls with Hping3, <a href="http://0daysecurity.com/articles/hping3_examples.html">http://0daysecurity.com/articles/hping3_examples.html</a></li>
                            <li>Nmap firewall testing, <a href="https://opensourceforu.com/2011/02/advanced-nmap-scanning-firewalls/">https://opensourceforu.com/2011/02/advanced-nmap-scanning-firewalls/</a> , <a href="https://searchsecurity.techtarget.com/tip/Nmap-Firewall-configuration-testing">https://searchsecurity.techtarget.com/tip/Nmap-Firewall-configuration-testing</a></li>
                            <li>Nmap Network Scanning, <a href="https://nmap.org/book/toc.html">https://nmap.org/book/toc.html</a>, Chapter 7:Nmap Scripting Engine, <a href="https://nmap.org/book/nse.html">https://nmap.org/book/nse.html</a></li>
                            <li>Top 125 Network Security Tools, <a href="https://sectools.org/">https://sectools.org/</a></li>
                        </ol>

                    </div>
                    <!-- /.box-body -->
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
                    <button type="button" class="btn btn-primary" id="quiz-submit4" onclick="postsubmission(4,12, '{!! $lab !!}')">Check</button>
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
                        <button type="button" class="btn btn-primary" id="quiz-submit0" onclick="postsubmission(0,12, '{!! $lab !!}')">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

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
        var countDownDate = new Date("Sep 28, 2018 23:59:59 GMT-07:00 ").getTime();
        var countDownDate2 = new Date("Oct 13, 2018 23:59:59 GMT-07:00 ").getTime();
        var countDownDate3 = new Date("Oct 26, 2018 23:59:59 GMT-07:00 ").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get todays date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            var distance2 = countDownDate2 - now;
            var distance3 = countDownDate3 - now;
            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            var days2 = Math.floor(distance2 / (1000 * 60 * 60 * 24));
            var hours2 = Math.floor((distance2 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes2 = Math.floor((distance2 % (1000 * 60 * 60)) / (1000 * 60));
            var seconds2 = Math.floor((distance2 % (1000 * 60)) / 1000);
            var days3 = Math.floor(distance3 / (1000 * 60 * 60 * 24));
            var hours3 = Math.floor((distance3 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes3 = Math.floor((distance3 % (1000 * 60 * 60)) / (1000 * 60));
            var seconds3 = Math.floor((distance3 % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"
            if ( $( "#countdown" ).length ) {
                document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";
            }
            if ( $( "#countdown2" ).length ) {
                document.getElementById("countdown2").innerHTML = days2 + "d " + hours2 + "h "
                    + minutes2 + "m " + seconds2 + "s ";
            }
            if ( $( "#countdown3" ).length ) {
                document.getElementById("countdown3").innerHTML = days3 + "d " + hours3 + "h "
                    + minutes3 + "m " + seconds3 + "s ";
            }

            // If the count down is over, write some text
            if (distance < 0) {
                if(document.getElementById("countdownall").innerHTML !="Submitted"){
                    document.getElementById("countdownall").innerHTML = "EXPIRED";
                }

            }
            if (distance2 < 0) {
                if(document.getElementById("countdownall2").innerHTML !="Submitted"){
                    document.getElementById("countdownall2").innerHTML = "EXPIRED";
                }
            }
            if (distance3 < 0) {
                if(document.getElementById("countdownall3").innerHTML !="Submitted"){
                    document.getElementById("countdownall3").innerHTML = "EXPIRED";
                }
            }

        }, 1000);


        $(document).ready(function() {

            $.getJSON( "/checktaskfinished",{

                "taskid": 101

            }, function( data ) {
                if(data[0].finished){
                    $("#bs-wizard-step-101").addClass('complete').children().attr('title','Finished');
                    document.getElementById("countdownall").innerHTML = "Submitted";
                }
            });
            $.getJSON( "/checktaskfinished",{

                "taskid": 102

            }, function( data ) {
                if(data[0].finished){
                    $("#bs-wizard-step-102").addClass('complete').children().attr('title','Finished');
                    document.getElementById("countdownall2").innerHTML = "Submitted";
                }
            });
            $.getJSON( "/checktaskfinished",{

                "taskid": 103

            }, function( data ) {
                if(data[0].finished){
                    $("#bs-wizard-step-103").addClass('complete').children().attr('title','Finished');
                    document.getElementById("countdownall3").innerHTML = "Submitted";
                }
            });
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