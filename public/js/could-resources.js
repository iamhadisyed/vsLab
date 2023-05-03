
function modal_instance_details() {

}

function instance_actions(element) {
    var data = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();
    var project = data.project_id;
    var vm_name = data.hostname;
    //var vm_value = element.attr('value');
    var vm_uuid = data.uuid;

    var action = "";
    if (element.hasClass('vm-restart')) {
        action = "Reboot";
    } else if (element.hasClass('vm-rebuild')) {
        action = "Rebuild";
    } else if (element.hasClass('vm-shutdown')) {
        action = "Shutdown";
    } else if (element.hasClass('vm-start')) {
        action = "Start";
    } else if (element.hasClass('vm-suspend')) {
        action = "Suspend";
    } else if (element.hasClass('vm-resume')) {
        action = "Resume";
    } else if (element.hasClass('vm-info')) {
        get_vm_info(project, vm_name, vm_uuid);
        return true;
    }

    var message =  'Are you sure you want to ' + action + ' ' + project + ':' + vm_name + '?<br />';
    if (action === 'Rebuild')
        message += '<p style="color:red">The VM will be restored to its original state.<br />' +
            'You will LOSE All YOUR DATA in this VM if you rebuild it.</p>';
    create_ConfirmDialog(action + ' a VM', message,
        function() {
            // var icon = "workspace-assets/images/icons/network_terminal-red.png";
            // var vm_group = net_topology_data[project].nodes.get(vm_name).group;
            // if (vm_group === 'quagga') icon = "workspace-assets/images/icons/network_router-red.png";
            //
            // net_topology_data[project].nodes.update([{
            //     id: vm_name, label: vm_name,
            //     image: icon
            //     //title: tooltip[0].outerHTML
            // }]);

            $.post("/cloud/vmAction", {
                    //"_token": $(this).find('input[name=_token]').val(),
                    "project": '17781cb64c5d41f184b9cca01c4937e6',
                    "vmId": vm_uuid,
                    "action": action
                },
                function (data) {
                    //setTimeout(function () {
                    //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                    //}, 500);
                    if (action==='Resume'||action==='Start'){
                        element.closest('tr').children('td').eq(6).html("active");
                    }else if(action==='Shutdown'){
                        element.closest('tr').children('td').eq(6).html("stopped");
                    }else if(action==='Suspend'){
                        element.closest('tr').children('td').eq(6).html("suspended");
                    }

                },
                'json'
            )
        },
        function() {
            //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
        });

    //var newtooltip = '<div id="canvas-tooltip_' + vm_value + '">' + tooltip.html() + '</div>';
    //net_topology_data[project].nodes.update([{
    //    id: vm_name, label: vm_name, // shape: 'image', group: group
    //    title: newtooltip,
    //    image: "workspace-assets/images/icons/network_terminal-red.png"
    //}]);
}

function checkbox_check_all(tableId, element) {
    var table = $('#' + tableId).DataTable();
    var rows = table.rows({ 'search': 'applied' }).nodes();
    //var rows = table.fnGetNodes();
    $('input[type="checkbox"]', rows).prop('checked', element[0].checked);
}

