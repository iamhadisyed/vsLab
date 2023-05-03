/**
 * Created by root on 8/8/15.
 */
function user_admin_window(winId, win_main) {
    var tabs = {
        tabId: ['user_admin', 'user_admin_role', 'user_amin_activities'],
        tabName: ['User Manager', 'Role Manager', 'Activities']
    };
    create_tabs(winId, win_main, tabs, null);

    $('<button onclick="admin_users_add_new_user()" class="submit">Add User</button>&nbsp;&nbsp;' +
        '<label>Search By &nbsp;</label>' +
        '<input type="radio" name="rbtn_user_admin_search_user" value="email" checked>' +
        '<label>Email: &nbsp;</label>' +
        '<input id="user_admin_users_search_email" />&nbsp;' +
        '<input type="radio" name="rbtn_user_admin_search_user" value="role">' +
        '<label>Role: &nbsp;</label>' +
            '<select id="sel_user_admin_search_user_role"></select>&nbsp;' +
        '<button onclick="user_admin_user_manager()">Search</button>&nbsp;&nbsp;' +
        '<span id="user_admin_users_search_count"></span>').appendTo($('#user_admin'));

    // var user_create_button = $(document.createElement('div')).appendTo($('#user_admin'));
    // user_create_button.append('<button class="submit" onclick="user_admin_user_operations($(this), 1)">Add New User</button>&nbsp;');
    var user_table = $(document.createElement('table')).appendTo($('#user_admin'));
    user_table.addClass('data').attr('id', 'table_user_admin_users').append(
        '<thead><tr><th class="hidden">ID</th>' +
        '<th>Email</th>' +
        '<th>Institute</th>' +
        '<th>Enabled</th>' +
        '<th>Role</th>' +
        '<th class="hidden">RoleString</th>' +
        '<th>Activated</th>' +
        '<th>Last Login</th>' +
        '<th>Last Activity</th>' +
        '<th>Created</th>' +
        '<th>Updated</th>' +
        '<th>Actions</th></tr></thead><tbody></tbody>');

    $.getJSON("sysadmin/getRoleList", function (roles) {
        $.each(roles, function (index, role) {
            if (role.type === 'system') {
                $('<option />').attr('value', role.name).text(role.name).appendTo($('#sel_user_admin_search_user_role'));
            }
        })
    });
//    user_admin_user_manager();
}

function user_admin_user_manager() {
    var tab = $('#table_user_admin_users');
    var tbody = $(tab).find('tbody').empty();

    var user_count = $('#user_admin_users_search_count');

    var by = $('input:radio[name=rbtn_user_admin_search_user]:checked').val();

    var role = (by === 'role') ?  $('#sel_user_admin_search_user_role').val() : '';

    var filters = $.trim($('#user_admin_users_search_email').val());

    var win_main = $(tab).closest('div.tab');
    run_waitMe(win_main, 'ios');
    $.post("useradmin/getUserList", {
            filters: filters,
            role: role
        },
        function (users) {
            user_count.html(users.length + ' users found.');
            $.each(users, function (index, user) {
                var roles = '';
                $.each(user.roles.split(','), function(idx, role) {
                   //roles += '<i class="fa fa-circle" aria-hidden="true"></i>&nbsp;' + role + '<br />';
                    roles += role + '<br />';
                });
                var tr = $('<tr />').appendTo(tbody);
                tr.append(
                    '<td class="hidden">' + user.id + '</td>' +
                    '<td>' + user.email + '</td>' +
                    '<td>' + user.institute + '</td>' +
                    '<td>' + ((user.enabled) ? 'No' : 'Yes') + '</td>' +
                    '<td style="font-size: small">' + roles + '</td>' +
                    '<td class="hidden">' + user.roles  + '</td>' +
                    '<td>' + user.activated + '</td>' +
                    '<td>' + user.last_login + '</td>' +
                    '<td>' + user.last_activity + '</td>' +
                    '<td>' + user.created + '</td>' +
                    '<td>' + user.updated + '</td>' +
                    '<td class="dropdown"><a class="btn btn-default user-admin-users-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>'
                );
            });
            $(win_main).waitMe('hide');
        },
        'json'
    );

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'user-admin-users-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="user-admin-user-profile">Update Profile</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="user-admin-user-roles">Update Role</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="user-admin-user-activity">View Activities</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="user-admin-user-delete">Delete</a></li>').appendTo(contextMenu);
}

