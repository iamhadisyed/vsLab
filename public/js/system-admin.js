/**
 * Created by James on 11/15/17.
 */

function system_admin_window(winId, win_main) {
    var tabs = {
        tabId: ['sys_config', 'sys_role_manager', 'sys_permission_manager', 'sys_site_manager', 'sys_project_manager'],
        tabName: ['System Configuration', 'Role', 'Permission', 'Site Resource Manager', 'Openstack Project Manager']
    };

    create_tabs(winId, win_main, tabs, null);

    $('<div><span style="font-size: medium; font-weight: 600">System Configuration for OpenStack</span><br><br><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_auth_url">Authentication URL:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_auth_url" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_region">Region:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_region" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_users_admin_name" >User Admin Name:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_users_admin_name" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_users_admin_id" >User Admin ID:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_users_admin_id" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_admin_password" >User Admin Password:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_admin_password" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_user_domain_name" >User Domain Name:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_user_domain_name" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_user_domain_id" >User Domain ID:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_user_domain_id" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_user_role_id" >User Role ID:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_user_role_id" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="sys_config_dummy_project_id" >Dummy Project ID:</label></td>' +
        '<td class="noBorder"><input type="text" id="sys_config_dummy_project_id" size="60" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"></td>' +
        '<td class="noBorder"><button class="submit" style="float: right;" onclick="sys_admin_save_sys_config($(this))">Save</button><br></td></tr>' +
        '</table></div>').appendTo($('#sys_config'));

    sys_admin_sys_config();

    var role_create_button = $(document.createElement('div')).appendTo($('#sys_role_manager'));
    role_create_button.append('<button class="submit" onclick="sys_admin_role_operations($(this), 1)">Add New Role</button>&nbsp;');
    var role_table = $(document.createElement('table')).appendTo($('#sys_role_manager'));
    role_table.addClass("data").attr("id", "table_sys_role_manager").append(
        "<thead><tr><th class='hidden'>ID</th><th>Name</th><th>Description</th><th>Type</th><th>Guard</th><th>Permissions</th><th>Created</th><th>Updated</th><th>Actions</th></tr></thead>");
    var role_tbody = $(document.createElement('tbody')).appendTo(role_table);

    var perm_create_button = $(document.createElement('div')).appendTo($('#sys_permission_manager'));
    perm_create_button.append('<button class="submit" onclick="sys_admin_permission_operations($(this), 1)">Add New Permission</button>&nbsp;');
    var perm_table = $(document.createElement('table')).appendTo($('#sys_permission_manager'));
    perm_table.addClass("data").attr("id", "table_sys_perm_manager").append(
        "<thead><tr><th class='hidden'>ID</th><th>Name</th><th>Description</th><th>Type</th><th>Guard</th><th>Created</th><th>Updated</th><th>Actions</th></tr></thead>");
    var perm_tbody = $(document.createElement('tbody')).appendTo(perm_table);

    var project_create_button = $(document.createElement('div')).appendTo($('#sys_project_manager'));
    project_create_button.append('<button class="submit" onclick="sys_admin_project_config()">Add New Project</button>&nbsp;');

    var project_table = $(document.createElement('table')).appendTo($('#sys_project_manager'));
    project_table.addClass("data").attr("id", "table_sys_proj_manager").append(
        "<thead><tr><th>Name</th><th>Description</th><th>Project ID</th><th>Domain</th><th>Enabled</th><th>Actions</th></tr></thead>");
    var project_tbody = $(document.createElement('tbody')).appendTo(project_table);

    var html_site_manage = '<div>' +
        '<button class="submit" onclick="sys_admin_site_operations($(this), 1)">Add New Site</button>' +
        '<table class="data" id="table_sys_site_manager">' +
        '<thead><tr><th class="hidden">ID</th><th>Name</th><th>Description</th><th>Available Resources</th><th>Administrators</th>' +
        '<th>Created</th><th>Updated</th><th>Actions</th></tr></thead>' +
        '<tbody></tbody></table></div>';
    $(html_site_manage).appendTo($('#sys_site_manager'));
}

function sys_admin_sys_config(tab) {

    $.getJSON("sysadmin/getSystemConfigData", function (data) {
        $('#sys_config_auth_url').attr('value', data.auth_url);
        $('#sys_config_region').attr('value', data.region);
        $('#sys_config_users_admin_name').attr('value', data.users_admin_name);
        $('#sys_config_users_admin_id').attr('value', data.users_admin_id);
        $('#sys_config_admin_password').attr('value', data.admin_password);
        $('#sys_config_user_domain_name').attr('value', data.user_domain_name);
        $('#sys_config_user_domain_id').attr('value', data.user_domain_id);
        $('#sys_config_user_role_id').attr('value', data.user_role_id);
        $('#sys_config_dummy_project_id').attr('value', data.dummy_project_id);
    });
}

function sys_admin_save_sys_config(element) {

    $.post("sysadmin/postSystemConfigData", {
        "auth_url":        $.trim($('#sys_config_auth_url').val()),
        "region":           $.trim($('#sys_config_region').val()),
        "users_admin_name": $.trim($('#sys_config_users_admin_name').val()),
        "users_admin_id":   $.trim($('#sys_config_users_admin_id').val()),
        "admin_password":   $.trim($('#sys_config_admin_password').val()),
        "user_domain_name": $.trim($('#sys_config_user_domain_name').val()),
        "user_domain_id":   $.trim($('#sys_config_user_domain_id').val()),
        "user_role_id":     $.trim($('#sys_config_user_role_id').val()),
        "dummy_project_id": $.trim($('#sys_config_dummy_project_id').val())
        },
        function (result) {
            if (result.status === 'Success') {
                swal('The configuration has been set.');
            } else {
                swal('', result.message, 'warning');
            }
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
        alert(xhr.responseText);
    });
}

