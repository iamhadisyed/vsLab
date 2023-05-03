/**
 * Created by James on 1/10/18.
 */

function group_admin_window(winId, win_main) {
    var tabs = {
        tabId: ['group_admin_new_group', 'group_admin_my_groups', 'group_admin_join_groups'],
        tabName: ['New Group', 'My Groups', 'Join Groups']
    };

    create_tabs(winId, win_main, tabs, null);

    $('<table class="noBorder"><tr class="noBorder"><td class="noBorder">' +
        '<form method="post" id="group_admin_form_apply_group" class="contact_form" >' +
        '<ul><li><h2>Create a New Group/Class</h2> &nbsp; &nbsp;' +
        '<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="group_admin_new_group_name"><span style="color: red">*</span>Group/Class Name:</label>' +
            '<input required id="group_admin_new_group_name" placeholder="Spring2018CSE465" />&nbsp;&nbsp;<span>(Alphanumeric without space)</span></li>' +
        '<li><label for="group_admin_new_group_desc"><span style="color: red">*</span>Description: </label>' +
            '<textarea required rows="2" cols="40" id="group_admin_new_group_desc" placeholder="The purpose of your group."/></li>' +
        '<li><label for="select_private_group_admin_new_group"><span style="color: red">*</span>Public/Private: </label>' +
            '<select id="select_private_group_admin_new_group" >' +
                '<option value="0">Public</option>' +
                '<option value="1">Private</option>' +
            '</select>&nbsp;&nbsp;' + '<span>(Public group is visible to all users)</span>' +
        '</li>' +
        '<li><label for="group_admin_new_group_require_rss">Require Resources:</label>' +
            '<select id="group_admin_new_group_require_rss" onchange="group_admin_toggle_request_rss($(this),1)" style="width: auto">' +
                '<option value="0">No</option><option value="1">Yes</option></select>' +
        '</li>' +
        '<li></li>' +
        '</ul>' +
        '<li><button type="button" class="submit" id="btn_submit_apply_group" onclick="group_admin_submit_group_apply()">Apply</button></li>' +
        '</form></td>' +
        '<td class="noBorder"><div id="group_admin_new_group_request_rss" style="display: none; margin-top: 56px">' +
            '<label style="font-style: italic">The requesting resources:</label><br>' +
            '<label for="group_admin_new_group_select_site">Site:</label>&nbsp;&nbsp;' +
            '<Select id="group_admin_new_group_select_site" onchange="group_admin_select_site_change($(this))"></select><br><span id="group_admin_new_group_select_site_desc"></span>' +
            '<table class="noBorder" style="table-layout: fixed; width: 190px;"><colgroup><col width="110px" /><col width="80px"></colgroup>' +
            '<tr class="noBorder"><td class="noBorder"><label for="group_admin_new_group_rss_labs">Number of Labs:</label></td>' +
            '<td class="noBorder"><input id="group_admin_new_group_rss_labs" size="3px"/></td></tr>' +
            '<tr class="noBorder"><td class="noBorder"><label for="group_admin_new_group_rss_vms">VM per lab: </label></td>' +
            '<td class="noBorder"><input id="group_admin_new_group_rss_vms" size="3px"/></td></tr>' +
            '<tr class="noBorder"><td class="noBorder"><label for="group_admin_new_group_rss_cpu">CPU per lab: </label></td>' +
            '<td class="noBorder"><input id="group_admin_new_group_rss_cpu" size="3px"/></td></tr>' +
            '<tr class="noBorder"><td class="noBorder"><label for="group_admin_new_group_rss_ram">Memory per lab: </label></td>' +
            '<td class="noBorder"><input id="group_admin_new_group_rss_ram" size="3px"/>MB</td></tr>' +
            '<tr class="noBorder"><td class="noBorder"><label for="group_admin_new_group_rss_storage">Storage per lab: </label></td>' +
            '<td class="noBorder"><input id="group_admin_new_group_rss_storage" size="3px"/>GB</td></tr>' +
            '<tr class="noBorder"><td class="noBorder"><label for="group_admin_new_group_expiration">Use util: </label></td>' +
            '<td class="noBorder"><input id="group_admin_new_group_expiration" size="8px"/></td></tr>' +
            '</div></td></tr>'
    ).appendTo($('#group_admin_new_group'));

    $.getJSON("siteadmin/getSiteAll", function (sites) {
        $.each(sites, function(index, site) {
            $('#group_admin_new_group_select_site').append($('<option />').val(site.id).attr('desc', site.description).html(site.name));
            if (index === 0 ) {
                $('#group_admin_new_group_select_site_desc').text(site.description);
            }
        })
    });

    $('#group_admin_new_group_expiration').datepicker();

    var html_my_groups = '<div>' +
        '<table class="data" id="table_group_admin_my_groups">' +
        '<thead><tr><th class="hidden">ID</th><th>Name</th><th>Description</th><th class="hidden">site_id</th><th>Site</th>' +
        '<th>Type</th><th>Status</th><th>Available Resources</th><th>Update</th><th>Expiration</th><th>Actions</th></tr></thead>' +
        '<tbody></tbody></table></div>';
    $(html_my_groups).appendTo($('#group_admin_my_groups'));

    var html_join_groups = '<div>' +
        '<label>Enroll Group:</label>&nbsp;<input type="text" id="group_admin_join_group_quick_name" size="20" placeholder="GroupName" />' +
        '<button onclick="group_admin_join_group_enroll($(this))">Enroll</button>&nbsp;&nbsp;&nbsp;' +
        '<button onclick="group_admin_join_group_search_enroll($(this))">Search & Enroll</button><br><br>' +
        '<table class="data" id="table_group_admin_join_groups">' +
        '<thead><tr><th class="hidden">site_id></th><th>Site</th><th class="hidden">ID</th><th>Group Name</th><th>Description</th>' +
        '<th>Owner</th><th>Status</th><th>Role</th><th>Actions</th></tr></thead>' +
        '<tbody></tbody></table></div>';
    $(html_join_groups).appendTo($('#group_admin_join_groups'));
}