function user_admin_user_config(element) {
    var uEmail = $(element).closest('tr').children().eq(1).html();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_user_admin_user').attr('title', 'Update ' + uEmail + ' Record');
    var tabs = $(document.createElement('div')).appendTo(dlg_form);
    tabs.attr('id', 'tabs-user-admin-user').append($('<ul>' +
        '<li><a href="#tabs-user-admin-user-profile">User Profile</a></li>' +
        '<li><a href="#tabs-user-admin-user-role">Roles</a></li>' +
        // '<li><a href="#tabs-user-admin-user-group">Group</a></li>' +
        '<li><a href="#tabs-user-admin-user-activity">Activities</a></li>' +
        '</ul>' +
        '<div id="tabs-user-admin-user-profile" style="overflow: hidden;"></div>' +
        '<div id="tabs-user-admin-user-role" style="overflow: hidden;"></div>' +
        // '<div id="tabs-user-admin-user-group" style="overflow: hidden;"></div>' +
        '<div id="tabs-user-admin-user-activity" style="overflow: hidden;"></div>'
    ));

    var user_profile_html = '<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_profile_email">Email:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_profile_email" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_profile_name">Name:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_profile_name" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_profile_institute" >Institute:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_profile_institute" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_profile_phone" >Phone:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_profile_phone" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_profile_address" >Address:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_profile_address" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_profile_state" >State:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_profile_state" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_profile_country" >Country:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_profile_country" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_profile_enabled">Enabled</label></td>' +
        '<td class="noBorder"><input type="checkbox" id="useradmin_profile_enabled" /></td></tr>' +
        '<tr class="noBorder"><td class="hidden" id="useradmin_profile_id"></td><td class="noBorder"></td></tr>' +
        '</table></div>';
    $(user_profile_html).appendTo($('#tabs-user-admin-user-profile'));

    var user_role_html = '<div><label>System Roles:</label><br><table width="100%">' +
        '<tr style="height:30px"><td style="vertical-align: middle"><div id="user_admin_user_roles"></div></td></tr></table><br>' +
        '<label>Group Roles:</label><br><table width="100%" class="data" id="table_user_admin_user_groups">' +
        '<thead><th class="hidden">site_id</th><th>Site</th><th class="hidden">group_id</th><th>Group Name</th><th>Role</th></thead><tbody></tbody></table></div>';
    $(user_role_html).appendTo($('#tabs-user-admin-user-role'));

    // $('<label>Tab 3</label>').appendTo($('#tabs-user-admin-user-group'));

    $('<label>Tab 3</label>').appendTo($('#tabs-user-admin-user-activity'));

    $('#tabs-user-admin-user').tabs();

    var uId = $(element).closest('tr').children().eq(0).html();
    var roleList = $('#user_admin_user_roles');
    var user_roles = $(element).closest('tr').children().eq(5).html().split(',');
    var group_tbody = $('#table_user_admin_user_groups').find('tbody').empty();

    $.getJSON("useradmin/getUserProfileRoleGroups/" + uId, function (data) {
        $('#useradmin_profile_email').val(data.user.email);
        $('#useradmin_profile_name').val(data.user.profile.first_name + ' ' + data.user.profile.last_name);
        $('#useradmin_profile_institute').val(data.user.institute);
        $('#useradmin_profile_phone').val(data.user.profile.phone);
        $('#useradmin_profile_address').val(data.user.profile.address + ' ' + data.user.profile.city + ' ' + data.user.profile.zip);
        $('#useradmin_profile_state').val(data.user.profile.state);
        $('#useradmin_profile_country').val(data.user.profile.country);
        $('#useradmin_profile_enabled').attr('checked', !(data.user.banned));
        $('#useradmin_profile_id').val(data.user.id);

        var group_roles = [];
        $.each(data.roles, function(index, role) {
            if (role.type === 'system') {
                if (($.inArray(role.name, user_roles) > -1)) {
                    $('<input />', {type: 'checkbox', name: 'user-admin-update-roles',
                        id: 'user_admin_update_roles-' + role.id,
                        value: role.name,
                        checked: 'checked'
                    }).css('vertical-align', '-2px').appendTo(roleList);
                } else {
                    $('<input />', {type: 'checkbox', name: 'user-admin-update-roles',
                        id: 'user_admin_update_roles-' + role.id,
                        value: role.name
                    }).css('vertical-align', '-2px').appendTo(roleList);
                }
                $('<label />', {
                    'for': 'user_admin_update_roles-' + role.id,
                    text: role.name
                }).css('font-weight', '100').append('&nbsp;&nbsp;').appendTo(roleList);
            } else if (role.type === 'group') {
                group_roles.push({ id: role.id, name: role.name });
            }
        });

        $.each(data.groups, function (index, group) {
            var tr = $('<tr />').appendTo(group_tbody);
            tr.append('<td class="hidden">' + group.site_id + '</td>' +
                '<td>' + group.site_name + '</td>' +
                '<td class="hidden">' + group.gid+ '</td>' +
                '<td>' + group.name + '</td>' +
                '<td>' + user_admin_dlg_user_gen_group_roles(group.gid, group.roles, group_roles) + '</td>');
        })
    });

    user_admin_dlg_user_update(dlg_form, element)
}

