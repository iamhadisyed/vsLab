function show_KG() {
    $("#layout").show();
    $("#nav-tabs").hide();
    $("#btn-vis-refresh").hide();
    $("#lab-env-topology").empty();
    $(".tab-pane").hide();
}

function vis_canvas_load_network_topology(project, isAdmin = false) {
    $("#layout").hide();
    $(".tab-pane").show();
    $("#nav-tabs").show();
    $("#btn-vis-refresh").show();
    var win_main = $("#lab-env-topology").empty();
    var net_topology = $(document.createElement('div')).appendTo(win_main);
    net_topology.attr('id', 'network_topology').css('width', '100%');

    var nodes = new vis.DataSet;
    var edges = new vis.DataSet;
    vis_net_topology_data[project] = {nodes: nodes, edges: edges};

    $('#btn-vis-openallconsole').hide();
    $('#btn-vis-refresh').css('background-color', '').css('width', '28').attr('disabled', 'disabled');
    $('#btn-vis-refresh').find('i').addClass('fa-spin');
    $('#vis-refresh-loading').show();
    //$('#vis-topology-progressbar').css('visibility', 'visible');

    // $.when(vis_populate_net_list(project)).then(function () {
    //run_waitMe(win_main, 'ios');
    //     $.getJSON("/cloud/getServers/" + project, function (jsondata) {
    $.getJSON("/cloud/getNetworkTopology/" + project, function (data)
    {
        //$('#vis-topology-progressbar').progressbar({ value: data.progress});

        vis_populate_net_list(project, data.network_list);
        $.each(data.vm_list, function (index, item) {
            var address_str = '<ul>';
            $.each(item.addresses, function (net, addresses) {
                address_str += '<li>' + net + ':';
                vis_net_topology_data[project].edges.add({from: item.id, to: net, length: 50});

                $.each(addresses, function (skey, address) {
                    address_str += address.addr + ' ';
                });
                address_str += '</li>';
            });
            address_str += '</ul>';

            var tooltip = '<div id="canvas-tooltip_' + item.id + '">';
            tooltip += '<div class="canvas-tooltip-networking">Networks:' + address_str + '</div>';
            tooltip += '<div class="canvas-tooltip-status">' + create_tooltip_vmstatus_buttons(project, item, isAdmin)
                + '</div></div>';

            var vm_icon = get_vm_icon(item);
            var group = vm_icon.group;
            var icon = vm_icon.icon;

            vis_net_topology_data[project].nodes.add({
                id: item.id, label: item.name, shape: 'image',
                title: tooltip, group: group, image: icon
            }); //"workspace-assets/images/icons/Hardware-My-Computer-3-icon.png" } );
        });

        setTimeout(function () {
            vis_canvas_render_network_topology(project);
        }, 1500);
        //$('#vis-topology-progressbar').css('visibility', 'hidden');

       // $('#net_topo_div_' + project).load(function() {
            $('#vis-refresh-loading').hide();
            $('#btn-vis-refresh').find('i').removeClass('fa-spin');
            $('#btn-vis-openallconsole').show();
        //});

        setTimeout(function () {
            $('#btn-vis-refresh').css('background-color', 'lightblue').css('width', '28').removeAttr('disabled');
        }, 200);

        vis_canvas_open_all_consoles(project);
    });
    //     });
    // });
    vis_canvas_prepare_network_topology('#network_topology', project);
}

function vis_canvas_prepare_network_topology(tab_div, project) {
    // var table = $(document.createElement('table')).appendTo($(tab_div));
    // table.css('width', '100%').append('<thead><tr><th></th></tr></thead>'); //.attr("class", "data");
    // var table_head = table.find('th');

    // $('<div id="vis_progressbar"></div>').css('background', 'Green').appendTo(table_head);
    // $('<p id="selection_' + project + '">Selection: None</p>').css('float', 'right').appendTo(table_head);

    var mynetwork_div = $(document.createElement('div')).appendTo($(tab_div));
    mynetwork_div.attr('id', 'net_topo_div_' + project).css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');

}

function vis_canvas_render_network_topology(project) {
    var container = document.getElementById('net_topo_div_' + project);
    var canvas_height = parseInt($('#net_topo_div_' + project).closest('div.row').css('height'));
    container.style.height = canvas_height - 163 + 'px';
    container.style.minHeight = '500px';
    // var configure_container = document.getElementById('network_topo_config_' + project);
    // configure_container.innerHTML = "";

    var options = {
        autoResize: true,
        height: '100%',
        width: '100%',
        interaction: {
            navigationButtons: true,
            hover: true
 //           keyboard: true
        },
        physics: {
            stabilization: false
        },
        configure: {
            enabled: false
        //     filter:function (option, path) {
        //         if (path.indexOf('physics') !== -1) {
        //             return true;
        //         }
        //         if (path.indexOf('smooth') !== -1 || option === 'smooth') {
        //             return true;
        //         }
        //         return false;
        //     },
        //     container: configure_container
        }
    };
    vis_net_topology[project] = new vis.Network(container, vis_net_topology_data[project], options);

    // add event listeners
    vis_net_topology[project].on('select', function (params) {
        document.getElementById('vis-topology-selection').innerHTML = 'Selection: ' + params.nodes;
    });

    vis_net_topology[project].on("resize", function (params) {
        console.log(params.width, params.height);
    });
}

function vis_populate_net_list(project, networkList) {
    //$.getJSON("/cloud/getNetworks/" + project, function (jsondata) {
        $.each(networkList, function (index, item) {
            var subnet_str = '';
            $.each(item.subnets, function (idx, val) {
                subnet_str += val.name + ':' + val.cidr + '<br>';
            });
            vis_net_topology_data[project].nodes.add({ id: item.name, label: item.name, shape: 'image',
                title: subnet_str, group: 'switch',
                image: DomainName + "workspace-assets/images/icons/network_switch.png" });

            var ports_str = '';
            $.each(item.ports, function (idx, val) {
                var ip_str = '';
                $.each(val.fixedIps, function (i, v) {
                    ip_str += v.ip_address;
                });
                ports_str += val.deviceOwner  + ':' + ip_str + '<br />';
                if (val.deviceOwner === 'network:router_interface_distributed' || val.deviceOwner === 'network:router_interface') {
                    vis_net_topology_data[project].nodes.add({ id: 'ext-router', label: 'External Router', shape: 'image',
                        title: ip_str, group: 'vrouter',
                        image: DomainName + "workspace-assets/images/icons/network_router-firewall.png" });
                    vis_net_topology_data[project].nodes.add({ id: 'Internet', label: 'Internet', shape: 'image',
                        title: 'Internet', group: 'internet',
                        image: DomainName + "workspace-assets/images/icons/System-Globe-icon.png" });
                    vis_net_topology_data[project].edges.add({from: 'ext-router', to: 'Internet', length: 50 });
                    vis_net_topology_data[project].edges.add({from: 'ext-router', to: item.name, length: 50 });
                }
            });
        });
    //});
}

function vis_canvas_open_all_consoles(project) {
    vis_net_topology_data[project].nodes.forEach(function(item, idx, obj) {
        if (item.group === 'vm') {
            get_vm_console(project, item.label, item.id);
        }
    });
    $('#lab-environment').find('#nav-tabs').find('a[href="#lab-env-topology-tab"]').tab('show');
}