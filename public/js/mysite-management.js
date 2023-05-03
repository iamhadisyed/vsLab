/**
 * Created by James on 3/22/2019.
 */

function modal_mysite_group_default_rss(element) {
    var data = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();
    var modal = $('#modal-mysite-group-default-rss');
    modal.modal('show');

    modal.find('#site-name-default-rss').empty().html(data.name);
    modal.find('#site-id-default-rss').empty().html(data.id);

    var rss = (data.group_default_resource !== null) ?
        JSON.parse(data.group_default_resource.replace(/&quot;/g, '"')) :
        JSON.parse('{ "labs": 0, "vms": 0 , "vcpus": 0, "ram": 0, "storage": 0}');

    modal.find('#group-default-rss-labs').empty().val(rss.labs);
    modal.find('#group-default-rss-vms').empty().val(rss.vms);
    modal.find('#group-default-rss-vcpus').empty().val(rss.vcpus);
    modal.find('#group-default-rss-ram').empty().val(rss.ram);
    modal.find('#group-default-rss-storage').empty().val(rss.storage);
}

function mysite_group_default_rss(element) {
    var modal = $('#modal-mysite-group-default-rss');
    var siteId = modal.find('#site-id-default-rss').html().trim();

    var default_rss = {};

    var labs = parseInt($('#group-default-rss-labs').val());
    var vms = parseInt($('#group-default-rss-vms').val());
    var vcpus = parseInt($('#group-default-rss-vcpus').val());
    var ram = parseInt($('#group-default-rss-ram').val());
    var storage = parseInt($('#group-default-rss-storage').val());

    if  (isNaN(labs) || isNaN(vms) || isNaN(vcpus) || isNaN(ram) || isNaN(storage)) {
        Swal.fire('Please enter integer in the resource fields!', '', 'warning');
        return;
    }

    default_rss['labs'] = labs;
    default_rss['vms'] = vms;
    default_rss['vcpus'] = vcpus;
    default_rss['ram'] = ram;
    default_rss['storage'] = storage;

    var site = {group_default_resource: JSON.stringify(default_rss)};

    $.post("/mysites/setting", {
            "id": siteId,
            "site": site
        }, function (result) {
            modal.modal('hide');

            if (result.status === 'Success') {
                Swal.fire(result.message, '', 'success');
                $('#dataTableBuilder').DataTable().draw(false);
            }
            else {
                Swal.fire('Site Setting Failed!', result.message, 'error');
            }
        },
        'json'
    );
}

function modal_mysite_group_resource(element) {
    var data = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();

    var modal = $('#modal-mysite-group-rss');
    modal.modal('show');

    modal.find('#group-id-rss').empty().html(data.id);
    modal.find('#group-name-rss').empty().html(data.name);
    modal.find('#group-rss-name').empty().val(data.name);
    modal.find('#group-rss-description').empty().val(data.description);
    modal.find('#group-rss-status').empty().val(data.status);
    modal.find('#group-rss-admins').empty().html(data.admins);
    modal.find('#group-rss-requested').empty().val(data.resource_requested);

    var rss = (data.resource_allocated !== null) ?
        JSON.parse(data.resource_allocated.replace(/&quot;/g, '"')) :
        JSON.parse('{ "labs": 0, "vms": 0 , "vcpus": 0, "ram": 0, "storage": 0}');

    modal.find('#group-rss-labs').empty().val(rss.labs);
    modal.find('#group-rss-vms').empty().val(rss.vms);
    modal.find('#group-rss-vcpus').empty().val(rss.vcpus);
    modal.find('#group-rss-ram').empty().val(rss.ram);
    modal.find('#group-rss-storage').empty().val(rss.storage);
    if (data.expiration === null || data.expiration === "") {
        modal.find('#group-rss-expiration').empty().val("");
    } else {
        modal.find('#group-rss-expiration').empty().datepicker("setDate", new Date(data.expiration));
    }
}

function mysite_group_resource(element) {
    var modal = $('#modal-mysite-group-rss');
    var groupId = modal.find('#group-id-rss').html().trim();

    var expiration = $('#group-rss-expiration').val();
    var labs = parseInt($('#group-rss-labs').val());
    var vms = parseInt($('#group-rss-vms').val());
    var vcpus = parseInt($('#group-rss-vcpus').val());
    var ram = parseInt($('#group-rss-ram').val());
    var storage = parseInt($('#group-rss-storage').val());

    if  (isNaN(labs) || isNaN(vms) || isNaN(vcpus) || isNaN(ram) || isNaN(storage)) {
        Swal.fire('Please enter integer in the resource fields!', '', 'warning');
        return;
    }
    if (expiration.match(/^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/) === null) {
        Swal.fire('The date format is incorrect!', '', 'error');
        return;
    }

    var allocate_rss = {};
    allocate_rss['labs'] = labs;
    allocate_rss['vms'] = vms;
    allocate_rss['vcpus'] = vcpus;
    allocate_rss['ram'] = ram;
    allocate_rss['storage'] = storage;

    var group = {resource_allocated: JSON.stringify(allocate_rss), expiration: expiration};

    $.post("/mysites/resource-allocation", {
            "id": groupId,
            "group": group
        }, function (result) {
            modal.modal('hide');

            if (result.status === 'Success') {
                Swal.fire(result.message, '', 'success');
                $('#dataTableBuilder').DataTable().draw(false);
            }
            else {
                Swal.fire('Allocate Resource to Group Failed!', result.message, 'error');
            }
        },
        'json'
    );
}