function system_project_manager() {
    var tab = $('#table_sys_proj_manager');
    var tbody = $(tab).find('tbody');
    tbody.empty();

    var win_main = $(tab).closest('div.tab');
    run_waitMe(win_main, 'ios');
    $.getJSON("/cloud/getProjectList/", function (projects) {
        $.each(projects, function (index, project) {
            var tr = $('<tr />').appendTo(tbody);
            tr.append(
                //'<td><input type="checkbox" name="subgroups_checkbox" value="' + team.id + '"></td>' +
                '<td>' + project.name + '</td>' +
                '<td>' + project.description + '</td>' +
                '<td>' + project.id + '</td>' +
                '<td>' + project.domain + '</td>' +
                '<td>' + ((project.enabled) ? 'Yes' : 'No') + '</td>' +
                '<td class="dropdown"><a class="btn btn-default sys-admin-proj-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>'
            );
        });
        $(win_main).waitMe('hide');
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'sys-admin-proj-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="sys-proj-members">Members</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="sys-proj-config">Update Info</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="sys-proj-usage">Usage</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="sys-proj-delete">Delete</a></li>').appendTo(contextMenu);
}

function sys_admin_project_config(element) {
    var isUpdate = true;
    var project = {};
    if (element === null || element === undefined) {
        isUpdate = false;
        project = {'pId': null, 'pName': '', 'pDesc': '', 'pDomain': 'Users', 'pEnabled': true};
    } else {
        var pName = element.closest('tr').children().eq(0).html();
        var pDesc = element.closest('tr').children().eq(1).html();
        var pId = element.closest('tr').children().eq(2).html();
        var pDomain = element.closest('tr').children().eq(3).html();
        var pEnabled = element.closest('tr').children().eq(4).html();
        project = {'pId': pId, 'pName': pName, 'pDesc': pDesc, 'pDomain': pDomain, 'pEnabled': (pEnabled === 'Yes')};
    }

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_sys_admin_proj_config').attr('title', (!isUpdate) ? 'Create Project' : 'Update Project');
    var tabs = $(document.createElement('div')).appendTo(dlg_form);
    tabs.attr('id', 'tabs-sys-proj-config').append($('<ul>' +
        '<li><a href="#tabs-proj-info">Project Information</a></li>' +
        '<li><a href="#tabs-proj-members">Project Members</a></li>' +
        '<li><a href="#tabs-proj-quota">Quota</a></li>' +
        '</ul>' +
        '<div id="tabs-proj-info" style="overflow: hidden;"></div>' +
        '<div id="tabs-proj-members" style="overflow: hidden;"></div>' +
        '<div id="tabs-proj-quota" style="overflow: hidden;"></div>'
    ));

    var proj_info_html = '<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_config_domain">Domain Name:</label></td>' +
            '<td class="noBorder"><input type="text" id="proj_config_domain" size="50" disabled value="' + project['pDomain'] + '" style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_config_name">Project Name:</label></td>' +
            '<td class="noBorder"><input type="text" id="proj_config_name" size="50" value="' + project['pName'] + '" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_config_desc" >Project Description:</label></td>' +
            '<td class="noBorder"><textarea id="proj_config_desc" rows="10" cols="50" style="vertical-align: top; resize: none">' + project['pDesc'] + '</textarea><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_config_enabled">Enabled</label></td>' +
            '<td class="noBorder"><input type="checkbox" id="proj_config_enabled" ' + ((project['pEnabled']) ? 'checked' : ' ') + ' /></td></tr>' +
        '<tr class="noBorder"><td class="hidden" id="proj_config_id">' + project['pId'] + '</td><td class="noBorder"></td></tr>' +
        '</table></div>';
    $(proj_info_html).appendTo($('#tabs-proj-info'));

    sys_admin_dlg_config_members(project['pId'], $('#tabs-proj-members'));

    var proj_quota_html = '<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_instances">Instances:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_instances" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_vcpus">VCPUs:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_vcpus" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_ram" >RAM (MB):</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_ram" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_metadata">Metadata Items:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_metadata" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_volumes">Volumes:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_volumes" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_snapshots">Volume Snapshots:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_snapshots" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_snapshot_size">Total Size of Volumes and Snapshots (GiB):</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_snapshot_size" size="50" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_fips">Floating IPs:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_fips" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_nets">Networks:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_nets" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_subnets">Subnets:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_subnets" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_ports">Ports:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_ports" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="proj_quota_routers">Routers:</label></td>' +
        '<td class="noBorder"><input type="text" id="proj_quota_routers" size="50" /><br></td></tr>' +
        '<tr class="noBorder"><td class="hidden" id="proj_quota_id">' + project['pId'] + '</td><td class="noBorder"></td></tr>' +
        '</table></div>';
    $(proj_quota_html).appendTo($('#tabs-proj-quota'));

    sys_admin_dlg_config_quota(project['pId'], $('#tabs-proj-quota'));

    $('#tabs-sys-proj-config').tabs();

    if (isUpdate) sys_admin_dlg_update_project(dlg_form);
    else sys_admin_dlg_create_project(dlg_form);
}

function sys_admin_project_members(element) {
    sys_admin_project_config(element);
    $('#tabs-sys-proj-config').tabs('option', 'active', 1);
}

function sys_admin_dlg_config_members(project_id, dlg_form) {
    var div_container = $(document.createElement('table')).attr('class', 'noBorder').attr('width', '100%').appendTo(dlg_form);
    var tr_container = $(document.createElement('tr')).attr('class', 'noBorder').appendTo(div_container);
    var left_form = $(document.createElement('td')).attr('class', 'noBorder').css('width', '200px').appendTo(tr_container);
    left_form.append(
        '<label for="select_proj_member_list">All Users:</label><br><br>' +
        '<input type="text" placeholder="Search email" id="sys_admin_search_user" style="width: 200px;" onkeyup="sys_admin_search_user_filter($(this))"/>' +
        '<select id="select_proj_member_list" MULTIPLE style="width: 200px; height: 260px"></select><br>' +
        '<h3 id="sys_admin_proj_member_user_counts"></h3>'
    );

    var middle = $(document.createElement('td')).attr('class', 'noBorder')
        .css('vertical-align', 'middle').css('text-align', 'center').css('width', '30px').appendTo(tr_container);
    $(document.createElement('button')).attr('id', 'btn_remove_proj_member').text('->').attr('title', 'Add Member')
        .attr('onclick', 'sys_admin_update_proj_member($(this), 1)').appendTo(middle);
    $('<br/><br/>').appendTo(middle);
    $(document.createElement('button')).attr('id', 'btn_add_proj_member').text('<-').attr('title', 'Remove Member')
        .attr('onclick', 'sys_admin_update_proj_member($(this), 0)').appendTo(middle);

    var right_list = $(document.createElement('td')).appendTo(tr_container);
    $('<label for="tbl_proj_members">Project Members:</label><br><br>').appendTo(right_list);
    var members = $(document.createElement('table')).addClass('data').attr('id','tbl_proj_members').css('width','100%').appendTo(right_list);
    members.append('<thead><tr><th><input type="checkbox" name="project_update_member_checkbox_all" onclick="check_checkbox_group($(this))"></th>' +
        '<th class="hidden">UserId</th><th>User Email</th><th>Role</th></tr></thead>');
    var m_tbody = $(document.createElement('tbody')).appendTo(members);

    var proj_users = [];
    if (project_id) {
        $.getJSON("cloud/getProjectUserList/" + project_id, function (users) {
            $.each(users, function (index, user) {
                var roles = '';
                $.each(user.roles, function (idx, role) {
                    roles += role.name + ','
                });
                m_tbody.append('<tr><td><input type="checkbox" name="team_update_member_checkbox"></td>' +
                    '<td class="hidden">' + user.id + '</td>' +
                    '<td><div style="width: 140px; word-break: break-all;" >' + user.email + '</td>' +
                    '<td>' + roles.slice(0, -1) + '</td></tr>');
                proj_users[user.id] = user.email;
            });
        });
    }

    var select_list = $('#select_proj_member_list');
    var user_counts_clone = $('#sys_admin_proj_member_user_counts');

    run_waitMe(dlg_form, 'ios');
    $.getJSON("cloud/getAllUserList", function (users) {
        var user_counts = 0;
        select_list.empty();
        $.each(users, function (index, user) {
            if (proj_users[user.id] === undefined) {
                select_list.append($(document.createElement('option')).attr('value', user.id).text(user.email));
                user_counts++;
            }
        });
        user_counts_clone.html(user_counts + ' Users.');
        $(dlg_form).waitMe('hide');
    });
}

function sys_admin_update_proj_member(element, action) {
    var user_list = element.closest('tr').find('select'); // $('#select_proj_member_list');
    var member_table = element.closest('tr').find('table').find('tbody'); //$('#tbl_proj_members tbody');

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

    } else if (action === 1) {    // add
        //var domain = sys_admin_get_domain('user');
        user_list.find('option:selected').each(function(index, selected) {
            member_table.append('<tr><td><input type="checkbox" name="group_admin_update_member_checkbox"></td>' +
                '<td class="hidden">' + $(selected).val() + '</td>' +
                '<td><div style="width: 140px; word-break: break-all;" >' + $(selected).text() + '</div></td>' +
                '<td>' + 'user' + '</td></tr>');
            $(selected).remove();
        });
    }
}

