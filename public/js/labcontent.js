function labaddpdf(labid) {
    if(labid==0) {
        //var difficulty=[document.querySelector('input[name="difftime"]:checked').value,document.querySelector('input[name="diffdesign"]:checked').value,document.querySelector('input[name="diffimp"]:checked').value,document.querySelector('input[name="diffconf"]:checked').value,document.querySelector('input[name="diffknow"]:checked').value];
        var taskcount = $('#taskcount').attr('data-value') - 1;
        if (document.getElementById('labcatid').value == '') {
            Swal.fire('Can\'t upload file yet! Please input a Lab Cat ID first!', '', 'fail');
        } else {
            $.post("/labcontents/saveContent", {
                    "labname": document.getElementById('labname').value,
                    "labcatid": document.getElementById('labcatid').value,
                    "labdesc": document.getElementById('desc').value,
                    "labdesc": CKEDITOR.instances.desc.getData(),
                    "tags": $("textarea[id=tags]").val(),
                    //"objects": $("textarea[id=objects]").val(),
                    "objects": CKEDITOR.instances.labdesceditor.getData(),
                    "experttime": document.getElementById('experttime').value,
                    "time": document.getElementById('time').value,
                    "difficulty": [0, 0, 0, 0, 0],
                    "os": document.getElementById('os').value,
                    "preparations": $("textarea[id=preparations]").val(),
                    "taskcount": taskcount,
                    "labcatid": document.getElementById('labcatid').value,
                },
                function (data1) {
                    //template_list_update('lab_templates', 'new');
                    //alert(data);
                    if (data1.status == 'Success') {

                        // document.getElementById("lab-add-file").onclick = function () {
                        //     labaddpdf(data1.id);
                        // };
                        // document.getElementById("save-labcontent-btn").onclick = function () {
                        //     savelabcontent(data1.id);
                        // };
                        $('#lab-add-pdf').attr("onclick","labaddpdf("+data1.id+")");
                        $('#save-labcontent-btn').attr("onclick","savelabcontent("+data1.id+")");
                        $('#lab-pdf-0').after($('#lab-pdf-0').clone().attr("id","lab-pdf-"+data1.id));

                        $('#dataTableBuilder').DataTable().draw();
                        var ask = 'Upload PDF';
                        var message = '<p><form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action=""><p><input id="file" name="file"  type="file"  accept="application/pdf" style="height: auto"><font color="red">File Size Under 10MB</font> </p>' +

                            '<input type="hidden" name="labid" value="' + data1.id + '">' +

                            '</p>';

                        create_ConfirmDialogwithCheck('.container-fluid', ask, message, '40%', '.container-fluid',
                            function () {


                                var btn_tooltip = $('<button class="btn-image-icon"></button>').css('margin-left', '5pt').css('margin-top', '3px').appendTo($('#lab-pdf-' + labid));
                                $('<i class=" fa fa-spinner fa-pulse fileuploading"></i>').appendTo(btn_tooltip);
                                btn_tooltip.attr('title', 'Uploading');

                                // $(btn_tooltip).insertAfter("#sub_"+taskid+"_"+subtaskid);

                                $('.btn-image-icon').on('click', function (event) {
                                    event.preventDefault();
                                });


                                $.ajax({
                                    type: 'POST',
                                    url: '/uploadpdfforlab',
                                    data: new FormData($("#upload_form")[0]),
                                    processData: false,
                                    contentType: false,
                                    success: function (data) {
                                        var fullPath = data.url;
                                        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                                        var filename = fullPath.substring(startIndex);
                                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                                            filename = filename.substring(1);
                                        }

                                        var tooltip = '<div class="tooltip_content">'
                                            + '<p>File Name: ' + filename + '</p>'
                                            + '<button class="btn btn-success btn-file-tooltip btn-lab-' + data1.id + '" title="open file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu' + data.url + '\',\'_blank\')"><i class="fa fa-file"></i></button>'
                                            + '<button class="btn btn-danger btn-file-tooltip btn-lab-' + data1.id + '" title="delete file" onclick="lab_content_tooltip_deletepdf(' + data1.id + ',' + data.id + ')"><i class="fa fa-trash"></i></button></div>';
                                        btn_tooltip.addClass('btn btn-default btn-lab-' + data1.id).attr('title', tooltip).attr("id", "lab_" + data1.id + "_" + data.id);
                                        $("#lab_" + data1.id + "_" + data.id).tooltipster(
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
                                        $(".fileuploading").removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-file');
                                        $("#lab-add-pdf").hide();
                                    }
                                });


                            },
                            function () {
                                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                            });
                        $('#dialog_confirm').css('height', '95%').css('max-width', '100%').css('width', '100%').parent().css('max-height', '80%');
                        $('.ui-dialog-buttonset').first().children().first().html('Save');
                    }else if(data1.status == 'Failed'){
                        Swal.fire('Can not upload file! Lab Cat ID already exist!', '', 'fail');
                    }
                },
                'json'
            ).fail(function (xhr, testStatus, errorThrown) {
                //alert(xhr.responseText);
            });
        }

    }else {
        var ask = 'Upload PDF';
        var message = '<p><form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action=""><p><input id="file" name="file"  type="file" accept="application/pdf" style="height: auto"><font color="red">File Size Under 10MB</font> </p>' +

            '<input type="hidden" name="labid" value="' + labid + '">' +

            '</p>';

        create_ConfirmDialogwithCheck('.container-fluid', ask, message, '40%', '.container-fluid',
            function () {


                var btn_tooltip = $('<button class="btn-image-icon"></button>').css('margin-left', '5pt').css('margin-top', '3px').appendTo($('#lab-pdf-' + labid));
                $('<i class=" fa fa-spinner fa-pulse fileuploading"></i>').appendTo(btn_tooltip);
                btn_tooltip.attr('title', 'Uploading');

                // $(btn_tooltip).insertAfter("#sub_"+taskid+"_"+subtaskid);

                $('.btn-image-icon').on('click', function (event) {
                    event.preventDefault();
                });


                $.ajax({
                    type: 'POST',
                    url: '/uploadpdfforlab',
                    data: new FormData($("#upload_form")[0]),
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var fullPath = data.url;
                        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                        var filename = fullPath.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }

                        var tooltip = '<div class="tooltip_content">'
                            + '<p>File Name: ' + filename + '</p>'
                            + '<button class="btn btn-success btn-file-tooltip btn-lab-' + labid + '" title="open file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu' + data.url + '\',\'_blank\')"><i class="fa fa-file"></i></button>'
                            + '<button class="btn btn-danger btn-file-tooltip btn-lab-' + labid + '" title="delete file" onclick="lab_content_tooltip_deletepdf(' + labid + ',' + data.id + ')"><i class="fa fa-trash"></i></button></div>';
                        btn_tooltip.addClass('btn btn-default btn-lab-' + labid).attr('title', tooltip).attr("id", "lab_" + labid + "_" + data.id);
                        $("#lab_" + labid + "_" + data.id).tooltipster(
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
                        $(".fileuploading").removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-file');
                        $("#lab-add-pdf").hide();

                    }
                });


            },
            function () {
                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
            });
        $('#dialog_confirm').css('height', '95%').css('max-width', '100%').css('width', '100%').parent().css('max-height', '80%');
        $('.ui-dialog-buttonset').first().children().first().html('Save');
    }
}

function labaddfile(labid) {
    if(labid==0) {
        //var difficulty=[document.querySelector('input[name="difftime"]:checked').value,document.querySelector('input[name="diffdesign"]:checked').value,document.querySelector('input[name="diffimp"]:checked').value,document.querySelector('input[name="diffconf"]:checked').value,document.querySelector('input[name="diffknow"]:checked').value];
        var taskcount = $('#taskcount').attr('data-value') - 1;
        if (document.getElementById('labcatid').value == '') {
            Swal.fire('Can\'t upload file yet! Please input a Lab Cat ID first!', '', 'fail');
        } else {
            $.post("/labcontents/saveContent", {
                    "labname": document.getElementById('labname').value,
                    "labcatid": document.getElementById('labcatid').value,
                    "labdesc": CKEDITOR.instances.desc.getData(),
                    "tags": $("textarea[id=tags]").val(),
                    //"objects": $("textarea[id=objects]").val(),
                    "objects": CKEDITOR.instances.labdesceditor.getData(),
                    "experttime": document.getElementById('experttime').value,
                    "time": document.getElementById('time').value,
                    "difficulty": [0, 0, 0, 0, 0],
                    "os": document.getElementById('os').value,
                    "preparations": $("textarea[id=preparations]").val(),
                    "taskcount": taskcount,
                    "labcatid": document.getElementById('labcatid').value,
                },
                function (data1) {
                    //template_list_update('lab_templates', 'new');
                    //alert(data);
                    if (data1.status == 'Success') {

                        // document.getElementById("lab-add-file").onclick = function () {
                        //     labaddfile(data1.id);
                        // };
                        // document.getElementById("save-labcontent-btn").onclick = function () {
                        //     savelabcontent(data1.id);
                        // };
                        $('#lab-add-file').attr("onclick","labaddfile("+data1.id+")");
                        $('#save-labcontent-btn').attr("onclick","savelabcontent("+data1.id+")");
                        $('#lab-file-0').after($('#lab-file-0').clone().attr("id","lab-file-"+data1.id));
                        $('#dataTableBuilder').DataTable().draw();
                        var ask = 'Upload File';
                        var message = '<p><form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action=""><p><input id="file" name="file"  type="file" style="height: auto"><font color="red">File Size Under 10MB</font> </p>' +

                            '<input type="hidden" name="labid" value="' + data1.id + '">' +

                            '<p>Please also describe the file you want to upload</br><textarea form="upload_form" name="desc" spellcheck="true" style="max-width: 100%;width: 100%"  id="filedesc" cols="50" rows="2"></textarea></p></p>';

                        create_ConfirmDialogwithCheck('.container-fluid', ask, message, '40%', '.container-fluid',
                            function () {


                                var btn_tooltip = $('<button class="btn-image-icon"></button>').css('margin-left', '5pt').css('margin-top', '3px').appendTo($('#lab-file-' + labid));
                                $('<i class=" fa fa-spinner fa-pulse fileuploading"></i>').appendTo(btn_tooltip);
                                btn_tooltip.attr('title', 'Uploading');

                                // $(btn_tooltip).insertAfter("#sub_"+taskid+"_"+subtaskid);

                                $('.btn-image-icon').on('click', function (event) {
                                    event.preventDefault();
                                });


                                $.ajax({
                                    type: 'POST',
                                    url: '/uploadfileforlab',
                                    data: new FormData($("#upload_form")[0]),
                                    processData: false,
                                    contentType: false,
                                    success: function (data) {
                                        var fullPath = data.url;
                                        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                                        var filename = fullPath.substring(startIndex);
                                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                                            filename = filename.substring(1);
                                        }

                                        var tooltip = '<div class="tooltip_content">'
                                            + '<p>File Name: ' + filename + '</p>'
                                            + '<button class="btn btn-warning btn-file-tooltip btn-lab-' + data1.id + '" title="download file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu' + data.url + '\',\'_blank\')"><i class="fa fa-download"></i></button>'
                                            + '<button class="btn btn-danger btn-file-tooltip btn-lab-' + data1.id + '" title="delete file" onclick="lab_content_tooltip_deletefile(' + data1.id + ',' + data.id + ')"><i class="fa fa-trash"></i></button></div>';
                                        btn_tooltip.addClass('btn btn-default btn-lab-' + data1.id).attr('title', tooltip).attr("id", "lab_" + data1.id + "_" + data.id);
                                        $("#lab_" + data1.id + "_" + data.id).tooltipster(
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
                                        $(".fileuploading").removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-file');

                                    }
                                });


                            },
                            function () {
                                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                            });
                        $('#dialog_confirm').css('height', '95%').css('max-width', '100%').css('width', '100%').parent().css('max-height', '80%');
                        $('.ui-dialog-buttonset').first().children().first().html('Save');
                    }else if(data1.status == 'Failed'){
                        Swal.fire('Can not upload file! Lab Cat ID already exist!', '', 'fail');
                    }
                },
                'json'
            ).fail(function (xhr, testStatus, errorThrown) {
                //alert(xhr.responseText);
            });
        }

    }else {
        var ask = 'Upload File';
        var message = '<p><form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action=""><p><input id="file" name="file"  type="file" style="height: auto"><font color="red">File Size Under 10MB</font> </p>' +

            '<input type="hidden" name="labid" value="' + labid + '">' +

            '<p>Please also describe the file you want to upload</br><textarea form="upload_form" name="desc" spellcheck="true" style="max-width: 100%;width: 100%"  id="filedesc" cols="50" rows="2"></textarea></p></p>';

        create_ConfirmDialogwithCheck('.container-fluid', ask, message, '40%', '.container-fluid',
            function () {


                var btn_tooltip = $('<button class="btn-image-icon"></button>').css('margin-left', '5pt').css('margin-top', '3px').appendTo($('#lab-file-' + labid));
                $('<i class=" fa fa-spinner fa-pulse fileuploading"></i>').appendTo(btn_tooltip);
                btn_tooltip.attr('title', 'Uploading');

                // $(btn_tooltip).insertAfter("#sub_"+taskid+"_"+subtaskid);

                $('.btn-image-icon').on('click', function (event) {
                    event.preventDefault();
                });


                $.ajax({
                    type: 'POST',
                    url: '/uploadfileforlab',
                    data: new FormData($("#upload_form")[0]),
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var fullPath = data.url;
                        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                        var filename = fullPath.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }

                        var tooltip = '<div class="tooltip_content">'
                            + '<p>File Name: ' + filename + '</p>'
                            + '<button class="btn btn-warning btn-file-tooltip btn-lab-' + labid + '" title="download file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu' + data.url + '\',\'_blank\')"><i class="fa fa-download"></i></button>'
                            + '<button class="btn btn-danger btn-file-tooltip btn-lab-' + labid + '" title="delete file" onclick="lab_content_tooltip_deletefile(' + labid + ',' + data.id + ')"><i class="fa fa-trash"></i></button></div>';
                        btn_tooltip.addClass('btn btn-default btn-lab-' + labid).attr('title', tooltip).attr("id", "lab_" + labid + "_" + data.id);
                        $("#lab_" + labid + "_" + data.id).tooltipster(
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
                        $(".fileuploading").removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-file');

                    }
                });


            },
            function () {
                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
            });
        $('#dialog_confirm').css('height', '95%').css('max-width', '100%').css('width', '100%').parent().css('max-height', '80%');
        $('.ui-dialog-buttonset').first().children().first().html('Save');
    }
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



