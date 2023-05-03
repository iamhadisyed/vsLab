function grouptakescreenshot(taskid, labid, subtaskid,vm_name,subgroupid) {
//function takescreenshot() {
    //var taskid = 1, labid=101, subtaskid=1, vm_name='Server';


    if($('#lab-environment').find('li.active').children().html()!=='Topology'){
    var vm_uuid=$('#lab-environment').find('li.active').children().attr('href').substring(9);
    var vm_name =  $('#lab-environment').find('li.active').children().html();
    var iframe = $('#vm_console_' + vm_uuid);
    var canvas = iframe.contents().find('canvas');
    var dataURL = canvas.get(0).toDataURL();

        if (iframe.contents().get(0).readyState !== 'complete')
        {
            Swal.fire('Screenshot failed!!', 'error');
        } else {
            var image = '<div style="width: 100%;"><img src="' + dataURL + '" width="100%" /></div>';
            var ask = 'Submit the screenshot?';
            var message = '<p>' + image
                +'<p>Screenshot Subject (50 Characters Max): <br/><input type="text" id="screenshottitle"  spellcheck="true" maxlength="50" style="width: 100%"  required /></p>'
                + '<p>Please also describe your screenshot, what does it show?</br><textarea spellcheck="true" style="max-width: 100%;width: 100%"  id="screenshotdesc" cols="50" rows="2"></textarea></p></p>';

            create_ConfirmDialogwithCheck('.container-fluid', ask, message, '40%', '.container-fluid',
                function () {
                    var title = $('#screenshottitle').val();

                    var desc = $('#screenshotdesc').val();

                    // var tooltip = '<div class="tooltip_content"><img src="' + dataURL + '" height="100px" />'
                    // +'<p>Subject:' + title.substring(0,50) + '</p>'
                    //     + '<button class="btn btn-warning" title="edit screenshot" onclick="lab_content_screenshot_tooltip_editimage('+taskid+','+labid+','+subtaskid+','+imagecount+')"><i class="fa fa-edit"></i></button>'
                    //     + '<button class="btn btn-danger" title="delete screenshot" onclick="lab_content_screenshot_tooltip_deleteimage('+taskid+','+labid+','+subtaskid+','+imagecount+')"><i class="fa fa-trash"></i></button></div>';

                    var btn_tooltip = $('<button class="btn-image-icon"></button>').css('margin-left', '5pt').css('margin-top','3px').appendTo($('#submission-task'+taskid+'-'+subtaskid));
                    $('<i class="fa fa-image"></i>').appendTo(btn_tooltip);

                    // $(btn_tooltip).insertAfter("#sub_"+taskid+"_"+subtaskid);

                    $('.btn-image-icon').on('click', function(event) {
                        event.preventDefault();
                    });


                    $.post("/groupsubmit", {

                            "taskid": taskid,
                            "labid": labid,
                            "subtaskid": subtaskid,
                            "title": title,
                            "answer": dataURL,
                            "desc":desc,
                            "type":1,
                            "source":vm_name,
                            "subgroupid":subgroupid

                        },
                        function (data) {
                            var tooltip = '<div class="tooltip_content"><img src="' + dataURL + '" height="100px" />'
                                +'<p>Subject:' + title.substring(0,50) + '</p>'
                                + '<button class="btn btn-warning btn-image-tooltip btn-task-'+taskid+'" title="edit screenshot" onclick="lab_content_screenshot_tooltip_editimage('+taskid+','+labid+','+subtaskid+','+data.id+')"><i class="fa fa-edit"></i></button>'
                                + '<button class="btn btn-danger btn-image-tooltip btn-task-'+taskid+'" title="delete screenshot" onclick="lab_content_screenshot_tooltip_deleteimage('+taskid+','+labid+','+subtaskid+','+data.id+')"><i class="fa fa-trash"></i></button></div>';
                            btn_tooltip.addClass('btn btn-default btn-task-'+taskid).attr('title', tooltip).attr("id", "sub_"+taskid+"_"+subtaskid+"_"+data.id);
                            $("#sub_"+taskid+"_"+subtaskid+"_"+data.id).tooltipster(
                                {trigger: 'custom',
                                    triggerOpen: {
                                        mouseenter: true,
                                        touchstart: true,
                                        click: true,
                                        tap: true
                                    },
                                    triggerClose: {
                                        mouseleave: true,
                                        originClick: true,
                                        touchleave: true,
                                        click: true,
                                        tap: true
                                    },
                                    contentAsHTML: true,
                                    interactive: true,
                                    content: tooltip
                                });
                        },
                        'json'
                    );

                },
                function () {
                    //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                });
            $('#dialog_confirm').css('height','95%').css('max-width','100%').css('width','100%').parent().css('max-height','80%');
            $('.ui-dialog-buttonset').first().children().first().html('Save');
        }
    } else {
        Swal.fire('Please switch to the VM console you want to take screenshot first!!', 'warning');
    }
}

function openfeedbackfrom(labcontentid, subgroupid, labid) {
//function takescreenshot() {
    //var taskid = 1, labid=101, subtaskid=1, vm_name='Server';


    if($('#lab-environment').find('li.active').children().html()!=='Topology'){
        var vm_uuid=$('#lab-environment').find('li.active').children().attr('href').substring(9);
        var vm_name =  $('#lab-environment').find('li.active').children().html();
        var iframe = $('#vm_console_' + vm_uuid);
        var canvas = iframe.contents().find('canvas');
        var dataURL = canvas.get(0).toDataURL();

        if (iframe.contents().get(0).readyState !== 'complete')
        {
            Swal.fire('Screenshot failed!!', 'error');
        } else {
            var image = '<div style="width: 100%;"><img src="' + dataURL + '" width="100%" /></div>';
            var ask = 'Ask Quesiton or Provide feedback:';
            var message = '<p>' + image
                +'<p> Subject (50 Characters Max): <br/><input type="text" id="feedbacksubject"  spellcheck="true" maxlength="50" style="width: 100%"  required /></p>'
                + '<p>Please describe your quesiton or feedback:</br><textarea spellcheck="true" style="max-width: 100%;width: 100%"  id="feedbackdesc" cols="50" rows="2"></textarea></p></p>';

            create_ConfirmDialogwithCheck('.container-fluid', ask, message, '35%', '.container-fluid',
                function () {
                    var title = $('#feedbacksubject').val();

                    var desc = $('#feedbackdesc').val();

                    // var tooltip = '<div class="tooltip_content"><img src="' + dataURL + '" height="100px" />'
                    // +'<p>Subject:' + title.substring(0,50) + '</p>'
                    //     + '<button class="btn btn-warning" title="edit screenshot" onclick="lab_content_screenshot_tooltip_editimage('+taskid+','+labid+','+subtaskid+','+imagecount+')"><i class="fa fa-edit"></i></button>'
                    //     + '<button class="btn btn-danger" title="delete screenshot" onclick="lab_content_screenshot_tooltip_deleteimage('+taskid+','+labid+','+subtaskid+','+imagecount+')"><i class="fa fa-trash"></i></button></div>';

                    $('.btn-image-icon').on('click', function(event) {
                        event.preventDefault();
                    });


                    $.post("/feedbacksubmit", {

                            "labid": labid,
                            "title": title,
                            "answer": dataURL,
                            "desc":desc,
                            "type":4,
                            "labcontentid":labcontentid,
                            "subgroupid":subgroupid

                        },
                        function (data) {
                            if (data.status == "Success") {
                                Swal.fire('Thank you! We\'ll get back to you ASAP!', '', 'success');

                            }else{
                                Swal.fire(data.msg, '', 'warning');

                            }
                        },
                        'json'
                    );

                },
                function () {
                    //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                });
            $('#dialog_confirm').css('height','95%').css('max-width','100%').css('width','100%').parent().css('max-height','80%');
            $('.ui-dialog-buttonset').first().children().first().html('Submit');
        }
    } else {
        var ask = 'Ask Quesiton or Provide feedback:';
        var message = '<p>'
            +'<p> Subject (50 Characters Max): <br/><input type="text" id="feedbacksubject"  spellcheck="true" maxlength="50" style="width: 100%"  required /></p>'
            + '<p>Please describe your quesiton or feedback:</br><textarea spellcheck="true" style="max-width: 100%;width: 100%"  id="feedbackdesc" cols="50" rows="2"></textarea></p></p>';

        create_ConfirmDialogwithCheck('.container-fluid', ask, message, '40%', '.container-fluid',
            function () {
                var title = $('#feedbacksubject').val();

                var desc = $('#feedbackdesc').val();

                // var tooltip = '<div class="tooltip_content"><img src="' + dataURL + '" height="100px" />'
                // +'<p>Subject:' + title.substring(0,50) + '</p>'
                //     + '<button class="btn btn-warning" title="edit screenshot" onclick="lab_content_screenshot_tooltip_editimage('+taskid+','+labid+','+subtaskid+','+imagecount+')"><i class="fa fa-edit"></i></button>'
                //     + '<button class="btn btn-danger" title="delete screenshot" onclick="lab_content_screenshot_tooltip_deleteimage('+taskid+','+labid+','+subtaskid+','+imagecount+')"><i class="fa fa-trash"></i></button></div>';

                $('.btn-image-icon').on('click', function(event) {
                    event.preventDefault();
                });


                $.post("/feedbacksubmit", {

                        "labid": labid,
                        "title": title,
                        "answer": '',
                        "desc":desc,
                        "type":4,
                        "labcontentid":labcontentid,
                        "subgroupid":subgroupid

                    },
                    function (data) {
                        if (data.status == "Success") {
                            Swal.fire('Thank you! We\'ll get back to you ASAP!', '', 'success');

                        }else{
                            Swal.fire(data.msg, '', 'warning');

                        }
                    },
                    'json'
                );

            },
            function () {
                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
            });
        $('#dialog_confirm').css('height','95%').css('max-width','100%').css('width','100%').parent().css('max-height','80%');
        $('.ui-dialog-buttonset').first().children().first().html('Submit');
    }
}

function groupaddfile(taskid, labid, subtaskid,subgroupid) {

            var ask = 'Upload File';
            var message = '<p><form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action=""><p><input id="file" name="file"  type="file" style="height: auto"><font color="red">File Size Under 10MB</font> </p>' +
                '<input type="hidden" name="taskid" value="'+taskid+'">'+
                '<input type="hidden" name="labid" value="'+labid+'">'+
                '<input type="hidden" name="subtaskid" value="'+subtaskid+'">' +
                '<input type="hidden" name="subgroupid" value="'+subgroupid+'"></form>'+
                '<p>Please also describe the file you want to upload</br><textarea form="upload_form" name="desc" spellcheck="true" style="max-width: 100%;width: 100%"  id="filedesc" cols="50" rows="2"></textarea></p></p>';

            create_ConfirmDialogwithCheck('.container-fluid', ask, message, '40%', '.container-fluid',
                function () {



                    var btn_tooltip = $('<button class="btn-image-icon"></button>').css('margin-left', '5pt').css('margin-top','3px').appendTo($('#submission-task'+taskid+'-'+subtaskid));
                    $('<i class=" fa fa-spinner fa-pulse fileuploading"></i>').appendTo(btn_tooltip);
                    btn_tooltip.attr('title','Uploading');

                    // $(btn_tooltip).insertAfter("#sub_"+taskid+"_"+subtaskid);

                    $('.btn-image-icon').on('click', function(event) {
                        event.preventDefault();
                    });


                    $.ajax({
                        type: 'POST',
                        url: '/groupuploadfile',
                        data: new FormData($("#upload_form")[0]),
                        processData: false,
                        contentType: false,
                        success:function (data) {
                            var fullPath = data.url;
                            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                            var filename = fullPath.substring(startIndex);
                            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                                filename = filename.substring(1);
                            }

                            var tooltip = '<div class="tooltip_content">'
                                +'<p>File Name: ' + filename + '</p>'
                                + '<button class="btn btn-warning btn-file-tooltip btn-task-'+taskid+'" title="download file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data.url+'\',\'_blank\')"><i class="fa fa-download"></i></button>'
                                + '<button class="btn btn-danger btn-file-tooltip btn-task-'+taskid+'" title="delete file" onclick="lab_content_screenshot_tooltip_deletefile('+taskid+','+labid+','+subtaskid+','+data.id+')"><i class="fa fa-trash"></i></button></div>';
                            btn_tooltip.addClass('btn btn-default btn-task-'+taskid).attr('title', tooltip).attr("id", "sub_"+taskid+"_"+subtaskid+"_"+data.id);
                            $("#sub_"+taskid+"_"+subtaskid+"_"+data.id).tooltipster(
                                {trigger: 'custom',
                                    triggerOpen: {
                                        mouseenter: true,
                                        touchstart: true,
                                        click: true,
                                        tap: true
                                    },
                                    triggerClose: {
                                        mouseleave: true,
                                        originClick: true,
                                        touchleave: true,
                                        click: true,
                                        tap: true
                                    },
                                    contentAsHTML: true,
                                    interactive: true,
                                    content: tooltip
                                });
                            $(".fileuploading").removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-file');

                        }
                    });



                },
                function () {
                    //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                });
            $('#dialog_confirm').css('height','95%').css('max-width','100%').css('width','100%').parent().css('max-height','80%');
            $('.ui-dialog-buttonset').first().children().first().html('Save');


}

function submitservey() {
    var q1=document.forms["survey_form"]["q1"].value;
    var q2=document.forms["survey_form"]["q2"].value;
    var q3=document.forms["survey_form"]["q3"].value;
    var q4=document.forms["survey_form"]["q4"].value;
    var q5=document.forms["survey_form"]["q5"].value;
    var q6=document.forms["survey_form"]["q6"].value;
    var q7=document.forms["survey_form"]["q7"].value;
    var q8=document.forms["survey_form"]["q8"].value;
    var q9=document.forms["survey_form"]["q9"].value;
    var q10=document.forms["survey_form"]["q10"].value;
    var q11=document.forms["survey_form"]["q11"].value;
    var q12=document.forms["survey_form"]["q12"].value;
    var q13=document.forms["survey_form"]["q13"].value;
    var q14=document.forms["survey_form"]["q14"].value;
    var q15=document.forms["survey_form"]["q15"].value;
    var q16=document.forms["survey_form"]["q16"].value;
    var q17=document.forms["survey_form"]["q17"].value;
    var q18=document.forms["survey_form"]["q18"].value;
    var q19=document.forms["survey_form"]["q19"].value;
    var q20=document.forms["survey_form"]["q20"].value;
    var q21=document.forms["survey_form"]["q21"].value;
    var q22=document.forms["survey_form"]["q22"].value;
    var q23=document.forms["survey_form"]["q23"].value;
    var q24=document.forms["survey_form"]["q24"].value;
    var q25=document.forms["survey_form"]["q25"].value;
    var q26=document.forms["survey_form"]["q26"].value;
    if(q1==""||q2==""||q3==""||q4==""||q5==""||q6==""||q7==""||q8==""||q9==""||q10==""||q11==""||q12==""||q13==""||q14==""||q15==""||q16==""||q17==""||q18==""||q19==""||q20==""||q21==""||q22==""||q23==""||q24==""||q25==""||q26==""){
        alert("Please finish all the questions first!");

    }else{
        $.ajax({
            type: 'POST',
            url: '/uploadsurvey',
            data: new FormData($("#survey_form")[0]),
            processData: false,
            contentType: false,
            success:function (data) {
                $('#survey-0').modal('hide');
                $('#finaltask.btn-task-112').prop('disabled', false);
                $("#finaltask.btn-task-112").attr('title', '');
                $('#startsurvey-110').prop('disabled', true);
                $("#startsurvey-110").attr('title', 'Survey Finished!');
            }
        });
    }

}

function test_btn_click(element) {
    var that = element;

}

// function create_ScreenshotDialog(div_id, title, ask, width, containment, okCallback, cancelCallback) {
//     var dlg_form = $(document.createElement('div')).appendTo($(div_id));
//     dlg_form.attr('id', 'dialog_confirm').attr('title', title).css('float', 'left');
//
//     //var form_html = '<p>' + ask + '</p>';
//     $(ask).css('margin-top', '25px').appendTo(dlg_form);
//     $('<form>Please also describe your screenshot, what does it show?</br><textarea id="screenshotdesc" name="screenshotdesc" cols="50" rows="2"></textarea><br></form>').css('margin-top', '25px').appendTo(dlg_form);
//     $('#dialog_confirm').dialog({
//         resizable: false,
//         position: {my: "center", at: "center", of: containment},
//         modal: true,
//         width: width,
//         buttons: {
//             "Save": function () {
//
//
//                 okCallback();
//                 $(this).dialog('close');
//             },
//             "Cancel": function () {
//                 $(this).dialog('close');
//                 cancelCallback();
//             }
//         },
//         close: function (event, ui) {
//             $(this).remove();
//         }
//     });
//     $('.ui-widget-content').css('background-color', 'beige');
//     $('.ui-dialog-titlebar').css('height', '35px');
//     $('.ui-dialog-titlebar-close').css('display', 'none');
// }

function groupopensubmission(taskid,userid,subgroupid){


    if($("#startsubmission_"+taskid).html()!='<i class="fa fa-minus"></i> Hide'){
        $("#submission_"+taskid).show();
        $("#startsubmission_"+taskid).html('<i class="fa fa-minus"></i> Hide');
        if (taskid===101){
            var subtaskarray = [1, 2, 3, 4, 5];
        }else if(taskid===102){
            var subtaskarray = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        }
        else if(taskid===103){
            var subtaskarray = [1, 2, 3];
        }
        else if(taskid===111){
            var subtaskarray = [1, 2, 3, 4];
        }
        else if(taskid===112){
            var subtaskarray = [1, 2, 3];
            $.getJSON( "/checktaskfinished",{

                "taskid": 110

            }, function( data ) {
                if(data[0].finished){
                    $('#finaltask.btn-task-112').prop('disabled', false);
                    $("#finaltask.btn-task-112").attr('title', '');
                    $('#startsurvey-110').prop('disabled', true);
                    $("#startsurvey-110").attr('title', 'Survey Finished!');
                }
            });
        }
        else{
            var subtaskarray = [1, 2, 3];
        }
        subtaskarray.forEach(function(subtaskid) {
            $('#submission-task' + taskid + '-' + subtaskid).append('<i class="fa fa-refresh fa-spin waiting-image-icon"></i>')
        });

        $.getJSON( "/checktaskfinished",{

            "taskid": taskid

        }, function( data ) {
            var now = new Date().getTime();
            var countDownDate = new Date("Sep 30, 2018 23:59:59 GMT-07:00 ").getTime();
            var countDownDate2 = new Date("Oct 13, 2018 23:59:59 GMT-07:00 ").getTime();
            var countDownDate3 = new Date("Oct 30, 2018 23:59:59 GMT-07:00 ").getTime();
            var countDownDate4 = new Date("Dec 2, 2018 23:59:59 GMT-07:00 ").getTime();
            var countDownDate5 = new Date("Dec 2, 2018 23:59:59 GMT-07:00 ").getTime();
            // Find the distance between now and the count down date
            var distance=[];
            distance[101] = countDownDate - now;
            distance[102] = countDownDate2 - now;
            distance[103] = countDownDate3 - now;
            distance[111] = countDownDate4 - now;
            distance[112] = countDownDate5 - now;
            if(data[0].finished){
                $(".btn-add-screenshot.btn-task-" + taskid).remove();
                $(".btn-add-file.btn-task-" + taskid).remove();
                $(".btn-upload-text.btn-task-" + taskid).remove();
                $("#viewreport.btn-task-" + taskid).html('<i class="fa fa-eye"></i>View Report');
                $("#finaltask.btn-task-" + taskid).prop('disabled', true);
                $("#finaltask.btn-task-" + taskid).html('Task Completed');
                $(".countdownall"+taskid).empty();
                $.getJSON("/tasksubmissionbysubgroup", {

                    "taskid": taskid,
                    "subgroupid":subgroupid

                }, function (data) {
                    for (var i = 0; i < data.length; i++) {


                        if (data[i].type == 1) {
                            var btn_tooltip = $('<button></button>').css('margin-left', '5pt').appendTo($('#submission-task' + taskid + '-' + data[i].subtask_id));
                            $('<i class="fa fa-image"></i>').appendTo(btn_tooltip);

                            var tooltip = '<div class="tooltip_content"><img src="' + data[i].submission + '" height="100px" />'
                                + '<p>Subject:' + data[i].title.substring(0, 50) + '</p><p>Submited by : '+data[i].username+'</p>';
                            btn_tooltip.addClass('btn btn-default btn-image-icon btn-task-' + taskid).attr('title', tooltip).attr("id", "sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).css('margin-top', '3px');

                            $("#sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).tooltipster(
                                {
                                    trigger: 'custom',
                                    triggerOpen: {
                                        mouseenter: true,
                                        touchstart: true,
                                        click: true,
                                        tap: true
                                    },
                                    triggerClose: {
                                        mouseleave: true,
                                        originClick: true,
                                        touchleave: true,
                                        click: true,
                                        tap: true
                                    },
                                    contentAsHTML: true,
                                    interactive: true,
                                    content: tooltip
                                });

                            $('.btn-image-icon').on('click', function (event) {
                                event.preventDefault();
                            });
                        }else if(data[i].type == 2){
                            var btn_tooltip = $('<button></button>').css('margin-left', '5pt').appendTo($('#submission-task' + taskid + '-' + data[i].subtask_id));
                            $('<i class="fa fa-file"></i>').appendTo(btn_tooltip);
                            var fullPath = data[i].submission;
                            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                            var filename = fullPath.substring(startIndex);
                            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                                filename = filename.substring(1);
                            }
                            var tooltip = '<div class="tooltip_content">'
                                + '<p>Filename:' + filename + '</p><p>Submited by : '+data[i].username+'</p>'
                                + '<button class="btn btn-warning btn-file-tooltip btn-task-'+taskid+'" title="download file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].submission+'\',\'_blank\')"><i class="fa fa-download"></i></button>';
                            btn_tooltip.addClass('btn btn-default btn-image-icon btn-task-' + taskid).attr('title', tooltip).attr("id", "sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).css('margin-top', '3px');

                            $("#sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).tooltipster(
                                {
                                    trigger: 'custom',
                                    triggerOpen: {
                                        mouseenter: true,
                                        touchstart: true,
                                        click: true,
                                        tap: true
                                    },
                                    triggerClose: {
                                        mouseleave: true,
                                        originClick: true,
                                        touchleave: true,
                                        click: true,
                                        tap: true
                                    },
                                    contentAsHTML: true,
                                    interactive: true,
                                    content: tooltip
                                });

                            $('.btn-image-icon').on('click', function (event) {
                                event.preventDefault();
                            });
                        }else if(data[i].type == 3){
                            CKEDITOR.instances['submission-task-'+taskid+'-'+subgroupid+'-3'].setData(data[i].desc);
                        }
                    }
                    $('.waiting-image-icon').remove();
                });
            }else if(distance[taskid]<0){
                $(".btn-add-screenshot.btn-task-" + taskid).remove();
                $(".btn-add-file.btn-task-" + taskid).remove();
                $("#viewreport.btn-task-" + taskid).html('<i class="fa fa-eye"></i>View Report');
                $("#finaltask.btn-task-" + taskid).prop('disabled', true);
                $("#finaltask.btn-task-" + taskid).html('Task Expired');
                $.getJSON("/tasksubmissionbysubgroup", {

                    "taskid": taskid,
                    "subgroupid":subgroupid

                }, function (data) {
                    for (var i = 0; i < data.length; i++) {


                        if (data[i].type == 1) {
                            var btn_tooltip = $('<button></button>').css('margin-left', '5pt').appendTo($('#submission-task' + taskid + '-' + data[i].subtask_id));
                            $('<i class="fa fa-image"></i>').appendTo(btn_tooltip);

                            var tooltip = '<div class="tooltip_content"><img src="' + data[i].submission + '" height="100px" />'
                                + '<p>Subject:' + data[i].title.substring(0, 50) + '</p><p>Submited by : '+data[i].username+'</p>';
                            btn_tooltip.addClass('btn btn-default btn-image-icon btn-task-' + taskid).attr('title', tooltip).attr("id", "sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).css('margin-top', '3px');

                            $("#sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).tooltipster(
                                {
                                    trigger: 'custom',
                                    triggerOpen: {
                                        mouseenter: true,
                                        touchstart: true,
                                        click: true,
                                        tap: true
                                    },
                                    triggerClose: {
                                        mouseleave: true,
                                        originClick: true,
                                        touchleave: true,
                                        click: true,
                                        tap: true
                                    },
                                    contentAsHTML: true,
                                    interactive: true,
                                    content: tooltip
                                });

                            $('.btn-image-icon').on('click', function (event) {
                                event.preventDefault();
                            });
                        }else if(data[i].type == 2){
                            var btn_tooltip = $('<button></button>').css('margin-left', '5pt').appendTo($('#submission-task' + taskid + '-' + data[i].subtask_id));
                            $('<i class="fa fa-file"></i>').appendTo(btn_tooltip);
                            var fullPath = data[i].submission;
                            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                            var filename = fullPath.substring(startIndex);
                            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                                filename = filename.substring(1);
                            }
                            var tooltip = '<div class="tooltip_content">'
                                + '<p>Filename:' + filename + '</p><p>Submited by : '+data[i].username+'</p>'
                                +'<button class="btn btn-default" title="View File" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].submission+'\',\'_blank\')"><i class="fa fa-download"></i>View File</button>';
                            btn_tooltip.addClass('btn btn-default btn-image-icon btn-task-' + taskid).attr('title', tooltip).attr("id", "sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).css('margin-top', '3px');

                            $("#sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).tooltipster(
                                {
                                    trigger: 'custom',
                                    triggerOpen: {
                                        mouseenter: true,
                                        touchstart: true,
                                        click: true,
                                        tap: true
                                    },
                                    triggerClose: {
                                        mouseleave: true,
                                        originClick: true,
                                        touchleave: true,
                                        click: true,
                                        tap: true
                                    },
                                    contentAsHTML: true,
                                    interactive: true,
                                    content: tooltip
                                });

                            $('.btn-image-icon').on('click', function (event) {
                                event.preventDefault();
                            });
                        }else if(data[i].type == 3){
                            CKEDITOR.instances['submission-task-'+taskid+'-'+subgroupid+'-3'].setData(data[i].desc);
                        }
                    }
                    $('.waiting-image-icon').remove();
                });

            }else{
                $.getJSON("/tasksubmissionbysubgroup", {

                    "taskid": taskid,
                    "subgroupid":subgroupid

                }, function( data ) {
                    for(var i=0;i<data.length;i++) {
                        if (data[i].type == 1) {
                            var btn_tooltip = $('<button></button>').css('margin-left', '5pt').css('margin-top','3px').appendTo($('#submission-task' + taskid + '-' + data[i].subtask_id));
                            $('<i class="fa fa-image"></i>').appendTo(btn_tooltip);

                            var tooltip = '<div class="tooltip_content"><img src="' + data[i].submission + '" height="100px" />'
                                + '<p>Subject:' + data[i].title.substring(0, 50) + '</p><p>Submited by : '+data[i].username+'</p>';
                            if(data[i].user_id==userid) {
                                tooltip= tooltip+'<button class="btn btn-warning btn-image-tooltip btn-task-' + taskid + '" title="edit screenshot" onclick="lab_content_screenshot_tooltip_editimage(' + taskid + ',' + data[i].lab_id + ',' + data[i].subtask_id + ',' + data[i].id + ')"><i class="fa fa-edit"></i></button>'
                                + '<button class="btn btn-danger btn-image-tooltip btn-task-' + taskid + '" title="delete screenshot" onclick="lab_content_screenshot_tooltip_deleteimage(' + taskid + ',' + data[i].lab_id + ',' + data[i].subtask_id + ',' + data[i].id + ')"><i class="fa fa-trash"></i></button></div>';
                            }
                            btn_tooltip.addClass('btn btn-default btn-image-icon btn-task-' + taskid).attr('title', tooltip).attr("id", "sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id);

                            $("#sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).tooltipster(
                                {
                                    trigger: 'custom',
                                    triggerOpen: {
                                        mouseenter: true,
                                        touchstart: true,
                                        click: true,
                                        tap: true
                                    },
                                    triggerClose: {
                                        mouseleave: true,
                                        originClick: true,
                                        touchleave: true,
                                        click: true,
                                        tap: true
                                    },
                                    contentAsHTML: true,
                                    interactive: true,
                                    content: tooltip
                                });

                            $('.btn-image-icon').on('click', function (event) {
                                event.preventDefault();
                            });
                        }else if (data[i].type == 2) {
                            var btn_tooltip = $('<button></button>').css('margin-left', '5pt').css('margin-top','3px').appendTo($('#submission-task' + taskid + '-' + data[i].subtask_id));
                            $('<i class="fa fa-file"></i>').appendTo(btn_tooltip);
                            var fullPath = data[i].submission;
                            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                            var filename = fullPath.substring(startIndex);
                            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                                filename = filename.substring(1);
                            }
                            var tooltip = '<div class="tooltip_content">'
                                + '<p>Filename:' + filename + '</p><p>Submited by : '+data[i].username+'</p>'
                            +'<p>Descripiton: ' + data[i].desc+'</p>';
                            if(data[i].user_id==userid) {
                                tooltip= tooltip+
                                    // '<button class="btn btn-warning btn-image-tooltip btn-task-' + taskid + '" title="Edit File" onclick="lab_content_screenshot_tooltip_editimage(' + taskid + ',' + data[i].lab_id + ',' + data[i].subtask_id + ',' + data[i].id + ')"><i class="fa fa-edit"></i></button>'
                                 '<button class="btn btn-warning" title="Download File" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].submission+'\',\'_blank\')"><i class="fa fa-download"></i></button>'
                                    + '<button class="btn btn-danger btn-image-tooltip btn-task-' + taskid + '" title="Delete File" onclick="lab_content_screenshot_tooltip_deletefile(' + taskid + ',' + data[i].lab_id + ',' + data[i].subtask_id + ',' + data[i].id + ')"><i class="fa fa-trash"></i></button></div>';
                            }
                            btn_tooltip.addClass('btn btn-default btn-image-icon btn-task-' + taskid).attr('title', tooltip).attr("id", "sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id);

                            $("#sub_" + taskid + "_" + data[i].subtask_id + "_" + data[i].id).tooltipster(
                                {
                                    trigger: 'custom',
                                    triggerOpen: {
                                        mouseenter: true,
                                        touchstart: true,
                                        click: true,
                                        tap: true
                                    },
                                    triggerClose: {
                                        mouseleave: true,
                                        originClick: true,
                                        touchleave: true,
                                        click: true,
                                        tap: true
                                    },
                                    contentAsHTML: true,
                                    interactive: true,
                                    content: tooltip
                                });

                            $('.btn-image-icon').on('click', function (event) {
                                event.preventDefault();
                            });
                        }else if(data[i].type == 3){
                            CKEDITOR.instances['submission-task-'+taskid+'-'+subgroupid+'-3'].setData(data[i].desc);
                        }
                    }
                    $('.waiting-image-icon').remove();
                });
            }
            //$('.waiting-image-icon').remove();
        });



    }else{
        $("#submission_"+taskid).hide();
        $(".btn-image-icon.btn-task-"+taskid).remove();
        $("#startsubmission_"+taskid).html('<i class="fa fa-plus"></i>Open Task Submission');
    }
}

function lab_content_screenshot_tooltip_deleteimage(taskid, labid, subtaskid,imageid) {

    var message = '<p>Are you sure you want to delete this screenshot?</p>';
    var ask = 'Delete the screenshot?';

    create_ConfirmDialog('.container-fluid', ask, message, '40%', '.container-fluid',
        function () {

            $("#sub_"+taskid+"_"+subtaskid+"_"+imageid).remove();
            $.post("/deletesubmission", {

                    "submissionid": imageid

                },
                function (data) {

                },
                'json'
            );
        },
        function () {
            //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
        });
}

function lab_content_screenshot_tooltip_deletefile(taskid, labid, subtaskid,imageid) {

    var message = '<p>Are you sure you want to delete this file?</p>';
    var ask = 'Delete the uploaded file?';

    create_ConfirmDialog('.container-fluid', ask, message, '40%', '.container-fluid',
        function () {

            $("#sub_"+taskid+"_"+subtaskid+"_"+imageid).remove();
            $.post("/deletesubmission", {

                    "submissionid": imageid

                },
                function (data) {

                },
                'json'
            );
        },
        function () {
            //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
        });
}



function lab_content_screenshot_tooltip_editimage(taskid, labid, subtaskid,imageid){

    $.get( "/submit/"+imageid, function( data ) {
        var dataURL =data.dataURL;
        var retake_btn='<botton class="btn btn-warning" title="retake screenshot" onclick="retakescreenshot()">Re-take Screenshot</botton>';
        var image = '<div class="edit_image_preview" style="width: 100%;"><img src="' + dataURL + '"  width="100%" /></div>';
        var ask = 'Update the screenshot?';
        var message = '<p>' + image + retake_btn
            +'<p>Screenshot Subject (50 Characters Max):<br/><input spellcheck="true" type="text" id="screenshottitle" maxlength="50" style="width: 100%" value="'+ data.title+'"  /></p>'
            + '<p>Description</br><textarea spellcheck="true" style="max-width: 100%;width : 100%" id="screenshotdesc" cols="50" rows="2">'+data.desc+'</textarea></p></p>';

        create_ConfirmDialogwithCheck('.container-fluid', ask, message, '40%', '.container-fluid',
            function () {
                $("#sub_"+taskid+"_"+subtaskid+"_"+imageid).tooltipster('content','');
                $("#sub_"+taskid+"_"+subtaskid+"_"+imageid).first().children().first().removeClass('fa-image').addClass('fa-refresh').addClass('fa-spin');

                var title = $('#screenshottitle').val();

                var desc = $('#screenshotdesc').val();
                var answer = document.getElementsByClassName("edit_image_preview")[0].children[0].src;
                var attr = $('.edit_image_preview').attr('source');


                if (typeof attr !== typeof undefined && attr !== false) {
                    $.post("/updatesubmission", {
                            "submissionid":imageid,
                            "title": title,
                            "answer": answer,
                            "desc":desc,
                            "source":attr
                        },
                        function (data1) {

                            var tooltip = '<div class="tooltip_content"><img src="' + answer + '" height="100px" />'
                                +'<p>Subject:' + title.substring(0,50) + '</p>'
                                + '<button class="btn btn-warning btn-image-tooltip btn-task-'+taskid+'" title="edit screenshot" onclick="lab_content_screenshot_tooltip_editimage('+taskid+','+labid+','+subtaskid+','+imageid+')"><i class="fa fa-edit"></i></button>'
                                + '<button class="btn btn-danger btn-image-tooltip btn-task-'+taskid+'" title="delete screenshot" onclick="lab_content_screenshot_tooltip_deleteimage('+taskid+','+labid+','+subtaskid+','+imageid+')"><i class="fa fa-trash"></i></button></div>';
                            //$("#sub_"+taskid+"_"+subtaskid+"_"+imageid).attr('title', tooltip);
                            $("#sub_"+taskid+"_"+subtaskid+"_"+imageid).tooltipster('content',tooltip);
                            $("#sub_"+taskid+"_"+subtaskid+"_"+imageid).first().children().first().removeClass('fa-refresh').removeClass('fa-spin').addClass('fa-image');
                        },
                        'json'
                    );
                }else{
                    $.post("/updatesubmission", {
                            "submissionid":imageid,
                            "title": title,
                            "answer": answer,
                            "desc":desc
                        },
                        function (data1) {

                            var tooltip = '<div class="tooltip_content"><img src="' + answer + '" height="100px" />'
                                +'<p>Subject:' + title.substring(0,50) + '</p>'
                                + '<button class="btn btn-warning btn-image-tooltip btn-task-'+taskid+'" title="edit screenshot" onclick="lab_content_screenshot_tooltip_editimage('+taskid+','+labid+','+subtaskid+','+imageid+')"><i class="fa fa-edit"></i></button>'
                                + '<button class="btn btn-danger btn-image-tooltip btn-task-'+taskid+'" title="delete screenshot" onclick="lab_content_screenshot_tooltip_deleteimage('+taskid+','+labid+','+subtaskid+','+imageid+')"><i class="fa fa-trash"></i></button></div>';
                            //$("#sub_"+taskid+"_"+subtaskid+"_"+imageid).attr('title', tooltip);
                            $("#sub_"+taskid+"_"+subtaskid+"_"+imageid).tooltipster('content',tooltip);
                            $("#sub_"+taskid+"_"+subtaskid+"_"+imageid).first().children().first().removeClass('fa-refresh').removeClass('fa-spin').addClass('fa-image');
                        },
                        'json'
                    );
                }

            },
            function () {
                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
            });
        $('#dialog_confirm').css('height','95%').css('max-width','100%').css('width','100%').parent().css('max-height','80%');
        $('.ui-dialog-buttonset').first().children().first().html('Save');
    });
}

function retakescreenshot() {

    if($('#lab-environment').find('li.active').children().html()!=='Topology') {
        var vm_uuid = $('#lab-environment').find('li.active').children().attr('href').substring(9);
        var vm_name =  $('#lab-environment').find('li.active').children().html();
        var iframe = $('#vm_console_' + vm_uuid);
        var canvas = iframe.contents().find('canvas');
        var dataURL = canvas.get(0).toDataURL();
        if (dataURL === undefined) {
            Swal.fire('Screenshot failed!!', 'error');
        } else {
            $('.edit_image_preview').html('<img src="' + dataURL + '"  width="100%" />').attr('source',vm_name);
        }
    }else{
        Swal.fire('Please switch to the VM console you want to take screenshot first!!', 'warning');
    }
}

function active_bar_step(element){
    //$('.bs-wizard-step').removeClass('active');
    //$(element).parent().addClass('active');
}

function finish_task(taskid) {
    // if (taskid===101){
    //     var subtaskarray = [1, 2, 3, 4, 5];
    // }else if(taskid===102){
    //     var subtaskarray = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    // }else if(taskid===103){
    //     var subtaskarray = [1, 2, 3];
    // }else if(taskid===111) {
    //     var subtaskarray = [1, 2, 3, 4];
    // }else if(taskid===112) {
    //     var subtaskarray = [1, 2, 3];
    // }

        var finishflag=1;
    // for (var i = 0; i < subtaskarray.length; i++) {
    //     if ($('#submission-task' + taskid + '-' + subtaskarray[i]).children().length) {
    //         finishflag = 1;
    //     } else {
    //
    //         finishflag = 0;
    //         var subtasknum = i + 1;
    //         var alertmessage = 'Submission ' + subtasknum + ' is empty, please finish it before submission!';
    //         Swal.fire(alertmessage, 'warning');
    //         break;
    //     }
    // }
    if (finishflag === 1) {
        var message = '<p>Are you sure you want to finish this task?<br /> ' +
            'All submission will be final and you will not be able to edit them anymore.</p>';
        var ask = 'Finish the Task?';

        create_ConfirmDialog('.container-fluid', ask, message, '30%', '.container-fluid',
            function () {
                $(".btn-add-screenshot.btn-task-" + taskid).remove();
                $(".btn-add-file.btn-task-" + taskid).remove();
                $(".btn-task-" + taskid + ".btn-image-icon").remove();
                $("#viewreport.btn-task-" + taskid).html('<i class="fa fa-eye"></i>View Report');
                $("#finaltask.btn-task-" + taskid).prop('disabled', true);
                $("#finaltask.btn-task-" + taskid).html('Task Completed');
                $("#bs-wizard-step-"+taskid).addClass('complete').children().attr('title','Finished');
                $(".countdownall"+taskid).empty();


                $.post("/finishtask", {

                        "taskid": taskid,


                    },
                    function (data) {
                    if (data.length> 10){
                        alert(data);
                    }
                        $("#submission_"+taskid).hide();
                        $("#startsubmission_"+taskid).html('<i class="fa fa-plus"></i>Open Task Submission');
                    },
                    'json'
                );
            },
            function () {
                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
            });
    }
}



    function view_groupreport(taskid,subgroupid) {


        if (taskid===101){
            var subtaskarray = [1, 2, 3, 4, 5];
            var message ='<h2>Below is your current submission for Task 1</h2>';
            var report=[];
            subtaskarray.forEach(function(subtaskid){
                report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
            });
            report[1]=report[1]+' 1.1:</h3><p> Take screenshot(s) of your iptable rules on your Gateway, explain what rules you\'ve added to allow internet access for your Client and Server.</p><h4>Your Submission:</h4>'
            report[2]=report[2]+' 1.2a:</h3><p> Take screenshot(s) on your server to show your hello world demo page.</p><h4>Your Submission:</h4>'
            report[3]=report[3]+' 1.2b:</h3><p> Take screenshot(s) of your vsftpd.conf on your server and explain what you changed.</p><h4>Your Submission:</h4>'
            report[4]=report[4]+' 1.2c:</h3><p> Take screenshot(s) of zone file(s) you added, explain what you did. Also take screenshots on your server to show nslookup results of added URLs.</p><h4>Your Submission:</h4>'
            report[5]=report[5]+' 1.3:</h3><p>Take screenshot(s) on your Client to show your installation command line output.(It\'s ok if your already install them, just run the installation command again and show the result.)</p><h4>Your Submission:</h4>'

        }else if(taskid===102){
            var subtaskarray = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            var message ='<h2>Below is your current submission for Task 2</h2>';
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
        }else if(taskid===103){
            var subtaskarray = [1, 2, 3];
            var message ='<h2>Below is your current submission for Task 3</h2>';
            var report=[];
            subtaskarray.forEach(function(subtaskid){
                report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
            });
            report[1]=report[1]+' 3.3:</h3><p> Provide screenshots to illustrate your scanning results for each type/option and provide output to show the evidence that you firewall was established properly based on the rule setup requirements from Task 2. If your results demonstrate unnecessary open ports/services, then you will get point deductions.</p><h4>Your Submission:</h4>'
            report[2]=report[2]+' 3.4:</h3><p> Submit your script/program as a file with good marked comments/illustrations in the script/program to explain function modules.</p><h4>Your Submission:</h4>'
            report[3]=report[3]+' 3.5:</h3><p> Submit a readme.txt file to illustrate required running environment and how to use the script. </p><h4>Your Submission:</h4>'
        }else if(taskid===104){
            var subtaskarray = [1, 2, 3, 4, 5];
            var message ='<h2>Below is your current submission for Task 1</h2>';
            var report=[];
            subtaskarray.forEach(function(subtaskid){
                report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
            });
            report[1]=report[1]+' 1.2.1:</h3><p> Take screenshot(s) of your demo.conf file</p><h4>Your Submission:</h4>'
            report[2]=report[2]+' 1.2.2:</h3><p> Open a browser and test the webpage demo.asu-sercuritylab.com, take a screenshot to show your result.</p><h4>Your Submission:</h4>'
            report[3]=report[3]+' 1.3.1:</h3><p> Take screenshot(s) of your demo.conf file</p><h4>Your Submission:</h4>'
            report[4]=report[4]+' 1.3.2:</h3><p> Open a browser and test the webpage https://demo.asu-sercuritylab.com and https://www.asu-securitylab.com, take screenshots to show your result.</p><h4>Your Submission:</h4>'
            report[5]=report[5]+' 1.4:</h3><p> Demo the authentication is successful by using the client browser to access the https://demo.asu-securitylab.com  webpage, take screenshots to show your result. Also take screenshots of configure files you\'ve changed.</p><h4>Your Submission:</h4>'

        }else if(taskid===111){
            var subtaskarray = [1, 2, 3, 4];
            var message ='<h2>Below is your current submission for Task 1</h2>';
            var report=[];
            subtaskarray.forEach(function(subtaskid){
                report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
            });
            report[1]=report[1]+' 1.2:</h3><p> Take screenshot(s) of your NMAP scan result.</p><h4>Your Submission:</h4>'
            report[2]=report[2]+' 1.3:</h3><p> After install Metasploit, take a screenshot of <em>sudo msfconsole</em> result</p><h4>Your Submission:</h4>'
            report[3]=report[3]+' 1.4.d:</h3><p> Take screenshot(s) of <em>exploit</em> output</p><h4>Your Submission:</h4>'
            report[4]=report[4]+' 1.4.es:</h3><p> Take screenshot(s) of the file you added.</p><h4>Your Submission:</h4>'

        }else if(taskid===112){
            var subtaskarray = [1, 2, 3];
            var message ='<h2>Below is your current submission for Task 1</h2>';
            var report=[];
            subtaskarray.forEach(function(subtaskid){
                report[subtaskid]='<div class="report-subtask'+subtaskid+'"><h3>Subtask';
            });
            report[1]=report[1]+' 2.1:</h3><p>  Take screenshot(s) of your pentest result.</p><h4>Your Submission:</h4>'
            report[2]=report[2]+' 2.3:</h3><p>  Upload your log file and report (each student need to upload your own files.)</p><h4>Your Submission:</h4>'
            report[3]=report[3]+' 2.4:</h3><p>  Take screenshot(s) showing how you compromised the target system.</p><h4>Your Submission:</h4>'

        }

        $.getJSON("/tasksubmissionbysubgroup", {

            "taskid": taskid,
            "subgroupid":subgroupid

        }, function (data) {


            for (var i = 0; i < data.length; i++) {
                if(data[i].type==1) {
                    var taskcontent = '<div class="subtasksubmission">' +
                        '<p>Subject: <font color="blue">' + data[i].title.substring(0, 50) + '</font> by '+data[i].username+'</p>Screenshot:<br/><img src="' + data[i].submission + '" style="max-width: 95%" /><p>Descripiton: <font color="blue">' + data[i].desc +
                        '</font></p></div>';
                    report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;
                }else if(data[i].type==2){
                    var fullPath = data[i].submission;
                    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                    var filename = fullPath.substring(startIndex);
                    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                        filename = filename.substring(1);
                    }
                    var taskcontent = '<div class="subtasksubmission">' +
                        '<p>Filename: <font color="blue">' + filename + '</font></p>' +
                        '<button class="btn btn-default" title="Download File" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].submission+'\',\'_blank\')"><i class="fa fa-download"></i>Download File</button>' +
                        '<p>Descripiton: <font color="blue">' + data[i].desc +
                        '</font></p></div>';
                    report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;
                }else if(data[i].type==3){
                    var taskcontent = '<div class="subtasksubmission">' +
                        '<p>Text Answer: <font color="blue">' + data[i].desc +
                        '</font></p></div>';
                    report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;
                }
            }
            subtaskarray.forEach(function(subtaskid){
                report[subtaskid]=report[subtaskid]+'</div>';
                message=message+report[subtaskid];
            });
            var ask = 'Task Report';



            create_ConfirmDialog('.container-fluid', ask, message, '50%', '.container-fluid',
                function () {
                    PrintElem('dialog_confirm');
                },function(){});
            $('#dialog_confirm').css('height','95%').css('max-width','100%').css('width','100%').parent().css('height','80%');
            if($('li.header:contains("Super User Functions")').length!=0){
                $('.ui-dialog-buttonset').first().children().first().html('Print');
                $('.ui-dialog-buttonset').first().children().eq(1).html('Close');
            }else{
                $('.ui-dialog-buttonset').first().children().first().remove();
                $('.ui-dialog-buttonset').first().children().first().html('Close');
            }

        });
    }

function create_ConfirmDialogwithCheck(div_id, title, ask, width, containment, okCallback, cancelCallback) {
    var dlg_form = $(document.createElement('div')).appendTo($(div_id));
    dlg_form.attr('id', 'dialog_confirm').attr('title', title).css('float', 'left').css('width','100%').css('max-height','80%').css('overflow-y', 'auto');

    //var form_html = '<p>' + ask + '</p>';
    $(ask).css('margin-top', '25px').appendTo(dlg_form);
    $('#dialog_confirm').dialog({
        resizable: false,
        position: {my: "center", at: "center", of: containment},
        modal: true,
        width: width,
        buttons: {
            "Yes": function () {
                var input = document.getElementById('file');
                if(input!==null){
                    var file = input.files[0];
                }

                if($('#screenshottitle').val()==''){
                    alert('Subject cannot be empty!');
                }else if($('#screenshotdesc').val()==''){
                    alert('Description cannot be empty!');
                }else if($('#file').val()==''){
                    alert('Please select one file to be uploaded!');
                }else if(input!==null){
                    if(file.size>10090000) {
                        alert('Cannot upload file over 10 MB.')
                    }else{
                        okCallback();

                        $(this).dialog('close');
                    }
                }else{
                    okCallback();

                    $(this).dialog('close');
                }
            },
            "Cancel": function () {
                cancelCallback();
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
    $('.ui-widget-content').css('background-color', 'beige');
    $('.ui-dialog-titlebar').css('height', '35px');
    $('.ui-dialog-titlebar-close').css('display', 'none');
}

function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}

function view_report(taskid,subgroupid) {


        var subtaskarray = [1, 2, 3];
        var message ='<h2>Below is your current submission:</h2>';
        var report=[];
        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]='<div class="report-subtask'+subtaskid+'">';
        });
        report[1]=report[1]+'<h3 class="reportscreenshot">Screenshots:</h3><h4 class="reportscreenshot">Your Submission:</h4>'
        report[2]=report[2]+'<h3 class="reportfile">Files:</h3><h4 class="reportfile">Your Submission:</h4>'
        report[3]=report[3]+'<h3 class="reporttext">Texts:</h3><h4 class="reporttext">Your Submission:</h4>'



    $.getJSON("/tasksubmissionbysubgroup", {

        "taskid": taskid,
        "subgroupid": subgroupid

    }, function (data) {

        var screenshotflag =0;
        var fileflag =0;
        var textflag =0;
        for (var i = 0; i < data.length; i++) {

            if(data[i].type==1) {
                screenshotflag =1;
                var taskcontent = '<div class="subtasksubmission">' +
                    '<p>Subject: <font color="blue">' + data[i].title.substring(0, 50) + '</font></p>Screenshot:<br/><img src="' + data[i].submission + '" style="max-width: 95%" /><p>Descripiton: <font color="blue">' + data[i].desc +
                    '</font></p></div>';
                report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;
            }else if(data[i].type==2){
                fileflag =1;
                var fullPath = data[i].submission;
                var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                var filename = fullPath.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                var taskcontent = '<div class="subtasksubmission">' +
                    '<p>Filename: <font color="blue">' + filename + '</font></p>' +
                    '<button class="btn btn-default" title="Download File" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].submission+'\',\'_blank\')"><i class="fa fa-download"></i>Download File</button>' +
                    '<p>Descripiton: <font color="blue">' + data[i].desc +
                    '</font></p></div>';
                report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;
            }else if(data[i].type==3){
                testflag=1;
                var taskcontent = '<div class="subtasksubmission">' +
                    '<p>Text Answer: <font color="blue">' + data[i].desc +
                    '</font></p></div>';
                report[data[i].subtask_id] = report[data[i].subtask_id] + taskcontent;
            }

        }

        subtaskarray.forEach(function(subtaskid){
            report[subtaskid]=report[subtaskid]+'</div>';
            message=message+report[subtaskid];
        });
        var ask = 'Task Report';



        create_ConfirmDialog('.container-fluid', ask, message, '50%', '.container-fluid',
            function () {
                PrintElem('dialog_confirm');
            },function(){});
        $('#dialog_confirm').css('height','95%').css('max-width','100%').css('width','100%').parent().css('height','80%');
        if($('li.header:contains("Super User Functions")').length!=0){
            $('.ui-dialog-buttonset').first().children().first().html('Print');
            $('.ui-dialog-buttonset').first().children().eq(1).html('Close');
        }else{
            $('.ui-dialog-buttonset').first().children().first().remove();
            $('.ui-dialog-buttonset').first().children().first().html('Close');
        }
        if(screenshotflag==0){
            $('.reportscreenshot').empty();
        }
        if(fileflag==0){
            $('.reportfile').empty();
        }
        if(textflag==0){
            $('.reporttext').empty();
        }

    });
}

function upload_text(taskid, subgroupid) {
//function takescreenshot() {
    //var taskid = 1, labid=101, subtaskid=1, vm_name='Server';


    var desc = CKEDITOR.instances['submission-task-'+taskid+'-'+subgroupid+'-3'].getData();
    var empty ='';

                    $.post("/groupsubmit", {

                            "taskid": taskid,

                            "subtaskid": 3,
                            "title":empty,
                            "answer": empty,
                            "desc":desc,
                            "type":3,
                            "source":empty,
                            "subgroupid":subgroupid

                        },
                        function (data) {
                                if(data.status==='Success'){
                                    alert("Text submitted!");
                                    $("#upload-"+taskid+"-text").prop("disabled",true);
                                }
                        },
                        'json'
                    );

}
