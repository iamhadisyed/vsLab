/**
 * Created by root on 7/8/15.
 */

function create_tooltip_vmstatus_buttons(item, vm_value) {
    // var selected-vm = $('selection_' + project + '">Selection: None
    var tooltip =
        'Status: <span class="canvas-tooltip-vmstatus">' + item.status + '</span><br />' +
        'PowerState: <span class="canvas-tooltip-powerstate">' + PowerStatusEnum.properties[item.powerState].name + '</span>';

    if (item.taskState !== null) {
        tooltip += '<br />Task State: <span class="canvas-tooltip-taskstatus">' + item.taskState + '</span>' +
                   '<br /><span><img src= "img/waiting.gif" width="100" height="20" style="margin-left: auto; margin-right: auto"/></span>';
    }
    else {
        tooltip += '<div class="canvas-tooltip-buttons">';
        if (item.powerState === PowerStatusEnum.RUNNING) { //'ACTIVE') {
            tooltip += '<button class="btn-tooltip get-console" title="Get Console" '
            + 'name="' + item.name + '" value="' + vm_value + '">'
            + '</button>';
            tooltip += '<button class="btn-tooltip vm-restart" title="Restart" onclick="vm_actions($(this))" '
            + 'name="' + item.name + '" value="' + vm_value + '">'
            + '</button>';
            tooltip += '<button class="btn-tooltip vm-rebuild" title="Rebuild" onclick="vm_actions($(this))" '
            + 'name="' + item.name + '" value="' + vm_value + '">'
            + '</button>';
            tooltip += '<button class="btn-tooltip vm-shutdown" title="Shutdown" onclick="vm_actions($(this))" '
            + 'name="' + item.name + '" value="' + vm_value + '">'
            + '</button>';
            tooltip += '<button class="btn-tooltip vm-suspend" title="Suspend" onclick="vm_actions($(this))" '
            + 'name="' + item.name + '" value="' + vm_value + '">'
            + '</button>';
            tooltip += '<button class="btn-tooltip vm-pause" title="Pause" onclick="vm_actions($(this))" '
            + 'name="' + item.name + '" value="' + vm_value + '">'
            + '</button>';
        } else if (item.powerState === PowerStatusEnum.SHUTDOWN) { //'SHUTOFF') {
            if (item.status === 'SUSPENDED') {
                tooltip += '<button class="btn-tooltip vm-resume" title="Resume" onclick="vm_actions($(this))" '
                + 'name="' + item.name + '" value="' + vm_value + '">'
                + '</button>';
            } else {
                tooltip += '<button class="btn-tooltip vm-start" title="Start" onclick="vm_actions($(this))" '
                + 'name="' + item.name + '" value="' + vm_value + '">'
                + '</button>';
                tooltip += '<button class="btn-tooltip vm-rebuild" title="Rebuild" onclick="vm_actions($(this))" '
                + 'name="' + item.name + '" value="' + vm_value + '">'
                + '</button>';
            }
            //tooltip += '<button class="btn-tooltip vm-info" title="VM Info" onclick="vm_actions($(this))" '
            //+ 'name="' + item.name + '" value="' + vm_value + '">'
            //+ '</button>';
        } else if (item.powerState === PowerStatusEnum.PAUSED) {
            tooltip += '<button class="btn-tooltip vm-unpause" title="Resume" onclick="vm_actions($(this))" '
                + 'name="' + item.name + '" value="' + vm_value + '">'
                + '</button>';
        }
        tooltip += '</div>';
    }
    return tooltip;
}

function get_vm_icon(vm) {
    var group = "";
    var icon = "";
//    var os_type =vm.image.os_distro;
    var os_type = vm.image.name.split("-")[0];
//    var os_version = vm.image.os_version;

    if (vm.status === 'ACTIVE') {
        switch (os_type) {
            case 'quagga':
            case 'Quagga':
                icon = "workspace-assets/images/icons/network_router.png";
                group = 'quagga';
                break;
            case 'ubuntu':
            case 'Ubuntu':
                icon = "workspace-assets/images/icons/terminal-ubuntu.png";
                group = 'vm';
                break;
            case 'windows':
            case 'Windows':
                icon = "workspace-assets/images/icons/terminal-windows.png";
                group = 'vm';
                break;
            case 'Redhat':
            case 'redhat':
                icon = "workspace-assets/images/icons/terminal-redhat.png";
                group = 'vm';
                break;
            case 'Kali':
            case 'kali':
                icon = "workspace-assets/images/icons/terminal-kali.png";
                group = 'vm';
                break;
            case 'Meta':
            case 'meta':
                icon = "workspace-assets/images/icons/terminal-Meta.png";
                group = 'vm';
                break;
            case 'Centos':
            case 'centos':
                icon = "workspace-assets/images/icons/terminal-centos.png";
                group = 'vm';
                break;
            case 'Fedora':
            case 'fedora':
                icon = "workspace-assets/images/icons/terminal-fedora.png";
                group = 'vm';
                break;
            case 'debian':
            case 'Debian':
                icon = "workspace-assets/images/icons/terminal-debian.png";
                group = 'vm';
                break;
            case 'suse':
            case 'Suse':
                icon = "workspace-assets/images/icons/terminal-suse.png";
                group = 'vm';
                break;
            case 'netbsd':
            case 'Netbsd':
                icon = "workspace-assets/images/icons/terminal-netBSD.png";
                group = 'vm';
                break;
            case 'openbsd':
            case 'Openbsd':
                icon = "workspace-assets/images/icons/terminal-openBSD.png";
                group = 'vm';
                break;
            default:
                icon = "workspace-assets/images/icons/network_terminal.png";
                group = 'vm';
        }
    }
    else if (vm.status === 'SUSPENDED') {
        if (os_type === 'quagga') icon = "workspace-assets/images/icons/network_router-gray.png";
        else icon = "workspace-assets/images/icons/network_terminal-gray.png";
    }
    else if (vm.status === 'PAUSED') {
        if (os_type === 'quagga') icon = "workspace-assets/images/icons/network_router-gray.png";
        else icon = "workspace-assets/images/icons/network_terminal-green.png";
    }
    else {
        if (os_type === 'quagga') icon = "workspace-assets/images/icons/network_router-black.png";
        else icon = "workspace-assets/images/icons/network_terminal-black.png";
    }
    return {'group': group, 'icon': icon};
}