// function addtask(){
//     taskcount=$('#taskcount').attr('data-value');
//     $('.btn-addtask').remove();
//     var tasklist=document.getElementById("taskbox");
//
//     tasklist.insertAdjacentHTML('beforeend','<div id="taskbox' +taskcount+'">\n' +
//     '                                <h4>Task ' +taskcount+'</h4>\n' +
//         '                                <h5>Task Name: <input  id="taskname'+taskcount+'" name="taskname'+taskcount+'" type="text" value="" /></h5>\n' +
//         '<div  id="task'+taskcount+'submissioncount" data-value="1"></div>'+
//     '                                <h5>Description :</h5>\n' +
//     '                                <textarea name="taskdesceditor'+taskcount+'" id="taskdesceditor'+taskcount+'">&lt;p&gt;This is some sample content.&lt;/p&gt;</textarea>\n' +
//     '                                <h5>Submissions :</h5>\n' +
//     // '                                Submission 1 Description:  <br/><textarea name="object" id="object" style="width: 80%">\n' +
//     // '                                </textarea><br/>\n' +
//     // '                                Submission 1 Type:<select>\n' +
//     // '                                    <option value="Screenshot">Screenshot</option>\n' +
//     // '                                    <option value="File">File</option>\n' +
//     // '                                    <option value="Text">Text only</option>\n' +
//     // '\n' +
//     // '                                </select><br/><br/>' +
//         '<div id="submissionbox' +taskcount+'"></div>'+
//         '                                <button type="button" class="btn btn-default btn-info" onclick="addsubmission(' +taskcount+')"><i class="fa fa-file-text"></i>Add Submission</button><br/><br/>\n' +
//         '   <button  type="button" class="btn btn-default btn-warning   btn-removetask" onclick="removetask('+taskcount+')"  ><i class="fa fa-trash"></i>Remove Task'+taskcount+'</button><br/><br/>\n'+
//         '</div>\n' +
//
//     '<button  type="button" class="btn btn-default btn-success   btn-addtask" onclick="addtask()"  ><i class="fa fa-share"></i>Add Task</button><br/>\n' +
//
//     '\n' +
//     '\n' );
//     $('#taskcount').attr('data-value',++taskcount);
//     var taskcountnew=taskcount-1;
//     ClassicEditor
//         .create( document.querySelector( '#taskdesceditor'+taskcountnew ) )
//         .then( editor => {
//         console.log( 'Editor was initialized', editor );
//             myEditor = editor;
// } )
// .catch( err => {
//         console.error( err.stack );
// } );
// }
//
// function removetask(taskid){
//
//
//     $("#taskbox"+taskid).remove();
//
//
//     $('#taskcount').attr('data-value',--taskcount);
//
// }
//
// function addsubmission(taskid){
//     submissioncount=$('#task'+taskid+'submissioncount').attr('data-value');
//     var submissionlist=document.getElementById("submissionbox"+taskid);
//     submissionlist.insertAdjacentHTML('beforeend','<div  id="task'+taskid+'submissionbox'+submissioncount+'">Submission ' +submissioncount+' Description:  <br/><textarea name="task'+taskid+'submissiondesc'+submissioncount+'" id="task'+taskid+'submissiondesc'+submissioncount+'" style="width: 80%">' +
//         '</textarea><br/>\n' +
//         '                                Submission ' +submissioncount+' Type:<select id="task'+taskid+'submissiontype'+submissioncount+'">\n' +
//         '                                    <option value="Screenshot">Screenshot</option>\n' +
//         '                                    <option value="File">File</option>\n' +
//         '                                    <option value="Text">Text only</option>\n' +
//         '\n' +
//         '                                </select><br/><br/>\n' +
//         '   <button  type="button" class="btn btn-default btn-warning   btn-removesubmission" onclick="removesubmission('+taskid+','+submissioncount+')"  ><i class="fa fa-trash"></i>Remove Submission '+submissioncount+'</button><br/><br/>\n'+
//         '</div>');
//     $('#task'+taskid+'submissioncount').attr('data-value',++submissioncount);
// }
//
// function removesubmission(taskid,submissionid){
//     $("#task"+taskid+"submissionbox"+submissionid).remove();
//     $('#task'+taskid+'submissioncount').attr('data-value',--submissioncount);
// }

// function savelabcontent(labcontentid){
//     var difficulty=document.querySelector('input[name="difftime"]:checked').value;
//     var taskcount=$('#taskcount').attr('data-value')-1;
//     if(labcontentid==0){
//         $.post("/labcontent/saveContent", {
//                 "labname": document.getElementById('labname').value,
//                 "tags":$("textarea[id=tags]").val(),
//                 "objects": $("textarea[id=objects]").val(),
//                 "experttime": document.getElementById('experttime').value,
//                 "time": document.getElementById('time').value,
//                 "difficulty" : difficulty,
//                 "os": document.getElementById('os').value,
//                 "preparations": $("textarea[id=preparations]").val(),
//             "taskcount": taskcount,
//             "labcatid":document.getElementById('labcatid').value,
//             },
//             function (data) {
//                 //template_list_update('lab_templates', 'new');
//                 //alert(data);
//                 if (data.status == 'Success') {
//                     Swal.fire('Lab Saved!', '', 'success');
//                     window.location.href = '/labcontent/';
//                 }
//             },
//             'json'
//         ).fail(function (xhr, testStatus, errorThrown) {
//             //alert(xhr.responseText);
//         });
//     }else{
//         $.post("/labcontent/updateContent", {
//                 "id" : labcontentid,
//                 "labname": document.getElementById('labname').value,
//                 "tags":$("textarea[id=tags]").val(),
//                 "objests": $("textarea[id=objests]").val(),
//                 "experttime": document.getElementById('experttime').value,
//                 "time": document.getElementById('time').value,
//                 "difficulty" : difficulty,
//                 "os": document.getElementById('os').value,
//                 "preparations": $("textarea[id=preparations]").val(),
//                 "taskcount": taskcount
//             },
//             function (data) {
//                 //template_list_update('lab_templates', 'new');
//                 //alert(data);
//                 if (data != null) {
//                     // alert('Not Found');
//                 }
//             },
//             'json'
//         ).fail(function (xhr, testStatus, errorThrown) {
//             //alert(xhr.responseText);
//         });
//     }
//
//
// }

function savelabcontent(labcontentid){
    if((document.querySelector('input[name="difftime"]:checked')&&document.querySelector('input[name="diffdesign"]:checked')&&document.querySelector('input[name="diffimp"]:checked')&&document.querySelector('input[name="diffconf"]:checked')&&document.querySelector('input[name="diffknow"]:checked'))==null){
        var difficulty=[0, 0, 0, 0, 0];
    }else{
        var difficulty=[document.querySelector('input[name="difftime"]:checked').value,document.querySelector('input[name="diffdesign"]:checked').value,document.querySelector('input[name="diffimp"]:checked').value,document.querySelector('input[name="diffconf"]:checked').value,document.querySelector('input[name="diffknow"]:checked').value];
    }
    var taskcount=$('#taskcount').attr('data-value')-1;
    if(labcontentid==0){
        $.post("/labcontents/saveContent", {
                "labname": document.getElementById('labname').value,
                "labdesc": CKEDITOR.instances.desc.getData(),
                "tags":$("textarea[id=tags]").val(),
                //"objects": $("textarea[id=objects]").val(),
                "objects": CKEDITOR.instances.labdesceditor.getData(),
                "experttime": document.getElementById('experttime').value,
                "time": document.getElementById('time').value,
                "difficulty" : difficulty,
                "os": document.getElementById('os').value,
                "preparations": $("textarea[id=preparations]").val(),
                "taskcount": taskcount,
                "labcatid":document.getElementById('labcatid').value,
                "pdfflag":$('#pdf-trigger').bootstrapSwitch('state')
            },
            function (data) {
                //template_list_update('lab_templates', 'new');
                //alert(data);
                if (data.status == 'Success') {
                    Swal.fire('Lab Saved!', '', 'success');
                    // $("#labcontent").hide();
                    $('#dataTableBuilder').DataTable().draw();
                }else if(data.status == 'Failed'){
                    Swal.fire('Lab not saved! Lab Cat ID already exist!', '', 'fail');
                }
            },
            'json'
        ).fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
        });
    }else{
        $.post("/labcontents/updateContent", {
                "id" : labcontentid,
                "labname": document.getElementById('labname').value,
                "labdesc": CKEDITOR.instances.desc.getData(),
                "tags":$("textarea[id=tags]").val(),
                "objects": CKEDITOR.instances.labdesceditor.getData(),
                "experttime": document.getElementById('experttime').value,
                "time": document.getElementById('time').value,
                "difficulty" : difficulty,
                "os": document.getElementById('os').value,
                "preparations": $("textarea[id=preparations]").val(),
                "taskcount": taskcount,
                "labcatid":document.getElementById('labcatid').value,
                "pdfflag":$('#pdf-trigger').bootstrapSwitch('state')
            },
            function (data) {
                //template_list_update('lab_templates', 'new');
                //alert(data);
                if (data.status == 'Success') {
                    Swal.fire('Lab Updated!', '', 'success');
                    $('#dataTableBuilder').DataTable().draw();
                    // $("#labcontent").hide();

                }
            },
            'json'
        ).fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
        });
    }


}

function deletelabcontent(element){
    var tr = $(element).closest('tr');
    var row = $('#dataTableBuilder').DataTable().row(tr);
    var labcontentid = row.data().id;
    Swal.fire({
        title: 'Delete this lab?',
        text: 'It can\'t be recovered!',
        type: 'question',
        showCancelButton: true,
        confirmButton: 'Yes'
    }).then((result) => {
        if (result.value) {
            $.post("/labcontents/deleteContent", {
                    "id" : labcontentid,
                },
                function (data) {
                    //template_list_update('lab_templates', 'new');
                    //alert(data);
                    if (data.status == 'Success') {
                        Swal.fire('Lab Deleted!', '', 'success');
                        $('#dataTableBuilder').DataTable().draw();
                        $("#labcontent").hide();
                        $("#workspace").hide();
                    }else if(data.status == 'eFailed'){
                        Swal.fire({
                            title: 'Delete Lab  Error!', type: 'error',
                            html: '<em>Reason</em><br />' +
                            '<b>This Lab Environment is still being used. <br/>Please unassign it from all projects in Lab Management!</br><br />'

                        });
                    }
                },
                'json'
            ).fail(function (xhr, testStatus, errorThrown) {
                //alert(xhr.responseText);
            });
        }
    });
}


function savelabtask(taskid,labid){
    var submission=[document.getElementById("screenshots").checked,document.getElementById("files").checked,document.getElementById("texts").checked];

    if(taskid==0){
        $.post("/labcontents/saveTask", {
                "taskname": document.getElementById('taskname').value,
                // "description":myEditor.getData(),
                "description":CKEDITOR.instances.taskdesceditor.getData(),
                "submission": submission,
                "labid":labid
            },
            function (data) {
                //template_list_update('lab_templates', 'new');
                //alert(data);
                if (data.status == 'Success') {
                    Swal.fire('Task Saved!', '', 'success');
                    $('#tasks-'+labid).DataTable().draw();
                    // $("#labcontent").hide();
                }else if(data.status == 'Failed'){
                    Swal.fire('Task not saved! Lab Cat ID already exist!', '', 'fail');
                }
            },
            'json'
        ).fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
        });
    }else{
        $.post("/labcontents/updateTask", {
                "id" : taskid,
                "labid":labid,
                "taskname": document.getElementById('taskname').value,
                "description":CKEDITOR.instances.taskdesceditor.getData(),
                "submission": submission
            },
            function (data) {
                //template_list_update('lab_templates', 'new');
                //alert(data);
                if (data.status == 'Success') {
                    Swal.fire('Task Updated!', '', 'success');
                    $('#tasks-'+labid).DataTable().draw();
                    // $("#labcontent").hide();

                }
            },
            'json'
        ).fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
        });
    }


}

function deletelabtask(element,labid){
    var tr = $(element).closest('tr');
    var row = $('#tasks-'+labid).DataTable().row(tr);
    var taskid = row.data().id;
    Swal.fire({
        title: 'Delete this lab task?',
        text: 'It can\'t be recovered!',
        type: 'question',
        showCancelButton: true,
        confirmButton: 'Yes'
    }).then((result) => {
        if (result.value) {
            $.post("/labcontents/deleteTask", {
                    "id" : taskid,
                },
                function (data) {
                    //template_list_update('lab_templates', 'new');
                    //alert(data);
                    if (data.status == 'Success') {
                        Swal.fire('Lab Task Deleted!', '', 'success');
                        $('#tasks-'+labid).DataTable().draw();
                        $("#labcontent").hide();
                        $("#workspace").hide();
                                           }
                },
                'json'
            ).fail(function (xhr, testStatus, errorThrown) {
                //alert(xhr.responseText);
            });
        }
    });
}



function task_details_format(data) {
    return '<div style="margin-left: 10px; margin-right: 10px;"><h4>Lab ' + data.name + '\'s Tasks</h4>' +
        '<table class="table details-table table-condensed" id="tasks-' + data.id + '" cellpadding="0" border="0" width="100%">' +
        '<thead>' +
        '<tr>' +
        '<th>Task ID</th>' +
        '<th>Name</th>' +
        '<th>Submission</th>' +
        '<th>Submission</th>' +
        '<th>Action</th>' +
        '</tr>' +
        '</thead>' +
        '</table></div>';
}

