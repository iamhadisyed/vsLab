function hide_icon_login() {
    var bGroupIcon = false;
    var bAdminIcon = false;
    console.info('ready-role all:'+$('#user_all_role'));
    var roles = $('#user_all_role').html().split(":");
    for (role in roles) {
        console.info('ready-role:'+role);
        if (roles[role]=='global_admin_docker_id'){
            bAdminIcon = true;
        }
        if (roles[role]=='global_group_docker_id'){
            bGroupIcon = true;
        }
    }
    //if (bAdminIcon==false) {
    //    $('#global_admin_docker_id').css('display', 'none');
    //}
    //if (bGroupIcon==false) {
    //    $('#global_group_docker_id').css('display', 'none');
    //}
}

function private_public_help(obj) {
    alert('Group Name and Description are required. Private group will not show up in group related search result.\nPublic group is searchable in group related search.');
}

function res_desc_help(obj) {
    alert('Please input : \n1. Class schedule (e.g., fall 2015, spring 2016). Starting and ending time.\n2. The number students\n3. Lab types (individual labs or group labs) and how many lab running environments?\n4. How many VMs for each user/lab team?\n5. Desired  configurations of the requested VMs configuration (Number virtual CPU, memory, and storage)\n6. Other requirements');
}

function from_profile_display_tab() {

    var groups = $("#from_profile_groupprofile");
    groups.empty();
    groups.append("<tr><th>Group</th><th>Role</th><th>Action</th></tr>");
    run_waitMe($("#from_profile_groupprofile"), 'ios');
    $.getJSON("useradmin/getProfile", function (jsondata) {
        $("#from_profile_groupprofile").waitMe('hide');
        var bNormalUser = 0;
        $.each(jsondata, function (index, item) {
            if (index == 'group_role') {
                $.each(item, function (index3, item3) {
                    if (item3.role=='GlobalSuperUser' && bNormalUser < 1) {
                        bNormalUser = 1;
                    } else if (item3.role=='GlobalAdmin' && bNormalUser < 2) {
                        bNormalUser = 2;
                    } else {
                        groups.append("<tr><td>" + item3.group + "</td><td>" + item3.role + "</td><td><button type=\"button\" class=\"gm_duplicate_leave_group\" >Leave</button></td></tr>");
                    }
                });
            }
        });
    }).done(function () {
    }).fail(function (xhr, testStatus, errorThrown) {
        alert(xhr.responseText + testStatus + errorThrown);
    }).always(function () {
    });

    $.getJSON("group/getOwnGroupList_byRole", function (jsondata) {
        $("#from_profile_groupprofile").waitMe('hide');
        var list = $("#from_profile_groupprofile");
        $.each(jsondata, function (index, item) {
            if (index == 'groups') {
                $.each(item, function (index3, item3) {
                    list.append("<tr><td>" + item3.group_name + "</td><td>" + 'Group Owner' + "</td><td><button type=\"button\" class=\"gm_duplicate_delete_group\" >Delete</button></td></tr>");
                });
            }
        });
    }).done(function () {
    });
}