function user_admin_dlg_user_gen_group_roles(gid, g_roles, roles) {
    var html_str = '';
    $.each(roles, function(index, role) {
        html_str += '<input type="checkbox" name="user-admin-update-roles-groups" style="vertical-align: -2px" ' +
            'id="user_admin_update_roles_groups-' + gid + '-' + role.id + '" value="' + role.name + '" ' +
            ((role.name === 'group_owner') ? 'disabled ' : '') +
            (($.inArray(role.id, g_roles) > -1) ? 'checked' : '') + ' />' +
            '<label for="user_admin_update_roles_groups-' + gid + '-' + role.id + '" style="font-weight: 100">' + role.name + '</label>&nbsp;';
        if ((index+1) % 3 === 0) {
            html_str += '<br>';
        }
    });
    return html_str;
}

function user_admin_dlg_check_user_roles(u_roles) {
    var chkbox = $('#user_admin_user_roles').find('input[type="checkbox"]');
    for (var i = 0; i < chkbox.length; i++) {
        chkbox[i].checked = ($.inArray(chkbox[i].value, u_roles) > -1);
    }
}

function user_admin_user_profile(element) {
    user_admin_user_config(element);
    $('#tabs-user-admin-user').tabs('option', 'active', 0);
}

function user_admin_user_roles(element) {
    user_admin_user_config(element);
    $('#tabs-user-admin-user').tabs('option', 'active', 1);
}

function user_admin_user_activity(element) {
    user_admin_user_config(element);
    $('#tabs-user-admin-user').tabs('option', 'active', 2);
}

function user_admin_dlg_user_get_roles() {
    var chkbox = $('#user_admin_user_roles').find('input[type=checkbox]');
    var checked_user_roles_id = [];
    var checked_user_roles_name = [];
    for (var i=0; i < chkbox.length; i++) {
        if (chkbox[i].checked) {
            checked_user_roles_id.push(chkbox[i].id.slice('user_admin_update_roles-'.length));
            checked_user_roles_name.push(chkbox[i].value);
        }
    }
    return {id: checked_user_roles_id, name: checked_user_roles_name};
}

