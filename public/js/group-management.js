/**
 * Created by James on 10/28/2018.
 */


function modal_create_group(element) {
    var modal = $('#modal-group-create');
    var select_site = $('#site_select');

    $.getJSON('/siteadmin/getSiteAll', function(sites) {
        $.each(sites, function(index, site) {
            select_site.append($('<option value="' + site.id + '" data-desc="' + site.description + '" >' +  site.name.trim() + '</option>'));
        });
    });

    modal.modal('show');
}

function create_group(element) {
    var name = $('#new-group-name').val().trim();
    var desc = $('#new-group-description').val().trim();
    if (name.length <= 0|| desc.length <=0) {
        Swal.fire('Please enter Group Name and Description!', '', 'warning');
        return;
    }
    if (name.indexOf(' ') !== -1) {
        Swal.fire('White space is not allowed in the Group Name!', '', 'error');
        return;
    }
    if (name.indexOf('(') !== -1) {
        Swal.fire('Brackets are not allowed in the Group Name!', '', 'error');
        return;
    }
    if (name.indexOf(')') !== -1) {
        Swal.fire('Brackets are not allowed in the Group Name!', '', 'error');
        return;
    }
    var require_rss = {};
    var site_id = 1;
    if ($('#resource_requested').is(':checked')) {
        var expiration = $('#expiration').val();
        var labs = parseInt($('#labs').val());
        var vms = parseInt($('#vms').val());
        var cpu = parseInt($('#vcpus').val());
        var ram = parseInt($('#ram').val());
        var storage = parseInt($('#storage').val());

        if  (isNaN(labs) || isNaN(vms) || isNaN(cpu) || isNaN(ram) || isNaN(storage)) {
            Swal.fire('Please enter integer in the requesting resources!', '', 'warning');
            return;
        }
        if (expiration.match(/^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/) === null) {
            Swal.fire('The date format is incorrect!', '', 'error');
            return;
        }

        require_rss['labs'] = labs;
        require_rss['vms'] = vms;
        require_rss['cpu'] = cpu;
        require_rss['ram'] = ram;
        require_rss['storage'] = storage;
        require_rss['expiration'] = expiration;
        site_id = $('#site_select').val();
    } else {
        site_id = 1;
        require_rss = {};
    }

    var group = {name: name, description: desc, site_id: site_id, expiration: expiration,
        private: $('#isPublic').val(), resource_requested: JSON.stringify(require_rss)};

    $.post("groups", {
            "group": group
        }, function (result) {
            $('#modal-group-create').modal('hide');

            if (result.status === 'Success') {
                Swal.fire(result.message, '', 'success');
                $('#dataTableBuilder').DataTable().draw();
            }
            else {
                Swal.fire('Create Group Failed!', result.message, 'error');
            }
        },
        'json'
    );
}

function group_details_format(data) {
    return '<div style="margin-left: 10px; margin-right: 10px;"><h4>Group ' + data.name + '\'s Members</h4>' +
        '<table class="table details-table table-condensed" id="group-members-' + data.id + '" cellpadding="0" border="0" width="100%">' +
        '<thead>' +
        '<tr>' +
        '<th><input id="members_check_all" type="checkbox" onchange="checkbox_check_all($(this))"/></th>' +
        '<th>Name</th>' +
        '<th>Email</th>' +
        '<th>Status</th>' +
        //'<th>Org ID</th>' +
        '<th>Last Activity</th>' +
        '<th>Last Login</th>' +
        '<th>Last Logout</th>' +
        '<th>Role</th>' +
        '<th>Action</th>' +
        '</tr>' +
        '</thead>' +
        '</table></div>';
}