function group_admin_toggle_request_rss(element, rss_div) {
    var select = (rss_div === 1) ?  $('#group_admin_new_group_request_rss') : $('#group_admin_dlg_request_rss');

    if (element.val() === "1") {
        select.css('display', 'block');
    } else {
        select.css('display', 'none');
    }
}

function group_admin_select_site_change(element) {
    var id = element.attr('id');
    if (id === 'group_admin_new_group_select_site') {
        $('#group_admin_new_group_select_site_desc').text($('#group_admin_new_group_select_site :selected').attr('desc'));
    } else if ( id === 'group_admin_dlg_request_rss_select_site') {
        $('#group_admin_dlg_request_rss_select_site_desc').text($('#group_admin_dlg_request_rss_select_site :selected').attr('desc'));
    }
}

function group_admin_submit_group_apply() {
    var name = $.trim($('#group_admin_new_group_name').val());
    var desc = $.trim($('#group_admin_new_group_desc').val());
    var expiration = $('#group_admin_new_group_expiration').val();
    if (name.length <= 0|| desc.length <=0) {
        swal('Oops...', 'Please enter Group Name and Description!', 'warning');
        return;
    }
    if (name.indexOf(' ') !== -1) {
        swal('Oops...', 'White space is not allowed in the Group Name!', 'warning');
        return;
    }
    var require_rss = {};
    var site_id = 1;
    if ($('#group_admin_new_group_require_rss').val() === "1") {
        var labs = parseInt($('#group_admin_new_group_rss_labs').val());
        var vms = parseInt($('#group_admin_new_group_rss_vms').val());
        var cpu = parseInt($('#group_admin_new_group_rss_cpu').val());
        var ram = parseInt($('#group_admin_new_group_rss_ram').val());
        var storage = parseInt($('#group_admin_new_group_rss_vms').val());

        if  (isNaN(labs) || isNaN(vms) || isNaN(cpu) || isNaN(ram) || isNaN(storage)) {
            swal('Oops...', 'Please enter integer in the requesting resources!');
            return;
        }
        if (expiration.match(/^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/) === null) {
            swal('Oops...', 'The date format is incorrect!', 'warning');
            return;
        }

        require_rss['labs'] = labs;
        require_rss['vms'] = vms;
        require_rss['cpu'] = cpu;
        require_rss['ram'] = ram;
        require_rss['storage'] = storage;
        require_rss['expiration'] = expiration;
        site_id = $('#group_admin_new_group_select_site').val();
    } else {
        site_id = 1;
        require_rss = {};
    }

    var group = {name: name, description: desc, site_id: site_id, expiration: expiration,
        private: $('#select_private_group_admin_new_group').val(), resource_requested: JSON.stringify(require_rss)};
    $.post("group/addNewGroup", {
            "group": group
        }, function (result) {
            if (result.status === 'Success') {
                swal('', result.message, 'success');
                $('#group_admin_new_group_name').val('');
                $('#group_admin_new_group_desc').val('');
                $('#group_admin_new_group_expiration').val('');
            }
            else {
                swal('Oops...', result.message, 'warning');
            }
        },
        'json'
    );
}

