/**
 * Created by root on 3/30/15.
 */

function profile_group_project_display(winId, win_main) {
    var groupname = winId.substring('#window_group_project_'.length);
    var tabs = {
        tabId: ['team_list_' + groupname, 'lab_deploy_list_' + groupname],
        tabName: ['Team List', 'Lab Deployment']
    };

    create_tabs(winId, win_main, tabs, null);

    var team_buttons = $(document.createElement('div')).appendTo($('#team_list_' + groupname ));
    team_buttons.append('<button class="submit" onclick="create_subgroup($(this))">Create Teams</button>&nbsp;' +
                        //'<button onclick="assign_template($(this))">Assign Templates</button>' +
                        '<button class="submit" onclick="assign_lab($(this))">Assign Labs</button>');

    var team_table = $(document.createElement('table')).appendTo($('#team_list_' + groupname));
    team_table.addClass("data").attr("id", "tbl_team_list_" + groupname).append(
        //'<thead><tr><th>checkbox</th><th>Subgroup</th><th>Actions</th><th>Last Copied Template ID</th><th>Last Assigned Template ID</th><th>Project Name</th></tr></thead>'
        '<thead><tr><th><input type="checkbox" name="lab_assign_list_checkbox_all" onclick="list_check($(this))"></th>' +
        '<th class="hidden">TeamID</th><th>Team</th><th>Description</th><th>Members</th><th>Labs</th>' +
        '<th>Actions</th></tr></thead>'
    );
    var team_tbody = $(document.createElement('tbody')).appendTo(team_table);
    getTeamListTable(groupname);

    var lab_buttons = $(document.createElement('div')).appendTo($('#lab_deploy_list_' + groupname));
    lab_buttons.append('<button class="submit"  id="btn_lab_deploy_batch" type="batch" onclick="lab_deploy($(this))">Deploy Assigned Labs</button>&nbsp;' +
                       '<button class="submit" id="btn_lab_update_batch" type="batch" group="' + groupname + '" onclick="lab_rename($(this))">Update Lab Info</button>&nbsp;' +
                       '<button class="submit" id="btn_lab_delete_batch" type="batch" onclick="lab_delete($(this))">Delete Labs</button>&nbsp;'
                       //'<button class="submit" id="btn_lab_refresh" onclick="lab_refresh($(this))">Refresh Table</button>'
                       );
    var lab_table = $(document.createElement('table')).appendTo($('#lab_deploy_list_' + groupname));
    lab_table.addClass("data").attr("id", "tbl_lab_deploy_list_" + groupname).append(
        //'<thead><tr><th>checkbox</th><th>Subgroup</th><th>Actions</th><th>Last Copied Template ID</th><th>Last Assigned Template ID</th><th>Project Name</th></tr></thead>'
        '<thead><tr><th><input type="checkbox" name="lab_assign_list_checkbox_all" onclick="list_check($(this))"></th>' +
        '<th class="hidden">Team_id</th><th>Team</th>' +
        '<th class="hidden">Temp_id</th><th>Lab/Template</th><th class="hidden">ProjectName</th><th>Lab Name</th>' +
        '<th>Description</th><th>Assign At</th><th>Lab Deploy At</th><th class="hidden">sys_status</th><th>Status</th><th>Lab Due At</th><th>Actions</th><th class="hidden">lab_id</th></tr></thead>'
    );
    var lab_tbody = $(document.createElement('tbody')).appendTo(lab_table);
    //getLabDeployListTable(groupname);

    //$(winId).find('div.window_bottom')
    //    .text('Subgroup template setting');
}

