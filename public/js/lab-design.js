function lab_design_management(win_main) {
    var tbody = $('#tbl_lab_design_list tbody');
    tbody.empty();
    //run_waitMe(tbody, 'ios');
    $.getJSON("/cloud/getLabDesign", function (jsondata) {
        $.each(jsondata, function (index, item) {
            $('<tr>' +
                '<td class="hidden">' + item.lab_id + '</td>' +
                '<td>' + item.lab_name + '</td>' +
                '<td class="hidden">' + item.temp_id + '</td>' +
                '<td>' + item.temp_name + '</td>' +
                '<td class="hidden">' + item.content_id + '</td>' +
                '<td class="hidden">' + item.term_id + '</td>' +
                '<td>' + item.content_id + '</td>' +
                '<td>' + item.description + '</td>' +
                '<td class="dropdown"><a class="btn btn-default labDesign-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                '</tr>').appendTo(tbody);

        });
        $(win_main).waitMe('hide');
    });
    var contextMenuown = $(document.createElement('ul')).appendTo(tbody);
    contextMenuown.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'labDesign-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="labDesign-view">View</a></li>').appendTo(contextMenuown);
    $('<li><a tabindex="-1" href="#" class="labDesign-edit">Edit</a></li>').appendTo(contextMenuown);
    $('<li><a tabindex="-1" href="#" class="labDesign-delete">Delete</a></li>').appendTo(contextMenuown);
    //$('<li><a tabindex="-1" href="#" class="ownclass-assign">Assign Template</a></li>').appendTo(contextMenuown);
}

function create_a_new_lab() {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_create_a_lab').attr('title', 'Compose a New Lab');

    $('<div><span class="required_notification">* Denotes Required Field</span></li><br><span style="color: red">*</span><label for="new_lab_name">Lab name:</label>&nbsp;' +
        '<input type="text" id="new_lab_name" ><br><br>' +
        '<span style="color: red">*</span><label for="select_lab_template">Select a Lab Running Environment:</label>&nbsp;' +
        '<select id="select_lab_template"></select><br><br>' +
        '<span style="color: red">*</span><label for="select_lab_content">Select  Lab Content:</label>&nbsp;' +
        '<br><select id="select_lab_content"></select><br><br>' +
        '<span style="color: red">*</span><label for="new_lab_desc">Description:</label><br>' +
        '<textarea id="new_lab_desc" style="width: 250px; height: 100px; resize: none;"></textarea></div>').appendTo(dlg_form);

    $('#dlg_create_a_lab').dialog({
        modal: true,
        height: 320,
        overflow: "auto",
        width: 300,
        buttons: {
            "Compose": function () {
                if ($('#new_lab_name').val().trim()==''||$('#new_lab_desc').val().trim()=='' ){
                    alert('Please finish all required fields!');
                } else if($('#select_lab_template :selected').val()==$('#select_lab_content :selected').val()&&$('#select_lab_template :selected').val()==''){
                    alert('Lab Environment and Lab Contant can not be both None, please select at least one of them!');
                }
                else{
                    $.post("/cloud/assignTemplate", {
                            "lab_name": $('#new_lab_name').val().trim(),
                            "temp_id": $('#select_lab_template :selected').val(),
                            "content_id": $('#select_lab_content :selected').val(),
                            "name_id": $('#select_lab_content :selected').attr('value1'),
                            "desc": $('#new_lab_desc').val().trim()

                        },
                        function (data) {
                            //if (data.status == 'Success') {
                            lab_design_management();
                            //} else {
                            //    alert(data.message);
                            //}
                        },
                        'json'
                    );
                    lab_design_management();
                    $(this).dialog('close');
                }

            },
            "Cancel": function () {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
    $('<option />').attr('value', '').attr('value1','').text('None').appendTo('#select_lab_template');
    $.getJSON("/cloud/getTemplate", function (jsondata) {
        $.each(jsondata, function (index, item) {
            $('<option />').attr('value', item.temp_id).text(item.temp_name).appendTo('#select_lab_template');
        });
    });
    $('<option />').attr('value', '').attr('value1','').text('None').appendTo('#select_lab_content');
    $.getJSON("/cloud/getOwnClass", function (jsondata) {

        $.each(jsondata, function (index, item) {
            $('<option />').attr('value', item.courseid).attr('value1',item.nameid).text(item.coursename).appendTo('#select_lab_content');
        });
    });
}