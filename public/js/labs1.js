/**
 * Created by root on 2/10/15.
 */

var lab_resources = {};
var lab_template = { "AWSTemplateFormatVersion": "2010-09-09", "Resources": lab_resources };


function prepare_lab_design(tab_div) {
    var project = '';
    var popup_div = $(document.createElement('div')).appendTo($(tab_div));
    popup_div.addClass('addNode-popUp').attr('id', 'network-popUp_' + project)
        .css('top', '200px').css('left', '150px').css('width', '400px').css('height', '250px');
    $('<span id="operation_' + project + '" style="font-size:20px;">node</span> <br>' +
    '<table style="margin:auto;">' +
    '<tr><td>id</td><td><input id="node-id_' + project + '" value="new value"></td></tr>' +
    '<tr><td>Type</td><td><select id="node-type_' + project + '">' + '<option value="0">Select a Resource...</option>' +
    '<option value="vm">VM</option><option value="switch">Switch</option><option value="router">Router</option></select><br />' +
    '<tr><td>Settings</td><td>' +
    '<select style="display:none;" id="node-type-vm-image_' + project + '"><option value="0">Select a image...</option></select>' +
    '<select style="display:none;" id="node-type-vm-flavor_' + project + '"><option value="0">Select a flavor...</option></select>' +
    '<select style="display:none;" id="node-type-router_' + project + '"><option value="0">Select a router type...</option>' +
    '<option value="quagga">Quagga</option><option value="vrouter">Virtual Router</option></select>' +
    '<input style="display:none;" type="text" id="node-type-sw-subnets_' + project + '" placeholder="192.168.1.0/24, 192.168.2.0/24" />' +
    '</td></tr>' +
    '<tr><td>label</td><td><input id="node-label_' + project + '" value="new value"> </td></tr></table>' +
    '<input type="button" value="Save" id="saveButton_' + project + '"></button>' +
    '<input type="button" value="Cancel" id="cancelButton_' + project + '"></button></div>').appendTo(popup_div);

    var table = $(document.createElement('table')).appendTo($(tab_div + project));
    table.attr("class", "data").css('width', '100%').append('<thead><tr><th></th></tr></thead>');
    var table_head = table.find('th');

    $('<span id="selection_' + project + '">Selection: None</span>').css('float', 'left').appendTo(table_head);
    $('<span id="temp_name">Lab Name: New</span>').css('float', 'left').css('margin-left', '30%').appendTo(table_head);
    var newbtn1 = $(document.createElement('button')).appendTo(table_head);
    newbtn1.addClass("dialog-btn").attr('id', 'btn_verify_design_' + project).css('float', 'right').text('Verify Design');
    var newbtn2 = $(document.createElement('button')).appendTo(table_head);
    newbtn2.addClass("dialog-btn").attr('id', 'btn_save_design_' + project).css('float', 'right').text('Save Design');

    var mynetwork_div = $(document.createElement('div')).appendTo($(tab_div + project));
    mynetwork_div.attr('id', 'lab_design_div_' + project).css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');
    //var display_nodes = $(document.createElement('textarea')).appendTo($(tab_div + project));
    //display_nodes.attr('id', 'json_nodes').css('width','100%').css('height', '100px');
    //var display_edges = $(document.createElement('textarea')).appendTo($(tab_div + project));
    //display_edges.attr('id', 'json_edges').css('width','100%').css('height', '100px');
}

var design_network = null;
var nodes = new vis.DataSet();
var edges = new vis.DataSet();

