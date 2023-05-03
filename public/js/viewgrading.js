
function viewgrading(userid,taskid,email,groupid) {
    if (taskid=='101'){
        var subtaskarray = [1, 2, 3, 4, 5];
        var message ='';
        var report=[];
        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
        });
        report[1]=report[1]+' 1.1:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow internet access for your Client and Server.</p><h4>Your Submission:</h4>'
        report[2]=report[2]+' 1.2a:</h3><p> Take screenshot(s) on your server to show your hello world demo page.</p><h4>Your Submission:</h4>'
        report[3]=report[3]+' 1.2b:</h3><p> Take screenshot(s) of your vsftpd.conf on your server and explain what you changed.</p><h4>Your Submission:</h4>'
        report[4]=report[4]+' 1.2c:</h3><p> Take screenshot(s) of zone file(s) you added, explain what you did. Also take screenshots on your server to show nslookup results of added URLs.</p><h4>Your Submission:</h4>'
        report[5]=report[5]+' 1.3:</h3><p>Take screenshot(s) on your Client to show your installation command line output.(It\'s ok if your already install them, just run the installation command again and show the result.)</p><h4>Your Submission:</h4>'

    }else if(taskid=='102'){
        var subtaskarray = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        var message ='';
        var report=[];
        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
        });
        report[1]=report[1]+' 2.1:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to drop all traffic in all chain by default.</p><h4>Your Submission:</h4>'
        report[2]=report[2]+' 2.3a:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow DNS traffic from Client to Server.</p><h4>Your Submission:</h4>'
        report[3]=report[3]+' 2.3b:</h3><p> Take screenshot(s) of nslookup result of any three URLs in the list on the Client. </p><h4>Your Submission:</h4>'
        report[4]=report[4]+' 2.4a:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow Client to access the web page (http) on Server.</p><h4>Your Submission:</h4>'
        report[5]=report[5]+' 2.4b:</h3><p> Take screenshot(s) on your Client to show that you can access those two webpages on Server./p><h4>Your Submission:</h4>'
        report[6]=report[6]+' 2.4c:</h3><p> (Challenging Question - Bonus Question) What if you want to open these two URLs from the server-side network? For example, when you browse the URLs in the browser on the server? If it does not work, can you change iptables rules to make it work? Why or why not? If it does not work, what is the most effective solution to address this issue considering that you have a large numbers of hosts on the server-side network and they can also access to the web server? Please describe your solution and if you make configuration changes, please take screenshots and explain your solution.</p><h4>Your Submission:</h4>';
        report[7]=report[7]+' 2.5a:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow Client to access the ftp server on Server </p><h4>Your Submission:</h4>';
        report[8]=report[8]+' 2.5b:</h3><p> Take screenshot(s) on your Client to show that how to access the ftp service from the client and download the demo file successfully.</p><h4>Your Submission:</h4>';
        report[9]=report[9]+' 2.6:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow Server to ping Client and GW.</p><h4>Your Submission:</h4>';
    }else if(taskid=='103'){
        var subtaskarray = [1, 2, 3];
        var message ='';
        var report=[];
        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
        });
        report[1]=report[1]+' 3.3:</h3><p> Provide screenshots to illustrate your scanning results for each type/option and provide output to show the evidence that you firewall was established properly based on the rule setup requirements from Task 2. If your results demonstrate unnecessary open ports/services, then you will get point deductions.</p><h4>Your Submission:</h4>'
        report[2]=report[2]+' 3.4:</h3><p> Submit your script/program as a file with good marked comments/illustrations in the script/program to explain function modules.</p><h4>Your Submission:</h4>'
        report[3]=report[3]+' 3.5:</h3><p> Submit a readme.txt file to illustrate required running environment and how to use the script. </p><h4>Your Submission:</h4>'
    }else if(taskid=='104'){
        var subtaskarray = [1, 2, 3, 4, 5];
        var message ='';
        var report=[];
        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
        });
        report[1]=report[1]+' 1.1:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow internet access for your Client and Server.</p><h4>Your Submission:</h4>'
        report[2]=report[2]+' 1.2a:</h3><p> Take screenshot(s) on your server to show your hello world demo page.</p><h4>Your Submission:</h4>'
        report[3]=report[3]+' 1.2b:</h3><p> Take screenshot(s) of your vsftpd.conf on your server and explain what you changed.</p><h4>Your Submission:</h4>'
        report[4]=report[4]+' 1.2c:</h3><p> Take screenshot(s) of zone file(s) you added, explain what you did. Also take screenshots on your server to show nslookup results of added URLs.</p><h4>Your Submission:</h4>'
        report[5]=report[5]+' 1.3:</h3><p>Take screenshot(s) on your Client to show your installation command line output.(It\'s ok if your already install them, just run the installation command again and show the result.)</p><h4>Your Submission:</h4>'

    }else if(taskid=='105'){
        var subtaskarray = [1, 2, 3, 4, 5];
        var message ='';
        var report=[];
        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
        });
        report[1]=report[1]+' 1.1:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow internet access for your Client and Server.</p><h4>Your Submission:</h4>'
        report[2]=report[2]+' 1.2a:</h3><p> Take screenshot(s) on your server to show your hello world demo page.</p><h4>Your Submission:</h4>'
        report[3]=report[3]+' 1.2b:</h3><p> Take screenshot(s) of your vsftpd.conf on your server and explain what you changed.</p><h4>Your Submission:</h4>'
        report[4]=report[4]+' 1.2c:</h3><p> Take screenshot(s) of zone file(s) you added, explain what you did. Also take screenshots on your server to show nslookup results of added URLs.</p><h4>Your Submission:</h4>'
        report[5]=report[5]+' 1.3:</h3><p>Take screenshot(s) on your Client to show your installation command line output.(It\'s ok if your already install them, just run the installation command again and show the result.)</p><h4>Your Submission:</h4>'

    }else if(taskid=='111'){
        var subtaskarray = [1, 2, 3, 4];
        var message ='';
        var report=[];
        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
        });
        report[1]=report[1]+' 1.2:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow internet access for your Client and Server.</p><h4>Your Submission:</h4>'
        report[2]=report[2]+' 1.3:</h3><p> Take screenshot(s) on your server to show your hello world demo page.</p><h4>Your Submission:</h4>'
        report[3]=report[3]+' 1.4d:</h3><p> Take screenshot(s) of your vsftpd.conf on your server and explain what you changed.</p><h4>Your Submission:</h4>'
        report[4]=report[4]+' 1.4e:</h3><p> Take screenshot(s) of zone file(s) you added, explain what you did. Also take screenshots on your server to show nslookup results of added URLs.</p><h4>Your Submission:</h4>'

    }else{
        var subtaskarray = [1, 2, 3, 4];
        var message ='';
        var report=[];
        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]='<div class="report-subtask'+subtaskid+'"';
        });
        report[1]=report[1]+'id="imagearea"><h4>Screenshots:</h4>'
        report[2]=report[2]+'id="filearea"><h4>Files:</h4>'
        report[3]=report[3]+'id="textarea"><h4>Text:</h4>'
    }
    $.getJSON("/tasksubmissionbyuser", {

        "taskid": taskid,
        "userid": userid

    }, function (data) {
        var imagecount=0;
        var filecount=0;
        var textcount=0;

        for (var i = 0; i < data.length; i++) {
            if(data[i].feedback==null){
                data[i].feedback="";
            }
            if(data[i].type==1) {
                imagecount=imagecount+1;
                var taskcontent = '<div class="subtasksubmission">' +
                    '<p>Subject: <font color="red">' + data[i].title.substring(0, 50) + '</font></p>Screenshot:<br/><img src="' + data[i].submission + '" style="max-width: 95%" /></p>Descripiton: <font color="red">' + data[i].desc +
                    '</font><br/>' +
                    // '<input type="text" id="screenshottitle" maxlength="10"/><br/>
                     'Feedback:<textarea readonly spellcheck="true" style="max-width: 100%;width : 100%" class="gradingfeedback" id="'+ data[i].id+'" cols="50" rows="2">'+data[i].feedback+'</textarea>' +
                    '</p></div>';
                report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;

            }else if(data[i].type==2){
                filecount=filecount+1;
                var fullPath = data[i].submission;
                var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                var filename = fullPath.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                // var fileext=filename.split('.').pop();
                // var basename=filename.split(".", 1).pop();
                // var baseemail=email.split('@',1).pop();
                var taskcontent = '<div class="subtasksubmission">' +
                    '<p>Filename: <font color="red">' + filename + '</font></p>' +
                    '<button class="btn btn-default" title="Download File" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].submission+'\',\'_blank\')"><i class="fa fa-download"></i>Download File</button>' +
                    // '<a target="_blank" href="https://submissions.storage.mobicloud.asu.edu'+data[i].submission+'" download="'+basename+'-'+baseemail+'.'+fileext+'">Download File</a>'+
                    '<p>Descripiton: <font color="blue">' + data[i].desc +
                    '</font><br/>' +
                     'Feedback:<textarea readonly spellcheck="true" style="max-width: 100%;width : 100%" class="gradingfeedback" id="'+ data[i].id+'"  cols="50" rows="2">'+data[i].feedback+'</textarea>' +
                    '</p></div>';
                report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;
            }
        }

        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]=report[subtaskid]+'</div>';
            message=message+report[subtaskid];
        });
        $('#tobegrading').html(message).css('height','750px').css('overflow-y','auto');

        $('#gradingbox').show();
        $('#submissionbox').show();
        $('.content-wrapper').height(1200);
        // Split(['#upperpage', '#lowerpage'],
        //     {
        //         sizes: [50, 50],
        //         minSize: 300,
        //         direction:'vertical',
        //         //elementStyle: {'height': 'calc(50% - 5px'},
        //         //gutterStyle: function (dimension, gutterSize) { return {'flex-basis':  gutterSize + 'px'} },
        //         gutterSize: 6,
        //         cursor: 'col-resize',
        //     });
        $('#gradingtaskid').html(taskid);
        $('#gradinguserid').html(userid);
        $('#gradingusername').html(email);
        if(imagecount==0){
            $('#imagearea').empty();
        }
        if(filecount==0){
            $('#filearea').empty();
        }
        if(textcount==0){
            $('#textarea').empty();
        }

    });
    $.getJSON("/gettaskfullgrade", {

        "taskid": taskid,
        "groupid":groupid

    }, function (data) {
        $('#totalpointoftask').html(data.fullgrade);

    });
    $.getJSON("/getgrade", {

        "taskid": taskid,
        "userid": userid,
        "groupid":groupid

    }, function (data) {
         $('#taskgradingfeedback').val(data.feedback);

        $('#givenpoints').val(data.grade);
    });

}



