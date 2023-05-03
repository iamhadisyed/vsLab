/**
 * Created by root on 2/10/15.
 */

var lab_resources = {};
//var lab_template = { "AWSTemplateFormatVersion": "2010-09-09", "Resources": lab_resources };


function prepare_lab_design(tab_div, windowid) {
    var project = windowid.substring('#window_temp_design'.length);
    var popup_div = $(document.createElement('div')).appendTo($(tab_div + project));
    popup_div.addClass('addNode-popUp').attr('id', 'network-popUp_' + project)
        .css('top', '200px').css('left', '150px').css('width', '450px').css('height', '250px');
    $('<span id="operation_' + project + '" style="font-size:20px;">node</span> <br>' +
    '<table style="margin:auto;">' +
        //'<tr><td>id</td><td><input id="node-id_' + project + '" value="new value"></td></tr>' +
    '<tr><td>Type</td><td><select id="node-type_' + project + '">' + '<option value="0">Select a Resource...</option>' +
    '<option value="vm">VM</option><option value="switch">Switch</option><option value="router">Router</option></select><br />' +
    '<tr><td>Settings</td><td>' +
    '<select style="display:none;" id="node-type-vm-image_' + project + '"><option value="0">Select a image...</option></select>' +
        //'<select style="display:none;" id="node-type-vm-flavor_' + project + '"><option value="0">Select a flavor...</option></select>' +
    '<select style="display:none;" id="node-type-router_' + project + '"><option value="0">Select a router type...</option>' +
    '<option value="quagga">Quagga</option><option value="vrouter">Virtual Router</option></select>' +
    '<input style="display:none;" type="text" id="node-type-sw-subnets_' + project + '" placeholder="192.168.1.0/24, 192.168.2.0/24" />' +
    '</td></tr>' +
    '<tr><td>Name</td><td><input id="node-label_' + project + '" value="new value"> </td></tr></table>' +
    '<input type="button" value="Save" id="saveButton_' + project + '"></button>' +
    '<input type="button" value="Cancel" id="cancelButton_' + project + '"></button></div>').appendTo(popup_div);

    var table = $(document.createElement('table')).appendTo($(tab_div + project));
    table.attr("class", "data").css('width', '100%').append('<thead><tr><th></th></tr></thead>');
    var table_head = table.find('th');

    $('<span id="selection_' + project + '">Selection: None</span>').css('float', 'left').appendTo(table_head);
    if (project != "new") {
        $('<span id="temp_name">Template ID: ' + project + '</span>').css('float', 'left').css('margin-left', '30%').appendTo(table_head);
    }
    //var newbtn1 = $(document.createElement('button')).appendTo(table_head);
    //newbtn1.addClass("dialog-btn").attr('id', 'btn_verify_design_' + project).css('float', 'right').text('Verify Design');
    var newbtn2 = $(document.createElement('button')).appendTo(table_head);
    if (project == "new") {
        newbtn2.addClass("dialog-btn").attr('id', 'btn_save_design_' + project).css('float', 'right').text('Save Design');
    } else {
        newbtn2.addClass("dialog-btn").attr('id', 'btn_save_design_' + project).css('float', 'right').text('Update Design');
    }
    var mynetwork_div = $(document.createElement('div')).appendTo($(tab_div + project));
    mynetwork_div.attr('id', 'lab_design_div_' + project).css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');
    //var display_nodes = $(document.createElement('textarea')).appendTo($(tab_div + project));
    //display_nodes.attr('id', 'json_nodes').css('width','100%').css('height', '100px');
    //var display_edges = $(document.createElement('textarea')).appendTo($(tab_div + project));
    //display_edges.attr('id', 'json_edges').css('width','100%').css('height', '100px');
}

function prepare_lab_design_for_open(tab_div, temp_id) {
    var project = temp_id;
    var popup_div = $(document.createElement('div')).appendTo($(tab_div + project));
    popup_div.addClass('addNode-popUp').attr('id', 'network-popUp_' + project)
        .css('top', '200px').css('left', '150px').css('width', '450px').css('height', '250px');
    $('<span id="operation_' + project + '" style="font-size:20px;">node</span> <br>' +
    '<table style="margin:auto;">' +
        //'<tr><td>id</td><td><input id="node-id_' + project + '" value="new value"></td></tr>' +
    '<tr><td>Type</td><td><select id="node-type_' + project + '">' + '<option value="0">Select a Resource...</option>' +
    '<option value="vm">VM</option><option value="switch">Switch</option><option value="router">Router</option></select><br />' +
    '<tr><td>Settings</td><td>' +
    '<select style="display:none;" id="node-type-vm-image_' + project + '"><option value="0">Select a image...</option></select>' +
        //'<select style="display:none;" id="node-type-vm-flavor_' + project + '"><option value="0">Select a flavor...</option></select>' +
    '<select style="display:none;" id="node-type-router_' + project + '"><option value="0">Select a router type...</option>' +
    '<option value="quagga">Quagga</option><option value="vrouter">Virtual Router</option></select>' +
    '<input style="display:none;" type="text" id="node-type-sw-subnets_' + project + '" placeholder="192.168.1.0/24, 192.168.2.0/24" />' +
    '</td></tr>' +
    '<tr><td>Name</td><td><input id="node-label_' + project + '" value="new value"> </td></tr></table>' +
    '<input type="button" value="Save" id="saveButton_' + project + '"></button>' +
    '<input type="button" value="Cancel" id="cancelButton_' + project + '"></button></div>').appendTo(popup_div);

    var table = $(document.createElement('table')).appendTo($(tab_div + project));
    table.attr("class", "data").css('width', '100%').append('<thead><tr><th></th></tr></thead>');
    var table_head = table.find('th');

    $('<span id="selection_' + project + '">Selection: None</span>').css('float', 'left').appendTo(table_head);
    if (project != "new") {
        $('<span id="temp_name">Template ID: ' + project + '</span>').css('float', 'left').css('margin-left', '30%').appendTo(table_head);
    }
    //var newbtn1 = $(document.createElement('button')).appendTo(table_head);
    //newbtn1.addClass("dialog-btn").attr('id', 'btn_verify_design_' + project).css('float', 'right').text('Verify Design');
    //var newbtn2 = $(document.createElement('button')).appendTo(table_head);
    //if (project == "new") {
    //    newbtn2.addClass("dialog-btn").attr('id', 'btn_save_design_' + project).css('float', 'right').text('Save Design');
    //} else {
    //    newbtn2.addClass("dialog-btn").attr('id', 'btn_save_design_' + project).css('float', 'right').text('Update Design');
    //}
    var mynetwork_div = $(document.createElement('div')).appendTo($(tab_div + project));
    mynetwork_div.attr('id', 'lab_design_div_' + project).css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');
    //var display_nodes = $(document.createElement('textarea')).appendTo($(tab_div + project));
    //display_nodes.attr('id', 'json_nodes').css('width','100%').css('height', '100px');
    //var display_edges = $(document.createElement('textarea')).appendTo($(tab_div + project));
    //display_edges.attr('id', 'json_edges').css('width','100%').css('height', '100px');
}

