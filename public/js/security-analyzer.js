/**
 * Created by root on 3/28/17.
 */

var phy_topology_data = {};
var sysanalysis_ag_toploty_data = {};

function security_analyzer_window(winId, win_main) {
    var tabs = {
        tabId: ['net_scan', 'ag_viewer', 'ag_analyzer', 'flow_rule_checker', 'cm_deploy'],
        tabName: ['Network Scan', 'Attack Graph', 'Attack Analyzer', 'Flow Rule Checker', 'Countermeasure Deployment']
    };
    create_tabs(winId, win_main, tabs, null);

    //var div_net_scan = $(document.createElement('div')).appendTo($('#net_scan'));
    //var iframe_div_net_scan = $(document.createElement('iframe'));

    //var div_ag_viewer = $(document.createElement('div')).appendTo($('#ag_viewer'));

    var div_ag_analyzer = $(document.createElement('div')).appendTo($('#ag_analyzer'));
    var iframe_div_ag_analyzer = $(document.createElement('iframe'));
    iframe_div_ag_analyzer.attr("name", "frame_ag_analyzer").attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://ag-model.mobisphere.asu.edu/canviz-0.1/testag.html").appendTo(div_ag_analyzer);

    var div_rule_checker = $(document.createElement('div')).appendTo('#flow_rule_checker');
    $('<a href="http://odl.mobisphere.asu.edu/index.html" target="_blank">Go To Flow Rules Checker</a>').appendTo(div_rule_checker);
    // var iframe_div_rule_checker = $(document.createElement('iframe'));
    // iframe_div_rule_checker.attr("name", "frame_rule_checker").attr("width", "100%").attr("style", "height: 100em")
    //     .attr("src", "https://ag-model.mobisphere.asu.edu/network-design/test-network-design.html?url=test.json").appendTo(div_rule_checker);
    //
    // var div_cm_deploy = $(document.createElement('div')).appendTo('#cm_deploy');
    // var iframe_cm_deploy = $(document.createElement('iframe'));
    // iframe_cm_deploy.attr("name", "frame_cm_deploy").attr("width", "100%").attr("style", "height: 100em")
    //     .attr("src", "https://ag-model.mobisphere.asu.edu/network-design/test-network-design.html?url=test.json").appendTo(div_cm_deploy);

    prepare_ag_viewer('#ag_viewer');
    prepare_network_scan('#net_scan');


}

function prepare_ag_viewer(div_tab_agviewer) {
    var table = $(document.createElement('table')).appendTo($(div_tab_agviewer));
    table.attr("class", "data").css('width', '100%').append('<thead><tr><th></th></tr></thead>');
    var table_head = table.find('th');

    var vis_canvas_id = 'getag_canvas_div';
    var mynetwork_div = $(document.createElement('div')).appendTo($(div_tab_agviewer));
    mynetwork_div.attr('id', vis_canvas_id).css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');

    //prepare_slide_toggle_panel(div_tab_agviewer, table_head, 'sysana_getag');

    $('<span style="font-size:85%;">10%</span>').css('float', 'left').appendTo(table_head);
    $('<input id="ag-slider" type="range" min="0.1" max="1.0" step="0.05" value="1.0" onchange="ag_view_container_resize(this.value)"/>')
        .css('float', 'left').css('width', '100px').appendTo(table_head);
    $('<span style="font-size:85%;">100%</span>').css('float', 'left').appendTo(table_head);
    $('<span style="position:absolute; font-size:70%;"><span id="ag-slider-value"></span></span>').appendTo(table_head);

    var btn_get_ag = $(document.createElement('button')).appendTo(table_head);
    btn_get_ag.addClass('dialog-btn').attr('id', 'btn_sysana_getag').attr('title', 'Generate Attack Graph')
        .attr('onclick','generate_attack_graph("' + div_tab_agviewer + '", "getag_canvas_div","sysana_getag")')
        .css('position', 'absolute').css('left', '300px').html('Generate Attack Graph');
    var btn_attack_detect = $(document.createElement('button')).appendTo(table_head);
    btn_attack_detect.addClass('dialog-btn').attr('id', 'btn_sysana_attack_detect').attr('title', 'Attack Detection')
        .attr('onclick','generate_attack_graph("' + div_tab_agviewer + '", "getag_canvas_div","sysana_attdetect")')
        .css('position', 'absolute').css('left', '500px').html('Attack Detection');

    prepare_slide_toggle_right_panel(div_tab_agviewer, table_head, 'sysana_getag_detail');
}

