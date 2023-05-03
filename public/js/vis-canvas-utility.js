/**
 * Created by root on 7/8/15.
 */

var DomainName = 'https://www2.thothlab.com/';

var PowerStatusEnum = {
    NOSTATE: 0, RUNNING: 1, BLOCKED: 2, PAUSED: 3, SHUTDOWN: 4,
    SHUTOFF: 5, CRASHED: 6, SUSPENDED: 7, FAILED: 8, BUILDING: 9,
    properties: {
        0: {name: "NO STATE", value: 0}, 1: {name: "RUNNING", value: 1},
        2: {name: "BLOCKED", value: 2},  3: {name: "PAUSED", value: 3},
        4: {name: "SHUTDOWN", value: 4}, 5: {name: "SHUTOFF", value: 5},
        6: {name: "CRASHED", value: 6},  7: {name: "SUSPENDED", value: 7},
        8: {name: "FAILED", value: 8},   9: {name: "BUILDING", value: 9}
    }
};

var ActionButtons = {
    DELETE: 0, ADD: 1, EDIT: 2,
    properties: {
        0: {name: "Delete", value: 0}, 1: {name: "Add", value: 1}, 2: {name: "Edit", value: 3}
    }
};

var vis_net_topology_data = {};
var vis_net_topology = {};

function vis_canvas_redraw(project) {
    vis_net_topology[project].redraw();
    return true;
}

function create_tooltip_vmstatus_buttons(project, vm, isAdmin) {
    // var selected-vm = $('selection_' + project + '">Selection: None
    var tooltip =
        'Status: <span class="canvas-tooltip-vmstatus">' + vm.status + '</span><br />' +
        'PowerState: <span class="canvas-tooltip-powerstate">' + PowerStatusEnum.properties[vm.powerState].name + '</span>';

    if (vm.taskState !== null) {
        tooltip += '<br />Task State: <span class="canvas-tooltip-taskstatus">' + vm.taskState + '</span>' +
                   '<br /><span class="progress_bar" style="width:120px; height: 10px; display: inline-block; background-size: 100% 100%"/></span>';
    }
    else {
        tooltip += '<div class="canvas-tooltip-buttons">';
        if (vm.powerState === PowerStatusEnum.RUNNING) { //'ACTIVE') {
            tooltip += '<button class="btn-tooltip get-console" title="Get Console" onclick="vm_actions($(this))" '
                + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                + '</button>';
            tooltip += '<button class="btn-tooltip vm-restart" title="Restart" onclick="vm_actions($(this))" '
                + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                + '</button>';
            tooltip += '<button class="btn-tooltip vm-rebuild" title="Rebuild" onclick="vm_actions($(this))" '
                + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                + '</button>';
            tooltip += '<button class="btn-tooltip vm-shutdown" title="Shutdown" onclick="vm_actions($(this))" '
                + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                + '</button>';
            if (isAdmin) {
                tooltip += '<button class="btn-tooltip vm-snapshot" title="Snapshot" onclick="vm_actions($(this))" '
                    + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                    + '</button>';
            }
            tooltip += '<button class="btn-tooltip vm-suspend" title="Suspend" onclick="vm_actions($(this))" '
                + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                + '</button>';
            tooltip += '<button class="btn-tooltip vm-pause" title="Pause" onclick="vm_actions($(this))" '
                + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                + '</button>';
        } else if (vm.powerState === PowerStatusEnum.SHUTDOWN) { //'SHUTOFF') {
            if (vm.status === 'SUSPENDED') {
                tooltip += '<button class="btn-tooltip vm-resume" title="Resume" onclick="vm_actions($(this))" '
                    + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                    + '</button>';
            } else {
                tooltip += '<button class="btn-tooltip vm-start" title="Start" onclick="vm_actions($(this))" '
                    + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                    + '</button>';
                tooltip += '<button class="btn-tooltip vm-rebuild" title="Rebuild" onclick="vm_actions($(this))" '
                    + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                    + '</button>';
            }
            //tooltip += '<button class="btn-tooltip vm-info" title="VM Info" onclick="vm_actions($(this))" '
            //+ 'name="' + item.name + '" value="' + vm_value + '">'
            //+ '</button>';
        } else if (vm.powerState === PowerStatusEnum.PAUSED) {
            tooltip += '<button class="btn-tooltip vm-unpause" title="Resume" onclick="vm_actions($(this))" '
                + 'name="' + vm.name + '" data-project="' + project + '" data-vmid="' + vm.id + '">'
                + '</button>';
        }
        tooltip += '</div>';
    }
    return tooltip;
}