function task_details_initTable(tableId, data) {
    var labid=data.id;
    var table = $('#' + tableId).DataTable({
        dom: '<"toolbar">rtip',
        //buttons: ['csv', 'print', 'reload'],
        destroy: true,
        processing: true,
        serverSide: true,
        // scrollX: true,
        // scrollCollapse: true,
        // scroller: true,
        ajax: data.details_url,
        columnDefs: [
        //     {
        //     targets: 0,
        //         defaultContent:'',
        //     searchable:false,
        //     orderable:false,
        //     className: 'submission-details-control',
        //     // render: function (data, type, full, meta){
        //     //     return '<input type="checkbox" name="' + tableId + '-members[]" value="'
        //     //         + $('<div/>').text(data).html() + '">';
        //     // }
        // },
            {
            targets: 4,
            searchable:false,
            orderable:false,
            render: function (data, type, full, meta) {
                return '<div class="btn-group">' +
                    '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    'Action <span class="caret"></span>' +
                    '</button>' +
                    '<ul class="dropdown-menu" style="min-width: 10px;">' +
                    '<li><a href="#" onclick="showtaskeditor($(this),'+labid+')">Edit Task</a></li>' +
                    '<li role="separator" class="divider"></li>' +
                    '<li><a href="#" onclick="deletelabtask($(this),'+labid+')" style="color:red">Delete Task</a></li>' +
                    '</ul>' +
                    '</div>';
            }
        }],
        columns: [

            // { data: "null", name: "submission_details_url" },
            { data: "id", name: "id" ,"visible": false},
            { data: "name", name: "name" },
            { data: "submission", name: "submission","visible": false},
            { data: "state"},


        ]
    });

    $('#'+tableId+'_wrapper').children('div.toolbar').html('<span style="margin-left: 20px;">' +
        '<button type="button" class="btn btn-primary" style="margin-left: 5px" onclick="showtaskeditor(0,'+data.id+')">Add New Task</button></span>');
        // '<button type="button" class="btn btn-danger" style="margin-left: 5px" onclick="group_members_delete($(this))">Remove Members</button>' +
        // '<button type="button" class="btn btn-info" style="margin-left: 5px" onclick="group_members_change_roles($(this))">Change Role</button></span>');
}

function checkbox_check_all(element) {
    var table = $(element).closest('table').DataTable();
    var rows = table.rows({ 'search': 'applied' }).nodes();
    //var rows = table.fnGetNodes();
    $('input[type="checkbox"]', rows).prop('checked', element[0].checked);
}

function loadtasks(element) {
    var tr = $(element).closest('tr');
    var row = $('#dataTableBuilder').DataTable().row(tr);
    var tableId = 'tasks-' + row.data().id;

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        close_workspace_and_content();
        tr.removeClass('shown');
    } else {
        // Open this row
        row.child(task_details_format(row.data())).show();
        task_details_initTable(tableId, row.data());
        tr.addClass('shown');
        tr.next().find('td').addClass('no-padding bg-gray');
    }
}

function showlabeditor(element){
    $('#dataTableBuilder').DataTable().rows().every(function (rowIdx,tableLoop,rowLoop) {
        if (this.child.isShown()) {
            // This row is already open - close it
            this.child.hide();
            close_workspace_and_content();
            $(".shown").removeClass('shown');
        }
    });
    if(element!==0){
        var tr = $(element).closest('tr');
        var row = $('#dataTableBuilder').DataTable().row(tr);
        var labid = row.data().id;
    }else{
        var labid = 0
    }

    $("#labcontent").show();
    $('.editor-box').html('<form>\n' +
        '                                <p>Lab Name:</p>\n' +
        '                                <input id="labname" name="labname" type="text" value="" />\n' +
        '<p>Lab Catgory ID:</p>\n' +
        '                                <input id="labcatid" name="labcatid" type="text" value="" />\n' +
        '                                <p>Description:</p>\n' +
        '                                <textarea name="desc" id="desc" style="width: 80%"></textarea><br />' +
        '                                <p>Keywords(tags):</p>\n' +
        '                                <textarea name="tags" id="tags" style="width: 80%"></textarea><br />' +
        '<div class="col-md-8 checkbox">'+
        '<input type="checkbox" id="pdf-trigger" name="pdf-trigger" /></div><br /><br /><br /><br />'+
        '<div id="form-pdf-upload" style="display : none">'+
        '<p>PDF File:</p><button id="lab-add-pdf" type="button" class="btn btn-default btn-add-pdfs" onclick="labaddpdf('+labid+')"><i class="fa fa-plus"></i>Attach PDF file</button><br/>'+
        '<div id="lab-pdf-'+labid+'" style="width: 45px; border: 1px solid white;min-height: 45px; vertical-align: middle"></div>'+
        '</div><div id="form-manual-input" style="">'+
        '                                <p>Objects:</p>\n' +
        '                                <textarea name="objects" id="labdesceditor" style="width: 80%"></textarea>\n' +
        '<p>Attach file(s):</p><button id="lab-add-file" type="button" class="btn btn-default btn-add-file" onclick="labaddfile('+labid+')"><i class="fa fa-plus"></i>Attach File(s)</button><br/>'+
        '<div id="lab-file-'+labid+'" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>'+
        '                                <p>Estimated Time:</p>\n' +
        '                                <p>Expert: <input id="experttime" name="experttime" type="text" value="" /></p>\n' +
        '                                <p>Novice: <input id="time" name="time" type="text" value="" /></p>\n' +
        '                                <p>Difficulty:</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;">Time:</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;"><input id="difftime" name="difftime" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="difftime" name="difftime" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="difftime" name="difftime" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="difftime" name="difftime" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="difftime" name="difftime" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="difftime" name="difftime" type="radio" value="5" /> 5&nbsp;&nbsp;</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;">Design:</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;"><input id="diffdesign" name="diffdesign" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="diffdesign" name="diffdesign" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="diffdesign" name="diffdesign" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="diffdesign" name="diffdesign" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="diffdesign" name="diffdesign" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="diffdesign" name="diffdesign" type="radio" value="5" /> 5&nbsp;&nbsp;</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;">Implementation:</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;"><input id="diffimp" name="diffimp" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="diffimp" name="diffimp" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="diffimp" name="diffimp" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="diffimp" name="diffimp" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="diffimp" name="diffimp" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="diffimp" name="diffimp" type="radio" value="5" /> 5&nbsp;&nbsp;</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;">Configration:</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;"><input id="diffconf" name="diffconf" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="diffconf" name="diffconf" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="diffconf" name="diffconf" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="diffconf" name="diffconf" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="diffconf" name="diffconf" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="diffconf" name="diffconf" type="radio" value="5" /> 5&nbsp;&nbsp;</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;">Knowledge:</p>\n' +
        '                                <p style="text-align: left; padding-left: 30px;"><input id="diffknow" name="diffknow" type="radio" value="0" /> N/A &nbsp;&nbsp;<input id="diffknow" name="diffknow" type="radio" value="1" /> 1&nbsp;&nbsp; <input id="diffknow" name="diffknow" type="radio" value="2" /> 2 &nbsp;&nbsp;<input id="diffknow" name="diffknow" type="radio" value="3" /> 3&nbsp;&nbsp; <input id="diffknow" name="diffknow" type="radio" value="4" /> 4&nbsp;&nbsp; <input id="diffknow" name="diffknow" type="radio" value="5" /> 5&nbsp;&nbsp;</p>\n' +
        '                                <p>Required OS:</p>\n' +
        '                                <input id="os" name="os" type="text" value="" />\n' +
        '                                <p>Preparations:</p>\n' +
        '                                <textarea name="preparations" id="preparations" style="width: 80%"></textarea></div></form>'+
    '<button  type="button" class="btn btn-default pull-right" id="save-labcontent-btn" onclick="savelabcontent('+labid+')"><i class="fa fa-eye"></i>Save</button>');
    CKEDITOR.replace( 'labdesceditor' );
    CKEDITOR.instances.labdesceditor.on('change', function() {
        console.log("TESTlabdesc");
    });
    CKEDITOR.replace( 'desc' );
    CKEDITOR.instances.desc.on('change', function() {
        console.log("TESTdesc");
    });
    if(element!==0){
        $('#pdf-trigger').bootstrapSwitch({
            state: false,
            onColor: 'success',
            onText: 'PDF',
            offText: 'INPUT',
            labelText: 'Upload PDF file or<br/> Input Content Manually? ',
            labelWidth: 150,
            onSwitchChange: function (event, state) {
//			       console.log('switch state:' + state);
                if (state) {
                    $('#form-manual-input').hide();
                    $('#form-pdf-upload').show();
//                    $('.alert-success').find('ul').append('<li>The password must contain <ul><li>more than 6 characters</li>' +
//                        '<li>at-least 1 Uppercase</li><li>at-least 1 Lowercase</li><li>at-least 1 Numeric</li>' +
//                        '<li>at-least 1 special character</li></ul></li>');
                } else {
                    $('#form-manual-input').show();
                    $('#form-pdf-upload').hide();
//                    $('.alert-success').find('ul').find('li:last-child').remove();
                }
//                   event.preventDefault();
            }
        });
        $.getJSON("/getlabcontentbyid", {

            "labid": labid,


        }, function (data) {
            if(data[0].pdfflag==1){
                $('#pdf-trigger').bootstrapSwitch('animate', false);
                $('#pdf-trigger').bootstrapSwitch('state', true, false);
                $('#pdf-trigger').bootstrapSwitch('animate', true);
                $('#form-manual-input').hide();
                $('#form-pdf-upload').show();
            }
            document.getElementById('labname').value = data[0].name;
            document.getElementById('editorboxtitle').innerHTML = 'Editor: Lab '+data[0].name;
            document.getElementById('labcatid').value = data[0].lab_cat_id;
            document.getElementById('desc').value = data[0].description;
            CKEDITOR.instances.desc.setData(data[0].description);
            $('#desc').html( CKEDITOR.instances.desc.getData() );
            document.getElementById('tags').value = data[0].tags;
            //document.getElementById('labdesceditor').value = data[0].objects;
            CKEDITOR.instances.labdesceditor.setData(data[0].objects);
            $('#labdesceditor').html( CKEDITOR.instances.labdesceditor.getData() );
            document.getElementById('experttime').value = data[0].experttime;
            document.getElementById('time').value = data[0].time;
            var difficulty = JSON.parse(data[0].difficulty);
            $("input[name=difftime][value="+difficulty[0]+"]").attr('checked', true);
            $("input[name=diffdesign][value="+difficulty[1]+"]").attr('checked', true);
            $("input[name=diffimp][value="+difficulty[2]+"]").attr('checked', true);
            $("input[name=diffconf][value="+difficulty[3]+"]").attr('checked', true);
            $("input[name=diffknow][value="+difficulty[4]+"]").attr('checked', true);
            document.getElementById('os').value = data[0].os;
            document.getElementById('preparations').value = data[0].preparations;
            });
        $.getJSON("/getfilebylab", {

            "labcontent_id": labid,

        }, function (data) {
            for (var i = 0; i < data.length; i++) {
                if(data[i].desc!='pdf'){
                    var btn_tooltip = $('<button></button>').css('margin-left', '5pt').appendTo($('#lab-file-'+labid));

                    $('<i class="fa fa-file"></i>').appendTo(btn_tooltip);
                    var fullPath = data[i].fileurl;
                    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                    var filename = fullPath.substring(startIndex);
                    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                        filename = filename.substring(1);
                    }
                    var tooltip = '<div class="tooltip_content">'
                        + '<p>Filename:' + filename + '</p>'
                        + '<button class="btn btn-warning btn-file-tooltip btn-lab-'+labid+'" title="download file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].fileurl+'\',\'_blank\')"><i class="fa fa-download"></i></button>'
                        + '<button class="btn btn-danger btn-file-tooltip btn-lab-'+labid+'" title="delete file" onclick="lab_content_tooltip_deletefile('+labid+','+data[i].id+')"><i class="fa fa-trash"></i></button></div>';
                    btn_tooltip.addClass('btn btn-default btn-lab-'+labid).attr('title', tooltip).attr("id", "lab_"+labid+"_"+data[i].id).css('margin-top', '3px');;
                    $("#lab_"+labid+"_"+data[i].id).tooltipster(
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

                }else{
                    var btn_tooltip = $('<button></button>').css('margin-left', '5pt').appendTo($('#lab-pdf-'+labid));

                    $('<i class="fa fa-file"></i>').appendTo(btn_tooltip);
                    var fullPath = data[i].fileurl;
                    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                    var filename = fullPath.substring(startIndex);
                    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                        filename = filename.substring(1);
                    }
                    var tooltip = '<div class="tooltip_content">'
                        + '<p>Filename:' + filename + '</p>'
                        + '<button class="btn btn-success btn-file-tooltip btn-lab-'+labid+'" title="open file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu'+data[i].fileurl+'\',\'_blank\')"><i class="fa fa-file"></i></button>'
                        + '<button class="btn btn-danger btn-file-tooltip btn-lab-'+labid+'" title="delete file" onclick="lab_content_tooltip_deletepdf('+labid+','+data[i].id+')"><i class="fa fa-trash"></i></button></div>';
                    btn_tooltip.addClass('btn btn-default btn-lab-'+labid).attr('title', tooltip).attr("id", "lab_"+labid+"_"+data[i].id).css('margin-top', '3px');;
                    $("#lab_"+labid+"_"+data[i].id).tooltipster(
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
                    $('#lab-add-pdf').hide();
                }

            }

            $('.waiting-image-icon').remove();
        });
    }else{
        document.getElementById('editorboxtitle').innerHTML = 'Editor:New Lab';
        $('#pdf-trigger').bootstrapSwitch({
            state: false,
            onColor: 'success',
            onText: 'PDF',
            offText: 'INPUT',
            labelText: 'Upload PDF file or<br/> Input Content Manually? ',
            labelWidth: 150,
            onSwitchChange: function (event, state) {
//			       console.log('switch state:' + state);
                if (state) {
                    $('#form-manual-input').hide();
                    $('#form-pdf-upload').show();
//                    $('.alert-success').find('ul').append('<li>The password must contain <ul><li>more than 6 characters</li>' +
//                        '<li>at-least 1 Uppercase</li><li>at-least 1 Lowercase</li><li>at-least 1 Numeric</li>' +
//                        '<li>at-least 1 special character</li></ul></li>');
                } else {
                    $('#form-manual-input').show();
                    $('#form-pdf-upload').hide();
//                    $('.alert-success').find('ul').find('li:last-child').remove();
                }
//                   event.preventDefault();
            }
        });
    }
    $('#labcontent').removeClass('col-md-6 ');
    $('#labcontent').addClass('col-md-12 ');

}

