/**
 * Created by James on 1/16/2018. To replace original Settings functions
 */

function profile_display(winId, win_main) {
    var tabs = {
        tabId: ['mysettings-profile-view',
            'owncloud_myfiles_merge_profile',
            'profile_application_vpn_owncloudclient',
            'system_parameters',
            'activity_log'
        ],
        tabName: ['My Profile',
            'My Files',
            'Application',
            'System Parameters',
            'Activity Log'
        ]
    };

    create_tabs(winId, win_main, tabs, null);

    var links = $(document.createElement('div')).appendTo($('#system_parameters'));
    links.append(
        "Below is a list of available tutorials: " +
        "<br>" +
        "<div>" +
        //"<a target='_blank' href='https://www.thothlab.org/downloads/ThoTh_Lab_Studio_Tutorial.pdf'>" +
        //"<img src='https://www.thothlab.org/workspace-assets/images/icons/icon_64_pdf.png'>" +
        //"ThoTh Lab Studio Tutorial" +
        //"</a><br><a target='_blank' href='https://www.thothlab.org/downloads/ThoTh_Lab_Operation_Flows.pdf'>" +
        //"<img src='https://www.thothlab.org/workspace-assets/images/icons/icon_64_pdf.png'>" +
        //"ThoTh Lab Operation Flows" +
        //"</a>" +
        "<br><a target='_blank' href='https://www.thothlab.org/downloads/Virtual_Machine_Password_List.txt'>" +
        "<img src='https://www.thothlab.org/workspace-assets/images/icons/icon_64_txt.png'>" +
        "Virtual Machine Password List" +
        "</a>" +
        "</div>"
    );

    $('<button id="edit_profile_dig" onclick="show_update_profile_dlg()">Edit profile</button>&nbsp;&nbsp;' +
        '<button id="edit_profile_password" onclick="show_update_password_dlg()">Change password</button>' +
        '<div><table class="noBorder">' +
        '<tr class="noBorder"><td class="noBorder"><label for="mysettings_profile_email">Email:</label></td>' +
        '<td class="noBorder"><input type="text" id="mysettings_profile_email" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="mysettings_profile_name">Names:</label></td>' +
        '<td class="noBorder"><input type="text" id="mysettings_profile_name" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="mysettings_profile_institute" >Institute:</label></td>' +
        '<td class="noBorder"><input type="text" id="mysettings_profile_institute" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="mysettings_profile_phone" >Phone:</label></td>' +
        '<td class="noBorder"><input type="text" id="mysettings_profile_phone" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="mysettings_profile_address" >Address:</label></td>' +
        '<td class="noBorder"><input type="text" id="mysettings_profile_address" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="mysettings_profile_state" >State:</label></td>' +
        '<td class="noBorder"><input type="text" id="mysettings_profile_state" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="mysettings_profile_country" >Country:</label></td>' +
        '<td class="noBorder"><input type="text" id="mysettings_profile_country" size="50" disabled style="background-color: lightgray" /><br><br></td></tr>' +
        '<tr class="noBorder"><td class="noBorder"><label for="mysettings_profile_enabled">Enabled</label></td>' +
        '<td class="noBorder"><input type="checkbox" id="mysettings_profile_enabled" /></td></tr>' +
        '<tr class="noBorder"><td class="hidden" id="mysettings_profile_id"></td><td class="noBorder"></td></tr>' +
        '</table></div>').appendTo($('#mysettings-profile-vie'));


    var html = '<div id="host_owncloud_profile" />' ;// +
    $(html).appendTo($('#owncloud_myfiles_merge_profile'));
    var iframe_console = $(document.createElement('iframe')).appendTo($('#host_owncloud_profile'));
    iframe_console.attr("id", "profile_iframe_id_owncloud")
        .attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://personal.thothlab.org/index.php/apps/files");
    //display_owncloud_tab();

    var form_htm2l = '<form method="post" id="form_apply_superuserrole" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Apply for Class</h2></li>' +
        '<p>I would like to upgrade to "super user" role so as to access "lab management" and "lab design" features</p>' +
        '<li><button  id="submit_apply_superuserrole_class">Apply</button></li>' +
        '</form>';
    $(form_htm2l).appendTo($('#apply_superuser'));
    // update button if already sent request
    $.getJSON("group/getsentPendingEnroll2", function (jsondata) {
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'pending') {
                $.each(item, function (index3, item3) {
                    if (item3.group_name == 'forIconGlobalSuperUser') {
                        document.getElementById('submit_apply_superuserrole_class').innerHTML = "SuperUser application pending";
                        document.getElementById('submit_apply_superuserrole_class').disabled = true;
                    }
                });
            }
        })
    });


    var html5 = //'<h2>OpenVPN configuration download</h2>' +
                //'<button id="downloadvpnconifg" >Show VPN Config Download Link</button>' +
                //'<br/>' +
        '<button hidden id="mockdownloadbuttonvpn">Download OpenVPN config</button>' +
        '<a target="_blank" download id="downloadvpnlink" />' +
        '<br />' +
        '<p>OpenVPN client (Windows) link: https://openvpn.net/index.php/open-source/downloads.html</p>' +
        '<p>OpenVPN client (Linux) link: https://community.openvpn.net/openvpn/wiki/OpenvpnSoftwareRepos</p>' +
        '<p>VPN client (Mac) link: https://tunnelblick.net/downloads.html</p>' +
        '<br/>' +
        '<p>RDP client (Linux) link: http://www.rdesktop.org/</p>' +
        '<p>RDP client (Mac) link: https://itunes.apple.com/us/app/microsoft-remote-desktop/id715768417?mt=12</p>';// +