function sys_admin_get_domain(name) {
    var domain = {'id': '', 'name': ''};
    $.getJSON("cloud/getDomain/" + name, function (result) {
        domain['id'] = result.id;
        domain['name'] = result.name;
    });
    return domain;
}

function sys_admin_search_user_filter(input) {
    var current, i, filter,
        options = input.next().find('option'); // $('#select_proj_member_list').find('option');

    filter = $(input).val();
    i = 1;
    $(options).each(function(){
        current = $(this);
        $(current).removeAttr('selected');
        if ($(current).text().indexOf(filter) !== -1) {
            $(current).show();
            if(i === 1){
                $(current).attr('selected', 'selected');
            }
            i++;
        } else {
            $(current).hide();
        }
        input.parent().find('h3').html(i-1 + ' users found.');
    });
}

function sys_admin_dlg_config_quota(pId, dlg_form) {
    var instances = $('#proj_quota_instances'),
        vcpus = $('#proj_quota_vcpus'),
        ram = $('#proj_quota_ram'),
        volumes = $('#proj_quota_volumes'),
        snapshots = $('#proj_quota_snapshots'),
        snapshot_size = $('#proj_quota_snapshot_size'),
        metadata = $('#proj_quota_metadata'),
        fips = $('#proj_quota_fips'),
        nets = $('#proj_quota_nets'),
        subnets = $('#proj_quota_subnets'),
        ports = $('#proj_quota_ports'),
        routers = $('#proj_quota_routers');

    if (pId) {
        run_waitMe(dlg_form, 'ios');
        $.getJSON("cloud/getQuota/" + pId, function (data) {
            instances.attr('value', data.instances);
            vcpus.attr('value', data.vcpus);
            ram.attr('value', data.ram);
            volumes.attr('value', data.volumes);
            snapshots.attr('value', data.snapshots);
            snapshot_size.attr('value', data.snapshot_size);
            metadata.attr('value', data.metadata);
            fips.attr('value', data.fips);
            nets.attr('value', data.nets);
            subnets.attr('value', data.subnets);
            ports.attr('value', data.ports);
            routers.attr('value', data.routers);
            $(dlg_form).waitMe('hide')
        })
    }
}

