/**
 * Created by root on 1/23/15.
 */

function hearbeat () {
    //$.getJSON("cloud/queryHearbeat", {'query_user':"huijunwu@asu.edu"}, function(data){
    //    console.log("hearbeat query test 1: "+JSON.stringify(data));
    //});
    $.getJSON("cloud/hearbeat");
    //$.getJSON("cloud/queryHearbeat", {'query_user':"huijunwu@asu.edu"}, function(data){
    //    console.log("hearbeat query test 2: "+JSON.stringify(data));
    //});
}

function makeShowFirstLoginDig_do() {
    // start heart beat timer
    //setInterval(hearbeat, 1000*60*1);// milli-second
    //
    $.getJSON("group/getFirstLogin", function (jsondata) {
        $.each(jsondata, function (index, item) {
            if (index == 'user' && item == 1) {
                //$('#global_profile_docker_id').dblclick();
                makeShowFirstLoginDig();

                $("input[name=" + 'email' + "]").attr("value", document.getElementsByClassName('menu_trigger')[0].text);
                $.getJSON("group/getProfile", function (jsondata) {
                    $.each(jsondata, function (index4, item4) {
                        //alert(item.userid);
                        if (index4 == 'user_profile') {
                            $.each(item4, function (index3, item3) {
                                if (index3 == 'avatar') {
                                    //$('#avatar_img').attr('src', "files/"+item3);
                                    //$("#avatar_img").attr("src", "img/avatar/" + item3 + ".jpg");
                                } else {
                                    $("input[name=firsttime_" + index3 + "]").attr("value", item3);
                                }
                            });
                            //$("#project_list").trigger("change");
                        }
                    })
                });
            } else {
                show_help_info();
            }
        });
    });
}

function makeShowFirstLoginDig(){
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').
        attr('id', 'dlg_first_login').
        attr('title', 'First time login information collection');
    //dlg_form.css('max-height', '400px').css('overflow', 'auto');

    $.getJSON("group/checkIfInvited", function (jsondata) {
        if (jsondata.group_based_registration_invitation==1) {
            // add password update
            var form_both_html = '<form method="post" id="form_firsttime_update_both_user_profile" class="contact_form" >' +
                '<ul>' +
                '<li><h2>Update Profile</h2>' +
                '<span class="required_notification">* Denotes Required Field</span></li>' +
                '<li><label >Email (user id):</label><input name="firsttime_both_email" value="'+$('#menu_username').html()+'" disabled></li>' +
                '</ul>' +
                //'<li><label >Alt email (password recovery):</label><input required name="firsttime_both_alt_email"></li>' +
                '<li><label >First name:</label><input name="firsttime_both_first_name" required></li>' +
                '<li><label >Last name:</label><input name="firsttime_both_last_name" required></li>' +
                '<li><label >Phone:</label><input  name="firsttime_both_phone" placeholder="(xxx) xxx xxxx"></li>' +
                '<li><label >Address:</label><input   name="firsttime_both_address" placeholder="please enter your address"></li>' +
                '<li><label >City:</label><input   name="firsttime_both_city" placeholder="please enter your city"></li>' +
                '<li><label >State:</label><input   name="firsttime_both_state" placeholder="eg. AZ"></li>' +
                '<li><label >Country:</label><input  required name="firsttime_both_country" placeholder="eg. US"></li>' +
                '<li><label >Zip:</label><input   name="firsttime_both_zip" placeholder="6 digit zip code"></li>' +
                '<li><label >Organization:</label><input  required name="firsttime_both_institute" placeholder="please enter your organization"></li>' +
//                '<ul>' +
                '<li><label for="vm_name">Current password:</label><input required name="cur_both_pass" type="password"></li>' +
                '<li><label for="vm_name">New password:</label><input required name="new_both_pass" type="password"></li>' +
                '<li><label for="vm_name">Password again:</label><input required name="new_both_pass_again" type="password"></li>' +
//                '</ul>' +
                '<li><button type="button" class="submit" id="firsttime_submit_update_both_pass_and_user_profile">Update</button></li>' +
                '' +
                '</form>';
            $(form_both_html).appendTo(dlg_form);
            $('#dlg_first_login').dialog({
                modal: true,
                height: 500,
                overflow: "auto",
                width: 700,
                close: function (event, ui) {
                    $(this).remove();
                },
                closeOnEscape: false,
                open: function(event, ui) {
                    $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                }
            });
        }else if (jsondata.group_based_registration_invitation==0) {
            // copy old first time login
            var form_html = '<form method="post" id="form_firsttime_update_user_profile" class="contact_form" >' +
                '<ul>' +
                '<li><h2>Update Profile</h2>' +
                '<span class="required_notification">* Denotes Required Field</span></li>' +
                '<li><label >Email (user id):</label><input name="firsttime_email" value="'+$('#menu_username').html()+'" disabled></li>' +
                '</ul>' +
                //'<li><label >Alt email (password recovery):</label><input required name="firsttime_alt_email"></li>' +
                '<li><label >First name:</label><input name="firsttime_first_name" required></li>' +
                '<li><label >Last name:</label><input name="firsttime_last_name" required></li>' +
                '<li><label >Phone:</label><input  name="firsttime_phone" placeholder="(xxx) xxx xxxx"></li>' +
                '<li><label >Address:</label><input   name="firsttime_address" placeholder="please enter your address"></li>' +
                '<li><label >City:</label><input   name="firsttime_city" placeholder="please enter your city"></li>' +
                '<li><label >State:</label><input   name="firsttime_state" placeholder="eg. AZ"></li>' +
                '<li><label >Country:</label><input  required name="firsttime_country" placeholder="eg. US"></li>' +
                '<li><label >Zip:</label><input   name="firsttime_zip" placeholder="6 digit zip code"></li>' +
                '<li><label >Organization:</label><input  required name="firsttime_institute" placeholder="please enter your organization"></li>' +
                '<li><button type="button" class="submit" id="firsttime_submit_update_user_profile">Update</button></li>' +
                '' +
                '</form>';
            $(form_html).appendTo(dlg_form);
            $('#dlg_first_login').dialog({
                modal: true,
                height: 500,
                overflow: "auto",
                width: 700,
                close: function (event, ui) {
                    $(this).remove();
                }//,
                // normal login profile setting is optional
                //closeOnEscape: false,
                //open: function(event, ui) {
                //    $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                //}
            });
        }
    }).done(function () {
    }).fail(function (xhr, testStatus, errorThrown) {
        alert(xhr.responseText + testStatus + errorThrown);
    }).always(function () {
    });
}