function suspend_machine(element) {
    var instances = [];
    var INSTANCE_INVALID = ['stopped'];
    var INSTANCE_SUSPENDED = ['suspended'];
    // var PROJECT_IN_PROGRESS = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS'];
    var invalid_instances = "";
    var active_instances = "";
    var suspended_instances = "";
    var labs = [];
    var table = $('#dataTableBuilder').DataTable();
    // var proj_desc = 'project for group: ' + $('#group_selector option:selected').text().trim();

    if (element.is('button')) {
        labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select instances!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        labs.push(element);
    }

    for (var i = 0; i < labs.length; i++) {
        var rowData = table.row(labs[i].closest('tr')).data();
        if (INSTANCE_INVALID.indexOf(rowData.vm_state) >= 0) {
            invalid_instances += rowData.display_name + '<br />';
        } else if (INSTANCE_SUSPENDED.indexOf(rowData.vm_state) >=0) {
            suspended_instances += rowData.display_name + '<br />';
        } else {
            instances.push({ name: rowData.display_name, desc: 'Project: ' + rowData.project_name, instance: rowData.uuid, projectid: rowData.project_id, elements: i});
        }
    }

    if (invalid_instances.length > 0 || suspended_instances.length > 0) {
        Swal.fire({
            title: 'Suspend Instances Error!', type: 'error',
            html: '<em>Reason</em><br />' +
            ((invalid_instances.length > 0) ? '<b>Invalid instances (stopped):</b><br />' + invalid_instances + '<br />': '') +
            ((suspended_instances.length > 0) ? '<b>Invalid instances (suspended):</b><br />' + suspended_instances + '<br />': '')  + '</div>'
        });
    } else {
        Swal.fire({
            title: 'Suspend the selected labs?',
            text: 'The suspend process will take a few minutes.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Request Submitted!', 'Please refresh page to check VM status', 'success');
                instances.forEach(function(instance, index) {
                    setTimeout(function() {
                        // var row = table.rows(function(idx, data, node) {
                        //     return (data.project_name === project['name']) })[0];


                        $.post("/cloud/vmAction", {
                                //"_token": $(this).find('input[name=_token]').val(),
                                "project": '17781cb64c5d41f184b9cca01c4937e6',
                                "vmId": instance.instance,
                                "action": "Suspend"
                            },
                            function (data) {
                                //setTimeout(function () {
                                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                                //}, 500);
                                labs[instance.elements].closest('tr').children[6].innerHTML="suspended";

                            },
                            'json'
                        );

                        // $.post('deploy', {
                        //     'project': project
                        // }, function (data) {
                        //     table.cell(row, 'status:name').invalidate().draw(false);
                        //     update_a_lab_status(row);
                        //     //table.cell(row, 'status:name').data('Deploying').draw();
                        // });
                    }, 500 * index);
                });
            }
        });
    }
}

function stop_machine(element) {
    var instances = [];
    var INSTANCE_INVALID = ['stopped'];
    var INSTANCE_SUSPENDED = ['suspended'];
    // var PROJECT_IN_PROGRESS = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS'];
    var invalid_instances = "";
    var active_instances = "";
    var suspended_instances = "";
    var labs = [];
    var table = $('#dataTableBuilder').DataTable();
    // var proj_desc = 'project for group: ' + $('#group_selector option:selected').text().trim();

    if (element.is('button')) {
        labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select instances!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        labs.push(element);
    }

    for (var i = 0; i < labs.length; i++) {
        var rowData = table.row(labs[i].closest('tr')).data();
        if (INSTANCE_INVALID.indexOf(rowData.vm_state) >= 0) {
            invalid_instances += rowData.display_name + '<br />';
        } else if (INSTANCE_SUSPENDED.indexOf(rowData.vm_state) >=0) {
            suspended_instances += rowData.display_name + '<br />';
        } else {
            instances.push({ name: rowData.display_name, desc: 'Project: ' + rowData.project_name, instance: rowData.uuid, projectid: rowData.project_id});
        }
    }

    if (invalid_instances.length > 0 || suspended_instances.length > 0) {
        Swal.fire({
            title: 'Reboot Instances Error!', type: 'error',
            html: '<em>Reason</em><br />' +
            ((invalid_instances.length > 0) ? '<b>Invalid instances (stopped):</b><br />' + invalid_instances + '<br />': '') +
            ((suspended_instances.length > 0) ? '<b>Invalid instances (suspended):</b><br />' + suspended_instances + '<br />': '')  + '</div>'
        });
    } else {
        Swal.fire({
            title: 'Shutdown the selected labs?',
            text: 'The shutdown process will take a few minutes.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Request Submitted!', 'Please refresh page to check VM status', 'success');
                instances.forEach(function(instance, index) {
                    setTimeout(function() {
                        // var row = table.rows(function(idx, data, node) {
                        //     return (data.project_name === project['name']) })[0];


                        $.post("/cloud/vmAction", {
                                //"_token": $(this).find('input[name=_token]').val(),
                                "project": '17781cb64c5d41f184b9cca01c4937e6',
                                "vmId": instance.instance,
                                "action": "Shutdown"
                            },
                            function (data) {
                                //setTimeout(function () {
                                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                                //}, 500);
                                labs[instance.elements].closest('tr').children[6].innerHTML="stopped";
                            },
                            'json'
                        );

                        // $.post('deploy', {
                        //     'project': project
                        // }, function (data) {
                        //     table.cell(row, 'status:name').invalidate().draw(false);
                        //     update_a_lab_status(row);
                        //     //table.cell(row, 'status:name').data('Deploying').draw();
                        // });
                    }, 500 * index);
                });
            }
        });
    }
}