var design_network = {};
var lab_design_json = {};
var lab_design_data = {};
var portcount = {};
var routercount = {};
var vmcount={};
function lab_design(windowid) {
    var nodes = new vis.DataSet();
    var edges = new vis.DataSet();
    var project = windowid.substring('#window_temp_design'.length);

    vmcount[project] = 0;
    routercount[project] = 0;
    portcount[project] = 0;
    nodes.clear();
    edges.clear();
    nodes.on('*', function () {
        $('#json_nodes').html(JSON.stringify(nodes.get(), null, 4));
    });
    edges.on('*', function () {
        $('#json_edges').html(JSON.stringify(edges.get(), null, 4));
    });

    lab_design_data[project] = {nodes: nodes, edges: edges};

    var DIR = 'workspace-assets/images/icons/';
    var LENGTH_MAIN = 150;
    var LENGTH_SUB = 50;
    var temp = {};
    var design = {};
    if (project !== 'new') {
        $.getJSON("/cloud/getDesign/" + project, function (jsondata) {
            temp = JSON.parse(jsondata.temp);
            design = JSON.parse(jsondata.temp_vis);
            lab_resources[project] = {};
            jQuery.extend(lab_resources[project], temp);
            nodes.add(design.nodes);
            edges.add(design.edges);
            for (vm in lab_resources[project]) {
                if (lab_resources[project][vm].Type == "OS::Neutron::Router") {
                    routercount[project] = routercount[project] + 1;
                }
                if (lab_resources[project][vm].Type == "OS::Nova::Server") {
                    vmcount[project] = vmcount[project] + 1;
                    if (!jQuery.isEmptyObject(lab_resources[project][vm].Properties.networks)) {
                        portcount[project] = portcount[project] + lab_resources[project][vm].Properties.networks.length;
                    }
                }
            }
        });
    } else {
        lab_resources['new'] = {};
        nodes.add({id: "Internet", label: 'Internet', shape: 'image', image: DIR + 'System-Globe-icon.png'});
    }

    //var keyobj = {KeyPair:{Type:"OS::Nova::KeyPair",Properties:{name:"keypair",save_private_key:"true"}}};
    ////lab_resources.push();
    //lab_resources[project]={};
    //jQuery.extend(lab_resources[project],keyobj);

    var container = document.getElementById('lab_design_div_' + project);
    var canvas_height = parseInt($('#lab_design_div_' + project).closest('div.window_inner').css('height'));
    container.style.height = canvas_height - 163 + 'px';
    container.style.minHeight = '300px';
    var options = {
        interaction: {
            navigationButtons: true,
            hover: true

        },
        nodes: {
            borderWidthSelected: 2
        },

        //stabilize: false,
        manipulation: {
            addNode: function (data, callback) {
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
            //editNode: function (data, callback) {
            //    var span = document.getElementById('operation_' + project);
            //    //var idInput = document.getElementById('node-id_' + project);
            //    var labelInput = document.getElementById('node-label_' + project);
            //    var saveButton = document.getElementById('saveButton_' + project);
            //    var cancelButton = document.getElementById('cancelButton_' + project);
            //    var div = document.getElementById('network-popUp_' + project);
            //    span.innerHTML = "Edit Node";
            //    //idInput.value = data.id;
            //    labelInput.value = data.label;
            //    saveButton.onclick = saveData.bind(this, data, callback);
            //    cancelButton.onclick = clearPopUp.bind();
            //    div.style.display = 'block';
            //},
            deleteNode: function (data, callback) {
                //alert(JSON.stringify(data));
                var node = nodes.get(data.nodes[0]);
                var edge = {};
                for (var i = 1; i <= data.edges.length; i++) {
                    edge[i - 1] = edges.get(data.edges[i - 1]);
                }
                if (JSON.stringify(node) !== JSON.stringify(nodes.get())) {
                    for (value in lab_resources[project]) {
                        if (value == node.id) {
                            delete lab_resources[project][value];
                        }
                    }
                    if (node.group == "vrouter") {
                        routercount[project] = 0;
                        for (value in lab_resources[project]) {
                            if (value.substring(0, node.id.length + 'interface'.length) == node.id + 'interface') {
                                delete lab_resources[project][value];
                            }
                        }
                    } else if (node.group == "vm" || node.group == "quagga") {
                        vmcount[project] = vmcount[project] - 1;
                        for (value in lab_resources[project]) {
                            if (value.substring(0, node.id.length + 'port'.length) == node.id + 'port') {
                                delete lab_resources[project][value];
                            }
                        }
                    } else if (node.label == "Internet") {
                        alert("Internet can not be deleted!");
                        return;
                    } else if (node.group == "switch") {
                        for (value in lab_resources[project]) {
                            if (value.substring(0, node.id.length + 'subnet'.length) == node.id + 'subnet') {
                                for (vm in lab_resources[project]) {
                                    if (lab_resources[project][vm].Type == "OS::Nova::Server") {
                                        if (!jQuery.isEmptyObject(lab_resources[project][vm].Properties.networks)) {
                                            for (var j = 0; j < lab_resources[project][vm].Properties.networks.length; j++) {
                                                var port = lab_resources[project][vm].Properties.networks[j].port.Ref;
                                                if (lab_resources[project][port].Properties.network.Ref == node.id) {
                                                    lab_resources[project][vm].Properties.networks.splice(j, 1);
                                                    delete lab_resources[project][port];
                                                }
                                            }
                                        }
                                    }
                                }
                                delete lab_resources[project][value];
                            }
                        }
                    }
                } else {
                    //alert(JSON.stringify(edge[0]));
                    var from = nodes.get(edge[0].from);
                    var to = nodes.get(edge[0].to);
                    if (from.label == "Internet") {
                        delete lab_resources[project][to.id].Properties;
                    } else if (to.group == "vrouter") {
                        if (from.group == "switch") {
                            for (value in lab_resources[project]) {
                                if (value.substring(0, to.id.length + 'interface'.length) == to.id + 'interface') {
                                    delete lab_resources[project][value];
                                }
                            }
                        }
                    }
                    if (to.label == "Internet") {
                        delete lab_resources[project][from.id].Properties;
                    } else if (from.group == "vrouter") {
                        if (to.group == "switch") {
                            for (value in lab_resources[project]) {
                                if (value.substring(0, from.id.length + 'interface'.length) == from.id + 'interface') {
                                    delete lab_resources[project][value];
                                }
                            }
                        }
                    }
                    if (from.group == "switch") {
                        if (to.group == "vm" || to.group == "quagga") {
                            for (var l = 0; l < lab_resources[project][to.id].Properties.networks.length; l++) {
                                var ports = lab_resources[project][to.id].Properties.networks[l].port.Ref;
                                if (lab_resources[project][ports].Properties.network.Ref == from.id) {
                                    lab_resources[project][to.id].Properties.networks.splice(l, 1);
                                    delete lab_resources[project][ports];
                                }
                            }
                        }
                    }
                    if (to.group == "switch") {
                        if (from.group == "vm" || from.group == "quagga") {
                            for (var l = 0; l < lab_resources[project][from.id].Properties.networks.length; l++) {
                                var ports = lab_resources[project][from.id].Properties.networks[l].port.Ref;
                                if (lab_resources[project][ports].Properties.network.Ref == from.id) {
                                    lab_resources[project][from.id].Properties.networks.splice(l, 1);
                                    delete lab_resources[project][ports];
                                }
                            }
                        }
                    }
                }
                callback(data);

            },
            deleteEdge: function (data, callback) {
                var edge = edges.get(data.edges[0]);
                var nodefrom = nodes.get(edge.from);
                var nodeto = nodes.get(edge.to);
                if (nodefrom.group == "switch" && nodeto.label == "vm") {
                    for (value in lab_resources[project]) {
                        if (value.substring(0, nodeto.id.length + 'port'.length) == nodeto.id + 'port') {

                            delete lab_resources[project][value];
                        }
                        if (value == nodeto.id) {

                            delete lab_resources[project][value].Properties.networks;
                        }
                    }
                } else if (nodeto.group == "switch" && nodefrom.label == "vm") {
                    for (value in lab_resources[project]) {
                        if (value.substring(0, nodefrom.id.length + 'port'.length) == nodefrom.id + 'port') {

                            delete lab_resources[project][value];
                        }
                        if (value == nodefrom.id) {

                            delete lab_resources[project][value].Properties.networks;
                        }

                    }
                }
                if (nodefrom.group == "vrouter" && nodeto.label == "Internet") {
                    for (value in lab_resources[project]) {
                        if (value == nodefrom.id) {

                            delete lab_resources[project][value].Properties;
                        }

                    }
                } else if (nodeto.group == "vrouter" && nodefrom.label == "Internet") {
                    for (value in lab_resources[project]) {
                        if (value == nodeto.id) {

                            delete lab_resources[project][value].Properties;
                        }

                    }
                }
                if (nodefrom.group == "vrouter" && nodeto.group == "switch") {
                    for (value in lab_resources[project]) {
                        if (value.substring(0, nodefrom.id.length + 'interface'.length) == nodefrom.id + 'interface') {
                            if (lab_resources[project][value].Properties.subnet.Ref.substring(0, nodeto.id.length + 'subnet0'.length) == nodeto.id + 'subnet0') {
                                delete lab_resources[project][value];
                            }
                        }
                    }
                } else if (nodeto.group == "vrouter" && nodefrom.group == "switch") {
                    for (value in lab_resources[project]) {
                        if (value.substring(0, nodeto.id.length + 'interface'.length) == nodeto.id + 'interface') {
                            if (lab_resources[project][value].Properties.subnet.Ref.substring(0, nodefrom.id.length + 'subnet0'.length) == nodefrom.id + 'subnet0') {
                                delete lab_resources[project][value];
                            }
                        }
                    }
                }
                callback(data);
            },

            addEdge: function (data, callback) {

                if (data.from == data.to) {
                    var r = confirm("Do you want to connect the node to itself?");
                    if (r == true) {
                        callback(data);
                    }
                }
                else {
                    var nodefrom = nodes.get(data.from);
                    var nodeto = nodes.get(data.to);
                    var obj = {};
                    if (nodefrom.label == "Internet") {
                        if (nodeto.group == "vrouter") {
                            obj = {};
                            obj["Properties"] = {external_gateway_info: {network: "ext-net"}};
                            //lab_resources.new.push(obj);
                            jQuery.extend(lab_resources[project][nodeto.label], obj);
                            var myJsonString = JSON.stringify(lab_resources[project]);
                            console.log(myJsonString);
                        }
                        else {
                            alert('Internet can only be connected to a virtual router.');
                            return;
                        }
                    }
                    if (nodeto.label == "Internet") {
                        if (nodefrom.group == "vrouter") {
                            obj = {};
                            obj["Properties"] = {external_gateway_info: {network: "ext-net"}};
                            //lab_resources.new.push(obj);
                            jQuery.extend(lab_resources[project][nodefrom.label], obj);
                            var myJsonString = JSON.stringify(lab_resources[project]);
                            console.log(myJsonString);
                        }
                        else {
                            alert('Only virtual routers can be connected to internet.');
                            return;
                        }
                    }
                    if (nodefrom.group == "switch") {
                        if (nodeto.group == "vrouter") {
                            obj = {};
                            obj[nodeto.label + "interface" + routercount] = {
                                Type: "OS::Neutron::RouterInterface",
                                Properties: {router_id: {Ref: nodeto.label}, subnet: {Ref: nodefrom.label + "subnet0"}}
                            };
                            //lab_resources.new.push(obj);
                            jQuery.extend(lab_resources[project], obj);
                            var myJsonString = JSON.stringify(lab_resources[project]);
                            console.log(myJsonString);
                            routercount = routercount + 1;
                        }
                        if (nodeto.group == "vm" || nodeto.group == "quagga") {
                            obj = {};
                            obj[nodeto.label + "port" + portcount[project]] = {
                                Type: "OS::Neutron::Port",
                                Properties: {
                                    fixed_ips: [{subnet_id: {Ref: nodefrom.label + "subnet0"}}],
                                    network: {Ref: nodefrom.label}
                                }
                            };
                            //lab_resources.new.push(obj);
                            jQuery.extend(lab_resources[project], obj);
                            obj = {};
                            obj["port"] = {Ref: nodeto.label + "port" + portcount[project]};
                            lab_resources[project][nodeto.label].Properties.networks.push(obj);
                            var myJsonString = JSON.stringify(lab_resources[project]);
                            console.log(myJsonString);
                            portcount[project] = portcount[project] + 1;
                        }
                        else {
                            if (nodeto.group != "vrouter") {
                                alert('A switch can only be connected to virtual routers, quagga routers or virtual machines!');
                                return;
                            }
                        }
                    }
                    if (nodeto.group == "switch") {
                        if (nodefrom.group == "vrouter") {
                            obj = {};
                            obj[nodefrom.label + "interface" + routercount] = {
                                Type: "OS::Neutron::RouterInterface",
                                Properties: {router_id: {Ref: nodefrom.label}, subnet: {Ref: nodeto.label + "subnet0"}}
                            };
                            //lab_resources.new.push(obj);
                            jQuery.extend(lab_resources[project], obj);
                            var myJsonString = JSON.stringify(lab_resources[project]);
                            console.log(myJsonString);
                            routercount = routercount + 1;
                        }
                        if (nodefrom.group == "vm" || nodefrom.group == "quagga") {
                            obj = {};
                            obj[nodefrom.label + "port" + portcount[project]] = {
                                Type: "OS::Neutron::Port",
                                Properties: {
                                    fixed_ips: [{subnet_id: {Ref: nodeto.label + "subnet0"}}],
                                    network: {Ref: nodeto.label}
                                }
                            };
                            //lab_resources.new.push(obj);
                            jQuery.extend(lab_resources[project], obj);
                            obj = {};
                            obj["port"] = {Ref: nodefrom.label + "port" + portcount[project]};
                            lab_resources[project][nodefrom.label].Properties.networks.push(obj);
                            var myJsonString = JSON.stringify(lab_resources[project]);
                            console.log(myJsonString);
                            portcount[project] = portcount[project] + 1;
                        } else {
                            if (nodefrom.group != "vrouter") {
                                alert('Only virtual routers, quagga routers and virtual machines can be connected to a switch!');
                                return;
                            }
                        }
                    }
                    if (nodefrom.group == "vrouter") {
                        if (nodeto.group == "vm" || nodeto.group == "quagga") {
                            alert('A virtual router can only be connected to internet or switches!');
                            return;
                        }
                    }
                    if (nodeto.group == "vrouter") {
                        if (nodefrom.group == "vm" || nodefrom.group == "quagga") {
                            alert('A virtual machine or Quagga router can only be connected to switches!');
                            return;
                        }
                    }
                    if (nodefrom.group == "vm" || nodefrom.group == "quagga") {
                        if (nodeto.label == "Internet") {
                            alert('A virtual machine or Quagga router can only be connected to switches!');
                            return;
                        }
                    }
                    if (nodefrom.group == "vm" || nodefrom.group == "quagga") {
                        if (nodeto.group == "vm" || nodeto.group == "quagga") {
                            alert('A virtual machine or Quagga router can only be connected to switches!');
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
        }
    };
    design_network[project] = new vis.Network(container, lab_design_data[project], options);

    // add event listeners
    design_network[project].on('select', function (params) {
        document.getElementById('selection_' + project).innerHTML = 'Selection: ' + params.nodes;
    });

    design_network[project].on("resize", function (params) {
        console.log(params.width, params.height)
    });

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
        data.id = labelInput.value;
        data.shape = 'image';

        if (nodes.get(data.label) !== null) {
            alert('Node with same name already exist, please use another name!');
            return;
        }
        if (node_type == '0') {
            alert('Please select a node type!');
            return;
        }
        if (node_type == 'vm') {
            if (vmcount[project] <= 40){
                vmcount[project] = vmcount[project] + 1;
                var node_type_image = document.getElementById('node-type-vm-image_' + project).value;
            var node_type_image_num = document.getElementById('node-type-vm-image_' + project).selectedIndex;
            var node_type_image_name = document.getElementById('node-type-vm-image_' + project)[node_type_image_num].innerHTML;
            var node_type_flavor = "";//document.getElementById('node-type-vm-flavor_' + project).value;
            if (node_type_image == '0') {
                alert('Please select image!');
                return;
            }
            //alert(node_type_image_name);
            var os_type = node_type_image_name.split("-")[0];
            switch (node_type_image_name) {
                case 'Ubuntu-12.04-4-Desktop-Eng-64Bit-20160701-SEED (80.00 GB)':
                    node_type_flavor = "2ad83de9-f8d3-43e9-9a06-37915c8e3e52";
                    break;
                case 'Ubuntu-14.04-3-Desktop-Eng-64Bit-20180112-clean (15.00 GB)':
                    node_type_flavor = "51a92e52-f801-4b20-8175-af5d0dd8fe5c";
                    break;
                case 'Ubuntu-14.04-3-Server-Eng-64Bit-20160701-clean (10.00 GB)':
                    node_type_flavor = "51a92e52-f801-4b20-8175-af5d0dd8fe5c";
                    break;
                case 'Ubuntu-16.04-2-Desktop-Eng-64Bit-20180112-withSpark (250.00 GB)':
                    node_type_flavor = "b0c8cbab-d95b-4292-bdeb-54a3a95a047a";
                    break;
                case 'Windows-2008-2-Server-Eng-64Bit-20180112-Clean (80.00 GB)':
                    node_type_flavor = "2ad83de9-f8d3-43e9-9a06-37915c8e3e52";
                    break;
                case 'Windows-7-3-Desktop-Eng-64Bit-20180112-PSUIST554Attack (41.00 GB)':
                    node_type_flavor = "bbc61531-831c-4092-9951-d19d081e6e58";
                    break;
                case 'Windows-7-3-Desktop-Eng-64Bit-20180112-PSUIST554Student (41.00 GB)':
                    node_type_flavor = "bbc61531-831c-4092-9951-d19d081e6e58";
                    break;
                case 'redhat6.9 (80.00 GB)':
                    node_type_flavor = "2ad83de9-f8d3-43e9-9a06-37915c8e3e52";
                    break;
                case 'Quagga-Router-Ubuntu-14.04-Server-64-150406 (3.00 GB)':
                    node_type_flavor = "e7743066-f089-4a6a-833e-e43506addfec";
                    break;
                case 'Quagga-Clean-Router-Ubuntu-14.04-Server-64-150406 (3.00 GB)':
                    node_type_flavor = "e7743066-f089-4a6a-833e-e43506addfec";
                    break;
                case 'Windows-10-Fusion360-QXL (41.00 GB)':
                    node_type_flavor = "288c29cf-53b1-48a4-80e5-80b4dac232c4";
                    break;
                case 'Ubuntu-20.04-GUI (41.00 GB)':
                    node_type_flavor = "d6a50d9d-0a07-4617-950e-2172c53e47e4";
                    break;
                case 'Kali-20201b (38.00 GB)':
                    node_type_flavor = "d6a50d9d-0a07-4617-950e-2172c53e47e4";
                    break;
                case 'nessus (50.00 GB)':
                    node_type_flavor = "d6a50d9d-0a07-4617-950e-2172c53e47e4";
                    break;
                default:
                    node_type_flavor = "bbc61531-831c-4092-9951-d19d081e6e58";
            }
            var icon = "";
            //if (item.name.substring(0, 1) == 'R') {
            switch (os_type) {
                case 'Quagga':
                    icon = "workspace-assets/images/icons/network_router.png";

                    break;
                case 'Ubuntu':

                    icon = "workspace-assets/images/icons/terminal-ubuntu.png";

                    break;
                case 'SEED':

                    icon = "workspace-assets/images/icons/terminal-ubuntu.png";

                    break;
                case 'Windows':

                    icon = "workspace-assets/images/icons/terminal-windows.png";


                    break;
                case 'Win7new (40.00 GB)':

                    icon = "workspace-assets/images/icons/terminal-windows.png";


                    break;
                case 'Redhat':

                    icon = "workspace-assets/images/icons/terminal-redhat.png";


                    break;
                case 'CentOS':

                    icon = "workspace-assets/images/icons/terminal-centos.png";

                    break;
                case 'Fedora':

                    icon = "workspace-assets/images/icons/terminal-fedora.png";

                    break;
                case 'Debian':

                    icon = "workspace-assets/images/icons/terminal-debian.png";

                    break;
                case 'Suse':

                    icon = "workspace-assets/images/icons/terminal-suse.png";

                    break;
                case 'NetBSD':

                    icon = "workspace-assets/images/icons/terminal-netBSD.png";

                    break;
                case 'OpenBSD':

                    icon = "workspace-assets/images/icons/terminal-openBSD.png";


                    break;
                case 'Metasploitable':

                    icon = "workspace-assets/images/icons/terminal-Meta.png";


                    break;
                case 'Kali (15.00 GB)':

                    icon = "workspace-assets/images/icons/terminal-kali.png";

                    break;
                case 'Untangle (5.00 GB)':

                    icon = "workspace-assets/images/icons/terminal-debian.png";

                    break;
                default:

                    icon = "workspace-assets/images/icons/network_terminal.png";


            }
            data.image = icon;
            data.group = 'vm';
            obj = {};
            obj[data.label] = {
                Type: "OS::Nova::Server",
                Properties: {
                    flavor: node_type_flavor,
                    image: node_type_image,
                    //key_name: "KeyPair",
                    name: data.label,
                    networks: []
                }
            };
            //lab_resources.push(obj);
            jQuery.extend(lab_resources[project], obj);
        }

            else
                {
                    alert('You may only have 40 vm nodes.');
                    return;
            }
        }
        else if (node_type == 'switch') {
            var node_type_sw_subnets = document.getElementById('node-type-sw-subnets_' + project).value;
            data.image = DIR + 'network_switch.png';
            data.group = 'switch';
            obj = {};
            obj[data.label] = {Type: "OS::Neutron::Net", Properties: {admin_state_up: "true", name: data.label}};
            //lab_resources.push(obj);
            jQuery.extend(lab_resources[project], obj);
            var subnets = node_type_sw_subnets.split(',');
            var patt = new RegExp("^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/([0-9]|[1-2][0-9]|3[0-2]))$");
            if (subnets.length == 0) {
                alert('Please input subnet address and range in setting!');
                return;
            }else if(subnets[0]==''){
                alert('Please input subnet address and range in setting!');
                return;
            }else if(!patt.test(subnets[0])){
                alert('Please input subnet address and range (ex. 192.168.1.0/24)');
                return;
            }

            for (var i = 0; i < subnets.length; ++i) {
                obj = {};
                obj[data.label + "subnet" + i.toString()] = {
                    Type: "OS::Neutron::Subnet",
                    Properties: {cidr: subnets[i], network: {Ref: data.label}, dns_nameservers:["8.8.8.8", "8.8.4.4"]}
                };
                //lab_resources.push(obj);
                jQuery.extend(lab_resources[project], obj);
            }

        } else if (node_type == 'router') {

            var node_type_router = document.getElementById('node-type-router_' + project).value;
            if (node_type_router == '0') {
                alert('Please select a router type!');
                return;
            }
            data.group = node_type_router;
            if (node_type_router == 'quagga') {
                if (vmcount[project] <= 40) {
                data.image = DIR + 'network_router.png';
                obj = {};
                obj[data.label] = {
                    Type: "OS::Nova::Server",
                    Properties: {
                        flavor: "e7743066-f089-4a6a-833e-e43506addfec",
                        image: "8eed4860-b83d-4b0a-950d-b776dae82481",
                        //key_name: "KeyPair",
                        name: data.label,
                        networks: []
                    }
                };
                //lab_resources.push(obj);
                jQuery.extend(lab_resources[project], obj);
                    vmcount[project] = vmcount[project] + 1;
            }
                else{ alert('You may only have 40 vm nodes.');
                    return;
                }

            }

            else if (node_type_router == 'vrouter') {
                if (routercount[project] == 0) {
                    data.image = DIR + 'network_router-firewall.png';
                    obj = {};
                    obj[data.label] = {Type: "OS::Neutron::Router"};
                    //lab_resources.push(obj);
                    jQuery.extend(lab_resources[project], obj);
                    routercount[project] = routercount[project] + 1;
                }else {
                    alert('Only one virtual router is allowed in a lab!');
                    return;
                }

            }
        }
        clearPopUp();
        callback(data);
    }
}

function lab_design_for_topo(temp_id) {
    var nodes = new vis.DataSet();
    var edges = new vis.DataSet();
    var project = temp_id;

    vmcount[project] = 0;
    cpucount[project] = 0;
    routercount[project] = 0;
    portcount[project] = 0;
    nodes.clear();
    edges.clear();
    nodes.on('*', function () {
        $('#json_nodes').html(JSON.stringify(nodes.get(), null, 4));
    });
    edges.on('*', function () {
        $('#json_edges').html(JSON.stringify(edges.get(), null, 4));
    });

    lab_design_data[project] = {nodes: nodes, edges: edges};

    var DIR = 'workspace-assets/images/icons/';
    var LENGTH_MAIN = 150;
    var LENGTH_SUB = 50;
    var temp = {};
    var design = {};
    if (project !== 'new') {
        $.getJSON("/cloud/getDesign/" + project, function (jsondata) {
            temp = JSON.parse(jsondata.temp);
            design = JSON.parse(jsondata.temp_vis);
            lab_resources[project] = {};
            jQuery.extend(lab_resources[project], temp);
            nodes.add(design.nodes);
            edges.add(design.edges);
            for (vm in lab_resources[project]) {
                if (lab_resources[project][vm].Type == "OS::Neutron::Router"){
                    routercount[project] = routercount[project]+1;
                }
                if (lab_resources[project][vm].Type == "OS::Nova::Server") {
                    if (!jQuery.isEmptyObject(lab_resources[project][vm].Properties.networks)) {
                        portcount[project] = portcount[project] + lab_resources[project][vm].Properties.networks.length;
                    }
                }
            }
        });
    } else {
        lab_resources['new'] = {};
        nodes.add({id: "Internet", label: 'Internet', shape: 'image', image: DIR + 'System-Globe-icon.png'});
    }

    //var keyobj = {KeyPair:{Type:"OS::Nova::KeyPair",Properties:{name:"keypair",save_private_key:"true"}}};
    ////lab_resources.push();
    //lab_resources[project]={};
    //jQuery.extend(lab_resources[project],keyobj);

    var container = document.getElementById('lab_design_div_' + project);
    var canvas_height = parseInt($('#lab_design_div_' + project).closest('div.window_inner').css('height'));
    container.style.height = canvas_height - 163 + 'px';
    container.style.minHeight = '550px';
    var options = {
        interaction:
        {
            navigationButtons: true,
            hover: true
        }

        //stabilize: false,

    };
    design_network[project] = new vis.Network(container, lab_design_data[project], options);

    // add event listeners
    design_network[project].on('select', function (params) {
        document.getElementById('selection_' + project).innerHTML = 'Selection: ' + params.nodes;
    });

    design_network[project].on("resize", function (params) {
        console.log(params.width, params.height)
    });

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
        data.id = labelInput.value;
        data.shape = 'image';

        if (nodes.get(data.label) !== null) {
            alert('Node with same name already exist, please use another name!');
            return;
        }
        if (node_type == '0') {
            alert('Please select a node type!');
            return;
        }
        if (node_type == 'vm') {

            var node_type_image = document.getElementById('node-type-vm-image_' + project).value;
            var node_type_image_num = document.getElementById('node-type-vm-image_' + project).selectedIndex;
            var node_type_image_name = document.getElementById('node-type-vm-image_' + project)[node_type_image_num].innerHTML;
            var node_type_flavor = "";//document.getElementById('node-type-vm-flavor_' + project).value;
            if (node_type_image == '0') {
                alert('Please select image!');
                return;
            }
            //alert(node_type_image_name);
            var os_type = node_type_image_name.split("-")[0];
            switch (node_type_image_name) {
                case 'Ubuntu-12.04-4-Desktop-Eng-64Bit-20160701-SEED (80.00 GB)':
                    node_type_flavor = "2ad83de9-f8d3-43e9-9a06-37915c8e3e52";
                    break;
                case 'Ubuntu-14.04-3-Desktop-Eng-64Bit-20180112-clean (15.00 GB)':
                    node_type_flavor = "51a92e52-f801-4b20-8175-af5d0dd8fe5c";
                    break;
                case 'Ubuntu-14.04-3-Server-Eng-64Bit-20160701-clean (10.00 GB)':
                    node_type_flavor = "51a92e52-f801-4b20-8175-af5d0dd8fe5c";
                    break;
                case 'Ubuntu-16.04-2-Desktop-Eng-64Bit-20180112-withSpark (250.00 GB)':
                    node_type_flavor = "b0c8cbab-d95b-4292-bdeb-54a3a95a047a";
                    break;
                case 'Windows-2008-2-Server-Eng-64Bit-20180112-Clean (80.00 GB)':
                    node_type_flavor = "2ad83de9-f8d3-43e9-9a06-37915c8e3e52";
                    break;
                case 'Windows-7-3-Desktop-Eng-64Bit-20180112-PSUIST554Attack (41.00 GB)':
                    node_type_flavor = "bbc61531-831c-4092-9951-d19d081e6e58";
                    break;
                case 'Windows-7-3-Desktop-Eng-64Bit-20180112-PSUIST554Student (41.00 GB)':
                    node_type_flavor = "bbc61531-831c-4092-9951-d19d081e6e58";
                    break;
                case 'Quagga-Router-Ubuntu-14.04-Server-64-150406 (3.00 GB)':
                    node_type_flavor = "e7743066-f089-4a6a-833e-e43506addfec";
                    break;
                case 'Quagga-Clean-Router-Ubuntu-14.04-Server-64-150406 (3.00 GB)':
                    node_type_flavor = "e7743066-f089-4a6a-833e-e43506addfec";
                    break;
                case 'redhat6.9 (80.00 GB)':
                    node_type_flavor = "2ad83de9-f8d3-43e9-9a06-37915c8e3e52";
                    break;
                case 'Windows-10-Fusion360-QXL (41.00 GB)':
                    node_type_flavor = "288c29cf-53b1-48a4-80e5-80b4dac232c4";
                    break;
                case 'Ubuntu-20.04-GUI (41.00 GB)':
                    node_type_flavor = "d6a50d9d-0a07-4617-950e-2172c53e47e4";
                    break;
                case 'Kali-20201b (38.00 GB)':
                    node_type_flavor = "d6a50d9d-0a07-4617-950e-2172c53e47e4";
                    break;
                case 'nessus (50.00 GB)':
                    node_type_flavor = "d6a50d9d-0a07-4617-950e-2172c53e47e4";
                    break;
                default:
                    node_type_flavor = "bbc61531-831c-4092-9951-d19d081e6e58";
            }
            var icon = "";
            //if (item.name.substring(0, 1) == 'R') {
            switch (os_type) {
                case 'Quagga':
                    icon = "workspace-assets/images/icons/network_router.png";

                    break;
                case 'Ubuntu':

                    icon = "workspace-assets/images/icons/terminal-ubuntu.png";

                    break;
                case 'SEED':

                    icon = "workspace-assets/images/icons/terminal-ubuntu.png";

                    break;
                case 'Windows':

                    icon = "workspace-assets/images/icons/terminal-windows.png";


                    break;
                case 'Redhat':

                    icon = "workspace-assets/images/icons/terminal-redhat.png";


                    break;
                case 'CentOS':

                    icon = "workspace-assets/images/icons/terminal-centos.png";

                    break;
                case 'Fedora':

                    icon = "workspace-assets/images/icons/terminal-fedora.png";

                    break;
                case 'Debian':

                    icon = "workspace-assets/images/icons/terminal-debian.png";

                    break;
                case 'Suse':

                    icon = "workspace-assets/images/icons/terminal-suse.png";

                    break;
                case 'NetBSD':

                    icon = "workspace-assets/images/icons/terminal-netBSD.png";

                    break;
                case 'Metasploitable':

                    icon = "workspace-assets/images/icons/terminal-Meta.png";


                    break;
                case 'Kali (15.00 GB)':

                    icon = "workspace-assets/images/icons/terminal-kali.png";

                    break;
                case 'Untangle (5.00 GB)':

                    icon = "workspace-assets/images/icons/terminal-debian.png";

                    break;
                case 'OpenBSD':

                    icon = "workspace-assets/images/icons/terminal-openBSD.png";


                    break;
                default:

                    icon = "workspace-assets/images/icons/network_terminal.png";


            }
            data.image = icon;
            data.group = 'vm';
            obj = {};
            obj[data.label] = {
                Type: "OS::Nova::Server",
                Properties: {
                    flavor: node_type_flavor,
                    image: node_type_image,
                    //key_name: "KeyPair",
                    name: data.label,
                    networks: []
                }
            };
            //lab_resources.push(obj);
            jQuery.extend(lab_resources[project], obj);
        } else if (node_type == 'switch') {
            var node_type_sw_subnets = document.getElementById('node-type-sw-subnets_' + project).value;
            data.image = DIR + 'network_switch.png';
            data.group = 'switch';
            obj = {};
            obj[data.label] = {Type: "OS::Neutron::Net", Properties: {admin_state_up: "true", name: data.label}};
            //lab_resources.push(obj);
            jQuery.extend(lab_resources[project], obj);
            var subnets = node_type_sw_subnets.split(',');
            if (subnets.length == 1) {
                alert('Please input subnet !');
                return;
            }
            for (var i = 0; i < subnets.length; ++i) {
                obj = {};
                obj[data.label + "subnet" + i.toString()] = {
                    Type: "OS::Neutron::Subnet",
                    Properties: {cidr: subnets[i], network: {Ref: data.label}}
                };
                //lab_resources.push(obj);
                jQuery.extend(lab_resources[project], obj);
            }

        } else if (node_type == 'router') {

            var node_type_router = document.getElementById('node-type-router_' + project).value;
            if (node_type_router == '0') {
                alert('Please select a router type!');
                return;
            }
            data.group = node_type_router;
            if (node_type_router == 'quagga') {
                data.image = DIR + 'network_router.png';
                obj = {};
                obj[data.label] = {
                    Type: "OS::Nova::Server",
                    Properties: {
                        flavor: "e7743066-f089-4a6a-833e-e43506addfec",
                        image: "8eed4860-b83d-4b0a-950d-b776dae82481",
                        //key_name: "KeyPair",
                        name: data.label,
                        networks: []
                    }
                };
                //lab_resources.push(obj);
                jQuery.extend(lab_resources[project], obj);
            }
            else if (node_type_router == 'vrouter') {
                if (routercount[project] == 0) {
                    data.image = DIR + 'network_router-firewall.png';
                    obj = {};
                    obj[data.label] = {Type: "OS::Neutron::Router"};
                    //lab_resources.push(obj);
                    jQuery.extend(lab_resources[project], obj);
                    routercount[project] = routercount[project] + 1;
                }else {
                    alert('Only one virtual router is allowed in a lab!');
                    return;
                }

            }
        }
        clearPopUp();
        callback(data);
    }
}

function populate_lab_design(winId, win_main) {
    var tabs = {
        tabId: ['lab_design' + winId.substring('#window_temp_design'.length)],
        tabName: ['Drawing']
    };


    create_tabs(winId, win_main, tabs, null);
    prepare_lab_design('#lab_design', winId);
    setTimeout(function () {
        lab_design(winId);
    }, 500);

}

function populate_content_openlabtopo(winId, win_main) {

    var pieces =winId.split('_');
    if(pieces.length==6 ){
        var course_id= pieces[pieces.length-3];
    }else if(pieces.length==7){
        var course_id= pieces[pieces.length-4]+'_'+pieces[pieces.length-3];
    }else if(pieces.length==5){
        var course_id=pieces[3];
    }




    $.getJSON("/cloud/getOpenTempId/"+course_id, function (jsondata) {
        var temp_id = jsondata;
        var tabs = {
            tabId: ['lab_design' + temp_id],
            tabName: ['Topology']
        };
        create_tabs(winId, win_main, tabs, null);
        win_main.find(".tab-links").hide();
        //
        prepare_lab_design_for_open('#lab_design', temp_id);
        setTimeout(function () {
            lab_design_for_topo(temp_id);
        }, 500);
        //alert(JSON.stringify(jsondata));

    });




}



function populate_lab_template(winId) {
    $('#lab_templates').empty();
    $('<fieldset><legend style="font-weight: bold;font-size: larger">Lab Environment Design:</legend></fieldset><button class="btn_create_temp submit" name="New Design Template" value="temp_designnew">Create a New Lab Environment</button><br />').appendTo($('#lab_templates'));
    var table = $(document.createElement('table')).appendTo($('#lab_templates'));
    table.addClass("data").attr("id", "tbl_lab_templates").append('<thead><tr>' +
    '<th class="shrink">&nbsp;</th>' + '' +
    '<th>Template Name</th>' +
    '<th>Description</th>' +
    '<th>Template ID</th>' +
    '<th>Shared With</th>' +
    '<th class="hidden">ID</th>' +
    '<th>Actions</th>' +
    '</tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    $.getJSON("/cloud/getTemplate", function (jsondata) {
        $.each(jsondata, function (index, item) {
            if (item.temp_shared == 0) {
                var str = "Not Shared";
                $('<tr>' +
                '<td><img src="' + ICON_folder_me + '"></img></td>' +
                '<td>' + item.temp_name + '</td>' +
                '<td>' + item.temp_des + '</td>' +
                '<td>' + item.temp_id + '</td>' +
                '<td>' + str + '</td>' +
                    //'<td><button type="button" class="temp_deploy" >Deploy</button> <button type="button" name="Edit Design Template" value="temp_design' + item.temp_id +
                    //'" class="temp_edit">Edit</button> <button type="button" class="temp_share">Share</button> <button type="button" class="temp_copy" >Copy</button> <button type="button" class="temp_delete" >Delete</button></td>' +
                '<td class="dropdown"><a class="btn btn-default temp-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                    //'<td>&mdash;</td>' +
                '</tr>').appendTo(tbody);
            } else {
                $('<tr>' +
                '<td><img src="' + ICON_folder_shared + '"></img></td>' +
                '<td>' + item.temp_name + '</td>' +
                '<td>' + item.temp_des + '</td>' +
                '<td>' + item.temp_id + '</td>' +
                '<td><button type="button" class="shared_member"><i class="fa fa-users"></i></button></td>' +
                '<td class="dropdown"><a class="btn btn-default temp-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                    //'<td>&mdash;</td>' +
                '</tr>').appendTo(tbody);
            }
        });

        //$(winId).find('div.window_bottom')
        //    .text(jsondata.length + ' Templates');

    });
    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'temp-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="temp-deploy">Deploy Testing Env</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="temp_edit">Edit</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="temp-share">Share</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="temp-copy">Copy to</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="temp-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);

    $('<br><br><fieldset><legend style="font-weight: bold;font-size: larger">Deployed Lab Testing Environments:</legend></fieldset>').appendTo($('#lab_templates'));
    var table3 = $(document.createElement('table')).appendTo($('#lab_templates'));

    table3.addClass("data").attr("id", "tbl_testing_lab").append('<thead><tr>' +
    '<th class="shrink">&nbsp;</th>' +
        //'<th>Group</th>' +
    '<th>Name</th>' +
    '<th>Description</th>' +
    '<th class="hidden">UUID</th>' +
        //'<th>Members</th>' +
    '<th>Actions</th>' +
        //'<th>Notes</th>' +
    '</tr></thead>');
    var tbody3 = $(document.createElement('tbody')).appendTo(table3);


    $.getJSON("/cloud/getTestingLab", function (jsondata) {
        $.each(jsondata, function (index, item) {
            $('<tr>' +
            '<td><img src="' + ICON_project + '"></img></td>' +
                //'<td>' + group + '</td>' +
            '<td>' + item.name + '</td>' +
            '<td>' + item.description + '</td>' +
                //'<td class="hidden">' + item.id + '</td>' +
                //'<td><button type="button" class="proj_member"><i class="fa fa-users"></i></button></td>' +  //"<td>" + item.users + "</td>" +
            '<td><a class="btn btn-default proj-view" name="Project: '+item.name+'" value="project_'+item.name+'" href="#">Open</a>' +
                //'<td>&mdash;</td>' +
            '<button  class="btn btn-default btn-DeleteProject" value='+item.name+'>Delete</button></td>' +
            '</tr>').appendTo(tbody3);
        });

    });

}



function template_list_update(tblId, action, temptr) {
    var tbody = $(tblId).find('tbody');

    if (action == 'new') {
        tbody.empty();
        $.getJSON("/cloud/getTemplate", function (jsondata) {
            $.each(jsondata, function (index, item) {
                if (item.temp_shared == 0) {
                    var str = "Not Shared";
                    $('<tr>' +
                    '<td><img src="' + ICON_folder_me + '"></img></td>' +
                    '<td>' + item.temp_name + '</td>' +
                    '<td>' + item.temp_des + '</td>' +
                    '<td>' + item.temp_id + '</td>' +
                    '<td>' + str + '</td>' +
                    '<td class="dropdown"><a class="btn btn-default temp-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                        //'<td>&mdash;</td>' +
                    '</tr>').appendTo(tbody);
                } else {
                    $('<tr>' +
                    '<td><img src="' + ICON_folder_shared + '"></img></td>' +
                    '<td>' + item.temp_name + '</td>' +
                    '<td>' + item.temp_des + '</td>' +
                    '<td>' + item.temp_id + '</td>' +
                    '<td><button type="button" class="shared_member"><i class="fa fa-users"></i></button></td>' +
                    '<td class="dropdown"><a class="btn btn-default temp-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                    '</tr>').appendTo(tbody);
                }
            });
        });
        var contextMenu = $(document.createElement('ul')).appendTo(tbody);
        contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'temp-contextMenu')
            .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
        $('<li><a tabindex="-1" href="#" class="temp-deploy">Deploy Testing Env</a></li>').appendTo(contextMenu);
        $('<li><a tabindex="-1" href="#" class="temp_edit">Edit</a></li>').appendTo(contextMenu);
        $('<li><a tabindex="-1" href="#" class="temp-share">Share</a></li>').appendTo(contextMenu);
        $('<li><a tabindex="-1" href="#" class="temp-copy">Copy to</a></li>').appendTo(contextMenu);
        $('<li><a tabindex="-1" href="#" class="temp-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);

    } else if (action == 'delete') {
        temptr.remove();
        var contextMenu = $(document.createElement('ul')).appendTo(tbody);
        contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'temp-contextMenu')
            .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
        $('<li><a tabindex="-1" href="#" class="temp-deploy">Deploy Testing Env</a></li>').appendTo(contextMenu);
        $('<li><a tabindex="-1" href="#" class="temp_edit">Edit</a></li>').appendTo(contextMenu);
        $('<li><a tabindex="-1" href="#" class="temp-share">Share</a></li>').appendTo(contextMenu);
        $('<li><a tabindex="-1" href="#" class="temp-copy">Copy to</a></li>').appendTo(contextMenu);
        $('<li><a tabindex="-1" href="#" class="temp-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);
    }
}

function share_temp(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'temp_share').attr('title', 'Share Template with Users');

    var form_html = '<input class="hidden" name="got_template_id" value="' + element.parent().parent().parent().prev().prev().text() + '">' +
        '<label>User Name:</label>' +
        '<input name="search_share_user_txt">' +
        '<button id="share_temp_user_btn">Search</button>' +
        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="share_button" >Share</button>' +
        '<hr/>' +
        '<table class="data" id="search_share_user_table">' +
        '<tr><th>Checkbox</th><th>User</th><th>Institute</th><th>Org ID</th></tr>' +
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
function share_user_btn_temp(element) {
    var temp_id = element.prev().prev().prev().val();
    $.post("/cloud/searchAllUser", {
            "search_user_txt": $("input[name=search_share_user_txt]").val(),
            "temp_id": temp_id
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
    table.append("<tr><th>Checkbox</th><th>User</th><th>Institute</th><th>Org ID</th></tr>");
    //alert("send ajax for own group list0");
    $.each(jsondata, function (index, item) {
        //alert(item.userid);
        if (index == 'users') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td><input type="checkbox" name="share_temp_checkbox"></td><td>' + item3.email + '</td><td>' + item3.institute + '</td><td>' + item3.org_id + '</td></tr>');//<td><button class="btn-Share" >Share</button></td></tr>');

            });
        }
    });
}
function share_button(element) {
    var temp_id = element.prev().prev().prev().prev().val();
    var checked_users = [];
    var table = document.getElementById('search_share_user_table');
    for (var i = 1, row; i < table.rows.length; i++) {
        row = table.rows[i];
        var checkbox = row.cells[0];
        var useremail = row.cells[1];
        if (checkbox.firstChild.checked) {
            checked_users[checked_users.length] = useremail.firstChild.data;
        }
    }
    for (var i = 0; i < checked_users.length; i++) {
        $.post("cloud/shareTemp", {
                "temp_id": temp_id,
                "user_email": checked_users[i]
            },
            function (jsondata) {
                template_list_update("#tbl_lab_templates", "new", 0);
                element.closest('.dialog').dialog('close');
            },
            'json'
        );
    }
}
function btn_share(element) {
    var user_email = element.parent().prev().prev().prev().text();
    var temp_id = element.parent().parent().parent().parent().prev().prev().prev().prev().prev().val();
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

function copy_button(element) {
    var temp_id = element.prev().prev().prev().prev().val();
    var checked_users = [];
    var table = document.getElementById('search_copy_user_table');
    for (var i = 1, row; i < table.rows.length; i++) {
        row = table.rows[i];
        var checkbox = row.cells[0];
        var useremail = row.cells[1];
        if (checkbox.firstChild.checked) {
            checked_users[checked_users.length] = useremail.firstChild.data;
        }
    }
    for (var i = 0; i < checked_users.length; i++) {
        $.post("cloud/copyTemp", {
                "temp_id": temp_id,
                "user_email": checked_users[i]
            },
            function (jsondata) {
                template_list_update("#tbl_lab_templates", "new", 0);
                element.closest('.dialog').dialog('close');
            },
            'json'
        );
    }
}
function btn_copy(element) {
    var user_email = element.parent().prev().prev().prev().text();
    var temp_id = element.parent().parent().parent().parent().prev().prev().prev().prev().prev().val();
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

    var form_html = '<input class="hidden" name="got_template_id" value="' + element.parent().parent().parent().prev().prev().text() + '">' +
        '<label>User Name:</label>' +
        '<input name="search_copy_user_txt">' +
        '<button id="copy_temp_user_btn">Search</button>' +
        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="copy_button" >Copy to</button>' +
        '<hr/>' +
        '<table class="data" id="search_copy_user_table">' +
        '<tr><th>Checkbox</th><th>User</th><th>Institute</th><th>Org ID</th></tr>' +
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
function copy_user_btn_temp(element) {
    $.post("/cloud/searchAllUser", {
            "search_user_txt": $("input[name=search_copy_user_txt]").val(),
            "temp_id": 1
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
    table.append("<tr><th>Checkbox</th><th>User</th><th>Institute</th><th>Org ID</th></tr>");
    //alert("send ajax for own group list0");
    $.each(jsondata, function (index, item) {
        //alert(item.userid);
        if (index == 'users') {
            $.each(item, function (index3, item3) {
                table.append('<tr><td><input type="checkbox" name="share_temp_checkbox"></td><td>' + item3.email + '</td><td>' + item3.institute + '</td><td>' + item3.org_id + '</td></tr>'); //<td><button class="btn-Copy" >Copy</button></td></tr>');

            });
        }
    });
}
function verify_lab_template(element) {
    var project = element.prev().html().substring(13);
    var lab_template = {"AWSTemplateFormatVersion": "2010-09-09", "Resources": lab_resources[project]};
    $.post("/cloud/verifyHeatTemp", {
            //"_token": $(this).find('input[name=_token]').val(),
            "template": JSON.stringify(lab_template),
            "temp_id": project
        },
        function (data) {
            alert(data.msg);
            // display_share_user_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            alert(xhr.responseText);
        });
}

function update_lab_template(project) {
    //var lab_template = { "AWSTemplateFormatVersion": "2010-09-09", "Resources": lab_resources[project] };
    lab_design_json = {nodes: lab_design_data[project].nodes.get(), edges: lab_design_data[project].edges.get()};
    var lab_design_vmcount =vmcount[project];
    $.post("cloud/updateTemplate", {
            //"_token": $(this).find('input[name=_token]').val(),
            "temp_id": project,
            "temp": JSON.stringify(lab_resources[project]),
            "temp_design": JSON.stringify(lab_design_json),
            "temp_vmcount": lab_design_vmcount
        },
        function (data) {

                if (data.status == "Success") {
                    alert(data.msg);


                }
                else {
                    alert(data.msg);
                }

            template_list_update("#tbl_lab_templates", "new", 0);
            //alert(JSON.stringify(data));
            // display_share_user_table(data);
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            // alert(xhr.responseText);
        });
}

function delete_temp(tempid, element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'temp_delete').attr('title', 'Delete Template');

    var temptr = element.closest('tr');

    var form_html = '<input class="hidden" name="got_template_id" value="' + tempid + '">' +
        '<label>Are you sure you want to delete this template?</label>';
    $(form_html).appendTo(dlg_form);

    $('#temp_delete').dialog({
        modal: true,
        height: 120,
        overflow: "auto",
        width: 350,
        buttons: {
            "Cancel": function () {
                $(this).dialog('close');
            },
            "Delete": function () {
                $(this).dialog('close');

                template_list_update("#tbl_lab_templates", "delete", temptr);
                btn_delete(tempid);

            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
}
function btn_delete(temp_id) {

    $.post("/cloud/deleteTemp", {
            "temp_id": temp_id

        },
        function (data) {
            //alert(JSON.stringify(data));
            // display_share_user_table(data);
            //element.closest('.dialog').dialog('close');
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
        });
    return true;
}
function deploy_temp(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'temp_copy').attr('title', 'Deploy Template');
    var temp_id = element.parent().parent().parent().prev().prev().text();
    $.getJSON("/cloud/getDesign/" + temp_id, function (jsondata) {
        var temp = JSON.parse(jsondata.temp);
        lab_resources[temp_id] = {};
        jQuery.extend(lab_resources[temp_id], temp);
    });

    var form_html = '<input class="hidden" name="got_template_id" value="' + temp_id + '">' +
        '<label>Project Name:</label>' +
        '<input id="deploy_user_name_txt">' +
        '<hr/>' +
        '<button class="btn-Deploy">Deploy</button>' +
        '<hr/>';
    $(form_html).appendTo(dlg_form);

    $('#temp_copy').dialog({
        modal: true,
        height: 200,
        overflow: "auto",
        width: 400,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}
function btn_deleteproject(element){
    var project_name = element.val();
    $.post("/cloud/deleteProject", {
            //$(this).prop('action'), {
            //"_token": $(this).find('input[name=_token]').val(),
            "project": project_name,
            "stack": project_name
        },
        function (data) {
            if (data.status == "Success") {
                //alert(data.message);
                $.jAlert({'title':'Information','content':'The lab environment has been deleted.','theme':'red','btns':{'text':'close','theme':'red'}});
                element.closest('.dialog').dialog('close');
                populate_lab_template(element);
            }
            else {
                alert(data.message);
            }
        },
        'json'
    )
}
function btn_deploy(element) {
    var temp_id = element.prev().prev().prev().prev().val();
    var project_name = $("#deploy_user_name_txt").val();
    var lab_template = {"AWSTemplateFormatVersion": "2010-09-09", "Resources": lab_resources[temp_id]};
    if (project_name.indexOf(" ") != -1 || project_name.indexOf("_") != -1 || project_name.indexOf("$") != -1) {
        alert("Project name can/'t contain space or underscore or dollar sign, please edit!");
        return;
    }
    $.post("/cloud/createProject", {
            //$(this).prop('action'), {
            //"_token": $(this).find('input[name=_token]').val(),
            "project_name": project_name,
            "project_desc": "This project is created from Template ID " + temp_id + " .",
            "temp_id": temp_id
        },
        function (data) {
            if (data.status == "Success") {
                //alert(data.msg);
                //project_list_update("new",data.tenant);
                $.post("/cloud/deployTemp", {
                        //$(this).prop('action'), {
                        //"_token": $(this).find('input[name=_token]').val(),
                        "project": project_name,
                        "stack": project_name,
                        "template": JSON.stringify(lab_template),
                        "project_desc": "This project is created from Template No." + temp_id + " .",
                        "temp_id": temp_id
                    },
                    function (data) {
                        if (data.status == "Success") {
                            //alert(data.msg);
                            $.jAlert({'title':'Information','content':'The lab environment has been created.','theme':'green','btns':{'text':'close','theme':'green'}});
                            element.closest('.dialog').dialog('close');
                            populate_lab_template(element);
                        }
                        else {
                            alert(data.msg);
                        }
                    },
                    'json'
                )
                element.closest('.dialog').dialog('close');
                populate_lab_template(element);

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

/**
 * Created by root on 6/8/15.
 */
function removetags(){
    if(document.getElementById("container-1")){
        var node= document.getElementById("container-1");
        node.parentNode.removeChild(node);
    }
    if(document.getElementById("newp2")){
        var node1= document.getElementById("newp2");
        node1.parentNode.removeChild(node1);
    }
    if(document.getElementById("newp1")){
        var node2= document.getElementById("newp1");
        node2.parentNode.removeChild(node2);
    }
    if(document.getElementById("container-2")){
        var node3= document.getElementById("container-2");
        node3.parentNode.removeChild(node3);
    }

}

function loadyoutube(x){

    var video_link="https://www.youtube.com/embed/"+ x +"?enablejsapi=1&origin=http://example.com";

    //document.getElementById('demo').innerHTML = video_link;


    var embedv = document.createElement("iframe");
    embedv.setAttribute('id',"ytplayer1");
    embedv.setAttribute('type',"text/html");
    embedv.setAttribute('width',"640");
    embedv.setAttribute('height',"390");
    embedv.setAttribute('frameborder',"0");
    embedv.setAttribute('src',video_link);

    var element = document.getElementById("viewer");
    var child = document.getElementById("p1");
    element.insertBefore(embedv,child);

}

function loadPDF(y){

    //document.getElementById('demo1').innerHTML = y;
    var embedpdf = document.createElement("embed");
    embedpdf.setAttribute('id',"pdf1");
    embedpdf.setAttribute('type',"application/pdf");
    embedpdf.setAttribute('width',"500");
    embedpdf.setAttribute('height',"600");
    embedpdf.setAttribute('src',y);

    var element = document.getElementById("viewer");
    var child = document.getElementById("p1");
    element.insertBefore(embedpdf,child);

}