function show_help_info() {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').
        attr('id', 'dlg_show_help_dlg').
        attr('title', 'Help page');
    $.getJSON("group/checkIfShowHelp", function (jsondata) {
        if (jsondata.showHelp == 1) {
            var form_html = '<form method="post">' +
                '<p>This is Help page</p>' +
                '<br/><p>My Labs: manage my labs</p>' +
                '<br/><p>Group Management: create, mange your own groups</p>' +
                '<br/><p>Video Conference: one to one video</p>' +
                '<br/><p>Blog: publish your posts</p>' +
                '<br/><p>Open Labs: public labs</p>' +
                '<br/><p>Setting: profile, personal file, system parameters and activity log</p>' +
                '<br/><p>Help Desk: submit help tickets</p>' +
                '<br/>' +
                '<input type="checkbox" id="doNotShowHelpCheckBox" name="notShowAgain" value="notAgain">do not show again<br/>' +
                '<button type="button" class="submit" id="doNotShowHelp">Close</button>' +
                '</form>';
            $(form_html).appendTo(dlg_form);
            $('#dlg_show_help_dlg').dialog({
                modal: true,
                height: 500,
                overflow: "auto",
                width: 700,
                close: function (event, ui) {
                    $(this).remove();
                }
            });
        }
    });
}

function doNotShowHelp() {
    if (document.getElementById("doNotShowHelpCheckBox").checked){
        $.post("/group/doNotshowhelp", {
                "a" : 1
            },
            function (data) {
            },
            'json'
        );
    }
    $('#dlg_show_help_dlg').remove();
}

function downloadvpnconifg(winId, win_main) {
    $.getJSON("cloud/downloadvpnconifg", function (jsondata) {//
        //$("#mockdownloadbuttonvpn").attr("onClick", "window.location.href='https://www.thothlab.org/files/"+jsondata.filenames[0]+".ovpn'");
//        $("#downloadvpnlink").text("https://www.thothlab.org/files/"+jsondata.filenames[0]+".ovpn");
//        $("#downloadvpnlink").attr("action", "https://www.thothlab.org/files/"+jsondata.filenames[0]+".ovpn");
        $("#mockdownloadbuttonvpn").show();
        $("#downloadvpnlink").attr("href", "https://www.thothlab.org/files/"+jsondata.filenames[0]+".ovpn").show();
    }).done(function () {
    }).fail(function (xhr, testStatus, errorThrown) {
        alert(xhr.responseText + testStatus + errorThrown);
    }).always(function () {
    });
}
function display_owncloud_tab(){
    document.getElementById('profile_iframe_id_owncloud').contentWindow.location.reload(true);
}
function show_update_profile_dlg(){
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').
        attr('id', 'dlg_show_update_profile_dlg').
        attr('title', 'Update Profile');
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
    //$(form_user_profile).appendTo($('#userprofile_update'));
    $(form_user_profile).appendTo(dlg_form);
    $('#dlg_show_update_profile_dlg').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            $(this).remove();
        }
    });
    display_user_profile_tab();
}
function show_update_password_dlg(){
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').
        attr('id', 'dlg_show_update_passwword_dlg').
        attr('title', 'Update Password');
    var form_user_password = '<form method="post" id="form_update_user_password" class="contact_form" >' +
        '<ul>' +
        '<li><h2>Update Password</h2>' +
        '<li><label for="vm_name">Current password:</label><input name="cur_pass" type="password"></li>' +
        '<li><label for="vm_name">New password:</label><input name="new_pass" type="password"></li>' +
        '<li><label for="vm_name">Password again:</label><input name="new_pass_again" type="password"></li>' +
        '<li><button type="button" class="submit" id="submit_update_user_password">Update</button></li>' +
        '</ul>' +
        '</form>';
    //$(form_user_password).appendTo($('#passowrd_update'));
    $(form_user_password).appendTo(dlg_form);
    $('#dlg_show_update_passwword_dlg').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}
