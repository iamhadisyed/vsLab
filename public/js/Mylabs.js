/**
 * Created by root on 7/8/15.
 */
function edit_lab(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'lab_edit').attr('title', 'Edit Lab '+element.attr('name'));

    var form_html = '<input class="hidden" name="got_template_id" value="'+element.value+'">' +
        '<button id="assign_lab_temp">Assign Another Lab Template</button>' +
        '<hr/>' +
        '<label>Or you can change the Duration of this Lab:</label><br><br>'+
        '<input name="lab_time_input_txt">'+
        '<select><option id="1" value="hour">Hour</option><option id="1" value="day">Day</option><option id="1" value="week">Week</option><option id="1" value="month">Month</option></select>' +
        '<br><br><br><br><button style="float:right" id="share_temp_user_btn">Save Change</button>'+
        '</div>';
    $(form_html).appendTo(dlg_form);

    $('#lab_edit').dialog({
        modal: true,
        height: 200,
        overflow: "auto",
        width: 400,
        close: function (event, ui) {
            $(this).remove();
        }
    });

}