function showtaskeditor(element,labid){

    if(element!==0){
        var tr = $(element).closest('tr');
        var row = $('#tasks-'+labid).DataTable().row(tr);
        var taskid = row.data().id;
    }else{
        var taskid = 0
    }

    $("#labcontent").show();
    $('#labcontent').removeClass('col-md-6 ');
    $('#labcontent').addClass('col-md-12 ');
    $('.editor-box').html('<form>\n' +


        '                                <h5>Task Name: <br/><input  id="taskname" name="taskname" type="text" value="" /></h5>\n' +

        '                                <h5>Description :</h5>\n' +
        '                                <textarea name="taskdesceditor" id="taskdesceditor">This is some sample content.</textarea>\n' +
        '                                <h5>Submission Type :</h5>\n' +
        '<input type="checkbox" id="screenshots" name="screenshots" value="screenshots">Screenshots<br>\n' +
        '<input type="checkbox" id="files" name="files" value="files">Files<br>\n' +
        '<input type="checkbox" id="texts" name="texts" value="texts">Texts<br>'+
        '<div id="submissionbox"></div>'+
        '<button  type="button" class="btn btn-default pull-right  btn-task-112" onclick="savelabtask('+taskid+','+labid+')"><i class="fa fa-eye"></i>Save</button>');
    CKEDITOR.replace( 'taskdesceditor');
    CKEDITOR.instances.taskdesceditor.on('change', function() {
        console.log("TESTtaskdesc");
    });

    // ClassicEditor
    //     .create( document.querySelector( '#taskdesceditor') )
    //     .then( editor => {
    //         console.log( 'Editor was initialized', editor );
    //         myEditor = editor;
    //     } )
    //     .catch( err => {
    //         console.error( err.stack );
    //     } );

    if(element!==0){

        $.getJSON("/getlabtaskbyid", {

            "taskid": taskid,


        }, function (data) {
            document.getElementById('taskname').value = data[0].name;
            document.getElementById('editorboxtitle').innerHTML = 'Editor: Task '+data[0].name;
            //myEditor.setData(data[0].content);
            CKEDITOR.instances.taskdesceditor.setData(data[0].content);
            $('#taskdesceditor').html( CKEDITOR.instances.taskdesceditor.getData() );



            var submission = JSON.parse(data[0].submission);
            if(submission[0]=='true'){
                $("input[name=screenshots]").attr('checked', true);
            }
            if(submission[1]=='true'){
                $("input[name=files]").attr('checked', true);
            }
            if(submission[2]=='true'){
                $("input[name=texts]").attr('checked', true);
            }



        });
    }

}
// function submission_details_format(data) {
//     return '<div style="margin-left: 10px; margin-right: 10px;"><h4>Task ' + data.name + '\'s Submissions</h4>' +
//         '<table class="table details-table table-condensed" id="submissions-' + data.id + '" cellpadding="0" border="0" width="100%">' +
//         '<thead>' +
//         '<tr>' +
//         //'<th></th>' +
//         '<th>Submission ID</th>' +
//         '<th>Name</th>' +
//         '<th>Description</th>' +
//         '<th>Type</th>' +
//         '<th>Action</th>' +
//         '</tr>' +
//         '</thead>' +
//         '</table></div>';
// }
//
// function submission_details_initTable(tableId, data) {
//     var table = $('#' + tableId).DataTable({
//         dom: '<"toolbar">',
//         // buttons: ['csv', 'print', 'reload'],
//         destroy: true,
//         processing: true,
//         serverSide: true,
//         // scrollX: true,
//         // scrollCollapse: true,
//         // scroller: true,
//         ajax: data.submission_details_url,
//         columnDefs: [
//             // {
//             //     targets: 0,
//             //     defaultContent:'',
//             //     searchable:false,
//             //     orderable:false,
//             //     className: 'dt-body-center details-control',
//             //     // render: function (data, type, full, meta){
//             //     //     return '<input type="checkbox" name="' + tableId + '-members[]" value="'
//             //     //         + $('<div/>').text(data).html() + '">';
//             //     // }
//             // },
//             {
//                 targets: 4,
//                 searchable:false,
//                 orderable:false,
//                 render: function (data, type, full, meta) {
//                     return '<div class="btn-group">' +
//                         '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
//                         'Action <span class="caret"></span>' +
//                         '</button>' +
//                         '<ul class="dropdown-menu" style="min-width: 10px;">' +
//                         '<li><a href="#" onclick="group_management_edit($(this))">Edit Submission</a></li>' +
//                         '<li role="separator" class="divider"></li>' +
//                         '<li><a href="#" onclick="" style="color:red">Remove</a></li>' +
//                         '</ul>' +
//                         '</div>';
//                 }
//             }],
//         columns: [
//
//
//             { data: "id", name: "id" },
//             { data: "name", name: "name" },
//             { data: "description", name: "description"},
//             { data: "type", name: "type" },
//         ]
//     });
//
//     $('#'+tableId+'_wrapper').children('div.toolbar').html('<span style="margin-left: 20px;">' +
//         '<button type="button" class="btn btn-primary" style="margin-left: 5px" onclick="modal_group_add_members($(this))">Add New Submission</button></span>');
//         //
//         // '<button type="button" class="btn btn-danger" style="margin-left: 5px" onclick="group_members_delete($(this))">Remove Members</button>' +
//         // '<button type="button" class="btn btn-info" style="margin-left: 5px" onclick="group_members_change_roles($(this))">Change Role</button></span>');
// }
//
// function loadsubmission(element) {
//     var tr = $(element).closest('tr');
//     var row = $(element).closest('table').DataTable().row(tr);
//     var tableId = 'submissions-' + row.data().id;
//
//     if (row.child.isShown()) {
//         // This row is already open - close it
//         row.child.hide();
//         tr.removeClass('shown');
//     } else {
//         // Open this row
//         row.child(submission_details_format(row.data())).show();
//         submission_details_initTable(tableId, row.data());
//         tr.addClass('shown');
//         tr.next().find('td').addClass('no-padding bg-gray');
//     }
// }

