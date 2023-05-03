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
                        {{--<h3 class="box-title">Iptables Firewall Setup</h3>--}}
                        <div class="bs-wizard" style="border-bottom:0;">

                            <div id="bs-wizard-step-100" class="col-xs-3 bs-wizard-step ">
                                <div class="text-center bs-wizard-stepnum">Intro</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_100" class="bs-wizard-dot" onclick="active_bar_step(this)" title="Submission 1"></a>
                            </div>


                            <div id="bs-wizard-step-104" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 1</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_104" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>

                            <div id="bs-wizard-step-105" class="col-xs-3 bs-wizard-step">
                                <div class="text-center bs-wizard-stepnum">Task 2</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#task_105" class="bs-wizard-dot" onclick="active_bar_step(this)"></a>
                            </div>

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

                            <p>Software and Service</p>

                            <p><b>Objects</b>: </p>

                            <p>1. &nbsp; &nbsp;Install Apache2 Web Service on Ubuntu  </p>

                            <p>2. &nbsp; &nbsp;Configure the default Apache Web Service on Ubuntu </p>

                            <p>3. &nbsp; &nbsp;Configure a demo virtual host Apache Web Service on Ubuntu</p>

                            <p>4. &nbsp; &nbsp;Setup network intrusion detection to detect malicious traffic</p>

                            <p>5. &nbsp; &nbsp;Setup centralized logging system to collect snort alerts for post-event analysis.</p>


                            <p><b>Estimated Time</b>: </p>

                            <p> &nbsp; &nbsp; &nbsp; &nbsp;
                                Expert:
                                60 minutes</p>

                            <p> &nbsp; &nbsp; &nbsp; &nbsp;
                                Novice:
                                360 minutes</p>

                            <p><b>Required OS</b>: </p>
                            <p>Ubuntu</p>
                            <p><b>Preparations</b>: </p>
                            <p>1. &nbsp; &nbsp;  ThoTh Lab is used to run
                                the project.  </p>
                            <p>2. &nbsp; &nbsp;  Three virtual machines are assigned: Client, Gateway and Server.</p>
                            <p>
                                We are re-using the same environment in your first lab. </p>
                            <p>Gateway should allow both client and server to access to Internet. Please check your gateway firewall setup (e.g., iptables) to make sure both client and server can access to the public Internet. </p>

                            &nbsp; &nbsp; &nbsp; &nbsp;
                            {{--<div class="text-center">--}}
                                {{--<button type="button" class="btn btn-default btn-info" data-toggle="modal" data-target="#quiz-0" id="startquiz"><i class="fa fa-file-text"></i> Take Pre-Lab Survey</button>--}}
                                {{--<button type="button" class="btn btn-default btn-warning" data-toggle="modal" data-target="#quiz-4" id="startquiz"><i class="fa fa-warning"></i>Take Pre-Lab Quiz</button>--}}
                            {{--</div>--}}
                        </div>

                        <div id="task_104">
                            <h3 class="box-title">Task 1: Secure Web Service Setup</h3>

                            <p>Please follow the following procedures:</p>
                            <ol style="list-style-type: none;">
                                <li>1.1 Install Apache2 on Server.(Already done in Lab 2)</li>
                                <li>1.2 Create a virtual host on Server</li>
                                <ol style="list-style-type: lower-alpha;">
                                    <li>Go to /etc/apache2/sites-available folder</li>
                                    <li>Copy default to a virtual host file, name it demo.conf</li>
                                    <li>Edit demo.conf</li>
                                        <em>&lt;virtualHost *:80&gt;</em><br/>
                                        <em>&hellip;</em><br/>
                                        <em>DocumentRoot /var/www/demo</em><br/>
                                        <em>ServerName demo.asu-securitylab.com</em>
                                    <li>Create /var/www/demo folder and index.html in the folder (e.g., a hello world html page)</li>
                                    <li>Enable demo virtual host by</li>
                                    <em>a2ensite demo.conf</em>
                                    <li>Restart apache2</li>
                                    <li>Open a browser and test the webpage demo.asu-sercuritylab.com</li>

                                </ol>
                                <li>1.3 Create a self-signed certificate and configure https on Server</li>
                                In the latest ubuntu, the openssl is installed by default. If not, you should use apt-get to install openssl.
                                <ol style="list-style-type: lower-alpha;">
                                    <li>In the /etc/apache2/ folder create a folder /etc/apache2/sslcert/</li>
                                    <li>In /etc/apache2/sslcert/, create a server key and certificate by issuing the following command:</li>
                                    <em>openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout server.key -out serve.crt</em>
                                    <li>Go to /etc/apache2/sites-available folder, edit demo.conf:</li>
                                    <em>&lt;virtualHost *:443&gt;</em><br/>
                                    <em>&hellip;</em><br/>
                                    <em>DocumentRoot /var/www/demo</em><br/>
                                    <em>ServerName demo.asu-securitylab.com</em><br/>
                                    <em>SSLEngine on<br /> SSLCertificateFile /etc/apache2/sslcert/server.crt</em><br/>
                                    <em>SSLCertificateKeyFile /etc/apache2/sslcert/server.key</em>
                                    <li>Similarly, edit the default-ssl to change the configuration accordingly, and enable the default site ssl by issuring command:</li>
                                    <em>a2ensite default-ssl</em>
                                    <li>Restart apache2</li>
                                    <li>Open a browser and test the webpage https://demo.asu-sercuritylab.com and https://www.asu-securitylab.com </li>
                                </ol>
                                <li>1.4 Establish basic authentication for webservice (optional, bonus points)</li>
                                For basic authentication, users account information is maintained at the web server. It allows the server to authenticated a registered user to access the web content.
                                <ol style="list-style-type: lower-alpha;">
                                    <li>Create a few demo user accounts, e.g., user1, user2</li>
                                    <li>Shows the procedure to enable basic authentication for apache2</li>
                                    <li>Demo the authentication is successful by using the client browser to access the https://demo.asu-securitylab.com  webpage. </li>
                                </ol>

                            </ol>


                            <button type="button" class="btn btn-default btn-info btn-task-104"  id="startsubmission_104" onclick="opensubmission(104)"><i class="fa fa-plus"></i> Task 1 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall">You've got <span id="countdown"></span> left!</span><br/><b style="color: red;">Task 1 Due on Nov 9th, 2018 23:59:59 AZ Time!</b>

                            <div id='submission_104' class="modal-body" style="display: none">
                                <form>
                                    1.1 None submission.<br/>
                                    1.2.1 Take screenshot(s) of your demo.conf file<br>
                                    <button id='sub_104_1' type="button" class="btn btn-default btn-add-screenshot btn-task-104" onclick="takescreenshot(104,613,1,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task104-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.2.2 Open a browser and test the webpage demo.asu-sercuritylab.com, take a screenshot to show your result.<br>
                                    <button id='sub_104_2' type="button" class="btn btn-default btn-add-screenshot btn-task-104" onclick="takescreenshot(104,613,2,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task104-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.3.1 Take screenshot(s) of your demo.conf file<br>
                                    <button id='sub_104_3' type="button" class="btn btn-default btn-add-screenshot btn-task-104" onclick="takescreenshot(104,613,3,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task104-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.3.2 Open a browser and test the webpage https://demo.asu-sercuritylab.com and https://www.asu-securitylab.com, take screenshots to show your result.<br>
                                    <button id='sub_104_4' type="button" class="btn btn-default btn-add-screenshot btn-task-104" onclick="takescreenshot(104,613,4,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task104-4" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    1.4 (Optional with bonus points) Demo the authentication is successful by using the client browser to access the https://demo.asu-securitylab.com  webpage, take screenshots to show your result. Also take screenshots of configure files you've changed.<br>
                                    <button id='sub_104_5' type="button" class="btn btn-default btn-add-screenshot btn-task-104" onclick="takescreenshot(104,613,5,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task104-5" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    <button type="button" data-toggle="modal" data-target="#survey-0" id="startsurvey-100" class="btn btn-default btn-info" ><i class="fa fa-file-text"></i> Take Pre-Lab Survey</button>
                                    <button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-104" onclick="finish_task(104)" disabled title="Please finish survey first!"><i class="fa fa-share"></i>Complete Task 1</button>
                                    <button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-104" onclick="view_report(104)"><i class="fa fa-eye"></i>Pre-view Report</button>



                                </form>
                            </div>
                            <br/>

                        </div>
                        <br />
                        <div id="task_105">
                            <h3 class="box-title">Task 2: Network Intrusion Detection and Prevention - Snort and Syslog</h3>

                            <ol style="list-style-type: none;">
                                <li>2.1 Install Snort and Set up Syslog service</li>
                                <ol style="list-style-type: lower-alpha;">
                                    <li>Please follow the instructions from https://www.snort.org to install the latest version of snort on Gateway.<br/>
                                        During the installation of snort, you will be asked for your home network address. The server network is your home (or private) network.<br/>
                                        Hint: saw a lot of error saying missing library?<br/>
                                        Run <em>sudo apt-get update</em>

                                        and
                                        <em>sodo apt-get install snort </em>
                                    </li>
                                    <li>(Optional, do this ONLY IF you want to finish task 2.3) Make sure you have installed the required module to perform IPS with NFQ in Snort.<br/>
                                        To enable NFQ, you need to install NFQ specific libraries by <br/>
                                        <em>sudo apt-get install libnetfilter-queue-dev</em><br/>
                                        After the&nbsp;./configure&nbsp;option, you will see the DAQ modules that are enabled. You must have NFQ enabled here, as seen below:<br/>
                                        <table width="902">
                                            <tbody>
                                            <tr>
                                                <td width="872">
                                                    <p>Build AFPacket DAQ module.. : yes</p>
                                                    <p>Build Dump DAQ module...... : yes</p>
                                                    <p>Build IPFW DAQ module...... : yes</p>
                                                    <p>Build IPQ DAQ module....... : no</p>
                                                    <p>Build NFQ DAQ module....... : yes&nbsp; &lt;&lt;&lt;&lt;&lt; MUST BE YES</p>
                                                    <p>Build PCAP DAQ module...... : yes</p>
                                                    <p>Build netmap DAQ module.... : no</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </li>
                                    <li>Make sure you have Web Server, ssh and syslog (rsyslog) running on the server.<br/>
                                    You may check by run:<br/>

                                        <em>service apache2 status</em>, <em>service ssh status</em> and <em>service rsyslog status</em></li>



                                </ol>
                                <li>2.2 Snort IDS mode setup and traffic inspection</li>
                                <ol style="list-style-type: lower-alpha;">
                                    <li>Edit snort configuration file (snort.conf) located in /etc/snort/</li>
                                    <li>For testing purpose, not all rulesets are needed, so run the following command to comment out all of them:<br/>
                                        <em>sudo sed -i "s/include \$RULE\_PATH/#include \$RULE\_PATH/" /etc/snort/snort.conf</em></li>

                                    <li>To enable local rules and test snort, uncomment the following line in icmp.rules<br/><em>include $RULE_PATH/icmp.rules</em></li>

                                    <li>Test the configuration file using:<br/><em>sudo snort -T -i ens160 -c /etc/snort/snort.conf</em><br/>
                                        and successful output should looks like:<br/><em>Snort successfully validated the configuration!<br/>
                                            Snort exiting
                                        </em></li>
                                    <li>Edit icmp.rules, adding the following rule:</li>
                                    <ol>
                                        <li>Comment out all existing icmp rules</li>
                                        <li>Set up the following rule:<br/>
                                            <em>alert icmp any any -> $HOME_NET any (msg:"ICMP test detected"; GID:1; sid:10000001; rev:001; classtype:icmp-event;)</em><br/>
                                            This rule means generate an alert on icmp packets originated from any source and any port to the HOME_NET on any port. HOME_NET can be changed to include HOME_NET IP.
                                        </li>
                                        </ol>
                                    <li>For testing, from the gateway enable ping from the client network to the server network, and ping from the client to the server and make snort listen on the server side network interface X (make sure the firewall is setup correctly to allow ping from the client to the server):<br/>
                                        <em>sudo /usr/local/bin/snort -A console -q -u snort -g snort -c /etc/snort/snort.conf -i ethX</em><br/>The snort should see the ping alert from the console, additionally, the alert should be also saved in the log file /var/log/snort/snort.log.</li>
                                    <li>Once step f is passed, disable the icmp testing rule, and compose a new rule to sent an alert when capture ping message when there are more than 10 icmp request sent from the same IP address. And test your solution.</li>

                                </ol>

                                <li>2.3 Snort IPS mode setup and traffic inspection (Optional)<br/>Write rules in the snort and setup iptables properly on the Gateway to satisfy the following requirements:</li>
                                <ol style="list-style-type: lower-alpha;">
                                    <li>Write simple rules in the local.rules to check the traffic originated from the client (or attacker) to the server.</li>
                                <ol>
                                    <li>Any HTTP connection request from the client to the server.
                                        <br/>Hint: source ip is client&rsquo;s ip, dest ip should be server&rsquo;s ip and dest port should be 80.
                                    </li>
                                    <li>Any ssh connection request from the client to the server.
                                        <br/>Hint: source ip is client&rsquo;s ip, dest ip should be server&rsquo;s ip and dest port should be 22.
                                    </li>
                                    <li>ICMP echo request message with sequence number = 10 (using ping command from the client to the server).
                                        <br/>Hint: The key is the sequence number 7.
                                    </li>
                                </ol>
                                    <li>Configure Snort in the IPS mode with DAQ type afpacket and block the ping traffic from the attacker to the server. (Note that your iptables should initially allow ping from the client to the server without using snort; once the Snort starts, detected ping traffic when it reaches seq#=10, ping should be blocked.)
                                    <br/>Hint: Block ICMP protocol.
                                </li>
                                    <li>
                                        Configure Snort in the IPS mode with DAQ type NFQ, and block HTTP request from the attacker to the server.
                                        <br/>Hint: Block port 80.
                                    </li>
                                    <li>The snort log should be handled by the rsyslog. The rsyslog server is running at the server. The gateway should generate log and store them at the server side (you can decide using what file at the server side to store the logs).
                                        <br/>Hint: install rsyslog on both gateway and server, then config rsyslog on gateway to send log to server. The configuration should be done in /etc/rsyslog.conf
                                    </li>
                                </ol>



                            </ol>


                            <button type="button" class="btn btn-default btn-info btn-task-105"  id="startsubmission_105" onclick="opensubmission(105)"><i class="fa fa-plus"></i> Task 2 Submission</button><span class="label label-warning" style="font-size:100%" id="countdownall2">You've got <span id="countdown2"></span> left!</span><br/><b style="color: red;">Task 2 Due on Nov 16th, 2018 23:59:59 AZ Time!</b>

                            <div id='submission_105' class="modal-body" style="display: none">
                                <form>

                                    2.1 Take screenshots of <em>service apache2 status</em>, <em>service ssh status</em> and <em>service rsyslog status</em> result:<br>
                                    <button id='sub_105_1' type="button" class="btn btn-default btn-add-screenshot btn-task-105" onclick="takescreenshot(105,615,1,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task105-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.2.d Take a screenshot of the result of configure file testing:<br>
                                    <button id='sub_105_2' type="button" class="btn btn-default btn-add-screenshot btn-task-105" onclick="takescreenshot(105,615,2,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task105-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.2.f Take screenshots of ping alert from the console and alert logs in log file /var/log/snort/snort.log<br>
                                    <button id='sub_105_3' type="button" class="btn btn-default btn-add-screenshot btn-task-105" onclick="takescreenshot(105,615,3,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task105-3" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.2.g Take a screenshot of the new rule you added.<br>
                                    <button id='sub_105_4' type="button" class="btn btn-default btn-add-screenshot btn-task-105" onclick="takescreenshot(105,615,4,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task105-4" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    2.3 Take screenshots of snort rules you added for subtask a,b,c and test result for each subtask. For subtask d, take screenshots of rsyslog configure files on both GW and Server.<br>
                                    <button id='sub_105_5' type="button" class="btn btn-default btn-add-screenshot btn-task-105" onclick="takescreenshot(105,615,5,'Server')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br>
                                    <div id="submission-task105-5" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>
                                    <button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-105" onclick="finish_task(105)"  title="Please finish survey first!"><i class="fa fa-share"></i>Complete Task 2</button>
                                    <button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-105" onclick="view_report(105)"><i class="fa fa-eye"></i>Pre-view Report</button>




                                </form>
                            </div>
                            <br/>

                        </div>
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

                        <h4>References:</h4>
                        <ol>
                            <li>Ubuntu Tutorials including Apache2 installation and setup tutorials, <a href="https://tutorials.ubuntu.com/">https://tutorials.ubuntu.com/</a></li>
                            <li>How to setup Apache Virtual Hosts on Ubuntu 14.04, <a href="https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts">https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts</a></li>
                            <li>How to create an Install an Apache Self-signed Certificate, <a href="https://www.sslshopper.com/article-how-to-create-and-install-an-apache-self-signed-certificate.html">https://www.sslshopper.com/article-how-to-create-and-install-an-apache-self-signed-certificate.html</a></li>
                            <li>Writing Snort Rules: How To write Snort rules and keep your sanity (version 1.7) by Martin Roesch, <a href="https://paginas.fe.up.pt/~mgi98020/pgr/writing_snort_rules.htm">https://paginas.fe.up.pt/~mgi98020/pgr/writing_snort_rules.htm</a></li>
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
    <div class="modal fade" id="survey-0" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pre-lab Survey:</h4>
                </div>
                <div class="modal-body">
                    <form id="survey_form">
                        <input type="hidden" name="taskid" value="100">
                        <input type="hidden" name="labid" value="615">
                        <div><strong>Question 1</strong>: The previous labs provide good hands-on skills to understand network foundations.<br /> <input name="q1" type="radio" value="0" />strongly disagree <input name="q1" type="radio" value="1" />disagree <input name="q1" type="radio" value="2" />neutral <input name="q1" type="radio" value="3" />agree <input name="q1" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 2</strong>: The previous lab material is well-designed and easy to practice.<br /> <input name="q2" type="radio" value="0" />strongly disagree <input name="q2" type="radio" value="1" />disagree <input name="q2" type="radio" value="2" />neutral <input name="q2" type="radio" value="3" />agree <input name="q2" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 3</strong>: The practice of Quiz in ThoTh lab helps my lab works.<br /> <input name="q3" type="radio" value="0" />strongly disagree <input name="q3" type="radio" value="1" />disagree <input name="q3" type="radio" value="2" />neutral <input name="q3" type="radio" value="3" />agree <input name="q3" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 4</strong>: How do you rate the workload of this class so far?<br /> <input name="q4" type="radio" value="0" />too low <input name="q4" type="radio" value="1" />on the low side <input name="q4" type="radio" value="2" />average <input name="q4" type="radio" value="3" />on the high side <input name="q4" type="radio" value="4" />too high</div><br />
                        <div><strong>Question 5</strong>: How much do you understand about the lab works so far?<br /> <input name="q5" type="radio" value="0" />Less than 25% <input name="q5" type="radio" value="1" />25%-50% <input name="q5" type="radio" value="2" />50%-75% <input name="q5" type="radio" value="3" />75%-95% <input name="q5" type="radio" value="4" />above 95%</div><br />
                        <div><strong>Question 6</strong>: How much do you understand about the lab works so far?<br /> <input name="q6" type="radio" value="0" />Less than 25% <input name="q6" type="radio" value="1" />25%-50% <input name="q6" type="radio" value="2" />50%-75% <input name="q6" type="radio" value="3" />75%-95% <input name="q6" type="radio" value="4" />above 95%</div><br />
                        <div><strong>Question 7</strong>: How do you think the learning pace so far in the class?<br /> <input name="q7" type="radio" value="0" />too slow <input name="q7" type="radio" value="1" />a bit slow <input name="q7" type="radio" value="2" />just right <input name="q7" type="radio" value="3" />a bit fast <input name="q7" type="radio" value="4" />too fast</div><br />
                        <div><strong>Question 8</strong>: Do you think that the lab assignment helps you learn?<br /> <input name="q8" type="radio" value="0" />definitely yes <input name="q8" type="radio" value="1" />to some extent <input name="q8" type="radio" value="2" />sort of <input name="q8" type="radio" value="3" />not really <input name="q8" type="radio" value="4" />not at all</div><br />
                        <div><strong>Question 9</strong>: Do you feel you have enough knowledge to complete lab assignment?<br /> <input name="q9" type="radio" value="0" />definitely yes <input name="q9" type="radio" value="1" />to some extent <input name="q9" type="radio" value="2" />sort of <input name="q9" type="radio" value="3" />not really <input name="q9" type="radio" value="4" />not at all</div><br />
                        <div><strong>Question 10</strong>: How much do you understand the lecture materials?<br /> <input name="q10" type="radio" value="0" />Less than 25% <input name="q10" type="radio" value="1" />25%-50% <input name="q10" type="radio" value="2" />50%-75% <input name="q10" type="radio" value="3" />75%-95% <input name="q10" type="radio" value="4" />above 95%</div><br />
                        <div><strong>Question 11</strong>: How much do you understand the lab materials?<br /> <input name="q11" type="radio" value="0" />Less than 25% <input name="q11" type="radio" value="1" />25%-50% <input name="q11" type="radio" value="2" />50%-75% <input name="q11" type="radio" value="3" />75%-95% <input name="q11" type="radio" value="4" />above 95%</div><br />
                        <div><strong>Question 12</strong>: Lab 1 and Lab 2 encourages me to be interested in network security issues.<br /> <input name="q12" type="radio" value="0" />Strongly disagree <input name="q12" type="radio" value="1" />disagree <input name="q12" type="radio" value="2" />neutral <input name="q12" type="radio" value="3" />agree <input name="q12" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 13</strong>: ThoThLab helps me to understand a practical network environment.<br /> <input name="q13" type="radio" value="0" />Strongly disagree <input name="q13" type="radio" value="1" />disagree <input name="q13" type="radio" value="2" />neutral <input name="q13" type="radio" value="3" />agree <input name="q13" type="radio" value="4" />strongly agree</div><br />
                        <div><strong>Question 14</strong>: Please provide a brief reflection of Lab 1 and Lab 2, and a feedback of using ThoThlab.</div><textarea spellcheck="true" style="max-width: 100%;width : 100%" id="q14" name="q14" cols="50" rows="2"></textarea>
                    </form><p>&nbsp;</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="survey-submit100" onclick="submitservey()">Save</button>
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
        var countDownDate = new Date("Nov 9, 2018 23:59:59 GMT-07:00 ").getTime();
        var countDownDate2 = new Date("Nov 16, 2018 23:59:59 GMT-07:00 ").getTime();
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