function sys_admin_dlg_update_project(dlg_form) {
    $(dlg_form).dialog({
        modal: true,
        height: 510,
        overflow: "auto",
        width: 610,
        buttons: {
            "Update": function () {
                var proj_name = $.trim($('#proj_config_name').val());
                if (proj_name.indexOf(' ') !== -1) {
                    swal('Oops...', 'White space is not allowed in the Project Name!', 'warning');
                    return;
                }
                if (proj_name.length <= 0) {
                    swal('Oops...', 'Please enter project Name!', 'warning');
                    return;
                }
                var project = {'id': $('#proj_config_id').html(), 'name': proj_name, 'desc': $.trim($('#proj_config_desc').val()),
                    'domain': $('#proj_config_domain').val(), 'enabled': $('#proj_config_enabled').is(':checked')};
                var members = [];
                var member_table = $('#tbl_proj_members').find('tbody');
                var checked = member_table.find('input[type=checkbox]');
                for (var i = 0; i < checked.length; i++) {
                    var user = checked[i].parentNode.parentNode;
                    members.push({id: user.children[1].textContent, email: user.children[2].textContent, roles: user.children[3].textContent});
                }
                $.post("cloud/updateProject", {
                        "project": project,
                        "members": members
                    },
                    function(newP) {
                        if (newP.status === 'Success') {
                            var projId = $('#table_sys_proj_manager').find('tbody td:contains(' + project['id'] + ')');
                            projId.prev().html(project['desc']);
                            projId.prev().prev().html(project['name']);
                            projId.next().next().html((project['enabled']) ? 'Yes' : 'No');
                            swal('', newP.message, 'success');
                        } else {
                            swal('Oops...', newP.message, 'warning');
                        }
                    },
                    'json'
                );
                $(this).empty();
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).empty();
                $(this).dialog('close');
            }
        },
        close: function() {
            $(this).empty();
        }
    });
}

function sys_admin_dlg_create_project(dlg_form) {
    $(dlg_form).dialog({
        modal: true,
        height: 510,
        overflow: "auto",
        width: 610,
        buttons: {
            "Create": function () {
                var proj_name = $.trim($('#proj_config_name').val());
                if (proj_name.indexOf(' ') !== -1) {
                    swal('Oops...', 'White space is not allowed in the Project Name!', 'warning');
                    return;
                }
                if (proj_name.length <= 0) {
                    swal('Oops...', 'Please enter project Name!', 'warning');
                    return;
                }
                var project = {'name': proj_name, 'desc': $.trim($('#proj_config_desc').val())};
                var members = [];
                var member_table = $('#tbl_proj_members').find('tbody');
                var checked = member_table.find('input[type=checkbox]');
                for (var i = 0; i < checked.length; i++) {
                    var user = checked[i].parentNode.parentNode;
                    members.push({id: user.children[1].textContent, email: user.children[1].textContent, roles: user.children[3].textContent});
                }
                $.post("cloud/createProject", {
                        "project": project,
                        "members": members
                    },
                    function(newP) {
                        if (newP.status === 'Success') {
                            var tbody = $('#table_sys_proj_manager').find('tbody');
                            $('<tr>' +
                            //'<td><input type="checkbox" name="subgroups_checkbox" value="' + team.id + '"></td>' +
                            '<td>' + newP.project.name + '</td>' +
                            '<td>' + newP.project.description + '</td>' +
                            '<td>' + newP.project.id + '</td>' +
                            '<td>' + 'Users' + '</td>' +
                            '<td>' + 'Yes' + '</td>' +
                            '<td class="dropdown"><a class="btn btn-default sys-admin-proj-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                            '</tr>').prependTo(tbody);
                            swal('', newP.message, 'success');
                        } else {
                            swal('Oops...', newP.message, 'warning');
                        }
                    },
                    'json'
                );
                $(this).empty();
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).empty();
                $(this).dialog('close');
            }
        },
        close: function() {
            $(this).empty();
        }
    });
}

// function sys_admin_project_info(project) {
//     $('#tabs-sys-proj-config').tabs('option', 'active', 0);
// }

function sys_admin_project_quota(project) {
    $('#tabs-sys-proj-config').tabs('option', 'active', 2);
}

function sys_admin_project_delete(element) {
    var pId = element.closest('tr').children().eq(2).html();
    var message = 'Are you sure you want to delete the project?';
    create_ConfirmDialog('Delete A Project', message, function() {
        $.post("cloud/deleteProject", {
                "projectId": pId
            },
            function (result) {
                if ((result.status === 'Success')) {
                    element.closest('tr').remove();
                    var tbody = $('#table_sys_proj_manager').find('tbody');
                    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
                    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'sys-admin-proj-contextMenu')
                        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
                    $('<li><a tabindex="-1" href="#" class="sys-proj-members">Members</a></li>').appendTo(contextMenu);
                    $('<li><a tabindex="-1" href="#" class="sys-proj-config">Update Info</a></li>').appendTo(contextMenu);
                    $('<li><a tabindex="-1" href="#" class="sys-proj-usage">Usage</a></li>').appendTo(contextMenu);
                    $('<li><a tabindex="-1" href="#" class="sys-proj-delete">Delete</a></li>').appendTo(contextMenu);
                    swal('', result.message, 'success');
                } else {
                    swal('Oops...', result.message, 'warning');
                }
            },
            'json'
        );
    }, function () {
        // Cancel function
    });
}

