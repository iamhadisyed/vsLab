/**
 * Created by root on 11/17/17.
 */

function site_admin_window(winId, win_main) {
    var tabs = {
        tabId: ['site_admin_resource_manager', 'site_admin_group_manager', 'site_admin_group_approval'],
        tabName: ['Resource Usage Summary', 'Group Manager', 'Resource Request']
    };

    create_tabs(winId, win_main, tabs, null);

    var tab_head = win_main.find('ul');
    var site_sel = $(document.createElement('select')).appendTo(tab_head);
    site_sel.attr('id', 'site_admin_site_select').css('position', 'absolute').css('right', (160+20)+'px')
        .css('color', '#1C94C4').css('margin-top', '4px').attr('onchange', 'site_admin_group_manager()');
    site_sel.append($('<option />').val(-1).html('...Select a Site...'));

    // var alert_arrow = $('<span><i class="fa fa-arrow-left faa-horizontal animated fa-2x"></i></span>').css('position', 'absolute')
    //     .css('right', '150px').css('top', '10px').css('color', 'red');
    // site_sel.after(alert_arrow);

    $.getJSON("siteadmin/getSiteList", function (sites) {
        $.each(sites, function(index, site) {
            site_sel.append($('<option />').val(site.id).html(site.name));
        })
    });

    var html_summary = '<div>' +
        '<label id="site_admin_summary_active_groups">Active Groups: 0</label><br><br>' +
        '<label id="site_admin_summary_pending_groups">Pending Groups: 0</label><br><br>' +
        '<label id="site_admin_summary_disabled_groups">Disabled Groups: 0</label><br><br>' +
        '<label id="site_admin_summary_denied_groups">Denied Groups: 0</label><br><br>' +
        '<label id="site_admin_summary_total_resources">Total Resource: </label><br><br>' +
        '<label id="site_admin_summary_available_resources">Available Resources:</label><br><br>' +
        '<label id="site_admin_summary_resource_usage">Resource Usage:</label>' +
        '</div>';
    $(html_summary).appendTo($('#site_admin_resource_manager'));

    var html_group_manager = '<div>' +
        '<table class="data" id="table_site_admin_group">' +
        '<thead><tr><th class="hidden">ID</th><th>Name</th><th>Description</th><th>Status</th>' +
        '<th>Owner</th><th>Resource Usage</th><th>Expiration</th><th>Resource Approved</th><th>Updated</th><th>Actions</th></tr></thead>' +
        '<tbody></tbody></table></div>';
    $(html_group_manager).appendTo($('#site_admin_group_manager'));

    var html_group_approval = '<div>' +
        '<table class="data" id="table_site_admin_group_pending">' +
        '<thead><tr><th class="hidden">ID</th><th>Name</th><th>Description</th><th>Status</th>' +
        '<th>Owner</th><th>Requested Resource</th><th>Expiration</th><th>Created</th><th>Actions</th></tr></thead>' +
        '<tbody></tbody></table></div>';
    $(html_group_approval).appendTo($('#site_admin_group_approval'));

}


function site_admin_group_manager() {
    var tbody = $('#table_site_admin_group').find('tbody').empty();
    var tbody_pending = $('#table_site_admin_group_pending').find('tbody').empty();
    var select_site = $('#site_admin_site_select').val();

    $.getJSON("group/getGroupsBySite/" + select_site, function (data) {
        var active=0, pending=0, disabled=0, denied=0;
        $.each(data.groups, function (index, item) {
            if (item.status === 'Pending' || item.status === 'Denied') {
                (item.status === 'Pending') ? pending++ : denied++;
                $('<tr><td class="hidden">' + item.group.id + '</td><td>' + item.group.name + '</td><td>' +
                    item.group.description + '</td><td>' + item.status + '</td><td>' + item.owner + '</td><td>' +
                    item.group.resource_requested + '</td><td>' +
                    ((item.status === 'Pending') ? JSON.parse(item.group.resource_requested).expiration : '') + '</td><td>' +
                    item.group.created_at + '</td><td>' +
                    ((item.status === 'Pending') ?
                    '<button title="Approve/Decline" onclick="site_admin_group_process($(this))">Process</button>' :
                        '<button title="Delete" onclick="site_admin_delete_group($(this))">Delete</button>')   +
                    '</td></tr>').appendTo(tbody_pending);
            }
            else if (item.status === 'Active' || item.status === 'Disabled') {
                (item.status === 'Active' ) ? active++ : disabled++;
                $('<tr><td class="hidden">' + item.group.id + '</td><td>' + item.group.name + '</td><td>' +
                    item.group.description + '</td><td>' + item.status + '</td><td>' + item.owner + '</td><td>' +
                    item.group.resource_usage + '</td><td>' + item.group.expiration + '</td><td>' +
                    ((item.group.approved_at === null) ? 'None' : item.group.approved_at) + '</td><td>' +
                    item.group.updated_at + '</td><td>' +
                    '<button title="Edit" onclick="site_admin_group_operations($(this))">Edit</button>&nbsp;' +
                    '</td></tr>').appendTo(tbody);
            }
        });

        $('#site_admin_summary_active_groups').html('Active Groups: ' + active);
        $('#site_admin_summary_pending_groups').html('Pending Groups: ' + pending);
        $('#site_admin_summary_disabled_groups').html('Disabled Groups: ' + disabled);
        $('#site_admin_summary_denied_groups').html('Denied Groups: ' + denied);
        $('#site_admin_summary_total_resources').html('Total Resource: ' + data.site.resources);
        $('#site_admin_summary_resource_usage').html('Resource Usage: ' + data.site.resource_usage);
    })
}