function profile_display(winId, win_main) {
    var tabs = {
        //tabId: ['profile_view', 'group_enroll', 'group_manage', 'userprofile_update'],
        //tabName: ['Profile Summary', 'Enroll Group', 'Group Owner', 'User Profile']
        //tabId: ['profile_view', 'userprofile_update', 'enroll_group', 'pending_enroll'],
        //tabName: ['Summary', 'User Profile', 'Group:Enroll', 'Pending:Enroll']
        tabId: ['profile_view',
            'owncloud_myfiles_merge_profile',
            'profile_application_vpn_owncloudclient',
            //'userprofile_update',
            //'passowrd_update',
            //'apply_superuser',
            'system_parameters',
            'activity_log'
        ],//, 'vpn_config'
        tabName: ['My Profile',
            'My Files',
            'Application',
            //'User Profile',
            //'Password Update',
            //'Class Application',
            'System Parameters',
            'Activity Log'
        ]//, 'VPN config'
        //tabId: ['profile_view', 'userprofile_update', 'group_member', 'group_owner'],
        //tabName: ['Summary', 'User Profile', 'Group Member', 'Group Owner']
    };

    create_tabs(winId, win_main, tabs, null);
    //alert('create_tabs done');

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

    var html = '<h2>User Profile &nbsp;' +
        '<button id="edit_profile_dig" onclick="show_update_profile_dlg()">Edit profile</button>&nbsp;' +
        '<button id="edit_profile_password" onclick="show_update_password_dlg()">Change password</button>' +
        '</h2>' +
        '<table id="userprofileform" class="data"></table>' +
        '<br/>';// +
        //'<h2>Group and Role</h2>' +
        //'<table id="groupprofile" class="data"></table>';
    $(html).appendTo($('#profile_view'));
    display_tab();

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

function display_activity_log() {

    var tbody = $('#tbl_activity_log').find('tbody').empty();

    run_waitMe($('#tbl_activity_log'), 'ios');
    $.getJSON("/cloud/getActivityLog", function (jsondata) {
        $('#tbl_activity_log').waitMe('hide');
        $.each(jsondata, function (index, item) {
            $('<tr>' +
            '<td><img src="' + ICON_computer_sm + '"></img></td>' +
            '<td>' + item.timestamp + '</td>' +
            '<td>' + item.type + '</td>' +
            '<td>' + item.action + '</td>' +
            '<td>' + item.description + '</td>' +
            '<td>' + item.details + '</td>' +
            '<td>' + item.ip_address + '</td>' +
            '<td>' + item.agent + '</td>' +
            '</tr>').appendTo(tbody);
        });

        //$(winId).find('div.window_bottom')
        //    .text(jsondata.length + ' logs)');
        //$(win_main).waitMe('hide');

        $('#tbl_activity_log').tablesorter({
            widthFixed: true,
            widgets: [ 'uitheme', 'zebra'],
            //dateFormat: "mmddyyyy",
            //sortInitialOrder: "asc",
            headers: {
                0: { sorter: false }
            },
            textExtraction : {
                0: function(node) { return $(node).text(); },
                1: function(node) { return $(node).text(); }
            },
            sortForce: null,
            sortList: [],
            sortAppend: null,
            sortLocaleCompare: false,
            sortReset: false,
            sortRestart: false,
            sortMultiSortKey: "shiftKey",
            onRenderHeader: function() {
                $(this).find('span').addClass('headerSpan');
            },
            selectorHeaders: 'thead th',
            cssAsc        : "headerSortUp",
            cssChildRow   : "expand-child",
            cssDesc       : "headerSortDown",
            cssHeader     : "header",
            tableClass    : 'tablesorter',
            widgetColumns : { css: ["primary", "secondary", "tertiary"] },
            widgetUitheme : { css: [
                "ui-icon-arrowthick-2-n-s", // Unsorted icon
                "ui-icon-arrowthick-1-s",   // Sort up (down arrow)
                "ui-icon-arrowthick-1-n"    // Sort down (up arrow)
                ]
            },
            widgetZebra: { css: ["ui-widget-content", "ui-state-default"] },
            cancelSelection : true,
            debug: false
        });
    });
}

//function submit_upload_avatar_file() {
//    $.ajax({
//        url: "cloud/acceptFile", // Url to which the request is send
//        type: "POST",             // Type of request to be send, called as method
//        data: new FormData($('#uploadimage')), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
//        contentType: false,       // The content type used when sending data to the server.
//        cache: false,             // To unable request pages to be cached
//        processData: false,        // To send DOMDocument or non processed data file it is set to false
//        success: function (data)   // A function to be called if request succeeds
//        {
//            //$('#loading').hide();
//
//            $("#fileuploadmessage").html(data);
//        }
//    });
//}

function submit_upload_avatar2() {
    var uploadfilename = '';
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_uploader').attr('title', 'File Uploader');

    var form_html = '<span class="btn btn-success fileinput-button">' +
        //'<i class="glyphicon glyphicon-plus"></i><span>File</span>' +
        '<span>File</span>' +
            //'<input id="fileuploader" type="file" name="files[]" data-url="/fileUpload" multiple>' +
        '<input id="fileuploader" type="file" name="uploaded_file" data-url="/workspace/fileUpload">' +
        '</span>';
    $(form_html).appendTo(dlg_form);
    $('<div id="progress" class="progress"><div class="progress-bar progress-bar-success"></div></div>').appendTo(dlg_form);
    $('<div id="files" class="files"></div>').appendTo(dlg_form);

    $('#dlg_uploader').dialog({
        modal: true,
        width: 400,
        buttons: [
            {
                id: "dlg_btn_ok",
                text: "OK",
                click: function () {
                    $(this).dialog('close');
                }
            },
            {
                id: "dlg_btn_cancel",
                text: "Cancel",
                click: function () {
                    $(this).dialog('close');
                }
            }
        ],
        close: function (event, ui) {
            $(this).remove();
        }
    });

    $('#dlg_btn_ok').button('disable');

    //$(function () {
    //    'use strict';
    // Change this to the location of your server-side upload handler:
    var uploadButton = $('<button/>')
        .addClass('btn btn-primary')
        .prop('disabled', true)
        .text('Processing...')
        .on('click', function () {
            var $this = $(this),
                data = $this.data();
            $this
                .off('click')
                .text('Abort')
                .on('click', function () {
                    $this.remove();
                    data.abort();
                });
            data.submit().always(function () {
                $this.remove();
            });
        });

    $('#fileuploader').fileupload({
        url: '/workspace/fileUpload',
        method: 'post',
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, // 5 MB
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {
        // huijun: remove all old children
        var fileslisenots = document.getElementById("files");
        while (fileslisenots.firstChild) {
            fileslisenots.removeChild(fileslisenots.firstChild);
        }

        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
            var node = $('<p/>')
                .append($('<span/>').text(file.name));
            if (!index) {
                node
                    .append('<br>')
                    .append(uploadButton.clone(true).data(data));
            }
            if (index==data.files.length-1) // huijun: force the last one
                node.appendTo(data.context);
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('Upload')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        //alert('upload done!');
        //$.each(data.result.files, function (index, file) {
        //    if (file.url) {
        //        var link = $('<a>')
        //            .attr('target', '_blank')
        //            .prop('href', file.url);
        //        $(data.context.children()[index])
        //            .wrap(link);
        //    } else if (file.error) {
        //        var error = $('<span class="text-danger"/>').text(file.error);
        //        $(data.context.children()[index])
        //            .append('<br>')
        //            .append(error);
        //    }
        //});
        //alert(JSON.stringify(data));
        //$.each(data.result), function (index, result) {
        if (data.result.success) {
            var done = $('<span class="text-danger"/>').text('File upload done.');
            $(data.context.children()[0])
                .append('<br>')
                .append(done);
            uploadfilename = data.result.file.substring(data.result.file.lastIndexOf('/')+1);
            $('#avatar_img').attr('src', "files/"+uploadfilename);
        } else {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[0])
                .append('<br>')
                .append(error);
        }
        //}
        $('#dlg_btn_ok').button('enable');

    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
    //});

    //$("#fileUploader").fileupload({
    //    dataType: 'json',
    //    add: function (e, data) {
    //        data.context = $('<button/>').text('Upload')
    //            .appendTo(dlg_form)
    //            .click(function () {
    //                data.context = $('<p/>').text('Uploading...').replaceAll($(this));
    //                data.submit();
    //            });
    //    },
    //    done: function (e, data) {
    //        data.context.text('Upload finished.');
    //    }
    //    //url:"fileUpload",
    //    //allowedTypes:"png,gif,jpg,jpeg",
    //    //fileName:"uploaded_img",
    //    //onSuccess:function(files, data, xhr)
    //    //{
    //    //    data= $.parseJSON(data); // yse parseJSON here
    //    //    if(data.error){
    //    //        alert('upload failed!');
    //    //        //there is an error
    //    //    } else {
    //    //        //there is no error
    //    //        //fileName = data['fileName'];
    //    //        //$('#file_input').val(fileName);
    //    //        alert('upload ok!');
    //    //    }
    //    //}
    //});
}

function submit_upload_avatar() {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_upload_image').attr('title', 'Upload new avatar');

    //var form_html = '<form method="post" id="form_upload_image" class="contact_form" enctype="multipart/form-data" >' +
    //    'Select image to upload:' +
    //    '<input type="file" name="fileToUpload" id="fileToUpLoad">' +
    //    '<input type="submit" name="submit" value="Upload Image">' +
    //    '</form>';
    //var form_html = '<form id="uploadimage" method="post" enctype="multipart/form-data">' +
    //'<input type="text" name="first" value="Bob" />' +
    //'<input type="text" name="middle" value="James" />' +
    //'<input type="text" name="last" value="Smith" />' +
    //'<input name="image" type="file" />' +
    //'<button id="submit_upload_avatar_file">Submit</button>' +
    //'</form>' +
    //'<div id="fileuploadmessage" />';
    //var form_html = '<input id="fileupload" type="file" name="files[]" data-url="/" multiple></input>';
    var form_html = '<form id="fileupload" action="cloud/upload" method="POST" enctype="multipart/form-data">' +
            <!-- Redirect browsers with JavaScript disabled to the origin page -->
        '<noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>' +
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        '<div class="fileupload-buttonbar">' +
        '<div class="fileupload-buttons">' +
            <!-- The fileinput-button span is used to style the file input field as button -->
        '<span role="button" class="fileinput-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"><span class="ui-button-icon-primary ui-icon ui-icon-plusthick"></span><span class="ui-button-text">' +
        '<span>Add files...</span>' +

        '</span><input name="files[]" multiple="" type="file"></span>' +
        '<button role="button" type="submit" class="start ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"><span class="ui-button-icon-primary ui-icon ui-icon-circle-arrow-e"></span><span class="ui-button-text">Start upload</span></button>' +
        '<button role="button" type="reset" class="cancel ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"><span class="ui-button-icon-primary ui-icon ui-icon-cancel"></span><span class="ui-button-text">Cancel upload</span></button>' +
        '<button role="button" type="button" class="delete ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete</span></button>' +
        '<input class="toggle" type="checkbox">' +
            <!-- The global file processing state -->
        '<span class="fileupload-process"></span>' +
        '</div>' +
            <!-- The global progress state -->
        '<div class="fileupload-progress fade" style="display:none">' +
            <!-- The global progress bar -->
        '<div aria-valuenow="0" class="progress ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100"><div style="display: none; width: 0%;" class="ui-progressbar-value ui-widget-header ui-corner-left"></div></div>' +
            <!-- The extended global progress state -->
        '<div class="progress-extended">&nbsp;</div>' +
        '</div>' +
        '</div>' +
            <!-- The table listing the files available for upload/download -->
        '<table role="presentation"><tbody class="files"></tbody></table>' +
        '</form>';

    var script_ = '<!-- The template to display files available for upload -->' +
        '<script id="template-upload" type="text/x-tmpl">' +
        '{% for (var i=0, file; file=o.files[i]; i++) { %}' +
        '<tr class="template-upload fade">' +
        '<td>    <span class="preview"></span>    </td>    <td>' +
        '<p class="name">{%=file.name%}</p><strong class="error"></strong></td><td>' +
        '<p class="size">Processing...</p>' +
        '<div class="progress"></div></td><td>' +
        '{% if (!i && !o.options.autoUpload) { %}' +
        '<button class="start" disabled>Start</button>' +
        '{% } %}' +
        '{% if (!i) { %}' +
        '<button class="cancel">Cancel</button>' +
        '{% } %}' +
        '</td></tr>' +
        '{% } %}' +
        '</script>' +
            <!-- The template to display files available for download -->
        '<script id="template-download" type="text/x-tmpl">' +
        '{% for (var i=0, file; file=o.files[i]; i++) { %}' +
        '<tr class="template-download fade">' +
        '<td><span class="preview">' +
        '{% if (file.thumbnailUrl) { %}' +
        '<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>' +
        '{% } %}' +
        '</span></td><td><p class="name"><a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?\'data - gallery\':\'\'%}>{%=file.name%}</a></p>' +
        '{% if (file.error) { %}' +
        '<div><span class="error">Error</span> {%=file.error%}</div>' +
        '{% } %}' +
        '</td>' +
        '<td><span class="size">{%=o.formatFileSize(file.size)%}</span></td><td>' +
        '<button class="delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'    {        "withCredentials"    :        true    }    \'{% } %}>Delete</button>' +
        '<input type="checkbox" name="delete" value="1" class="toggle"></td></tr>' +
        '{% } %}' +
        '</script>';

    $(form_html).appendTo(dlg_form);
    $(script_).appendTo(dlg_form);
    $('#dlg_upload_image').dialog({
        modal: true,
        height: 500,
        width: 1000,
        close: function (event, ui) {
            $(this).remove();
        }
    });


    $("#fileupload").fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                //$('<p/>').text(file.name).appendTo(document.body);
                alert('file upload done!');
            })
        }
    });
}