function loadlabcontent(labcontentid,userid,subgroupid,labid) {

    var countDownDate;
    var taskid=[];
    var countDownDateString='';
    //var labname =row.name;
    var labcontent=
        // '<h2 class="box-title">Intro:</h2>\n' +
        '    <p id="title-labcatid"><b>Lab Catgory ID:</b>&nbsp&nbsp<span id="preview-labcatid"></span></p>\n' +
        '\n' +

        '    <p id="title-tags"><b>Keywords</b>(tags): </p>\n' +
        '\n' +
        '    <p id="preview-tags"></p>\n' +
        '\n' +
        '    <p id="pdf"></p>\n' +
        '\n' +
        '    <p id="title-files"><b>Files</b>: </p>\n' +
        '\n' +
        '<div class="lab-files" id="lab-file-'+labcontentid+'" style="border: 1px solid white; vertical-align: middle"></div>' +
        '    <p id="title-desc"><b>Description</b>: </p>\n' +
        '\n' +
        '    <p id="preview-desc"></p>\n' +
        '\n' +
        '    <p id="title-objects"><b>Objects</b>: </p>\n' +
        '\n' +
        '    <p id="preview-objects"></p>\n' +
        '\n' +
        '    <p class="title-time"><b>Estimated Time</b>: </p>\n' +
        '\n' +
        '    <p class="title-time">Expert: <span id="preview-experttime"></span></p>\n' +
        '\n' +
        '    <p class="title-time">Novice: <span id="preview-time"></span></p>\n' +
        '\n' +
        '    <p class="title-diff"><b>Difficulty</b>:(Level 1 to 5, lower is easier, 0 means N/A) </p>\n' +
        '<div class="title-diff"><div id="body"><div id="chart"></div></div></div>' +
        '    <p class="title-diff">Time: Level <span id="preview-difftime"></span></p>\n' +
        '    <p class="title-diff">Design: Level <span id="preview-diffdesign"></span></p>\n' +
        '    <p class="title-diff">Implementation: Level <span id="preview-diffimp"></span></p>\n' +
        '    <p class="title-diff">Configration: Level <span id="preview-diffconf"></span></p>\n' +
        '    <p class="title-diff">Knowledge: Level <span id="preview-diffknow"></span></p>\n' +

        '    <p id="title-os"><b>Required OS</b>: </p>\n' +
        '    <p id="preview-os"></p>\n' +
        '    <p id="title-preparations"><b>Preparations</b>: </p>\n' +
        '    <p id="preview-preparations"></p><div id="tasks-box"></div>';


    var div = $('#labbox');

    div.find('#lab-content').empty().html(labcontent);
    $.getJSON("/getlabinfobyid", {

        "labid": labid,
        "labcontentid": labcontentid


    }, function (data) {

        countDownDateString=new Date(data[0].due_at);
        var offset = new Date().getTimezoneOffset();
        offset /= 60;
        countDownDateString.setHours( countDownDateString.getHours() - offset);
        countDownDate=countDownDateString.getTime();
        div.find('.dueon').empty().html(countDownDateString);
    });

    $.getJSON("/getlabcontentbyid", {

        "labid": labcontentid,


    }, function (data) {
        $('#lab-name').empty().html(data[0].name);
        // div.find('#lab-submission').empty().html('<button id="finaltask" type="button" class="btn btn-default btn-success pull-right  btn-task-103" onclick="finish_lab('+labid+')"><i class="fa fa-share"></i>Complete '+data[0].name+'</button><br/><br/>'+
        //     '<span class="label label-warning pull-right" style="font-size:100%" id="countdownall">You\'ve got <span id="countdown"></span> left!</span>' +
        //     '<br/><b style="color: red;" class="pull-right dueon" > Due on '+countDownDateString+'</b>'
        //     // '<button id="viewreport" type="button" class="btn btn-default pull-right  btn-task-103" onclick="view_report(103)"><i class="fa fa-eye"></i>Pre-view Report</button>\n'
        // );
        // var x = setInterval(function() {
        //
        //         // Get todays date and time
        //         var now = new Date().getTime();
        //
        //         // Find the distance between now and the count down date
        //         var distance = countDownDate - now;
        //
        //         // Time calculations for days, hours, minutes and seconds
        //         var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        //         var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        //         var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        //         var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        //
        //
        //         // Output the result in an element with id="demo"
        //         if ($("#countdown").length) {
        //             document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
        //                 + minutes + "m " + seconds + "s ";
        //         }
        //
        //         if (distance < 0) {
        //             if (document.getElementById("countdownall").innerHTML != "Submitted") {
        //                 document.getElementById("countdownall").innerHTML = "EXPIRED";
        //             }
        //
        //         }
        //
        // }, 1000);
        div.find('#preview-labcatid').html(data[0].lab_cat_id);
        if(data[0].description){
            div.find('#preview-desc').html(data[0].description);
        }else{
            div.find('#preview-desc').empty();
            div.find('#title-desc').empty();
        }
        if(data[0].tags){
            div.find('#preview-tags').html(data[0].tags);
        }else{
            div.find('#preview-tags').empty();
            div.find('#title-tags').empty();
        }
        if(data[0].pdfflag==1){
            if(data[0].lab_cat_id==="FAQ-00101"){
                div.find('#pdf').empty().html(
                    '<iframe  src="https://www.thothlab.com/doc/FAQ.pdf"  width="97%" height="900">'
                );
            }else{
                div.find('#pdf').empty().html(
                    '<iframe  src="https://submissions.storage.mobicloud.asu.edu'+data[0].pdfurl+'"  width="97%" height="900">'
                );
            }

            div.find('#preview-objects').empty();
            div.find('#title-objects').empty();
            div.find('.title-time').empty();
            div.find('.title-diff').empty();
            div.find('#preview-os').empty();
            div.find('#title-os').empty();
            div.find('#preview-preparations').empty();
            div.find('#title-preparations').empty();
            div.find('#title-files').empty();
            div.find('.lab-files').height(1);
        }else{
            if(data[0].objects){
                div.find('#preview-objects').html(data[0].objects);
            }else{
                div.find('#preview-objects').empty();
                div.find('#title-objects').empty();
            }
            if(data[0].experttime||data[0].time){
                div.find('#preview-experttime').html( data[0].experttime);
                div.find('#preview-time').html(data[0].time);
            }else{
                div.find('.title-time').empty();
            }

            if(data[0].difficulty){
                var difficulty = JSON.parse(data[0].difficulty);
                var zeros=["0","0","0","0","0"];
                if(difficulty[0]==0){
                    div.find('#preview-difftime').html('N/A');
                }else{
                    div.find('#preview-difftime').html(difficulty[0]);
                }
                if(difficulty[1]==0){
                    div.find('#preview-diffdesign').html('N/A');
                }else{
                    div.find('#preview-diffdesign').html(difficulty[1]);
                }
                if(difficulty[2]==0){
                    div.find('#preview-diffimp').html('N/A');
                }else{
                    div.find('#preview-diffimp').html(difficulty[2]);
                }
                if(difficulty[3]==0){
                    div.find('#preview-diffconf').html('N/A');
                }else{
                    div.find('#preview-diffconf').html(difficulty[3]);
                }
                if(difficulty[4]==0){
                    div.find('#preview-diffknow').html('N/A');
                }else{
                    div.find('#preview-diffknow').html(difficulty[4]);
                }
                if(arraysEqual(difficulty,zeros)){
                    div.find('.title-diff').empty();
                }
                var w = 200,
                    h = 200;

                var colorscale = d3.scale.category10();

//Legend titles


//Data
                var d = [
                    [
                        {axis:"Time",value:difficulty[0]},
                        {axis:"Design",value:difficulty[1]},
                        {axis:"Implementation",value:difficulty[2]},
                        {axis:"Configuration",value:difficulty[3]},
                        {axis:"Knowledge",value:difficulty[4]}
                    ]
                ];

//Options for the Radar chart, other than default
                var mycfg = {
                    w: w,
                    h: h,
                    maxValue: 6,
                    levels: 6,
                    ExtraWidthX: 300
                }

//Call function to draw the Radar chart
//Will expect that data is in %'s
                RadarChart.draw("#chart", d, mycfg);

////////////////////////////////////////////
/////////// Initiate legend ////////////////
////////////////////////////////////////////

                var svg = d3.select('#body')
                    .selectAll('svg')
                    .append('svg')
                    .attr("width", w+300)
                    .attr("height", h)

//Create the title for the legend
//             var text = svg.append("text")
//                 .attr("class", "title")
//                 .attr('transform', 'translate(90,0)')
//                 .attr("x", w - 70)
//                 .attr("y", 10)
//                 .attr("font-size", "12px")
//                 .attr("fill", "#404040")
//                 .text("What % of owners use a specific service in a week");

//Initiate Legend
                var legend = svg.append("g")
                    .attr("class", "legend")
                    .attr("height", 100)
                    .attr("width", 200)
                    .attr('transform', 'translate(90,20)')
                ;
                // //Create colour squares
                // legend.selectAll('rect')
                //     .data(LegendOptions)
                //     .enter()
                //     .append("rect")
                //     .attr("x", w - 65)
                //     .attr("y", function(d, i){ return i * 20;})
                //     .attr("width", 10)
                //     .attr("height", 10)
                //     .style("fill", function(d, i){ return colorscale(i);})
                // ;
                // //Create text next to squares
                // legend.selectAll('text')
                //     .data(LegendOptions)
                //     .enter()
                //     .append("text")
                //     .attr("x", w - 52)
                //     .attr("y", function(d, i){ return i * 20 + 9;})
                //     .attr("font-size", "11px")
                //     .attr("fill", "#737373")
                //     .text(function(d) { return d; })
                // ;

            }else{
                div.find('.title-diff').empty();
            }

            // $("input[name=difftime][value="+difficulty[0]+"]").attr('checked', true);
            // $("input[name=diffdesign][value="+difficulty[1]+"]").attr('checked', true);
            // $("input[name=diffimp][value="+difficulty[2]+"]").attr('checked', true);
            // $("input[name=diffconf][value="+difficulty[3]+"]").attr('checked', true);
            // $("input[name=diffknow][value="+difficulty[4]+"]").attr('checked', true);
            if(data[0].os){
                div.find('#preview-os').html(data[0].os);
            }else{
                div.find('#preview-os').empty();
                div.find('#title-os').empty();
            }
            if(data[0].preparations){
                div.find('#preview-preparations').html(data[0].preparations);
            }else{
                div.find('#preview-preparations').empty();
                div.find('#title-preparations').empty();
            }
            $.getJSON("/getfilebylab", {

                "labcontent_id": labcontentid,

            }, function (data) {
                if(data.length==0){
                    div.find('#title-files').empty();
                    div.find('#lab-file-'+labcontentid).empty();
                }else {
                    for (var i = 0; i < data.length; i++) {

                        var btn_tooltip = $('<button></button>').css('margin-left', '5pt').appendTo($('#lab-file-' + labcontentid));

                        $('<i class="fa fa-file"></i>').appendTo(btn_tooltip);
                        var fullPath = data[i].fileurl;
                        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                        var filename = fullPath.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }
                        var tooltip = '<div class="tooltip_content">'
                            + '<p>Filename:' + filename + '</p>'
                            + '<button class="btn btn-warning btn-file-tooltip btn-lab-' + labcontentid + '" title="download file" onclick="window.open(\'https://submissions.storage.mobicloud.asu.edu' + data[i].fileurl + '\',\'_blank\')"><i class="fa fa-download"></i></button>';
                        btn_tooltip.addClass('btn btn-default btn-lab-' + labid).attr('title', tooltip).attr("id", "lab_" + labcontentid + "_" + data[i].id).css('margin-top', '3px');
                        ;
                        $("#lab_" + labcontentid + "_" + data[i].id).tooltipster(
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
                    }
                }

                $('.waiting-image-icon').remove();
            });
        }


    });

    var taskcount;
    $.getJSON("/getlabtaskbylab", {

        "labid": labcontentid,


    }, function (data) {
        taskcount=data.length;
        for (var i = 0; i < data.length; i++) {
            var tasktitle = $('<h3 id="task_'+data[i].id+'">Task: '+data[i].name+'</h3>').appendTo($('#tasks-box'));
            var taskcontent = $('<p><b>Task Desription:</b></p>'+data[i].content).appendTo($('#tasks-box'));
            var submission=JSON.parse(data[i].submission);
            if(submission[0]=='true'||submission[1]=='true'||submission[2]=='true'){
                $('<button type="button" class="btn btn-default btn-info btn-task-'+data[i].id+'"  id="startsubmission_'+data[i].id+'" onclick="groupopensubmission('+data[i].id+','+userid+','+subgroupid+')"><i class="fa fa-plus"></i> Open Submission</button><b class="dueon1" style="color: red;"> Due on</b> <b class="dueon" style="color: red;">'+countDownDateString+'</b>').appendTo($('#tasks-box'));
                var tasksubmission= $('<div id="submission_'+data[i].id+'" class="modal-body" style="display: none"><span class="label label-warning countdownall'+data[i].id+'" style="font-size:100%" id="countdownall'+i+'">You\'ve got <span id="countdown'+i+'"></span> left!</span><br/><form id="submission_form_'+data[i].id+'"></form> <br/><br/> <button id="finaltask" type="button" class="btn btn-default btn-success pull-right finaltask  btn-task-'+data[i].id+'" onclick="finish_task('+data[i].id+')"><i class="fa fa-share"></i>Complete Task</button>\n' +
                    '                            <button id="viewreport" type="button" class="btn btn-default pull-right viewreport btn-task-'+data[i].id+'" onclick="view_report('+data[i].id+','+subgroupid+')"><i class="fa fa-eye"></i>Pre-view Report</button></div>').appendTo($('#tasks-box'));
            }
            if(submission[0]=='true'){
                var tasksubmission= $('<p>Take screenshot(s):</p><button id="sub_'+data[i].id+'_1" type="button" class="btn btn-default btn-add-screenshot btn-task-'+data[i].id+'" onclick="grouptakescreenshot('+data[i].id+','+data[i].labid+',1,\'Server\','+subgroupid+')"><i class="fa fa-plus"></i>Add Screenshot and Explanation</button><br><div id="submission-task'+data[i].id+'-1" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>').appendTo($('#submission_form_'+data[i].id));
            }
            if(submission[1]=='true'){
                var tasksubmission= $('<p>Upload file(s):</p><button id="sub_'+data[i].id+'_2" type="button" class="btn btn-default btn-add-file btn-task-'+data[i].id+'" onclick="groupaddfile('+data[i].id+','+data[i].labid+',2,'+subgroupid+')"><i class="fa fa-plus"></i>Upload File</button><br><div id="submission-task'+data[i].id+'-2" style="border: 1px solid lightblue;min-height: 45px; vertical-align: middle"></div>').appendTo($('#submission_form_'+data[i].id));
            }
            if(submission[2]=='true'){
                var tasksubmission= $('<p>Input answer or description:</p>  <textarea id="submission-task-'+data[i].id+'-'+subgroupid+'-3" class="text-editor" style="border: 1px solid lightblue; min-height: 45px; vertical-align: middle; min-width: 500px;"></textarea>').appendTo($('#submission_form_'+data[i].id));
                $('<button id="upload-'+data[i].id+'-text" type="button" class="btn btn-default btn-primary pull-right btn-upload-text btn-task-'+data[i].id+'" onclick="upload_text('+data[i].id+','+subgroupid+')"><i class="fa fa-edit"></i>Save Answer</button>').appendTo($('#submission_form_'+data[i].id));
                CKEDITOR.replace( 'submission-task-'+data[i].id+'-'+subgroupid+'-3');
                taskid.push(data[i].id);
                CKEDITOR.instances['submission-task-'+data[i].id+'-'+subgroupid+'-3'].on('change', function(e) {
                    var str=JSON.stringify(e.editor.name);
                    var paras=str.split("-");
                    var taskid=paras[2];
                    // var subgroupid=paras[3];
                    $("#upload-"+taskid+"-text").prop("disabled",false);
                    // if(Date.now() - lastMove > 60000) {
                    //
                    //     if(lastMove==0){
                    //         console.log("first");
                    //         first_autosave_text(taskid, subgroupid);
                    //         lastMove = Date.now();
                    //     }else{
                    //         console.log("TEST");
                    //         autosave_text(taskid, subgroupid);
                    //         lastMove = Date.now();
                    //     }
                    // }
                });
            }
            //countDownDate[i] = new Date("Dec 2, 2019 23:59:59 GMT-07:00 ").getTime();


        }

        setInterval(function() {
            for (var i = 0; i < taskid.length; i++) {
                if (CKEDITOR.instances['submission-task-' + taskid[i] + '-' + subgroupid + '-3'].checkDirty()) {
                    console.log(i);
                    first_autosave_text(taskid[i], subgroupid);
                    $("#upload-"+taskid[i]+"-text").prop("disabled",true);
                    CKEDITOR.instances['submission-task-' + taskid[i] + '-' + subgroupid + '-3'].resetDirty()
                }
            }
        }, 60 * 1000,taskid,subgroupid);
    });

    var x = setInterval(function() {
        for (var i = 0; i <taskcount; i++) {
            // Get todays date and time
            var now = new Date().getTime();
            var pageCountDownDateString= div.find('.dueon').first().text();
            var countDownDate1=new Date(pageCountDownDateString).getTime();
            // Find the distance between now and the count down date
            var distance = countDownDate1 - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);


            // Output the result in an element with id="demo"
            if ($("#countdown" + i).length) {
                document.getElementById("countdown" + i).innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";
            }

            if (distance < 0) {
                if (document.getElementById("countdownall" + i).innerHTML != "Submitted") {
                    document.getElementById("countdownall" + i).innerHTML = "EXPIRED";
                    $(".btn-add-file").hide();
                    $(".btn-add-screenshot").hide();
                     $(".btn-upload-text").hide();
                    $('.text-editor').prop('disabled', true);
                    $(".viewreport").html('<i class="fa fa-eye"></i>View Report');
                    $(".finaltask").prop('disabled', true);
                    $(".finaltask").html('Task Completed');


                }

            }
        }
    }, 1000);


    $('#float-feedback-form').attr('onsubmit', 'openfeedbackfrom('+labcontentid+','+subgroupid+','+labid+'); return false;');

}