function user_admin_dlg_check_user_group_roles() {
    var group_roles = [];
    $('#table_user_admin_user_groups').find('tbody').find('tr').each(function (i, row) {
       var g_id = $(row).children().eq(2).html();
       var roles = [];
       $(row).children().eq(4).find('input[type=checkbox]').each(function (j, chkbox) {
           if (chkbox.checked) {
               roles.push(chkbox.id.split('-')[2]);
           }
       });
       if (roles.length > 0) {
           group_roles.push({gId: g_id, roles: roles});
       }
    });
    return group_roles;
}

function user_admin_dlg_user_update(dlg_form, element) {
    var uId = $(element).closest('tr').children().eq(0).html();
    $(dlg_form).dialog({
        modal: true,
        height: 510,
        overflow: "auto",
        width: 610,
        buttons: {
            "Update": function () {
                $.post("useradmin/updateUserRole/" + uId, {
                        "roles": user_admin_dlg_user_get_roles().id,
                        "group_roles": user_admin_dlg_check_user_group_roles()
                    },
                    function(item) {
                        if (item.status === 'Success') {
                            var roles = '';
                            $.each(item.roles.split(','), function(idx, role) {
                                roles += '<i class="fa fa-circle" aria-hidden="true"></i>&nbsp;' + role + '<br />';
                            });
                            $(element).closest('tr').children().eq(4).html(roles);
                            $(element).closest('tr').children().eq(5).html(item.roles);
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
        },
        close: function() {
            $(this).empty();
        }
    });
}

function admin_users_add_new_user() {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_user_admin_new_user').attr('title', 'Add New User');
    var form = $(document.createElement('div')).appendTo(dlg_form);

    $('<div><label style="font-style: italic;">Enter new user\' personal information:</label><br><br><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_new_user_email">Email:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_new_user_email" size="55"/><br><br></td></tr>' +
        '<tr class="noBorder">' +
                '<td class="noBorder"><label for="useradmin_new_user_first_name">First Name:</label></td>' +
                '<td class="noBorder"><input type="text" id="useradmin_new_user_first_name" size="20"/>&nbsp;&nbsp;' +
                '<label for="useradmin_new_user_last_name" >Last Name:</label>&nbsp;' +
                '<input type="text" id="useradmin_new_user_last_name" size="20"/><br><br></td>' +
        '</tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_new_user_institute" >Institute:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_new_user_institute" size="55"/><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_new_user_phone" >Phone:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_new_user_phone" size="55"/><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_new_user_address" >Address:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_new_user_address" size="55"/><br><br></td></tr>' +
        '<tr class="noBorder">' +
                '<td class="noBorder"><label for="useradmin_new_user_state" >State:</label></td>' +
                '<td class="noBorder"><input type="text" id="useradmin_new_user_state" size="20"/>&nbsp;&nbsp;' +
                '<label for="useradmin_new_user_zip" >Zip:</label>&nbsp;' +
                '<input type="text" id="useradmin_new_user_zip" size="20"/><br><br></td>' +
        '</tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="useradmin_new_user_country" >Country:</label></td>' +
        '<td class="noBorder"><input type="text" id="useradmin_new_user_country" size="55"/><br><br></td></tr>' +
        //'<tr class="noBorder"><td class="noBorder"><label for="useradmin_new_user_enabled">Enabled</label></td>' +
        // '<td class="noBorder"><input type="checkbox" id="useradmin_new_user_enabled" /></td></tr>' +
        '</table></div>').appendTo(form);

    $(dlg_form).dialog({
        modal: true,
        height: 510,
        overflow: "auto",
        width: 610,
        buttons: {
            "Submit": function () {
                var email = $.trim($('#useradmin_new_user_email').val());
                if (!sys_admin_validate_emails(email) || email.length <= 0) {
                    swal('Oops...', 'Email address is invalid!', 'warning');
                    return;
                }
                var user = {'email': $.trim($('#useradmin_new_user_email').val()),
                            'first_name': $.trim($('#useradmin_new_user_first_name').val()),
                            'last_name': $.trim($('#useradmin_new_user_last_name').val()),
                            'institute': $.trim($('#useradmin_new_user_institute').val()),
                            'phone': $.trim($('#useradmin_new_user_phone').val()),
                            'address': $.trim($('#useradmin_new_user_address').val()),
                            'state': $.trim($('#useradmin_new_user_state').val()),
                            'zip': $.trim($('#useradmin_new_user_zip').val()),
                            'country': $.trim($('#useradmin_new_user_country').val())
                };
                $.post("useradmin/addNewUser", {
                        "user": user
                    },
                    function(item) {
                        if (item.status === 'Success') {
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
        },
        close: function() {
            $(this).empty();
        }
    });
}








function windows_admin_display(winId, win_main) {
    // var admin = false;
    // var res = $('#user_all_role')[0].innerHTML.split(':');
    // for (var i= 0; i<res.length; i++) {
    //     if ( 'class_approval' === res[i] ) {
    //         admin = true;
    //     }
    // }
//    $.getJSON("cloud/getGlobalAdmin", function (jsondata) {
//            if (jsondata.global_admin == 1) {
//    if (admin) {
//                setTimeout(function() {
        do_windows_display_admin(winId, win_main);
//                }, 1500);
//    }
//    });
}

function do_windows_display_admin(winId, win_main) {
    var tabs = {
        //tabId: ['apply_for_a_class', 'class_approval', 'group_member', 'group_owner', ],
        //tabName: ['Apply for a user class', 'Class approval', 'Enrollment', 'Lab Management']
        // tabId: [/*'class_approval', 'role_upgrade', */'role_upgrade2', 'superuser_role_approval','system_monitor', 'previlige_update' , 'site_manage', 'Temp_role_resource', 'log_search', 'pending_group_create_approval'],
        // tabName: [ /*'Class approval', 'Role upgrade', */'Role Management', 'SuperUser Approval', 'System monitor', 'Privilege Update', 'Site Management', 'Temp role resource', 'Log Search', 'Group Creation Approval']
        tabId: ['role_upgrade2', 'superuser_role_approval', 'previlige_update', 'pending_group_create_approval'],
        tabName: ['Role Management', 'SuperUser Approval', 'Privilege Update', 'Group Creation Approval']
    };
    create_tabs(winId, win_main, tabs, null);

    var forma_html = '<form method="post" id="form_approve_class"  >' +
        '<table class="data" id="gm_table_class_approval">' +
        '<tr><th>Class</th><th>Owner</th><th>Action</th></tr>' +
        '</table>' +
//        '<button type="button" class="submit" id="submit_approve_class">Approve</button>' +
        '</form>';
    $(forma_html).appendTo($('#class_approval'));

    var forma2_html = '<form method="post" id="form_user_role_change"  >  ' +
        '<li><label >user email:</label><input required name="user_role_change_email"></li>' +
        '<li><label >role:</label><select id="user_role_change_role"><option value="normal">normal user</option><option value="super">super user</option></select></li>' +
        '<button type="button" class="submit" id="submit_user_role_change">Update</button>' +
        '</form>';
    $(forma2_html).appendTo($('#role_upgrade'));

    var form3_html =
        '<label>Search by &nbsp;</label>' +
        '<input type="radio" name="search_user_in_role_mgmt" value="email" checked>' +
        '<label>User email: &nbsp;</label>' +
        '<input name="search_user_txt_copy"> &nbsp;' +
//        '<button id="search_user_btn_copy" onclick="search_user_btn_copy()">Search</button>' +
//        '<br/>' +
//        '<p>Or</p> search by role' +
        '<input type="radio" name="search_user_in_role_mgmt" value="role">' +
        '<label>By Role: &nbsp;</label>' +
        '<select id="searchbyrole_updaterole">' +
        //'<option value=""></option>' +
        '<option value="adminuser">Admin</option>' +
        '<option value="superuser">Super User</option>' +
        '<option value="normaluser">Normal User</option>' +
        '<option value="trialuser">Trial User</option>' +
        '</select>&nbsp;' +
        '<button id = "btn_search_user_in_role_mgmt" onclick="search_user_role_mgmt()">Search</button>' +
        '<br/>' +
        '<hr/>' +
        '<table class="data" id="search_user_table_copy">' +
        '<tr><th>User</th><th>Role</th><th>Action</th></tr>' +
        '</table>' +
        '<div id="showusernumber_copy"></div>';
    $(form3_html).appendTo($('#role_upgrade2'));

    var form_html = '<table class="data" id="pending_superuser_enroll_table">' +
        '<tr><th>Group</th><th>Applicant</th><th>Role</th><th>Action</th></tr>' +
            //'<tr><td>Jell</td><td>Yuli</td><td><input type="button" value="Approve"></td></tr>' +
            //'<tr><td>Eve</td><td>James</td><td><input type="button" value="Approve"></td></tr>' +
        '</table>';
    $(form_html).appendTo($('#superuser_role_approval'));

    display_superuser_approval_table();

    var form_html = '<table class="data" id="pending_group_create_table">' +
        '<tr><th>Group</th><th>Description</th><th>Type</th><th>Expiration</th><th>Action</th></tr>' +
        '</table>';
    $(form_html).appendTo($('#pending_group_create_approval'));
    display_group_approval_table();

    var table = $(document.createElement('table')).appendTo($('#previlige_update'));
    table.addClass("data").attr("id", "search_permisions").append('<thead><tr>' +
    '<th>Role</th>' +
    '<th>Privilige</th>' +
    '<th>Action</th>' +
    '</tr></thead>');

    var tbody = $(document.createElement('tbody')).appendTo(table);

    display_privilige_table();

    $(winId).find('div.window_bottom').text('User Admin functions');

    display_pending_class_request();

}


function btn_edit_role_json(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_edit_role_json').attr('title', 'Edit role resource json description');

    var form_html2 = '<form><ul>' +
        '<li>' +
        'json:<input  id="input_roel_json" size="50" value="'+"json description"+'"/>' +
        '</li>' +
        '<li>' +
        '<button type="button" class="submit" onclick="post_edit_role_json(null)" >Update</button>' +
        '</li>' +
        '</ul>' +
//        '<input type="submit" value="add" id="post_222add_site" onclick="post_add_site(null)" >' +
        '</form>';// +
    //'<div id="showusernumber"></div>';
    $(form_html2).appendTo(dlg_form);

    $('#dlg_edit_role_json').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            display_role_res_table();
            $(this).remove();
        }
    });
}

function post_edit_role_json(element) {
    console.log("in function post_edit_role_json");
    $.post("/cloud/updateRoleJson", {
            "role_json": $("#input_roel_json").val()
        },
        function (data) {
            //alert("test 111 " + JSON.stringify(data));
            $.jAlert(
                {
                    'title':'Information',
                    'content':'Role json Request Sent',
                    'theme':'blue',
                    'btns':
                    {
                        'text':'close',
                        'theme':'green'
                    }
                }
            );
        },
        'json'
    );
}

function display_superuser_approval_table() {
    run_waitMe($('#superuser_role_approval'), 'ios');
    $.post("group/getSuperuserPendingEnroll", {
            "groupname" : "forIconGlobalSuperUser"
        },
        function (jsondata) {
            $('#superuser_role_approval').waitMe('hide');
            var table = $("#pending_superuser_enroll_table");
            table.empty();
            table.append("<tr><th>Applicant</th><th>Action</th></tr>");
            $.each(jsondata, function (index, item) {
                //alert(item.userid);
                if (index == 'pending') {
                    $.each(item, function (index3, item3) {
                        table.append('<tr><td>' + item3.user_email + '</td><td><button class="btn-pendingsuperuser-enroll" >Approve</button></td></tr>');
                    });
                }
            })
        });
}

function display_group_approval_table() {
    run_waitMe($('#superuser_role_approval'), 'ios');
    $.post("group/getGroupPendingEnroll", {
            "groupname" : "forIconGlobalSuperUser"
        },
        function (jsondata) {
            $('#pending_group_create_approval').waitMe('hide');
            var table = $("#pending_group_create_table");
            table.empty();
            table.append("<tr><th>Group</th><th>Description</th><th>Expiration</th><th>Action</th></tr>");
            $.each(jsondata, function (index, item) {
                //alert(item.userid);
                if (index == 'pending') {
                    $.each(item, function (index3, item3) {
                        table.append('<tr><td>' + item3.group_name + '</td><td>' + item3.group_desc + '</td><td>\' + item3.group_type + \'</td><td>\' + item3.group_expire + \'</td><td><button class="btn-pendinggroup-enroll" >Approve</button></td></tr>');
                    });
                }
            })
        });
}

function btn_pendingsuperuser_enroll(element) {
    var p2 = element.parent();
    //alert(p2.prev().html());
    //alert(p2.prev().prev().html());
    $.post("/cloud/approveEnrollGroup", {
            "group_name": "forIconGlobalSuperUser",
            "user_email": p2.prev().html()
        },
        function (data) {
        },
        'json'
    );
    p2.text('approved');
    display_superuser_approval_table();
}

function get_role_list_html_copy(type) {
    switch (type) {
        case 0:
            return '<select ><option value="trial">trial user</option><option value="normal" selected>normal user</option><option value="super">super user</option><option value="admin">admin user</option></select>';
        case 1:
            return '<select ><option value="trial">trial user</option><option value="normal">normal user</option><option value="super" selected >super user</option><option value="admin">admin user</option></select>';
        case 2:
            return '<select ><option value="trial">trial user</option><option value="normal">normal user</option><option value="super">super user</option><option value="admin" selected>admin user</option></select>';
        case 3:
            return '<select ><option value="trial" selected>trial user</option><option value="normal">normal user</option><option value="super">super user</option><option value="admin">admin user</option></select>';
    }

}

function display_privilige_table(){
    $.getJSON("/cloud/getPermissions", function (jsondata) {
        var tbody = $('#search_permisions').find('tbody').empty();
        $.each(jsondata, function (index, item) {
            $('<tr>' +
            '<td>' + item.description + '</td>' +
            '<td>' + item.permissiondetails + '</td>' +
            '<td>' + '<button class="edit_permission_button">Edit</button>' + '</td>' +
            '</tr>').appendTo(tbody);
        });
    });
}

function post_update_permission(element) {
    $.post("/cloud/PPpermissionUpdate", {
            "permission_id": $("#input_permission_desc").val(),
            "permission_content": $("#input_permission_edit").val()
        },
        function (data) {
            //alert(data);
            $.jAlert(
                {
                    'title':'Information',
                    'content':'Permission Update Request Sent',
                    'theme':'blue',
                    'btns':
                    {
                        'text':'close',
                        'theme':'green'
                    }
                }
            );
        },
        'json'
    );//.fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
        //    $.jAlert({'title':'Information', 'content':' Update Request Sent', 'theme':'blue', 'btns':{'text':'close','theme':'green'}})
        //}).done(function (xhr, testStatus, errorThrown) {
            //alert('done' + xhr.responseText);
          //  $.jAlert({'title':'Information', 'content':'Permission  Request Sent', 'theme':'blue', 'btns':{'text':'close','theme':'green'}})
        //});
}

function edit_permission_button(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_edit_permission').attr('title', 'Edit role permissions');

    var form_html2 = '<form>' +
        '<label>Edit role permissions:</label>' +
        '<hr/>' +
        '<input hidden id="input_permission_desc" size="50" value="'+element.parent().prev().prev().html()+'"/>' +
        '<input id="input_permission_edit" size="50" value="'+element.parent().prev().html()+'"/>' +
        '<hr/>' +
        '<button  id="post_update_permission" >submit</button>' +
        '</form>';// +
        //'<div id="showusernumber"></div>';
    $(form_html2).appendTo(dlg_form);

    $('#dlg_edit_permission').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function btn_invite_copy(element) {
    var p3 = element.parent();
    //alert($("#own_group_list").val());
    //alert(p3.prev().prev().prev().html());
    $.post("/cloud/userRoleUpdate", {
            "user_email": p3.prev().prev().html(),
            "new_role": p3.prev().children(0).val()
        },
        function (data) {
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
            $.jAlert({'title':'Information', 'content':'Role Update Request Sent', 'theme':'blue', 'btns':{'text':'close','theme':'green'}})
        }).done(function (xhr, testStatus, errorThrown) {
            //alert('done' + xhr.responseText);
            $.jAlert({'title':'Information', 'content':'Role Update Request Sent', 'theme':'blue', 'btns':{'text':'close','theme':'green'}})
        });


    //p3.text('pending');
}

function display_group_invite_show_table_copy(jsondata) {
    var table = $("#search_user_table_copy");
    table.empty();
    table.append("<tr><th>User</th><th>Role</th><th>Action</th></tr>");
    //alert("send ajax for own group list0");
    var count = 0;
    $.each(jsondata, function (index, item) {
        //alert(item.userid);
        if (index == 'users_normal') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td>' + item3 + '</td><td>' + get_role_list_html_copy(0) + '</td><td><button class="btn-invite-copy-for-role-update" >Update</button></td></tr>');
                count = count + 1;
            });
        } else if (index == 'users_admin') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td>' + item3 + '</td><td>' + get_role_list_html_copy(2) + '</td><td><button class="btn-invite-copy-for-role-update" >Update</button></td></tr>');
                count = count + 1;
            });
        } else if (index == 'users_super') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td>' + item3 + '</td><td>' + get_role_list_html_copy(1) + '</td><td><button class="btn-invite-copy-for-role-update" >Update</button></td></tr>');
                count = count + 1;
            });
        } else if (index == 'users_trial') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td>' + item3 + '</td><td>' + get_role_list_html_copy(3) + '</td><td><button class="btn-invite-copy-for-role-update" >Update</button></td></tr>');
                count = count + 1;
            });
        }
    });
    $('#showusernumber_copy').html(count + ' found');
}