function lab_design(project) {
    var routercount = 0;
    var portcount = 0;
    nodes.clear();
    edges.clear();
    nodes.subscribe('*', function() { $('#json_nodes').html(JSON.stringify(nodes.get(), null,4)); });
    edges.subscribe('*', function() { $('#json_edges').html(JSON.stringify(edges.get(), null,4)); });
    var lab_design_data = { nodes: nodes, edges: edges };

    var DIR = 'workspace-assets/images/icons/';
    var LENGTH_MAIN = 150;
    var LENGTH_SUB = 50;

    nodes.add({id:"Internet", label: 'Internet', shape: 'image', image : DIR + 'System-Globe-icon.png'});
    //nodes.push({id:2, label: 'test2'});
    //edges.push({from:1, to:2});

    var keyobj = {KeyPair:{Type:"OS::Nova::KeyPair",Properties:{name:"keypair",save_private_key:"true"}}};
    //lab_resources.push();
    jQuery.extend(lab_resources,keyobj);

    var container = document.getElementById('lab_design_div_' + project);
    var canvas_height = parseInt($('#lab_design_div_' + project).closest('div.window_inner').css('height'));
    container.style.height = canvas_height - 163 + 'px';
    container.style.minHeight = '550px';
    var options = {
        stabilize: false,
        dataManipulation: true,
        onAdd: function (data, callback) {
            var span = document.getElementById('operation_' + project);
            //var idInput = document.getElementById('node-id_' + project);
            var labelInput = document.getElementById('node-label_' + project);
            var saveButton = document.getElementById('saveButton_' + project);
            var cancelButton = document.getElementById('cancelButton_' + project);
            var div = document.getElementById('network-popUp_' + project);
            var node_type = document.getElementById('node-type_' + project).value;
            span.innerHTML = "Add Node";
            //idInput.value = data.id;
            labelInput.value = data.label;
            saveButton.onclick = saveData.bind(this, data, callback);
            cancelButton.onclick = clearPopUp.bind();
            div.style.display = 'block';
        },
        onEdit: function (data, callback) {
            var span = document.getElementById('operation_' + project);
            //var idInput = document.getElementById('node-id_' + project);
            var labelInput = document.getElementById('node-label_' + project);
            var saveButton = document.getElementById('saveButton_' + project);
            var cancelButton = document.getElementById('cancelButton_' + project);
            var div = document.getElementById('network-popUp_' + project);
            span.innerHTML = "Edit Node";
            //idInput.value = data.id;
            labelInput.value = data.label;
            saveButton.onclick = saveData.bind(this, data, callback);
            cancelButton.onclick = clearPopUp.bind();
            div.style.display = 'block';
        },
        onConnect: function (data, callback) {

            if (data.from == data.to) {
                var r = confirm("Do you want to connect the node to itself?");
                if (r == true) {
                    callback(data);
                }
            }
            else {
                //if (data[data.from].group == "switch" && data[data.to].group == "switch") {
                //    alert("Switch cannot connect another switch!");
                //}
                //if (data[data.from].group == "switch" || data[data.to].group == "switch") {
                //    data.label+"subnet1"
                //}
                //var id=data.from;
                var nodefrom = nodes.get(data.from);
                var nodeto = nodes.get(data.to);
                var obj = {};
                if (nodefrom.label ==  "Internet"){
                    if (nodeto.group == "vrouter"){
                        obj = {};
                        obj["Properties"] = {external_gateway_info: {network:"ext-net"}};
                        //lab_resources.new.push(obj);
                        jQuery.extend(lab_resources[nodeto.label],obj);
                        var myJsonString = JSON.stringify(lab_resources);
                        console.log(myJsonString);
                    }
                    else{
                        alert('Internet can only be connected to a virtual router.');
                        return;
                    }
                }
                if (nodeto.label ==  "Internet"){
                    if (nodefrom.group == "vrouter"){
                        obj = {};
                        obj["Properties"] = {external_gateway_info: {network:"ext-net"}};
                        //lab_resources.new.push(obj);
                        jQuery.extend(lab_resources[nodefrom.label],obj);
                        var myJsonString = JSON.stringify(lab_resources);
                        console.log(myJsonString);
                    }
                    else{
                        alert('Only virtual routers can be connected to internet.');
                        return;
                    }
                }
                if (nodefrom.group ==  "switch"){
                    if (nodeto.group == "vrouter"){
                        obj = {};
                        obj[nodeto.label+"interface"+routercount] = {Type:"OS::Neutron::RouterInterface",Properties:{router_id:{Ref:nodeto.label},subnet:{Ref:nodefrom.label+"subnet0"}}};
                        //lab_resources.new.push(obj);
                        jQuery.extend(lab_resources,obj);
                        var myJsonString = JSON.stringify(lab_resources);
                        console.log(myJsonString);
                        routercount= routercount+1;
                    }
                    if (nodeto.group == "vm"){
                        obj = {};
                        obj[nodeto.label+"port"+portcount] = {Type:"OS::Neutron::Port",Properties:{fixed_ips:[{subnet_id:{Ref:nodefrom.label+"subnet0"}}],network:{Ref:nodefrom.label}}};
                        //lab_resources.new.push(obj);
                        jQuery.extend(lab_resources,obj);
                        obj = {};
                        obj["port"]={Ref:nodeto.label+"port"+portcount};
                        lab_resources[nodeto.label].Properties.networks.push(obj);
                        var myJsonString = JSON.stringify(lab_resources);
                        console.log(myJsonString);
                        portcount= portcount+1;
                    }
                    else{
                        if (nodeto.group != "vrouter") {
                            alert('A switch can only be connected to either virtual routers or virtual machines!');
                            return;
                        }
                    }
                }
                if (nodeto.group ==  "switch"){
                    if (nodefrom.group == "vrouter"){
                        obj = {};
                        obj[nodefrom.label+"interface"+routercount] = {Type:"OS::Neutron::RouterInterface",Properties:{router_id:{Ref:nodeto.label},subnet:{Ref:nodeto.label+"subnet0"}}};
                        //lab_resources.new.push(obj);
                        jQuery.extend(lab_resources,obj);
                        var myJsonString = JSON.stringify(lab_resources);
                        console.log(myJsonString);
                        routercount= routercount+1;
                    }
                    if (nodefrom.group == "vm"){
                        obj = {};
                        obj[nodefrom.label+"port"+portcount] = {Type:"OS::Neutron::Port",Properties:{fixed_ips:[{subnet_id:{Ref:nodeto.label+"subnet0"}}],network:{Ref:nodeto.label}}};
                        //lab_resources.new.push(obj);
                        jQuery.extend(lab_resources,obj);
                        obj = {};
                        obj["port"]={Ref:nodefrom.label+"port"+portcount};
                        lab_resources[nodefrom.label].Properties.networks.push(obj);
                        var myJsonString = JSON.stringify(lab_resources);
                        console.log(myJsonString);
                        portcount= portcount+1;
                    }
                    else{
                        if (nodefrom.group != "vrouter"){
                            alert('Only virtual routers and virtual machines can be connected to a switch!');
                        return;
                        }
                    }
                }
                if (nodefrom.group == "vrouter"){
                    if(nodeto.group == "vm") {
                        alert('A virtual router can only be connected to internet or switches!');
                        return;
                    }
                }
                if (nodeto.group == "vrouter"){
                    if(nodefrom.group == "vm") {
                        alert('A virtual machine can only be connected to switches!');
                        return;
                    }
                }
                if (nodefrom.group == "vm"){
                    if(nodeto.label == "Internet") {
                        alert('A virtual machine can only be connected to switches!');
                        return;
                    }
                }
                if (nodefrom.group == nodeto.group) {

                    alert('Same type of nodes can not be connected together!');
                    return;

                }
            //    var edgeexist = 0;
            //    edges.forEach(function(edge,nodefrom,nodeto){
            //            if(((edges.from == nodefrom.label) && (edges.to == nodeto.label))||((edges.to == nodefrom.label) && (edges.from == nodeto.label))){
            //            edgeexist =1;
            //            }
            //    }
            //);
            //    if(edgeexist == 1){
            //        edgeexist = 0;
            //        alert('Only one edge is allowed between two nodes!');
            //        return;
            //    }


                callback(data);
            }
        }
    };

    design_network = new vis.Network(container, lab_design_data, options);

    // add event listeners
    design_network.on('select', function (params) {
        document.getElementById('selection_' + project).innerHTML = 'Selection: ' + params.nodes;
    });

    design_network.on("resize", function (params) {
        console.log(params.width, params.height)
    });

    //var canvas = document.querySelector('canvas');
    //fitToContainer(canvas);
    //
    //function fitToContainer(canvas){
    //    // Make it visually fill the positioned parent
    //    canvas.style.width ='100%';
    //    canvas.style.height='100%';
    //    // ...then set the internal size to match
    //    canvas.width  = canvas.offsetWidth;
    //    canvas.height = canvas.offsetHeight;
    //}

    function clearPopUp() {
        var saveButton = document.getElementById('saveButton_' + project);
        var cancelButton = document.getElementById('cancelButton_' + project);
        saveButton.onclick = null;
        cancelButton.onclick = null;
        var div = document.getElementById('network-popUp_' + project);
        div.style.display = 'none';
    }

    function saveData(data, callback) {
        //var idInput = document.getElementById('node-id_' + project);
        var labelInput = document.getElementById('node-label_' + project);
        var div = document.getElementById('network-popUp_' + project);
        var node_type = document.getElementById('node-type_' + project).value;
        var obj = {};
        //data.id = idInput.value;
        data.label = labelInput.value;
        data.id =labelInput.value;
        data.shape = 'image';

        if (nodes.get(data.label) !== null){
            alert('Node with same name already exist, please use another name!');
            return;
        }
        if (node_type == '0') {
            alert('Please select a node type!');
            return;
        }
        if (node_type == 'vm') {
            data.image = DIR + 'network_terminal.png';
            var node_type_image = document.getElementById('node-type-vm-image_' + project).value;
            var node_type_flavor = document.getElementById('node-type-vm-flavor_' + project).value;
            if (node_type_image == '0' || node_type_flavor == '0') {
                alert('Please select image and flavor!');
                return;
            }
            data.group = 'vm';
            obj = {};
            obj[data.label] = {Type:"OS::Nova::Server",Properties:{flavor:node_type_flavor,image:node_type_image,key_name:{Ref:"KeyPair"},name:data.label,networks:[]}};
            //lab_resources.push(obj);
            jQuery.extend(lab_resources,obj);
        } else if (node_type == 'switch') {
            var node_type_sw_subnets = document.getElementById('node-type-sw-subnets_' + project).value;
            data.image = DIR + 'network_switch.png';
            data.group = 'switch';
            obj = {};
            obj[data.label] = {Type:"OS::Neutron::Net", Properties:{admin_state_up:"true", name: data.label}};
            //lab_resources.push(obj);
            jQuery.extend(lab_resources,obj);
            var subnets = node_type_sw_subnets.split(',');
            if (subnets.length == 0) {
                alert('Please input subnet !');
                return;
            }
            for (var i=0; i<subnets.length; ++i){
                obj= {};
                obj[data.label+"subnet"+i.toString()] = {Type:"OS::Neutron::Subnet",Properties:{cidr:subnets[i],network:{Ref:data.label}}};
                //lab_resources.push(obj);
                jQuery.extend(lab_resources,obj);
            }

        } else if (node_type == 'router') {
            data.image = DIR + 'network_router.png';
            var node_type_router = document.getElementById('node-type-router_' + project).value;
            if (node_type_router == '0') {
                alert('Please select a router type!');
                return;
            }
            data.group = node_type_router;
            if (node_type_router == 'quagga') {
                obj = {};
                obj[data.label] = {Type:"OS::Nova::Server",Properties:{flavor:"",image:"",key_name:{Ref:"KeyPair"},name:data.label,networks:[]}};
                //lab_resources.push(obj);
                jQuery.extend(lab_resources,obj);
            }
            else if(node_type_router == 'vrouter'){
                obj = {};
                obj[data.label] = {Type:"OS::Neutron::Router"};
                //lab_resources.push(obj);
                jQuery.extend(lab_resources,obj);

            }
        }
        clearPopUp();
        callback(data);
    }
}
function populate_lab_design(winId, win_main){
    var tabs = {
        tabId: ['lab_design_new'],
        tabName: ['Drawing']
    };


    create_tabs(winId, win_main, tabs, null);
    prepare_lab_design('#lab_design_new');
    setTimeout(function () {
                     lab_design('');
                }, 500);

        }