function previewlab(element) {
    var tr = $(element).closest('tr');
    var row = $('#dataTableBuilder').DataTable().row(tr).data();
    var labid = row.id;
    var labname =row.name;
    var labcontent= '<h2 class="box-title">Intro:</h2>\n' +
        '    <p><p><b>Lab Catgory ID:</b></p>\n' +
        '\n' +
        '    <p id="preview-labcatid"></p>\n' +
        '\n' +
        '    <p><b>Keywords</b>(tags): </p>\n' +
        '\n' +
        '    <p id="preview-tags"></p>\n' +
        '\n' +
        '    <p id="pdf"></p>\n' +
        '\n' +
        '    <p id="title-objects"><b>Objects</b>: </p>\n' +
        '\n' +
        '    <p id="preview-objects"></p>\n' +
        '\n' +
        '    <p class="title-time"><b>Estimated Time</b>: </p>\n' +
        '\n' +
        '    <p class="title-time">Expert: <span id="preview-experttime"></span></p>\n' +
        '\n' +
        '    <p class="title-time">Novice: <span id="preview-time"></span></p>\n' +
        '\n' +
        '    <p class="title-diff"><b>Difficulty</b>:(Level 1 to 5, lower is easier) </p>\n' +
        '<div class="title-diff"><div id="body"><div id="chart"></div></div></div>' +
        '    <p class="title-diff">Time: Level <span id="preview-difftime"></span></p>\n' +
        '    <p class="title-diff">Design: Level <span id="preview-diffdesign"></span></p>\n' +
        '    <p class="title-diff">Implementation: Level <span id="preview-diffimp"></span></p>\n' +
        '    <p class="title-diff">Configration: Level <span id="preview-diffconf"></span></p>\n' +
        '    <p class="title-diff">Knowledge: Level <span id="preview-diffknow"></span></p>\n' +
        '    <p id="title-os"><b>Required OS</b>: </p>\n' +
        '    <p id="preview-os"></p>\n' +
        '    <p id="preview-prepare"><b>Preparations</b>: </p>\n' +
        '    <p id="preview-preparations"></p><br/><div id="tasks-box"></div>';


    var modal = $('#modal-preview-lab');
    modal.find('#lab-name').empty().html(labname);
    modal.find('#lab-content').empty().html(labcontent);
    $.getJSON("/getlabcontentbyid", {

        "labid": labid,


    }, function (data) {
        modal.find('#preview-labcatid').html(data[0].lab_cat_id);
        modal.find('#preview-tags').html(data[0].tags);
        if(data[0].pdfflag==1){
            modal.find('#pdf').empty().html(
                '<iframe  src="https://submissions.storage.mobicloud.asu.edu'+data[0].pdfurl+'"  width="97%" height="900">'
            );
            modal.find('#preview-objects').empty();
            modal.find('#title-objects').empty();
            modal.find('.title-time').empty();
            modal.find('.title-diff').empty();
            modal.find('#preview-os').empty();
            modal.find('#title-os').empty();
            modal.find('#preview-prepare').empty();
            modal.find('#title-preparations').empty();

        }else{
            modal.find('#preview-objects').html(data[0].objects);
            modal.find('#preview-experttime').html( data[0].experttime);
            modal.find('#preview-time').html(data[0].time);

            if(data[0].difficulty){
                var difficulty = JSON.parse(data[0].difficulty);
                var zeros=["0","0","0","0","0"];
                if(difficulty[0]==0){
                    modal.find('#preview-difftime').html('N/A');
                }else{
                    modal.find('#preview-difftime').html(difficulty[0]);
                }
                if(difficulty[1]==0){
                    modal.find('#preview-diffdesign').html('N/A');
                }else{
                    modal.find('#preview-diffdesign').html(difficulty[1]);
                }
                if(difficulty[2]==0){
                    modal.find('#preview-diffimp').html('N/A');
                }else{
                    modal.find('#preview-diffimp').html(difficulty[2]);
                }
                if(difficulty[3]==0){
                    modal.find('#preview-diffconf').html('N/A');
                }else{
                    modal.find('#preview-diffconf').html(difficulty[3]);
                }
                if(difficulty[4]==0){
                    modal.find('#preview-diffknow').html('N/A');
                }else{
                    modal.find('#preview-diffknow').html(difficulty[4]);
                }
                if(arraysEqual(difficulty,zeros)){
                    modal.find('.title-diff').empty();
                }
                var w = 200,
                    h = 200;

                var colorscale = d3.scale.category10();

//Legend titles


//Data
                var d = [
                    [
                        {axis:"Time",value:difficulty[0]},
                        {axis:"Design",value:difficulty[1]},
                        {axis:"Implementation",value:difficulty[2]},
                        {axis:"Configuration",value:difficulty[3]},
                        {axis:"Knowledge",value:difficulty[4]}
                    ]
                ];

//Options for the Radar chart, other than default
                var mycfg = {
                    w: w,
                    h: h,
                    maxValue: 6,
                    levels: 6,
                    ExtraWidthX: 300
                }

//Call function to draw the Radar chart
//Will expect that data is in %'s
                RadarChart.draw("#chart", d, mycfg);

////////////////////////////////////////////
/////////// Initiate legend ////////////////
////////////////////////////////////////////

                var svg = d3.select('#body')
                    .selectAll('svg')
                    .append('svg')
                    .attr("width", w+300)
                    .attr("height", h)

//Create the title for the legend
//             var text = svg.append("text")
//                 .attr("class", "title")
//                 .attr('transform', 'translate(90,0)')
//                 .attr("x", w - 70)
//                 .attr("y", 10)
//                 .attr("font-size", "12px")
//                 .attr("fill", "#404040")
//                 .text("What % of owners use a specific service in a week");

//Initiate Legend
                var legend = svg.append("g")
                    .attr("class", "legend")
                    .attr("height", 100)
                    .attr("width", 200)
                    .attr('transform', 'translate(90,20)')
                ;
                // //Create colour squares
                // legend.selectAll('rect')
                //     .data(LegendOptions)
                //     .enter()
                //     .append("rect")
                //     .attr("x", w - 65)
                //     .attr("y", function(d, i){ return i * 20;})
                //     .attr("width", 10)
                //     .attr("height", 10)
                //     .style("fill", function(d, i){ return colorscale(i);})
                // ;
                // //Create text next to squares
                // legend.selectAll('text')
                //     .data(LegendOptions)
                //     .enter()
                //     .append("text")
                //     .attr("x", w - 52)
                //     .attr("y", function(d, i){ return i * 20 + 9;})
                //     .attr("font-size", "11px")
                //     .attr("fill", "#737373")
                //     .text(function(d) { return d; })
                // ;

            }else{
                modal.find('.title-diff').empty();
            }

            // $("input[name=difftime][value="+difficulty[0]+"]").attr('checked', true);
            // $("input[name=diffdesign][value="+difficulty[1]+"]").attr('checked', true);
            // $("input[name=diffimp][value="+difficulty[2]+"]").attr('checked', true);
            // $("input[name=diffconf][value="+difficulty[3]+"]").attr('checked', true);
            // $("input[name=diffknow][value="+difficulty[4]+"]").attr('checked', true);
            //     modal.find('#preview-difftime').html(difficulty[0]);
            //     modal.find('#preview-diffdesign').html(difficulty[1]);
            //     modal.find('#preview-diffimp').html(difficulty[2]);
            //     modal.find('#preview-diffconf').html(difficulty[3]);
            //     modal.find('#preview-diffknow').html(difficulty[4]);
            // $("input[name=difftime][value="+difficulty[0]+"]").attr('checked', true);
            // $("input[name=diffdesign][value="+difficulty[1]+"]").attr('checked', true);
            // $("input[name=diffimp][value="+difficulty[2]+"]").attr('checked', true);
            // $("input[name=diffconf][value="+difficulty[3]+"]").attr('checked', true);
            // $("input[name=diffknow][value="+difficulty[4]+"]").attr('checked', true);
            modal.find('#preview-os').html(data[0].os);
            if(data[0].preparations=='N/A'||data[0].preparations==''){
                modal.find('#preview-preparations').empty();
                modal.find('#preview-prepare').empty();
            }else{
                modal.find('#preview-preparations').html(data[0].preparations);
            }
        }


    });
    $.getJSON("/getlabtaskbylab", {

        "labid": labid,


    }, function (data) {
        for (var i = 0; i < data.length; i++) {
            var tasktitle = $('<h3 id="task_'+data[i].id+'">Task: '+data[i].name+'</h3>').appendTo($('#tasks-box'));
            var taskcontent = $('<p><b>Task Description:</b></p>'+data[i].content).appendTo($('#tasks-box'));
            var submission=JSON.parse(data[i].submission);
            if(submission[0]=='true'||submission[1]=='true'||submission[2]=='true'){
                var tasksubmission= $('<h5 id="submission_'+data[i].id+'">Submissions:</h5><form id="submission_form_'+data[i].id+'"></form>').appendTo($('#tasks-box'));
            }
            if(submission[0]=='true'){
                var tasksubmission= $('<p>Take screenshot(s):</p>  <div id="submission-task'+data[i].id+'-1" style="border: 1px solid lightblue; min-height: 45px; vertical-align: middle;">Submission Area</div>').appendTo($('#submission_form_'+data[i].id));
            }
            if(submission[1]=='true'){
                var tasksubmission= $('<p>Upload file(s):</p>  <div id="submission-task'+data[i].id+'-1" style="border: 1px solid lightblue; min-height: 45px; vertical-align: middle;">Submission Area</div>').appendTo($('#submission_form_'+data[i].id));
            }
            if(submission[2]=='true'){
                var tasksubmission= $('<p>Input answer or description:</p>  <div id="submission-task'+data[i].id+'-1" style="border: 1px solid lightblue; min-height: 45px; vertical-align: middle;">Submission Area</div>').appendTo($('#submission_form_'+data[i].id));
            }

        }
    });


    modal.modal('show');
}

function loadlabenvlist(){
    var table = $('#deployed_lab_env').DataTable({
        pageLength: 5,
        dom: '',
        // lengthMenu: [ [10, 15, 20, 25, 50, -1], [10, 15, 20, 25, 50, "All"] ],
        destroy: true,
        paging: true,
        processing: true,
        serverSide: true,
        ajax: $('#url').text(),
        columnDefs: [
            {
                targets: 3,
                searchable:false,
                orderable:false,
                render: function (data, type, full, meta) {
                    return '<button type="button" class="btn btn-default" onclick="loaddeployedlab($(this));">' +
                        'Open' +
                        '</button>';
                }
            }],
        columns: [
            {data: "project_id","visible": false},
            {data: "name"},// name: "users.name"},
            {data: "description"},// name: "users.email"},

        ]
    });
}

function loaddeployedlab(element){
    var tr = $(element).closest('tr');
    var row = $('#deployed_lab_env').DataTable().row(tr).data();
    var projectid = row.project_id;

    $('#workspacelabname').html(row.name);
    document.getElementById("vis-reload").onclick = function () { vis_canvas_redraw(projectid); };
    // document.getElementById("btn-vis-refresh").onclick = function () { vis_canvas_load_network_topology(projectid); };
    // document.getElementById("btn-vis-openallconsole").onclick = function () { vis_canvas_open_all_consoles(projectid); };
    document.getElementById("labenvnav").innerHTML= '<li class="active"><a id="vis-reload" href="#lab-env-topology-tab" data-toggle="tab" onclick="vis_canvas_redraw();">Topology</a></li>\n' +
        '                            <li><a href="#lab-env-topology-tab" id="demo" data-toggle="tab">Timer</a></li>';
    document.getElementById("labenvtab").innerHTML= ' <div class="active tab-pane" id="lab-env-topology-tab" >\n' +
        '                                <div class="box-body" >\n' +
        '                                    <table width="100%">\n' +
        '                                        <thead><tr><th>\n' +
        '                                                <button id="btn-vis-refresh" title="Refresh Topology" disabled style="float: left" onclick="vis_canvas_load_network_topology(\''+projectid+'\')"><i class="fa fa-refresh"></i></button>\n' +
        '                                                <p id="vis-refresh-loading" style="float:left; display: none;"> &nbsp; &nbsp; Loading...</p>\n' +
        '                                                <button id="btn-vis-openallconsole" title="Open All Consoles" style="float: left; display: none; background-color: lightblue" onclick="vis_canvas_open_all_consoles(\''+projectid+'\')"><i class="fa fa-window-restore"></i></button>\n' +
        '                                                <p id="vis-topology-selection" style="float: right">Selection: None</p></th></tr></thead>\n' +
        '                                        <tbody id="lab-env-topology"></tbody>\n' +
        '                                    </table>\n' +
        '                                </div>\n' +
        '                            </div>';
    vis_canvas_load_network_topology(projectid);
    // document.getElementById("vis-reload").setAttribute("onclick","javascript:vis_canvas_redraw("+projectid+");");
    // document.getElementById("btn-vis-refresh").setAttribute("onclick","javascript:vis_canvas_load_network_topology("+projectid+");");
    // document.getElementById("btn-vis-openallconsole").setAttribute("onclick","javascript:vis_canvas_open_all_consoles("+projectid+");");

}




function group_members_delete(element) {
    var table = $(element).closest('table').DataTable();
    var tableId = $(element).closest('td').find('table').attr('id');
    var members = $('[name="' + tableId + '-members[]"]:checked');

    if (members.length <= 0) {
        Swal.fire('Please select members!', '', 'warning');
        return;
    }


}

function group_members_change_roles(element) {
    var table = $(element).closest('table').DataTable();
    var tableId = $(element).closest('td').find('table').attr('id');
    var members = $('[name="' + tableId + '-members[]"]:checked');

    if (members.length <= 0) {
        Swal.fire('Please select members!', '', 'warning');
        return;
    }

    var select_roles = '<select>';
    $.getJSON('groups/available-roles', function(roles){
        $.each(roles, function (index, role) {
            select_roles += '<option value="' + role.id + '">' + role.name + '</option>';
        });
        select_roles += '</select>';
    });

    Swal.fire({title: 'Select a new role:',
        type: 'info',
        html: select_roles,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Update!',
        //confirmButtonAriaLabel: 'Thumbs up, great!',
        cancelButtonText: '<i class="fa fa-thumbs-down"></i>',
        //cancelButtonAriaLabel: 'Thumbs down'
    });
}

function run_waitMe(selector, effect) {
    $(selector).waitMe({
        effect: effect,
        text: 'Please wait ...',
        bg: 'rgba(255,255,255,0.7)',
        color:'#000'
    });
}

function group_management_edit(element) {
    var data = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();
    var groupId = data.id;
    var groupName = data.name;

    var modal = $('#modal-group-edit');

    modal.modal('show');
    modal.find('#group-name').empty().html(groupName);
    modal.find('#group-id').empty().html(groupId);

    group_management_group_config(element);
}

