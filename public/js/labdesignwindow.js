/**
 * Created by root on 8/14/15.
 */
var debug = 0; // 0 - off and 1 - on
function do_windows_labdesign_display(winId, win_main) {
    var tabs = {
        tabId: ['lab_repo','lab_templates', 'own_lab'],
        tabName: ['Lab Repository', 'Design Lab Environment', 'Design Lab Content']
    };
    create_tabs(winId, win_main, tabs, null);

    var title_one = '<fieldset><legend style="font-weight: bold;font-size: larger">Open Labs:</legend>' +  '</fieldset>';
    var div_console = $(document.createElement('div')).appendTo($('#lab_repo'));
    //div_console.attr("id", "studio"); //.attr("height","100%");
    $(title_one).appendTo(div_console);

    var textbox = document.createElement('input');
    textbox.id = "search_plugin";
    textbox.type = "text";
    textbox.placeholder = "Search Labs...";
    $(textbox).appendTo($('#lab_repo'));

    var div_tree =document.createElement('div');
    div_tree.id = "jstree_openlab";
    div_tree.className = "jstree_openlab";
    $(div_tree).appendTo($('#lab_repo'));
    run_waitMe(win_main, 'ios');

    //$(openlabs).appendTo(div_tree);

    var group_member = '<fieldset>' +
        '<button type="button" class="btn_lab_studio submit" name="Your Labs" value="edxstudio">Create Your Own New Content</button>' +
        '</fieldset><br>';

    $(group_member).appendTo('#own_lab');
    var div_console1 = $(document.createElement('div')).appendTo($('#own_lab'));
    var title_two = '<fieldset><legend style="font-weight: bold;font-size: larger">Self-defined Content:</legend></fieldset>';
    $(title_two).appendTo(div_console1);
    var tableownclass = $(document.createElement('table')).appendTo(div_console1);
    tableownclass.addClass("data").attr("id", "tbl_own_class").append('<thead><tr>' +

    '<th>Lab Name</th>' +
    '<th>Lab ID</th>' +
    '<th>Assigned Template ID</th>'+
    '<th>Actions</th>' +
    '</tr></thead>');
    var tbodyownclass = $(document.createElement('tbody')).appendTo(tableownclass);
    //div_console.attr("id", "studio"); //.attr("height","100%");
    //run_waitMe(win_main, 'ios');
    $.getJSON("/cloud/getOwnClass", function (jsondata) {
        $.each(jsondata, function (index, item) {
            $('<tr>' +
            '<td>' + item.coursename + '</td>' +
            '<td>' + item.courseid + '</td>' +
            '<td>' + item.tempid + '</td>' +
            '<td class="hidden">' + item.nameid + '</td>' +
            '<td class="hidden">' + item.urlid + '</td>' +
            '<td class="dropdown"><a class="btn btn-default ownclass-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                //'<td>&mdash;</td>' +
            '</tr>').appendTo(tbodyownclass);

        });
        //$(win_main).waitMe('hide');
    });
    var contextMenuown = $(document.createElement('ul')).appendTo(tbodyownclass);
    contextMenuown.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'ownclass-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="ownclass-view">View</a></li>').appendTo(contextMenuown);
    $('<li><a tabindex="-1" href="#" class="ownclass-edit">Edit</a></li>').appendTo(contextMenuown);
