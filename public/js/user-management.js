/**
 * Created by root on 11/20/18.
 */

function run_waitMe(selector, effect) {
    $(selector).waitMe({
        effect: effect,
        text: 'Please wait ...',
        bg: 'rgba(255,255,255,0.7)',
        color:'#000'
    });
}

function user_management_edit(element) {
    var data = $('#dataTableBuilder').DataTable().row($(element).closest('tr')).data();
    var userId = data.id;
    var userName = data.name;
    var userRoles = data.roles;

    var modal = $('#modal-user-edit');

    modal.modal('show');
    modal.find('#user-name').html(userName);
    modal.find('#user-id').html(userId);
    modal.find('#user-roles').html(userRoles);

    user_management_user_config(data);
}

function user_management_delete(element) {
    var data = $('#dataTableBuilder').DataTable().row($(element).closest('tr')).data();

    Swal.fire({
        title: 'Delete User?',
        text: 'Are you sure you want delete ' + data.email,
        type: 'question',
        showCancelButton: true,
        confirmButton: 'Yes'
    }).then((result) => {
        if (result.value) {
            $.post('users/delete', {
                'user_id': data.id
            }, function (result) {
                if (result.status === 'Success') {
                    Swal.fire('User Deleted!', result.message, 'success');
                    $('#dataTableBuilder').DataTable().draw(false);
                } else {
                    Swal.fire('Delete User Failed!', result.message, 'error');
                }
            });
        }
    });
}

function user_management_user_config(userdata) {
    var dlg_form = $('#user_management_config').empty();
    var userId = userdata.id;
    var userName = userdata.name;
    var userRoles = userdata.roles;

    // var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    // dlg_form.addClass('dialog').attr('id', 'dlg_user_admin_user').attr('title', 'Update ' + uEmail + ' Record');
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
        '<label>Group Roles:</label><br><div style="max-height: 400px; overflow-y: auto;"><table width="100%" class="table table-condensed" id="table_user_admin_user_groups">' +
        '<thead><th class="hidden">site_id</th><th>Site</th><th class="hidden">group_id</th><th>Group Name</th><th>Role</th></thead><tbody></tbody></table></div></div>';
    $(user_role_html).appendTo($('#tabs-user-admin-user-role'));

    // $('<label>Tab 3</label>').appendTo($('#tabs-user-admin-user-group'));

    $('<label>Tab 3</label>').appendTo($('#tabs-user-admin-user-activity'));

    $('#tabs-user-admin-user').tabs();

    //var uId = $(element).closest('tr').children().eq(0).html();
    var roleList = $('#user_admin_user_roles');
    //var user_roles = $(element).closest('tr').children().eq(5).html().split(',');
    var user_roles = userRoles.split('<br>');
    var group_tbody = $('#table_user_admin_user_groups').find('tbody').empty();

    $.getJSON("useradmin/getUserProfileRoleGroups/" + userId, function (data) {
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

    // user_admin_dlg_user_update(dlg_form, element)
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

function user_admin_dlg_user_update(element) {
    var modal = $('#modal-user-edit');
    var uId = modal.find('#user-id').html();
    var userName = modal.find('#user-name').html();


    $.post("useradmin/updateUserRole/" + uId, {
            "roles": user_admin_dlg_user_get_roles().id,
            "group_roles": user_admin_dlg_check_user_group_roles()
        },
        function(item) {
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