function group_admin_my_groups() {
    var tbody = $('#table_group_admin_my_groups').find('tbody').empty();

    $.getJSON("group/getGroupsByOwner", function (items) {
        $.each(items, function (index, item) {
            var tr = $('<tr />').appendTo(tbody);
            tr.append('<td class="hidden">' + item.group.id + '</td>' +
                '<td>' + item.group.name + '</td>' +
                '<td>' + item.group.description + '</td>' +
                '<td class="hidden">' + ((item.site !== null) ? item.site.id : '0') + '</td>' +
                '<td>' + ((!item.site) ? 'none' : item.site.name) + '</td>' +
                '<td>' + ((item.group.private) ? 'Private' : 'Public') + '</td>' +
                '<td>' + item.status + '</td>' +
                '<td>' + item.available_rss.replace(/\"/g, "") + '</td>' +
                '<td>' + group_admin_my_groups_update_at(item.status, item.group, item.available_rss) + '</td>' +
                '<td>' + item.expiration + '</td>' +
                //(((item.status === 'Pending' || item.status === 'Denied' || item.status === 'Disabled'))
                ((item.status === 'Disabled')
                    ? '<td><button title="Detail Info" onclick="group_admin_group_detail($(this))">Detail</button>' +
                      '<button title="Withdraw" onclick="group_admin_group_delete($(this))">Delete</button></td>'
                    : '<td class="dropdown"><a class="btn btn-default group-admin-groups-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>')
            )
        })
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'group-admin-groups-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="group-admin-group-edit">Edit</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="group-admin-group-member">Members</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="group-admin-group-usage">Usage</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="group-admin-group-delete">Delete</a></li>').appendTo(contextMenu);

}

function group_admin_my_groups_update_at(status, group, resource) {
    if (status === 'Pending') {
        return 'Submitted @ ' + group.updated_at;
    } else if (status === 'Disabled') {
        return 'Disabled @ ' + group.updated_at;
    } else {
        if (resource === 'None') {
            return 'Created @ ' + group.created_at;
        } else {
            return 'Approved @ ' + group.approved_at;
        }
    }
}

function group_admin_group_config(element) {
    var gId = $(element).closest('tr').children().eq(0).html();
    var gName = $(element).closest('tr').children().eq(1).html();
    var status = $(element).closest('tr').children().eq(6).html();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_group_admin_group').attr('title', 'Update Group Configuration: ' + gName);
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
            '<li><a href="#tabs-group-admin-group-usage">Resource Usage</a></li>' +
            '</ul>' +
            '<div id="tabs-group-admin-group-detail" style="overflow: hidden;"></div>' +
            '<div id="tabs-group-admin-group-members" style="overflow: hidden;"></div>' +
            '<div id="tabs-group-admin-group-enroll" style="overflow: hidden;"></div>' +
            '<div id="tabs-group-admin-group-usage" style="overflow: hidden;"></div>'
        ));
    }

    $('<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label>Name:</label></td>' +
        '<td class="noBorder"><label id="group_admin_detail_name"></label><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="group_admin_detail_desc">Description:</label></td>' +
        '<td class="noBorder"><textarea id="group_admin_detail_desc" rows="2" cols="48" disabled style="background-color: lightgray"></textarea><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="group_admin_detail_private" >Private</label></td>' +
        '<td class="noBorder"><input type="checkbox" id="group_admin_detail_private" /></td></tr>' +
        '</table></div>').appendTo($('#tabs-group-admin-group-detail'));


    // if (status === 'Active' || status === 'Disabled') {
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
        $.getJSON("group/getGroupInfo/" + gId, function (group) {
            $('#group_admin_detail_name').html(group.name);
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
            $('#group_admin_detail_name').html(data.group.name);
            $('#group_admin_detail_desc').val(data.group.description);
            $('#group_admin_detail_private').attr('checked', (data.group.private === 1));

            if ((status === 'Active' && data.group.resource_requested !== '{}') || status === 'Pending') {
                $('<div><table class="noBorder">' +
                    '<tr class="noBorder"><td class="noBorder"><label>Status:</label></td>' +
                    '<td class="noBorder"><label>' + status + '</label><br><br></td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_detail_requested_rss" >Requested<br>Resources:</label></td>' +
                    '<td class="noBorder"><textarea id="group_admin_detail_requested_rss" rows="3" cols="48" disabled style="background-color: lightgray"> ' +
                        data.group.resource_requested + '</textarea><br><br></td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label for="group_admin_detail_requested_exp" >Requested<br>Expiration:</label></td>' +
                    '<td class="noBorder"><input type="text" id="group_admin_detail_requested_exp" size="50" disabled style="background-color: lightgray" value="' +
                        JSON.parse(data.group.resource_requested).expiration + '" /><br><br></td></tr>' +
                    '</table></div>').appendTo($('#tabs-group-admin-group-detail'));
                if (status === 'Active') {
                    $('<div>' +
                        '<label>Approved At:</label>&nbsp;&nbsp;<label id="group_admin_detail_approved_at">' + data.group.approved_at + '</label><br><br>' +
                        '<label>Approved Resources:</label>&nbsp;&nbsp;<label id="group_admin_detail_approved_rss">' + data.group.resource_allocated + '</label><br><br>' +
                        '<label>Approved Expiration:</label>&nbsp;&nbsp;<label id="group_admin_detail_approved_exp">' + data.group.expiration + '</label><br><br>' +
                        '<label>Reason:</label>&nbsp;&nbsp;<label id="group_admin_detail_reason">' + data.group.reason + '</label><br><br>' +
                        '</div>').appendTo($('#tabs-group-admin-group-detail'));
                }
            } else if (status === 'Active' && data.group.resource_requested === '{}') {
                $('<div><table class="noBorder">' +
                    '<tr class="noBorder"><td class="noBorder"><label>Status:</label></td>' +
                    '<td class="noBorder"><label style="font-style: italic">' + status + ', but no allocated resources can be used!</label></td></tr>' +
                    '<tr class="noBorder"><td class="noBorder"><label>Require Resource:</label><br><br></td>' +
                    '<td class="noBorder"><select id="group_admin_dlg_require_rss" onchange="group_admin_toggle_request_rss($(this),2)">' +
                        '<option value="0">No</option><option value="1">Yes</option></td></tr>' +
                    '</table></div>').appendTo($('#tabs-group-admin-group-detail'));
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
                    '</table></div>').appendTo($('#tabs-group-admin-group-detail'));

                $('#group_admin_dlg_request_expiration').datepicker();
                $('#group_admin_new_group_select_site').clone().prop('id', 'group_admin_dlg_request_rss_select_site').appendTo($('#group_admin_dlg_request_rss_select_site_div'));
            }

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
                member_tbody.append('<tr ' + ((disabled) ? 'disabled style="display: none;"' : '') + '>' +
                    '<td><input type="checkbox" name="group_admin_update_member_checkbox" ' +
                    ((disabled) ? 'disabled' : '') + ' ></td>' +
                    '<td class="hidden">' + user.data.id + '</td>' +
                    '<td><div style="width: 140px; word-break: break-all;" >' + user.data.email + '</div></td>' +
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
            user_counts_show.html(user_counts + ' Users.');
            $('#tabs-group-admin-group-members').waitMe('hide');
        });
    }
    group_admin_dlg_update(dlg_form, element, status);
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
    var div_container = $(document.createElement('table')).attr('class', 'noBorder').attr('width', '100%').appendTo(tab_form);
    var tr_container = $(document.createElement('tr')).attr('class', 'noBorder').appendTo(div_container);
    var left_form = $(document.createElement('td')).attr('class', 'noBorder').css('width', '200px').appendTo(tr_container);
    left_form.append(
        '<label>Default Role:&nbsp;</label><select id="group_admin_dlg_member_default_role"></select><br>' +
        '<label>All Users:</label><br><br>' +
        '<input type="text" placeholder="Search email" id="group_admin_search_user" style="width: 200px;" onkeyup="sys_admin_search_user_filter($(this))"/>' +
        '<select id="group_admin_select_group_member_list" MULTIPLE style="width: 200px; height: 260px"></select><br>' +
        '<h3 id="group_admin_dlg_user_counts"></h3>'
    );

    var middle = $(document.createElement('td')).attr('class', 'noBorder')
        .css('vertical-align', 'middle').css('text-align', 'center').css('width', '30px').appendTo(tr_container);
    $(document.createElement('button')).attr('id', 'btn_remove_group_member').text('->').attr('title', 'Add Member')
        .attr('onclick', 'group_admin_update_group_member($(this), 1)').appendTo(middle);
    $('<br/><br/>').appendTo(middle);
    $(document.createElement('button')).attr('id', 'btn_add_group_member').text('<-').attr('title', 'Remove Member')
        .attr('onclick', 'group_admin_update_group_member($(this), 0)').appendTo(middle);

    var right_list = $(document.createElement('td')).appendTo(tr_container);
    $('<label>Group Members:</label>&nbsp;&nbsp;<label id="group_admin_dlg_member_counts"></label>' +
        '<button onclick="group_admin_dlg_members_change_role()" style="float: right">Change Role</button><br><br>').appendTo(right_list);
    var members = $(document.createElement('table')).addClass('data').attr('id','tbl_dlg_group_members')
        .css('table-layout', 'fixed').appendTo(right_list);
    members.append('<thead>' +
        '<div><th><input type="checkbox" name="group_admin_update_member_checkbox_all" onclick="check_checkbox_group($(this))"></th>' +
        '<th class="hidden">UserId</th><th><div style="width: 140px; word-break: break-all;" >User Email</div></th>' +
        '<th class="hidden">role_id</th><th style="width: 90px;">Role</th></tr></thead><tbody></tbody>');
}