function reboot_machine(element) {
    var instances = [];
    var INSTANCE_INVALID = ['stopped'];
    var INSTANCE_SUSPENDED = ['suspended'];
    // var PROJECT_IN_PROGRESS = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS'];
    var invalid_instances = "";
    var active_instances = "";
    var suspended_instances = "";
    var labs = [];
    var table = $('#dataTableBuilder').DataTable();
    // var proj_desc = 'project for group: ' + $('#group_selector option:selected').text().trim();

    if (element.is('button')) {
        labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select instances!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        labs.push(element);
    }

    for (var i = 0; i < labs.length; i++) {
        var rowData = table.row(labs[i].closest('tr')).data();
        if (INSTANCE_INVALID.indexOf(rowData.vm_state) >= 0) {
            invalid_instances += rowData.display_name + '<br />';
        } else if (INSTANCE_SUSPENDED.indexOf(rowData.vm_state) >=0) {
            suspended_instances += rowData.display_name + '<br />';
        } else {
            instances.push({ name: rowData.display_name, desc: 'Project: ' + rowData.project_name, instance: rowData.uuid, projectid: rowData.project_id});
        }
    }

    if (invalid_instances.length > 0 || suspended_instances.length > 0) {
        Swal.fire({
            title: 'Reboot Instances Error!', type: 'error',
            html: '<em>Reason</em><br />' +
            ((invalid_instances.length > 0) ? '<b>Invalid instances (stopped):</b><br />' + invalid_instances + '<br />': '') +
            ((suspended_instances.length > 0) ? '<b>Invalid instances (suspended):</b><br />' + suspended_instances + '<br />': '')  + '</div>'
        });
    } else {
        Swal.fire({
            title: 'Reboot the selected labs?',
            text: 'The reboot process will take a few minutes.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Request Submitted!', 'Please refresh page to check VM status', 'success');
                instances.forEach(function(instance, index) {
                    setTimeout(function() {
                        // var row = table.rows(function(idx, data, node) {
                        //     return (data.project_name === project['name']) })[0];


                        $.post("/cloud/vmAction", {
                                //"_token": $(this).find('input[name=_token]').val(),
                                "project": '17781cb64c5d41f184b9cca01c4937e6',
                                "vmId": instance.instance,
                                "action": "Reboot"
                            },
                            function (data) {
                                //setTimeout(function () {
                                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                                //}, 500);
                            },
                            'json'
                        );

                        // $.post('deploy', {
                        //     'project': project
                        // }, function (data) {
                        //     table.cell(row, 'status:name').invalidate().draw(false);
                        //     update_a_lab_status(row);
                        //     //table.cell(row, 'status:name').data('Deploying').draw();
                        // });
                    }, 500 * index);
                });
            }
        });
    }
}

function resume_machine(element) {
    var instances = [];
    var INSTANCE_INVALID = ['stopped'];
    var INSTANCE_SUSPENDED = ['active'];
    // var PROJECT_IN_PROGRESS = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS'];
    var invalid_instances = "";
    var active_instances = "";
    var suspended_instances = "";
    var labs = [];
    var table = $('#dataTableBuilder').DataTable();
    // var proj_desc = 'project for group: ' + $('#group_selector option:selected').text().trim();

    if (element.is('button')) {
        labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select instances!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        labs.push(element);
    }

    for (var i = 0; i < labs.length; i++) {
        var rowData = table.row(labs[i].closest('tr')).data();
        if (INSTANCE_INVALID.indexOf(rowData.vm_state) >= 0) {
            invalid_instances += rowData.display_name + '<br />';
        } else if (INSTANCE_SUSPENDED.indexOf(rowData.vm_state) >=0) {
            suspended_instances += rowData.display_name + '<br />';
        } else {
            instances.push({ name: rowData.display_name, desc: 'Project: ' + rowData.project_name, instance: rowData.uuid, projectid: rowData.project_id});
        }
    }

    if (invalid_instances.length > 0 || suspended_instances.length > 0) {
        Swal.fire({
            title: 'Resume Instances Error!', type: 'error',
            html: '<em>Reason</em><br />' +
            ((invalid_instances.length > 0) ? '<b>Invalid instances (stopped, please start instead):</b><br />' + invalid_instances + '<br />': '') +
            ((suspended_instances.length > 0) ? '<b>Invalid instances (still active)</b><br />' + suspended_instances + '<br />': '')  + '</div>'
        });
    } else {
        Swal.fire({
            title: 'Resume the selected labs?',
            text: 'The resume process will take a few minutes.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Request Submitted!', 'Please refresh page to check VM status', 'success');
                instances.forEach(function(instance, index) {
                    setTimeout(function() {
                        // var row = table.rows(function(idx, data, node) {
                        //     return (data.project_name === project['name']) })[0];


                        $.post("/cloud/vmAction", {
                                //"_token": $(this).find('input[name=_token]').val(),
                                "project": '17781cb64c5d41f184b9cca01c4937e6',
                                "vmId": instance.instance,
                                "action": "Resume"
                            },
                            function (data) {
                                //setTimeout(function () {
                                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                                //}, 500);
                                labs[instance.elements].closest('tr').children[6].innerHTML="active";
                            },
                            'json'
                        );

                        // $.post('deploy', {
                        //     'project': project
                        // }, function (data) {
                        //     table.cell(row, 'status:name').invalidate().draw(false);
                        //     update_a_lab_status(row);
                        //     //table.cell(row, 'status:name').data('Deploying').draw();
                        // });
                    }, 500 * index);
                });
            }
        });
    }
}