function do_windows_group_display(winId, win_main) {
    var tabs = {
        tabId: [/*'group_display_from_profile',*/ 'apply_for_a_class', 'group_owner' , 'lab_enroll'/*, 'apply_superuser'*/ ],
        tabName: [/*'Group Summary',*/ 'New Group', 'Group Owner Functions' , 'Group Member Functions'/*, 'Class Application'*/]
    };
    create_tabs(winId, win_main, tabs, null);

    var html = '<h2>Group and Role</h2>' +
        '<table id="from_profile_groupprofile" class="data"></table>';
    $(html).appendTo($('#group_display_from_profile'));
    from_profile_display_tab();

    var form_html = '<form method="post" id="form_create_class" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Create a New Group</h2> &nbsp; &nbsp;' +
        //'<pre>       </pre>' +
        '<u><font color="blue"><a id="private_public_help" onclick="javascript:private_public_help(this);" title="Group Name and Description are required. Private group will not show up in group related search result.\nPublic group is searchable in group related search.">Help</a></font></u>' +
        '<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="vm_name"><span style="color: red">*</span>Group Name: </style></label><input required name="create_class_name" ></li>' +
        '<li><label ><span style="color: red">*</span>Group Description: </label><textarea required rows="4" cols="50" id="create_class_desc" /></li>' +
        '<li><label for="vm_name"><span style="color: red">*</span>Public/Private: </label>' +
        '<select id="select_class_public_private" >' +
//        '<option value="2"> </option>' +
        '<option value="0">Public</option>' +
        '<option value="1">Private</option>' +
            //'<option value="group3">group3</option>' +
        '</select>&nbsp;&nbsp;' +
        //'<u><font color="blue"><a id="private_public_help" onclick="javascript:private_public_help(this);" title="Private group will not show up in group related search result.\nPublic group is searchable in group related search.">Help</a></font></u>' +
        '</li>' +
        '<li style="display:none"><label >Resource description: </label>' +
        '<textarea rows="4" cols="50" id="create_classresource_desc" />&nbsp;&nbsp;' +
        '<u><font color="blue"><a id="res_desc_help" onclick="javascript:res_desc_help(this);" title="Please input : \n1. Class schedule (e.g., fall 2015, spring 2016). Starting and ending time.\n2. The number students\n3. Lab types (individual labs or group labs) and how many lab running environments?\n4. How many VMs for each user/lab team?\n5. Desired  configurations of the requested VMs configuration (Number virtual CPU, memory, and storage)\n6. Other requirements">Help</a></font></u>' +
        '</li>' +
        '<li>' +
        '<label>Expiration:</label>' +
        '<input id="activetimedatetime" name="group_life_time">' +
        '</li>' +
        '</ul>' +
        '<li><button type="button" class="submit" id="submit_create_class">Create</button></li>' +
        '</form>';
    $(form_html).appendTo($('#apply_for_a_class'));
    $('#activetimedatetime').intimidatetime();

    // tab 2
    var p_table = $(document.createElement('p')).appendTo($('#group_owner'));
    $('<br>').appendTo(p_table);
    //$('<h3 >Your own groups</h3>').appendTo(p_table);
    var table = $(document.createElement('table')).appendTo(p_table);
    table.addClass('data').attr("id", "go_table").append('<thead><tr><th>Name</th><th>Description</th><th>Status</th><th>Expiration</th><th>Action</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);
    //display_group_owner_table();

    // tab 3
    // added by huijun: move group member functions from windowgroup.js here
    var group_member = '' +
            //'<fieldset><legend>Enroll group</legend>' +
            '<fieldset>' +
            '<input name="gm_enroll_group_name" placeholder="group name">' +
            '<button type="button" id="gm_submit_enroll_group">Quick Enroll</button>' +
            '<br/><br/>' +
            '<button type="button" class="submit" id="gm_submit_enroll_group_search">Search & Enroll Groups</button>' +
                //'<br/><br/>' +
                //'<button type="button" class="submit" id="gm_submit_group_based_enroll">Group-based Enroll</button>' +
            '</fieldset>' +
            '<br/>' +
        //'<fieldset><legend>Enrolled Groups</legend>' +
        '<table class="data" id="gm_table_summary"></table>' //+
        //'</fieldset>'//+
        //'<br/>' +
        //    '<p>-------------------------------------------------</p>' +
        //'<fieldset><legend>Pending Enrollment Requests to others</legend>' +
        //'<table class="data" id="gm_table_pending_sent"></table>' +
        //'</fieldset>'+
        //'<br/>' +
        //    '<p>-------------------------------------------------</p>' +
        //'<fieldset><legend>Pending Enrollment Requests from others</legend>' +
        //'<table class="data" id="gm_table_pending_invite"></table>' +
        //'</fieldset>'
        ;
    $(group_member).appendTo($('#lab_enroll'));
    display_group_member_table();

    // tab 4
    //var form_htm2l = '<form method="post" id="form_apply_superuserrole" class="contact_form" >' +
    //    '<ul>' +
    //    '<li><h2>Apply for Class</h2></li>' +
    //    '<p>I would like to upgrade to "super user" role so as to access "lab management" and "lab design" features</p>' +
    //    '<li><button  id="submit_apply_superuserrole_class">Apply</button></li>' +
    //    '</form>';
    //$(form_htm2l).appendTo($('#apply_superuser'));
    //// update button if already sent request
    //$.getJSON("cloud/getsentPendingEnroll2", function (jsondata) {
    //    $.each(jsondata, function (index, item) {
    //        //alert(item.userid);
    //        if (index == 'pending') {
    //            $.each(item, function (index3, item3) {
    //                if (item3.group_name == 'forIconGlobalSuperUser') {
    //                    document.getElementById('submit_apply_superuserrole_class').innerHTML = "Application Pending";
    //                    document.getElementById('submit_apply_superuserrole_class').disabled = true;
    //                }
    //            });
    //        }
    //    })
    //});

    //$('#private_public_help').onclick = Function("alert('Private group will not show up in search result');");
    //$('#res_desc_help').onclick = Function("alert('1. Class schedule (e.g., fall 2015, spring 2016). Starting and ending time.\n2. The number students\n3. Lab types (individual labs or group labs) and how many lab running environments?\n4. How many VMs for each user/lab team?\n5. Desired  configurations of the requested VMs configuration (Number virtual CPU, memory, and storage)\n6. Other requirements');");

    //var p_button = $(document.createElement('p')).appendTo($('#group_owner'));
    //$('<button type="button" class="submit" id="go_create_group">Create New Group</button>').appendTo(p_button);
    //$('<button type="button" class="submit" id="gm_submit_group_based_enroll">Group-based Enroll</button>').appendTo(p_button);
    //var p_table = $(document.createElement('p')).appendTo($('#group_owner'));
    //$('<br>').appendTo(p_table);
    //$('<h3 >Your own groups</h3>').appendTo(p_table);
    //var table = $(document.createElement('table')).appendTo(p_table);
    //table.addClass('data').attr("id", "go_table").append('<thead><tr><th>Name</th><th>Description</th><th>Status</th><th>Action</th></tr></thead>');
    //var tbody = $(document.createElement('tbody')).appendTo(table);

    //$(winId).find('div.window_bottom')
    //    .text('Group setting');
    //

    //$(openlabs).appendTo(div_tree);

    //$(winId).find('div.window_bottom')
    //    .text('Apply for a user group or a class.');
    //$(win_main).waitMe('hide');
}

function submit_apply_superuserrole_class(element) {
    $.post("/group/enrollGroup", {
            "group_name": "forIconGlobalSuperUser"
        },
        function (data) {
        },
        'json'
    );
    document.getElementById('submit_apply_superuserrole_class').innerHTML = "Application Pending";
    document.getElementById('submit_apply_superuserrole_class').disabled = true;
}

function do_windows_group_display2(winId, win_main) {
    var tabs = {
        tabId: [/*'apply_for_a_class', 'group_member',*/ 'lab_management_2' ],
        tabName: [/*'New Class/Group', 'User Management',*/ 'Lab Management']
    };
    create_tabs(winId, win_main, tabs, null);

    var form_html = '<form method="post" id="form_create_class" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Apply for a User Group or a Class</h2>' +
        '<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="vm_name"><span style="color: red">*</span>Class/group name: </style></label><input required name="create_class_name" ></li>' +
        '<li><label ><span style="color: red">*</span>Class/group description: </label><textarea required rows="4" cols="50" id="create_class_desc" /></li>' +
        '<li><label for="vm_name"><span style="color: red">*</span>Public/Private: </label>' +
        '<select id="select_class_public_private" >' +
        '<option value="2"> </option>' +
        '<option value="1">Private</option>' +
        '<option value="0">Public</option>' +
            //'<option value="group3">group3</option>' +
        '</select>&nbsp;&nbsp;' +
        '<u><font color="blue"><a id="private_public_help" onclick="javascript:private_public_help(this);" title="Private group will not show up in group related search result.\nPublic group is searchable in group related search.">Help</a></font></u>' +
        '</li>' +
        '<li><label >Resource description: </label>' +
        '<textarea rows="4" cols="50" id="create_classresource_desc" />&nbsp;&nbsp;' +
        '<u><font color="blue"><a id="res_desc_help" onclick="javascript:res_desc_help(this);" title="Please input : \n1. Class schedule (e.g., fall 2015, spring 2016). Starting and ending time.\n2. The number students\n3. Lab types (individual labs or group labs) and how many lab running environments?\n4. How many VMs for each user/lab team?\n5. Desired  configurations of the requested VMs configuration (Number virtual CPU, memory, and storage)\n6. Other requirements">Help</a></font></u>' +
        '</li>' +
        '</ul>' +
        '<li><button type="button" class="submit" id="submit_create_class">Apply</button></li>' +
        '</form>';
    //$(form_html).appendTo($('#apply_for_a_class'));
    //$('#private_public_help').onclick = Function("alert('Private group will not show up in search result');");
    //$('#res_desc_help').onclick = Function("alert('1. Class schedule (e.g., fall 2015, spring 2016). Starting and ending time.\n2. The number students\n3. Lab types (individual labs or group labs) and how many lab running environments?\n4. How many VMs for each user/lab team?\n5. Desired  configurations of the requested VMs configuration (Number virtual CPU, memory, and storage)\n6. Other requirements');");

    //var p_button = $(document.createElement('p')).appendTo($('#lab_management_2'));
    //$('<button type="button" class="submit" id="go_create_group">Create New Group</button>').appendTo(p_button);
    //$('<button type="button" class="submit" id="gm_submit_group_based_enroll">Group-based Enroll</button>').appendTo(p_button);
    var p_table = $(document.createElement('p')).appendTo($('#lab_management_2'));
    $('<br>').appendTo(p_table);
    $('<h3 >Your own groups</h3>').appendTo(p_table);
    var table = $(document.createElement('table')).appendTo(p_table);
    table.addClass('data').attr("id", "go2_table").append('<thead><tr><th>Name</th><th>Description</th><th>Status</th><th>Action</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    display_group_owner_table2();

    //$(winId).find('div.window_bottom')
    //    .text('Group setting');
    //


    //$(openlabs).appendTo(div_tree);

    //$(winId).find('div.window_bottom')
    //    .text('Apply for a user group or a class.');
    //$(win_main).waitMe('hide');

    //display_group_owner_table();

}

function do_windows_group_display_admin(winId, win_main) {
    var tabs = {
        tabId: ['apply_for_a_class', 'class_approval', 'group_member', 'group_owner', ],
        tabName: ['Apply for a user class', 'Class approval', 'Enrollment', 'Lab Management']
    };
    create_tabs(winId, win_main, tabs, null);

    var form_html = '<form method="post" id="form_create_class" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Create Class</h2>' +
        '<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="vm_name">Class name:</label><input name="create_class_name" required></li>' +
        '<li><label >Class description:</label><textarea rows="4" cols="50" id="create_class_desc" /></li>' +
        '<li><label for="vm_name">Public/Private:</label>' +
        '<select id="select_class_public_private">' +
        '<option value="1">Private</option>' +
        '<option value="0">Public</option>' +
            //'<option value="group3">group3</option>' +
        '</select></li>' +
        '</ul>' +
        '<li><button type="button" class="submit" id="submit_create_class">Apply</button></li>' +
        '</form>';
    $(form_html).appendTo($('#apply_for_a_class'));

    var forma_html = '<form method="post" id="form_approve_class"  >' +
        '<table class="data" id="gm_table_class_approval">' +
        '<tr><th>Class</th><th>Owner</th><th>Action</th></tr>' +
        '</table>' +
//        '<button type="button" class="submit" id="submit_approve_class">Approve</button>' +
        '</form>';
    $(forma_html).appendTo($('#class_approval'));

    var group_member = '<fieldset><legend>Enroll group</legend>' +
        '<input name="gm_enroll_group_name" placeholder="group name">' +
        '<button type="button" id="gm_submit_enroll_group">Quick Enroll</button>' +
        '<br/><br/>' +
        '<button type="button" class="submit" id="gm_submit_enroll_group_search">Search & Enroll Groups</button>' +
        '<br/><br/>' +
        '<button type="button" class="submit" id="gm_submit_group_based_enroll">Group-based Enroll</button>' +
        '</fieldset>' +
        '<br/>' +
        '<fieldset><legend>Pending Enrollment Requests from others</legend>' +
        '<table class="data" id="gm_table_pending_invite"></table>' +
        '</fieldset>' +
        '<br/>' +
        '<fieldset><legend>Pending Enrollment Requests to others</legend>' +
        '<table class="data" id="gm_table_pending_sent"></table>' +
        '</fieldset>'+
        '<br/>' +
        '<fieldset><legend>Enrolled Labs</legend>' +
        '<table class="data" id="gm_table_summary"></table>' +
        '</fieldset>' ;
    $(group_member).appendTo($('#group_member'));
    display_group_member_table();

    var p_button = $(document.createElement('p')).appendTo($('#group_owner'));
    $('<button type="button" class="submit" id="go_create_group">Create New Group</button>').appendTo(p_button);
    var p_table = $(document.createElement('p')).appendTo($('#group_owner'));
    $('<br>').appendTo(p_table);
    $('<h3 >Your own groups</h3>').appendTo(p_table);
    var table = $(document.createElement('table')).appendTo(p_table);
    table.addClass('data').attr("id", "go_table").append('<thead><tr><th>Name</th><th>Description</th><th>Status</th><th>Action</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    $(winId).find('div.window_bottom')
        .text('Group setting');

}

function display_pending_class_request() {
    var tbody = $("#gm_table_class_approval").find('tbody');
    tbody.empty();
    tbody.append('<tr><th>Class</th><th>Desc</th><th>Owner</th><th>ID</th><th>Action</th></tr>');
    //tbody.append("<tr><th>Name</th><th>Description</th><th>Status</th><th>Action</th></tr>");

    $.getJSON("group/getPendingClass", function (jsondata) {
        //alert(JSON.stringify(jsondata));
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'groups') {
                $.each(item, function (index3, item3) {
                    //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                    //tbody.append("<tr><td>" + item3.group_name + "</td><td></td><td></td><td><button type=\"button\" class=\"show_member\" >Members</button>, <button type=\"button\" class=\"go_pending_enroll\" >Waiting List</button>, <button type=\"button\" class=\"go_add_member\" >Invite Members</button>, <button type=\"button\" class=\"delete_group\" >Delete Group</button>, <button type=\"button\" class=\"sub_group\" name='Group project relation' value='group_project_" + item3.group_name + "'>Subgroup</button></td>");
                    tbody.append("<tr><td>" + item3.groupname + '</td><td>'+ item3.group_desc +'</td><td>'+ item3.useremail +'</td><td>'+ item3.grpid +'</td><td><button type=\"button\" class=\"gm_approve_class\" >Approve</button>,<button type=\"button\" class=\"gm_reject_class\" >Reject</button></td></tr>');
                    // , <button type=\"button\" class=\"edit_group\" >Edit Group</button>
                });
            }
        });
    });
}