function sys_admin_role_manager() {
    var tbody = $('#table_sys_role_manager').find('tbody').empty();

    $.getJSON('sysadmin/getRoleList', function(items) {
        $.each(items, function (index, item) {
            tbody.append('<tr><td class="hidden">' + item.id +'</td><td>' + item.name + '</td><td>' + item.desc + '</td><td>' +
                item.type + '</td><td>' + item.guard + '</td><td><a href="#" title="' + item.permissions + '">' + item.permissions.split(',').length + ' permissions' + '</a></td><td>' +
                item.created + '</td><td>' + item.updated + '</td><td>' +
                '<button onclick="sys_admin_role_operations($(this),2)">Edit</button>&nbsp;' + '' +
                '<button onclick="sys_admin_role_operations($(this),0)">Delete</button>' +
                '</td></tr>');
        })
    });
}

function sys_admin_role_operations(element, action) {
    var role = {};
    if (action === 0) { // delete
        swal('Oops...', 'Role deletion function is not implemented yet!', 'warning');
        return;
    } else if (action === 1) { // add
        role = {'id': null, 'name': '', 'type': 'default', 'guard': 'web', 'desc': '', 'perms': null};
    } else if (action === 2) {  // update
        var rId = element.closest('tr').children().eq(0).html();
        var rName = element.closest('tr').children().eq(1).html();
        var rDesc = element.closest('tr').children().eq(2).html();
        var rType = element.closest('tr').children().eq(3).html();
        var rGuard = element.closest('tr').children().eq(4).html();
        var rPerms = element.closest('tr').find('a').attr('title');
        role = {'id': rId, 'name': rName, 'type': rType, 'guard': rGuard, 'desc': rDesc, 'perms': rPerms};
    }

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_sys_admin_role').attr('title', (action === 1) ? 'Create Role' : 'Edit Role');

    var role_info_html = '<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_role_name">Role Name:</label></td>' +
        '<td class="noBorder"><input type="text" id="dlg_sys_admin_role_name" size="50" value="' + role['name'] + '" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_role_type">Role Type:</label></td>' +
        '<td class="noBorder"><input type="text" id="dlg_sys_admin_role_type" size="50" value="' + role['type'] + '" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_role_guard">Guard Name:</label></td>' +
        '<td class="noBorder"><input type="text" id="dlg_sys_admin_role_guard" size="50" value="' + role['guard'] + '" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_role_desc" >Role Description:</label></td>' +
        '<td class="noBorder"><textarea id="dlg_sys_admin_role_desc" rows="5" cols="50" style="vertical-align: top; resize: none">' + role['desc'] + '</textarea><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_role_perm" >Permissions:</label></td>' +
        '<td class="noBorder"><div id="dlg_sys_admin_role_perm_list"></div></td></tr>' +
        '<tr class="noBorder"><td class="hidden" id="dlg_sys_admin_role_id">' + role['id'] + '</td><td class="noBorder"></td></tr>' +
        '</table></div>';
    $(role_info_html).appendTo(dlg_form);

    sys_admin_dlg_role_perm_checklist(role['perms']);

    sys_admin_dlg_role(dlg_form, element, action);
}

function sys_admin_dlg_role_perm_checklist(perms) {
    var chk_div = $('#dlg_sys_admin_role_perm_list');
    $(document.createElement('button')).attr('onclick', 'sys_admin_dlg_role_perm_checks(1,null)').html('Check All').appendTo(chk_div);
    $(document.createElement('button')).attr('onclick', 'sys_admin_dlg_role_perm_checks(0,null)').html('Clear All').appendTo(chk_div);

    if (perms !== null) {
        $(document.createElement('button')).attr('onclick', 'sys_admin_dlg_role_perm_checks(3,"' + perms + '")').html('Restore').appendTo(chk_div);
    }

    $('<br />').appendTo(chk_div);

    $.getJSON('sysadmin/getPermissionList', function(items) {
        var chks = [];
        $.each(items, function (index, item) {
            chks.push({id: item.id, name: item.name});
        });

        for (var i=0; i < chks.length; i++) {
            $('<input />', {type: 'checkbox', name: 'perm-check-list', id: 'sys_admin_role_perms-'+ chks[i]['id'], value: chks[i]['name']}).css('vertical-align', '-2px').appendTo(chk_div);
            $('<label />', {'for': 'sys_admin_role_perms-' + chks[i]['id'], text: chks[i]['name']}).append('&nbsp;').css('font-weight', '100').css('font-size', 'small').appendTo(chk_div);
            if ((i+1)%4 === 0)
                $('<br />').appendTo(chk_div);
        }
        if (perms !== null) {
            sys_admin_dlg_role_perm_checks(3, perms)
        }
    });
}

function sys_admin_dlg_role_perm_checks(action, perms) {
    var chkbox = $('#dlg_sys_admin_role_perm_list').find('input[type="checkbox"]');
    if (action === 3) {
        var perm_arry = perms.split(',');
        for (var i = 0; i < chkbox.length; i++) {
            chkbox[i].checked = ($.inArray(chkbox[i].value, perm_arry) > -1);
        }
    } else {
        for (var j = 0; j < chkbox.length; j++) {
            chkbox[j].checked = (action === 1);
        }
    }
}

function sys_admin_dlg_role_get_perms() {
    var chkbox = $('#dlg_sys_admin_role_perm_list').find('input[type=checkbox]');
    var checked_perms_id = [];
    var checked_perms_name = [];
    for (var i=0; i < chkbox.length; i++) {
        if (chkbox[i].checked) {
            checked_perms_id.push(chkbox[i].id.slice('sys_admin_role_perms-'.length));
            checked_perms_name.push(chkbox[i].value);
        }
    }
    return {id: checked_perms_id, name: checked_perms_name};
}