function ag_view_container_resize(value) {
    var myRange = document.querySelector('#ag-slider');
    var myValue = document.querySelector('#ag-slider-value');
    var myUnits = '%';
    var off = myRange.offsetWidth / (parseInt(myRange.max) - parseInt(myRange.min));
    var px =  ((myRange.valueAsNumber - parseInt(myRange.min)) * off) - (myValue.offsetParent.offsetWidth / 2);

    myValue.parentElement.style.left = px + 50 + 'px';
    myValue.parentElement.style.top = myRange.offsetHeight + 15 + 'px';
    myValue.innerHTML = (myRange.value * 100).toFixed() + myUnits;

    myRange.oninput =function(){
        px = ((myRange.valueAsNumber - parseInt(myRange.min)) * off) - (myValue.offsetWidth / 2);
        myValue.innerHTML = (myRange.value * 100).toFixed() + myUnits;
        myValue.parentElement.style.left = px + 15 + 'px';
    };
}

function prepare_network_scan(div_net_scan) {
    var table = $(document.createElement('table')).appendTo($(div_net_scan));
    table.attr("class", "data").css('width', '100%').append('<thead><tr><th></th></tr></thead>');
    var table_head = table.find('th');

    prepare_slide_toggle_panel(div_net_scan, table_head, 'sysana_net_scan');

    // var group_sel = $(document.createElement('select')).appendTo(table_head);
    // group_sel.attr('id', 'netscan_group_selector').css('position', 'absolute').css('left', '150px').css('width', '150px')
    //     .css('color', '#1C94C4').change(function() { netscan_select_project($('#netscan_group_selector>option:selected').text()); }); //.css('margin-top', '4px');
    // group_sel.append($('<option />').val(-1).html('...Select a Group...'));
    //
    // $.getJSON("cloud/getGroupListByUser", function (jsondata) {
    //     $.each(jsondata.groups, function(index, item) {
    //         group_sel.append($('<option />').val(index).html(item));
    //     })
    // });

    var project_sel = $(document.createElement('select')).appendTo(table_head);
    project_sel.attr('id', 'netscan_project_selector').css('position', 'absolute').css('left', '310px').css('width', '150px')
        .css('color', '#1C94C4')
        .change(function() {
            netscan_prepare_topology(div_net_scan, $('#netscan_project_selector>option:selected').val(), 'sysana_net_scan');
        }); //.css('margin-top', '4px');
    // project_sel.append($('<option />').val(-1).html('...Select a Project...'));
    netscan_select_project();

    var view_sel = $(document.createElement('select')).appendTo(table_head);
    view_sel.attr('id', 'netscan_view_selector').css('position', 'absolute').css('left', '470px').css('width', '100px')
        .css('color', '#1C94C4')
        .change(function() {
            netscan_change_topology_view();
        }); //.css('margin-top', '4px');
    view_sel.append($('<option />').val(0).html('Logical View')).append($('<option />').val(1).html('Physical View'));

    var btn_net_scan = $(document.createElement('button')).appendTo(table_head);
    btn_net_scan.addClass('dialog-btn').attr('id', 'btn_netscan').attr('title', 'Scaning a Network')
        .attr('onclick','security_analysis_network_scan()')
        .css('position', 'absolute').css('left', '580px').html('Scan Network');

    $('<input />', { type: 'checkbox', id: 'chk_countermeasure', value: 'Countermeasure' }).css('position', 'absolute').css('left', '710px').appendTo(table_head);
    $('<label />', { 'for': 'chk_countermeasure', text: 'Countermeasure' }).css('position', 'absolute').css('left', '730px').appendTo(table_head);

    prepare_slide_toggle_right_panel(div_net_scan, table_head, 'sysana_netscan_detail');
}