function group_details_initTable(tableId, data) {
    $('#' + tableId).DataTable({
        dom: 'Blf<"toolbar">rtip',
        buttons: ['csv', 'print', 'reload'],
        destroy: true,
        processing: true,
        serverSide: true,
        // scrollX: true,
        // scrollCollapse: true,
        // scroller: true,
        ajax: data.details_url,
        columnDefs: [{
            targets: 0,
            searchable:false,
            orderable:false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
                return '<input type="checkbox" name="' + tableId + '-members[]" value="'
                    + $('<div/>').text(data).html() + '">';
            }
        }, {
            targets: 8,
            searchable:false,
            orderable:false,
            render: function (data, type, full, meta) {
                return '<div class="btn-group">' +
                    '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    'Action <span class="caret"></span>' +
                    '</button>' +
                    '<ul class="dropdown-menu" style="min-width: 10px;">' +
                    '<li><a href="#" onclick="gotousertimeline($(this))">Show Activity History</a></li>' +
                    '<li><a href="#" onclick="group_members_change_roles($(this))">Change Role</a></li>' +
                    '<li role="separator" class="divider"></li>' +
                    '<li><a href="#" onclick="group_members_delete($(this))" style="color:red">Remove</a></li>' +
                    '</ul>' +
                    '</div>';
            }
        }],
        columns: [
            { data: "checkbox", name: "checkbox" },
            { data: "name", name: "users.name" },
            { data: "email", name: "users.email" },
            { data: "status" },
            //{ data: "org_id", name: "users.org_id" },
            { data: "last_activity", name: "users.last_activity"},
            { data: "last_login", name: "users.last_login"},
            { data: "last_logout", name: "users.last_logout"},
            { data: "role", name: "roles.name" }
        ]
    });

    $('div.toolbar').html('<span style="margin-left: 20px;">' +
        '<button type="button" class="btn btn-primary" style="margin-left: 5px" onclick="modal_group_add_members($(this))">Add Members</button>' +
        '<button type="button" class="btn btn-danger" style="margin-left: 5px" onclick="group_members_delete($(this))">Remove Members</button>');
        // '<button type="button" class="btn btn-info" style="margin-left: 5px" onclick="group_members_change_roles($(this))">Change Role</button></span>');
}

function checkbox_check_all(element) {
    var table = $(element).closest('table').DataTable();
    var rows = table.rows({ 'search': 'applied' }).nodes();
    //var rows = table.fnGetNodes();
    $('input[type="checkbox"]', rows).prop('checked', element[0].checked);
}

function group_members(element) {
    var tr = $(element).closest('tr');
    var row = $('#dataTableBuilder').DataTable().row(tr);
    var tableId = 'group-members-' + row.data().id;

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        row.child(group_details_format(row.data())).show();
        group_details_initTable(tableId, row.data());
        tr.addClass('shown');
        tr.next().find('td').addClass('no-padding bg-gray');
    }
}

function modal_group_add_members(element) {
    var row = $('#dataTableBuilder').DataTable().row(element.closest('tr').prev()).data();
    var groupId = row.id;
    var groupName = row.name;

    var modal = $('#modal-group-members-add');
    modal.find('#group-name-add-member').empty().html(groupName);
    modal.find('#group-id-add-member').empty().html(groupId);

    var table = $('#group_add_members_all_users').DataTable({
        pageLength: 10,
        // lengthMenu: [ [10, 15, 20, 25, 50, -1], [10, 15, 20, 25, 50, "All"] ],
        destroy: true,
        paging: true,
        processing: true,
        serverSide: true,
        dom: 'lf<"members-toolbar">rtip',
        // deferLoading: 10,
        // scrollX: true,
        // scrollCollapse: true,
        // scrollY: 300,
        // scroller: {
        //     loadingIndicator: true
        // },
        ajax: modal.find('#url').text(),
        columnDefs: [{
            targets: 0,
            searchable: false,
            orderable: false,
            className: 'dt-body-center',
            render: function (data, type, full, meta) {
                return '<input type="checkbox" name="users[]" value="' + data + '">';
            }
        }],
        columns: [
            {data: "checkbox"}, // name: "checkbox"},
            {data: "name"},// name: "users.name"},
            {data: "email"},// name: "users.email"},
            {data: "institute"}, //name: "users.institute"},
            //{data: "org_id"} //, name: "users.org_id"},
            // { data: "role", name: "roles.name" }
        ]
    });

    $('div.members-toolbar').html('<span style="margin-left: 20px;"><strong>Role for New Members:</strong> ' +
        '<select id="select_role_for_new_members"><option value="-1">...Select Role...</option></select></span>'
    );

    $.getJSON('groups/available-roles', function(roles) {
        $.each(roles, function(index, role) {
            $('<option value="' + role.id + '">' + role.name + '</option>').appendTo($('#select_role_for_new_members'));
        });

    });

    var excludes = $('#group-members-' + groupId).DataTable().column(2).data().toArray().join('|');
    table.column(2).search('^(?!.*(' + excludes + ')).*$', true, false).draw();
    modal.modal('show');
}