function sys_admin_dlg_role(dlg_form, element, action) {
    var myButtons = {};
    if (action === 1) {  // create
        myButtons = {
            "Create": function () {
                var role_name = $.trim($('#dlg_sys_admin_role_name').val());
                if (role_name.indexOf(' ') !== -1) {
                    swal('Oops...', 'White space is not allowed in the Role Name!', 'warning');
                    return;
                }
                if (role_name.length <= 0) {
                    swal('Oops...', 'Please enter Role Name!', 'warning');
                    return;
                }
                var role = {'name': role_name, 'desc': $.trim($('#dlg_sys_admin_role_desc').val()),
                    'type': $.trim($('#dlg_sys_admin_role_type').val()), 'guard': $.trim($('#dlg_sys_admin_role_guard').val()),
                    'perms': sys_admin_dlg_role_get_perms().id};
                $.post("sysadmin/addRole", {
                        "role": role
                    },
                    function(item) {
                        if (item.status === 'Success') {
                            var tbody = $('#table_sys_role_manager').find('tbody');
                            $('<tr><td class="hidden">' + item.id + '</td><td>' + item.name + '</td><td>' + item.desc + '</td><td>' +
                                item.type + '</td><td>' + item.guard + '</td><td>' +
                                '<a href="#" title="' + item.permissions + '">' + item.permissions.split(',').length + ' permissions</a>' + '</td><td>' +
                                item.created + '</td><td>' + item.updated + '</td><td>' +
                                '<button onclick="sys_admin_role_operations($(this),2)">Edit</button>&nbsp;' + '' +
                                '<button onclick="sys_admin_role_operations($(this),0)">Delete</button>' +
                                '</td></tr>').prependTo(tbody);
                            swal('', item.message, 'success');
                        } else {
                            swal('Oops...', item.message, 'warning');
                        }
                    },
                    'json'
                );
                $(this).empty();
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).empty();
                $(this).dialog('close');
            }
        }
    } else if (action === 2) {  // update
        myButtons = {
            "Update": function () {
                var role_name = $.trim($('#dlg_sys_admin_role_name').val());
                if (role_name.indexOf(' ') !== -1) {
                    swal('Oops...', 'White space is not allowed in the Role Name!', 'warning');
                    return;
                }
                if (role_name.length <= 0) {
                    swal('Oops...', 'Please enter Role Name!', 'warning');
                    return;
                }
                var role = {'name': role_name, 'description': $.trim($('#dlg_sys_admin_role_desc').val()),
                    'type': $.trim($('#dlg_sys_admin_role_type').val()), 'guard_name': $.trim($('#dlg_sys_admin_role_guard').val())};

                $.post("sysadmin/updateRole/" + $('#dlg_sys_admin_role_id').html(), {
                        "role": role,
                        "permissions": sys_admin_dlg_role_get_perms().id
                    },
                    function(item) {
                        if (item.status === 'Success') {
                            element.closest('tr').children().eq(1).html(role['name']);
                            element.closest('tr').children().eq(2).html(role['description']);
                            element.closest('tr').children().eq(3).html(role['type']);
                            element.closest('tr').children().eq(4).html(role['guard_name']);
                            element.closest('tr').children().eq(5).html('<a href="#" title="' + item['permissions'] + '">' + item['permissions'].split(',').length + ' permissions</a>');
                            element.closest('tr').children().eq(7).html(item.updated);
                            swal('', item.message, 'success');
                        } else {
                            swal('Oops...', item.message, 'warning');
                        }
                    },
                    'json'
                );
                $(this).empty();
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).empty();
                $(this).dialog('close');
            }
        }
    }

    $(dlg_form).dialog({
        modal: true,
        height: 510,
        overflow: "auto",
        width: 610,
        buttons: myButtons,
        close: function() {
            $(this).empty();
        }
    });
}

function sys_admin_permission_manager() {
    var tbody = $('#table_sys_perm_manager').find('tbody').empty();

    $.getJSON('sysadmin/getPermissionList', function(items) {
        $.each(items, function (index, item) {
            tbody.append('<tr><td class="hidden">' + item.id + '</td><td>' + item.name + '</td><td>' + item.desc + '</td><td>' +
                item.type + '</td><td>' + item.guard + '</td><td>' + item.created + '</td><td>' + item.updated + '</td><td>' +
                '<button onclick="sys_admin_permission_operations($(this),2)">Edit</button>&nbsp;' + '' +
                '<button onclick="sys_admin_permission_operations($(this),0)">Delete</button>' +
                '</td></tr>');
        })
    });
}

function sys_admin_permission_operations(element, action) {
    var perm = {};
    if (action === 0) { // delete
        swal('Oops...', 'Permission cannot be deleted!', 'warning');
        return;
    } else if (action === 1) { // add
        perm = {'id': null, 'name': '', 'type': 'default', 'guard': 'web', 'desc': ''};
    } else if (action === 2) {  // update
        var pId = element.closest('tr').children().eq(0).html();
        var pName = element.closest('tr').children().eq(1).html();
        var pDesc = element.closest('tr').children().eq(2).html();
        var pType = element.closest('tr').children().eq(3).html();
        var pGuard = element.closest('tr').children().eq(4).html();
        perm = {'id': pId, 'name': pName, 'type': pType, 'guard': pGuard, 'desc': pDesc};
    }

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_sys_admin_permission').attr('title', (action === 1) ? 'Create Permission' : 'Edit Permission');

    var perm_info_html = '<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_perm_name">Permission Name:</label></td>' +
        '<td class="noBorder"><input type="text" id="dlg_sys_admin_perm_name" size="50" value="' + perm['name'] + '" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_perm_type">Permission Type:</label></td>' +
        '<td class="noBorder"><input type="text" id="dlg_sys_admin_perm_type" size="50" value="' + perm['type'] + '" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_perm_guard">Guard Name:</label></td>' +
        '<td class="noBorder"><input type="text" id="dlg_sys_admin_perm_guard" size="50" value="' + perm['guard'] + '" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_perm_desc" >Permission Description:</label></td>' +
        '<td class="noBorder"><textarea id="dlg_sys_admin_perm_desc" rows="10" cols="50" style="vertical-align: top; resize: none">' + perm['desc'] + '</textarea><br><br></td></tr>' +
        '<tr class="noBorder"><td class="hidden" id="dlg_sys_admin_perm_id">' + perm['id'] + '</td><td class="noBorder"></td></tr>' +
        '</table></div>';
    $(perm_info_html).appendTo(dlg_form);

    sys_admin_dlg_permission(dlg_form, element, action)
}