function create_subgroup(element) {
    var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_create_subgroup').attr('title', 'Create Team in Group ' + groupname);

    //var form_html = $(document.createElement('div')).appendTo(dlg_form);
    var left_form = $(document.createElement('div')).css('float', 'left').appendTo(dlg_form);
    var right_list = $(document.createElement('div')).css('float', 'right').css('margin', '5px').appendTo(dlg_form);
    left_form.append('<input type="radio" name="subgroup_create_team" value="individual" onclick="create_team_select($(this))">&nbsp;Create Individual Team<br>' +
    '<input type="radio" name="subgroup_create_team" value="group" checked onclick="create_team_select($(this))">&nbsp;Create Group Team<br>' +
    '<div id="subgroup_create_team_group">' +
    '<label for="create_subgroup_name">Team name:</label>' +
    '<br>' +
    '<input type="text" id="create_subgroup_name">' +
    '<br>' +
    '<label for="create_subgroup_desc">Description:</label>' +
    '<br>' +
    '<textarea id="create_subgroup_desc" style="width: 200px; height: 95px; resize: none;"></textarea>' +
    '</div>' +
    '<div style="align-content: center; margin-top: 10px;">' +
    //'<button onclick="submit_create_subgroup($(this))"  value="' + groupname + '" >Create</button>' +
    '</div>');
    var table = $(document.createElement('table')).addClass('data').attr('id','tbl_member_list_in_set_member').css('width','530px').appendTo(right_list);
    table.append('<thead><tr><th><input type="checkbox" name="create_team_checkbox_all" onclick="check_checkbox_group($(this))"></th>' +
    '<th>Group User</th><th>Institute</th><th>Org ID (member id)</th><th>Role</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    $('#dlg_create_subgroup').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 800,
        buttons: {
            "Create": function() {
                var members = [];
                var sel_radio= $('input[name=subgroup_create_team]:checked').val();
                var member_tbody = $('#tbl_member_list_in_set_member').find('tbody');
                var check = member_tbody.find('input[name=create_team_checkbox]:checked:enabled');
                if (check.length == 0) {
                    $.jAlert({
                        'title': 'Warning', 'content': 'Please select members from the group user list!',
                        'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                    });
                    return;
                }
                for (var i=0; i < check.length; i++) {
                    if (check[i].checked)
                        members.push(check[i].parentNode.nextSibling.innerHTML);
                }
                var member_str = members.map(function(id) { return "'" + id + "'"; }).join(", ");
                var member_in_str = "(" + member_str + ")";
                var subgroup_name = $('#create_subgroup_name').val();
                var subgroup_desc = $('#create_subgroup_desc').val();
                var subgroup_tbody = $('#tbl_team_list_' + groupname).find('tbody');

                if (sel_radio == 'individual') {
                    $.post("cloud/create_individual_team", {
                            "group_name": groupname,
                            "members": member_in_str
                        },
                        function (jsondata) {
                            if (jsondata.status == 'Success') {
                                $.each(jsondata.subgroups, function (index, item) {
                                    $('<tr>' +
                                    '<td><input type="checkbox" name="subgroups_checkbox" value="' + item.id + '"></td>' +
                                    '<td class="hidden">' + item.id + '</td><td>' + item.name + '</td>' +
                                    '<td>' + item.desc + '</td><td>' + item.name + '</td><td></td>' +
                                    '<td class="dropdown"><a class="btn btn-default team-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                                    '</tr>').prependTo(subgroup_tbody);
                                });
                            }
                            if (jsondata.exists.length > 0) {
                                $.jAlert({
                                    'title': 'Warning', 'content': JSON.stringify(jsondata.exists) + ' already exist.',
                                    'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                                });
                            }
                        },
                        'json'
                    ).fail(function (xhr, testStatus, errorThrown) {
                            alert(xhr.responseText);
                        });

                } else if (sel_radio == 'group') {
                    if ((subgroup_name.trim().length == 0) || (subgroup_desc.trim().length == 0)) {
                        $.jAlert({
                            'title': 'Warning', 'content': 'Please enter the Team Name and Description!',
                            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                        });
                        return;
                    }
                    $.post("cloud/create_subgroup", {
                            "group_name": groupname,
                            "members": members,
                            "subgroup_name": subgroup_name,
                            "subgroup_desc": subgroup_desc
                        },
                        function (data) {
                            if (data.status == 'Success') {
                                $('<tr>' +
                                '<td><input type="checkbox" name="subgroups_checkbox" value="' + data.subgroup_id + '"></td>' +
                                '<td class="hidden">' + data.subgroup_id + '</td><td>' + subgroup_name + '</td>' +
                                '<td>' + subgroup_desc + '</td><td>' + members + '</td><td></td>' +
                                '<td class="dropdown"><a class="btn btn-default team-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                                '</tr>').prependTo(subgroup_tbody);
                            } else {
                                $.jAlert({
                                    'title': 'Warning', 'content': JSON.stringify(data.message),
                                    'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                                });
                            }
                        },
                        'json'
                    ).fail(function (xhr, testStatus, errorThrown) {
                            alert(xhr.responseText);
                        });
                }
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });

    $.post("/cloud/getGroupMembers", {
            "group_name": groupname
        },
        function (jsondata) {
            $.each(jsondata, function (index, item) {
                if (index == 'members') {
                    $.each(item, function (index3, item3) {
                        if (item3.role != 'GroupOwner') {
                            tbody.append('<tr><td><input type="checkbox" name="create_team_checkbox"></td><td>' + item3.email +
                            "</td><td>" + item3.institute + "</td><td>" + item3.org_id + "</td><td>" + item3.role + "</td></tr>");
                        }
                    });
                }
            });
        },
        'json'
    );
}

function create_team_select(radio_btn) {
    if (radio_btn.val() == 'group') {
        $('#subgroup_create_team_group').css('display', 'block');
    } else if (radio_btn.val() == 'individual') {
        $('#subgroup_create_team_group').css('display', 'none');
    }
}

function check_checkbox_group(element) {
    var tbody = element.closest('table').find('tbody');
    //var tbody = $('#tbl_member_list_in_set_member').find('tbody');
    var check = tbody.find('input[type=checkbox]');
    for (var i=0; i < check.length; i++) {
        check[i].checked = element[0].checked;
    }
}

function list_check(element) {
    //var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    var tbody = element.closest('table').find('tbody');
    var check = tbody.find('input[type=checkbox]');
    for (var i=0; i < check.length; i++) {
        check[i].checked = element[0].checked;
    }
}

function getTeamListTable(groupname) {
    var tab = $('#tbl_team_list_' + groupname);
    $(tab).find('thead input[type=checkbox]').attr('checked', false);
    var tbody = $(tab).find('tbody');
    tbody.empty();
    var win_main = $(tab).closest('div.tab');
    run_waitMe(win_main, 'ios');
    $.getJSON("/cloud/getTeamList/" + groupname, function (jsondata) {
        $.each(jsondata, function (index, team) {
            var temps = '';
            for (var i=0; i<team.templates.length; i++) {
                //temps.push(team.templates[i].temp_name);
                temps += '<i class="fa fa-flask"></i>' + team.templates[i].temp_name + '<br />';
            }
            //var team_members = '';
            //for (var i=0; i<team.members.length; i++) {
            //    team_members += '<i class="fa fa-user"></i>' + team.members[i] + '<br />';
            //}

            $('<tr>' +
            '<td><input type="checkbox" name="subgroups_checkbox" value="' + team.id + '"></td>' +
            '<td class="hidden">' + team.id + '</td>' +
            '<td>' + team.name + '</td>' +
            '<td>' + team.desc + '</td>' +
            '<td>' + team.members.toString().split(',').join('<br />') + '</td>' +
            '<td>' + temps + '</td>' +
            '<td class="dropdown"><a class="btn btn-default team-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
            '</tr>').appendTo(tbody);
        });
        $(win_main).waitMe('hide');
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'team-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="team-members">Members</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="go_pending_enroll">Waiting List</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="go_add_member">Invite Members</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="team-edit">Rename</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="team-delete">Delete</a></li>').appendTo(contextMenu);
}

function getLabDeployListTable(groupname) {
    var tab = $('#tbl_lab_deploy_list_' + groupname);
    $(tab).find('thead input[type=checkbox]').attr('checked', false);
    var tbody = $(tab).find('tbody');
    tbody.empty();

    var win_main = $(tab).closest('div.tab');
    run_waitMe(win_main, 'ios');
    $.getJSON("/cloud/getSubgroupTemplateProject/" + groupname, function (jsondata) {
        $.each(jsondata, function (index, lab) {
            //var progress = '<span><img src= "img/waiting.gif" width="50" height="20" style="margin-left: auto; margin-right: auto"/>';
            //if (lab.status == 'CREATE_IN_PROGRESS')
            //    progress += 'Creating</span>';
            ////else if (lab.status == 'CREATE_COMPLETE')
            ////    progress = 'COMPLETE';
            //else
            //    progress = lab.status;
            var tr = $('<tr />').appendTo(tbody);
            tr.append(
            '<td><input type="checkbox" name="subgroups_checkbox" value="' + lab.subgroup_id + '"></td>' +
            '<td class="hidden">' + lab.subgroup_id + '</td>' +
            '<td>' + lab.subgroup_name + '</td>' +
            '<td class="hidden">' + lab.template_id + '</td>' +
            '<td>' + lab.template_name + '</td>' +
            '<td class="hidden">' + lab.project_name + '</td>' +
            '<td>' + lab.lab_name + '</td>' +
            '<td>' + lab.desc + '</td>' +
            '<td>' + (new Date(lab.assign_at.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,"") + '</td>' +
            '<td>' + ((lab.deploy_at == '0000-00-00 00:00:00') ? '-' : (new Date(lab.deploy_at.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,"")) + '</td>' +
            '<td class="hidden">' + lab.status + '</td>' +
            '<td>' + '' + '</td>' +
            '<td>' + ((lab.due_at == '0000-00-00 00:00:00') ? '-' : (new Date(lab.due_at.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,"")) + '</td>' +
            '<td class="dropdown"><a class="btn btn-default labDeploy-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>'
            );
            display_status(tr.children().eq(11), lab.status, tr.children().eq(10), lab.deploy_at);
            if (lab.status == 'Deploying' || lab.status == 'CREATE_IN_PROGRESS' || lab.status == 'Deleting' ) {
                update_deploy_list(groupname, tr.children().eq(11), lab, null, '');
            }
        });
        $(win_main).waitMe('hide');
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'labDeploy-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="lab-view">View</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="lab-deploy">Deploy</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="lab-delete">Delete</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="lab-rename">Update Info</a></li>').appendTo(contextMenu);
}

function get_selected_lab(element) {
    var labs = [];
    if (element.attr('type') == 'batch') {
        var checked_lab = $(element).closest('div.tab').find('tbody input[type=checkbox]:checked:enabled');
        if (checked_lab.length == 0) {
            $.jAlert({
                'title': 'Warning', 'content': 'You need to select at least one lab to update or delete the lab!',
                'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
            });
            return false;
        }
        for (var i = 0; i < checked_lab.length; i++) {
            var tr = checked_lab[i].parentNode.parentNode;
            var lab = { team_id: tr.children[1].textContent, team_name: tr.children[2].textContent,
                temp_id: tr.children[3].textContent, temp_name: tr.children[4].textContent,
                project_name: tr.children[5].textContent, lab_name: tr.children[6].textContent,
                lab_desc: tr.children[7].textContent, status: tr.children[10].textContent };
            labs.push(lab);
        }
    } else {
        var tr = element.closest('tr').children();
        var lab = { team_id: tr.eq(1).html(), team_name: tr.eq(2).html(), temp_id: tr.eq(3).html(),
            temp_name: tr.eq(4).html(), project_name: tr.eq(5).html(), lab_name: tr.eq(6).html(),
            lab_desc: tr.eq(7).html(), status: tr.eq(10).html() };
        labs.push(lab);
    }
    return labs;
}

function lab_deploy(element) {
    var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    var labs = get_selected_lab(element);
    if (labs === false) return;
    if (labs.length > 20) {
        $.jAlert({
            'title': 'Warning', 'content': 'The deployment in batch cannot select more than 20 labs!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
        return;
    }
    labs.forEach(function(item, index, object) {
       if (item.status === 'Deploying' || item.status == 'CREATE_COMPLETE' || item.status == 'CREATE_IN_PROGRESS') {
           object.splice(index, 1);
       }
    });
    var rows = element.closest('.tab-content').find('tbody').children();
    //var message = 'The deployment process will take a few minutes.<br /> Please click REFRESH TABLE to update the status.<br />';
    var message = 'The deployment process will take a few minutes.<br /> Please wait for the process finish before using it.<br />';
    create_ConfirmDialog('Deploy the Selected Labs', message,
        function() {
            labs.forEach(function(lab, index, object) {
                var status = rows.children('td:contains(' + lab.project_name + ')').siblings('td:eq(10)');
                var deploy_at = rows.children('td:contains(' + lab.project_name + ')').siblings('td:eq(8)');
                display_status(status, 'Deploying', null, '');
                //var current = new Date().toISOString().slice(0,19).replace('T', ' ');

                $.post("/cloud/deploy_lab", {
                    "groupname": groupname,
                    "lab": lab
                    //"datetime": current
                    },
                    function(data) {
                        if ((data.status == 'Success') && (lab['temp_id'] != 0)) {
                            //display_status(status, 'Deploying');
                            setTimeout(function () {
                                update_deploy_list(groupname, status, lab, deploy_at);
                            }, 5000);
                        } else {
                            display_status(status, data.deploy_status, deploy_at);
                        }
                    },
                    'json'
                );
            })
        }, function () {
                // Cancel function
        }
    );
}

function update_deploy_list(groupname, status_holder, lab, deploy_at) {
    $.post("/cloud/checkStackStatus", {
        "project_name": lab.project_name
        },
        function(data) {
            if (data.status == 'CREATE_COMPLETE') {
                display_status(status_holder, data.status, deploy_at);
                update_lab_project_table(lab, data.status, status_holder, deploy_at);
            }
            else if (data.status == 'CREATE_IN_PROGRESS' || data.status == 'DELETE_IN_PROGRESS') {
                display_status(status_holder, data.status, deploy_at);
                setTimeout(function () {
                    update_deploy_list(groupname, status_holder, lab, deploy_at);
                }, 5000);
            } else if (data.status == 'DELETE_COMPLETE' || data.status == 'Stack Deleted') {
                //update_lab_project_table(lab, data.status, status_holder);
                project_delete(groupname, status_holder, lab);
            }
        },
    'json'
    );
}

function update_lab_project_table(lab, status, status_holder, deploy_at) {
    var current = new Date().toISOString().slice(0,19).replace('T', ' ');
    $.post("/cloud/update_Lab_Project", {
            "project_name"  : lab.project_name,
            "status"        : status,
            "deploy_at"     : current
        },
        function(data) {
            deploy_at.html((new Date(current.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,""));
            if (data.status == 'fail') {
                display_status(status_holder, data.status, deploy_at);
            }
        },
        'json'
    );
}

function display_status(status_holder, status, deploy_at) {
    var spinner = $('<span />').append('<img src=' + ICON_status_progress + ' width="50" height="20" style="margin-left: auto; margin-right: auto"/>');
    status_holder.prev().html(status);

    if (status == 'Deleting' || status == 'Deploying' || status == 'CREATE_IN_PROGRESS' || status == 'DELETE_IN_PROGRESS' || status == 'Deleting Project') {
        status_holder.html(spinner.append(status));

    } else if (status == 'CREATE_COMPLETE') {
        status_holder.html('<img src=' + ICON_status_ok + ' title="Deploy Complete" />');
        //deploy_at.html(current);
    } else if (status.length != 0) {
        status_holder.html('<img src=' + ICON_status_error + ' title="' + status + '" />');
    }
}

function lab_rename(element) {
    var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    var labs = get_selected_lab(element);
    if (labs === false) return;

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_update_lab_info').attr('title', 'Update Lab Information');

    $('<div><label for="update_lab_name">Lab name:</label><br>' +
    '<input type="text" id="update_lab_name"><br>' +
    '<label for="update_lab_due">Lab due date:</label><br>' +
    '<input type="text" id="lab_due_date" ><br>' +     //<button onclick="show_datetimepicker()">Pick Date</button><br>' +
    '<label for="update_lab_desc">Description:</label><br>' +
    '<textarea id="update_lab_desc" style="width: 220px; height: 100px; resize: none;"></textarea></div>').appendTo(dlg_form);
    //$('<button />').attr('id', 'submit_update_lab_info').text('Update').attr('type', element.attr('type'))
    //    .attr('group', element.attr('group')).attr('onclick', 'submit_update_lab_info($(this))').appendTo(dlg_form);
    $('#dlg_update_lab_info').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 300,
        buttons: {
            "Update": function () {
                $.post("/cloud/update_lab_info", {
                        "labs": labs,
                        "lab_name": $('#update_lab_name').val().trim(),
                        "lab_due": $('#lab_due_date').val().trim(),
                        "desc": $('#update_lab_desc').val().trim()
                    },
                    function (data) {
                        //if (data.status == 'Success') {
                            getLabDeployListTable(groupname);
                        //} else {
                        //    alert(data.message);
                        //}
                    },
                    'json'
                );
                $(this).dialog('close');
            },
            "Cancel": function () {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });

    $('#lab_due_date').appendDtpicker();
}

function lab_delete(element) {
    var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    var labs = get_selected_lab(element);
    if (labs === false) return;
    var rows = element.closest('.tab-content').find('tbody').children();
    var message =  'Are you sure you want to delete the selected labs?<br />';
    create_ConfirmDialog('Delete the Selected Labs', message,
        function() {
            $.each(labs, function(index, lab) {
                var status = rows.children('td:contains(' + lab.project_name + ')').siblings('td:eq(10)');
                display_status(status, 'Deleting', null, '');
                $.post("cloud/delete_stack", {
                        "groupname": groupname,
                        "lab": lab
                    },
                    function(data) {
                        //alert(JSON.stringify(data));
                        if (data.status == 'Success') {
                            display_status(status, data.message, null, '');
                            setTimeout(function () {
                                project_delete(groupname, status, lab);
                            }, 500);
                        } else {
                            if (data.message == 'DELETE_IN_PROGRESS') {
                                display_status(status, data.message, null, '');
                                setTimeout(function () {
                                    update_deploy_list(groupname, status, lab, null, '');
                                }, 5000);
                            } else
                                display_status(status, data.message, null, '');
                        }
                    }
                );
            });
        },function() {
            // Cancel function
        });
}

function project_delete(groupname, status, lab) {
    display_status(status, 'Deleting Project', null, '');
    $.post("cloud/delete_project", {
            "groupname": groupname,
            "lab": lab
        },
        function(data) {
            //alert(JSON.stringify(data));
            if (data.status == 'Success') {
                //rows.children('td:contains(' + lab.project_name + ')').remove();
                status.closest('tr').remove();

                var tbody = $('#tbl_lab_deploy_list_' + groupname).find('tbody');
                var contextMenu = $(document.createElement('ul')).appendTo(tbody);
                contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'labDeploy-contextMenu')
                    .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
                $('<li><a tabindex="-1" href="#" class="lab-deploy">Deploy</a></li>').appendTo(contextMenu);
                $('<li><a tabindex="-1" href="#" class="lab-delete">Delete</a></li>').appendTo(contextMenu);
                $('<li><a tabindex="-1" href="#" class="lab-rename">Update Info</a></li>').appendTo(contextMenu);
            } else {
                display_status(status, data.message, null, '');
            }
        }
    );
}

function update_team_info(element) {
    var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    var team_id = element.closest('tr').children().eq(1).html();
    var team_name = element.closest('tr').children().eq(2).html();
    var team_desc = element.closest('tr').children().eq(3).html();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_update_team_info').attr('title', 'Update Team Information');

    $('<div><label for="update_team_name">Team name:</label><br>' +
        '<input type="text" id="update_team_name" value="' + team_name  + '"><br>' +
        '<label for="update_team_desc">Description:</label><br>' +
        '<textarea id="update_team_desc" style="width: 220px; height: 100px; resize: none;">' + team_desc +
        '</textarea></div>').appendTo(dlg_form);
    //var submit = $('<button />').attr('id', 'submit_update_team_info').text('Update')
    //    .attr('onclick', 'submit_update_team_info("' + groupname + '", ' + team_id + ')');
    //submit.appendTo(dlg_form);
     $('#dlg_update_team_info').dialog({
        modal: true,
        height: 250,
        overflow: "auto",
        width: 300,
         buttons: {
             "Update": function () {
                 var t_name = $('#update_team_name').val();
                 var t_desc = $('#update_team_desc').val();
                 $.post("cloud/updateSubGroup", {
                         "group_name": groupname,
                         "subgroup_id": team_id,
                         "subgroup_name": t_name,
                         "subgroup_desc": t_desc
                     },
                     function (jsondata) {
                         if (jsondata.status == "Success") {
                             $(element).closest('tr').children().eq(2).html(t_name);
                             $(element).closest('tr').children().eq(3).html(t_desc);
                         }
                         else {
                             $.jAlert({
                                 'title': 'Warning', 'content': jsondata.message,
                                 'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                             });
                         }
                     },
                     'json'
                 );
                 $(this).dialog('close');
             },
             "Cancel": function() {
                 $(this).dialog('close');
             }
         },
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function delete_team(team_id, element) {
    var message =  'Are you sure you want to delete team ' + $(element).attr('value') + ' ?<br />';
    create_ConfirmDialog('Delete a team', message,
        function() {
            var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
            $.post("cloud/delete_subgroup", {
                    "subgroup_id": team_id
                },
                function (jsondata) {
                    if (jsondata.status == "Success") {
                        element.closest('tr').remove();
                        var tbody = $('#tbl_team_list_' + groupname).find('tbody');
                        var contextMenu = $(document.createElement('ul')).appendTo(tbody);
                        contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'team-contextMenu')
                            .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
                        $('<li><a tabindex="-1" href="#" class="team-members">Members</a></li>').appendTo(contextMenu);
                        //$('<li><a tabindex="-1" href="#" class="go_pending_enroll">Waiting List</a></li>').appendTo(contextMenu);
                        //$('<li><a tabindex="-1" href="#" class="go_add_member">Invite Members</a></li>').appendTo(contextMenu);
                        $('<li><a tabindex="-1" href="#" class="team-edit">Rename</a></li>').appendTo(contextMenu);
                        $('<li><a tabindex="-1" href="#" class="team-delete">Delete</a></li>').appendTo(contextMenu);
                    }
                    else {
                        $.jAlert({
                            'title': 'Warning', 'content': jsondata.message,
                            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                        });
                    }
                },
                'json'
            );
        },function() {
            // Cancel function
        });
}

function assign_template(element) {
    var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    var checked_team = element.closest('div.tab').find('tbody input[type=checkbox]:checked:enabled');
    if (checked_team.length == 0) {
        $.jAlert({
            'title': 'Warning', 'content': 'You need to select at least one team to assign a lab template!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
        return;
    }
    var assign_teams = [];
    var team_names = [];
    for (var i = 0; i < checked_team.length; i++) {
        var tr = checked_team[i].parentNode.parentNode;
        var team = {team_id: tr.children[1].textContent, team_name: tr.children[2].textContent,
            templates: tr.children[5].textContent };
        assign_teams.push(team);
        team_names.push(tr.children[2].textContent);
    }

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_new_assign_template').attr('title', 'Assign Template');

    $('<div>Select Template: <select id="select_team_template"></select></div>').appendTo(dlg_form);
    $('<br><br><p>Assign the selected template to following subgroups: <br><br><p>' +
        team_names.toString().split(',').join('<br />') + '</p></p>').appendTo(dlg_form);
    //$('<button />').attr('id', 'new_assign_template_button').text('Assign')
    //    .attr('onclick', 'submit_new_assign_template("' + table_id + '")').appendTo(dlg_form);

    $('#dlg_new_assign_template').dialog({
        modal: true,
        height: 250,
        overflow: "auto",
        width: 400,
        buttons: {
            "Assign": function() {
                var selected_temp_id = $('#select_team_template :selected').val();
                var selected_temp_name = $('#select_team_template :selected').text();
                var duplicate = [];
                var assign_to = [];
                for (var i=0; i < assign_teams.length; i++) {
                    if ($.inArray(selected_temp_name, assign_teams[i].templates.split(',')) >= 0) {
                        duplicate.push(assign_teams[i].team_name);
                    } else {
                        assign_to.push(assign_teams[i]);
                    }
                }
                if (duplicate.length > 0) {
                    $.jAlert({
                        'title': 'Warning', 'content': 'The selected template has been already assigned to the team: ' + duplicate,
                        'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                    });
                }
                $.post("cloud/assignTemplateToTeams", {
                        "groupname": groupname,
                        "temp_id": selected_temp_id,
                        "teams": assign_to,
                        "lab_id": 0
                    },
                    function (data) {
                        if (data.failed.length > 0) {
                            $.jAlert({
                                'title': 'Warning', 'content': 'Assign the template ' + selected_temp_name + ' to ' + data.failed + ' failed.',
                                'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                            });
                        }
                    }).done(getTeamListTable(groupname));
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });

    //$.getJSON("cloud/getOwnClass", function (jsondata) {
    //    var table = $("#select_team_template");
    //    table.empty();
    //    $.each(jsondata, function (index, item) {
    //        table.append('<option name=="' + item.courseid + '" value="' + item.nameid + '">' + item.coursename + '</option>');
    //    });
    //});

    $.getJSON("cloud/getTemplate1", function (jsondata) {
        var select_temp = $("#select_team_template");
        $.each(jsondata, function (index, item) {
            if (index == 'templates') {
                $.each(item, function (index3, item3) {
                    $(select_temp).append('<option value="' + item3.id + '">' + item3.name + '</option>');
                });
            }
        })
    });
}

function assign_lab(element) {
    var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    var checked_team = element.closest('div.tab').find('tbody input[type=checkbox]:checked:enabled');
    if (checked_team.length == 0) {
        $.jAlert({
            'title': 'Warning', 'content': 'You need to select at least one team to assign a lab template!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
        return;
    }
    var assign_teams = [];
    var team_names = [];
    for (var i = 0; i < checked_team.length; i++) {
        var tr = checked_team[i].parentNode.parentNode;
        var team = {team_id: tr.children[1].textContent, team_name: tr.children[2].textContent,
            templates: tr.children[5].textContent };
        assign_teams.push(team);
        team_names.push(tr.children[2].textContent);
    }

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_new_assign_template').attr('title', 'Assign Template');

    $('<div>Select Lab: <select id="select_team_template"></select></div>').appendTo(dlg_form);
    $('<br><br><p>Assign the selected Lab to: <br><br><p>' + team_names.toString() + '</p></p>').appendTo(dlg_form);
    //$('<button />').attr('id', 'new_assign_template_button').text('Assign')
    //    .attr('onclick', 'submit_new_assign_template("' + table_id + '")').appendTo(dlg_form);

    $('#dlg_new_assign_template').dialog({
        modal: true,
        height: 250,
        overflow: "auto",
        width: 400,
        buttons: {
            "Assign": function() {
                var selected_temp_id = $('#select_team_template :selected').val();

                var selected_lab_id=($('#select_team_template :selected').attr('value1').length==0)?0:$('#select_team_template :selected').attr('value1');
                var selected_temp_name = $('#select_team_template :selected').text();
                var duplicate = [];
                var assign_to = [];
                for (var i=0; i < assign_teams.length; i++) {
                    if ($.inArray(selected_temp_name, assign_teams[i].templates.split(',')) >= 0) {
                        duplicate.push(assign_teams[i].team_name);
                    } else {
                        assign_to.push(assign_teams[i]);
                    }
                }
                if (duplicate.length > 0) {
                    $.jAlert({
                        'title': 'Warning', 'content': 'The selected template has been already assigned to the team: ' + duplicate,
                        'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                    });
                }
                $.post("cloud/assignTemplateToTeams", {
                        "groupname": groupname,
                        "temp_id": selected_temp_id,
                        "teams": assign_to,
                        "lab_id": selected_lab_id,
                        "assign_at": new Date().toISOString().slice(0,19).replace('T', ' ')
                    },
                    function (data) {
                        if (data.failed.length > 0) {
                            $.jAlert({
                                'title': 'Warning', 'content': 'Assign the template ' + selected_temp_name + ' to ' + data.failed + ' failed.',
                                'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                            });
                        }
                    }).done(getTeamListTable(groupname));
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });

    var table = $("#select_team_template");
    $.getJSON("cloud/getOwnClassTemp", function (jsondata) {
        //var table = $("#select_team_template");
        //table.empty();
        table.append('<option disabled style="font-weight: bold">My Own Labs:</option>');
        $.each(jsondata, function (index, item) {
            table.append('<option value1="' + item.labid + '" value="' + item.tempid + '">&nbsp;&nbsp;&nbsp;' + item.coursename + '</option>');
        });
    });

    $.getJSON("cloud/getOpenClassTemp", function (jsondata){
        //var table = $("seletct_team_template");
        table.append('<option disabled style="font-weight: bold">Open Labs:</option>');
        $.each(jsondata, function (index, item) {
            table.append('<option value1="' + item.labid + '" value="' + item.tempid + '">&nbsp;&nbsp;&nbsp;' + item.coursename + '</option>');
        });
    });

    //$.getJSON("cloud/getTemplate1", function (jsondata) {
    //    var select_temp = $("#select_team_template");
    //    $.each(jsondata, function (index, item) {
    //        if (index == 'templates') {
    //            $.each(item, function (index3, item3) {
    //                $(select_temp).append('<option value="' + item3.id + '">' + item3.name + '</option>');
    //            });
    //        }
    //    })
    //});
}

function update_team_members(team_id, team_name, team_members, element) {
    var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    //var team_name = $(element).attr('value');
    var teammembers =  team_members.split('<br>'); //$(element).attr('name').split(',');

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_subgroup_members').attr('title', 'Update Team Member for ' + team_name);

    var left_form = $(document.createElement('div')).css('float', 'left').appendTo(dlg_form);
    var right_list = $(document.createElement('div')).css('float', 'right').css('margin', '5px').appendTo(dlg_form);
    left_form.append('<div>' +
            '<br><label for="select_team_member_list">Team Members:</label><br><br>' +
            '<select id="select_team_member_list" MULTIPLE style="width: 170px"></select><br><br>');
    //$('<button />').attr('id', 'btn_update_team_members').attr('team_name', team_name).attr('team_id', team_id).text('Update')
    //    .attr('groupname', groupname).attr('onclick', 'submit_update_team_members($(this))').appendTo(left_form);
    $(document.createElement('button')).attr('id', 'btn_add_team_member')
        .css('position', 'absolute').css('left', '200px').css('top', '60px').text('<-').appendTo(dlg_form);
    $(document.createElement('button')).attr('id', 'btn_remove_team_member')
        .css('position', 'absolute').css('left', '200px').css('top', '90px').text('->').appendTo(dlg_form);

    var table = $(document.createElement('table')).addClass('data').attr('id','tbl_update_member_in_team').css('width','530px').appendTo(right_list);
    table.append('<thead><tr><th><input type="checkbox" name="team_update_member_checkbox_all" onclick="check_checkbox_group($(this))"></th>' +
    '<th class="hidden">UserId</th><th>Group User</th><th>Institute</th><th>Org ID (member id)</th><th>Role</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    $.post("/cloud/getGroupMembers", {
            "group_name": groupname
        },
        function (jsondata) {
            $.each(jsondata, function (index, item) {
                if (index == 'members') {
                    $.each(item, function (key, user) {
                        if ($.inArray(user.email, teammembers) >= 0) {
                            $('<option value="' + user.id + '">' + user.email + '</option>')
                                .attr('institute', user.institute).attr('org_id', user.org_id).attr('role', user.role)
                                .appendTo($('#select_team_member_list'));
                        } else {
                            if (user.role != 'GroupOwner') {
                                tbody.append('<tr><td><input type="checkbox" name="team_update_member_checkbox"></td><td class="hidden">' + user.id + '</td><td>' +
                                user.email + '</td><td>' + user.institute + '</td><td>' + user.org_id + '</td><td>' + user.role + '</td></tr>');
                            }
                        }
                    });
                    $('#btn_add_team_member').attr('title', 'Add team member').attr('onclick', 'change_team_member(1)');
                    $('#btn_remove_team_member').attr('title', 'Remove team member').attr('onclick', 'change_team_member(0)');
                }
            });
        },
        'json'
    );

    $('#dlg_subgroup_members').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 800,
        buttons: {
            "Update": function() {
                var members = [];
                var members_name = [];
                $('#select_team_member_list option').each(function () {
                    var userdata = {user_id: $(this).val(), user_name: $(this).text(), role: $(this).attr('role')};
                    members_name.push($(this).text());
                    members.push(userdata);
                });

                $(this).dialog('close');

                $.post("/cloud/update_subgroup_member", {
                        "team_name": team_name,
                        "team_id": team_id,
                        "members": members
                    },
                    function (jsondata) {
                        if (jsondata.status == 'Success') {
                            var members_names = (members_name.length == 0) ? " " : members_name.toString().split(',').join('<br />');
                            $(element).closest('tr').children().eq(4).html(members_names);
                        } else {
                            $.jAlert({
                                'title': 'Warning', 'content': jsondata.message + ' Please check Activity Log.',
                                'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                            });
                        }
                    },
                    'json'
                )
            },
            "Cancel": function() {
                $(this).dialog('close')
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function change_team_member(type) {
    var select_members = $('#select_team_member_list');
    var member_table = $('#tbl_update_member_in_team tbody');

    if (type == 1) {  // add
        var checked = member_table.find('input[type=checkbox]:checked:enabled');
        for (var i = 0; i < checked.length; i++) {
            //var user = checked[i].closest('tr');
            var user = checked[i].parentNode.parentNode;
            var opt = $('<option />').text(user.children[2].textContent)
                .attr('value', user.children[1].textContent).attr('institute', user.children[3].textContent)
                .attr('org_id', user.children[4].textContent).attr('role', user.children[5].textContent);
            opt.appendTo(select_members);
            checked[i].parentNode.parentNode.remove();
        }
    } else if (type == 0) { // remove
        $('#select_team_member_list :selected').each(function(index, selected) {
            member_table.append('<tr><td><input type="checkbox" name="team_update_member_checkbox"></td><td class="hidden">' +
            $(selected).val() + '</td><td>' + $(selected).text() + '</td><td>' + $(selected).attr('institute') + '</td><td>' +
            $(selected).attr('org_id') + '</td><td>' + $(selected).attr('role') + '</td></tr>');
            $(selected).remove();
        });
    }
}

function lab_refresh(element) {
    var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    getLabDeployListTable(groupname);
}