function prepare_slide_toggle_panel(div_panel, table_head, project) {
    var popup_div = $(document.createElement('div')).appendTo($(div_panel));
    popup_div.addClass('addNode-popUp').attr('id', 'network_topo_popUp_' + project)
        .css('top', '90px').css('left', '20px').css('width', '400px').css('height', '520px')
        .css('text-align', 'left'); //.css('display','none');
    //$('<span id="network_topo_operation_' + project + '" style="font-size:20px;">node</span> <br>' +
    $('<div id="network_topo_config_' + project + '"></div>')
        .css('float', 'left').css('height', '500px').appendTo(popup_div);
    //.css('float', 'left').css('width', '400px').css('height', '600px').appendTo(popup_div);

    var btn_toggle_config = $(document.createElement('button')).appendTo(table_head);
    btn_toggle_config.addClass('toggle-button').attr('id', 'btn_toggle_options_' + project)
        .attr('onclick', 'toggle_net_topology_display_options("' + project + '")')
        .css('float', 'left').attr('title', 'open option panel')
        .html('<i class="fa fa-cog"></i> Options <span class="fa fa-caret-down"></span>');
}

function prepare_slide_toggle_right_panel(div_panel, table_head, project) {
    var popup_div = $(document.createElement('div')).appendTo($(div_panel));
    popup_div.addClass('addNode-popUp').attr('id', 'network_topo_popUp_' + project)
        .css('top', '90px').css('right', '20px').css('width', '300px').css('height', '420px')
        .css('text-align', 'left'); //.css('display','none');
    //$('<span id="network_topo_operation_' + project + '" style="font-size:20px;">node</span> <br>' +

    var btn_toggle_config = $(document.createElement('button')).appendTo(table_head);
    btn_toggle_config.addClass('toggle-button').attr('id', 'btn_toggle_options_' + project)
        .attr('onclick', 'toggle_net_topology_display_options("' + project + '")')
        .css('float', 'right').attr('title', 'open detail panel')
        .html('<i class="fa fa-cog"></i> Details <span class="fa fa-caret-down"></span>');

    if (project == 'sysana_netscan_detail') {
        var inner_div = $(document.createElement('div')).appendTo(popup_div);
        inner_div.attr('id', 'network_topo_config_' + project)
            .css('float', 'left').css('height', '400px');
        //.css('float', 'left').css('width', '400px').css('height', '600px').appendTo(popup_div);
        $('<p id="node_detail_' + project + '"></p>').appendTo(inner_div);


    } else if (project == 'sysana_getag_detail') {
        var agviewtabs= $('<ul id="ag-detail-tabs"></ul>').appendTo(popup_div);
        $('<li class="ag-tab"><a id="ag-tab1" onclick="ag_view_click_tab(this)">Label</a></li>' +
            '<li class="ag-tab"><a id="ag-tab2" onclick="ag_view_click_tab(this)">Input</a></li>' +
            '<li class="ag-tab"><a id="ag-tab3" onclick="ag_view_click_tab(this)">Paths</a></li>' +
            '<li class="ag-tab"><a id="ag-tab4" onclick="ag_view_click_tab(this)">Alerts</a></li>').appendTo(agviewtabs);
        $('<div class="ag-tab-container" id="ag-tab1C" style="overflow: auto"></div>' +
            '<div class="ag-tab-container" id="ag-tab2C" style="overflow: auto"></div>' +
            '<div class="ag-tab-container" id="ag-tab3C" style="overflow: auto"></div>' +
            '<div class="ag-tab-container" id="ag-tab4C" style="overflow: auto"></div>').appendTo(popup_div);
        $('#ag-detail-tabs li a:not(:first)').addClass('inactive');
        $('.ag-tab-container').hide();
        $('.ag-tab-container:first').hide();
    }
}