function group_admin_update_group_member(element, action) {
    var user_list = $('#group_admin_select_group_member_list');
    var member_table = element.closest('tr').find('table').find('tbody');
    var member_counts_str = $('#group_admin_dlg_member_counts').html();

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
    }
}

function group_admin_dlg_members_change_role() {
    var member_table = $('#tbl_dlg_group_members').find('tbody');
    var checked = member_table.find('input[type=checkbox]:checked:enabled');
    if (checked.length <= 0) {
        swal('Oops...', 'Please select members!', 'warning');
        return;
    }
    var isYes = false;
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dialog_confirm').attr('title', 'Change Role');

    var form_html = '<span>Change the role of selected users to: </span><select id="group_admin_dlg_member_update_select_role"></select>';
    $(form_html).appendTo(dlg_form);
    $('#dialog_confirm').dialog({
        modal: true,
        width: 400,
        buttons: {
            "Yes": function () {
                isYes = true;
                $(this).dialog('close');
            },
            "Cancel": function () {
                isYes = false;
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        },
        beforeClose: function () {
            if (isYes) {
                $.each(checked, function (index, row) {
                    $(row).closest('tr').children().eq(3).html($('#group_admin_dlg_member_update_select_role').val());
                    $(row).closest('tr').children().eq(4).html($('#group_admin_dlg_member_update_select_role option:selected').text());
                })
            }
        }
    });

    $.getJSON("group/getGroupAvailableRoles", function (roles) {
        $.each(roles, function (index, role) {
            $('<option value="' + role.id + '">' + role.name + '</option>').appendTo($('#group_admin_dlg_member_update_select_role'));
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

function group_admin_dlg_update(dlg_form, element, status) {
    var gId = $(element).closest('tr').children().eq(0).html();
    var gName = $(element).closest('tr').children().eq(1).html();
    var myButtons = {};
    if (status !== 'Disabled') {  // Active group
        myButtons = {
            "Update": function () {
                var isPrivate = ($('#group_admin_detail_private').is(':checked')) ? 1 : 0;
                var group = {'id': gId, 'name': gName, 'private': isPrivate, 'status': status};

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
                            swal('Oops...', 'Please enter integer in the requesting resources!');
                            return;
                        }
                        if (expiration.match(/^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/) === null) {
                            swal('Oops...', 'The date format is incorrect!', 'warning');
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
                        swal('Oops...', 'The format of input email addresses in the Batch Enroll are incorrect!', 'warning');
                        return;
                    }
                    if (batch_emails.length > 0) {
                        group['batch_emails'] = batch_emails;
                    }
                }

                $.post("group/updateGroup", {
                        "group": group
                    },
                    function(item) {
                        if (item.status === 'Success') {
                            if (item.group_status === 'Pending') {
                                element.closest('tr').children().eq(6).html('Pending');
                                element.closest('tr').children().eq(7).html('Reviewing...');
                                element.closest('tr').children().eq(8).html(group_admin_my_groups_update_at('Pending', item.group, 'Yes'));
                                element.closest('tr').children().eq(9).html(item.group.expiration);
                            }
                            element.closest('tr').children().eq(5).html(((item.group.private === 1) ? 'Private' : 'Public'));
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
        height: 550,
        overflow: "auto",
        width: 610,
        buttons: myButtons,
        close: function() {
            $(this).empty();
        }
    });
}

function group_admin_group_delete(element) {
    var gName = $(element).closest('tr').children().eq(1).html();
    var gStatus = $(element).closest('tr').children().eq(6).html();

    if (gStatus === 'Active' || gStatus === 'Disabled') {
        swal('Oops...', 'Active group\'s deletion function will implment soon!', 'warning');
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
                        swal('', data.message, 'success');
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
                        swal('Oops...', data.message, 'warning');
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
            swal('Oops...', 'Please upload .csv or .txt file!', 'warning');
            return false;
        }

        if (csvFile !== undefined) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var csvResult = e.target.result.split(/\r|\n|\r\n/);
                tArea.append(csvResult);
            };
            reader.readAsText(csvFile);
        }
    } else if (action === 2) {
        if (sys_admin_validate_emails(tArea.val())) {
            swal('', 'The format of input email addresses are correct.', 'success');
        } else {
            swal('Oops...', 'The format of input email addresses are incorrect!', 'warning');
        }
    }
}

function group_admin_join_groups() {
    var tbody = $('#table_group_admin_my_groups').find('tbody').empty();

    $.getJSON("group/getGroupsByOwner", function (items) {
        $.each(items, function (index, item) {
            var tr = $('<tr />').appendTo(tbody);
            tr.append('<td class="hidden">' + item.group.id + '</td>' +
                '<td>' + item.group.name + '</td>' +
                '<td>' + item.group.description + '</td>' +
                '<td class="hidden">' + ((item.site !== null) ? item.site.id : '0') + '</td>' +
                '<td>' + ((!item.site) ? 'none' : item.site.name) + '</td>' +
                '<td>' + ((item.group.private) ? 'Private' : 'Public') + '</td>' +
                '<td>' + item.status + '</td>' +
                '<td>' + item.available_rss + '</td>' +
                '<td>' + group_admin_my_groups_update_at(item.status, item.group) + '</td>' +
                '<td>' + item.expiration + '</td>' +
                (((item.status === 'Pending' || item.status === 'Denied' || item.status === 'Disabled'))
                    ? '<td><button title="Detail Info" onclick="group_admin_group_detail($(this))">Detail</button>' +
                    '<button title="Withdraw" onclick="group_admin_group_delete($(this))">Delete</button></td>'
                    : '<td class="dropdown"><a class="btn btn-default group-admin-groups-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>')
            );
        })
    });
}