function sys_admin_dlg_permission(dlg_form, element, action) {
    var myButtons = {};
    if (action === 1) {  // create
        myButtons = {
            "Create": function () {
                var perm_name = $.trim($('#dlg_sys_admin_perm_name').val());
                if (perm_name.indexOf(' ') !== -1) {
                    swal('Oops...', 'White space is not allowed in the Permission Name!', 'warning');
                    return;
                }
                if (perm_name.length <= 0) {
                    swal('Oops...', 'Please enter permission Name!', 'warning');
                    return;
                }
                var perm = {'name': perm_name, 'desc': $.trim($('#dlg_sys_admin_perm_desc').val()),
                    'type': $.trim($('#dlg_sys_admin_perm_type').val()), 'guard': $.trim($('#dlg_sys_admin_perm_guard').val())};
                $.post("sysadmin/addPermission", {
                        "permission": perm
                    },
                    function(item) {
                        if (item.status === 'Success') {
                            var tbody = $('#table_sys_perm_manager').find('tbody');
                            $('<tr><td class="hidden">' + item.id + '</td><td>' + item.name + '</td><td>' + item.desc + '</td><td>' +
                                item.type + '</td><td>' + item.guard + '</td><td>' + item.created + '</td><td>' + item.updated + '</td><td>' +
                                '<button onclick="sys_admin_permission_operations($(this),2)">Edit</button>&nbsp;' + '' +
                                '<button onclick="sys_admin_permission_operations($(this),0)">Delete</button>' +
                                '</td></tr>').prependTo(tbody);
                            swal('', item.message, 'success');
                        } else {
                            swal('Oops...', item.message, 'warning');
                        }
                    },
                    'json'
                );
                $(this).empty();
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).empty();
                $(this).dialog('close');
            }
        }
    } else if (action === 2) {  // update
        myButtons = {
            "Update": function () {
                var perm_name = $.trim($('#dlg_sys_admin_perm_name').val());
                if (perm_name.indexOf(' ') !== -1) {
                    swal('Oops...', 'White space is not allowed in the Permission Name!', 'warning');
                    return;
                }
                if (perm_name.length <= 0) {
                    swal('Oops...', 'Please enter permission Name!', 'warning');
                    return;
                }
                var perm = {'name': perm_name, 'description': $.trim($('#dlg_sys_admin_perm_desc').val()),
                    'type': $.trim($('#dlg_sys_admin_perm_type').val()), 'guard_name': $.trim($('#dlg_sys_admin_perm_guard').val())};
                $.post("sysadmin/updatePermission/" + $('#dlg_sys_admin_perm_id').html(), {
                        "permission": perm
                    },
                    function(item) {
                        if (item.status === 'Success') {
                            element.closest('tr').children().eq(1).html(perm['name']);
                            element.closest('tr').children().eq(2).html(perm['description']);
                            element.closest('tr').children().eq(3).html(perm['type']);
                            element.closest('tr').children().eq(4).html(perm['guard_name']);
                            element.closest('tr').children().eq(6).html(item.updated);
                            swal('', item.message, 'success');
                        } else {
                            swal('Oops...', item.message, 'warning');
                        }
                    },
                    'json'
                );
                $(this).empty();
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).empty();
                $(this).dialog('close');
            }
        }
    }

    $(dlg_form).dialog({
        modal: true,
        height: 510,
        overflow: "auto",
        width: 610,
        buttons: myButtons,
        close: function() {
            $(this).empty();
        }
    });
}

function sys_admin_site_manager() {
    var tbody = $('#table_sys_site_manager').find('tbody').empty();
    $.getJSON("/siteadmin/getSiteAll", function (items) {
        $.each(items, function (index, item) {
            $('<tr>' +
                '<td class="hidden">' + item.id + '</td>' +
                '<td>' + item.name + '</td>' +
                '<td>' + item.description + '</td>' +
                '<td>' + item.resources + '</td>' +
                '<td>' + item.admins.replace(',', '<br>') + '</td>' +
                '<td>' + item.created + '</td>' +
                '<td>' + item.updated + '</td>' +
                '<td><button title="Edit" onclick="sys_admin_site_operations($(this),2)">Edit</button>&nbsp;' +
                '<button title="Delete" onclick="sys_admin_site_operations($(this),0)">Delete</button></td>' +
                '</tr>').appendTo(tbody);
        });
    });
}