function btn_delete_from_group(element){
    var group_name=element.closest('tr').children().eq(4).html();
    var user_name=element.closest('tr').children().eq(0).html();
    var role_name=element.closest('tr').children().eq(3).html();

    var message =  'Are you sure you want to remove user ' + user_name + ' ?<br />';
    create_ConfirmDialog('Remove a Member', message,
        function() {
            element.closest('tr').remove();
            $.post("/group/leaveGroup", {
                "group_name": group_name,
                "role_name": role_name,
                "userid": user_name
            },
                function (jsondata) {
                    if (jsondata.status == "Success") {
                        //element.closest('tr').remove();
                        }
                    else {
                        alert(jsondata.message);
                    }
                },
                'json'
            );
        },function() {
            // Cancel function
        });
}

function btn_reject_class_comments(element) {
    //var p4 = element.parent();
    //alert(p4.prev().prev().prev().html());
    //alert(p4.prev().prev().html());
    $.post("/group/AdminRejectClass", {
            "group_id": $('#rejgroupid').val(),
            "comment": $('#edit_reject_class_comments').val()
        },
        function (data) {
            $('#btn_reject_class').remove();
            display_pending_class_request();
        },
        'json'
    );
    //p4.text('processing');
}

function btn_reject_class(element) {
    var p4 = element.parent();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'btn_reject_class').attr('title', 'Edit comments');

    var form_html = '<p>Input the comments to the request applicant:</p>' +
        '<textarea id="edit_reject_class_comments">' +  '</textarea>' +
        '<br/>' +
        '<input type="hidden" id="rejgroupid" value="'+p4.prev().html()+'">' +
        '<button id="btn_reject_class_comments">Submit</button>';
    $(form_html).appendTo(dlg_form);

    $('#btn_reject_class').dialog({
        modal: true,
        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function btn_approve_class_comments(element) {
    //var p4 = element.parent();
    //alert(p4.prev().prev().prev().html());
    //alert(p4.prev().prev().html());
    $.post("/group/AdminApproveClass", {
            "group_id": $('#appgroupid').val(),
            "comment": $('#edit_approve_class_comments').val()
        },
        function (data) {
            $('#btn_approve_class').remove();
            display_pending_class_request();
        },
        'json'
    );
    //p4.text('processing');
}

function btn_approve_class(element) {
    var p4 = element.parent();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'btn_approve_class').attr('title', 'Edit comments');

    var form_html = '<p>Input the comments to the request applicant:</p>' +
        '<textarea id="edit_approve_class_comments">' +  '</textarea>' +
        '<br/>' +
        '<input type="hidden" id="appgroupid" value="'+p4.prev().html()+'">' +
        '<button id="btn_approve_class_comments">Submit</button>';
    $(form_html).appendTo(dlg_form);

    $('#btn_approve_class').dialog({
        modal: true,
        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });

}

function windows_group_display(winId, win_main) {
    var admin = false;
    var res = $('#user_all_role')[0].innerHTML.split(':');
    for (var i= 0; i<res.length; i++) {
        if ( 'class_approval' === res[i] ) {
            admin = true;
        }
    }
//    $.getJSON("cloud/getGlobalAdmin", function (jsondata) {
//            if (jsondata.global_admin == 1) {
    //if (admin) {
//                setTimeout(function() {
                    //do_windows_group_display_admin(winId, win_main);
//                }, 1500);
            //} else {
//                setTimeout(function() {
                    do_windows_group_display(winId, win_main);
//                }, 1500);
           // }
//    });
}

function windows_group_display2(winId, win_main) {
    var admin = false;
    var res = $('#user_all_role')[0].innerHTML.split(':');
    for (var i= 0; i<res.length; i++) {
        if ( 'class_approval' === res[i] ) {
            admin = true;
        }
    }
//    $.getJSON("cloud/getGlobalAdmin", function (jsondata) {
//            if (jsondata.global_admin == 1) {
    //if (admin) {
//                setTimeout(function() {
    //do_windows_group_display_admin(winId, win_main);
//                }, 1500);
    //} else {
//                setTimeout(function() {
    do_windows_group_display2(winId, win_main);
//                }, 1500);
    // }
//    });
}

function update_subgroup_name_button_old(element) {
    //alert($("input[name=update_subgroup_groupname]").val() + ':' + $("input[name=update_subgroup_useremail]").val() + ':' + $("textarea[id=edit_subgroup_name_text]").val())
    $.post("subgroup/updateSubGroup", {
            "group_name": $("input[name=update_subgroup_groupname]").val(),
            "user_email": $("input[name=update_subgroup_useremail]").val(),
            "subgroup_name": $("input[id=edit_subgroup_name_text]").val()
        },
        function (jsondata) {
            element.closest('.dialog').dialog('close');

            display_group_member_table();
        },
        'json'
    ).failed(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}

function update_subgroup_name_button(element) {
    //alert($("input[name=update_subgroup_groupname]").val() + ':' + $("input[name=update_subgroup_useremail]").val() + ':' + $("textarea[id=edit_subgroup_name_text]").val())
    $.post("subgroup/updateSubGroup", {
            "group_name": element.closest('div.window').attr('id').substring('window_group_project_'.length),
            "user_email": element.parent().prev().prev().text(),//document.getElementsByClassName('menu_trigger')[0].text,
            "subgroup_name": element.parent().prev()[0].firstChild.value
        },
        function (jsondata) {
        },
        'json'
    ).failed(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });

    element.closest('.dialog').dialog('close');
}

function btn_edit_subgroup2(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_edit_subgroup2').attr('title', 'Edit subgroup');

    var form_html = '<p>update the subgroup name:</p>' +
        '<input id="edit_subgroup_name_text">' + '' + '</input>' +
        '<input type="hidden" name="update_subgroup_useremail" value="' + document.getElementsByClassName('menu_trigger')[0].text + '">' +
        '<input type="hidden" name="update_subgroup_groupname" value="' + element.parent().prev().prev().prev().text() + '">' +
        '<br/>' +
        '<button id="update_subgroup_name_button">Update</button>';
    $(form_html).appendTo(dlg_form);

    $('#dlg_edit_subgroup2').dialog({
        modal: true,
        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function btn_edit_subgroup(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_edit_subgroup').attr('title', 'Edit subgroup');

    var form_html = '<p>update the subgroup name:</p>' +
        '<textarea id="edit_subgroup_name_text">' + element.parent().prev().text() + '</textarea>' +
        '<input type="hidden" name="update_subgroup_useremail" value="' + element.parent().prev().prev().text() + '">' +
        '<input type="hidden" name="update_subgroup_groupname" value="' + element.closest('div.window').attr('id').substring('window_group_project_'.length) + '">' +
        '<br/>' +
        '<button id="update_subgroup_name_button">Update</button>';
    $(form_html).appendTo(dlg_form);

    $('#dlg_edit_subgroup').dialog({
        modal: true,
        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function gm_submit_enroll_group() {
    var groupname = $("input[name=gm_enroll_group_name]").val();
    if (groupname === "") {    //} || groupname.indexOf(' ')>=0) {
        swal('Oops...', 'You need to enter the name!', 'warning');
    }
    else {
        $.post("/group/enrollGroup", {
                "group_name": groupname
            },
            function (data) {
                if (data.enroll == false) {
                    alert("Quick Enroll failed. Please check the group name.");
                } else {
                    alert("Enroll request has been sent.");
                }
                //            alert(JSON.stringify(data));
                //if (data != null) {
                //    alert('Not Found');
                //}
            },
            'json'
        ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText + testStatus + errorThrown);
        });
    }
}

function go_create_group() {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_create_group').attr('title', 'Create new group');

    var form_html = '<form method="post" id="form_create_group" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Create Group</h2>' +
        '<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="vm_name">Group name:</label><input name="create_group_name" required></li>' +
        '<li><label >Group description:</label><textarea rows="4" cols="50" id="create_group_desc" /></li>' +
        '<li><label for="vm_name">Public/Private:</label>' +
        '<select id="select_public_private">' +
        '<option value="1">Private</option>' +
        '<option value="0">Public</option>' +
            //'<option value="group3">group3</option>' +
        '</select></li>' +
        '</ul>' +
        '<li><button type="button" class="submit" id="submit_create_group">Apply</button></li>' +
        '</form>';
    $(form_html).appendTo(dlg_form);
    $('#dlg_create_group').dialog({
        modal: true,

        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function btn_cancel_sent(element) {
    var p4 = element.parent();
    //alert(p4.prev().prev().prev().html());
    //alert(p4.prev().prev().html());
    $.post("/group/CancelSent", {
            "group_name": p4.prev().prev().prev().html()//,
            //"role_name": p4.prev().html()
        },
        function (data) {
            display_group_member_table();
        },
        'json'
    );
    p4.text('canceling');


}

function btn_leave_group(element) {
    var p4 = element.parent();
    //alert(p4.prev().prev().prev().html());
    //alert(p4.prev().prev().html());
    $.post("/group/leaveGroup", {
            "group_name": p4.prev().prev().prev().html(),
            "role_name": p4.prev().html()
        },
        function (data) {
        },
        'json'
    );
    p4.text('leaving');

    display_group_member_table();
}
function btn_duplicate_leave_group(element) {
    run_waitMe($("#from_profile_groupprofile"), 'ios');
    var p4 = element.parent();
    //alert(p4.prev().prev().prev().html());
    //alert(p4.prev().prev().html());
    $.post("/group/leaveGroup", {
            "group_name": p4.prev().prev().html(),
            "role_name": p4.prev().html()
        },
        function (data) {
            $("#from_profile_groupprofile").waitMe('hide');
            element.closest("tr").remove();
        },
        'json'
    );
    //p4.text('leaving');

    //display_group_member_table();
    //from_profile_display_tab();
}

function btn_duplicate_delete_group(element) {
    var p4 = element.parent();
    //alert(p4.prev().prev().prev().html());
    //alert(p4.prev().prev().html());
    //$.post("/group/leaveGroup", {
    //        "group_name": p4.prev().prev().html(),
    //        "role_name": p4.prev().html()
    //    },
    //    function (data) {
    //    },
    //    'json'
    //);
    run_waitMe($("#from_profile_groupprofile"), 'ios');

    $.post("cloud/delete_group1", {
            "groupname": p4.prev().prev().html()
        },
        function (jsondata) {
            $("#from_profile_groupprofile").waitMe('hide');
            element.closest("tr").remove();
            //from_profile_display_tab();
        },
        'json'
    );
    //p4.text('deleting');

    //display_group_member_table();

}
function display_group_member_table() {
    var win_main = $('#lab_enroll');
    run_waitMe(win_main, 'ios');

    //$("#gm_table_pending_invite").empty();
    //$("#gm_table_pending_sent").empty();
    $("#gm_table_summary").empty();

    $.getJSON("group/getMemberAll", function (jsondata) {
        var groups = $("#gm_table_summary");
        groups.empty();
        //groups.addClass("tablesorter");
        groups.append("<tr><th>Type</th><th>Group</th><th>Owner</th><th>Role</th><th>Action</th></tr>");

        //var table = $("#gm_table_pending_invite");
        //table.empty();
        //table.append("<tr><th>Group</th><th>Owner</th><th>Role</th><th>Action</th></tr>");
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'pending1') {
                $.each(item, function (index3, item3) {
                    //table.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td>' + item3.role_desc + '</td><td><button class="btn-pending-invite" >Join</button></td></tr>');
                    groups.append('<tr><td>Invitation</td><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td>' + item3.role_desc + '</td><td><button class="btn-pending-invite" >Join</button></td></tr>');
                });
            }
        });

        // pending 2
        //var table = $("#gm_table_pending_sent");
        //table.empty();
        //table.append("<tr><th>Group</th><th>Action</th></tr>");
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'pending') {
                $.each(item, function (index3, item3) {
                    if (item3.group_name != 'forIconGlobalSuperUser') {
                        //table.append('<tr><td>' + item3.group_name + '</td><td><button type=\"button\" class=\"gm_cancel_sent\" >Cancel</button></td></tr>');
                        groups.append('<tr><td>Application</td><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td></td><td><button type=\"button\" class=\"gm_cancel_sent\" >Cancel</button></td></tr>');

                    }
                });
            }
        });

        // the last
        //var groups = $("#gm_table_summary");
        //var roles = $("#roleprofile");
        //groups.empty();
        //groups.append("<tr><th>Group</th><th>Role</th><th>Subgroup</th><th>Action</th></tr>");
        //groups.append("<tr><th>Group</th><th>Role</th><th>Action</th></tr>");
        //roles.empty();
        //alert(JSON.stringify(jsondata));
        //alert(jsondata);
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'user_profile') {

            } else if (index == 'group_role') {
                $.each(item, function (index3, item3) {
                    //if (item3.group!='forClassApprovalGlobalAdmin' && item3.group!='forIconGlobalSuperUser') {
                    //groups.append("<tr><td>" + item3.group + "</td><td>" + item3.role + "</td><td>" + item3.subgroup + "</td><td><button type=\"button\" class=\"gm_leave_group\" >Leave</button><button type=\"button\" class=\"btn_edit_subgroup2\" >Edit subgroup</button></td></tr>");
                    //groups.append("<tr><td>" + item3.group + "</td><td>" + item3.role + "</td><td>" + item3.subgroup + "</td><td><button type=\"button\" class=\"gm_leave_group\" >Leave</button></td></tr>");
                    groups.append("<tr><td>In Group</td><td>" + item3.group + "</td><td>" + item3.user_email + "</td><td>" + item3.role + "</td><td><button type=\"button\" class=\"gm_leave_group\" >Leave</button></td></tr>");
                    //}
                });
            }
        });
        $(win_main).waitMe('hide');
        sorttable.makeSortable(document.getElementById("gm_table_summary"));
        //groups.tablesorter({
        //    widthFixed: true,
        //    widgets: [ 'uitheme', 'zebra'],
        //    //dateFormat: "mmddyyyy",
        //    //sortInitialOrder: "asc",
        //    headers: {
        //        0: { sorter: false }
        //    },
        //    textExtraction : {
        //        0: function(node) { return $(node).text(); },
        //        1: function(node) { return $(node).text(); }
        //    },
        //    sortForce: null,
        //    sortList: [],
        //    sortAppend: null,
        //    sortLocaleCompare: false,
        //    sortReset: false,
        //    sortRestart: false,
        //    sortMultiSortKey: "shiftKey",
        //    onRenderHeader: function() {
        //        $(this).find('span').addClass('headerSpan');
        //    },
        //    selectorHeaders: 'thead th',
        //    cssAsc        : "headerSortUp",
        //    cssChildRow   : "expand-child",
        //    cssDesc       : "headerSortDown",
        //    cssHeader     : "header",
        //    tableClass    : 'tablesorter',
        //    widgetColumns : { css: ["primary", "secondary", "tertiary"] },
        //    widgetUitheme : { css: [
        //        "ui-icon-arrowthick-2-n-s", // Unsorted icon
        //        "ui-icon-arrowthick-1-s",   // Sort up (down arrow)
        //        "ui-icon-arrowthick-1-n"    // Sort down (up arrow)
        //    ]
        //    },
        //    widgetZebra: { css: ["ui-widget-content", "ui-state-default"] },
        //    cancelSelection : true,
        //    debug: false
        //});
    });
}