function start_machine(element) {
    var instances = [];
    var INSTANCE_INVALID = ['active'];
    var INSTANCE_SUSPENDED = ['suspended'];
    // var PROJECT_IN_PROGRESS = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS'];
    var invalid_instances = "";
    var active_instances = "";
    var suspended_instances = "";
    var labs = [];
    var table = $('#dataTableBuilder').DataTable();
    // var proj_desc = 'project for group: ' + $('#group_selector option:selected').text().trim();

    if (element.is('button')) {
        labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select instances!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        labs.push(element);
    }

    for (var i = 0; i < labs.length; i++) {
        var rowData = table.row(labs[i].closest('tr')).data();
        if (INSTANCE_INVALID.indexOf(rowData.vm_state) >= 0) {
            invalid_instances += rowData.display_name + '<br />';
        } else if (INSTANCE_SUSPENDED.indexOf(rowData.vm_state) >=0) {
            suspended_instances += rowData.display_name + '<br />';
        } else {
            instances.push({ name: rowData.display_name, desc: 'Project: ' + rowData.project_name, instance: rowData.uuid, projectid: rowData.project_id});
        }
    }

    if (invalid_instances.length > 0 || suspended_instances.length > 0) {
        Swal.fire({
            title: 'Start Instances Error!', type: 'error',
            html: '<em>Reason</em><br />' +
            ((invalid_instances.length > 0) ? '<b>Invalid instances (still active):</b><br />' + invalid_instances + '<br />': '') +
            ((suspended_instances.length > 0) ? '<b>Suspended_instances (please use resume instead):</b><br />' + suspended_instances + '<br />': '')  + '</div>'
        });
    } else {
        Swal.fire({
            title: 'Start the selected labs?',
            text: 'The start process will take a few minutes.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Request Submitted!', 'Please refresh page to check VM status', 'success');
                instances.forEach(function(instance, index) {
                    setTimeout(function() {
                        // var row = table.rows(function(idx, data, node) {
                        //     return (data.project_name === project['name']) })[0];


                        $.post("/cloud/vmAction", {
                                //"_token": $(this).find('input[name=_token]').val(),
                                "project": '17781cb64c5d41f184b9cca01c4937e6',
                                "vmId": instance.instance,
                                "action": "Start"
                            },
                            function (data) {
                                //setTimeout(function () {
                                //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                                //}, 500);
                                labs[instance.elements].closest('tr').children[6].innerHTML="active";
                            },
                            'json'
                        );

                        // $.post('deploy', {
                        //     'project': project
                        // }, function (data) {
                        //     table.cell(row, 'status:name').invalidate().draw(false);
                        //     update_a_lab_status(row);
                        //     //table.cell(row, 'status:name').data('Deploying').draw();
                        // });
                    }, 500 * index);
                });
            }
        });
    }
}

function create_ConfirmDialog(div_id, title, ask, width, containment, okCallback, cancelCallback) {
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
                okCallback();
                $(this).dialog('close');
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
    $(dlg_form).closest('.ui-dialog').css('z-index', '1060');
    $('.ui-widget-content').css('background-color', 'beige');
    $('.ui-dialog-titlebar').css('height', '35px');
    $('.ui-dialog-titlebar-close').css('display', 'none');
}

function auto_suspending(element) {
    var groupId = $('#group_selector').val();
    $('.groupname-in-title').html($('#group_selector option:selected').text());
    $('#modal-auto-suspend').modal('show');
}