function vm_actions(element) {
    var project = element.attr('data-project');
    var vm_name = element.attr('name');
    //var vm_value = element.attr('value');
    var vm_uuid = element.attr('data-vmid');

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
    } else if (element.hasClass('vm-pause')) {
        action = "Pause";
    } else if (element.hasClass('vm-unpause')) {
        action = "Unpause";
    } else if (element.hasClass('vm-snapshot')) {
        action = "Snapshot";
    }
    else if (element.hasClass('vm-info')) {
        get_vm_info(project, vm_name, vm_uuid);
        return true;
    } else if (element.hasClass('get-console')) {
        get_vm_console(project, vm_name, vm_uuid);
        return true;
    }

    var message =  '<p> Are you sure you want to ' + action + ' ' + vm_name + '?</p>';
    if (action === 'Rebuild')
        message += '<p style="color:red">The VM will be restored to its original state.<br />' +
                   'You will LOSE All YOUR DATA in this VM if you rebuild it.</p>';
    create_ConfirmDialog('#lab-env-topology', action + ' a VM', message, 400, "#lab-env-topology-tab",
        function() {
            var icon =  DomainName + "workspace-assets/images/icons/network_terminal-red.png";
            var vm_group = vis_net_topology_data[project].nodes.get(vm_uuid).group;
            if (vm_group === 'quagga') icon =  DomainName + "workspace-assets/images/icons/network_router-red.png";

            vis_net_topology_data[project].nodes.update([{
                id: vm_uuid, label: vm_name,
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
                        update_topology_canvas(project, vm_name, vm_uuid);
                    //}, 500);
                },
                'json'
            )
        },
        function() {
            //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
        });

    // var newtooltip = '<div id="canvas-tooltip_' + vm_uuid + '">' + tooltip.html() + '</div>';
    // vis_net_topology_data[project].nodes.update([{
    //    id: vm_name, label: vm_name, // shape: 'image', group: group
    //    title: newtooltip,
    //    image: DomainName + "workspace-assets/images/icons/network_terminal-red.png"
    // }]);
}

function update_topology_canvas(project, vm_name, vm_uuid) {
    var tooltip = $.parseHTML(vis_net_topology_data[project].nodes.get(vm_uuid).title);
    var icon = DomainName + "workspace-assets/images/icons/network_terminal-red.png";
    var vm_group = vis_net_topology_data[project].nodes.get(vm_uuid).group;
    if (vm_group === 'quagga') icon = DomainName + "workspace-assets/images/icons/network_router-red.png";
    //
    //net_topology_data[project].nodes.update([{
    //    id: vm_name, label: vm_name,
    //    image: icon,
    //    title: tooltip[0].outerHTML
    //}]);

    $.getJSON("/cloud/getVM/" + project + "/" + vm_uuid, function (data) {
        tooltip[0].childNodes[1].innerHTML = create_tooltip_vmstatus_buttons(project, data);

        if (data.taskState === null) {
            vis_net_topology_data[data.tenantId].nodes.update([{
                id: vm_uuid, label: vm_name, // shape: 'image', group: group
                title: tooltip[0].outerHTML,
                image: get_vm_icon(data).icon
            }]);
            var console_tab = $('#lab-environment').find('#nav-tabs').find('a[href="#lab-env-' + vm_uuid + '"]');
            if (console_tab.length > 0 ) {
                get_vm_console(project, vm_name, vm_uuid)
            }
        } else {
            vis_net_topology_data[data.tenantId].nodes.update([{
                id: vm_uuid, label: vm_name, // shape: 'image', group: group
                title: tooltip[0].outerHTML,
                image: icon
            }]);
            setTimeout(function () {
                update_topology_canvas(project, vm_name, vm_uuid);
            }, 1500);
        }
    });
}

function get_vm_info(project, vm_name, vm_uuid) {
    //var vmId = vmValue.substring(('vm_' + project + '_').length);
    $.getJSON("/cloud/getVM/" + project + "/" + vm_uuid, function (data) {
        alert(JSON.stringify(data));
    });
}