function ag_view_click_tab(element) {
    var t = $(element).attr('id');
    if($(element).hasClass('inactive')){ //this is the start of our condition
        $('#ag-detail-tabs li a').addClass('inactive');
        $(element).removeClass('inactive');

        $('.ag-tab-container').hide();
        $('#'+ t + 'C').fadeIn('slow');
    }
}

function netscan_select_project() {
    $('#netscan_project_selector').empty().append($('<option />').val(-1).html('...Select a Project...'));
    $('#netscan_topo_div').empty();
    // if ($('#netscan_group_selector').val() < 0) {
    //     return;
    // }
    // $.getJSON("/cloud/getSubgroupTemplateProject/" + group, function (jsondata) {
    //     $.each(jsondata, function(index, lab) {
    //         $('#netscan_project_selector').append($('<option />').val(index).html(lab.project_name));
    //     })
    // });
    $.getJSON("/cloud/getProjectList/", function (projects) {
        $.each(projects, function(index, project) {
            $('#netscan_project_selector').append($('<option />').val(project.id).html(project.name));
        })
    });
}

function netscan_prepare_topology(netscan_tab, project, slide_panel_id) {

    if (project < 0) {
        $('#netscan_topo_div').empty();
        return;
    }

    var vis_canvas_id = 'netscan_canvas_div';
    var mynetwork_div = $(document.createElement('div')).appendTo($(netscan_tab));
    mynetwork_div.attr('id', vis_canvas_id).css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');

    var nodes = new vis.DataSet;
    var edges = new vis.DataSet;
    var p_nodes = new vis.DataSet;
    var p_edges = new vis.DataSet;
    net_topology_data[project] = {nodes: nodes, edges: edges};
    phy_topology_data[project] = {nodes: p_nodes, edges: p_edges};

    var win_main = $(netscan_tab).closest('.window_main');
    $.when(netscan_net_list(project)).then(function () {
        run_waitMe(win_main, 'ios');
        //$.getJSON('hypervisor-servers.json', function(jsondata) {
        $.getJSON('/cloud/getHypervisors', function(jsondata) {
            $.each(jsondata, function(index, host) {
                var tooltip = '<div>' + host.hypervisor_hostname + ' (' + host.host_ip + ')' + '<br>'
                    + 'Memory: ' + host.memory_mb + ' MB<br>'
                    + 'Memory Used: ' + host.memory_mb_used + ' MB<br>'
                    + 'Free Ram: ' + host.free_ram_mb + ' MB<br>'
                    + 'vcpus: ' + host.vcpus + '<br>'
                    + 'vcpus used: ' + host.vcpus_used + '<br>'
                    + 'State: ' + host.state + '<br>'
                    + 'Status: ' + host.status
                    + '</div>';
                var vm_str = "";
                // $.each(host.servers, function(idx, vm) {
                //     vm_str += vm.id + ',';
                // });
                phy_topology_data[project].nodes.add({
                    id: host.hypervisor_hostname, label: host.hypervisor_hostname, shape: 'image',
                    title: tooltip, group: 'hypervisor', vms: vm_str,
                    image: "workspace-assets/images/icons/Home-Server-icon-64.png"
                });
                phy_topology_data[project].nodes.add({
                    id: 'dvr-' + host.hypervisor_hostname, label: 'dvr-' + host.hypervisor_hostname, shape: 'image',
                    title: 'Distributed Virtual Router', group: 'dvr', vms: '',
                    image: "workspace-assets/images/icons/network_router.png"
                });
                phy_topology_data[project].edges.add({from: host.hypervisor_hostname, to: 'dvr-' + host.hypervisor_hostname, length: 50});
                phy_topology_data[project].edges.add({from: 'dvr-' + host.name, to: 'Internet', length: 100});
                phy_topology_data[project].nodes.add({
                    id: 'ovs-' + host.hypervisor_hostname, label: 'ovs-' + host.hypervisor_hostname, shape: 'image',
                    title: 'Open vSwitch Agent', group: 'ovs',
                    image: "workspace-assets/images/icons/network_switch.png"
                });
                phy_topology_data[project].edges.add({from: host.hypervisor_hostname, to: 'ovs-' + host.hypervisor_hostname, length: 50});
                phy_topology_data[project].edges.add({from: 'ovs-' + host.hypervisor_hostname, to: 'tunnel-sw', length: 100});
            })
        });

        phy_topology_data[project].nodes.add({ id: 'Internet', label: 'Internet', shape: 'image',
            title: 'Internet', group: 'internet',
            image: "workspace-assets/images/icons/System-Globe-icon.png"});
        phy_topology_data[project].nodes.add({ id: 'tunnel-sw', label: 'tunnel sw', shape: 'image',
            title: 'Physical Switch', group: 'phy-sw',
            image: "workspace-assets/images/icons/hp_switch_icon.png"});

        $.getJSON("/cloud/getServers/" + project, function (jsondata) {
            var i = 0;
            $.each(jsondata, function (index, item) {
                var address_str = '<ul>';
                var addr = '';
                $.each(item.addresses, function (net, addresses) {
                    address_str += '<li>' + net + ':';
                    net_topology_data[project].edges.add({from: item.name, to: net, length: 50});

                    $.each(addresses, function (skey, address) {
                        address_str += address.addr + ' ';
                        addr += address.addr + ' ';
                    });
                    address_str += '</li>';
                });
                address_str += '</ul>';

                var vm_value = 'vm_' + project + '_' + item.id;
                var tooltip = '<div id="canvas-tooltip_' + vm_value + ')">';
                tooltip += '<div class="canvas-tooltip-networking">Networks:' + address_str + '</div>';
                tooltip += '<div class="canvas-tooltip-status">' + create_tooltip_vmstatus_buttons(item, vm_value) + '</div>';
                tooltip += '<div class="canvas-tooltip-security-status">Security: not scan yet. </div>';
                tooltip += '<div class="canvas-tooltip-vmservices">Open Ports: N/A </div></div>';

                var vm_icon = get_vm_icon(item);
                var group = vm_icon.group;
                var icon = vm_icon.icon;

                net_topology_data[project].nodes.add({
                    id: item.name, label: item.name, shape: 'image',
                    title: tooltip, group: group, image: icon, vmid: vm_value, ips: addr
                }); //"workspace-assets/images/icons/Hardware-My-Computer-3-icon.png" } );

                if (group === 'vm') {
                    phy_topology_data[project].nodes.forEach(function(node) {
                        if (node.group === 'hypervisor') {
                            if (node.vms.indexOf(item.id) >= 0) {
                                phy_topology_data[project].edges.add({from: item.name, to: node.id, length: 50});
                            }
                        }
                    });
                    phy_topology_data[project].nodes.add({
                        id: item.name, label: item.name, shape: 'image',
                        title: tooltip, group: group, image: icon, vmid: vm_value, ips: addr
                    });
                }
            });
            $(win_main).waitMe('hide');
            setTimeout(function() {
                show_vis_canvas(netscan_tab, vis_canvas_id, slide_panel_id, net_topology_data[project]);
            },1500);
        });
    });
}