// Catch the form submit and upload the files
function uploadFiles(event) {
    event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening

    // START A LOADING SPINNER HERE

    // Create a formdata object and add the files
    var data = new FormData();
    $.each(files, function (key, value) {
        data.append(key, value);
    });

    $.ajax({
        url: 'cloud/acceptFile',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data, textStatus, jqXHR) {
            if (typeof data.error === 'undefined') {
                // Success so call function to process the form
                submitForm(event, data);
            }
            else {
                // Handle errors here
                console.log('ERRORS: ' + data.error);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

function display_tab() {
    var form1 = $("#userprofileform");
    //var groups = $("#groupprofile");
    //var roles = $("#roleprofile");
    form1.empty();
    form1.append("<tr><th>Attribute</th><th>Value</th></tr>");
    //groups.empty();
    //groups.append("<tr><th>Group</th><th>Role</th><th>Action</th></tr>");
    //groups.append("<tr><th>Group</th><th>Role</th></tr>");
    //roles.empty();

    var d0 = new Date();
    var n0 = d0.getTime();
    console.log("client side, before cloud/getProfile current time "+n0);
    $.getJSON("cloud/getProfile", function (jsondata) {
        var bNormalUser = 0;
        //alert(JSON.stringify(jsondata));
        //alert(jsondata);
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'user_profile') {
                $.each(item, function (index3, item3) {
                    if (index3 == 'first_name') {
                        form1.append("<tr><td>" + "First name" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'last_name') {
                        form1.append("<tr><td>" + "Last name" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'phone') {
                        form1.append("<tr><td>" + "Phone" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'address') {
                        form1.append("<tr><td>" + "Address" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'city') {
                        form1.append("<tr><td>" + "City" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'state') {
                        form1.append("<tr><td>" + "State" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'country') {
                        form1.append("<tr><td>" + "Country" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'zip') {
                        form1.append("<tr><td>" + "Zip" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'institute') {
                        form1.append("<tr><td>" + "Organization" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'org_id') {
                       // form1.append("<tr><td>" + "Org ID (member id)" + "</td><td>" + item3 + "</td></tr>");
                    }
                });
            } else if (index == 'group_role') {
                $.each(item, function (index3, item3) {
                    if (item3.role=='GlobalSuperUser' && bNormalUser < 1) {
                        bNormalUser = 1;
                        //form1.append("<tr><td>" + "Role" + "</td><td>" + 'SuperUser' + "</td></tr>");
                    } else if (item3.role=='GlobalAdmin' && bNormalUser < 2) {
                        bNormalUser = 2;
                        //form1.append("<tr><td>" + "Role" + "</td><td>" + 'Admin' + "</td></tr>");
                    } else if (item3.role=='Trial' ) {
                        bNormalUser = -1;
                        //form1.append("<tr><td>" + "Role" + "</td><td>" + 'Admin' + "</td></tr>");
                    }
                });
            } else if (index == 'ttt') {
            }
        });
        //$("#project_list").trigger("change");
        var spltstring = $('#user_all_role').html().split(':');
        for (var stringrole in spltstring) {
            if (spltstring[stringrole] == 'global_group_docker_id' && bNormalUser < 1) {
                bNormalUser = 1;
            } else if (spltstring[stringrole] == 'global_admin_docker_id' && bNormalUser < 2) {
                bNormalUser = 2;
            } else if ('global_trial' == spltstring[stringrole]) {
                bNormalUser = -1;
            }
        }
        if (bNormalUser==0) {
            form1.append("<tr><td>" + "Role" + "</td><td>" + 'User<button id="submit_apply_superuserrole_class">Apply for SuperUser</button>' + "</td></tr>");
        }else if (bNormalUser==1) {
            form1.append("<tr><td>" + "Role" + "</td><td>" + 'Super User' + "</td></tr>");

            document.getElementById('submit_apply_superuserrole_class').innerHTML = "Application not available";
            document.getElementById('submit_apply_superuserrole_class').disabled = true;
        }else if (bNormalUser==2) {
            form1.append("<tr><td>" + "Role" + "</td><td>" + 'Admin' + "</td></tr>");

            document.getElementById('submit_apply_superuserrole_class').innerHTML = "Application not available";
            document.getElementById('submit_apply_superuserrole_class').disabled = true;
        } else if (bNormalUser==-1) {
            form1.append("<tr><td>" + "Role" + "</td><td>" + 'Trial' + "</td></tr>");

            document.getElementById('submit_apply_superuserrole_class').innerHTML = "Application not available";
            document.getElementById('submit_apply_superuserrole_class').disabled = true;
        }
    }).done(function () {
    }).fail(function (xhr, testStatus, errorThrown) {
        alert(xhr.responseText + testStatus + errorThrown);
    }).always(function () {
    });
    downloadvpnconifg();
}

function checkTrialRole() {
    var spltstring = $('#user_all_role').html().split(':');
    for (var stringrole in spltstring) {
        if (spltstring[stringrole] == 'global_group_docker_id' && bNormalUser < 1) {
            bNormalUser = 1;
        } else if (spltstring[stringrole] == 'global_admin_docker_id' && bNormalUser < 2) {
            bNormalUser = 2;
        } else if ('global_trial' == spltstring[stringrole]) {
            bNormalUser = -1;
            return true;
        }
    }
    return false;
}

function display_tab_old() {
    var form1 = $("#userprofileform");
    var groups = $("#groupprofile");
    //var roles = $("#roleprofile");
    form1.empty();
    form1.append("<tr><th>Attribute</th><th>Value</th></tr>");
    groups.empty();
    //groups.append("<tr><th>Group</th><th>Role</th><th>Action</th></tr>");
    groups.append("<tr><th>Group</th><th>Role</th></tr>");
    //roles.empty();

    var d0 = new Date();
    var n0 = d0.getTime();
    console.log("client side, before cloud/getProfile current time "+n0);
    $.getJSON("cloud/getProfile", function (jsondata) {
        var bNormalUser = 0;
        //alert(JSON.stringify(jsondata));
        //alert(jsondata);
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'user_profile') {
                $.each(item, function (index3, item3) {
                    if (index3 == 'first_name') {
                        form1.append("<tr><td>" + "First name" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'last_name') {
                        form1.append("<tr><td>" + "Last name" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'phone') {
                        form1.append("<tr><td>" + "Phone" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'address') {
                        form1.append("<tr><td>" + "Address" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'city') {
                        form1.append("<tr><td>" + "City" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'state') {
                        form1.append("<tr><td>" + "State" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'country') {
                        form1.append("<tr><td>" + "Country" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'zip') {
                        form1.append("<tr><td>" + "Zip" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'institute') {
                        form1.append("<tr><td>" + "Organization" + "</td><td>" + item3 + "</td></tr>");
                    } else if (index3 == 'org_id') {
                        // form1.append("<tr><td>" + "Org ID (member id)" + "</td><td>" + item3 + "</td></tr>");
                    }
                });
            } else if (index == 'group_role') {
                $.each(item, function (index3, item3) {
                    if (item3.role=='GlobalSuperUser' && bNormalUser < 1) {
                        bNormalUser = 1;
                        //form1.append("<tr><td>" + "Role" + "</td><td>" + 'SuperUser' + "</td></tr>");
                    } else if (item3.role=='GlobalAdmin' && bNormalUser < 2) {
                        bNormalUser = 2;
                        //form1.append("<tr><td>" + "Role" + "</td><td>" + 'Admin' + "</td></tr>");
                    } else {
                        groups.append("<tr><td>" + item3.group + "</td><td>" + item3.role + "</td></tr>");
                    }
                });
            } else if (index == 'ttt') {
                console.log("server side time 0: "+item.t0*1000);
                console.log("server side time 1: "+item.t1*1000);
                console.log("server side db initialization: "+(item.t1*1000-item.t0*1000)+" ms");

                console.log("server side time 2: "+item.t2*1000);
                console.log("server side time 3: "+item.t3*1000);
                console.log("server side select 1: "+(item.t3*1000-item.t2*1000)+ " ms");

                console.log("server side time 4: "+item.t4*1000);
                console.log("server side select 1 fetch results: "+(item.t4*1000-item.t3*1000)+" ms");

                console.log("server side time 5: "+item.t5*1000);
                console.log("server side select 2: "+(item.t5*1000-item.t4*1000)+" ms");

                console.log("server side time 6: "+item.t6*1000);
                console.log("server side select 2 fetch results: "+(item.t6*1000-item.t5*1000)+" ms");
            }
        });
        //$("#project_list").trigger("change");
        var spltstring = $('#user_all_role').html().split(':');
        for (var stringrole in spltstring) {
            if (spltstring[stringrole] == 'global_group_docker_id' && bNormalUser < 1) {
                bNormalUser = 1;
            } else if (spltstring[stringrole] == 'global_admin_docker_id' && bNormalUser < 2) {
                bNormalUser = 2;
            }
        }
        if (bNormalUser==0) {
            form1.append("<tr><td>" + "Role" + "</td><td>" + 'User' + "</td></tr>");
        }else if (bNormalUser==1) {
            form1.append("<tr><td>" + "Role" + "</td><td>" + 'Super User' + "</td></tr>");
        }else if (bNormalUser==2) {
            form1.append("<tr><td>" + "Role" + "</td><td>" + 'Admin' + "</td></tr>");
        }
    }).done(function () {
        var d = new Date();
        var n = d.getTime();
        console.log("client side, after group/getProfile current time "+n);
        console.log("client side,  group/getProfile ajax total: "+(n-n0)+ " ms");
    }).fail(function (xhr, testStatus, errorThrown) {
        alert(xhr.responseText + testStatus + errorThrown);
    }).always(function () {
        console.log("complete");
    });

    //var d = new Date();
    //var n = d.getTime();
    //console.log("before group/getOwnGroupList current time in millisecond "+n);
    $.getJSON("group/getOwnGroupList", function (jsondata) {
        var list = $("#groupprofile");
        //list.empty();select
        //list.append("<tr><th>Name</th><th>Description</th><th>Status</th><th>Member</th><th>Waiting List</th><th>Action</th></tr>");
        //alert(JSON.stringify(jsondata));
        $.each(jsondata, function (index, item) {
            //alert(item.userid);
            if (index == 'groups') {
                $.each(item, function (index3, item3) {
                    //list.append('<tr><td>' + item3.group_name + '</td><td>' + item3.user_email + '</td><td><button class="btn-pending-enroll" >Approve</button></td></tr>');
                    //list.append("<tr><td>" + item3.group_name + "</td><td></td><td></td><td><button type=\"button\" class=\"show_member\" >Members</button></td><td><button type=\"button\" class=\"go_pending_enroll\" >Waiting List</button></td><td><button type=\"button\" class=\"go_add_member\" >Invite Members</button>, <button type=\"button\" class=\"delete_group\" >Delete Group</button></td>");
                    list.append("<tr><td>" + item3.group_name + "</td><td>" + 'Group Owner' + "</td></tr>");
                    // , <button type=\"button\" class=\"edit_group\" >Edit Group</button>
                });
            }
        });
    }).done(function () {
        //  var d = new Date();
        //var n = d.getTime();
        //console.log("after cloud/getOwnGroupList current time in millisecond "+n);
    });
}


function display_user_profile_tab() {
    //email
    $("input[name=" + 'email' + "]").attr("value", document.getElementsByClassName('menu_trigger')[0].text);
    $.getJSON("cloud/getProfile", function (jsondata) {
        //alert(JSON.stringify(jsondata));
        //alert(jsondata);
        $.each(jsondata, function (index4, item4) {
            //alert(item.userid);
            if (index4 == 'user_profile') {
                $.each(item4, function (index3, item3) {
                    if (index3 == 'avatar') {
                        $('#avatar_img').attr('src', "files/"+item3);
                        //$("#avatar_img").attr("src", "img/avatar/" + item3 + ".jpg");
                    } else {
                        $("input[name=" + index3 + "]").attr("value", item3);
                    }
                });
                //$("#project_list").trigger("change");
            }
        })
    });
}

function get_role_list_html() {
    return '<select><option value="13">Student</option><option value="12">TA</option><option value="14">Instructor</option></select>';
}

function check_password(password) {
    if (password.length <8)
        return false;

    if (password.replace(/[^A-Z]/g, "").length ==0)
        return false;

    if (password.replace(/[^a-z]/g, "").length <=1)
        return false;

    if (password.replace(/[^0-9]/g, "").length <=1)
        return false;

    return true;
}

function check_phonenumber(phonenumber) {
    if (phonenumber.length <8)
        return false;

    if (phonenumber.replace(/[0-9]/g, "").length >0)
        return false;

    return true;
}

function check_zipnumber(zipcode) {
    if (zipcode.length <5)
        return false;

    if (zipcode.replace(/[0-9]/g, "").length >0)
        return false;

    return true;
}


function submit_update_user_password(element) {
    if ($("input[name=cur_pass]").val() != '' || $("input[name=new_pass]").val() != '' || $("input[name=new_pass_again]").val() != '') {
        if ($("input[name=new_pass]").val() == $("input[name=new_pass_again]").val() && $("input[name=cur_pass]").val() != '') {
            if (check_password($("input[name=new_pass]").val())) {
                $.post("group/manualChangeUserPassword", {
                        "email": document.getElementsByClassName('menu_trigger')[0].text,
                        "_token": $('meta[name="_token"]').attr('content'),
                        "cur_pass": $("input[name=cur_pass]").val(),
                        "new_pass": $("input[name=new_pass]").val()
                    },
                    function (data) {
                    },
                    'json'
                ).fail(function (xhr, testStatus, errorThrown) {
                        alert(xhr.responseText);
                    }).done(function (xhr, testStatus, errorThrown) {
                        //alert('done' + xhr.responseText);
                        alert('Password update done.');
                    });
            } else {
                alert('password should be minimum 8 characters including both letters (minimal 2) and numbers (minimal 2), at least one letter is uppercase');
            }

            //update_profile_without_pass();
        } else {
            alert('new passwords are not equal');
        }
    } else {
        alert('please input the passwords');
    }
}

function submit_update_user_profile(element) {
    /*if ($("input[name=alt_email]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'alt_email is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else */if ($("input[name=first_name]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'first_name is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=last_name]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'last_name is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=phone]").val()!='' &&
        !check_phonenumber($("input[name=phone]").val())) {
        $.jAlert({
            'title': 'Warning', 'content': 'phone is wrong format!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } /*else if ($("input[name=address]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'address is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=city]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'city is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=state]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'state is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    }*/ else if ($("input[name=country]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'country is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=zip]").val()!='' &&
        !check_zipnumber($("input[name=zip]").val())) {
        $.jAlert({
            'title': 'Warning', 'content': 'zip is wrong format!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=institute]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'institute is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else {
        if (false && ($("input[name=cur_pass]").val() != '' || $("input[name=new_pass]").val() != '' || $("input[name=new_pass_again]").val() != '')) {
            if ($("input[name=new_pass]").val() == $("input[name=new_pass_again]").val() && $("input[name=cur_pass]").val() != '') {
                $.post("/user/changepassword2/", {
                        "email": document.getElementsByClassName('menu_trigger')[0].text,
                        "_token": $('meta[name="_token"]').attr('content'),
                        "cur_pass": $("input[name=cur_pass]").val(),
                        "new_pass": $("input[name=new_pass]").val()
                    },
                    function (data) {
                    },
                    'json'
                ).fail(function (xhr, testStatus, errorThrown) {
                        alert(xhr.responseText);
                    }).done(function (xhr, testStatus, errorThrown) {
                        alert('done' + xhr.responseText);
                    });
                update_profile_without_pass();
            } else {
                alert('new passwords are not equal');
            }
        } else {
            update_profile_without_pass();
        }
    }

}
function firsttime_submit_update_user_profile(element) {
    /*if ($("input[name=firsttime_alt_email]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'alt_email is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else*/ if ($("input[name=firsttime_first_name]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'first_name is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_last_name]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'last_name is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_phone]").val()!='' &&
        !check_phonenumber($("input[name=firsttime_phone]").val())) {
        $.jAlert({
            'title': 'Warning', 'content': 'phone is wrong format!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } /*else if ($("input[name=firsttime_address]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'address is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_city]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'city is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_state]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'state is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    }*/ else if ($("input[name=firsttime_country]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'country is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_zip]").val()!='' &&
        !check_zipnumber($("input[name=firsttime_zip]").val()) ) {
        $.jAlert({
            'title': 'Warning', 'content': 'zip is wrong format!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_institute]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'institute is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else {
        $.post("/group/updateUserProfile", {
                "alt_email": $("input[name=firsttime_alt_email]").val(),

                "first_name": $("input[name=firsttime_first_name]").val(),
                "last_name": $("input[name=firsttime_last_name]").val(),
                "phone": $("input[name=firsttime_phone]").val(),
                "address": $("input[name=firsttime_address]").val(),
                "city": $("input[name=firsttime_city]").val(),
                "state": $("input[name=firsttime_state]").val(),
                "country": $("input[name=firsttime_country]").val(),
                "zip": $("input[name=firsttime_zip]").val(),
                "institute": $("input[name=firsttime_institute]").val(),
                "org_id": $("input[name=org_id]").val(),

                "cur_pass": $("input[name=cur_pass]").val(),
                "new_pass": $("input[name=new_pass]").val()
            },
            function (data) {
                if (data.status == "Success") {
                    //alert(data.msg);
                    element.closest('.dialog').dialog('close');
                }
                else {
                    //alert(data.msg);
                    element.closest('.dialog').dialog('close');
                }
            },
            'json'
        );
    }
}
function firsttime_submit_update_both_pass_and_user_profile(element) {
    /*if ($("input[name=firsttime_both_alt_email]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'alt_email is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else*/ if ($("input[name=firsttime_both_first_name]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'first_name is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_both_last_name]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'last_name is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_both_phone]").val()!='' &&
        !check_phonenumber($("input[name=firsttime_both_phone]").val())) {
        $.jAlert({
            'title': 'Warning', 'content': 'phone is wrong format!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } /*else if ($("input[name=firsttime_both_address]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'address is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_both_city]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'city is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_both_state]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'state is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    }*/ else if ($("input[name=firsttime_both_country]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'country is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_both_zip]").val()!='' &&
        !check_zipnumber($("input[name=firsttime_both_zip]").val()) ) {
        $.jAlert({
            'title': 'Warning', 'content': 'zip is wrong format!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else if ($("input[name=firsttime_both_institute]").val()=='') {
        $.jAlert({
            'title': 'Warning', 'content': 'institute is required!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
    } else {
        // update pass
        if ($("input[name=cur_both_pass]").val() != '' || $("input[name=new_both_pass]").val() != '' || $("input[name=new_both_pass_again]").val() != '') {
            if ($("input[name=new_both_pass]").val() == $("input[name=new_both_pass_again]").val() && $("input[name=cur_both_pass]").val() != '') {
                if (check_password($("input[name=new_both_pass]").val())) {
                    $.post("group/manualChangeUserPassword", {
                            "email": document.getElementsByClassName('menu_trigger')[0].text,
                            "_token": $('meta[name="_token"]').attr('content'),
                            "cur_pass": $("input[name=cur_both_pass]").val(),
                            "new_pass": $("input[name=new_both_pass]").val()
                        },
                        function (data) {
                            if (data.status == "Success") {
                                //alert(data.msg);
                                element.closest('.dialog').dialog('close');
                            }
                            else {
                                //alert(data.msg);
                                element.closest('.dialog').dialog('close');
                            }
                        },
                        'json'
                    ).fail(function (xhr, testStatus, errorThrown) {
                            alert(xhr.responseText);
                        }).done(function (xhr, testStatus, errorThrown) {
                            //alert('done' + xhr.responseText);
                            alert('profile update done.');
                        });
                } else {
                    alert('password should be minimum 8 characters including both letters (minimal 2) and numbers (minimal 2), at least one letter is uppercase');
                }

                //update_profile_without_pass();
            } else {
                alert('new passwords are not equal');
            }
        } else {
            alert('please input the passwords');
        }
        // update without pass
        $.post("/group/updateUserProfile", {
                "alt_email": $("input[name=firsttime_both_alt_email]").val(),

                "first_name": $("input[name=firsttime_both_first_name]").val(),
                "last_name": $("input[name=firsttime_both_last_name]").val(),
                "phone": $("input[name=firsttime_both_phone]").val(),
                "address": $("input[name=firsttime_both_address]").val(),
                "city": $("input[name=firsttime_both_city]").val(),
                "state": $("input[name=firsttime_both_state]").val(),
                "country": $("input[name=firsttime_both_country]").val(),
                "zip": $("input[name=firsttime_both_zip]").val(),
                "institute": $("input[name=firsttime_both_institute]").val(),
                "org_id": $("input[name=org_id]").val(),

                "cur_pass": $("input[name=cur_both_pass]").val(),
                "new_pass": $("input[name=new_both_pass]").val()
            },
            function (data) {

            },
            'json'
        );
    }
}
function update_profile_without_pass() {

    //alert($("input[name=first_name]").value);
    $.post("/group/updateUserProfile", {
            "alt_email": $("input[name=alt_email]").val(),

            "first_name": $("input[name=first_name]").val(),
            "last_name": $("input[name=last_name]").val(),
            "phone": $("input[name=phone]").val(),
            "address": $("input[name=address]").val(),
            "city": $("input[name=city]").val(),
            "state": $("input[name=state]").val(),
            "country": $("input[name=country]").val(),
            "zip": $("input[name=zip]").val(),
            "institute": $("input[name=institute]").val(),
            "org_id": $("input[name=org_id]").val(),

            "cur_pass": $("input[name=cur_pass]").val(),
            "new_pass": $("input[name=new_pass]").val()
        },
        function (data) {
            $.jAlert({
                'title':'Information',
                'content' : 'Profile updated',
                'theme' : 'blue',
                'btns' : {'text':'close','theme':'green'}
            });
//            alert("Profile updated");
            //if (data.status == "Success") {
            //    display_user_profile_tab();
            //}
            //else {
            //    display_user_profile_tab();
            //}
            //$(".tabs").tabs("option","active",0);
            //display_tab();
        },
        'json'
    );
}