function get_vm_console(project, vm_name, vm_uuid) {
    var nav_tabs = $('#lab-environment').find('#nav-tabs');
    var tab_content = $('#lab-environment').find('.tab-content');
    //nav_tabs.find('.active').removeClass('active');

    var new_tab = nav_tabs.find('a[href="#lab-env-' + vm_uuid + '"]');

    if (new_tab.length === 0) {
        $('<li><a href="#lab-env-' + vm_uuid + '" data-toggle="tab">' + vm_name + '</a></li>').appendTo(nav_tabs);
        var new_tab_content = $('<div class="active tab-pane" id="lab-env-' + vm_uuid + '"></div>').appendTo(tab_content);

        var div_console = $('<div />').appendTo(new_tab_content);
        div_console.addClass('box-body');

        var iframe_console = $(document.createElement('iframe')).appendTo(div_console);
        iframe_console.attr("id", "vm_console_" + vm_uuid).attr("width", "100%").css('height','80vh')
            .attr("src", "/cloud/getConsole/" + project + "/" + vm_uuid).attr('onload', 'vis_console_loaded($(this))');
        // $(window).resize(function(){
        //
        //         $("#vm_console_" + vm_uuid).css('height','100vh').css('height', '-=250px');
        //
        // });
    } else {
        $('#lab-env-' + vm_uuid).find('iframe').attr('src', $('#lab-env-' + vm_uuid).find('iframe').attr('src'));
    }

    nav_tabs.find('a[href="#lab-env-' + vm_uuid + '"]').tab('show');
}

function vis_console_loaded(iframe) {
    var div_console = iframe.parent();
    var vm_uuid = iframe.attr('id').substring('vm_console_'.length);

    var overlay_btn_div = $('<div />').prependTo(div_console);
    overlay_btn_div.css('position', 'absolute').css('height', '20pt').css('z-index', '50').css('opacity', '0.8')
        .css('margin-left', '3pt').css('margin-top', '2pt');

    var overlay_btn_close = $(document.createElement('button')).appendTo(overlay_btn_div);
    overlay_btn_close.attr("id", "btn_console_close_" + vm_uuid).attr("title", "Close Console")
        .attr("onclick", "vis_console_overlay_button(1, '" + vm_uuid + "')").css('margin-right', '1pt')
        .css("width", "20pt").css("height", "20pt");
    overlay_btn_close.append('<i class="fa fa-window-close" style="margin-left: -5pt"></i>');

    var overlay_btn_refresh = $(document.createElement('button')).appendTo(overlay_btn_div);
    overlay_btn_refresh.attr("id", "btn_console_refresh_" + vm_uuid).attr("title", "Refresh Console")
        .attr("onclick", "vis_console_overlay_button(2, '" + vm_uuid + "')").css('margin-right', '1pt')
        .css("width", "20pt").css("height", "20pt");
    overlay_btn_refresh.append('<i class="fa fa-refresh" style="margin-left: -5pt"></i>');

    var overlay_btn_screenshot = $(document.createElement('button')).appendTo(overlay_btn_div);
    overlay_btn_screenshot.attr("id", "btn_console_screenshot_" + vm_uuid).attr("title", "Take a Screenshot")
        .attr("onclick", "vis_console_overlay_button(3, '" + vm_uuid + "')").css('margin-right', '1pt')
        .css("width", "20pt").css("height", "20pt");
    overlay_btn_screenshot.append('<i class="fa fa-camera-retro" style="margin-left: -5pt"></i>');

    var overlay_btn_openintab = $(document.createElement('button')).appendTo(overlay_btn_div);
    overlay_btn_openintab.attr("id", "btn_console_openintab_" + vm_uuid).attr("title", "Open Console in a New Tab")
        .attr("onclick", "window.open('https://www.thothlab.com"+iframe.attr('src')+"')").css('margin-right', '1pt')
        .css("width", "20pt").css("height", "20pt");
    overlay_btn_openintab.append('<i class="fa fa-external-link" style="margin-left: -5pt"></i>');


}