function netscan_net_list(project) {
    $.getJSON("/cloud/getNetworks/" + project, function (jsondata) {
        $.each(jsondata, function (index, item) {
            var subnet_str = '';
            $.each(item.subnets, function (idx, val) {
                subnet_str += val.name + ':' + val.cidr + '<br>';
            });
            net_topology_data[project].nodes.add({ id: item.name, label: item.name, shape: 'image',
                title: subnet_str, group: 'switch',
                image: "workspace-assets/images/icons/network_switch.png", vmid: "", ips: "" });

            var ports_str = '';
            $.each(item.ports, function (idx, val) {
                var ip_str = '';
                $.each(val.fixedIps, function (i, v) {
                    ip_str += v.ip_address;
                });
                ports_str += val.deviceOwner  + ':' + ip_str + '<br />';
                if (val.deviceOwner == 'network:router_centralized_snat') {
                    $('#btn_netscan').attr('value', 'snat-' + val.deviceId);
                }
                else if (val.deviceOwner == 'network:router_interface_distributed' || val.deviceOwner == 'network:router_interface') {
                    net_topology_data[project].nodes.add({ id: 'ext-router', label: 'External Router', shape: 'image',
                        title: ip_str, group: 'vrouter',
                        image: "workspace-assets/images/icons/network_router-firewall.png", vmid: "", ips: "" });
                    net_topology_data[project].nodes.add({ id: 'Internet', label: 'Internet', shape: 'image',
                        title: 'Internet', group: 'internet',
                        image: "workspace-assets/images/icons/System-Globe-icon.png", vmid: "", ips: "" });
                    net_topology_data[project].edges.add({from: 'ext-router', to: 'Internet', length: 50 });
                    net_topology_data[project].edges.add({from: 'ext-router', to: item.name, length: 50 });
                }
            });
        });
    });
}