function search_user_role_mgmt() {
    var by = $('input:radio[name=search_user_in_role_mgmt]:checked').val();
    if (by === 'email') {
        search_user_btn_copy();
    } else if (by === 'role') {
        search_user_btn_copy2();
    }
}

function search_user_btn_copy() {
    var user = $("input[name=search_user_txt_copy]").val();
    if (user === '') {
        swal("Oops...", "Please enter user's email!", 'warning');
        return;
    }
    run_waitMe($("#search_user_table_copy"), 'ios');
    $.post("/useradmin/searchUserRole", {
            "search_user_txt": user//,
            //"select_group_id": $("#own_group_list").val()
        },
        function (data) {
            $("#search_user_table_copy").waitMe('hide');
            //alert(JSON.stringify(data));
            display_group_invite_show_table_copy(data);
        },
        'json'
    ).failed(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}

function search_user_btn_copy2() {
    run_waitMe($("#search_user_table_copy"), 'ios');
    $.post("/useradmin/searchUserRolebyRole", {
            "search_role_txt": $("#searchbyrole_updaterole").val()//,
            //"select_group_id": $("#own_group_list").val()
        },
        function (data) {
            $("#search_user_table_copy").waitMe('hide');
            //alert(JSON.stringify(data));
            display_group_invite_show_table_copy(data);
        },
        'json'
    );//.failed(function (xhr, testStatus, errorThrown) {
    //        alert(xhr.responseText);
    //    });
}

function submit_user_role_change() {
    $.post("/cloud/userRoleUpdate", {
            "user_email": $("input[name=user_role_change_email]").val(),
            "new_role": $("#user_role_change_role").val()
        },
        function (data) {
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
            $.jAlert({'title':'Information', 'content':'Role Update Request Sent', 'theme':'blue', 'btns':{'text':'close','theme':'green'}})
            //alert('request sent' );
        }).done(function (xhr, testStatus, errorThrown) {
            //alert('done' + xhr.responseText);
            //alert('request sent' );
        });
}

