/**
 * Created by root on 4/28/15.
 */

function populate_owncloud(winId, win_main) {
    // disbale xframe_restriction in owncloud/config/config.php
    var iframe_console = $(document.createElement('iframe')).appendTo(win_main);
    iframe_console//.attr("name", "vm_console_" + "owncloud")
        .attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://personal.thothlab.org/");
    $(winId).find('div.window_bottom').text('Files');
}
function populate_wordpress(winId, win_main) {
    // disbale xframe_restriction in owncloud/config/config.php
    var iframe_console = $(document.createElement('iframe')).appendTo(win_main);
    iframe_console//.attr("name", "vm_console_" + "owncloud")
        .attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://support.thothlab.org/wordpress/wp-admin/");
    $(winId).find('div.window_bottom').text('Blog'); // Blog & Ticket
}

function populate_owncloud2(winId, win_main) {
    var tabs = {
        tabId: ['AllFiles', 'SharedWithYou', 'SharedWithOthers', 'SharedByLink'],
        tabName: ['All files', 'Shared with you', 'Shared with others', 'Shared by link']
    };

    create_tabs(winId, win_main, tabs, null);

    $(winId).find('div.window_bottom').text('Files');

    var html = '<table id="owncloudAllfiles" class="data"></table>';
    $(html).appendTo($('#AllFiles'));
    display_all_file_tab('');
}

function display_all_file_tab(path) {
    $.post("cloud/getOwncloudFilelist", {
        "path": path
    }, function (jsondata) {
        var table = $("#owncloudAllfiles");
        table.empty();
        table.append("<tr><th>Name</th><th>Modified</th></tr>");
        $.each(jsondata, function (index, item) {
            var name = decodeURI(index.substring('/remote.php/webdav'.length));
            if (name != '/') {
                table.append("<tr><td>" + name + "</td><td>" + item['{DAV:}getlastmodified'] + "</td></tr>");
            }
        });
    }, 'json');
}