function netscan_change_topology_view () {
    if ($('#netscan_view_selector>option:selected').val() == 0) {
        //alert('logical view');
        show_vis_canvas('#net_scan', 'netscan_canvas_div', 'sysana_net_scan', net_topology_data[project]);
    } else {
        //alert('Physical View');
        show_vis_canvas('#net_scan', 'netscan_canvas_div', 'sysana_net_scan', phy_topology_data[project]);
    }
}

function show_vis_canvas(tab_div, div_container, config_container, view_dataset) {
    var container = document.getElementById(div_container);
    var canvas_height = parseInt($('#' + div_container).closest('div.window_inner').css('height'));
    container.style.height = canvas_height - 163 + 'px';
    container.style.minHeight = '550px';
    var configure_container = document.getElementById('network_topo_config_' + config_container);
    configure_container.innerHTML = "";

    var options = {
        //autoResize: true,
        //height: '100%',
        //width: '100%',
        interaction: {
            navigationButtons: true,
            hover: true,
            keyboard: true
        },
        physics: {
            stabilization: false
        },
        configure: {
            filter:function (option, path) {
                if (path.indexOf('physics') !== -1) {
                    return true;
                }
                if (path.indexOf('smooth') !== -1 || option === 'smooth') {
                    return true;
                }
                return false;
            },
            container: configure_container
        }
    };

    var vis_canvas = new vis.Network(container, view_dataset, options);

    // add event listeners
    vis_canvas.on('select', function (params) {
        sysanalysis_load_node_detail(tab_div, params.nodes);
    });

    vis_canvas.on("resize", function (params) {
        console.log(params.width, params.height);
    });
}

function sysanalysis_load_node_detail(tab_div, node) {
    if (tab_div == '#net_scan') {
        var vulns = net_topology_data[project].nodes.get(node)[0].vulns;
        if (vulns == null ) {
            var str = '<p>Detail Info for ' + node + '</p>';
            document.getElementById('node_detail_sysana_netscan_detail').innerHTML = str;
        }
        else {
            var str = '<p>Detail Info for ' + node + '</p><p style="font-size:80%;"' + vulns + '</p>';
            document.getElementById('node_detail_sysana_netscan_detail').innerHTML = str;
        }
    } else if (tab_div == '#ag_viewer') {
        var str = '<p>Detail Info for ' + node + '</p>'; // + '</p><p style="font-size:80%;"' + vulns + '</p>';
        document.getElementById('node_detail_sysana_getag_detail').innerHTML = str;
    }
}