function populate_lab_template(winId){
    $('<button class="btn_create_temp" name="New Design Template" value="temp_design">Create New Template</button><br />').appendTo($('#lab_templates'));
    var table = $(document.createElement('table')).appendTo($('#lab_templates'));
    table.addClass("data").attr("id", "tbl_lab_templates").append('<thead><tr>' +
    '<th class="shrink">&nbsp;</th>' + '' +
    '<th>Template Name</th>' +
    '<th>Description</th>' +
    '<th>Template ID</th>' +
    '<th class="hidden">ID</th>' +
    '<th>Actions</th>' +
    '</tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    $.getJSON("/cloud/getTemplate", function (jsondata) {
        $.each(jsondata, function (index, item) {
            $('<tr>' +
            '<td><img src="' + ICON_folder_sm + '"></img></td>' +
            '<td>' + item.temp_name + '</td>' +
            '<td>' + item.temp_des + '</td>' +
            '<td>' + item.temp_id + '</td>' +
            '<td><button type="button" class="temp_deploy" >Deploy</button> <button type="button" name="Edit Design Template" value="temp_design' + item.temp_id +
            '" class="temp_edit">Edit</button> <button type="button" class="temp_share">Share</button> <button type="button" class="temp_copy" >Copy</button> <button type="button" class="temp_delete" >Delete</button></td>' +
            '</tr>').appendTo(tbody);
        });

        $(winId).find('div.window_bottom')
            .text(jsondata.length + ' Templates');

    });
}