function viewresult(labid,taskid){
    if(labid==614){
        if(taskid==101){
            var message = '<h3>Task 1 Grade: <a id="reportgrade" style="color: red"></a> out of 50</h3><h4>Feedback: <a id="reportfeedback" style="color: red"></a></h4></br>';
            var report = [];
            var subtaskarray = [1, 2, 3, 4, 5];
            subtaskarray.forEach(function (subtaskid) {
                report[subtaskid] = '<div class="report-subtask' + subtaskid + '"><h3>Subtask';
            });
            report[1] = report[1] + ' 1:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow internet access for your Client and Server.</p><h4>Submission:</h4>'
            report[2] = report[2] + ' 2.a:</h3><p> Take screenshot(s) on your server to show your hello world demo page.</p><h4>Submission:</h4>'
            report[3] = report[3] + ' 2.b:</h3><p> Take screenshot(s) of your vsftpd.conf on your server and explain what you changed.</p><h4>Submission:</h4>'
            report[4] = report[4] + ' 2.c:</h3><p> Take screenshot(s) of zone file(s) you added, explain what you did. Also take screenshots on your server to show nslookup results of added URLs.</p><h4>Submission:</h4>'
            report[5] = report[5] + ' 3:</h3><p>Take screenshot(s) on your Client to show your installation command line output.(It\'s ok if your already install them, just run the installation command again and show the result.)</p><h4>Submission:</h4>'
        }else if(taskid==102){
            var message = '<h3>Task 2 Grade: <a id="reportgrade" style="color: red"></a> out of 50</h3><h4>Feedback: <a id="reportfeedback" style="color: red"></a></h4></br>';
            var report = [];
            var subtaskarray = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            subtaskarray.forEach(function(subtaskid){
                report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
            });
            report[1]=report[1]+' 2.1:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to drop all traffic in all chain by default.</p><h4>Your Submission:</h4>'
            report[2]=report[2]+' 2.3a:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow DNS traffic from Client to Server.</p><h4>Your Submission:</h4>'
            report[3]=report[3]+' 2.3b:</h3><p> Take screenshot(s) of nslookup result of any three URLs in the list on the Client. </p><h4>Your Submission:</h4>'
            report[4]=report[4]+' 2.4a:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow Client to access the web page (http) on Server.</p><h4>Your Submission:</h4>'
            report[5]=report[5]+' 2.4b:</h3><p> Take screenshot(s) on your Client to show that you can access those two webpages on Server./p><h4>Your Submission:</h4>'
            report[6]=report[6]+' 2.4c:</h3><p> (Challenging Question - Bonus Question) What if you want to open these two URLs from the server-side network? For example, when you browse the URLs in the browser on the server? If it does not work, can you change iptables rules to make it work? Why or why not? If it does not work, what is the most effective solution to address this issue considering that you have a large numbers of hosts on the server-side network and they can also access to the web server? Please describe your solution and if you make configuration changes, please take screenshots and explain your solution.</p><h4>Your Submission:</h4>';
            report[7]=report[7]+' 2.5a:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow Client to access the ftp server on Server </p><h4>Your Submission:</h4>';
            report[8]=report[8]+' 2.5b:</h3><p> Take screenshot(s) on your Client to show that how to access the ftp service from the client and download the demo file successfully.</p><h4>Your Submission:</h4>';
            report[9]=report[9]+' 2.6:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow Server to ping Client and GW.</p><h4>Your Submission:</h4>';

        }else if(taskid=='103'){
            var subtaskarray = [1, 2, 3];
            var message ='<h3>Task 3 Grade: <a id="reportgrade" style="color: red"></a> out of 50</h3><h4>Feedback: <a id="reportfeedback" style="color: red"></a></h4></br>';
            var report=[];
            subtaskarray.forEach(function(subtaskid){
                report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
            });
            report[1]=report[1]+' 3.3:</h3><p> Provide screenshots to illustrate your scanning results for each type/option and provide output to show the evidence that you firewall was established properly based on the rule setup requirements from Task 2. If your results demonstrate unnecessary open ports/services, then you will get point deductions.</p><h4>Your Submission:</h4>'
            report[2]=report[2]+' 3.4:</h3><p> Submit your script/program as a file with good marked comments/illustrations in the script/program to explain function modules.</p><h4>Your Submission:</h4>'
            report[3]=report[3]+' 3.5:</h3><p> Submit a readme.txt file to illustrate required running environment and how to use the script. </p><h4>Your Submission:</h4>'
        }


        $.getJSON("/tasksubmission", {

            "taskid": taskid


        }, function (data) {


            for (var i = 0; i < data.length; i++) {
                if (data[i].desc == '' || data[i].desc == null) {
                    data[i].desc = 'N/A';
                }
                if(data[i].type==1) {
                    var taskcontent = '<div class="subtasksubmission">' +
                        '<p>Subject: <font color="red">' + data[i].title.substring(0, 50) + '</font></p>Screenshot:<br/><img src="' + data[i].submission + '" style="max-width: 95%" /></p>Descripiton: <font color="red">' + data[i].desc +
                        '</font><br/>' +
                        // '<input type="text" id="screenshottitle" maxlength="10"/><br/>
                        // 'Feedback:<textarea spellcheck="true" style="max-width: 100%;width : 100%" id="gradingfeedback" cols="50" rows="2"></textarea>' +
                        '</p></div>';
                    report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;

                }else if(data[i].type==2){
                    var fullPath = data[i].submission;
                    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                    var filename = fullPath.substring(startIndex);
                    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                        filename = filename.substring(1);
                    }
                    var taskcontent = '<div class="subtasksubmission">' +
                        '<p>Filename: <font color="red">' + filename + '</font></p>' +
                        '<button class="btn btn-default" title="Download File" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].submission+'\',\'_blank\')"><i class="fa fa-download"></i>Download File</button>' +

                        '<p>Descripiton: <font color="blue">' + data[i].desc +
                        '</font><br/>' +
                        // 'Feedback:<textarea spellcheck="true" style="max-width: 100%;width : 100%" id="gradingfeedback" cols="50" rows="2"></textarea>' +
                        '</p></div>';
                    report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;
                }
                // var taskcontent = '<div class="subtasksubmission">' +
                //     '<p>Subject: <font color="blue">' + data[i].title.substring(0, 50) + '</font></p>Screenshot:<br/><img src="' + data[i].submission + '" style="max-width: 95%" /></p>Descripiton: <font color="blue">' + data[i].desc +
                //     '</font><br/>' +
                //     // '<input type="text" id="screenshottitle" maxlength="10"/><br/>
                //
                //     '</p></div>';
                // report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;

            }
            subtaskarray.forEach(function (subtaskid) {
                report[subtaskid] = report[subtaskid] + '</div>';
                message = message + report[subtaskid];
            });
            $('#tobegrading').html(message).css('height', '750px').css('overflow-y', 'auto');

            $('#submissionbox').show();
            $('.content-wrapper').height(1200);
            $.getJSON("/getgradenouserid", {

                "taskid": taskid


            }, function (data) {
                if (jQuery.isEmptyObject(data)) {
                    $('#reportfeedback').html('Task Not Submitted!');

                    $('#reportgrade').html('N/A');
                } else {
                    if (data.feedback == '' || data.feedback == null) {
                        data.feedback = 'N/A';
                    }
                    $('#reportfeedback').html(data.feedback);

                    $('#reportgrade').html(data.grade);
                }

            });


        });

    }else if(labid==613){
        alert('Grade not available!')
    }

}