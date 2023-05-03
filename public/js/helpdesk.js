/**
 * Created by root on 8/17/16.
 */

function windows_helpdesk_display(winId, win_main) {
    var tabs = {
        tabId: ['help_tickets'],
        tabName: ['My Tickets']
    };
    create_tabs(winId, win_main, tabs, null);

    $('<iframe src="https://localhost/tickets" frameborder="0" scrolling="no" id="tickets" width="100%" height="100%"></iframe>')
        .appendTo($('#help_tickets'));
}

function windows_helpdesk_display_old(winId, win_main) {
    var tabs = {
        tabId: ['help_create_ticket', 'support_center', 'agent_panel'],
        tabName: ['New Ticket', 'Support Center', 'Agent Panel']
    };
    create_tabs(winId, win_main, tabs, null);

    var support_form = $(document.createElement('div')).appendTo($('#help_create_ticket'));

    var form_html = '<form method="post" id="form_support" class="contact_form" >' +
        '<ul>' +
            '<li><h2>Customer Support</h2>&nbsp;&nbsp;' +
                //'<a id="customer_support_help" title="Help">Help</a>' +
                '<span class="required_notification">* Denotes Required Field</span></li>' +
            '<li><label><span style="color: red">*</span>Category: </label>' +
                '<select id="select_help_category" >' +
                    '<option value="0">Lab Issues (to instructor/TA)</option>' +
                    '<option value="1">Workspace Issues (to system admin)</option>' +
                '</select>' +
            '</li>' +
            '<li><label><span style="color: red">*</span>Subject: </label><input required name="help_subject" style="width:400px;"></li>' +
            '<li><label><span style="color: red">*</span>Description: </label><textarea required rows="5" cols="100" style="width:400px;" id="help_description" /></li>' +
            '<li><table border="0" style="width:500px;"><tr><td style="border-width:0px;">' +
                '<button onclick="take_screenshot($(this))">Take Screenshot</button></td>' +
                '<td style="border-width:0px;"><div id="help_screenshot_thumbnail"></div></td></tr></table></li>' +
        '</ul>' +
            '<li><button class="submit" onclick="submit_help_form($(this))">Submit</button></li>' +
        '</form>';
    $(form_html).appendTo(support_form);
    $('<div id="div_screenshot"></div>').appendTo(support_form);

    var div_support_center = $(document.createElement('div')).appendTo('#support_center');
    var iframe_support_center = $(document.createElement('iframe'));
    iframe_support_center.attr("name", "support_center").attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://ticket.thothlab.org/login.php?do=ext&bk=cas.client").appendTo(div_support_center);

    var div_agent_panel = $(document.createElement('div')).appendTo('#agent_panel');
    var iframe_agent = $(document.createElement('iframe'));
    iframe_agent.attr("name", "agent").attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://ticket.thothlab.org/scp/login.php?do=ext&bk=cas").appendTo(div_agent_panel);
}

function take_screenshot(element) {
    //Feedback({h2cPath:'packages/html2canvas/html2canvas.js'});
    $.feedback({
        ajaxURL: null,
        html2canvasURL: 'packages/feedback/html2canvas.js'
    });
}
/*
function take_screenshot(element) {
    $(element).closest('div.window').hide();
    html2canvas($('#desktop'), {
        logging: true,
        proxy: 'https://webdev2.mobicloud.asu.edu/html2canvasproxy.php',
        useCORS: true,
        onrendered: function(canvas) {
            $('#help_screenshot').remove();
            $('#help_screenshot_thumbnail').empty();
            //var img = new Image();
            //img.src = canvas.toDataURL("image/png");
            //img.width = 150;
            //img.height = 100;
            //img.title = '<img src="' + Canvas2Image.convertToImage(canvas, 100, 100, "image/png") + '" />';
            $('#help_screenshot_thumbnail').append(Canvas2Image.convertToPNG(canvas, 150, 100));
            $('#div_screenshot').append(canvas).find('canvas').last()
                //.css('display', 'none')
                .attr('id', 'help_screenshot');
        }
    });
    setTimeout(function() {
        $('#dock').find('a[href="#window_help"]').click();
    }, 500);
}
*/
function submit_help_form(element) {
    alert('This function is coming very soon.');
}

function take_console_screeshot(divId) {
    var iframe = document.getElementById(divId);

    iframe.postMessage('takeScreenshot', 'https://vm-console.thothlab.org');

    $.jAlert("Test <img src='" + img.src + "'>", "A jAlert");
    //canvas.toBlob(function(blob) {
    //    saveAs(blob, "test.png")
    //});
}