function display_group_member_table_old() {
    $.getJSON("cloud/getPendingInvite", function (jsondata) {
        var table = $("#gm_table_pending_invite");
        table.empty();
        table.append("<tr><th>Group</th><th>Owner</th><th>Role</th><th>Action</th></tr>");
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'pending') {
                $.each(item, function (index3, item3) {
                    table.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td>' + item3.role_desc + '</td><td><button class="btn-pending-invite" >Join</button></td></tr>');
                });
            }
        })
    });
    $.getJSON("group/getsentPendingEnroll2", function (jsondata) {
        var table = $("#gm_table_pending_sent");
        table.empty();
        table.append("<tr><th>Group</th><th>Action</th></tr>");
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index === 'pending') {
                $.each(item, function (index3, item3) {
                    if (item3.group_name !== 'forIconGlobalSuperUser') {
                        table.append('<tr><td>' + item3.group_name + '</td><td><button type=\"button\" class=\"gm_cancel_sent\" >Cancel</button></td></tr>');

                    }
                });
            }
        })
    });
    $.getJSON("cloud/getProfile", function (jsondata) {
        var groups = $("#gm_table_summary");
        groups.empty();
        groups.append("<tr><th>Group</th><th>Role</th><th>Action</th></tr>");

        $.each(jsondata, function (index, item) {
            if (index === 'user_profile') {

            } else if (index === 'group_role') {
                $.each(item, function (index3, item3) {
                    //if (item3.group!='forClassApprovalGlobalAdmin' && item3.group!='forIconGlobalSuperUser') {
                        //groups.append("<tr><td>" + item3.group + "</td><td>" + item3.role + "</td><td>" + item3.subgroup + "</td><td><button type=\"button\" class=\"gm_leave_group\" >Leave</button><button type=\"button\" class=\"btn_edit_subgroup2\" >Edit subgroup</button></td></tr>");
                        //groups.append("<tr><td>" + item3.group + "</td><td>" + item3.role + "</td><td>" + item3.subgroup + "</td><td><button type=\"button\" class=\"gm_leave_group\" >Leave</button></td></tr>");
                        groups.append("<tr><td>" + item3.group + "</td><td>" + item3.role + "</td><td><button type=\"button\" class=\"gm_leave_group\" >Leave</button></td></tr>");
                    //}
                });
            }
        });
        //$("#project_list").trigger("change");
    }).done(function () {
        console.log("second success");
    }).fail(function (xhr, testStatus, errorThrown) {
        alert(xhr.responseText + testStatus + errorThrown);
    }).always(function () {
        console.log("complete");
    });
}