function group_members_add(element) {
    var modal = $('#modal-group-members-add');
    var group_id = modal.find('#group-id-add-member').text();
    var group_name = modal.find('#group-name-add-member').text();
    var active = modal.find('ul.nav-tabs li.active').find('a').attr('href');

    if (active === '#add-members') {
        var table = $(element).closest('table').DataTable();
        var members = $('[name="users[]"]:checked');
        var role_id = $('#select_role_for_new_members').val();

        if (members.length <= 0) {
            Swal.fire('Please select members!', '', 'warning');
            return;
        }
        if (role_id < 0) {
            Swal.fire('Please select role for new members!', '', 'warning');
            return;
        }
        var final_m = [];
        for (var i=0; i < members.length; i++) {
            final_m.push(members[i].value);
        }
        $.post('/groups/add-members', {
            group_id: group_id,
            members: final_m,
            role_id: role_id
            }, function(result) {
                if (result.status === 'Success') {
                    Swal.fire('Group members added!', result.message + ' to ' + group_name, 'success');
                    modal.modal('hide');
                    $('#group-members-' + group_id).DataTable().draw();
                }
            });
    } else if (active === '#batch-enroll') {
        var batch_emails = $.trim($('#group_admin_dlg_batch_enroll_emails').val());
        if (batch_emails.length > 0 && !sys_admin_validate_emails(batch_emails)) {
            Swal.fire('The format of email addresses are incorrect!', '', 'error');
        } else if (batch_emails.length <= 0) {
            Swal.fire('Please enter email addresses!', '', 'warning');
        } else if (batch_emails.length > 0) {
            $.post('/groups/batch-enroll', {
                group_id: group_id,
                batch_emails: batch_emails
                }, function(data) {
                    if (data.status === 'Success') {
                        modal.modal('hide');
                        Swal.fire('Batch Enroll Done!', data.message + ' to ' + group_name, 'success');
                        $('#group-members-' + group_id).DataTable().draw();
                    } else {
                        Swal.fire('Batch Enroll Failed!', data.message, 'warning');
                    }
                });
        }
    }
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

function group_members_delete(element) {
    var group_row = (element.is('button')) ?
        $('#dataTableBuilder').DataTable().row(element.closest('tr').prev()).data() :
        $('#dataTableBuilder').DataTable().row(element.closest('table').closest('tr').prev()).data();
    var groupId = group_row.id;
    var groupName = group_row.name;
    var member_table = $('#group-members-' + groupId).DataTable();
    var members = [], remove_members = [];
    var invalid = "";

    if (element.is('button')) {
        members = $('[name="group-members-' + groupId + '-members[]"]:checked');
        if (members.length <= 0) {
            Swal.fire('Please select labs!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        members.push(element);
    }

    for (var i=0; i<members.length; i++) {
        var row = member_table.row(members[i].closest('tr')).data();
        if (row.role.indexOf('group_owner') >=0 ) {
            invalid += row.name + ',';
        } else {
            remove_members.push(row.id);
        }
    }

    if (invalid.length > 0) {
        Swal.fire({
            title: 'Remove Member Failed!', type: 'error',
            html: '<em>Reason</em><br />' +
                'Group owner <strong>' + invalid.slice(0,-1) + '</strong> cannot be deleted!'
        });
    } else {
        Swal.fire({
            title: 'Remove members?',
            text: 'Please confirm to remove the selected members from ' + groupName,
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.post('/groups/remove-members', {
                    'group_id': groupId,
                    'members': remove_members
                }, function (result) {
                    if (result.status === 'Success') {
                        Swal.fire('Remove Members Done.', result.message + 'from ' + groupName, 'success');
                        member_table.draw();
                    } else {
                        Swal.fire('Remove Members Failed!', result.message + 'from ' + groupName +
                            '<br />Please release and delete their allocated resources from "Lab Deployment" in the "Lab Management" and' +
                            '<br />remove them from the team list in the "Team \& Lab Assignment" in the "Lab Mangement" first.', 'error');
                    }
                });
            }
        });
    }
}

function gotousertimeline(element) {
    var group_row = $('#dataTableBuilder').DataTable().row(element.closest('table').closest('tr').prev()).data();
    var groupId = group_row.id;
    var row = $('#group-members-' + groupId).DataTable().row(element.closest('tr')).data();
    //alert(row.id);
    window.location.href = "/timeline/"+row.id;
}


function group_members_change_roles(element) {
    var group_row = $('#dataTableBuilder').DataTable().row(element.closest('table').closest('tr').prev()).data();
    var groupId = group_row.id;
    var groupName = group_row.name;
    var row = $('#group-members-' + groupId).DataTable().row(element.closest('tr')).data();
    // var tableId = $(element).closest('td').find('table').attr('id');

    Swal.fire({title: 'Change role for ' + ((row.name === "") ? row.email : row.name),
        type: 'info',
        html: '<div><p id="avail_roles" align="left" style="margin-left: 100px;"></p></div>',
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: 'Update',
        //confirmButtonAriaLabel: 'Thumbs up, great!',
        cancelButtonText: 'Cancel',
        //cancelButtonAriaLabel: 'Thumbs down',
        onBeforeOpen: (dom) => {
            $.getJSON('groups/available-roles', function(roles){
                $.each(roles, function (index, role) {
                    if (role.name === 'group_owner') {
                        if (row.role.indexOf(role.name) >= 0) {
                            $('<input type="checkbox" name="roles[]" value="' + role.id + '" disabled checked>' +
                                role.name + '<br />').appendTo(dom.querySelector('#avail_roles'));
                        }
                    } else {
                        $('<input type="checkbox" name="roles[]" value="' + role.id + '" ' +
                            ((row.role.indexOf(role.name) >= 0) ? 'checked' : '') + '>' +
                            role.name + '<br />').appendTo(dom.querySelector('#avail_roles'));
                    }
                });
            });
        }
    }).then((result) => {
        if (result.value) {
            var checked_roles = [];
            $('[name="roles[]"]:checked').each(function() {
                checked_roles.push($(this).val());
            });
            $.post('/groups/change-roles', {
                'group_id': groupId,
                'user_id': row.id,
                'roles': checked_roles
            }, function (result) {
                if (result.status === 'Success') {
                    Swal.fire('Role Change Done.', row.name + '\'s' + result.message + 'in ' + groupName, 'success');
                    $('#group-members-' + groupId).DataTable().draw();
                } else {
                    Swal.fire('Role Change Failed!', result.message + 'in ' + groupName, 'error');
                }
            });
        }
    });
}

function group_delete(element) {
    var data = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();
    var groupId = data.id;
    var groupName = data.name;
    var status = data.status;

    Swal.fire({
        title: 'Delete Group?',
        text: 'Please confirm to delete group ' + groupName,
        type: 'question',
        showCancelButton: true,
        confirmButton: 'Yes'
    }).then((result) => {
        if (result.value) {
            $.post('/groups/delete', {
                'group_id': groupId,
                'group_name': groupName
            }, function (result) {
                if (result.status === 'Success') {
                    Swal.fire('Group Deleted.', result.message, 'success');
                    $('#dataTableBuilder').DataTable().draw(false);
                } else {
                    Swal.fire('Delete Group Failed!', result.message, 'error');
                }
            });
        }
    });
}

function group_management_edit(element) {
    var data = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();
    var groupId = data.id;
    var groupName = data.name;
    var status = data.status;

    var modal = $('#modal-group-edit');
    modal.modal('show');
    modal.find('#group-name-edit').empty().html(groupName);
    modal.find('#group-id-edit').empty().html(groupId);
    modal.find('#group-status-edit').empty().html(status);

    $.getJSON("group/getGroupInfo/" + groupId, function (group) {
        modal.find('#group_info_name').empty().val(group.name);
        modal.find('#group_info_desc').empty().val(group.description);
        modal.find('#group_info_private').attr('checked', (group.private === 1));
        modal.find('#group_info_status').empty().val(status);

        // if (status === 'Disabled') {
        //     $('#group_admin_detail_requested_rss').val(group.resource_requested);
        //     $('#group_admin_detail_requested_exp').val(JSON.parse(group.resource_requested).expiration);
        //
        //     if (status === 'Disabled') {
        //         $('#group_admin_detail_approved_at').html(group.approved_at);
        //         $('#group_admin_detail_approved_rss').html(group.resource_allocated);
        //         $('#group_admin_detail_approved_exp').html(JSON.parse(group.resource_requested).expiration);
        //         $('#group_admin_detail_disabled_at').html(group.updated_at);
        //         $('#group_admin_detail_reason').html(group.reason);
        //     } else if (status === 'Denied') {
        //         $('#group_admin_detail_denied_at').html(group.approved_at);
        //         $('#group_admin_detail_reason').html(group.reason);
        //     }
        // } else {
        //     if ((status === 'Active' && group.resource_requested !== null) || status === 'Pending') {
        //         $('<div class="form-group"><label class="col-xs-2 control-label" for="group_admin_detail_status" >Status:</label>' +
        //             '<div class="col-xs-10"><label id="group_admin_detail_status">' + status + '</label></div></div>' +
        //             '<div class="form-group"><label class="col-xs-2 control-label" for="group_admin_detail_requested_rss">Requested Resources:</label>' +
        //             '<div class="col-xs-10"><textarea class="form-control" id="group_admin_detail_requested_rss" rows="3" disabled style="resize : none;"> ' +
        //             group.resource_requested + '</textarea></div></div>' +
        //             '<div class="form-group"><label class="col-xs-2 control-label" for="group_admin_detail_requested_exp">Requested Expiration:</label>' +
        //             '<div class="col-xs-10"><input class="form-control" type="text" id="group_admin_detail_requested_exp" disabled value="' +
        //             JSON.parse(group.resource_requested).expiration + '"></div></div>').appendTo(div_detail_info_form);
        //         if (status === 'Active') {
        //             $('<div class="form-group"><label class="col-xs-2 control-label">Approved At:</label>' +
        //                 '<div class="col-xs-10"><label id="group_admin_detail_approved_at">' + group.approved_at + '</label></div></div>' +
        //                 '<div class="form-group"><label class="col-xs-2 control-label">Approved Resources:</label>' +
        //                 '<div class="col-xs-10"><label id="group_admin_detail_approved_rss">' + group.resource_allocated + '</label></div></div>' +
        //                 '<div class="form-group"><label class="col-xs-2 control-label">Approved Expiration:</label>' +
        //                 '<div class="col-xs-10"><label id="group_admin_detail_approved_exp">' + group.expiration + '</label></div></div>' +
        //                 '<div class="form-group"><label class="col-xs-2 control-label">Reason:</label>' +
        //                 '<div class="col-xs-10"><label id="group_admin_detail_reason">' + group.reason + '</label></div></div>').appendTo(div_detail_info_form);
        //         }
        //     } else if (status === 'Active' && group.resource_requested === null) {
        //         $('<div class="col-md-6>' +
        //             '<tr class="noBorder"><td class="noBorder"><label>Status:</label></td>' +
        //             '<td class="noBorder"><label style="font-style: italic">' + status + ', but no allocated resources can be used!</label></td></tr>' +
        //             '<tr class="noBorder"><td class="noBorder"><label>Require Resource:</label><br><br></td>' +
        //             '<td class="noBorder"><select id="group_admin_dlg_require_rss" onchange="group_admin_toggle_request_rss($(this),2)">' +
        //             '<option value="0">No</option><option value="1">Yes</option></td></tr>' +
        //             '</table></div>').appendTo(div_detail_info_form);
        //         $('<div id="group_admin_dlg_request_rss" style="display: none;">' +
        //             '<label style="font-style: italic;">Select requesting resources from:</label><br><br>' +
        //             '<table class="noBorder" style="table-layout: fixed; width: 190px;"><colgroup><col width="120px" /><col width="250px"></colgroup>' +
        //             '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_select_site">Site:</label></td>' +
        //             '<td class="noBorder"><span id="group_admin_dlg_request_rss_select_site_div"></span>&nbsp;&nbsp;<span id="group_admin_dlg_request_rss_select_site_desc"></span></td></tr>' +
        //             '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_labs">Number of Labs:</label></td>' +
        //             '<td class="noBorder"><input id="group_admin_dlg_request_rss_labs" size="3px"/></td></tr>' +
        //             '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_vms">VM per lab: </label></td>' +
        //             '<td class="noBorder"><input id="group_admin_dlg_request_rss_vms" size="3px"/></td></tr>' +
        //             '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_cpu">CPU per lab: </label></td>' +
        //             '<td class="noBorder"><input id="group_admin_dlg_request_rss_cpu" size="3px"/></td></tr>' +
        //             '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_ram">Memory per lab: </label></td>' +
        //             '<td class="noBorder"><input id="group_admin_dlg_request_rss_ram" size="3px"/>MB</td></tr>' +
        //             '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_rss_storage">Storage per lab: </label></td>' +
        //             '<td class="noBorder"><input id="group_admin_dlg_request_rss_storage" size="3px"/>GB</td></tr>' +
        //             '<tr class="noBorder"><td class="noBorder"><label for="group_admin_dlg_request_expiration">Use util: </label></td>' +
        //             '<td class="noBorder"><input id="group_admin_dlg_request_expiration" size="8px"/></td></tr>' +
        //             '</table></div>').appendTo(div_detail_info_form);
        //
        //         $('#group_admin_dlg_request_expiration').datepicker();
        //         $('#group_admin_new_group_select_site').clone().prop('id', 'group_admin_dlg_request_rss_select_site').appendTo($('#group_admin_dlg_request_rss_select_site_div'));
        //     }
        // }
    });

}

function group_info_update(element) {
    var modal = $('#modal-group-edit');
    var gId = modal.find('#group-id-edit').html();
    var gName = modal.find('#group-name-edit').html();
    var status = modal.find('#group-status-edit').html();

    var isPrivate = ($('#group_info_private').is(':checked')) ? 1 : 0;
    var group = {'id': gId, 'name': gName, 'description': $('#group_info_desc').val(),
        'private': isPrivate, 'status': status};

    $.post("group/updateGroup", {
            "group": group
        },
        function(item) {
            if (item.status === 'Success') {
                modal.modal('hide');
                Swal.fire(item.message, '', 'success');
                $('#dataTableBuilder').DataTable().draw();
            } else {
                Swal.fire(item.message, '', 'warning');
            }
        },
        'json'
    );
}


/**
 *
 * Old Functions
 *
 */

function run_waitMe(selector, effect) {
    $(selector).waitMe({
        effect: effect,
        text: 'Please wait ...',
        bg: 'rgba(255,255,255,0.7)',
        color:'#000'
    });
}

function group_management_group_update(element) {
    var modal = $('#modal-group-edit');
    var gId = modal.find('#group-id-edit').html();
    var gName = modal.find('#group-name-edit').html();
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

        $.post("group/updateGroup", {
                "group": group
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
    var gId = modal.find('#group-id-edit').html();
    var gName = modal.find('#group-name-edit').html();
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
            Swal.fire('Email addresses are correct.', '', 'success');
        } else {
            Swal.fire('The format of email addresses are incorrect!', '', 'error');
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