function template_list_update(tabId, action) {
    var tbody = $('#' + tabId).closest('div.tabs').find('#tbl_' + tabId).find('tbody');

    if (action == 'new') {
        $('<tr class="active">' +
        '<td><img src="' + ICON_project + '"></img></td>' +
        '<td>' + item.temp_name + '</td>' +
        '<td>' + item.temp_des + '</td>' +
        '<td>' + item.temp_id + '</td>' +
        '<td><button type="button" class="temp_deploy" >Deploy</button> <button type="button" name="Edit Design Template" value="temp_design' + item.temp_id +
        '" class="temp_edit">Edit</button> <button type="button" class="temp_share">Share</button> <button type="button" class="temp_copy" >Copy</button> <button type="button" class="temp_delete" >Delete</button></td>' +
        '</tr>').prependTo(tbody);
        //'</tr>').appendTo(tbody);
        //tbody.closest('div.window_content')
        //    .animate({scrollTop: tbody.closest('div.window_content').get(0).scrollHeight}, 2000);
    } else if (action == 'update') {

    }
}

function share_temp(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'temp_share').attr('title', 'Share Template with Users');

    var form_html = '<input class="hidden" name="got_template_id" value="'+element.parent().prev().text()+'">' +
        '<label>User Name:</label>' +
        '<input name="search_share_user_txt">' +
        '<button id="share_temp_user_btn">Search</button>' +
        '<hr/>' +
        '<table class="data" id="search_share_user_table">' +
        '<tr><th>User</th><th>Institute</th><th>Org ID</th><th>Action</th></tr>' +
            //'<tr><td>Yuli</td><td>asu</td><td>123</td><td><input type="button" value="Invite"></td></tr>' +
            //'<tr><td>James</td><td>asu</td><td>456</td><td><input type="button" value="Invite"></td></tr>' +
        '</table></div>';
    $(form_html).appendTo(dlg_form);

    $('#temp_share').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            $(this).remove();
        }
    });

}
function share_user_btn_temp() {
    $.post("/cloud/searchAllUser", {
            "search_user_txt": $("input[name=search_share_user_txt]").val()

        },
        function (data) {
            //alert(JSON.stringify(data));
            display_share_user_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}
function display_share_user_table(jsondata) {
    var table = $("#search_share_user_table");
    table.empty();
    table.append("<tr><th>User</th><th>Institute</th><th>Org ID</th><th>Action</th></tr>");
    //alert("send ajax for own group list0");
    $.each(jsondata, function (index, item) {
        //alert(item.userid);
        if (index == 'users') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td>' + item3.email + '</td><td>' + item3.institute + '</td><td>' + item3.org_id + '</td><td><button class="btn-Share" >Share</button></td></tr>');

            });
        }
    });
}
function btn_share(element){
    var user_email = element.parent().prev().prev().prev().text();
    var temp_id= element.parent().parent().parent().parent().prev().prev().prev().prev().prev().val();
    $.post("/cloud/shareTemp", {
            "user_email": user_email,
            "temp_id": temp_id

        },
        function (data) {
            alert(JSON.stringify(data));
           // display_share_user_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}
function btn_copy(element){
    var user_email = element.parent().prev().prev().prev().text();
    var temp_id= element.parent().parent().parent().parent().prev().prev().prev().prev().prev().val();
    $.post("/cloud/copyTemp", {
            "user_email": user_email,
            "temp_id": temp_id

        },
        function (data) {
            alert(JSON.stringify(data));
            // display_share_user_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}
function copy_temp(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'temp_copy').attr('title', 'Copy Template with Users');

    var form_html = '<input class="hidden" name="got_template_id" value="'+element.parent().prev().text()+'">' +
        '<label>User Name:</label>' +
        '<input name="search_copy_user_txt">' +
        '<button id="copy_temp_user_btn">Search</button>' +
        '<hr/>' +
        '<table class="data" id="search_copy_user_table">' +
        '<tr><th>User</th><th>Institute</th><th>Org ID</th><th>Action</th></tr>' +
            //'<tr><td>Yuli</td><td>asu</td><td>123</td><td><input type="button" value="Invite"></td></tr>' +
            //'<tr><td>James</td><td>asu</td><td>456</td><td><input type="button" value="Invite"></td></tr>' +
        '</table></div>';
    $(form_html).appendTo(dlg_form);

    $('#temp_copy').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            $(this).remove();
        }
    });

}
function copy_user_btn_temp() {
    $.post("/cloud/searchAllUser", {
            "search_user_txt": $("input[name=search_copy_user_txt]").val()

        },
        function (data) {
            //alert(JSON.stringify(data));
            display_copy_user_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}
function display_copy_user_table(jsondata) {
    var table = $("#search_copy_user_table");
    table.empty();
    table.append("<tr><th>User</th><th>Institute</th><th>Org ID</th><th>Action</th></tr>");
    //alert("send ajax for own group list0");
    $.each(jsondata, function (index, item) {
        //alert(item.userid);
        if (index == 'users') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td>' + item3.email + '</td><td>' + item3.institute + '</td><td>' + item3.org_id + '</td><td><button class="btn-Copy" >Copy</button></td></tr>');

            });
        }
    });
}
function verify_lab_template(){
    $.post("/cloud/verifyHeatTemp", {
            //"_token": $(this).find('input[name=_token]').val(),
            "template":  JSON.stringify(lab_template)
        },
        function (data) {
            alert(JSON.stringify(data));
            // display_share_user_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}

function delete_temp(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'temp_delete').attr('title', 'Delete Template');

    var form_html = '<input class="hidden" name="got_template_id" value="' + element.parent().prev().text() + '">' +
        '<label>Are you sure you want to delete this template?</label>' +
        '<hr/><hr/><td><button class="btn-Delete" >Delete!</button></td>';
    $(form_html).appendTo(dlg_form);

    $('#temp_delete').dialog({
        modal: true,
        height: 100,
        overflow: "auto",
        width: 350,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}
function btn_delete(element){
    var temp_id= element.prev().prev().prev().prev().val();
    $.post("/cloud/deleteTemp", {
            "temp_id": temp_id

        },
        function (data) {
            alert(JSON.stringify(data));
            // display_share_user_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}
function deploy_temp(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'temp_copy').attr('title', 'Copy Template with Users');

    var form_html = '<input class="hidden" name="got_template_id" value="'+element.parent().prev().text()+'">' +
        '<label>Project Name:</label>' +
        '<input name="deploy_user_name_txt">' +
        '<hr/>' +
        '<button class="btn-Deploy">Deploy</button>' +
        '<hr/>' ;
    $(form_html).appendTo(dlg_form);

    $('#temp_copy').dialog({
        modal: true,
        height: 500,
        overflow: "auto",
        width: 700,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}
function btn_deploy(element) {
    var temp_id = element.prev().prev().prev().prev().val();
    $.post("/cloud/createProject", {
            //$(this).prop('action'), {
            //"_token": $(this).find('input[name=_token]').val(),
            "project_name": $("input[name=deploy_user_name_txt]").val(),
            "project_desc": "Project Created from Template"+temp_id
        },
        function (data) {
            if (data.status == "Success") {
                alert(data.msg);
                element.closest('.dialog').dialog('close');

            }
            else {
                alert(data.msg);
            }
        },
        'json'
    )
        //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
        .fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}