//        '<button type="submit">Download OpenVPN config</button>' +
//        '</form>';// +
//        '<input type="button"  id="downloadvpnlink" download value="Download OpenVPN config" />';
    //'<h2>Group and Role</h2>' +
    //'<table id="groupprofile" class="data"></table>';
    //$(html5).appendTo($('#vpn_config'));
    $(html5).appendTo($('#profile_application_vpn_owncloudclient'));
    $("#mockdownloadbuttonvpn").click(function() {
        var dl = $("#downloadvpnlink");
        dl[0].click();
    });

    //var form_vm_html = '<form method="post" class="contact_form" id="form_group_enroll">' +
    //    '<input type="submit" value="Enroll"><br/>' +
    //    '<h2>group type</h2><br/>' +
    //    '<select >' +
    //    '<option value="Course">Course</option>' +
    //    '<option value="Project">Project</option>' +
    //    '</select><br/>' +
    //    '<h2>group name</h2><br/>' +
    //    '<select >' +
    //    '<option value="CSE123">CSE123</option>' +
    //    '<option value="CSE224">CSE224</option>' +
    //    '</select>' +
    //    '</form>';
    //$(form_vm_html).appendTo($('#group_enroll'));

    var form_user_profile = '<form method="post" id="form_update_user_profile" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Update Profile</h2>' +
        '<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="vm_name">Email (user id):</label><input name="email" disabled></li>' +
        '<li><label for="vm_name">Avatar:</label><img id="avatar_img" src="files/snac-group-members.jpg" alt="avatar" width="100" height="100"></li>' +
        '<li><button type="button" class="submit" id="submit_upload_avatar">Upload new image</button></li>' +
        '</ul>' +
        //'<li><label for="vm_name">Alt email (password recovery):</label><input required name="alt_email"></li>' +
        '<li><label for="vm_name">First name:</label><input name="first_name" required></li>' +
        '<li><label for="vm_name">Last name:</label><input name="last_name" required></li>' +
        '<li><label for="vm_name">Phone:</label><input  name="phone"></li>' +
        '<li><label for="vm_name">Address:</label><input  name="address"></li>' +
        '<li><label for="vm_name">City:</label><input  name="city"></li>' +
        '<li><label for="vm_name">State:</label><input  name="state"></li>' +
        '<li><label for="vm_name">Country:</label><input required name="country"></li>' +
        '<li><label for="vm_name">Zip:</label><input  name="zip"></li>' +
        '<li><label for="vm_name">Organization:</label><input required name="institute"></li>' +
        //'<li><label for="vm_name">Org ID (member ID):</label><input name="org_id"></li>' +
        //        '<li><label for="vm_name">Current password:</label><input name="cur_pass" type="password"></li>' +
        //        '<li><label for="vm_name">New password:</label><input name="new_pass" type="password"></li>' +
        //        '<li><label for="vm_name">Password again:</label><input name="new_pass_again" type="password"></li>' +
        '<li><button type="button" class="submit" id="submit_update_user_profile">Update</button></li>' +
        '' +
        //'<li><button type="button" class="submit" id="submit_update_user_password">Save password</button></li>' +
        '</form>';
    $(form_user_profile).appendTo($('#userprofile_update'));


    var form_user_password = '<form method="post" id="form_update_user_password" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Update Password</h2>' +
        '<li><label for="vm_name">Current password:</label><input name="cur_pass" type="password"></li>' +
        '<li><label for="vm_name">New password:</label><input name="new_pass" type="password"></li>' +
        '<li><label for="vm_name">Password again:</label><input name="new_pass_again" type="password"></li>' +
        '<li><button type="button" class="submit" id="submit_update_user_password">Update</button></li>' +
        '</ul>' +
        '</form>';
    $(form_user_password).appendTo($('#passowrd_update'));

    /*var iframe_console = $(document.createElement('iframe')).appendTo($('#passowrd_update'));
    iframe_console//.attr("name", "vm_console_" + "owncloud")
        .attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "/user/change-password");*/
    //var group_enroll = '<label>Group Name:</label>' +
    //    '<input name="search_group_txt">' +
    //    '<button id="search_group_btn">Search</button>' +
    //    '<hr/>' +
    //    '<table class="data" id="search_group_table">' +
    //    '<tr><th>Group</th><th>Action</th></tr>' +
    //        //'<tr><td>Jell</td><td><input type="button" value="Enroll"></td></tr>' +
    //        //'<tr><td>Eve</td><td><input type="button" value="Enroll"></td></tr>' +
    //    '</table>';
    //$(group_enroll).appendTo($('#enroll_group'));

    //var group_invite = '<p>Pick the group' +
    //    '<select id="own_group_list">' +
    //        //'<option value="group1">group1</option>' +
    //        //'<option value="group2">group2</option>' +
    //        //'<option value="group3">group3</option>' +
    //    '</select>' +
    //    '<hr/>' +
    //    '<label>User Name:</label>' +
    //    '<input name="search_user_txt">' +
    //    '<button id="search_user_btn">Search</button>' +
    //    '<hr/>' +
    //    '<table class="data" id="search_user_table">' +
    //    '<tr><th>User</th><th>Organization</th><th>Org ID</th><th>Action</th></tr>' +
    //        //'<tr><td>Yuli</td><td>asu</td><td>123</td><td><input type="button" value="Invite"></td></tr>' +
    //        //'<tr><td>James</td><td>asu</td><td>456</td><td><input type="button" value="Invite"></td></tr>' +
    //    '</table></div>';
    //$(group_invite).appendTo($('#invite_group'));

    //var pending_enroll = '<table class="data" id="pending_enroll_table">' +
    //    '<tr><th>Group</th><th>Applicant</th><th>Action</th></tr>' +
    //        //'<tr><td>Jell</td><td>Yuli</td><td><input type="button" value="Approve"></td></tr>' +
    //        //'<tr><td>Eve</td><td>James</td><td><input type="button" value="Approve"></td></tr>' +
    //    '</table>';
    //$(pending_enroll).appendTo($('#pending_enroll'));

    //var pending_invite = '<table class="data" id="pending_invite_table">' +
    //    '<tr><th>Group</th><th>Owner</th><th>Action</th></tr>' +
    //        //'<tr><td>Jell</td><td>Yuli</td><td><input type="button" value="Join"></td></tr>' +
    //        //'<tr><td>Eve</td><td>James</td><td><input type="button" value="Join"></td></tr>' +
    //    '</table>';
    //$(pending_invite).appendTo($('#pending_invite'));

    //var create_group = '<form method="post" id="form_create_group" class="contact_form" >' +
    //    '<ul>' +
    //    '<li><h2>Create Group</h2>' +
    //    '<span class="required_notification">* Denotes Required Field</span></li>' +
    //    '<li><label for="vm_name">Group name:</label><input name="create_group_name" required></li>' +
    //    '<li><label for="vm_name">Public/Private:</label>' +
    //    '<select id="select_public_private">' +
    //    '<option value="0">Public</option>' +
    //    '<option value="1">Private</option>' +
    //        //'<option value="group3">group3</option>' +
    //    '</select></li>' +
    //    '</ul>' +
    //    '<li><button type="button" class="submit" id="submit_create_group">Create</button></li>' +
    //    '</form>';
    //$(create_group).appendTo($('#create_group'));

    var create_proj = '<form method="post" id="form_create_proj" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Create Project</h2>' +
        '<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="vm_name">Project name:</label><input name="create_proj_name" required></li>' +
        '</ul>' +
        '<li><button type="button" class="submit" id="submit_create_proj">Create</button></li>' +
        '</form>';
    $(create_proj).appendTo($('#create_proj'));

    //var group_member = '<fieldset><legend>Enroll group</legend>' +
    //    '<input name="gm_enroll_group_name" value="group name">' +
    //    '<button type="button" id="gm_submit_enroll_group">Quick Enroll</button>' +
    //    '<br/><br/>' +
    //    '<button type="button" class="submit" id="gm_submit_enroll_group_search">Advanced Search >></button>' +
    //    '</fieldset>' +
    //    '<br/>' +
    //    '<fieldset><legend>Pending group invitation</legend>' +
    //    '<table class="data" id="gm_table_pending_invite"></table>' +
    //    '</fieldset>' +
    //    '<br/>' +
    //    '<fieldset><legend>Current enrolled groups</legend>' +
    //    '<table class="data" id="gm_table_summary"></table>' +
    //    '</fieldset>';
    //$(group_member).appendTo($('#group_member'));


    //var p_button = $(document.createElement('p')).appendTo($('#group_owner'));
    //$('<button type="button" class="submit" id="go_create_group">Create New Group >></button>').appendTo(p_button);
    //var p_table = $(document.createElement('p')).appendTo($('#group_owner'));
    //$('<h3 >Your own groups</h3>').appendTo(p_table);
    //var table = $(document.createElement('table')).appendTo(p_table);
    //table.addClass('data').attr("id", "go_table").append('<thead><tr><th>Name</th><th>Description</th><th>Status</th><th>Action</th></tr></thead>');
    //var tbody = $(document.createElement('tbody')).appendTo(table);

    //var group_owner = '<button type="button" class="submit" id="go_create_group">Create New Group >></button>' +
    //    '<br/>' +
    //    '<br/>' +
    //    '<fieldset><legend>Your own groups</legend>' +
    //    '<table class="data" id="go_table"></table>' +
    //    '</fieldset>';
    //$(group_owner).appendTo($('#group_owner'));

    //var table = $(document.createElement('table')).appendTo($('#activity_log'));
    //table.addClass("data").attr("id", "tbl_profile_log").append('<thead><tr>' +
    //'<th class="shrink">&nbsp;</th>' +
    //'<th>Time</th>' +
    //'<th>Type</th>' +
    //'<th>Action</th>' +
    ////'<th class="hidden">UUID</th>' +
    //'<th>Description</th>' +
    //'<th>Details</th>' +
    //'<th>IP Address</th>' +
    //'<th>User Agent</th>' +
    //'</tr></thead>');
    //var tbody = $(document.createElement('tbody')).appendTo(table);
    //
    //run_waitMe(win_main, 'ios');
    //$.getJSON("/cloud/getActivityLog", function (jsondata) {
    //    $.each(jsondata, function (index, item) {
    //        $('<tr>' +
    //        '<td><img src="' + ICON_computer_sm + '"></img></td>' +
    //        '<td>' + item.timestamp + '</td>' +
    //        '<td>' + item.type + '</td>' +
    //        '<td>' + item.action + '</td>' +
    //        '<td>' + item.description + '</td>' +
    //        '<td>' + item.details + '</td>' +
    //        '<td>' + item.ip_address + '</td>' +
    //        '<td>' + item.agent + '</td>' +
    //        '</tr>').appendTo(tbody);
    //    });
    //
    //    $(winId).find('div.window_bottom')
    //        .text(jsondata.length + ' logs)');
    //    $(win_main).waitMe('hide');
    //}

    //var pager = '<div id="tbl_activity_log_pager" class="pager"><form>' +
    //    '<img class="first" title="First" src="https://www.thothlab.org/workspace-assets/images/icons/pager_first.png />"' +
    //    '<img class="prev" title="Previous" src="https://www.thothlab.org/workspace-assets/images/icons/pager_prev.png />"' +
    //    '<span class="pagedisplay"></span>' +
    //    '<img class="next" title="Next" src="https://www.thothlab.org/workspace-assets/images/icons/pager_next.png />"' +
    //    '<img class="last" title="Last" src="https://www.thothlab.org/workspace-assets/images/icons/pager_last.png />"' +
    //    '<select class="pagesize"><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="all">All Rows</option></select>' +
    //    '</form></div>';
    //$(pager).appendTo($('#tbl_activity_log'));

    var table = $(document.createElement('table')).appendTo($('#activity_log'));
    table.addClass("data").addClass("tablesorter").attr("id", "tbl_activity_log").append('<thead><tr>' +
        '<th class="shrink">&nbsp;</th>' +
        '<th>Time</th>' +
        '<th>Type</th>' +
        '<th>Action</th>' +
        //'<th class="hidden">UUID</th>' +
        '<th>Description</th>' +
        '<th>Details</th>' +
        '<th>IP Address</th>' +
        '<th>User Agent</th>' +
        '</tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    $(winId).find('div.window_bottom')
        .text('Settings');

}