function group_management_group_config(element) {
    var dlg_form = $('#group_management_config').empty();
    var gId = element.attr('data-groupId');
    var gName = element.attr('data-groupName');
    var status = element.attr('data-groupStatus');

    // var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    // dlg_form.addClass('dialog').attr('id', 'dlg_group_admin_group').attr('title', 'Update Group Configuration: ' + gName);
    var tabs = $(document.createElement('div')).appendTo(dlg_form);
    if (status === 'Disabled') {
        tabs.attr('id', 'tabs-group-admin-group').append($('<ul>' +
            '<li><a href="#tabs-group-admin-group-detail">Detail Info</a></li>' +
            '</ul>' +
            '<div id="tabs-group-admin-group-detail" style="overflow: hidden;"></div>'));
    } else {
        tabs.attr('id', 'tabs-group-admin-group').append($('<ul>' +
            '<li><a href="#tabs-group-admin-group-detail">Detail Info</a></li>' +
            '<li><a href="#tabs-group-admin-group-members">Update Members</a></li>' +
            '<li><a href="#tabs-group-admin-group-enroll">Batch Enroll</a></li>' +
            // '<li><a href="#tabs-group-admin-group-usage">Resource Usage</a></li>' +
            '</ul>' +
            '<div id="tabs-group-admin-group-detail" style="overflow: hidden;"></div>' +
            '<div id="tabs-group-admin-group-members" style="overflow: hidden; padding-left: 0; padding-right: 0;"></div>' +
            '<div id="tabs-group-admin-group-enroll" style="overflow: hidden;"></div>'
            // '<div id="tabs-group-admin-group-usage" style="overflow: hidden;"></div>'
        ));
    }

    var div_detail_info_form = $(document.createElement('form'));
    div_detail_info_form.addClass('form-horizontal').appendTo($('#tabs-group-admin-group-detail'));
    $('<div class="form-group"><label class="col-xs-2 control-label" for="group_admin_detail_name">Name:</label>' +
        '<div class="col-xs-10"><input class="form-control" id="group_admin_detail_name" disabled type="text"></div></div>' +
        '<div class="form-group"><label class="col-xs-2 control-label" for="group_admin_detail_desc">Description:</label>' +
        '<div class="col-xs-10"><textarea class="form-control" id="group_admin_detail_desc" rows="2" style="resize: none;"></textarea></div></div>' +
        '<div class="form-group"><div class="col-xs-offset-2 col-xs-10"><label><input id="group_admin_detail_private" type="checkbox"> Private Group</label>' +
        '</div></div>').appendTo(div_detail_info_form);


    //
    // } else if (status === 'Denied') {
    //     $('<div>' +
    //         '<label>Denied At:</label>&nbsp;&nbsp;<label id="group_admin_detail_denied_at"></label><br><br>' +
    //         '<label>Reason</label>&nbsp;&nbsp;<label id="group_admin_detail_reason"></label><br><br>' +
    //         '<button>Reapply</button>' +
    //         '</div>').appendTo($('#tabs-group-admin-group-detail'));
    // } else if (status === 'Pending') {
    //     $('<br><br><label style="font-style: italic; text-align: center">Your resource and expiration date requests are currently processing by the site admin.</label>').appendTo($('#tabs-group-admin-group-detail'));
    // }
    // if (status === 'Disabled') {
    //     $('<div>' +
    //         '<label>Disabled At:</label>&nbsp;&nbsp;<label id="group_admin_detail_disabled_at"></label><br><br>' +
    //         '<label>Reason</label>&nbsp;&nbsp;<label id="group_admin_detail_reason"></label><br><br>' +
    //         '<button>Reactive</button>' +
    //         '</div>').appendTo($('#tabs-group-admin-group-detail'));
    // }

    $('#tabs-group-admin-group').tabs();

    if (status === 'Disabled') {
        run_waitMe($('#group-management-config'), 'ios');
        $.getJSON("group/getGroupInfo/" + gId, function (group) {
            $('#group_admin_detail_name').val(group.name);
            $('#group_admin_detail_desc').val(group.description);
            $('#group_admin_detail_requested_rss').val(group.resource_requested);
            $('#group_admin_detail_requested_exp').val(JSON.parse(group.resource_requested).expiration);
            $('#group_admin_detail_private').attr('checked', (group.private === 1));

            if (status === 'Disabled') {
                $('#group_admin_detail_approved_at').html(group.approved_at);
                $('#group_admin_detail_approved_rss').html(group.resource_allocated);
                $('#group_admin_detail_approved_exp').html(JSON.parse(group.resource_requested).expiration);
                $('#group_admin_detail_disabled_at').html(group.updated_at);
                $('#group_admin_detail_reason').html(group.reason);
            } else if (status === 'Denied') {
                $('#group_admin_detail_denied_at').html(group.approved_at);
                $('#group_admin_detail_reason').html(group.reason);
            }
            $('#group-management-config').waitMe('hide');
        });
    } else {
        group_admin_dlg_members($('#tabs-group-admin-group-members'));

        $('<label>Please enter members\' email address separated by ";" or upload .csv/.txt file: </label><br><br>' +
            '<textarea id="group_admin_dlg_batch_enroll_emails" style="resize: none; width: 530px; height: 270px; overflow: auto"></textarea><br><br>' +
            '<input type="file" id="group_admin_dlg_upload_file" accept=".txt, .csv" title="Upload .csv file" multiple onchange="group_admin_dlg_upload_file($(this),1)" />' +
            '<button style="float: right" title="Verify the format of the input data" onclick="group_admin_dlg_upload_file($(this),2)">Verify</button>&nbsp;&nbsp;&nbsp;' +
            '<button style="float: right" title="Clear the input data" onclick="group_admin_dlg_upload_file($(this),0)">Reset</button>')
            .appendTo($('#tabs-group-admin-group-enroll'));
        $('<label>Under Construction</label>').appendTo($('#tabs-group-admin-group-usage'));

        var member_tbody = $('#tbl_dlg_group_members');
        var member_counts_show = $('#group_admin_dlg_member_counts');
        var group_users = [];
        $.getJSON("group/getGroupUser/" + gId, function (data) {
            $('#group_admin_detail_name').val(data.group.name);
            $('#group_admin_detail_desc').val(data.group.description);
            $('#group_admin_detail_private').attr('checked', (data.group.private === 1));

            var member_counts = 0;
            $.each(data.users, function (index, user) {
                var roles = '';
                var role_id = '';
                var disabled = false;
                $.each(user.roles, function (idx, role) {
                    roles += role.name + '<br>';
                    role_id += role.id + ',';
                    if (role.name === 'group_owner') disabled = true;
                });
                member_tbody.append('<tr>' + // + ((disabled) ? 'disabled style="display: none;"' : '') + '>' +
                    '<td><input type="checkbox" name="group_admin_update_member_checkbox" ' +
                    ((disabled) ? 'disabled' : '') + ' ></td>' +
                    '<td class="hidden">' + user.data.id + '</td>' +
                    '<td><div style="width: 150px; word-break: break-all;" >' + user.data.email + '</div></td>' +
                    '<td class="hidden">' + role_id.slice(0, -1) + '</td>' +
                    '<td style="width: 90px;">' + roles.slice(0, -4) + '</td></tr>');
                group_users[user.data.id] = user.data.email;
                member_counts++;
            });
            $(member_counts_show).html(member_counts);

            $.each(data.available_roles, function (index, role) {
                if (role.name !== 'group_owner') {
                    $('#group_admin_dlg_member_default_role').append('<option value="' + role.id + '" ' +
                        ((role.name === 'student') ? 'selected="selected"' : '') + '>' + role.name + '</option>');
                }
            });

            if ((status === 'Active' && data.group.resource_requested !== null) || status === 'Pending') {
                $('<div class="form-group"><label class="col-xs-2 control-label" for="group_admin_detail_status" >Status:</label>' +
                    '<div class="col-xs-10"><label id="group_admin_detail_status">' + status + '</label></div></div>' +
                    '<div class="form-group"><label class="col-xs-2 control-label" for="group_admin_detail_requested_rss">Requested Resources:</label>' +
                    '<div class="col-xs-10"><textarea class="form-control" id="group_admin_detail_requested_rss" rows="3" disabled style="resize : none;"> ' +
                    data.group.resource_requested + '</textarea></div></div>' +
                    '<div class="form-group"><label class="col-xs-2 control-label" for="group_admin_detail_requested_exp">Requested Expiration:</label>' +
                    '<div class="col-xs-10"><input class="form-control" type="text" id="group_admin_detail_requested_exp" disabled value="' +
                    JSON.parse(data.group.resource_requested).expiration + '"></div></div>').appendTo(div_detail_info_form);
                if (status === 'Active') {
                    $('<div class="form-group"><label class="col-xs-2 control-label">Approved At:</label>' +
                        '<div class="col-xs-10"><label id="group_admin_detail_approved_at">' + data.group.approved_at + '</label></div></div>' +
                        '<div class="form-group"><label class="col-xs-2 control-label">Approved Resources:</label>' +
                        '<div class="col-xs-10"><label id="group_admin_detail_approved_rss">' + data.group.resource_allocated + '</label></div></div>' +
                        '<div class="form-group"><label class="col-xs-2 control-label">Approved Expiration:</label>' +
                        '<div class="col-xs-10"><label id="group_admin_detail_approved_exp">' + data.group.expiration + '</label></div></div>' +
                        '<div class="form-group"><label class="col-xs-2 control-label">Reason:</label>' +
                        '<div class="col-xs-10"><label id="group_admin_detail_reason">' + data.group.reason + '</label></div></div>').appendTo(div_detail_info_form);
                }
            } else if (status === 'Active' && data.group.resource_requested === null) {
                $('<div class="col-md-6>' +
                    '<tr class="noBorder"><td class="noBorder"><label>Status:</label></td>' +
                    '<td class="noBorder"><label style="font-style: italic">' + status + ', but no allocated resources can be used!</label></td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label>Require Resource:</label><br><br></td>' +
                    '<td class="noBorder"><select id="group_admin_dlg_require_rss" onchange="group_admin_toggle_request_rss($(this),2)">' +
                    '<option value="0">No</option><option value="1">Yes</option></td></tr>' +
                    '</table></div>').appendTo(div_detail_info_form);
                $('<div id="group_admin_dlg_request_rss" style="display: none;">' +
                    '<label style="font-style: italic;">Select requesting resources from:</label><br><br>' +
                    '<table class="noBorder" style="table-layout: fixed; width: 190px;"><colgroup><col width="120px" /><col width="250px"></colgroup>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_select_site">Site:</label></td>' +
                    '<td class="noBorder"><span id="group_admin_dlg_request_rss_select_site_div"></span>&nbsp;&nbsp;<span id="group_admin_dlg_request_rss_select_site_desc"></span></td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_labs">Number of Labs:</label></td>' +
                    '<td class="noBorder"><input id="group_admin_dlg_request_rss_labs" size="3px"/></td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_vms">VM per lab: </label></td>' +
                    '<td class="noBorder"><input id="group_admin_dlg_request_rss_vms" size="3px"/></td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_cpu">CPU per lab: </label></td>' +
                    '<td class="noBorder"><input id="group_admin_dlg_request_rss_cpu" size="3px"/></td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_ram">Memory per lab: </label></td>' +
                    '<td class="noBorder"><input id="group_admin_dlg_request_rss_ram" size="3px"/>MB</td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_storage">Storage per lab: </label></td>' +
                    '<td class="noBorder"><input id="group_admin_dlg_request_rss_storage" size="3px"/>GB</td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_expiration">Use util: </label></td>' +
                    '<td class="noBorder"><input id="group_admin_dlg_request_expiration" size="8px"/></td></tr>' +
                    '</table></div>').appendTo(div_detail_info_form);

                $('#group_admin_dlg_request_expiration').datepicker();
                $('#group_admin_new_group_select_site').clone().prop('id', 'group_admin_dlg_request_rss_select_site').appendTo($('#group_admin_dlg_request_rss_select_site_div'));
            }
        });

        var select_list = $('#group_admin_select_group_member_list');
        var user_counts_show = $('#group_admin_dlg_user_counts');

        run_waitMe($('#tabs-group-admin-group-members'), 'ios');
        $.getJSON("useradmin/getUserList", function (users) {
            var user_counts = 0;
            select_list.empty();
            $.each(users, function (index, user) {
                if (group_users[user.id] === undefined) {
                    select_list.append($(document.createElement('option')).attr('value', user.id).text(user.email));
                    user_counts++;
                }
            });
            user_counts_show.html(user_counts);
            $('#tabs-group-admin-group-members').waitMe('hide');
        });
    }
    //group_admin_dlg_update(tabs, element, status);
}

function group_admin_group_detail(element) {
    group_admin_group_config(element);
    $('#tabs-group-admin-group').tabs('option', 'active', 0);
}

function group_admin_group_members(element) {
    group_admin_group_config(element);
    $('#tabs-group-admin-group').tabs('option', 'active', 1);
}

function group_admin_group_usage(element) {
    group_admin_group_config(element);
    $('#tabs-group-admin-group').tabs('option', 'active', 3);
}

function group_admin_dlg_members(tab_form) {
    var div_container = $(document.createElement('div')).addClass('col-md-12').appendTo(tab_form);
    var div_container_row = $(document.createElement('div')).addClass('row justify-content-xs-center').appendTo(div_container);
    //var tr_container = $(document.createElement('tr')).attr('class', 'noBorder').appendTo(div_container);
    var left_form = $(document.createElement('div')).addClass('col col-xs-5').appendTo(div_container_row);
    left_form.append(
        '<label>Default Role:&nbsp;</label><select id="group_admin_dlg_member_default_role" title="Default role for selected users."></select><br>' +
        '<label id="group_admin_dlg_user_counts"></label><label>&nbsp;users found.</label><br>' +
        '<input type="text" placeholder="Search email" id="group_admin_search_user" style="width: 200px;" onkeyup="group_admin_search_user_filter($(this))"/>' +
        '<select id="group_admin_select_group_member_list" MULTIPLE style="width: 200px; height: 265px"></select>'
    );

    // $('#group_admin_select_group_member_list').multiselect();

    var middle = $(document.createElement('div')).addClass('col col-xs-1').css('padding-left', '0').css('margin-top', '150px').appendTo(div_container_row);
    //    .css('vertical-align', 'middle').css('text-align', 'center').css('width', '30px').appendTo(tr_container);
    $(document.createElement('button')).attr('id', 'btn_remove_group_member').html('<i class="fa fa-arrow-right"></i>').attr('title', 'Add Member')
        .attr('onclick', 'group_admin_update_group_member($(this), 1)').appendTo(middle);
    $('<br/><br/>').appendTo(middle);
    $(document.createElement('button')).attr('id', 'btn_add_group_member').html('<i class="fa fa-arrow-left"></i>').attr('title', 'Remove Member')
        .attr('onclick', 'group_admin_update_group_member($(this), 0)').appendTo(middle);

    var right_list = $(document.createElement('div')).addClass('col col-xs-6').css('padding-left', '0').css('padding-right', '0').appendTo(div_container_row);
    $('<label>Group Members:</label>&nbsp;&nbsp;<label id="group_admin_dlg_member_counts"></label>' +
        '<button title="Change role for selected users." onclick="group_admin_dlg_members_change_role()" style="float: right">Change Role</button><br><br>').appendTo(right_list);
    var div_members = $(document.createElement('div')).css('max-height', '300px').css('overflow', 'auto').appendTo(right_list);
    var members = $(document.createElement('table')).addClass('table table-condensed').attr('id','tbl_dlg_group_members')
        .css('table-layout', 'fixed').css('border', 'solid 1px lightgrey').appendTo(div_members);
    members.append('<thead>' +
        '<div><th style="width: 25px;"><input type="checkbox" name="group_admin_update_member_checkbox_all" onclick="group_admin_check_checkbox_group($(this))"></th>' +
        '<th class="hidden">UserId</th><th style="width: 150px;">User Email</div></th>' +
        '<th class="hidden">role_id</th><th style="width: 90px;">Role</th></tr></thead><tbody></tbody>');
}

function group_admin_update_group_member(element, action) {
    var user_list = $('#group_admin_select_group_member_list');
    var member_table = $('#tbl_dlg_group_members').find('tbody');
    var member_counts_str = $('#group_admin_dlg_member_counts').html();
    var user_counts = $('#group_admin_dlg_user_counts').html();

    if (action === 0) {    // remove
        var checked = member_table.find('input[type=checkbox]:checked:enabled');
        for (var i = 0; i < checked.length; i++) {
            //var user = checked[i].closest('tr');
            var user = checked[i].parentNode.parentNode;
            var opt = $('<option />').text(user.children[2].textContent)
                .attr('value', user.children[1].textContent);
            opt.prependTo(user_list);
            checked[i].parentNode.parentNode.remove();
        }
        $('#group_admin_dlg_member_counts').html(parseInt(member_counts_str) - i);
        $('#group_admin_dlg_user_counts').html(parseInt(user_counts) + i);
    } else if (action === 1) {    // add
        //var domain = sys_admin_get_domain('user');
        var member_add_count = 0;
        user_list.find('option:selected').each(function(index, selected) {
            member_table.append('<tr><td><input type="checkbox" name="group_admin_update_member_checkbox"></td>' +
                '<td class="hidden">' + $(selected).val() + '</td>' +
                '<td><div style="width: 140px; word-break: break-all;" >' + $(selected).text() + '</div></td>' +
                '<td class="hidden">' + $('#group_admin_dlg_member_default_role').val() + '</td>' +
                '<td style="width: 90px;">' + $('#group_admin_dlg_member_default_role option:selected').text() + '</td></tr>');
            $(selected).remove();
            member_add_count++;
        });
        $('#group_admin_dlg_member_counts').html(parseInt(member_counts_str) + member_add_count);
        $('#group_admin_dlg_user_counts').html(parseInt(user_counts) - member_add_count);
    }
}