function site_admin_delete_group(element) {
    var gName = $(element).closest('tr').children().eq(1).html();
    var message = 'Do you really want to Delete "' + gName + '"?';
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

                        // var tbody = $('#table_group_admin_my_groups').find('tbody');
                        // var contextMenu = $(document.createElement('ul')).appendTo(tbody);
                        // contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'group-admin-groups-contextMenu')
                        //     .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
                        // $('<li><a tabindex="-1" href="#" class="group-admin-group-edit">Edit</a></li>').appendTo(contextMenu);
                        // $('<li><a tabindex="-1" href="#" class="group-admin-group-member">Members</a></li>').appendTo(contextMenu);
                        // $('<li><a tabindex="-1" href="#" class="group-admin-group-usage">Usage</a></li>').appendTo(contextMenu);
                        // $('<li><a tabindex="-1" href="#" class="group-admin-group-delete">Delete</a></li>').appendTo(contextMenu);
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

function site_admin_group_process(element) {
    var gId = $(element).closest('tr').children().eq(0).html();
    var gName = $(element).closest('tr').children().eq(1).html();
    var requested_exp = $(element).closest('tr').children().eq(6).html();
    var requested_rss = $(element).closest('tr').children().eq(5).html();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_site_admin_process_group').attr('title', 'Process Group ' + gName + ' Application');
    $('<div>' +
        '<label>Group Name:</label>&nbsp;<label>' + gName + '</label><br>' +
        '<label>Description:</label>&nbsp;<label>' + $(element).closest('tr').children().eq(2).html() + '</label><br>' +
        '<label>Group Owners:</label>&nbsp;<label>' + $(element).closest('tr').children().eq(4).html() + '</label><br>' +
        '<label>Requested Expiration Date:</label>&nbsp;<label>' + requested_exp + '</label><br>' +
        '<label>Requested Resources:</label>&nbsp;<div id="site_admin_dlg_group_process_requested_rss">' + requested_rss.replace(',', '<br>') + '</div>' +
        '</div><br><br>').appendTo(dlg_form);
    $('<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="site_admin_dlg_group_process_approved_rss" >Approved Resources:</label></td>' +
        '<td class="noBorder"><textarea id="site_admin_dlg_group_process_approved_rss" rows="3" cols="48">' + requested_rss + '</textarea><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="site_admin_dlg_group_process_approved_exp" >Approved Expiration:</label></td>' +
        '<td class="noBorder"><input type="text" id="site_admin_dlg_group_process_approved_exp" size="50" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="site_admin_dlg_group_process_reason" >Reason to Decline:</label></td>' +
        '<td class="noBorder"><textarea id="site_admin_dlg_group_process_reason" rows="3" cols="48"></textarea><br><br></td></tr>' +

        '</table></div>').appendTo(dlg_form);

    var datepicker = $('#site_admin_dlg_group_process_approved_exp');

    $(datepicker).datepicker({dateFormat: 'mm/dd/yy'}).datepicker('setDate', new Date(requested_exp));

    var group = {'id': gId, 'approved_rss': $.trim($('#site_admin_dlg_group_process_approved_rss').val()),
        'name': gName, 'approved_exp': $.trim($(datepicker).val()),
        'reason': $.trim($('#site_admin_dlg_group_process_reason').val())};

    var myButtons = {
        "Approve": function () {
            $.post("siteadmin/groupApplicationProcess", {
                    "process": 'Approved',
                    "site": $('#site_admin_site_select').val(),
                    "group": group
                },
                function(item) {
                    if (item.status === 'Success') {
                        element.closest('tr').remove();
                        var tbody = $('#table_site_admin_group').find('tbody');
                        $('<tr><td class="hidden">' + item.group.id + '</td><td>' + item.group.name + '</td><td>' +
                            item.group.description + '</td><td>' + item.status + '</td><td>' + item.owner + '</td><td>' +
                            item.group.resource_usage + '</td><td>' + item.group.expiration + '</td><td>' +
                            item.group.approved_at + '</td><td>' + item.group.updated_at + '</td><td>' +
                            '<button title="Edit" onclick="">Edit</button>&nbsp;' +
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
        "Decline": function () {
            $.post("siteadmin/groupApplicationProcess", {
                    "process": 'Decline',
                    "group": group
                },
                function(item) {
                    if (item.status === 'Success') {
                        element.closest('tr').children().eq(3).html('Denied');
                        $(element).closest('tr').children().eq(6).html('');
                        $(element).closest('tr').children().eq(5).html('');
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
    };

    $(dlg_form).dialog({
        modal: true,
        height: 530,
        overflow: "auto",
        width: 610,
        buttons: myButtons,
        close: function() {
            $(this).empty();
        }
    });
}

function site_admin_group_operations(element) {
    swal('Oops...', 'This function will implement soon!', 'warning');
}

function edit_site_user_button(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_manage_site').attr('title', 'manage site');

    var form_html2 = '<div>' +
        //'<form>' +
        '<input type="text" id="inut_site_name" value="'+element.parent().prev().prev().prev().prev().prev().html()+'" disabled>' +
        '<input type="text" id="site-group-input" placeholder="group name">' +
        '<input type="text" id="group-res-input" placeholder="group resource json">' +
        '<input type="submit" value="add group to site" onclick="submitAddGroup2Site()">' +
        //'</form>' +
        '<table id="group-res-table">' +
        '<tr><th>group resource (json)</th><th>used resource (json)</th></tr>' +
        '</table>' +
        '</div>';
    $(form_html2).appendTo(dlg_form);

    displayGroupResTable();

    $('#dlg_manage_site').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            display_site_table();
            $(this).remove();
        }
    });
}

function submitAddGroup2Site() {
    $.post("/cloud/addGroup2Site", {
            "group_name": $("#site-group-input").val(),
            "group_res_json": $("#group-res-input").val(),
            "site_name": $("#inut_site_name").val()
        },
        function (data) {
            //alert("test 111 " + JSON.stringify(data));
            $.jAlert(
                {
                    'title':'Information',
                    'content':'group-site Request Sent',
                    'theme':'blue',
                    'btns':
                        {
                            'text':'close',
                            'theme':'green'
                        }
                }
            );
            displayGroupResTable();
        },
        'json'
    );
}

function displayGroupResTable() {
    $.getJSON("/cloud/getGroupResourceTable", function (jsondata) {
        var tbody = $('#group-res-table').find('tbody').empty();
        $("<tr><th>Group name</th><th>Group resource (json)</th><th>Used resource (json)</th></tr>").appendTo(tbody);
        $.each(jsondata, function (index, item) {
            $('<tr>' + // group_name
                '<td>' + item.group_name + '</td>' +
                '<td>' + item.group_res + '</td>' +
                '<td>' + '{}' + '</td>' +
                '</tr>').appendTo(tbody);
        });
    });
}

function display_role_res_table() {
    $.getJSON("/cloud/getroleresTable", function (jsondata) {
        var tbody = $('#temp_res').find('tbody').empty();
        $("<tr><th>role</th><th>json</th><th>edit</th></tr>").appendTo(tbody);
        $.each(jsondata, function (index, item) {
            $('<tr>' +
                '<td>' + item.role + '</td>' +
                '<td>' + item.resjson + '</td>' +
                '<td>' + '<button class="edit_role_json_button">Edit</button>' + '</td>' +
                '</tr>').appendTo(tbody);
        });
    });
}