function gm_submit_group_based_enroll() {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_group_based_batch_enroll').attr('title', 'Group-based Enroll');
    //dlg_form.css('max-height', '400px').css('overflow', 'auto');

    var form_html = '<div>Please input members\' email and pick group, then click "Enroll"' +
        //'<button id="search_group_based_enroll_btn">Enroll</button>' +
        '<br/>Input the group members\' emails separated by ";"<br/>' +
        '<textarea id="group_based_input_emails_text" rows="5" cols="50" style="max-width:100%;" placeholder="Input the group members\' emails separated by \';\'">' +
        '</textarea>' +
        '<br/>' +
//        '<label>Group Name:</label>' +
//        '<input name="search_groupbasedenroll_txt">' +
//        '<button id="search_groupbasedenroll_btn">Filter</button>' +
        '<br/>' +
        '<table class="data" id="search_groupbasedenroll_table">' +
        '<tr><th>Group</th><th>Description</th><th>Action</th></tr>' +
            //'<tr><td>Jell</td><td><input type="button" value="Enroll"></td></tr>' +
            //'<tr><td>Eve</td><td><input type="button" value="Enroll"></td></tr>' +
        '</table>' +
        '<div id="search_groupbasedenroll_table_count"></div>' +
        '</div>';
    $(form_html).appendTo(dlg_form);
    $('#dlg_group_based_batch_enroll').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });

    search_groupbasedenroll_btn();
}

function search_groupbasedenroll_btn() {
    var win_main = $('#search_groupbasedenroll_table');
    run_waitMe(win_main, 'ios');
    $.getJSON("group/getOwnGroupList_byRole", function (jsondata) {
        var list = $("#search_groupbasedenroll_table");
        list.empty();
        list.append('<tr><th>Group</th><th style="display:none;">Group ID</th><th>Description</th><th>Action</th></tr>');
        //alert(JSON.stringify(jsondata));
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'groups') {
                $.each(item, function (index3, item3) {
                    list.append('<tr><td>' + item3.group_name + '</td><td style="display:none;">' + item3.group_id + '</td><td>' + item3.group_desc + '</td><td><button class="btn-groupbased-enroll" >Enroll</button></td></tr>');
                    //list.append("<option value='" + item3.group_id + "'>" + item3.group_name + "</option>");
                });
            }
        });
        $(win_main).waitMe('hide');
    });
}

function gm_submit_enroll_group_search() {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_enroll_group').attr('title', 'Advanced enroll group search');
    //dlg_form.css('max-height', '400px').css('overflow', 'auto');

    var form_html = '<label>Group Name:</label>' +
        '<input name="search_group_txt">' +
        '<button id="search_group_btn">Filter</button>' +
        '<hr/>' +
        '<table class="data" id="search_group_table">' +
        '<tr><th>Group</th><th>Owner</th><th>Description</th><th>Action</th></tr>' +
            //'<tr><td>Jell</td><td><input type="button" value="Enroll"></td></tr>' +
            //'<tr><td>Eve</td><td><input type="button" value="Enroll"></td></tr>' +
        '</table>' +
        '<div id="search_group_table_count"></div>';
    $(form_html).appendTo(dlg_form);
    $('#dlg_enroll_group').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 600,
        close: function (event, ui) {
            $(this).remove();
            display_group_member_table();
        }
    });

    search_group_btn();
}


