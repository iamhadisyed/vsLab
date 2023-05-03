/**
 * Created by root on 2/19/15.
 */
function populate_heat_templates(winId, tab_div) {

    var table = $(document.createElement('table')).appendTo($(tab_div));
    table.addClass("data").attr("id", "tbl_heat_templates").append('<thead><tr>' +
    '<th class="shrink">&nbsp;</th>' + '' +
    '<th>Name</th>' +
    '<th>Description</th>' +
    '<th class="hidden">ID</th>' +

    '<th>Actions</th>' +
    '<th>Notes</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    //run_waitMe(win_main, 'ios');
    //$.getJSON("/cloud/getProjects", function (jsondata) {
    //    $.each(jsondata, function (index, item) {
    //        $('<tr>' +
    //        '<td><img src="' + ICON_folder_sm + '"></img></td>' +
    //        '<td>' + item.name + '</td>' +
    //        '<td>' + item.description + '</td>' +
    //        '<td class="hidden">' + item.id + '</td>' +
    //        '<td><button type="button" class="proj_member"><i class="fa fa-users"></i></button></td>' +  //"<td>" + item.users + "</td>" +
    //        '<td class="dropdown"><a class="btn btn-default proj-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
    //        '<td>&mdash;</td>' +
    //        '</tr>').appendTo(tbody);
    //    });
    //
    //    $(winId).find('div.window_bottom')
    //        .text(jsondata.length + ' Projects (Double-Click the selected project to open the project content)');
    //    $(win_main).waitMe('hide');
    //});
    //
    //var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    //contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'proj-contextMenu')
    //    .css('top', '80%').css('font-size', '12px').css('right', '0px').css('left', 'auto');
    //$('<li><a tabindex="-1" href="#" class="proj-edit">Edit</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="proj-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);
}