function group_admin_dlg_members_change_role() {
    var member_table = $('#tbl_dlg_group_members').find('tbody');
    var checked = member_table.find('input[type=checkbox]:checked:enabled');
    if (checked.length <= 0) {
        Swal.fire('', 'Please select members!', 'warning');
        return;
    }

    Swal.fire({
        title: 'Change Role',
        html: '<span>Change role for selected users to:&nbsp;&nbsp;</span><select id="group_admin_dlg_member_update_select_role"></select>',
        showCancelButton: true
    }).then((result) => {
        if (result.value) {
            $.each(checked, function (index, row) {
                $(row).closest('tr').children().eq(3).html($('#group_admin_dlg_member_update_select_role').val());
                $(row).closest('tr').children().eq(4).html($('#group_admin_dlg_member_update_select_role option:selected').text());
            })
        }
    });

    $.getJSON("group/getGroupAvailableRoles", function (roles) {
        $.each(roles, function (index, role) {
            if (role.name !== 'group_owner') {
                $('<option value="' + role.id + '">' + role.name + '</option>').appendTo($('#group_admin_dlg_member_update_select_role'));
            }
        })
    })
}

function group_admin_dlg_get_members() {
    var member_table = $('#tbl_dlg_group_members').find('tbody');
    var members = member_table.find('input[type=checkbox]:enabled');
    var member_list = [];
    $.each(members, function (index, row) {
        member_list.push({
            id: $(row).closest('tr').children().eq(1).html(),
            name: $(row).closest('tr').children().eq(2).find('div').html(),
            roles: $(row).closest('tr').children().eq(3).html().split(',')
        })
    });
    return member_list;
}

function group_admin_dlg_update(element) {
    var modal = $('#modal-group-edit');
    var gId = modal.find('#group-id').html();
    var gName = modal.find('#group-name').html();
    var status = modal.find('#group_admin_detail_status').html();

    if (status !== 'Disabled') {  // Active group
        var isPrivate = ($('#group_admin_detail_private').is(':checked')) ? 1 : 0;
        var group = {'id': gId, 'name': gName, 'description': $('#group_admin_detail_desc').val(),
            'private': isPrivate, 'status': status};

        if (status !== 'Disabled' && status !== 'Denied') {
            group['members'] = group_admin_dlg_get_members();

            var require_rss_select = $('#group_admin_dlg_require_rss');
            if (require_rss_select.length > 0 && require_rss_select.val() === "1") {
                var require_rss = {};
                var labs = parseInt($('#group_admin_dlg_request_rss_labs').val());
                var vms = parseInt($('#group_admin_dlg_request_rss_vms').val());
                var cpu = parseInt($('#group_admin_dlg_request_rss_cpu').val());
                var ram = parseInt($('#group_admin_dlg_request_rss_ram').val());
                var storage = parseInt($('#group_admin_dlg_request_rss_storage').val());
                var expiration = $('#group_admin_dlg_request_expiration').val();

                if  (isNaN(labs) || isNaN(vms) || isNaN(cpu) || isNaN(ram) || isNaN(storage)) {
                    Swal.fire('', 'Please enter integer in the requesting resources!', 'warning');
                    return;
                }
                if (expiration.match(/^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/) === null) {
                    Swal.fire('', 'The date format is incorrect!', 'warning');
                    return;
                }

                require_rss['labs'] = labs;
                require_rss['vms'] = vms;
                require_rss['cpu'] = cpu;
                require_rss['ram'] = ram;
                require_rss['storage'] = storage;
                require_rss['expiration'] = expiration;

                group['resource_requested'] = JSON.stringify(require_rss);
                group['expiration'] = expiration;
                group['site_id'] = $('#group_admin_dlg_request_rss_select_site').val();
            }

            var batch_emails = $.trim($('#group_admin_dlg_batch_enroll_emails').val());
            if (batch_emails.length > 0 && !sys_admin_validate_emails(batch_emails)) {
                Swal.fire('', 'The format of input email addresses in the Batch Enroll are incorrect!', 'warning');
                return;
            }
            if (batch_emails.length > 0) {
                group['batch_emails'] = batch_emails;
            }
        }

        run_waitMe($('#group-management-config'), 'ios');
        $.post("group/updateGroup", {
                "group": group
            },
            function(item) {
                $('#group-management-config').waitMe('hide');
                if (item.status === 'Success') {
                    modal.modal('hide');
                    Swal.fire('', item.message, 'success');
                    $('#dataTableBuilder').DataTable().draw();
                } else {
                    Swal.fire('', item.message, 'warning');
                }
            },
            'json'
        );
    }
}

function group_admin_group_delete(element) {
    var gName = $(element).closest('tr').children().eq(1).html();
    var gStatus = $(element).closest('tr').children().eq(6).html();

    if (gStatus === 'Active' || gStatus === 'Disabled') {
        Swal.fire('', 'Active group\'s deletion function will implment soon!', 'warning');
        return;
    }
    var message = 'Do you really want to Withdraw/Drop/Delete "' + gName + '"?';
    create_ConfirmDialog('Delete Group', message,
        function() {
            $.post("group/deleteGroup", {
                    "group_id": $(element).closest('tr').children().eq(0).html(),
                    "group_name": gName
                },
                function(data) {
                    if (data.status === 'Success') {
                        Swal.fire('', data.message, 'success');
                        element.closest('tr').remove();

                        if (gStatus === 'Active' || gStatus === 'Disabled') {
                            var tbody = $('#table_group_admin_my_groups').find('tbody');
                            var contextMenu = $(document.createElement('ul')).appendTo(tbody);
                            contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'group-admin-groups-contextMenu')
                                .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
                            $('<li><a tabindex="-1" href="#" class="group-admin-group-edit">Edit</a></li>').appendTo(contextMenu);
                            $('<li><a tabindex="-1" href="#" class="group-admin-group-member">Members</a></li>').appendTo(contextMenu);
                            $('<li><a tabindex="-1" href="#" class="group-admin-group-usage">Usage</a></li>').appendTo(contextMenu);
                            $('<li><a tabindex="-1" href="#" class="group-admin-group-delete">Delete</a></li>').appendTo(contextMenu);
                        }
                    } else {
                        Swal.fire('', data.message, 'warning');
                    }
                },
                'json'
            );
        }, function () {
            // Cancel function
        }
    );
}

function group_admin_dlg_upload_file(element, action) {
    var tArea = $('#group_admin_dlg_batch_enroll_emails');

    if (action === 0) {
        tArea.val('');
    } else if (action === 1) {
        var csv = $('#group_admin_dlg_upload_file');
        var csvFile = csv[0].files[0];
        var ext = csv.val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['csv', 'txt']) === -1 ) {
            Swal.fire('', 'Please upload .csv or .txt file!', 'warning');
            return false;
        }

        if (csvFile !== undefined) {
            var reader = new FileReader();
            reader.onload = function (e) {
                //var csvResult = e.target.result.split(/\r|\n|\r\n/);
                //tArea.append(csvResult);
                tArea.val(e.target.result.replace(/(\r|\n|\r\n)/g,""));
            };
            reader.readAsText(csvFile);
        }
    } else if (action === 2) {
        if (group_admin_validate_emails(tArea.val())) {
            Swal.fire('', 'The format of input email addresses are correct.', 'success');
        } else {
            Swal.fire('', 'The format of input email addresses are incorrect!', 'warning');
        }
    }
}


function group_admin_search_user_filter(input) {
    var current, i, filter,
        options = input.next().find('option'); // $('#select_proj_member_list').find('option');

    //$("#group_admin_select_group_member_list option:selected").removeAttr("selected");
    filter = $(input).val();
    i = 1;
    $(options).each(function(){
        current = $(this);
        $(current).removeAttr('selected');
        if ($(current).text().indexOf(filter) !== -1) {
            $(current).show();
            $(current).removeAttr('disabled');
            // if(i === 1){
            //     $(current).attr('selected', 'selected');
            // }
            i++;
        } else {
            $(current).hide();
            $(current).attr('disabled', 'disabled');
        }
        $('#group_admin_dlg_user_counts').html(i-1);
    });
}

function group_admin_check_checkbox_group(element) {
    var tbody = element.closest('table').find('tbody');
    //var tbody = $('#tbl_member_list_in_set_member').find('tbody');
    var check = tbody.find('input[type=checkbox]');
    for (var i=0; i < check.length; i++) {
        if (check[i].disabled) continue;
        check[i].checked = element[0].checked;
    }
}

function group_admin_validate_emails(emailstr) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

    if (emailstr.length <= 0) return false;

    var emails = emailstr.split(';');
    var validation = true;

    $.each(emails, function(index, email) {
        if (email.trim().length > 0) {
            if (!filter.test(email.trim())) {
                validation = false;
            }
        }
    });

    return validation;
}

function arraysEqual(a, b) {
    if (a === b) return true;
    if (a == null || b == null) return false;
    if (a.length != b.length) return false;

    // If you don't care about the order of the elements inside
    // the array, you should sort both arrays here.
    // Please note that calling sort on an array will modify that array.
    // you might want to clone your array first.

    for (var i = 0; i < a.length; ++i) {
        if (a[i] !== b[i]) return false;
    }
    return true;
}

function lab_content_tooltip_deletefile(labid,fileid) {

    var message = '<p>Are you sure you want to delete this file?</p>';
    var ask = 'Delete the uploaded file?';

    create_ConfirmDialog('.container-fluid', ask, message, '40%', '.container-fluid',
        function () {

            $("#lab_"+labid+"_"+fileid).remove();
            $.post("/deletefileforlab", {

                    "fileid": fileid

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

function lab_content_tooltip_deletepdf(labid,fileid) {

    var message = '<p>Are you sure you want to delete this PDF?</p>';
    var ask = 'Delete the uploaded PDF file?';

    create_ConfirmDialog('.container-fluid', ask, message, '40%', '.container-fluid',
        function () {

            $("#lab_"+labid+"_"+fileid).remove();
            $.post("/deletepdfforlab", {

                    "fileid": fileid,
                "labid": labid

                },
                function (data) {

                },
                'json'
            );
            $("#lab-add-pdf").show();
        },
        function () {
            //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
        });

}

function open_workspace(){
    $("#workspace").show();
    // $("#labenv").show();
    // $("#labcontent").addClass('col-md-6');
    $("#showworkspace").hide();

    if ($('.container-fluid').innerWidth() > 768) {
        $('.gutter').show();
        $('#labenv').css('width', 'calc(100% - 3px)');
        $('#labcontent').css('width', 'calc(50% - 3px)');
    }
    $('#labcontent').removeClass('col-md-12 ');
    $('#labcontent').addClass('col-md-6 ');
    loadlabenvlist();
}

function close_contenteditor(){
    if ($('.gutter').css('display') !== 'none') {
        $('.gutter').hide();
        $('#labcontent').removeAttr('style');
        $('#labenv').removeAttr('style');

    }
    $("#showeditorbtn").show();
    $("#hideeditorbtn").hide();
    $("#labcontent").hide();
    $("#workspace").removeClass('col-md-6');
    $("#workspace").addClass('col-md-11');
}

function open_contenteditor(){
    if ($('.gutter').css('display') !== 'none') {
        $('.gutter').hide();
        $('#labcontent').removeAttr('style');
        $('#labenv').removeAttr('style');

    }
    $("#showeditorbtn").hide();
    $("#hideeditorbtn").show();
    $("#labcontent").show();
    $("#workspace").removeClass('col-md-11');
    $("#workspace").addClass('col-md-6');
}

function close_workspace(){
    if ($('.gutter').css('display') !== 'none') {
        $('.gutter').hide();
        $('#labcontent').removeAttr('style');
        $('#labenv').removeAttr('style');

    }
    $('#labcontent').removeClass('col-md-6 ');
    $('#labcontent').addClass('col-md-12 ');
    $("#showworkspace").show();
    $("#workspace").hide();
    //$("#labenv").hide();
    //$("#labcontent").removeClass('col-md-6');
}

function close_workspace_and_content(){
    if ($('.gutter').css('display') !== 'none') {
        $('.gutter').hide();
        $('#labcontent').removeAttr('style');
        $('#labenv').removeAttr('style');

    }
    $("#showworkspace").show();
    $("#labcontent").hide();
    $("#workspace").hide();
    //$("#labenv").hide();
    //$("#labcontent").removeClass('col-md-6');
}

// function finish_lab(labid) {
//
//
//     var finishflag =1;
//
//     if (finishflag === 1) {
//         var message = '<p>Are you sure you want to finish this lab?<br /> ' +
//             'All submission will be final and you will not be able to edit them anymore.</p>';
//         var ask = 'Finish the Task?';
//
//         create_ConfirmDialog('.container-fluid', ask, message, '30%', '.container-fluid',
//             function () {
//                 $("#countdownall").empty().html('Submitted');
//                 $("#dueon").empty();
//                 $(".btn-add-screenshot").remove();
//                 $(".btn-add-file").remove();
//                 $(".btn-image-icon").remove();
//                 $("#viewreport").html('<i class="fa fa-eye"></i>View Report');
//                 $("#finaltask").prop('disabled', true);
//                 $(".btn-info").prop('disabled', true);
//                 $(".btn-info").html('Lab Compleled');
//                 $("#finaltask").html('Lab Completed');
//                 // $("#bs-wizard-step-"+taskid).addClass('complete').children().attr('title','Finished');
//
//                 $.post("/finishlab", {
//
//                         "labid": labid,
//
//
//                     },
//                     function (data) {
//                         if (data.length> 10){
//                             alert(data);
//                         }
//
//                     },
//                     'json'
//                 );
//             },
//             function () {
//                 //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
//             });
//     }
// }
function first_autosave_text(taskid, subgroupid) {
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
        },
        'json'
    );
}

function autosave_text(taskid, subgroupid) {
    var desc = CKEDITOR.instances['submission-task-'+taskid+'-'+subgroupid+'-3'].getData();
    $.post("/groupsubmit/updatetext", {

            "taskid": taskid,
            "desc":desc,
            "subgroupid":subgroupid

        },
        function (data) {
            // if(data.status==='Success'){
            //     alert("second");
            // }
        },
        'json'
    );
}