function modal_mysite_user_edit(element) {
    var row = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();
    var site_id =  $('#site_selector').val();
    var site_name = $('#site_selector option:selected').text();

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
            $.getJSON('/mysites/available-roles', function(roles){
                $.each(roles, function (index, role) {
                    if (role.name === 'site_admin') {
                        if (row.roles.indexOf(role.name) >= 0) {
                            $('<input type="checkbox" name="roles[]" value="' + role.id + '" disabled checked>' +
                                role.name + '<br />').appendTo(dom.querySelector('#avail_roles'));
                        }
                    } else {
                        $('<input type="checkbox" name="roles[]" value="' + role.id + '" ' +
                            ((row.roles.indexOf(role.name) >= 0) ? 'checked' : '') + '>' +
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
            $.post('/mysites/change-roles', {
                'site_id': site_id,
                'user_id': row.id,
                'roles': checked_roles
            }, function (result) {
                if (result.status === 'Success') {
                    Swal.fire('Role Change Done.', row.name + '\'s' + result.message + 'in ' + site_name, 'success');
                    $('#dataTableBuilder').DataTable().draw();
                } else {
                    Swal.fire('Role Change Failed!', result.message + 'in ' + site_name, 'error');
                }
            });
        }
    });
}

function mysite_user_remove(element) {

}

function modal_mysite_add_users(element) {
    if ($('#site_selector').val() === '0') {
        Swal.fire('Please select a site first!', '', 'warning');
        return;
    }

    var siteId = $('#site_selector').val();
    var siteName = $('#site_selector option:selected').text();

    var modal = $('#modal-site-add-members');
    modal.find('#site-name-add-member').empty().html(siteName);
    modal.find('#site-id-add-member').empty().html(siteId);

    var table = $('#site_add_members_all_users').DataTable({
        pageLength: 10,
        // lengthMenu: [ [10, 15, 20, 25, 50, -1], [10, 15, 20, 25, 50, "All"] ],
        destroy: true,
        paging: true,
        processing: true,
        serverSide: true,
        dom: 'lf<"members-toolbar">rtip',
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
            {data: "institute", "defaultContent": ""}, //name: "users.institute"},
            {data: "org_id", "defaultContent": ""}, //name: "users.org_id"},
            // { data: "role", name: "roles.name" }
        ]
    });

    $('div.members-toolbar').html('<span style="margin-left: 20px;"><strong>Role for New Users:</strong> ' +
        '<select id="select_role_for_new_members"><option value="-1">...Select Role...</option></select></span>'
    );

    $.getJSON('/mysites/available-roles', function(roles) {
        $.each(roles, function(index, role) {
            $('<option value="' + role.id + '">' + role.name + '</option>').appendTo($('#select_role_for_new_members'));
        });
    });

    var excludes = $('#dataTableBuilder').DataTable().column(1).data().toArray().join('|');
    table.column(2).search('^(?!.*(' + excludes + ')).*$', true, false).draw();
    modal.modal('show');
}

function mysite_add_users(element) {
    var modal = $('#modal-site-add-members');
    var site_id = modal.find('#site-id-add-member').text();
    var site_name = modal.find('#site-name-add-member').text();
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
        $.post('/mysites/add-users', {
            site_id: site_id,
            members: final_m,
            role_id: role_id
        }, function(result) {
            if (result.status === 'Success') {
                Swal.fire('Members added!', result.message + ' to ' + site_name, 'success');
                modal.modal('hide');
                $('#dataTableBuilder').DataTable().draw();
            }
        });
    } else if (active === '#batch-enroll') {
        var batch_emails = $.trim($('#mysite_batch_enroll_emails').val());
        if (batch_emails.length > 0 && !sys_admin_validate_emails(batch_emails)) {
            Swal.fire('The format of email addresses are incorrect!', '', 'error');
        } else if (batch_emails.length <= 0) {
            Swal.fire('Please enter email addresses!', '', 'warning');
        } else if (batch_emails.length > 0) {
            $.post('/mysites/batch-enroll', {
                site_id: site_id,
                batch_emails: batch_emails
            }, function(data) {
                if (data.status === 'Success') {
                    modal.modal('hide');
                    Swal.fire('Batch Enroll Done!', data.message + ' to ' + site_name, 'success');
                    $('#dataTableBuilder').DataTable().draw();
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

function checkbox_check_all(element) {
    var table = $(element).closest('table').DataTable();
    var rows = table.rows({ 'search': 'applied' }).nodes();
    //var rows = table.fnGetNodes();
    $('input[type="checkbox"]', rows).prop('checked', element[0].checked);
}

function mysite_upload_file(element, action) {
    var tArea = $('#mysite_batch_enroll_emails');

    if (action === 0) {
        tArea.val('');
    } else if (action === 1) {
        var csv = $('#mysite_upload_file');
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
        if (mysite_validate_emails(tArea.val())) {
            Swal.fire('Email addresses are correct.', '', 'success');
        } else {
            Swal.fire('The format of email addresses are incorrect!', '', 'error');
        }
    }
}

function mysite_validate_emails(emailstr) {
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