function display_group_enroll(jsondata) {
    var table = $("#search_group_table");
    table.empty();
    table.append("<tr><th>Group</th><th>Owner</th><th>Description</th><th>Action</th></tr>");
    var count = 0;
    $.each(jsondata, function (index, item) {
        //alert(item.userid);
        if (index == 'groups') {
            $.each(item, function (index3, item3) {
                if (item3.group_name == 'base admin' || item3.group_name == 'editor' || item3.group_name == 'superadmin') {

                } else {
                    // this role list must be adjusted with database value, since not all roles in the database show here
                    table.append('<tr><td>' + item3.group_name + '</td><td>'+item3.owner_email+'</td><td>'+(item3.group_description==null?'':item3.group_description)+'</td><td><button class="btn-enroll" >Enroll</button></td></tr>');
                    count = count + 1;
                }
            });
        }
    });
    $('#search_group_table_count').html(count + ' found');
}

function display_group_owner_table2() {
    var tbody = $("#go2_table").find('tbody');
    tbody.empty();
    //tbody.append("<tr><th>Name</th><th>Description</th><th>Status</th><th>Action</th></tr>");

    var win_main = $('#lab_management_2');
    run_waitMe(win_main, 'ios');
    $.getJSON("group/getOwnGroupList_byRole", function (jsondata) {
        //alert(JSON.stringify(jsondata));
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'groups') {
                $.each(item, function (index3, item3) {
                    //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                    //tbody.append("<tr><td>" + item3.group_name + "</td><td></td><td></td><td><button type=\"button\" class=\"show_member\" >Members</button>, <button type=\"button\" class=\"go_pending_enroll\" >Waiting List</button>, <button type=\"button\" class=\"go_add_member\" >Invite Members</button>, <button type=\"button\" class=\"delete_group\" >Delete Group</button>, <button type=\"button\" class=\"sub_group\" name='Group project relation' value='group_project_" + item3.group_name + "'>Subgroup</button></td>");
                    tbody.append("<tr><td>" + item3.group_name + '</td><td>'+ item3.group_desc +'</td><td>'+ (item3.group_private==1?'private':'public') +'</td><td class="dropdown"><a class="btn btn-default group2-actionButton" data-toggle="dropdown" href="#">Edit <i class="fa fa-sort-down"></i></a></td></tr>');
                    // , <button type=\"button\" class=\"edit_group\" >Edit Group</button>
                });
            }
        });
        $(win_main).waitMe('hide');
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'group2-contextmenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');

    //$('<li><a tabindex="-1" href="#" class="show_member">Member Management</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="go_pending_enroll">Waiting List</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="go_add_member">Invite Members (search existing user)</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="go_add2_member">Invite Members (using email)</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="delete_group1">Delete Group</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="sub_group">Team Management</a></li>').appendTo(contextMenu);
}
function display_group_owner_table() {
    var tbody = $("#go_table").find('tbody');
    tbody.empty();
    //tbody.append("<tr><th>Name</th><th>Description</th><th>Status</th><th>Action</th></tr>");

    var win_main = $('#group_owner');
    run_waitMe(win_main, 'ios');
    $.getJSON("group/getOwnGroupList_byRole", function (jsondata) {
        //alert(JSON.stringify(jsondata));
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'groups') {
                $.each(item, function (index3, item3) {
                    //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                    //tbody.append("<tr><td>" + item3.group_name + "</td><td></td><td></td><td><button type=\"button\" class=\"show_member\" >Members</button>, <button type=\"button\" class=\"go_pending_enroll\" >Waiting List</button>, <button type=\"button\" class=\"go_add_member\" >Invite Members</button>, <button type=\"button\" class=\"delete_group\" >Delete Group</button>, <button type=\"button\" class=\"sub_group\" name='Group project relation' value='group_project_" + item3.group_name + "'>Subgroup</button></td>");
                    tbody.append("<tr><td>" + item3.group_name + '</td><td>'+ item3.group_desc +'</td><td>'+ (item3.group_private==1?'private':'public') +'</td><td>'+ item3.group_expire +'</td><td class="dropdown"><a class="btn btn-default group-actionButton" data-toggle="dropdown" href="#">Edit <i class="fa fa-sort-down"></i></a></td></tr>');
                    // , <button type=\"button\" class=\"edit_group\" >Edit Group</button>
                });
            }
        });
        $(win_main).waitMe('hide');
        sorttable.makeSortable(document.getElementById("go_table"));
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'group-contextmenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');

    $('<li><a tabindex="-1" href="#" class="update_group_meta">Group Info</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="show_member">Member Management</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="go_pending_enroll">Check Pending Application</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="go_add_member">Invite Members (search existing user)</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="go_add2_member">Invite Members (using email)</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="delete_group1">Delete Group</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="sub_group">Team Management</a></li>').appendTo(contextMenu);
}

