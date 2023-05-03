
function loadtask(taskid){
    if(taskid == 2){
        if(dones[taskid]==1){
            //document.getElementById("startquiz").setAttribute('disabled', 'disabled');
            document.getElementById("gototask").removeAttribute('disabled');
            $
        }else if(dones[taskid]==0){
            document.getElementById("startquiz").removeAttribute('disabled');
            //document.getElementById("gototask").setAttribute('disabled', 'disabled');
        }

        $("#startquiz").attr('data-target', '#quiz-2');
        $("#gototask").attr('onclick', 'loadtask(3)');
        $("#lasttask").attr('onclick', 'loadtask(1)');
        $("#lasttask").show();
        $("#gototask").show();
        $("#finaltask").hide();
    document.getElementById('taskbox').innerHTML = "<p>2.Setup IPtables packet forwarding policies on Gateway to allow:</p>\n" +
        "                                            <ul>\n" +
        "                                                <li><em>Ping from the client to the server and vis versa</em></li>\n" +
        "                                                <li><em>Ping from the client/server to the public domain such as google server 8.8.8.8</em></li>\n" +
        "                                            </ul>\n";

    }else if(taskid == 3){
        if(dones[taskid]==1){
            //document.getElementById("startquiz").setAttribute('disabled', 'disabled');
            document.getElementById("finaltask").removeAttribute('disabled');
        }else if(dones[taskid]==0){
            document.getElementById("startquiz").removeAttribute('disabled');
            //document.getElementById("finaltask").setAttribute('disabled', 'disabled');
        }

        $("#startquiz").attr('data-target', '#quiz-3');
        $("#lasttask").attr('onclick', 'loadtask(2)');
        $("#lasttask").show();
        $("#finaltask").show();
        $("#gototask").hide();

        document.getElementById('taskbox').innerHTML = "<p>Based on the network topology, learn how to use the following commands on Linux VMs:</p>\n" +
            "\n" +
            "                                            <p><em>Network interface configurations:</em></p>\n" +
            "\n" +
            "                                            <ul>\n" +
            "                                                <li><em>ifup</em></li>\n" +
            "                                                <li><em>ifdown</em></li>\n" +
            "                                                <li><em>ifconfig</em></li>\n" +
            "                                            </ul>\n" +
            "\n" +
            "                                            <p><em>Network reachability:</em></p>\n" +
            "\n" +
            "                                            <ul>\n" +
            "                                                <li><em>ping</em></li>\n" +
            "                                                <li><em>traceroute</em></li>\n" +
            "                                            </ul>\n" +
            "\n" +
            "                                            <p><em>Network interconnection and setup</em></p>\n" +
            "\n" +
            "                                            <ul>\n" +
            "                                                <li><em>netstat</em></li>\n" +
            "                                                <li><em>route</em></li>\n" +
            "                                            </ul>\n" +
            "\n" +
            "                                            <p><em>Capturing network traffic</em></p>\n" +
            "\n" +
            "                                            <ul>\n" +
            "                                                <li><em>tcpdump</em></li>\n" +
            "                                            </ul>\n" +
            "\n" +
            "                                            <p><em>Remote access and name services</em></p>\n" +
            "\n" +
            "                                            <ul>\n" +
            "                                                <li><em>telnet/ssh</em></li>\n" +
            "                                                <li><em>hostname/host</em></li>\n" +
            "                                                <li><em>&hellip;</em></li>\n" +
            "                                            </ul>\n" +
            "\n" +
            "                                            <p><em>Others</em></p>\n" +
            "                                            <ul>\n" +
            "                                                <li><em>nslookup&nbsp; # DNS</em></li>\n" +
            "                                                <li><em>dig&nbsp;&nbsp;&nbsp; # DNS</em></li>\n" +
            "                                                <li><em>arp&nbsp;&nbsp;&nbsp; #MAC and IP mapping</em></li>\n" +
            "                                                <li><em>nmap # port scanning</em></li>\n" +
            "                                                <li><em>whois&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; # Domain name</em></li>\n" +
            "                                                <li><em>dhclient&nbsp;&nbsp;&nbsp; #DHCP client</em></li>\n" +
            "                                                <li><em>&hellip;</em></li>\n" +
            "                                            </ul>\n" +
            "\n" +
            "                                            <p>Reference:</p>\n" +
            "\n" +
            "                                            <ol>\n" +
            "                                                <li>Network commands<br />\n" +
            "                                                    <a href=\"http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/c8319.htm\">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/c8319.htm</a> &nbsp;</li>\n" +
            "                                            </ol>\n" +
            "                                            <ol>\n" +
            "                                                <li>Internet commands<br />\n" +
            "                                                    <a href=\"http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm\">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm</a></li>\n" +
            "                                            </ol>\n" +
            "\n" +
            "                                            <ol>\n" +
            "                                                <li>Remote access and downloading commands<br />\n" +
            "                                                    <a href=\"http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm\">http://www.tldp.org/LDP/GNU-Linux-Tools-Summary/html/x8751.htm</a></li>\n" +
            "                                            </ol>";
    }else if(taskid == 1){
        if(dones[taskid]==1){
            //document.getElementById("startquiz").setAttribute('disabled', 'disabled');
            document.getElementById("gototask").removeAttribute('disabled');

        }else if(dones[taskid]==0){
            document.getElementById("startquiz").removeAttribute('disabled');
            //document.getElementById("gototask").setAttribute('disabled', 'disabled');
        }

        $("#startquiz").attr('data-target', '#quiz-1');
        $("#gototask").attr('onclick', 'loadtask(2)');
        $("#lasttask").hide();
        $("#finaltask").hide();
        document.getElementById('taskbox').innerHTML = "<p>Click on 'Access Lab Workspace' below, it will show your virtual lab environment. Move your mouse over a VM, it will show all network interfaces of that VM, their IP configurations, and running status.</p>\n" +
            "\n" +
            "                                            <p>Notes:</p>\n" +
            "\n" +
            "                                        <ol>\n" +
            "                                            <li>Usually system will assign one or two DHCP interfaces to each private network to allocate IP addresses to each VM.</li>\n" +
            "                                            <li>Changing IP addresses may cause network disconnection, thus change with cautious or DO NOT change IP addresses for each VM interface.</li>\n" +
            "                                            <li>In order to raise the privilege to run some commands (i.e., usually see &ldquo;&hellip;operation not permitted&rdquo;), then increase user&rsquo;s privilege is required:<br />\n" +
            "                                                <em>#sudo &ndash;i</em>&nbsp;&nbsp;&nbsp; # raise no matter what.</li>\n" +
            "                                        </ol>" +
            "<p><em>Note that: All Linux commands are case sensitive</em></p>";
    }
}