//    $('<li><a tabindex="-1" href="#" class="ownclass-assign">Assign Template</a></li>').appendTo(contextMenuown);

    function getOpenLabs(labId){
        return $.ajax({
            url: "cloud/getOpenLabs",
            type: "GET",
            data: {
              labId: labId
            },
            cache: false,
            error: function() {
                if(debug==1)
                    console.log("Fail");
            }
        })

    }
    $.when(getOpenLabs("SWS"), getOpenLabs("SSC"), getOpenLabs("CNS"), getOpenLabs("SOD"), getOpenLabs("CNE"), getOpenLabs("CCL"))
        .done(function(sws, ssc, cns, sod, cne, ccl) {
            if(debug==1)
                console.log(ccl);
            var children ={'sws':[],'ssc':[],'cns':[],'sod':[], 'cne':[], 'ccl':[]};
            var max_length = Math.max(sws[0][0].length, ssc[0][0].length, cns[0][0].length, sod[0][0].length, cne[0][0].length, ccl[0][0].length);
            console.log(max_length);

            for(var i = 0; i< max_length ; i++) {
                if(i<sws[0][0].length)
                    children.sws[i] = {'text': sws[0][2][i],'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_' + sws[0][0][i] + '_' + sws[0][1][i]}};
                if(i<ssc[0][0].length)
                    children.ssc[i] = {'text': ssc[0][2][i],'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_' + ssc[0][0][i] + '_' + ssc[0][1][i]}};
                if(i<cns[0][0].length)
                    children.cns[i] = {'text': cns[0][2][i],'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_' + cns[0][0][i] + '_' + cns[0][1][i]}};
                if(i<sod[0][0].length)
                    children.sod[i] = {'text': sod[0][2][i],'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_' + sod[0][0][i] + '_' + sod[0][1][i]}};
                if(i<cne[0][0].length)
                    children.cne[i] = {'text': cne[0][2][i],'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_' + cne[0][0][i] + '_' + cne[0][1][i]}};
                if(i<ccl[0][0].length)
                    children.ccl[i] = {'text': ccl[0][2][i],'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_' + ccl[0][0][i] + '_' + ccl[0][1][i]}};
            }
            if(debug==1)
                console.log(children.ccl);
            //$(win_main).waitMe('hide');
            $('#jstree_openlab').jstree({
                'state' : {'key' : 'state_demo' },
                'plugins' : ['themes', 'state', 'ui', 'types', 'search'],
                'types' : { 'default' : { 'icon' : 'fa fa-file-text-o'}, 'folder' : { 'icon' : 'fa fa-folder-o'}},
                'core': {
                    'themes': {
                        'name': 'proton',
                        'responsive': false
                    },
                    'data' : [
                        {
                            'text' : "Open Labs",
                            'type' : 'folder',
                            'state' : {
                                'opened' : true,
                                'selected' : true
                            },
                            'children' : [
                                { 'text' : 'Security Labs', 'type': 'folder', 'children' : [
                                    { 'text' : 'Software and Web Security', 'type': 'folder', 'children' : children.sws},
                                    { 'text' : 'System Security and Cryptography', 'type': 'folder', 'children' : children.ssc},
                                    { 'text' : 'Computer Network Security', 'type': 'folder', 'children' : children.cns}
                                ]},
                                { 'text' : 'Software Labs', 'type': 'folder', 'children' : children.sod},
                                { 'text' : 'Network Labs', 'type': 'folder', 'children' : children.cne},
                                { 'text' : 'Cloud Computing Labs', 'type': 'folder', 'children' : children.ccl}
                            ]
                        }
                    ]
                }
            }).bind("select_node.jstree", function (e, data) {
                return data.instance.toggle_node(data.node)
            });
            var to = false;
            $('#search_plugin').keyup(function () {
                if(to) { clearTimeout(to); }
                to = setTimeout(function () {
                    var v = $('#search_plugin').val();
                    $('#jstree_openlab').jstree(true).search(v);
                }, 250);
            });
    });

    //var table3 = $(document.createElement('table')).appendTo($('#testing_lab'));
    //table3.addClass("data").attr("id", "tbl_testing_lab").append('<thead><tr>' +
    //'<th class="shrink">&nbsp;</th>' +
    //    //'<th>Group</th>' +
    //'<th>Name</th>' +
    //'<th>Description</th>' +
    //'<th class="hidden">UUID</th>' +
    //    //'<th>Members</th>' +
    //'<th>Actions</th>' +
    //    //'<th>Notes</th>' +
    //'</tr></thead>');
    //var tbody3 = $(document.createElement('tbody')).appendTo(table3);
    //
    //run_waitMe(win_main, 'ios');
    //$.getJSON("/cloud/getProjects", function (jsondata) {
    //    $.each(jsondata, function (index, item) {
    //        $('<tr>' +
    //        '<td><img src="' + ICON_project + '"></img></td>' +
    //            //'<td>' + group + '</td>' +
    //        '<td>' + item.name + '</td>' +
    //        '<td>' + item.description + '</td>' +
    //        '<td class="hidden">' + item.id + '</td>' +
    //            //'<td><button type="button" class="proj_member"><i class="fa fa-users"></i></button></td>' +  //"<td>" + item.users + "</td>" +
    //        '<td class="dropdown"><a class="btn btn-default proj-actionButton"  href="#">Delete</a></td>' +
    //            //'<td>&mdash;</td>' +
    //        '</tr>').appendTo(tbody3);
    //    });
    //    $(win_main).waitMe('hide');
    //});

    var lab_design_tab = $(document.createElement('div')).appendTo($('#lab_repo'));
    $('<br><hr /><br>').appendTo(lab_design_tab);
    $('<button />').attr('onclick', 'create_a_new_lab()').addClass('submit').text('Compose a Private Lab').appendTo(lab_design_tab);
    $('<legend />').attr('style','font-weight: bold;font-size: larger').text('Private Labs:').appendTo(lab_design_tab);
    var lab_design_table = $(document.createElement('table')).appendTo(lab_design_tab);
    lab_design_table.addClass("data").attr("id", "tbl_lab_design_list").append('<thead><tr>' +
    '<th class="hidden">Lab ID</th>' +
    '<th>Lab Name</th>' +
    '<th class="hidden">Template_ID</th>'+
    '<th>Template</th>'+
    '<th class="hidden">lab_content_id</th>'+
    '<th>Lab Content</th>'+
    '<th>Description</th>'+
    '<th>Actions</th>' +
    '</tr></thead>');
    $(document.createElement('tbody')).appendTo(lab_design_table);

    lab_design_management(win_main);
}