function create_ConfirmDialog(div_id, title, ask, width, containment, okCallback, cancelCallback) {
    var dlg_form = $(document.createElement('div')).appendTo($(div_id));
    dlg_form.attr('id', 'dialog_confirm').attr('title', title).css('float', 'left').css('width','100%').css('max-height','80%').css('overflow-y', 'auto');

    //var form_html = '<p>' + ask + '</p>';
    $(ask).css('margin-top', '25px').appendTo(dlg_form);
    $('#dialog_confirm').dialog({
        resizable: false,
        position: {my: "center", at: "center", of: containment},
        modal: true,
        width: width,
        buttons: {
            "Yes": function () {
                okCallback();
                $(this).dialog('close');
            },
            "Cancel": function () {
                cancelCallback();
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
    $(dlg_form).closest('.ui-dialog').css('z-index', '1060');
    $('.ui-widget-content').css('background-color', 'beige');
    $('.ui-dialog-titlebar').css('height', '35px');
    $('.ui-dialog-titlebar-close').css('display', 'none');
}

function create_SimpleDialog(div_id, title, ask, width, containment, okCallback) {
    var dlg_form = $(document.createElement('div')).appendTo($(div_id));
    dlg_form.attr('id', 'dialog_confirm').attr('title', title).css('float', 'left').css('width','100%').css('max-height','80%').css('overflow-y', 'auto');

    //var form_html = '<p>' + ask + '</p>';
    $(ask).css('margin-top', '25px').appendTo(dlg_form);
    $('#dialog_confirm').dialog({
        resizable: false,
        position: {my: "center", at: "center", of: containment},
        modal: true,
        width: width,
        buttons: {
            "Yes": function () {
                okCallback();
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
    $(dlg_form).closest('.ui-dialog').css('z-index', '1060');
    $('.ui-widget-content').css('background-color', 'beige');
    $('.ui-dialog-titlebar').css('height', '35px');
    $('.ui-dialog-titlebar-close').css('display', 'none');
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
        else icon = "workspace-assets/images/icons/network_terminal-gray.png";
    }
    else {
        if (os_type === 'quagga') icon = "workspace-assets/images/icons/network_router-black.png";
        else icon = "workspace-assets/images/icons/network_terminal-black.png";
    }
    return {'group': group, 'icon': DomainName + icon};
}


function vis_console_overlay_button(action, vm_uuid) {

    if (action === 1) {  // close
        $('#lab-env-' + vm_uuid).remove();
        $('#lab-environment').find('#nav-tabs').find('a[href="#lab-env-' + vm_uuid + '"]').parent().remove();

        $('#lab-environment').find('#nav-tabs').find('a[href="#lab-env-topology-tab"]').tab('show');
    } else if (action === 2) {  // refresh
        $('#lab-env-' + vm_uuid).find('iframe').attr('src', $('#lab-env-' + vm_uuid).find('iframe').attr('src'));
    } else if (action === 3) {  // screenshot

        var iframe = $('#vm_console_' + vm_uuid);
        var canvas = iframe.contents().find('canvas');
        var dataURL = canvas.get(0).toDataURL();
        if (dataURL === undefined) {
            alert('Screenshot failed!!');
        } else {
            var image = '<div style="height: 400px; width: 550px;"><img src="' +  dataURL + '" height="400px" width="550px" /></div>';
            create_SimpleDialog('.container-fluid', 'Screenshot(Right Click to Save)', image, 600, '.container-fluid',
                function() {

                },
                function() {
                    //update_topology_canvas(project, vm_name, vm_uuid, vm_value);
                });
        }
    }
}


function open_content(){
    $("#labcontent").show();
    $("#labenv").addClass('col-md-6');
    $("#showtask").hide();

    if ($('.container-fluid').innerWidth() > 768) {
        $('.gutter').show();
        $("#hideenv").show();
        $('#labcontent').css('width', 'calc(50% - 3px)');
        $('#labenv').css('width', 'calc(50% - 3px)');
    }
}

function close_content(){
    if ($('.gutter').css('display') !== 'none') {
        $('.gutter').hide();
        $('#labcontent').removeAttr('style');
        $('#labenv').removeAttr('style');

    }
    $("#showtask").show();
    $("#labcontent").hide();
    $("#hideenv").hide();
    $("#labenv").removeClass('col-md-6');
}

function hide_env(){
    if ($('.gutter').css('display') !== 'none') {
        $('.gutter').hide();
        $('#labcontent').removeAttr('style');
        $('#labenv').removeAttr('style');

    }
    // $("#showtask").show();
    // $("#labcontent").hide();
    // $("#labenv").removeClass('col-md-6');
    $("#showenv").show();
    $("#labenv").hide();
    $("#closetask").hide();
    $("#labcontent").removeClass('col-md-6');
}

function open_env(){
    $("#labenv").show();
    $("#closetask").show();
    $("#labcontent").addClass('col-md-6');
    $("#showenv").hide();

    if ($('.container-fluid').innerWidth() > 768) {
        $('.gutter').show();
        $('#labcontent').css('width', 'calc(50% - 3px)');
        $('#labenv').css('width', 'calc(50% - 3px)');
    }
}