function vm_actions(element) {
    var project = element.closest('div.window').attr('id').substring('#window_project_'.length-1);
    var vm_name = element.attr('name');
    var vm_value = element.attr('value');
    var vm_uuid = element.attr('value').substring(('vm_' + project + '_').length);

    var action = "";
    if (element.hasClass('vm-restart')) {
        action = "Reboot";
    } else if (element.hasClass('vm-rebuild')) {
        action = "Rebuild";
    } else if (element.hasClass('vm-shutdown')) {
        action = "Shutdown";
    } else if (element.hasClass('vm-start')) {
        action = "Start";
    } else if (element.hasClass('vm-suspend')) {
        action = "Suspend";
    } else if (element.hasClass('vm-resume')) {
        action = "Resume";
    } else if (element.hasClass('vm-info')) {
        get_vm_info(project, vm_name, vm_uuid);
        return true;
    }

    var message =  'Are you sure you want to ' + action + ' ' + project + ':' + vm_name + '?<br />';
    if (action === 'Rebuild')
        message += '<p style="color:red">The VM will be restored to its original state.<br />' +
                   'You will LOSE All YOUR DATA in this VM if you rebuild it.</p>';
    create_ConfirmDialog(action + ' a VM', message,
        function() {
            var icon = "workspace-assets/images/icons/network_terminal-red.png";
            var vm_group = net_topology_data[project].nodes.get(vm_name).group;
            if (vm_group === 'quagga') icon = "workspace-assets/images/icons/network_router-red.png";

            net_topology_data[project].nodes.update([{
                id: vm_name, label: vm_name,
                image: icon
                //title: tooltip[0].outerHTML
            }]);

            $.post("/cloud/vmAction", {
                    //"_token": $(this).find('input[name=_token]').val(),
                    "project": project,
                    "vmId": vm_uuid,
                    "action": action
                },
                function (data) {
                    //setTimeout(function () {
                        update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                    //}, 500);
                },
                'json'
            )
        },
        function() {
            //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
        });

    //var newtooltip = '<div id="canvas-tooltip_' + vm_value + '">' + tooltip.html() + '</div>';
    //net_topology_data[project].nodes.update([{
    //    id: vm_name, label: vm_name, // shape: 'image', group: group
    //    title: newtooltip,
    //    image: "workspace-assets/images/icons/network_terminal-red.png"
    //}]);
}

function update_topology_canvas(project, vm_name, vm_uuid, vm_value) {
    var tooltip = $.parseHTML(net_topology_data[project].nodes.get(vm_name).title);
    var icon = "workspace-assets/images/icons/network_terminal-red.png";
    var vm_group = net_topology_data[project].nodes.get(vm_name).group;
    if (vm_group == 'quagga') icon = "workspace-assets/images/icons/network_router-red.png";
    //
    //net_topology_data[project].nodes.update([{
    //    id: vm_name, label: vm_name,
    //    image: icon,
    //    title: tooltip[0].outerHTML
    //}]);

    $.getJSON("/cloud/getVM/" + project + "/" + vm_uuid, function (data) {
        tooltip[0].childNodes[1].innerHTML = create_tooltip_vmstatus_buttons(data, vm_value);

        if (data.taskState === null) {
            net_topology_data[project].nodes.update([{
                id: vm_name, label: vm_name, // shape: 'image', group: group
                title: tooltip[0].outerHTML,
                image: get_vm_icon(data).icon
            }]);
        } else {
            net_topology_data[project].nodes.update([{
                id: vm_name, label: vm_name, // shape: 'image', group: group
                title: tooltip[0].outerHTML,
                image: icon
            }]);
            setTimeout(function () {
                update_topology_canvas(project, vm_name, vm_uuid, vm_value);
            }, 500);
        }
    });
}

function get_vm_info(project, vm_name, vm_uuid) {
    //var vmId = vmValue.substring(('vm_' + project + '_').length);
    $.getJSON("/cloud/getVM/" + project + "/" + vm_uuid, function (data) {
        alert(JSON.stringify(data));
    });
}