function labDesign_edit(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_create_a_lab').attr('title', 'Edit Lab');

    var lab_name= element.closest('tr').children().eq(1).html();
    var lab_desc= element.closest('tr').children().eq(7).html();

    $('<div><span class="required_notification">* Denotes Required Field</span></li><br><span style="color: red">*</span><label for="new_lab_name">Lab name:</label>&nbsp;' +
    '<input type="text" id="new_lab_name"  value="'+ lab_name +'"><br><br>' +
    '<span style="color: red">*</span><label for="select_lab_template">Select a Lab Running Environment:</label>&nbsp;' +
    '<select id="select_lab_template"></select><br><br>' +
    '<span style="color: red">*</span><label for="select_lab_content">Select Lab Content:</label>&nbsp;' +
    '<select id="select_lab_content"></select><br><br>' +
    '<span style="color: red">*</span><label for="new_lab_desc">Description:</label>' +
    '<textarea id="new_lab_desc" style="width: 250px; height: 100px; resize: none;" >'+ lab_desc +'</textarea></div>').appendTo(dlg_form);

    $('#dlg_create_a_lab').dialog({
        modal: true,
        height: 320,
        overflow: "auto",
        width: 300,
        buttons: {
            "Update": function () {
                $.post("/cloud/updateAssignTemp", {
                        "id": element.closest('tr').children().eq(0).html(),
                        "lab_name": $('#new_lab_name').val().trim(),
                        "temp_id": $('#select_lab_template :selected').val(),
                        "content_id": $('#select_lab_content :selected').val(),
                        "name_id": $('#select_lab_content :selected').attr('value1'),
                        "desc": $('#new_lab_desc').val().trim()
                    },
                    function (data) {
                        //if (data.status == 'Success') {
                        lab_design_management();
                        //} else {
                        //    alert(data.message);
                        //}
                    },
                    'json'
                );
                $(this).dialog('close');
            },
            "Cancel": function () {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
            lab_design_management();
        }
    });
    $('<option />').attr('value', '').attr('value1','').text('None').appendTo('#select_lab_template');

    $.getJSON("/cloud/getTemplate", function (jsondata) {

        $.each(jsondata, function (index, item) {
            $('<option />').attr('value', item.temp_id).text(item.temp_name).appendTo('#select_lab_template');
        });
    });
    $('<option />').attr('value', '').attr('value1','').text('None').appendTo('#select_lab_content');
    $.getJSON("/cloud/getOwnClass", function (jsondata) {

        $.each(jsondata, function (index, item) {
            $('<option />').attr('value', item.courseid).attr('value1',item.nameid).text(item.coursename).appendTo('#select_lab_content');
        });
    });
}

function labDesign_delete(element) {
    var lab_name= element.closest('tr').children().eq(1).html();
    var lab_id= element.closest('tr').children().eq(0).html();

    var message = 'Are you sure you want to delete the selected lab : '+lab_name+'?';
    create_ConfirmDialog('Delete the Selected Lab', message,
        function() {
            $.post("cloud/deleteAssignTemp", {
                "id": lab_id

            })
                .done(function (data) {
                    if (data.status == "Success") {
                    //    getLabDeployListTable(groupname);
                    }
                    lab_design_management();
                });
        }, function() {
            // Cancel function
        });
}

function do_windows_openlabs_display(winId, win_main) {
    var tabs = {
        tabId: ['lab_repo1'],
        tabName: ['Lab Repository']
    };
    create_tabs(winId, win_main, tabs, null);

    var title_one = '<fieldset><legend style="font-weight: bold;font-size: larger">Open Labs:</legend>' +  '</fieldset>';
    var div_console = $(document.createElement('div')).appendTo($('#lab_repo1'));
    //div_console.attr("id", "studio"); //.attr("height","100%");
    $(title_one).appendTo(div_console);

    var div_tree =document.createElement('div');
    div_tree.id = "jstree_openlabinopenlabs";
    div_tree.className = "jstree_openlabinopenlabs";
    $(div_tree).appendTo($('#lab_repo1'));

    $('#jstree_openlabinopenlabs').jstree({
        'state' : {'key' : 'state_demo' },
        'plugins' : ['themes', 'state', 'ui', 'types'],
        'types' : { 'default' : { 'icon' : 'fa fa-file-text-o'}, 'folder' : { 'icon' : 'fa fa-folder-o'}},
        'core': {
            'themes': {
                'name': 'proton',
                'responsive': false
            },
            'data' : [
                {
                    'text' : 'Open Labs',
                    'type' : 'folder',
                    'state' : {
                        'opened' : true,
                        'selected' : true
                    },
                    'children' : [
                        { 'text' : 'Security Labs', 'type': 'folder', 'children' : [
                            { 'text' : 'Software and Web Security', 'type': 'folder', 'children' : [
                                {'text': 'Buffer Overflow Vulnerability Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS_2015_T1'}},
                                {'text': 'Web Tracking Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS01_2015_T1'}},
                                {'text': 'Cross Site Scripting Attack Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS03_2015_T1'}},
                                {'text': 'Cross Site Request Forgery(CSRF) Attack Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS04_2015_T1'}},
                                {'text': 'Return to libc Attack Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS05_2015_T1'}},
                                {'text': 'Set-UID Program Vulnerability Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS06_2015_T1'}},
                                {'text': 'Race Condition Vulnerability Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS07_2015_T1'}},
                                {'text': 'Format String Vulnerability Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS08_2015_T1'}},
                                {'text': 'Shellshock Vulnerability Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS09_2015_T1'}},
                                {'text': 'SQL Injection Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS10_2015_T1'}},
                                {'text': 'Web Based Attack Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS11_2015_T1'}},
                                {'text': 'Web Content Exploitation Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS12_2015_T1'}},
                                {'text': 'Javascript Mischef',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS13_2015_T1'}},
                                {'text': 'Abusing Side Channel',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS14_2015_T1'}},
                                {'text': 'Cookie Management Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS15_2014_T1'}},
                                {'text': 'Dynamic Pages using Templates Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS16_2015_T1'}},
                                {'text': 'Wireshark and Penetration Testing Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS22_2015_T1'}},
                                {'text': 'SQL Injection and Input Filtering Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SWS17_2015_T1'}}
                            ]
                            },
                            { 'text' : 'System Security and Cryptography', 'type': 'folder', 'children' : [
                                {'text': 'Linux Capability Exploitation Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SS05_2015_T1'}},

                                {'text': 'Crypto Lab-One-Way Hash Function and MAC',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SSC02_2015_T1'}},
                                {'text': 'Crypto Lab-Public-Key Cryptography',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SSC03_2015_T1'}},
                                {'text': 'Crypto Lab-Secret-Key Encryption',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SSC_2015_T1'}},
                            ]
                            },
                            { 'text' : 'Computer Network Security', 'type': 'folder', 'children' : [
                                {'text': 'Remote DNS Attack Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNS02_2015_T1'}},
                                {'text': 'Local DNS Attack Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNS03_2015_T1'}},
                                {'text': 'Packet Sniffing and Spoofing Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNS04_2015_T1'}},
                                {'text': 'Linux Firewall Exploration Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNS05_2015_T1'}},
                                {'text': 'Network Services Setup',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNS06_2015_T1'}},
                                {'text': 'Firewall and IPtabls',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNS07_2015_T1'}},
                                {'text': 'Snort and Syslog',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNS08_2015_T1'}},
                                {'text': 'SSLStrip Attack Lab',
                                    'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNS18_2015_T1'}}
                            ]
                            }
                        ]},
                        { 'text' : 'Software Labs', 'type': 'folder', 'children' : [
                            {'text' : 'Simple Service and Application',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SOD6_2015_T1'}},
                            {'text' : 'Multi-Thread System: Hotel Booking System',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SOD7_2015_T1'}},
                            {'text' : 'Soap and Restful Service',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SOD8_2015_T1'}},
                            {'text' : 'XML, XSD and Xpath',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SOD9_2015_T1'}},
                            {'text' : 'Web Application using Web Services',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_SOD10_2015_T1'}}
                        ]},
                        { 'text' : 'Network Labs', 'type': 'folder', 'children' : [

                            {'text' : 'Basic Network Configuration Lab',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNE02_2015_T1'}},
                            {'text' : 'Network Routing Lab',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNE03_2015_T1'}},
                            {'text' : 'Routing Protocols and NMap',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNE04_2015_T1'}},
                            {'text' : 'Apache Server and DNS Server Lab',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNE05_2015_T1'}}
                        ]},
                        { 'text' : 'System Labs', 'type': 'folder', 'children' : [
                            {'text' : 'Linux Basics Lab',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CNE01_2015_T1'}},
                        ]},
                        { 'text' : 'Cloud Computing Labs', 'type': 'folder', 'children' : [
                            {'text' : 'Introduction to Cloud Computing',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CCL01_2015_T1'}},
                            {'text' : 'Hadoop, MapReduce Lab',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CCL02_2015_T1'}},
                            {'text' : 'Scalable Cloud Data Serving with Cassandra',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CCL03_2015_T1'}},
                            {'text' : 'Privacy Preserving Methods for Outsourced Computation',
                                'a_attr': {'class': 'btn_view_open_lab', 'name': 'Open Lab Content', 'value': 'open_lab_CCL04_2015_T1'}},
                        ]}
                    ]
                }
            ]
        }
    }).bind("select_node.jstree", function (e, data) {
        return data.instance.toggle_node(data.node)
    });
}