function delete_group1(element) {
    var message = 'Are you sure you want to delete?';
    create_ConfirmDialog('Deletion confirm', message, function (){
    // click ok
        run_waitMe($('#go_table'), 'ios');
        $.post("group/delete_group1", {
                "groupname": element.closest('tr').children().eq(0).html()
            },
            function (jsondata) {
                $($('#go_table')).waitMe('hide');
                if (jsondata.delete=="still subgroup there") {
                    $.jAlert({
                        'title':'Error',
                        'content':'There are subgroups associated with this group. Please delete subgroups before deleting group!',
                        'theme':'red',
                        'btns': {
                            'text':'close',
                            'theme':'green'
                        }
                    });
                } else {
//            display_group_owner_table();
                    element.closest("tr").remove();

                    var tbody = $("#go_table").find('tbody');
                    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
                    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'group-contextmenu')
                        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');

                    $('<li><a tabindex="-1" href="#" class="update_group_meta">Group Info</a></li>').appendTo(contextMenu);
                    $('<li><a tabindex="-1" href="#" class="show_member">Member Management</a></li>').appendTo(contextMenu);
                    $('<li><a tabindex="-1" href="#" class="go_pending_enroll">Waiting List</a></li>').appendTo(contextMenu);
                    $('<li><a tabindex="-1" href="#" class="go_add_member">Invite Members (search existing user)</a></li>').appendTo(contextMenu);
                    $('<li><a tabindex="-1" href="#" class="go_add2_member">Invite Members (using email)</a></li>').appendTo(contextMenu);
                    $('<li><a tabindex="-1" href="#" class="delete_group1">Delete Group</a></li>').appendTo(contextMenu);
                    //$('<li><a tabindex="-1" href="#" class="sub_group">Team Management</a></li>').appendTo(contextMenu);
                }
            },
            'json'
        );

    },function(){
        // click cancel, nothing
    });
}
function update_group_meta(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_update_group_meta').attr('title', 'Group Info');
    var groupid= element.closest('tr').children().eq(0).html();
    var group_desc = element.closest('tr').children().eq(1).html();
    var group_type = element.closest('tr').children().eq(2).html();
    var group_expire = element.closest('tr').children().eq(3).html();

    //var form_html = '<div><table class="data" id="list_group_user_table">' +
    //    '<tr><th>User</th><th>Institute</th><th>Org ID (member id)</th><th>Role</th><th>Action</th></tr></table></div>';
    //$(form_html).appendTo(dlg_form);

    var form_html = '<form method="post" id="form_create_class2" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Edit Group Info</h2>' +
        '<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="vm_name"><span style="color: red">*</span>Group Name: </style></label><input required name="create_class_name2" disabled value="' + groupid + '"></li>' +
        '<li><label ><span style="color: red">*</span>Group Description: </label><textarea required rows="4" cols="50" id="create_class_desc2">' + group_desc + '</textarea></li>' +
        '<li><label for="vm_name"><span style="color: red">*</span>Public/Private: </label>' +
        '<select id="select_class_public_private2" >' +
//        '<option value="2"> </option>' +
        '<option value="0">Public</option>' +
        '<option value="1">Private</option>' +
            //'<option value="group3">group3</option>' +
        '</select>&nbsp;&nbsp;' +
        '<u><font color="blue"><a id="private_public_help2" onclick="javascript:private_public_help(this);" title="Private group will not show up in group related search result.\nPublic group is searchable in group related search.">Help</a></font></u>' +
        '</li>' +
        '<li style="display:none"><label >Resource description: </label>' +
        '<textarea rows="4" cols="50" id="create_classresource_desc2" />&nbsp;&nbsp;' +
        '<u><font color="blue"><a id="res_desc_help2" onclick="javascript:res_desc_help(this);" title="Please input : \n1. Class schedule (e.g., fall 2015, spring 2016). Starting and ending time.\n2. The number students\n3. Lab types (individual labs or group labs) and how many lab running environments?\n4. How many VMs for each user/lab team?\n5. Desired  configurations of the requested VMs configuration (Number virtual CPU, memory, and storage)\n6. Other requirements">Help</a></font></u>' +
        '</li>' +
        '<li>' +
        '<label>Active Time Duration</label>' +
        '<input id="activetimedatetime2" name="group_life_time" value="' + group_expire + '">' +
        '</li>' +
        '</ul>' +
        '<li><button type="button" class="submit" id="submit_create_class2">Apply</button></li>' +
        '</form>';
    $(form_html).appendTo(dlg_form);
    $('#activetimedatetime2').intimidatetime();

    run_waitMe($('#dlg_update_group_meta'), 'ios');

    $.post("/group/getGroupMembers", {
            //"group_name": element.parent().prev().prev().prev().html()
            "group_name": element.closest('tr').children().eq(0).html()
        },
        function (jsondata) {
            $('#dlg_update_group_meta').waitMe('hide');

            //alert(data.msg);
            //var list = $("#list_group_user_table");
            //list.empty();
            //list.append('<tr><th>User</th><th>Institute</th><th>Org ID (member id)</th><th>Role</th><th>Action</th></tr>');
            ////alert(JSON.stringify(jsondata));
            //$.each(jsondata, function (index, item) {
            //    //alert(item.userid);
            //    if (index == 'members') {
            //        $.each(item, function (index3, item3) {
            //            if(item3.role =='GroupOwner'||item3.role== 'Trial') {
            //                list.append("<tr><td>" + item3.email + "</td><td>" + item3.institute + "</td><td>" + item3.org_id + "</td><td >" + item3.role + "</td><td hidden>" + groupid + "</td><td></td></tr>");
            //
            //            }else{
            //                //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
            //                list.append("<tr><td>" + item3.email + "</td><td>" + item3.institute + "</td><td>" + item3.org_id + "</td><td >" + item3.role + "</td><td hidden>" + groupid + "</td><td><button class='btn-delete-from-group'>Remove</button></td></tr>");
            //            }});
            //    }
            //});
        },
        'json'
    );

    $('#dlg_update_group_meta').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function show_member(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_showgroupmember').attr('title', 'Group member management');
    var groupid= element.closest('tr').children().eq(0).html();

    var form_html = '<div><table class="data" id="list_group_user_table">' +
        '<tr><th>User</th><th>Institute</th><th>Org ID (member id)</th><th>Role</th><th>Action</th></tr></table></div>';
    $(form_html).appendTo(dlg_form);

    run_waitMe($('#dlg_showgroupmember'), 'ios');

    $.post("/group/getGroupMembers", {
            //"group_name": element.parent().prev().prev().prev().html()
            "group_name": element.closest('tr').children().eq(0).html()
        },
        function (jsondata) {
            $('#dlg_showgroupmember').waitMe('hide');

            //alert(data.msg);
            var list = $("#list_group_user_table");
            list.empty();
            list.append('<tr><th>User</th><th>Institute</th><th>Org ID (member id)</th><th>Role</th><th>Action</th></tr>');
            //alert(JSON.stringify(jsondata));
            $.each(jsondata, function (index, item) {
                //alert(item.userid);
                if (index == 'members') {
                    $.each(item, function (index3, item3) {
                        if(item3.role =='GroupOwner'||item3.role== 'Trial') {
                            list.append("<tr><td>" + item3.email + "</td><td>" + item3.institute + "</td><td>" + item3.org_id + "</td><td >" + item3.role + "</td><td hidden>" + groupid + "</td><td></td></tr>");

                        }else{
                            //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                            list.append("<tr><td>" + item3.email + "</td><td>" + item3.institute + "</td><td>" + item3.org_id + "</td><td >" + item3.role + "</td><td hidden>" + groupid + "</td><td><button class='btn-delete-from-group'>Remove</button></td></tr>");
                        }});
                }
            });
        },
        'json'
    );

    $('#dlg_showgroupmember').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function ownclass_assign(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_ownclass_assign').attr('title', 'Assign Templates');
    var classid= element.closest('tr').children().eq(1).html();
    var nameid=  element.closest('tr').children().eq(3).html();

    var form_html = '<div hidden id="classid">'+classid+'</div><div hidden id="nameid">'+nameid+'</div> <div>Please choose a template' +
        '<select id="own_temp_list">' +

        '</select>' +

        '<button class="btn_assign_temp_to_lab">Assign to Lab</button></div>';
    $(form_html).appendTo(dlg_form);

    $.getJSON("/group/getTemplate", function (jsondata) {
        var list= $('#own_temp_list');
        list.empty();
        $.each(jsondata, function (index, item) {
            list.append("<option value='" + item.temp_id + "'>" + item.temp_name + "</option>")
        });
    });

        //$.getJSON("cloud/getOwnGroupList", function (jsondata) {
    //    var list = $("#own_group_list");
    //    list.empty();
    //    //alert(JSON.stringify(jsondata));
    //    $.each(jsondata, function (index, item) {
    //        //alert(item.userid);
    //        if (index == 'groups') {
    //            $.each(item, function (index3, item3) {
    //                if(item3.group_name == groupid)     {
    //                    list.append("<option selected value='" + item3.group_id + "'>" + item3.group_name + "</option>");
    //                }else {
    //                    //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
    //                    list.append("<option value='" + item3.group_id + "'>" + item3.group_name + "</option>");
    //                }
    //            });
    //        }
    //    });
    //});

    $('#dlg_ownclass_assign').dialog({
        modal: true,
        height: 100,
        overflow: "auto",
        width: 300,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}


function dlg_invite_group(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_group_invite').attr('title', 'Invite group members');
    //dlg_form.parent().css('max-height', '400px').css('overflow', 'auto');
    var groupid= element.closest('tr').children().eq(0).html();

    var form_html = '<p>Pick the new group' +
        '<select id="own_group_list">' +
            //'<option value="group1">group1</option>' +
            //'<option value="group2">group2</option>' +
            //'<option value="group3">group3</option>' +
        '</select>' +
        '<hr/>' +
        '<label>User Name:</label>' +
        '<input name="search_user_txt">' +
        '<button id="search_user_btn">Search</button>' +
        '<hr/>' +
        '<table class="data" id="search_user_table">' +
        '<tr><th>User</th><th>Institute</th><th>Org ID</th><th>Role</th><th>Action</th></tr>' +
            //'<tr><td>Yuli</td><td>asu</td><td>123</td><td><input type="button" value="Invite"></td></tr>' +
            //'<tr><td>James</td><td>asu</td><td>456</td><td><input type="button" value="Invite"></td></tr>' +
        '</table>' +
        '<div id="showusernumber"></div>';
    $(form_html).appendTo(dlg_form);
    run_waitMe($('#dlg_group_invite'), 'ios');
    $.getJSON("group/getOwnGroupList_byRole", function (jsondata) {
        var list = $("#own_group_list");
        list.empty();
        //alert(JSON.stringify(jsondata));
        $.each(jsondata, function (index, item) {
            $('#dlg_group_invite').waitMe('hide');
            //alert(item.userid);
            if (index == 'groups') {
                $.each(item, function (index3, item3) {
                    if(item3.group_name == groupid)     {
                        list.append("<option selected value='" + item3.group_id + "'>" + item3.group_name + "</option>");
                    }else {
                        //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                        list.append("<option value='" + item3.group_id + "'>" + item3.group_name + "</option>");
                    }
                });
            }
        });
    });

    $('#dlg_group_invite').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function dlg_pending_enroll(element) {
    var groupname = element.closest('tr').children().eq(0).html();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_pending_enroll').attr('title', 'Pending group enrollment requests');

    var form_html = '<table class="data" id="pending_enroll_table">' +
        '<tr><th>Group</th><th>Applicant</th><th>Role</th><th>Action</th></tr>' +
            //'<tr><td>Jell</td><td>Yuli</td><td><input type="button" value="Approve"></td></tr>' +
            //'<tr><td>Eve</td><td>James</td><td><input type="button" value="Approve"></td></tr>' +
        '</table>';
    $(form_html).appendTo(dlg_form);

    run_waitMe($('#dlg_pending_enroll'), 'ios');
    $.post("group/getPendingEnroll", {
            "groupname" : groupname
        },
        function (jsondata) {
            $('#dlg_pending_enroll').waitMe('hide');
            var table = $("#pending_enroll_table");
            table.empty();
            table.append("<tr><th>Group</th><th>Applicant</th><th>Role</th><th>Action</th></tr>");
            $.each(jsondata, function (index, item) {
                //alert(item.userid);
                if (index == 'pending') {
                    $.each(item, function (index3, item3) {
                        table.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td>' + get_role_list_html() + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                    });
                }
            })
        });

    $('#dlg_pending_enroll').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });

}

function display_group_invite() {
    //alert("send ajax for own group list");
    $.getJSON("group/getOwnGroupList_byRole", function (jsondata) {
        var list = $("#own_group_list");
        list.empty();
        //alert(JSON.stringify(jsondata));
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'groups') {
                $.each(item, function (index3, item3) {
                    //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                    list.append("<option value='" + item3.group_id + "'>" + item3.group_name + "</option>");
                });
            }
        });
    });

    var table = $("#search_user_table");
    table.empty();
    table.append("<tr><th>User</th><th>Institute</th><th>Org ID</th><th>Role</th><th>Action</th></tr>");
}

function display_group_invite_show_table(jsondata) {
    var table = $("#search_user_table");
    table.empty();
    table.append("<tr><th>User</th><th>Institute</th><th>Org ID</th><th>Role</th><th>Action</th></tr>");
    //alert("send ajax for own group list0");
    var count = 0;
    $.each(jsondata, function (index, item) {
        //alert(item.userid);
        if (index == 'users') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td>' + item3.email + '</td><td>' + item3.institute + '</td><td>' + item3.org_id + '</td><td>' + get_role_list_html() + '</td><td><button class="btn-invite" >Invite</button></td></tr>');
                //<tr><td>Yuli</td><td>asu</td><td>123</td><td><input type="button" value="Invite"></td></tr>
                count = count + 1;
            });
        }
    });
    $('#showusernumber').html(count + ' found');
}