function security_analysis_network_scan() {
    if ($('#netscan_project_selector').val() < 0) {
        alert("Please Select a Project.");
        return;
    }
    if ($.isEmptyObject(net_topology_data[project])) {
        alert("Please Select a Project.");
        return;
    }

    var vm_list = [];
    var vm_sel_list = $('<div id="netscan_select_vm_div"></div>');
    $('<p style="color: red">The scanning process will take a couple of minutes. Please check the status in the tooltip.</p>').appendTo(vm_sel_list);
    $('<input type="checkbox" name="chkbox_netscan_target_vms" value="All" onchange="netsscan_change_selectedVM(this)"/>' +
        '&nbsp;&nbsp;<span>All</span><br>').appendTo(vm_sel_list);
    net_topology_data[project].nodes.forEach(function(node) {
        if (node.group == 'vm')
        {
            vm_list.push({id: node.id, vmid: node.vmid, ips: node.ips});
            $('<input type="checkbox" name="chkbox_netscan_target_vms" value="' + node.id + '" onchange="netsscan_change_selectedVM(this)" />' +
                '&nbsp;&nbsp;<span>' + node.id + ': ' + node.ips.split(' ')[0] + '</span><br>').appendTo(vm_sel_list);
        }
    });

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_netscan_target_vms').attr('title', 'Select Targeting VMs');

    vm_sel_list.appendTo(dlg_form);

    $('#dlg_netscan_target_vms').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 300,
        buttons: {
            "Scan": function () {
                var ip_range = [];
                var checked_vm = $('#netscan_select_vm_div').find('input[type=checkbox]:checked:enabled');
                for (var i = 0; i < checked_vm.length; i++) {
                    var vmid = checked_vm[i].value;
                    if (vmid == 'All') continue;
                    ip_range.push(vmid);
                    var tooltip = $.parseHTML(net_topology_data[project].nodes.get(vmid).title);
                    tooltip[0].childNodes[2].innerHTML = 'Security: ' +
                        '<img src="workspace-assets/images/icons/computer_scanning.gif" title="Scanning..."/>';
                    net_topology_data[project].nodes.update([{
                        id: vmid, label: vmid, // shape: 'image', group: group
                        title: tooltip[0].outerHTML
                        //image: get_vm_icon(data).icon
                    }]);
                    phy_topology_data[project].nodes.update([{
                        id: vmid, label: vmid, // shape: 'image', group: group
                        title: tooltip[0].outerHTML
                        //image: get_vm_icon(data).icon
                    }]);
                }
                // $.post("/workspace/netscan", {
                //         "snat_ns": $('#btn_netscan').attr('value'),
                //         "ip_range": ip_range
                //     },
                //     function (data) {
                //         //alert(JSON.stringify(data));
                //     },
                //     'json'
                // );

                security_analysis_load_netscan();

                $(this).dialog('close');
            },
            "Cancel": function () {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function netsscan_change_selectedVM(element) {
    var vmlist = $(element).closest('div').find('input[type=checkbox]');
    if (element.checked && element.value == "All") {
        for (var i = 0; i < vmlist.length; i++) {
            vmlist[i].checked = true;
        }
    } else if (!element.checked && element.value != "All") {
        var chk_all = $(element).closest('div').find('input[type=checkbox][value="All"]');
        if (chk_all[0].checked) {
            chk_all[0].checked = false;
        }
    }
}

function security_analysis_load_netscan() {
    if ($('#netscan_project_selector>option:selected').text() == 'NCIS1-58ed810648888') {
        // $.getJSON('/workspace/netscan', function(jsondata) {
        //     var output = jsondata;
        // });
        var vm_list = [];
        net_topology_data[project].nodes.forEach(function(node) {
            if (node.group == 'vm')
            {
                vm_list.push({id: node.id, vmid: node.vmid, ips: node.ips.split(' ')[0]});
            }
        });
        var snat_ns = $('#btn_netscan').attr('value');
        var jsonfile = ($('#chk_countermeasure').prop('checked')) ? 'test-after.json' : 'test.json';
        //var jsonfile = 'netscan_' + snat_ns + '.json';
        $.getJSON('/security/' + jsonfile, function (data) {
            $.each(data.systems, function (index, vm) {
                var open_ports = '';
                var vulns_str = '';
                $.each(vm.services, function (idx, service) {
                    if (service.vulnerabilities.length > 0) {
                        open_ports += '<span style="color:red">' + service.port + ' (' + service.name + ')</span><br>';
                        vulns_str += service.port + ' (' + service.name + ')<br>';
                        $.each(service.vulnerabilities, function (indx, vuln) {
                            vulns_str += '&nbsp;&nbsp;&nbsp;' + vuln.cveid + '<br>';
                        })
                    } else {
                        open_ports += service.port + ' (' + service.name + ')<br>';
                    }
                });
                var sVM = $.grep(vm_list, function (e) { return e.ips.indexOf(vm.ip) >= 0 });
                if (sVM && sVM.length == 1) {
                    var tooltip = $.parseHTML(net_topology_data[project].nodes.get(sVM[0].id).title);
                    tooltip[0].childNodes[2].innerHTML = 'Security: scanned @ 4/25/2017 14:30';
                    tooltip[0].childNodes[3].innerHTML = 'Open Ports:<br><p style="font-size:85%;">' + open_ports + '</p>';
                    net_topology_data[project].nodes.update([{
                        id: sVM[0].id, label: sVM[0].id, // shape: 'image', group: group
                        title: tooltip[0].outerHTML, vulns: vulns_str
                        //image: get_vm_icon(data).icon
                    }]);
                    phy_topology_data[project].nodes.update([{
                        id: sVM[0].id, label: sVM[0].id, // shape: 'image', group: group
                        title: tooltip[0].outerHTML, vulns: vulns_str
                        //image: get_vm_icon(data).icon
                    }]);
                }
            })
        });
    }
}

function generate_attack_graph(div_tab, div_container, slide_panel_id) {

    var inputfile = ($('#chk_countermeasure').prop('checked')) ? '/security/demo-case3' : '/security/demo-case2';
    if (slide_panel_id == 'sysana_attdetect') {
        inputfile = '/security/demo-case4';
        $.get(inputfile + '.alert', function (data) {
            $("#ag-tab4C").html(data);
        });
    }
    var DOTstring = '';
    $.get(inputfile + '.gv', function (data) {
        DOTstring = data;
    });
    $.get(inputfile + '.P', function (data) {
        $("#ag-tab2C").html(data);
    });
    $.get(inputfile + '.L', function (data) {
        $("#ag-tab1C").html(data);
    });
    $.get(inputfile + '.path', function (data) {
        $("#ag-tab3C").html(data);
    });

    var win_main = $(div_tab).closest('.window_main');
    //var container = document.getElementById(div_container);
    var container = $('#' + div_container);
    // var inner_container = $(document.createElement('div')).appendTo('#' + div_container);
    // inner_container.css('position', 'relative').css('width', '100%').css('height', '100%')
    //     .css('border', '1px solid lightgray').css('transform-origin', '0 0 0px').css('overflow', 'auto');
    run_waitMe(win_main, 'ios');
    setTimeout(function() {
        container.html(Viz(DOTstring, options = {format: "svg", engine: "dot"}));
        //$('svg').zoomSvg();
        $(container).panzoom({startTransform: "scale(1.0)", $zoomRange: $('#ag-slider'), focal: {clientX:0, clientY:0},
            minScale: 0.1, maxScale: 1.0, rangeStep: 0.05, disableYAxis: true, contain: 'automatic'});
        $(container).css('transform-origin', '0 0 0px');
        $(win_main).waitMe('hide');
    },1500);
}