function sys_admin_site_operations(element, action) {
    var site = {};
    if (action === 0) { // delete
        swal('Oops...', 'Site cannot be deleted!', 'warning');
        return;
    } else if (action === 1) { // add
        site = {'id': null, 'name': '', 'desc': '', 'resources': '', 'admin': ''};
    } else if (action === 2) {  // update
        var pId = element.closest('tr').children().eq(0).html();
        var pName = element.closest('tr').children().eq(1).html();
        var pDesc = element.closest('tr').children().eq(2).html();
        var pRss = element.closest('tr').children().eq(3).html();
        var pAdmin = element.closest('tr').children().eq(4).html().replace('<br>', ';');
        site = {'id': pId, 'name': pName, 'desc': pDesc, 'resources': pRss, 'admin': pAdmin };
    }

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_sys_admin_site').attr('title', (action === 1) ? 'Create Site' : 'Edit Site');

    var site_info_html = '<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_site_name">Site Name:</label></td>' +
        '<td class="noBorder"><input type="text" id="dlg_sys_admin_site_name" size="50" value="' + site['name'] + '" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_site_admin">Administrators<br>(email separated by ";") :</label></td>' +
        '<td class="noBorder"><textarea id="dlg_sys_admin_site_admin" rows="2" cols="50" style="vertical-align: top; resize: none">' + site['admin'] + '</textarea><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_site_desc" >Description:</label></td>' +
        '<td class="noBorder"><textarea id="dlg_sys_admin_site_desc" rows="6" cols="50" style="vertical-align: top; resize: none">' + site['desc'] + '</textarea><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="dlg_sys_admin_site_rss" >Resources <br>(JSON format):</label></td>' +
        '<td class="noBorder"><textarea id="dlg_sys_admin_site_rss" rows="10" cols="50" style="vertical-align: top; resize: none">' + site['resources'] + '</textarea><br><br></td></tr>' +
        '<tr class="noBorder"><td class="hidden" id="dlg_sys_admin_site_id">' + site['id'] + '</td><td class="noBorder"></td></tr>' +
        '</table></div>';
    $(site_info_html).appendTo(dlg_form);

    sys_admin_dlg_site(dlg_form, element, action)
}

function sys_admin_dlg_site(dlg_form, element, action) {
    var myButtons = {};
    if (action === 1) {  // create
        myButtons = {
            "Create": function () {
                var site_name = $.trim($('#dlg_sys_admin_site_name').val());
                // if (site_name.indexOf(' ') !== -1) {
                //     swal('Oops...', 'Permission Name is not allow white space!', 'warning');
                //     return;
                // }
                if (site_name.length <= 0) {
                    swal('Oops...', 'Please enter Site Name!', 'warning');
                    return;
                }
                var admins = $.trim($('#dlg_sys_admin_site_admin').val());
                if ((admins.length <= 0) || !sys_admin_validate_emails(admins)) {
                    swal('Oops...', 'Please enter valid email addresses with separator ";" !');
                    return;
                }
                var site = {'name': site_name, 'desc': $.trim($('#dlg_sys_admin_site_desc').val()),
                    'resources': $.trim($('#dlg_sys_admin_site_rss').val())};
                $.post("siteadmin/addSite", {
                        "site": site,
                        "admins": admins
                    },
                    function(item) {
                        if (item.status === 'Success') {
                            var tbody = $('#table_sys_site_manager').find('tbody');
                            $('<tr><td class="hidden">' + item.id + '</td><td>' + item.name + '</td><td>' + item.description + '</td><td>' +
                                item.resources + '</td><td>' + admins.replace(';', '<br>') + '</td><td>' + item.created + '</td><td>' + item.updated + '</td><td>' +
                                '<button title="Edit" onclick="sys_admin_site_operations($(this),2)">Edit</button>&nbsp;' + '' +
                                '<button title="Delete" onclick="sys_admin_site_operations($(this),0)">Delete</button>' +
                                '</td></tr>').prependTo(tbody);
                            swal('', item.message, 'success');
                        } else {
                            swal('Oops...', item.message, 'warning');
                        }
                    },
                    'json'
                );
                $(this).empty();
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).empty();
                $(this).dialog('close');
            }
        }
    } else if (action === 2) {  // update
        myButtons = {
            "Update": function () {
                var site_name = $.trim($('#dlg_sys_admin_site_name').val());
                // if (site_name.indexOf(' ') !== -1) {
                //     swal('Oops...', 'Site Name is not allow white space!', 'warning');
                //     return;
                // }
                if (site_name.length <= 0) {
                    swal('Oops...', 'Please enter Site Name!', 'warning');
                    return;
                }
                var admins = $.trim($('#dlg_sys_admin_site_admin').val());
                if ((admins.length <= 0) || !sys_admin_validate_emails(admins)) {
                    swal('Oops...', 'Please enter valid email addresses with separator ";" !');
                    return;
                }
                var site = {'name': site_name, 'description': $.trim($('#dlg_sys_admin_site_desc').val()),
                    'resources': $.trim($('#dlg_sys_admin_site_rss').val())};
                $.post("siteadmin/updateSite/" + $('#dlg_sys_admin_site_id').html(), {
                        "site": site,
                        "admins": admins
                    },
                    function(item) {
                        if (item.status === 'Success') {
                            element.closest('tr').children().eq(1).html(site['name']);
                            element.closest('tr').children().eq(2).html(site['description']);
                            element.closest('tr').children().eq(3).html(site['resources']);
                            element.closest('tr').children().eq(4).html(admins.replace(';', '<br>'));
                            element.closest('tr').children().eq(6).html(item.updated);
                            swal('', item.message, 'success');
                        } else {
                            swal('Oops...', item.message, 'warning');
                        }
                    },
                    'json'
                );
                $(this).empty();
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).empty();
                $(this).dialog('close');
            }
        }
    }

    $(dlg_form).dialog({
        modal: true,
        height: 510,
        overflow: "auto",
        width: 610,
        buttons: myButtons,
        close: function() {
            $(this).empty();
        }
    });
}

function sys_admin_validate_emails(emailstr) {
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