function display_pending_enroll() {
    $.getJSON("group/getPendingEnroll", function (jsondata) {
        var table = $("#pending_enroll_table");
        table.empty();
        table.append("<tr><th>Group</th><th>Applicant</th><th>Action</th></tr>");
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'pending') {
                $.each(item, function (index3, item3) {
                    table.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                });
            }
        })
    });
}

function display_pending_invite() {
    $.getJSON("group/getPendingInvite", function (jsondata) {
        var table = $("#pending_invite_table");
        table.empty();
        table.append("<tr><th>Group</th><th>Owner</th><th>Action</th></tr>");
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'pending') {
                $.each(item, function (index3, item3) {
                    table.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-invite" >Join</button></td></tr>');
                });
            }
        })
    });
}


function search_user_btn() {
    run_waitMe($('#dlg_group_invite'), 'ios');
    $.post("/group/searchUser", {
            "search_user_txt": $("input[name=search_user_txt]").val(),
            "select_group_id": $("#own_group_list").val()
        },
        function (data) {
            $('#dlg_group_invite').waitMe('hide');
            //alert(JSON.stringify(data));
            display_group_invite_show_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}

function search_group_btn() {
    //alert('click');
    $.post("/group/searchGroup", {
            "search_group_txt": $("input[name=search_group_txt]").val()
        },
        function (data) {
            //alert(JSON.stringify(data));
            display_group_enroll(data);

        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}

function btn_groupbased_enroll(element) {
    var p4 = element.parent();
    //alert(p4.prev().html());
    //alert(p4.prev().prev().html());
    $.post("/group/groupBasedEnroll", {
            "group_id": p4.prev().prev().html(),
            "user_email": $("#group_based_input_emails_text").val(),
            "role_id": 13
        },
        function (data) {
            alert('enrollemnt request has been sent.');
            //element.closest('.dialog').dialog('close');
            $('#dlg_group_based_batch_enroll').remove();
        },
        'json'
    );
    p4.text('processing');

    //display_group_member_table();
}


function btn_pending_invite(element) {
    var p4 = element.parent();
    //alert(p4.prev().html());
    //alert(p4.prev().prev().html());
    $.post("/group/joinInviteGroup", {
            "group_name": p4.prev().prev().prev().html(),
            "user_email": p4.prev().prev().html()
        },
        function (data) {
        },
        'json'
    );
    p4.text('joined');

    display_group_member_table();
}

function btn_invite(element) {
    var p3 = element.parent();
    var usermail = p3.prev().prev().prev().prev().html();
    var message = "Are you sure you want to invite "+usermail;
    create_ConfirmDialog("", message,
        function() {
            //alert($("#own_group_list").val());
            //alert(p3.prev().prev().prev().html());
            $.post("/group/inviteGroup", {
                    "user_email": usermail,
                    "role_id": p3.prev().children(0).val(),
                    "group_id": $("#own_group_list").val()
                },
                function (data) {
                },
                'json'
            );
            p3.text('pending');
        }, function () {
            // nop
        })
}


function btn_pending_enroll(element) {
    var p2 = element.parent();
    //alert(p2.prev().html());
    //alert(p2.prev().prev().html());
    $.post("/group/approveEnrollGroup", {
            "group_name": p2.prev().prev().prev().html(),
            "user_email": p2.prev().prev().html()
        },
        function (data) {
        },
        'json'
    );
    p2.text('approved');
}

function btn_enroll(element) {
    var p = element.parent();
    //alert(p.prev().html());
    $.post("/group/enrollGroup", {
            "group_name": element.parent().prev().prev().prev().html()
        },
        function (data) {
        },
        'json'
    );
    p.text('pending');
}

function submit_create_group(element) {
    //alert("aaa");
    $.post("/group/createGroup", {
            "create_group_name": $("input[name=create_group_name]").val(),
            "create_group_desc": $("#create_group_desc").val(),
            "select_public_private": $("#select_public_private").val()
        },
        function (data) {
            element.closest('.dialog').dialog('close');
            display_group_owner_table();
        },
        'json'
    ).failed(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}

function submit_create_class(element) {
    var groupname = $("input[name=create_class_name]").val();
    if (groupname === "") {    //} || groupname.indexOf(' ')>=0) {
        swal('Oops...', 'You need to enter the name!', 'warning');
    } else if ($("#create_class_desc").val() === "") {
        swal('Oops...', 'You need to enter the description!', 'warning');
    } else {
        $.post("/group/createClass", {
                "create_group_name": $("input[name=create_class_name]").val(),
                "create_group_desc": $("#create_class_desc").val(),
                "select_public_private": $("#select_class_public_private").val(),
                "resource_desc": $("#create_classresource_desc").val(),
                "group_life_time": $("input[name=group_life_time]").val()
            },
            function (data) {
                if (!data.exist) {
                    swal('The group ' + groupname + ' has been created.').then(function() {
                        $("input[name=create_class_name]").val('');
                        $("#create_class_desc").val('');
                        $("#create_classresource_desc").val('');
                    });

                    /*$.jAlert({
                        'title': 'Info.', 'content': 'Creation done',
                        'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                    });*/
                } else {
                    swal('Oops...', 'The group name has already been used!', 'error');
                }
            },
            'json'
        ).fail(function (xhr, testStatus, errorThrown) {
                swal('Oops...', xhr.responseText, 'error');
            });
    }
}

