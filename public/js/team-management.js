/**
 * Created by root on 3/30/15.
 */
function modal_assign_labs(element, url) {
    var groupId = $('#group_selector').val();
    $('.groupname-in-title').html($('#group_selector option:selected').text());

    var teams = $('[name="ids[]"]:checked');
    if (teams.length <= 0) {
        Swal.fire('Please select teams!', '', 'warning');
        return;
    }
    var ul_teams = $('#selected_teams').empty();
    teams.each(function() {
        ul_teams.append('<li>' + $(this).attr('data-name') +
            '<input type="hidden" name="teams[]" value="' + $(this).val() + '"></li>');
    });

    $('#assign_labs_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        // scrollX: true,
        // scrollCollapse: true,
        // scroller: true,
        ajax: url + '/' + groupId,
        columnDefs: [{
            targets: 0,
            searchable:false,
            orderable:false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
                return '<input type="checkbox" name="labs[]" value="'
                    + $('<div/>').text(data).html() + '">';
            }
        }, {
            targets: 6,
            searchable:false,
            orderable:false,
            render: function (data, type, full, meta) {
                return '<div class="btn-group">' +
                    // '<button class="btn btn-xs btn-primary" onclick="assign_lab_preview($(this))">View</button>' +
                    '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    'Action <span class="caret"></span>' +
                    '</button>' +
                    '<ul class="dropdown-menu" style="min-width: 10px;">' +
                    '<li><a href="#" onclick="assign_lab_preview($(this))">View</a></li>' +
                    '</ul>' +
                    '</div>';
            }
        }],
        columns: [
            { data: "checkbox", name: "checkbox" },
            { data: "name", name: "labenv.name" },
            { data: "description", name: "labenv.description" },
            { data: "public", name: "labenv.publicflag"},
            { data: "created_by", name: "users.name" },
            { data: "updated_at", name: "labenv.updated_at" }
        ]
    });

    $('#modal-assign-labs').modal('show');
}

function assign_lab_preview(element) {
    var labdata = $('#assign_labs_table').DataTable().row($(element).closest('tr')).data();
    $('.preview-lab-name').html(labdata.name);


    $('#modal-assign-lab-preview').modal('show');
}

function modal_team_edit(element) {
    var groupId = $('#group_selector').val();
    $('.groupname-in-title').html($('#group_selector option:selected').text());

    var row = $('#dataTableBuilder').DataTable().row($(element).closest('tr')).data();

    $('#team-edit-id').text(row.id);
    $('#team-edit-name').val(row.name);
    $('#team-edit-description').val(row.description);
    $('#modal-team-edit').modal('show');
}


function team_edit_update() {
    var team_name = $('#team-edit-name').val().trim();

    $.post('/subgroups/update', {
        subgroup_id: $('#team-edit-id').text().trim(),
        subgroup_name: team_name,
        description: $('#team-edit-description').val().trim(),
    }, function (data) {
        if (data.status === 'failed') {
            Swal.fire('Team "' + team_name + '" update failed!', '', 'error');
        } else {
            Swal.fire('Team "' + team_name + '" updated.', '', 'success');
        }
        $('#modal-team-edit').modal('hide');
        $('#dataTableBuilder').DataTable().draw(false);
    });
}

function modal_team_members(element) {
    var groupId = $('#group_selector').val();
    $('.groupname-in-title').html($('#group_selector option:selected').text());

    var row = $('#dataTableBuilder').DataTable().row($(element).closest('tr')).data();

    $('#team-members-id').text(row.id);
    $('#team-members-name').text(row.name);
    // $('#team-members-description').text(row.description);
    var arr_members = row.members.split('<br>');
    var arr_members_ids = row.members_ids.split(',');
    $('#member_counts').html(arr_members.length);
    var excludes = "";

    var final_members = $('#final-members').empty();
    for (var i = 0; i < arr_members.length; i++ ) {
        final_members.append('<option value="' + arr_members_ids[i] + '">' + arr_members[i] + '</option>');
        excludes += arr_members[i] + "|";
    }

    $.getJSON('/groups/members-json/' + groupId, function (dataSet) {
        var table = $('#team_edit_group_members').DataTable({
            pageLength: 5,
            lengthMenu: [ [5, 10, 15, 20, 25, 50, -1], [5, 10, 15, 20, 25, 50, "All"] ],
            destroy: true,
            processing: true,
            serverSide: false,
            // scrollX: true,
            // scrollCollapse: true,
            // scroller: true,
            //ajax: url + '/' + groupId,
            data: dataSet,
            columnDefs: [{
                targets: 0,
                searchable: false,
                orderable: false,
                className: 'dt-body-center',
                render: function (data, type, full, meta) {
                    return '<input type="checkbox" name="members[]" value="'
                        + full.id + '" data-email="' + full.email + '">';
                }
            }],
            columns: [
                {data: "checkbox", name: "checkbox"},
                {data: "name", name: "name"},
                {data: "email", name: "email"},
                {data: "institute", name: "institute"},
                {data: "org_id", name: "org_id"},
                {data: "role", name: "role"}
            ]
        });
        table.column(2).search('^(?!.*(' + excludes.slice(0,-1) + ')).*$', true, false).draw();
        $('#modal-team-members').modal('show');
    });
}

function team_member_select(type) {
    var table = $('#team_edit_group_members').DataTable();
    var final_members = $('#final-members');
    var final_members_opt;
    var excludes = "";
    if (type === 1) { // add members <--
        var new_members = $('[name="members[]"]:checked');

        for (var i=0; i<new_members.length; i++) {
            var email = $(new_members[i]).attr('data-email');
            final_members.append('<option value="' + new_members[i].value + '">' + email + '</option>');
            excludes += email + "|";
        }
        final_members_opt = final_members.find('option');
        $.each(final_members_opt, function (index, item) {
            excludes += item.innerText + "|";
        });
        $('#member_counts').html(final_members_opt.length);
        table.column(2).search('^(?!.*(' + excludes.slice(0,-1) + ')).*$', true, false).draw();

    } else {    // remove members -->
        final_members.find('option:selected').remove();
        final_members_opt = final_members.find('option');
        excludes = "";
        $.each(final_members_opt, function (index, item) {
            excludes += item.innerText + "|";
        });
        $('#member_counts').html(final_members_opt.length);
        table.column(2).search('^(?!.*(' + excludes.slice(0,-1) + ')).*$', true, false).draw();
    }
}

function team_members_update() {
    var team_name = $('#team-members-name').text().trim();
    var members = $('#final-members').find('option');
    var final_m = [];
    for (var i=0; i<members.length; i++) {
        final_m.push({id: members[i].value, email: members[i].text});
    }

    $.post('/subgroups/updateMembers', {
        subgroup_id: $('#team-members-id').text().trim(),
        subgroup_name: team_name,
        // description: $('#team-edit-description').val().trim(),
        members: final_m
    }, function (data) {
        if (data.status === 'failed') {
            Swal.fire('Team "' + team_name + '" update failed!', '', 'error');
        } else {
            Swal.fire('Team "' + team_name + '" updated.', '', 'success');
        }
        $('#modal-team-members').modal('hide');
        $('#dataTableBuilder').DataTable().draw(false);
    });
}

function modal_create_team(element, url) {
    var groupId = $('#group_selector').val();
    $('.groupname-in-title').html($('#group_selector option:selected').text());

    $('#team_create_group_members').DataTable({
        pageLength: 5,
        lengthMenu: [ [5, 10, 15, 20, 25, 50, -1], [5, 10, 15, 20, 25, 50, "All"] ],
        destroy: true,
        processing: true,
        serverSide: true,
        // scrollX: true,
        // scrollCollapse: true,
        // scroller: true,
        ajax: url + '/' + groupId,
        columnDefs: [{
            targets: 0,
            searchable:false,
            orderable:false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
                return '<input type="checkbox" name="members[]" value="'
                        + $('<div/>').text(data).html() + '">';
            }
        }],
        columns: [
            { data: "checkbox", name: "checkbox" },
            { data: "name", name: "users.name" },
            { data: "email", name: "users.email" },
            { data: "institute", name: "users.institute" },
            // { data: "org_id", name: "users.org_id" },
            { data: "role", name: "roles.name" }
        ]
    });

    $('#modal-team-create').modal('show');
}

function delete_teams(element) {
    var table = $('#dataTableBuilder').DataTable();
    var teams = [];
    var invalid = "";
    var deleted_teams = [];

    if (element.is('button')) {
        teams = $('[name="ids[]"]:checked');
        if (teams.length <= 0) {
            Swal.fire('Please select teams!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        teams.push(element);
    }

    for (var i = 0; i < teams.length; i++) {
        var rowData = table.row(teams[i].closest('tr')).data();
        if (rowData.labs !== "") {
            invalid += rowData.name + '<br />';
        } else {
            deleted_teams.push(rowData.id);
        }
    }

    if (invalid !== "") {
        Swal.fire({
            title: 'Delete Teams Error!', type: 'error',
            html: '<em>Reason</em><br /><b>Teams have deployed labs (delete labs first):</b><br />' + invalid + '<br />'
        });
    } else {
        Swal.fire({
            title: 'Delete the selected teams?',
            text: '.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                deleted_teams.forEach(function(team, index) {
                    var row = table.rows(function (idx, data, node) {
                        return (data.id === team)
                    })[0];
                    setTimeout(function() {
                        $.post('delete', {
                            'team': team
                        }, function (data) {
                            if (data.status === 'Success') {
                                table.row(row).remove().draw(false);
                            }
                        });
                    }, 500 * index);
                });
            }
        });
    }
}

function checkbox_check_all(tableId, element) {
    var table = $('#' + tableId).DataTable();
    var rows = table.rows({ 'search': 'applied' }).nodes();
    //var rows = table.fnGetNodes();
    $('input[type="checkbox"]', rows).prop('checked', element[0].checked);
}

function modal_assign_lab_contents(element, url) {
    var groupId = $('#group_selector').val();
    $('.groupname-in-title').html($('#group_selector option:selected').text());

    var labs = $('[name="labs[]"]:checked');
    if (labs.length <= 0) {
        Swal.fire('Please select labs!', '', 'warning');
        return;
    }
    var ul_labs = $('#selected_teams_labs').empty();
    labs.each(function() {
        ul_labs.append('<li>' + $(this).val() +
            '<input type="hidden" name="labs[]" value="' + $(this).val() + '"></li>');
    });

    $('#assign_contents_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: url + '/' + groupId,
        columnDefs: [{
            targets: 0,
            searchable:false,
            orderable:false,
            className: 'dt-body-center',
            render: function (data, type, full, meta){
                return '<input type="checkbox" name="contents[]" value="'
                    + $('<div/>').text(data).html() + '">';
            }
        }, {
            targets: 6,
            searchable:false,
            orderable:false,
            render: function (data, type, full, meta) {
                return '<div class="btn-group">' +
                    // '<button class="btn btn-xs btn-primary" onclick="assign_lab_preview($(this))">View</button>' +
                    '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    'Action <span class="caret"></span>' +
                    '</button>' +
                    '<ul class="dropdown-menu" style="min-width: 10px;">' +
                    '<li><a href="#" onclick="assign_labcontent_preview($(this))">View</a></li>' +
                    '</ul>' +
                    '</div>';
            }
        }],
        columns: [
            { data: "checkbox", name: "checkbox" },
            { data: "category", name: "labcontent.lab_cat_id" },
            { data: "name", name: "labcontent.name" },
            { data: "public", name: "labcontent.publicflag" },
            { data: "created_by", name: "users.name" },
            { data: "updated_at", name: "labcontent.updated_at" }
        ]
    });

    $('#modal-assign-contents').modal('show');
}

function assign_labcontent_preview(element) {
    var labdata = $('#assign_contents_table').DataTable().row($(element).closest('tr')).data();
    $('.preview-labcontent-name').html(labdata.name);
    var labid = labdata.id;
    var labcontent=
        '    <p><p><b>Lab Catgory ID:</b></p>\n' +
        '\n' +
        '    <p id="preview-labcatid"></p>\n' +
        '\n' +
        '    <p><b>Keywords</b>(tags): </p>\n' +
        '\n' +
        '    <p id="preview-tags"></p>\n' +
        '\n' +
        '    <p><b>Objects</b>: </p>\n' +
        '\n' +
        '    <p id="preview-objects"></p>\n' +
        '\n' +
        '    <p><b>Estimated Time</b>: </p>\n' +
        '\n' +
        '    <p>Expert: <span id="preview-experttime"></span></p>\n' +
        '\n' +
        '    <p>Novice: <span id="preview-time"></span></p>\n' +
        '\n' +
        '    <p><b>Difficulty</b>:(Level 1 to 5, lower is easier) </p>\n' +
        '    <p>Time: Level <span id="preview-difftime"></span></p>\n' +
        '    <p>Design: Level <span id="preview-diffdesign"></span></p>\n' +
        '    <p>Implementation: Level <span id="preview-diffimp"></span></p>\n' +
        '    <p>Configration: Level <span id="preview-diffconf"></span></p>\n' +
        '    <p>Knowledge: Level <span id="preview-diffknow"></span></p>\n' +
        '    <p><b>Required OS</b>: </p>\n' +
        '    <p id="preview-os"></p>\n' +
        '    <p><b>Preparations</b>: </p>\n' +
        '    <p id="preview-preparations"></p><br/><div id="tasks-box"></div>';

    var modal = $('#modal-assign-labcontent-preview');
    modal.find('#preview-lab-content').empty().html(labcontent);
    $.getJSON("/getlabcontentbyid", {

        "labid": labid,


    }, function (data) {
        modal.find('#preview-labcatid').html(data[0].lab_cat_id);
        modal.find('#preview-tags').html(data[0].tags);
        modal.find('#preview-objects').html(data[0].objects);
        modal.find('#preview-experttime').html( data[0].experttime);
        modal.find('#preview-time').html(data[0].time);
        var difficulty = JSON.parse(data[0].difficulty);
        modal.find('#preview-difftime').html(difficulty[0]);
        modal.find('#preview-diffdesign').html(difficulty[1]);
        modal.find('#preview-diffimp').html(difficulty[2]);
        modal.find('#preview-diffconf').html(difficulty[3]);
        modal.find('#preview-diffknow').html(difficulty[4]);
        // $("input[name=difftime][value="+difficulty[0]+"]").attr('checked', true);
        // $("input[name=diffdesign][value="+difficulty[1]+"]").attr('checked', true);
        // $("input[name=diffimp][value="+difficulty[2]+"]").attr('checked', true);
        // $("input[name=diffconf][value="+difficulty[3]+"]").attr('checked', true);
        // $("input[name=diffknow][value="+difficulty[4]+"]").attr('checked', true);
        modal.find('#preview-os').html(data[0].os);
        modal.find('#preview-preparations').html(data[0].preparations);
    });
    $.getJSON("/getlabtaskbylab", {

        "labid": labid,


    }, function (data) {
        for (var i = 0; i < data.length; i++) {
            var tasktitle = $('<h3 id="task_'+data[i].id+'">Task '+data[i].name+':</h3>').appendTo($('#tasks-box'));
            var taskcontent = $('<p><b>Task Requirements:</b></p>'+data[i].content).appendTo($('#tasks-box'));
            var submission=JSON.parse(data[i].submission);
            if(submission[0]=='true'||submission[1]=='true'||submission[2]=='true'){
                var tasksubmission= $('<h5 id="submission_'+data[i].id+'">Submissions:</h5><form id="submission_form_'+data[i].id+'"></form>').appendTo($('#tasks-box'));
            }
            if(submission[0]=='true'){
                var tasksubmission= $('<p>Take screenshot(s):</p>  <div id="submission-task'+data[i].id+'-1" style="border: 1px solid lightblue; min-height: 45px; vertical-align: middle;">Submission Area</div>').appendTo($('#submission_form_'+data[i].id));
            }
            if(submission[1]=='true'){
                var tasksubmission= $('<p>Upload file(s):</p>  <div id="submission-task'+data[i].id+'-1" style="border: 1px solid lightblue; min-height: 45px; vertical-align: middle;">Submission Area</div>').appendTo($('#submission_form_'+data[i].id));
            }
            if(submission[2]=='true'){
                var tasksubmission= $('<p>Input answer or description:</p>  <div id="submission-task'+data[i].id+'-1" style="border: 1px solid lightblue; min-height: 45px; vertical-align: middle;">Submission Area</div>').appendTo($('#submission_form_'+data[i].id));
            }

        }
    });
    $('#modal-assign-labcontent-preview').modal('show');
}

function modal_update_labs(element) {
    var div_content = $('#div_update-contents').empty();

    if (element === null) {
        var groupId = $('#group_selector').val();
        $('.groupname-in-title').html($('#group_selector option:selected').text());
        $('.update-labs-project').html('');
        var labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select labs!', '', 'warning');
            return;
        }
        $('#update_description').val('');

        var contents = [];
        $(labs).each(function(index, lab) {
            $('#contents_container_' + $(lab).attr('data-id')).children('div[id^=content_]').each(function () {
                var tag = $(this).attr('id').split('_')[1];
                var content_id = tag.split('-')[1];
                if (!contents.some(function(e) {return e.id === content_id})) {
                    contents.push({id: content_id, name: $(this).find('span[id^=name_]').html()});
                }
            });
        });

        contents.forEach(function(content) {
            div_content.append('<div id="updatecontent_' + content.id + '"><div class="form-group">' +
                '<label><b>Lab Content:</b></label>' +
                '<input type="text" class="form-control" readonly id="labcontent_' + content.id + '"  />' +
                '<label><b>Labs Start At:</b></label>' +
                '<input type="text" class="form-control" id="labstart_' + content.id + '" />' +
                '</div>' +
                '<div class="form-group">' +
                '<label><b>Labs Due At:</b></label>' +
                '<input type="text" class="form-control" id="labdue_' + content.id + '" />' +
                '</div></div>');

            $('#labcontent_' + content.id).empty().val(content.name);

            $('#labstart_' + content.id).empty().datetimepicker({
                //inline: true,
                minDate: new Date()
            });
            $('#labstart_' + content.id).val(new Date());

            $('#labdue_' + content.id).empty().datetimepicker({
                //inline: true,
                minDate: new Date()
            });
            $('#labdue_' + content.id).val(new Date());
        });
    } else {
        var row = $('#dataTableBuilder').DataTable().row($(element).closest('tr')).data();
        $('.groupname-in-title').html(row.project_name);
        $('.update-labs-project').empty().html(row.project_name);

        $('#update_description').val(row.notes);

        $('#contents_container_' + row.id).children('div[id^=content_]').each(function () {
            var tag = $(this).attr('id').split('_')[1];
            var content_id = tag.split('-')[1];
            var content = $(this).find('span[id^=name_]').html();
            var start = $(this).find('span[id^=start_at_]').html();
            var due = $(this).find('span[id^=due_at_]').html();

            div_content.append('<div id="updatecontent_' + content_id + '"><div class="form-group">' +
                '<label><b>Lab Content:</b></label>' +
                '<input type="text" class="form-control" readonly id="labcontent_' + content_id + '"  />' +
                '<label><b>Labs Start At:</b></label>' +
                '<input type="text" class="form-control" id="labstart_' + content_id + '" />' +
                '</div>' +
                '<div class="form-group">' +
                '<label><b>Labs Due At:</b></label>' +
                '<input type="text" class="form-control" id="labdue_' + content_id + '" />' +
                '</div></div>');

            $('#labcontent_' + content_id).empty().val(content);

            $('#labstart_' + content_id).empty().datetimepicker({
                //inline: true,
                minDate: new Date()
            });
            $('#labstart_' + content_id).val(start);
            $('#labdue_' + content_id).empty().datetimepicker({
                //inline: true,
                minDate: new Date()
            });
            $('#labdue_' + content_id).val(due);
        });
    }
    $('#modal-update-labs').modal('show');
}

function update_labs() {
    var notes = $('#update_description').val().trim();
    var projects = [];
    var proj = $('.update-labs-project').text();
    if (proj === "") {
        var labs = $('[name="labs[]"]:checked');
        for (var i = 0; i < labs.length; i++) {
            projects.push(labs[i].value);
        }
    } else {
        projects.push(proj);
    }

    var contents = [];
    $('#div_update-contents').children('div[id^=updatecontent_]').each(function () {
        var content_id = $(this).attr('id').split('_')[1];
        contents.push({content_id: content_id, start_at: $('#labstart_' + content_id).datetimepicker('getValue'),
            due_at: $('#labdue_' + content_id).datetimepicker('getValue')});
    });

    $.post('/labsdeploy/update', {
            'projects': projects,
            'description': notes,
            'contents': contents
        }, function (data) {
            if (data.status === 'failed') {
                Swal.fire('Labs ' + data.projects.toString() + ' update failed!', '', 'error');
            } else {
                Swal.fire('Labs updated.', '', 'success');
            }
            $('#modal-update-labs').modal('hide');
            $('#dataTableBuilder').DataTable().draw();
        }
    );
}

// function modal_update_labs(element) {
//     if (element === null) {
//         var groupId = $('#group_selector').val();
//         $('.groupname-in-title').html($('#group_selector option:selected').text());
//         $('.update-labs-project').html('');
//         var labs = $('[name="labs[]"]:checked');
//         if (labs.length <= 0) {
//             Swal.fire('Please select labs!', '', 'warning');
//             return;
//         }
//
//         $('#lab_start_time').empty().datetimepicker({
//             //inline: true,
//             minDate: new Date()
//         });
//         $('#lab_start_time').val(new Date());
//
//         $('#lab_due_time').empty().datetimepicker({
//             //inline: true,
//             minDate: new Date()
//         });
//         $('#lab_due_time').val(new Date());
//         $('#description').empty();
//     } else {
//         var row = $('#dataTableBuilder').DataTable().row($(element).closest('tr')).data();
//         $('.groupname-in-title').html(row.project_name);
//         $('.update-labs-project').empty().html(row.project_name);
//
//         $('#lab_start_time').empty().datetimepicker({
//             //inline: true,
//             minDate: new Date()
//         });
//         $('#lab_start_time').val(row.start_at);
//         $('#lab_due_time').empty().datetimepicker({
//             //inline: true,
//             minDate: new Date()
//         });
//         $('#lab_due_time').val(row.due_at);
//         $('#description').empty().html(row.notes);
//     }
//
//     $('#modal-update-labs').modal('show');
// }
//
// function update_labs() {
//     var start_at = $('#lab_start_time').datetimepicker('getValue');
//     var due_at = $('#lab_due_time').datetimepicker('getValue');
//     var notes = $('#description').val().trim();
//     var projects = [];
//     var proj = $('.update-labs-project').text();
//     if (proj === "") {
//         var labs = $('[name="labs[]"]:checked');
//         for (var i = 0; i < labs.length; i++) {
//             projects.push(labs[i].value);
//         }
//     } else {
//         projects.push(proj);
//     }
//
//     $.post('/labsdeploy/update', {
//             'start_at' : start_at,
//             'due_at' : due_at,
//             'description': notes,
//             'projects': projects
//         }, function (data) {
//             if (data.status === 'failed') {
//                 Swal.fire('Labs ' + data.projects.toString() + ' update failed!', '', 'error');
//             } else {
//                 Swal.fire('Labs updated.', '', 'success');
//             }
//             $('#modal-update-labs').modal('hide');
//             $('#dataTableBuilder').DataTable().draw();
//         }
//     );
// }

function assign_lab_content(element) {
    if (element === null) {
        var groupId = $('#group_selector').val();
        var groupName = $('#group_selector option:selected').text();
        var labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select labs!', '', 'warning');
            return;
        }
        var projects = [];
        for (var i = 0; i < labs.length; i++) {
            projects.push(labs[i].value);
        }

        Swal.fire({
            title: 'Update Lab Content',
            type: 'info',
            html: '<div><div class="form-group row"><strong>From: </strong>' +
                            '<label class="radio-inline" for="isPublic1">' +
                                '<input type="radio" name="isPublic" id="isPublic1" value="0" checked>Private' +
                            '</label>' +
                            '<label class="radio-inline" for="isPublic2">' +
                                '<input type="radio" name="isPublic" id="isPublic2" value="1">Public' +
                            '</label>' +
                        '</div>' +
                        '<select class="form-control" id="select_labcontents"><option value="-1">...Select lab content...</option></select>' +
                  '</div>',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: 'Assign',
            //confirmButtonAriaLabel: 'Thumbs up, great!',
            cancelButtonText: 'Cancel',
            //cancelButtonAriaLabel: 'Thumbs down',
            onBeforeOpen: (dom) => {
                var type='0';
                $('input[type=radio][name=isPublic]').change(function() {
                    type = this.value;
                });
                $.getJSON('/labs/labcontents/' + type, function (labs) {
                    $.each(labs, function (index, lab) {
                        $('<option value="' + lab.id + '">' + lab.name + '</option>').appendTo(dom.querySelector('#select_labcontents'));
                    });
                });
            }
        }).then((result) => {
            if (result.value) {
                var lab_content_id = $('#select_labcontents').val();
                if (lab_content_id < 0) {
                    Swal.fire('Please Select a lab content!', '', 'warning');
                } else {
                    $.post('assign-labcontent', {
                        'project_name': projects,
                        'lab_content_id': lab_content_id
                    }, function (result) {
                        if (result.status === 'Success') {
                            Swal.fire('Lab Content Assigned.', '', 'success');
                            $('#dataTableBuilder').DataTable().draw();
                        } else {
                            Swal.fire('Assign Lab Content Failed!', result.message, 'error');
                        }
                    });
                }
            }
        });
    }
}

function modal_view_deployed_labs(element) {
    var data = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();
    var projectId = data.project_id;

    if (data.status !== "CREATE_COMPLETE") {
        Swal.fire('The lab is not deploy yet!', '', 'warning');
    } else {
        $('.view-lab-team-name').text(data.team_name);
        $('.view-lab-name').text(data.lab_name);
        var view_header =
            '<ul class="nav nav-tabs">' +
                '<li class="active"><a href="#lab-env-topology-tab" data-toggle="tab" onclick="vis_canvas_redraw(\'' + projectId + '\')">Topology</a></li>' +
            '</ul>' +
            '<div class="tab-content">' +
                '<div class="active tab-pane" id="lab-env-topology-tab" >' +
                        '<table width="100%"><thead><tr><th>' +
                            '<button id="btn-vis-refresh" title="Refresh Topology" disabled style="float: left" onclick="vis_canvas_load_network_topology(\'' + projectId + '\')"><i class="fa fa-refresh"></i></button>'  +
                            '<p id="vis-refresh-loading" style="float:left; display: none;"> &nbsp; &nbsp; Loading...</p>' +
                            '<button id="btn-vis-openallconsole" title="Open All Consoles" style="float: left; display: none; background-color: lightblue;" onclick="vis_canvas_open_all_consoles(\'' + projectId + '\')"><i class="fa fa-window-restore"></i></button>' +
                        '<p id="vis-topology-selection" style="float: right">Selection: None</p></th></tr></thead>' +
                        '<tbody id="lab-env-topology"></tbody>' +
                        '</table>' +
            '</div></div>';

        $('#lab-environment').empty().append(view_header);
        vis_canvas_load_network_topology(projectId);
        $('#modal-view-lab').modal('show');
    }
}

function update_labs_status() {
    var table = $('#dataTableBuilder').DataTable();
    if (!table.data().count()) return;
    var STATUS_CHECK = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS', 'CREATE_PROJECT', 'Deploying', 'Releasing', 'Deleting'];

    $('#dataTableBuilder_processing').hide();

    var update_interval = setInterval(function () {
        // table.ajax.reload(null, false);
        $('#dataTableBuilder_processing').hide();

        var projects = table.rows(function (idx, data, node) {
            return (STATUS_CHECK.indexOf(data.status) >= 0);
        }).data().pluck('project_name').toArray();

        if (projects.length > 0) {
            projects.forEach(function(project, index) {
                setTimeout(function() {
                    var row = table.rows(function (idx, data, node) {
                        return (data.project_name === project)
                    })[0];
                    $.post('labstatus', {
                            'project': project
                        }, function (jdata) {
                            table.cell(row, 'status:name').invalidate(jdata).draw(false);
                        }
                    );
                }, 500 * index);
            });
        } else {
            clearInterval(update_interval);
        }
    }, 2000);
}

function update_a_lab_status(row) {
    var table = $('#dataTableBuilder').DataTable();
    var STATUS_CHECK = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS', 'CREATE_PROJECT', 'Deploying', 'Releasing', 'Deleting'];
    $('#dataTableBuilder_processing').hide();

    var update_interval = setInterval(function () {
        //table.cell(row, 'status:name').invalidate().draw();
        if (STATUS_CHECK.indexOf(table.row(row).data().status) >= 0) {
            $.post('labstatus', {
                    'project': table.row(row).data().project_name
                }, function (jdata) {
                    table.cell(row, 'status:name').invalidate(jdata).draw(false);
                }
            );
        } else {
            clearInterval(update_interval);
        }
    }, 2000);
}

function deploy_labs(element) {
    var projects = [];
    var PROJECT_INVALID = ['PROJECT_FAILED'];
    var PROJECT_DEPLOYED = ['CREATE_COMPLETE'];
    var PROJECT_IN_PROGRESS = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS'];
    var invalid_projects = "";
    var inprogress_projects = "";
    var deployed_projects = "";
    var labs = [];
    var table = $('#dataTableBuilder').DataTable();
    var proj_desc = 'project for group: ' + $('#group_selector option:selected').text().trim();

    if (element.is('button')) {
        labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select labs!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        labs.push(element);
    }

    for (var i = 0; i < labs.length; i++) {
        var rowData = table.row(labs[i].closest('tr')).data();
        if (PROJECT_INVALID.indexOf(rowData.status) >= 0) {
            invalid_projects += rowData.project_name + '<br />';
        } else if (PROJECT_IN_PROGRESS.indexOf(rowData.status) >=0) {
            inprogress_projects += rowData.project_name + '<br />';
        } else if (PROJECT_DEPLOYED.indexOf(rowData.status) >=0) {
            deployed_projects += rowData.project_name + '<br />';
        } else {
            projects.push({ name: rowData.project_name, desc: proj_desc + ', team: ' + rowData.team_name +
            ', lab: ' + rowData.lab_name, lab: rowData.lab_id });
        }
    }

    if (invalid_projects.length > 0 || inprogress_projects.length > 0 || deployed_projects.length > 0) {
        Swal.fire({
            title: 'Deploy Labs Error!', type: 'error',
            html: '<em>Reason</em><br />' +
            ((invalid_projects.length > 0) ? '<b>Invalid labs (delete labs):</b><br />' + invalid_projects + '<br />': '') +
            ((deployed_projects.length > 0) ? '<b>Deployed labs (release labs first):</b><br />' + deployed_projects + '<br />': '') +
            ((inprogress_projects.length > 0) ? '<b>Labs in progress (wait for completion):</b><br />' + inprogress_projects : '' ) + '</div>'
        });
    } else {
        Swal.fire({
            title: 'Deploy the selected labs?',
            text: 'The deployment process will take a few minutes.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Request Submitted!', 'Please check the status for the deployment progress.', 'success');
                projects.forEach(function(project, index) {
                    setTimeout(function() {
                        var row = table.rows(function(idx, data, node) {
                            return (data.project_name === project['name']) })[0];
                        $.post('deploy', {
                            'project': project
                        }, function (data) {
                            table.cell(row, 'status:name').invalidate().draw(false);
                            update_a_lab_status(row);
                            //table.cell(row, 'status:name').data('Deploying').draw();
                        });
                    }, 500 * index);
                });
            }
        });
    }
}

function release_labs(element) {
    var projects = [];
    var PROJECT_INVALID = ['PROJECT_FAILED'];
    var PROJECT_NO_RSS = ['NONE', 'DELETE_COMPLETE', 'PROJECT_COMPLETE'];
    var PROJECT_IN_PROGRESS = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS'];
    var invalid_projects = "";
    var norss_projects = "";
    var inprogress_projects = "";
    var labs = [];
    var table = $('#dataTableBuilder').DataTable();

    if (element.is('button')) {
        labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select labs!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        labs.push(element);
    }

    for (var i = 0; i < labs.length; i++) {
        var rowData = table.row(labs[i].closest('tr')).data();
        if (PROJECT_INVALID.indexOf(rowData.status) >= 0) {
            invalid_projects += rowData.project_name + '<br />';
        } else if (PROJECT_IN_PROGRESS.indexOf(rowData.status) >= 0) {
            inprogress_projects += rowData.project_name + '<br />';
        } else if (PROJECT_NO_RSS.indexOf(rowData.status) >= 0) {
            norss_projects += rowData.project_name + '<br />';
        } else {
            projects.push({name: rowData.project_name, lab: rowData.lab_id});
        }
    }

    if (invalid_projects.length > 0 || inprogress_projects.length > 0 || norss_projects.length > 0) {
        Swal.fire({
            title: 'Release Resources Error!', type: 'error',
            html: '<em>Reason</em><br />' +
            ((invalid_projects.length > 0) ? '<b>Invalid labs (delete labs):</b><br />' + invalid_projects + '<br />': '') +
            ((norss_projects.length > 0) ? '<b>labs have no resource (deploy labs first):</b><br />' + norss_projects + '<br />': '') +
            ((inprogress_projects.length > 0) ? '<b>Labs in progress (wait for completion):</b><br />' + inprogress_projects : '' )
        });
    } else {
        Swal.fire({
            title: 'Release resources from the selected labs?',
            text: 'It will take a few minutes to remove the resource from the selected labs.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Request Submitted!', 'Please check the status for their progress.', 'success');
                projects.forEach(function(project, index) {
                    setTimeout(function() {
                        var row = table.rows(function (idx, data, node) {
                            return (data.project_name === project['name'])
                        })[0];
                        $.post('release', {
                            'project': project
                        }, function (data) {
                            table.cell(row, 'status:name').invalidate().draw(false);
                            //table.cell(row, 'status:name').data('Releasing').draw();
                            update_a_lab_status(row);
                        });
                    }, 500 * index);
                });
            }
        });
    }
}

function delete_labs(element) {
    var table = $('#dataTableBuilder').DataTable();
    var PROJECT_DEPLOYED = ['CREATE_COMPLETE'];
    var PROJECT_IN_PROGRESS = ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS'];
    var deployed_projects = "";
    var inprogress_projects = "";
    var labs = [];
    var projects = [];

    if (element.is('button')) {
        labs = $('[name="labs[]"]:checked');
        if (labs.length <= 0) {
            Swal.fire('Please select labs!', '', 'warning');
            return;
        }
    } else if (element.is('a')) {
        labs.push(element);
    }

    for (var i = 0; i < labs.length; i++) {
        var rowData = table.row(labs[i].closest('tr')).data();
        if (PROJECT_DEPLOYED.indexOf(rowData.status) >= 0) {
            deployed_projects += rowData.project_name + '<br />';
        } else if (PROJECT_IN_PROGRESS.indexOf(rowData.status) >= 0) {
            inprogress_projects += rowData.project_name + '<br />';
        } else {
            projects.push({id: rowData.project_id, name: rowData.project_name, lab: rowData.lab_id});
        }
    }
    if (deployed_projects.length > 0 || inprogress_projects.length > 0) {
        Swal.fire({
            title: 'Delete Labs Error!', type: 'error',
            html: '<em>Reason</em><br />' +
            ((deployed_projects.length > 0) ? '<b>Deployed labs (release resource first):</b><br />' + deployed_projects + '<br />': '') +
            ((inprogress_projects.length > 0) ? '<b>Labs in progress (wait for completion):</b><br />' + inprogress_projects : '' )
        });
    } else {
        Swal.fire({
            title: 'Delete the selected labs?',
            text: 'The created projects to the labs will be deleted.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Request Submitted!', 'The deleted labs will be removed from the list.', 'success');
                projects.forEach(function (project, index) {
                    setTimeout(function () {
                        var row = table.rows(function (idx, data, node) {
                            return (data.project_name === project['name'])
                        })[0];
                        $.post('delete', {
                            'project': project
                        }, function (data) {
                            table.cell(row, 'status:name').invalidate().draw(false);
                            update_a_lab_status(row);
                            // if (data === 'PROJECT_DELETED') {
                            //     table.row(row).remove().draw(false);
                            // } else {
                            //     table.cell(row, 'status:name').invalidate().draw(false);
                            // }
                        });
                    }, 500 * index);
                });
            }
        });
    }
}



/********** Old Functions **************/
/******************************
 *
 * @param winId
 * @param win_main
 */
function do_windows_labmanagement_display(winId, win_main) {
    //var groupname = winId.substring('#window_group_project_'.length);
    var groupname = 'all';
    var tabs = {
        tabId: ['team_list_' + groupname, 'lab_deploy_list_' + groupname],
        tabName: ['Team List', 'Lab Deployment']
    };

    create_tabs(winId, win_main, tabs, null);

    var tab_head = win_main.find('ul');
    var group_sel = $(document.createElement('select')).appendTo(tab_head);
    group_sel.attr('id', 'group_select_for_team').css('position', 'absolute').css('right', (160+20)+'px')
        .css('color', '#1C94C4').css('margin-top', '4px');
    group_sel.append($('<option />').val(-1).html('...Select a Group...'));

    $.getJSON("group/getGroupsByOwner", function (data) {
        $.each(data, function(index, item) {
            group_sel.append($('<option />').val(item.group.id).html(item.group.name));
        })
    });

    var team_buttons = $(document.createElement('div')).appendTo($('#team_list_' + groupname ));
    team_buttons.append('<button class="submit" onclick="create_subgroup($(this))">Create Teams</button>&nbsp;' +
        //'<button onclick="assign_template($(this))">Assign Templates</button>' +
    '<button class="submit" onclick="assign_lab($(this))">Assign Labs</button>');

    var team_table = $(document.createElement('table')).appendTo($('#team_list_' + groupname));
    team_table.addClass("data").addClass("tablesorter").attr("id", "tbl_team_list_" + groupname).append(
        //'<thead><tr><th>checkbox</th><th>Subgroup</th><th>Actions</th><th>Last Copied Template ID</th><th>Last Assigned Template ID</th><th>Project Name</th></tr></thead>'
        '<thead><tr><th><input type="checkbox" name="lab_assign_list_checkbox_all" onclick="list_check($(this))"></th>' +
        '<th class="hidden">TeamID</th><th>Team</th><th>Description</th><th>Members</th><th>Labs</th>' +
        '<th>Actions</th></tr></thead>'
    );
    var team_tbody = $(document.createElement('tbody')).appendTo(team_table);
    //getTeamListTable(groupname);

    var lab_buttons = $(document.createElement('div')).appendTo($('#lab_deploy_list_' + groupname));
    lab_buttons.append('<button class="submit"  id="btn_lab_deploy_batch" type="batch" onclick="lab_deploy($(this))">Deploy Assigned Labs</button>&nbsp;' +
        '<button class="submit" id="btn_lab_update_batch" type="batch" group="' + groupname + '" onclick="lab_rename($(this))">Update Lab Info</button>&nbsp;' +
        '<button class="submit" id="btn_lab_delete_batch" type="batch" onclick="lab_delete($(this))">Delete Labs</button>&nbsp;' +
        '<label id="resource_usage_' + groupname + '">Resource Usage:</label>&nbsp;'
        //'<button class="submit" id="btn_lab_refresh" onclick="lab_refresh($(this))">Refresh Table</button>'
    );
    var lab_table = $(document.createElement('table')).appendTo($('#lab_deploy_list_' + groupname));
    lab_table.addClass("data").addClass("tablesorter").attr("id", "tbl_lab_deploy_list_" + groupname).append(
        //'<thead><tr><th>checkbox</th><th>Subgroup</th><th>Actions</th><th>Last Copied Template ID</th><th>Last Assigned Template ID</th><th>Project Name</th></tr></thead>'
        '<thead><tr><th><input type="checkbox" name="lab_assign_list_checkbox_all" onclick="list_check($(this))"></th>' +
        '<th class="hidden">Team_id</th><th>Team</th>' +
        '<th class="hidden">Temp_id</th><th>Lab/Template</th><th class="hidden">ProjectName</th><th>Lab Name</th>' +
        '<th>Description</th><th>Assign At</th><th>Lab Deploy At</th><th class="hidden">sys_status</th><th>Status</th><th>Lab Due At</th><th>Actions</th><th class="hidden">lab_id</th></tr></thead>'
    );
    var lab_tbody = $(document.createElement('tbody')).appendTo(lab_table);
    //getLabDeployListTable(groupname);

    //$(winId).find('div.window_bottom')
    //    .text('Subgroup template setting');
}

function team_management_window(winId, win_main) {
    var groupname = winId.substring('#window_group_project_'.length);
    var tabs = {
        tabId: ['team_list_' + groupname, 'lab_deploy_list_' + groupname],
        tabName: ['Team List', 'Lab Deployment']
    };

    create_tabs(winId, win_main, tabs, null);

    var team_buttons = $(document.createElement('div')).appendTo($('#team_list_' + groupname ));
    team_buttons.append('<button class="submit" onclick="create_subgroup($(this))">Create Teams</button>&nbsp;' +
                        //'<button onclick="assign_template($(this))">Assign Templates</button>' +
                        '<button class="submit" onclick="assign_lab($(this))">Assign Labs</button>');

    var team_table = $(document.createElement('table')).appendTo($('#team_list_' + groupname));
    team_table.addClass("data").attr("id", "tbl_team_list_" + groupname).append(
        //'<thead><tr><th>checkbox</th><th>Subgroup</th><th>Actions</th><th>Last Copied Template ID</th><th>Last Assigned Template ID</th><th>Project Name</th></tr></thead>'
        '<thead><tr><th><input type="checkbox" name="lab_assign_list_checkbox_all" onclick="list_check($(this))"></th>' +
        '<th class="hidden">TeamID</th><th>Team</th><th>Description</th><th>Members</th><th>Labs</th>' +
        '<th>Actions</th></tr></thead>'
    );
    var team_tbody = $(document.createElement('tbody')).appendTo(team_table);
    getTeamListTable(groupname);

    var lab_buttons = $(document.createElement('div')).appendTo($('#lab_deploy_list_' + groupname));
    lab_buttons.append('<button class="submit"  id="btn_lab_deploy_batch" type="batch" onclick="lab_deploy($(this))">Deploy Assigned Labs</button>&nbsp;' +
                       '<button class="submit" id="btn_lab_update_batch" type="batch" group="' + groupname + '" onclick="lab_rename($(this))">Update Lab Info</button>&nbsp;' +
                       '<button class="submit" id="btn_lab_delete_batch" type="batch" onclick="lab_delete($(this))">Delete Labs</button>&nbsp;'
                       //'<button class="submit" id="btn_lab_refresh" onclick="lab_refresh($(this))">Refresh Table</button>'
                       );
    var lab_table = $(document.createElement('table')).appendTo($('#lab_deploy_list_' + groupname));
    lab_table.addClass("data").attr("id", "tbl_lab_deploy_list_" + groupname).append(
        //'<thead><tr><th>checkbox</th><th>Subgroup</th><th>Actions</th><th>Last Copied Template ID</th><th>Last Assigned Template ID</th><th>Project Name</th></tr></thead>'
        '<thead><tr><th><input type="checkbox" name="lab_assign_list_checkbox_all" onclick="list_check($(this))"></th>' +
        '<th class="hidden">Team_id</th><th>Team</th>' +
        '<th class="hidden">Temp_id</th><th>Lab/Template</th><th class="hidden">ProjectName</th><th>Lab Name</th>' +
        '<th>Description</th><th>Assign At</th><th>Lab Deploy At</th><th class="hidden">sys_status</th><th>Status</th><th>Lab Due At</th><th>Actions</th><th class="hidden">lab_id</th></tr></thead>'
    );
    var lab_tbody = $(document.createElement('tbody')).appendTo(lab_table);
    //getLabDeployListTable(groupname);

    //$(winId).find('div.window_bottom')
    //    .text('Subgroup template setting');
}

function create_subgroup(element) {

    if ($('#group_select_for_team').val() < 0) {
        swal('Please select a group from the group list!', '', 'warning');
        return;
    }
    var groupname = $('#group_select_for_team>option:selected').text();
    var gId = $('#group_select_for_team>option:selected').val();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_create_subgroup').attr('title', 'Create Team in Group ' + groupname);

    //var form_html = $(document.createElement('div')).appendTo(dlg_form);
    var left_form = $(document.createElement('div')).css('float', 'left').appendTo(dlg_form);
    var right_list = $(document.createElement('div')).css('float', 'right').css('margin', '5px').appendTo(dlg_form);
    left_form.append('<input type="radio" name="subgroup_create_team" value="individual" onclick="create_team_select($(this))">&nbsp;Create Individual Team<br>' +
    '<input type="radio" name="subgroup_create_team" value="group" checked onclick="create_team_select($(this))">&nbsp;Create Group Team<br>' +
    '<div id="subgroup_create_team_group">' +
    '<label for="create_subgroup_name">Team name:</label>' +
    '<br>' +
    '<input type="text" id="create_subgroup_name">' +
    '<br>' +
    '<label for="create_subgroup_desc">Description:</label>' +
    '<br>' +
    '<textarea id="create_subgroup_desc" style="width: 200px; height: 95px; resize: none;"></textarea>' +
    '</div>' +
    '<div style="align-content: center; margin-top: 10px;">' +
    //'<button onclick="submit_create_subgroup($(this))"  value="' + groupname + '" >Create</button>' +
    '</div>');
    var table = $(document.createElement('table')).addClass('data').attr('id','tbl_member_list_in_set_member').css('width','530px').appendTo(right_list);
    table.append('<thead><tr><th><input type="checkbox" name="create_team_checkbox_all" onclick="check_checkbox_group($(this))"></th>' +
    '<th class="hidden">ID</th><th>Group User</th><th>Institute</th><th>Org ID (member id)</th><th>Role</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    $('#dlg_create_subgroup').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 900,
        buttons: {
            "Create": function() {
                var members = [];
                var sel_radio= $('input[name=subgroup_create_team]:checked').val();
                var member_tbody = $('#tbl_member_list_in_set_member').find('tbody');
                var check = member_tbody.find('input[name=create_team_checkbox]:checked:enabled');
                if (check.length === 0) {
                    swal('Please select members from the group user list!', '', 'warning');
                    return;
                }
                for (var i=0; i < check.length; i++) {
                    if (check[i].checked)
                        members.push(check[i].parentNode.nextSibling.nextSibling.innerHTML);
                }
                //var member_str = members.map(function(id) { return "'" + id + "'"; }).join(", ");
                //var member_in_str = "(" + member_str + ")";
                var subgroup_name = $('#create_subgroup_name').val();
                var subgroup_desc = $('#create_subgroup_desc').val();
                //var subgroup_tbody = $('#tbl_team_list_' + groupname).find('tbody');
                var subgroup_tbody = $('#tbl_team_list_' + 'all').find('tbody');

                if (sel_radio === 'individual') {
                    $.post("subgroup/create_individual_team", {
                            "group_id": gId,
                            "group_name" : groupname,
                            "members": members,
                            "description": 'Individual Team'
                        },
                        function (data) {
                            if (data.status === 'Success') {
                                $.each(data.subgroups, function (index, team) {
                                    $('<tr>' +
                                    '<td><input type="checkbox" name="subgroups_checkbox" value="' + team.id + '"></td>' +
                                    '<td class="hidden">' + team.id + '</td><td>' + team.name + '</td>' +
                                    '<td>' + team.description + '</td><td>' + team.name + '</td><td></td>' +
                                    '<td class="dropdown"><a class="btn btn-default team-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                                    '</tr>').prependTo(subgroup_tbody);
                                });
                                swal(data.message, '', 'success');
                            } else {
                                swal(data.message, '', 'warning');
                            }
                        },
                        'json'
                    );

                } else if (sel_radio === 'group') {
                    if ((subgroup_name.trim().length === 0) || (subgroup_desc.trim().length === 0)) {
                        swal('Please enter the Team Name and Description!', '', 'warning');
                        return;
                    }
                    $.post("subgroup/create_subgroup", {
                            "group_id": gId,
                            "group_name": groupname,
                            "members": members,
                            "subgroup_name": subgroup_name,
                            "subgroup_desc": subgroup_desc
                        },
                        function (data) {
                            if (data.status === 'Success') {
                                $('<tr>' +
                                '<td><input type="checkbox" name="subgroups_checkbox" value="' + data.subgroup.id + '"></td>' +
                                '<td class="hidden">' + data.subgroup.id + '</td><td>' + subgroup_name + '</td>' +
                                '<td>' + subgroup_desc + '</td><td>' + members + '</td><td></td>' +
                                '<td class="dropdown"><a class="btn btn-default team-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                                '</tr>').prependTo(subgroup_tbody);
                                swal(data.message, '', 'success');
                            } else {
                                swal(data.message, '', 'warning');
                            }
                        },
                        'json'
                    )
                }
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });

    $.getJSON("/group/getGroupUser/" + gId, function (item) {
        $.each(item.users, function (index, user) {
            var isOwner = false;
            var roles = '';
            $.each(user.roles, function(indx, role) {
                if (role.name === 'group_owner') {
                    isOwner = true;
                } else {
                    roles += role.name + ','
                }
            });
            if (!isOwner) {
                tbody.append('<tr><td><input type="checkbox" name="create_team_checkbox"></td><td class="hidden">' + user.data.id +
                    '</td><td>' + user.data.email + '</td><td>' + user.data.institute + '</td><td>' + user.data.org_id + '</td><td>' +
                    roles.slice(0,-1) + '</td></tr>');
            }
        })
    });
}

function update_team_info(element) {
    //var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    var groupname = $('#group_select_for_team>option:selected').text();
    var team_id = element.closest('tr').children().eq(1).html();
    var team_name = element.closest('tr').children().eq(2).html();
    var team_desc = element.closest('tr').children().eq(3).html();

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_update_team_info').attr('title', 'Update Team Information');

    $('<div><label for="update_team_name">Team name:</label><br>' +
        '<input type="text" id="update_team_name" value="' + team_name  + '"><br>' +
        '<label for="update_team_desc">Description:</label><br>' +
        '<textarea id="update_team_desc" style="width: 220px; height: 100px; resize: none;">' + team_desc +
        '</textarea></div>').appendTo(dlg_form);
    //var submit = $('<button />').attr('id', 'submit_update_team_info').text('Update')
    //    .attr('onclick', 'submit_update_team_info("' + groupname + '", ' + team_id + ')');
    //submit.appendTo(dlg_form);
    $('#dlg_update_team_info').dialog({
        modal: true,
        height: 250,
        overflow: "auto",
        width: 300,
        buttons: {
            "Update": function () {
                var t_name = $('#update_team_name').val();
                var t_desc = $('#update_team_desc').val();
                $.post("subgroup/updateSubGroup", {
                        "group_name": groupname,
                        "subgroup_id": team_id,
                        "subgroup_name": t_name,
                        "subgroup_desc": t_desc
                    },
                    function (jsondata) {
                        if (jsondata.status === "Success") {
                            $(element).closest('tr').children().eq(2).html(t_name);
                            $(element).closest('tr').children().eq(3).html(t_desc);
                        }
                        else {
                            swal(jsondata.message, '', 'warning');
                        }
                    },
                    'json'
                );
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function delete_team(team_id, element) {
    var message =  'Are you sure you want to delete team ' + $(element).attr('value') + ' ?<br />';
    create_ConfirmDialog('Delete a team', message,
        function() {
            //var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
            var groupname = $('#group_select_for_team>option:selected').text();
            $.post("subgroup/delete_subgroup", {
                    "subgroup_id": team_id,
                    "group_name": groupname
                },
                function (jsondata) {
                    if (jsondata.status === "Success") {
                        element.closest('tr').remove();
                        //var tbody = $('#tbl_team_list_' + groupname).find('tbody');
                        var tbody = $('#tbl_team_list_' + 'all').find('tbody');
                        var contextMenu = $(document.createElement('ul')).appendTo(tbody);
                        contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'team-contextMenu')
                            .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
                        $('<li><a tabindex="-1" href="#" class="team-members">Members</a></li>').appendTo(contextMenu);
                        //$('<li><a tabindex="-1" href="#" class="go_pending_enroll">Waiting List</a></li>').appendTo(contextMenu);
                        //$('<li><a tabindex="-1" href="#" class="go_add_member">Invite Members</a></li>').appendTo(contextMenu);
                        $('<li><a tabindex="-1" href="#" class="team-edit">Rename</a></li>').appendTo(contextMenu);
                        $('<li><a tabindex="-1" href="#" class="team-delete">Delete</a></li>').appendTo(contextMenu);
                    }
                    else {
                        swal(jsondata.message, '', 'warning');
                    }
                },
                'json'
            );
        },function() {
            // Cancel function
        });
}

function create_team_select(radio_btn) {
    if (radio_btn.val() == 'group') {
        $('#subgroup_create_team_group').css('display', 'block');
    } else if (radio_btn.val() == 'individual') {
        $('#subgroup_create_team_group').css('display', 'none');
    }
}

function check_checkbox_group(element) {
    var tbody = element.closest('table').find('tbody');
    //var tbody = $('#tbl_member_list_in_set_member').find('tbody');
    var check = tbody.find('input[type=checkbox]');
    for (var i=0; i < check.length; i++) {
        if (check[i].disabled) continue;
        check[i].checked = element[0].checked;
    }
}

function list_check(element) {
    //var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    var tbody = element.closest('table').find('tbody');
    var check = tbody.find('input[type=checkbox]');
    for (var i=0; i < check.length; i++) {
        check[i].checked = element[0].checked;
    }
}

function getTeamListTable(groupname) {
    var tab = $('#tbl_team_list_' + groupname);
    $(tab).find('thead input[type=checkbox]').attr('checked', false);
    var tbody = $(tab).find('tbody');
    tbody.empty();

    if ($('#group_select_for_team').val() < 0) {
        return;
    }
    var group = $('#group_select_for_team>option:selected').text();

    var win_main = $(tab).closest('div.tab');
    run_waitMe(win_main, 'ios');
    $.getJSON("/subgroup/getTeamList/" + group, function (jsondata) {
        $.each(jsondata, function (index, team) {
            var temps = '';
            for (var i=0; i<team.templates.length; i++) {
                //temps.push(team.templates[i].temp_name);
                temps += '<i class="fa fa-flask"></i>' + team.templates[i].temp_name + '<br />';
            }
            //var team_members = '';
            //for (var i=0; i<team.members.length; i++) {
            //    team_members += '<i class="fa fa-user"></i>' + team.members[i] + '<br />';
            //}

            $('<tr>' +
            '<td><input type="checkbox" name="subgroups_checkbox" value="' + team.id + '"></td>' +
            '<td class="hidden">' + team.id + '</td>' +
            '<td>' + team.name + '</td>' +
            '<td>' + team.desc + '</td>' +
            '<td>' + team.members.toString().split(',').join('<br />') + '</td>' +
            '<td>' + temps + '</td>' +
            '<td class="dropdown"><a class="btn btn-default team-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
            '</tr>').appendTo(tbody);
        });
        $(win_main).waitMe('hide');
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'team-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="team-members">Members</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="go_pending_enroll">Waiting List</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="go_add_member">Invite Members</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="team-edit">Rename</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="team-delete">Delete</a></li>').appendTo(contextMenu);

}

function getLabDeployListTable(groupname) {
    var tab = $('#tbl_lab_deploy_list_' + groupname);
    $(tab).find('thead input[type=checkbox]').attr('checked', false);
    var tbody = $(tab).find('tbody');
    tbody.empty();

    if ($('#group_select_for_team').val() < 0) {
        return;
    }
    var group = $('#group_select_for_team>option:selected').text();

    var win_main = $(tab).closest('div.tab');
    run_waitMe(win_main, 'ios');
    $.getJSON("/subgroup/getSubgroupTemplateProject/" + group, function (jsondata) {
        $.each(jsondata, function (index, lab) {
            //var progress = '<span><img src= "img/waiting.gif" width="50" height="20" style="margin-left: auto; margin-right: auto"/>';
            //if (lab.status == 'CREATE_IN_PROGRESS')
            //    progress += 'Creating</span>';
            ////else if (lab.status == 'CREATE_COMPLETE')
            ////    progress = 'COMPLETE';
            //else
            //    progress = lab.status;
            var tr = $('<tr />').appendTo(tbody);
            tr.append(
            '<td><input type="checkbox" name="subgroups_checkbox" value="' + lab.subgroup_id + '"></td>' +
            '<td class="hidden">' + lab.subgroup_id + '</td>' +
            '<td>' + lab.subgroup_name + '</td>' +
            '<td class="hidden">' + lab.template_id + '</td>' +
            '<td>' + lab.template_name + '</td>' +
            '<td class="hidden">' + lab.project_name + '</td>' +
            '<td>' + lab.lab_name + '</td>' +
            '<td>' + lab.desc + '</td>' +
            '<td>' + (new Date(lab.assign_at.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,"") + '</td>' +
            '<td>' + ((lab.deploy_at === '0000-00-00 00:00:00') ? '-' : (new Date(lab.deploy_at.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,"")) + '</td>' +
            '<td class="hidden">' + lab.status + '</td>' +
            '<td>' + '' + '</td>' +
            '<td>' + ((lab.due_at === '0000-00-00 00:00:00') ? '-' : (new Date(lab.due_at.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,"")) + '</td>' +
            '<td class="dropdown"><a class="btn btn-default labDeploy-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>'
            );
            display_status(tr.children().eq(11), lab.status, tr.children().eq(10), lab.deploy_at);
            if (lab.status === 'Deploying' || lab.status === 'CREATE_IN_PROGRESS' || lab.status === 'Deleting' ) {
                update_deploy_list(group, tr.children().eq(11), lab, null, '');
            }
        });
        $(win_main).waitMe('hide');
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'labDeploy-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="lab-view">View</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="lab-deploy">Deploy</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="lab-delete">Delete</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="lab-rename">Update Info</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="lab-events">Stack Status</a></li>').appendTo(contextMenu);
}

function get_selected_lab(element) {
    var labs = [];
    if (element.attr('type') === 'batch') {
        var checked_lab = $(element).closest('div.tab').find('tbody input[type=checkbox]:checked:enabled');
        if (checked_lab.length === 0) {
            swal('You need to select at least one lab to update or delete the lab!', '', 'warning');
            return false;
        }
        for (var i = 0; i < checked_lab.length; i++) {
            var tr = checked_lab[i].parentNode.parentNode;
            var lab = { team_id: tr.children[1].textContent, team_name: tr.children[2].textContent,
                temp_id: tr.children[3].textContent, temp_name: tr.children[4].textContent,
                project_name: tr.children[5].textContent, lab_name: tr.children[6].textContent,
                lab_desc: tr.children[7].textContent, status: tr.children[10].textContent };
            labs.push(lab);
        }
    } else {
        var tr = element.closest('tr').children();
        var lab = { team_id: tr.eq(1).html(), team_name: tr.eq(2).html(), temp_id: tr.eq(3).html(),
            temp_name: tr.eq(4).html(), project_name: tr.eq(5).html(), lab_name: tr.eq(6).html(),
            lab_desc: tr.eq(7).html(), status: tr.eq(10).html() };
        labs.push(lab);
    }
    return labs;
}

function lab_deploy(element) {
    //var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    var groupname = $('#group_select_for_team>option:selected').text();
    var labs = get_selected_lab(element);
    if (labs === false) return;
    if (labs.length > 5) {
        swal('The deployment in batch cannot select more than 5 labs due to resourse limitation! Please reduce!', '', 'warning');
        return;
    }
    labs.forEach(function(item, index, object) {
       if (item.status === 'Deploying' || item.status === 'CREATE_COMPLETE' || item.status === 'CREATE_IN_PROGRESS') {
           object.splice(index, 1);
       }
    });
    var rows = element.closest('.tab-content').find('tbody').children();
    //var message = 'The deployment process will take a few minutes.<br /> Please click REFRESH TABLE to update the status.<br />';
    var message = 'The deployment process will take a few minutes.<br /> Please wait for the process finish before using it.<br />';
    create_ConfirmDialog('Deploy the Selected Labs', message,
        function() {
            labs.forEach(function(lab, index, object) {
                var status = rows.children('td:contains(' + lab.project_name + ')').siblings('td:eq(10)');
                var deploy_at = rows.children('td:contains(' + lab.project_name + ')').siblings('td:eq(8)');
                display_status(status, 'Deploying', null, '');
                //var current = new Date().toISOString().slice(0,19).replace('T', ' ');

                $.post("labs/deploy_lab", {
                    "groupname": groupname,
                    "lab": lab
                    //"datetime": current
                    },
                    function(data) {
                        if ((data.status === 'Success') && (lab['temp_id'] !== 0)) {
                            //display_status(status, 'Deploying');
                            setTimeout(function () {
                                update_deploy_list(groupname, status, lab, deploy_at);
                            }, 5000);
                        } else {
                            display_status(status, data.deploy_status, deploy_at);
                        }
                    },
                    'json'
                );
            })
        }, function () {
                // Cancel function
        }
    );
}

function update_deploy_list(groupname, status_holder, lab, deploy_at) {
    $.post("/labs/checkStackStatus", {
        "project_name": lab.project_name
        },
        function(data) {
            if (data.status === 'CREATE_COMPLETE') {
                display_status(status_holder, data.status, deploy_at);
                update_lab_project_table(lab, data.status, status_holder, deploy_at);
            }
            else if (data.status === 'CREATE_IN_PROGRESS' || data.status === 'DELETE_IN_PROGRESS') {
                display_status(status_holder, data.status, deploy_at);
                setTimeout(function () {
                    update_deploy_list(groupname, status_holder, lab, deploy_at);
                }, 5000);
            } else if (data.status === 'DELETE_COMPLETE' || data.status === 'Stack Deleted') {
                //update_lab_project_table(lab, data.status, status_holder);
                project_delete(groupname, status_holder, lab);
            }
        },
    'json'
    );
}

function update_lab_project_table(lab, status, status_holder, deploy_at) {
    var current = new Date().toISOString().slice(0,19).replace('T', ' ');
    $.post("/labs/update_Lab_Project", {
            "project_name"  : lab.project_name,
            "status"        : status,
            "deploy_at"     : current
        },
        function(data) {
            deploy_at.html((new Date(current.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,""));
            if (data.status === 'fail') {
                display_status(status_holder, data.status, deploy_at);
            }
        },
        'json'
    );
}

function display_status(status_holder, status, deploy_at) {
    var spinner = $('<span />').append('<img src=' + ICON_status_progress + ' width="50" height="20" style="margin-left: auto; margin-right: auto"/>');
    status_holder.prev().html(status);

    if (status == 'Deleting' || status == 'Deploying' || status == 'CREATE_IN_PROGRESS' || status == 'DELETE_IN_PROGRESS' || status == 'Deleting Project') {
        status_holder.html(spinner.append(status));

    } else if (status == 'CREATE_COMPLETE') {
        status_holder.html('<img src=' + ICON_status_ok + ' title="Deploy Complete" />');
        //deploy_at.html(current);
    } else if (status.length != 0) {
        status_holder.html('<img src=' + ICON_status_error + ' title="' + status + '" />');
    }
}

function lab_rename(element) {
    //var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    //var groupname = $('#group_select_for_team>option:selected').text();
    var labs = get_selected_lab(element);
    if (labs === false) return;

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_update_lab_info').attr('title', 'Update Lab Information');

    $('<div><label for="update_lab_name">Lab name:</label><br>' +
    '<input type="text" id="update_lab_name"><br>' +
    '<label for="update_lab_due">Lab due date:</label><br>' +
    '<input type="text" id="lab_due_date" ><br>' +     //<button onclick="show_datetimepicker()">Pick Date</button><br>' +
    '<label for="update_lab_desc">Description:</label><br>' +
    '<textarea id="update_lab_desc" style="width: 220px; height: 100px; resize: none;"></textarea></div>').appendTo(dlg_form);
    //$('<button />').attr('id', 'submit_update_lab_info').text('Update').attr('type', element.attr('type'))
    //    .attr('group', element.attr('group')).attr('onclick', 'submit_update_lab_info($(this))').appendTo(dlg_form);
    $('#dlg_update_lab_info').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 300,
        buttons: {
            "Update": function () {
                $.post("labs/update_lab_info", {
                        "labs": labs,
                        "lab_name": $('#update_lab_name').val().trim(),
                        "lab_due": $('#lab_due_date').val().trim(),
                        "desc": $('#update_lab_desc').val().trim()
                    },
                    function (data) {
                        //if (data.status == 'Success') {
                            getLabDeployListTable('all');
                        //} else {
                        //    alert(data.message);
                        //}
                    },
                    'json'
                );
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

    $('#lab_due_date').appendDtpicker();
}

function lab_events(element) {
    //var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    //var groupname = $('#group_select_for_team>option:selected').text();
    var labs = get_selected_lab(element);
    if (labs === false) return;
    var lab=labs[0].project_name;

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_show_lab_events').attr('title', 'Lab Deploy Events');

    $('<div><label for="lab_events">Events:</label><br>').appendTo(dlg_form);
    var table = $(document.createElement('table')).addClass('data').attr('id','tbl_update_member_in_team').css('width','530px').appendTo(dlg_form);
    table.append('<thead><tr>' +
    '<th class="hidden">ResourceID</th><th>Resource Name</th><th>Time</th><th>Status</th><th>Status Reason</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);
    //$('<button />').attr('id', 'submit_update_lab_info').text('Update').attr('type', element.attr('type'))
    //    .attr('group', element.attr('group')).attr('onclick', 'submit_update_lab_info($(this))').appendTo(dlg_form);
    $('#dlg_show_lab_events').dialog({
        modal: true,
        height: 600,
        overflow: "auto",
        width: 800,
        buttons: {
            "Refresh": function () {
                $.post("/labs/checkStackEvents", {
                        "project_name": lab
                    },
                    function (data) {
                        //if (data.status == 'Success') {
                        //$('#lab_events').val(JSON.stringify(data));

                        //} else {
                        //    alert(data.message);
                        //}
                        tbody.empty();
                        $.each(data, function (index, item) {
                            if(index !== 'size') {
                                tbody.append('<tr><td class="hidden">' + item.resourceLogicalId + '</td><td>' + item.resourceName + '</td><td>' + item.time + '</td><td>' + item.resourceStatus + '</td><td>' + item.resourceStatusReason + '</td></tr>');
                            }
                        });
                    },
                    'json'
                );

            },
            "Close": function () {
                $(this).remove();
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
    $.post("/labs/checkStackEvents", {
            "project_name": lab
        },
        function (data) {
            //if (data.status == 'Success') {
            //$('#lab_events').val(JSON.stringify(data));
            //} else {
            //    alert(data.message);
            //}
            $.each(data, function (index, item) {
                    if(index !== 'size') {
                        tbody.append('<tr><td class="hidden">' + item.resourceLogicalId + '</td><td>' + item.resourceName + '</td><td>' + item.time + '</td><td>' + item.resourceStatus + '</td><td>' + item.resourceStatusReason + '</td></tr>');
                    }
            });
        },
        'json'
    );
}

function lab_delete(element) {
    //var groupname = element.closest('.window').attr('id').substring('window_group_project_'.length);
    var groupname = $('#group_select_for_team>option:selected').text();
    var labs = get_selected_lab(element);
    if (labs === false) return;
    if (labs.length > 10) {
        $.jAlert({
            'title': 'Warning', 'content': 'The deletion in batch cannot select more than 10 labs!',
            'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
        });
        return;
    }
    var rows = element.closest('.tab-content').find('tbody').children();
    var message =  'Are you sure you want to delete the selected labs?<br />';
    create_ConfirmDialog('Delete the Selected Labs', message,
        function() {
            $.each(labs, function(index, lab) {
                var status = rows.children('td:contains(' + lab.project_name + ')').siblings('td:eq(10)');
                display_status(status, 'Deleting', null, '');
                $.post("labs/delete_stack", {
                        "groupname": groupname,
                        "lab": lab
                    },
                    function(data) {
                        //alert(JSON.stringify(data));
                        if (data.status === 'Success') {
                            display_status(status, data.message, null, '');
                            setTimeout(function () {
                                project_delete(groupname, status, lab);
                            }, 500);
                        } else {
                            if (data.message === 'DELETE_IN_PROGRESS') {
                                display_status(status, data.message, null, '');
                                setTimeout(function () {
                                    update_deploy_list(groupname, status, lab, null, '');
                                }, 5000);
                            } else
                                display_status(status, data.message, null, '');
                        }
                    }
                );
            });
        },function() {
            // Cancel function
        });
}

function project_delete(groupname, status, lab) {
    display_status(status, 'Deleting Project', null, '');
    $.post("labs/delete_project", {
            "groupname": groupname,
            "lab": lab
        },
        function(data) {
            //alert(JSON.stringify(data));
            if (data.status === 'Success') {
                //rows.children('td:contains(' + lab.project_name + ')').remove();
                status.closest('tr').remove();

                //var tbody = $('#tbl_lab_deploy_list_' + groupname).find('tbody');
                var tbody = $('#tbl_lab_deploy_list_' + 'all').find('tbody');
                var contextMenu = $(document.createElement('ul')).appendTo(tbody);
                contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'labDeploy-contextMenu')
                    .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
                $('<li><a tabindex="-1" href="#" class="lab-deploy">Deploy</a></li>').appendTo(contextMenu);
                $('<li><a tabindex="-1" href="#" class="lab-delete">Delete</a></li>').appendTo(contextMenu);
                $('<li><a tabindex="-1" href="#" class="lab-rename">Update Info</a></li>').appendTo(contextMenu);
            } else {
                display_status(status, data.message, null, '');
            }
        }
    );
}

function assign_template(element) {
    var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    var checked_team = element.closest('div.tab').find('tbody input[type=checkbox]:checked:enabled');
    if (checked_team.length === 0) {
        swal('You need to select at least one team to assign a lab template!', '', 'warning');
        return;
    }
    var assign_teams = [];
    var team_names = [];
    for (var i = 0; i < checked_team.length; i++) {
        var tr = checked_team[i].parentNode.parentNode;
        var team = {team_id: tr.children[1].textContent, team_name: tr.children[2].textContent,
            templates: tr.children[5].textContent };
        assign_teams.push(team);
        team_names.push(tr.children[2].textContent);
    }

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_new_assign_template').attr('title', 'Assign Template');

    $('<div>Select Template: <select id="select_team_template"></select></div>').appendTo(dlg_form);
    $('<br><br><p>Assign the selected template to following subgroups: <br><br><p>' +
        team_names.toString().split(',').join('<br />') + '</p></p>').appendTo(dlg_form);
    //$('<button />').attr('id', 'new_assign_template_button').text('Assign')
    //    .attr('onclick', 'submit_new_assign_template("' + table_id + '")').appendTo(dlg_form);

    $('#dlg_new_assign_template').dialog({
        modal: true,
        height: 250,
        overflow: "auto",
        width: 400,
        buttons: {
            "Assign": function() {
                var selected_temp_id = $('#select_team_template :selected').val();
                var selected_temp_name = $('#select_team_template :selected').text();
                var duplicate = [];
                var assign_to = [];
                for (var i=0; i < assign_teams.length; i++) {
                    if ($.inArray(selected_temp_name, assign_teams[i].templates.split(',')) >= 0) {
                        duplicate.push(assign_teams[i].team_name);
                    } else {
                        assign_to.push(assign_teams[i]);
                    }
                }
                if (duplicate.length > 0) {
                    swal('The selected template has been already assigned to the team: ' + duplicate, '', 'warning');
                }
                $.post("labs/assignTemplateToTeams", {
                        "groupname": groupname,
                        "temp_id": selected_temp_id,
                        "teams": assign_to,
                        "lab_id": 0
                    },
                    function (data) {
                        if (data.failed.length > 0) {
                            $.jAlert({
                                'title': 'Warning', 'content': 'Assign the template ' + selected_temp_name + ' to ' + data.failed + ' failed.',
                                'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                            });
                        }
                    }).done(getTeamListTable('all'));
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });

    //$.getJSON("cloud/getOwnClass", function (jsondata) {
    //    var table = $("#select_team_template");
    //    table.empty();
    //    $.each(jsondata, function (index, item) {
    //        table.append('<option name=="' + item.courseid + '" value="' + item.nameid + '">' + item.coursename + '</option>');
    //    });
    //});

    $.getJSON("cloud/getTemplate1", function (jsondata) {
        var select_temp = $("#select_team_template");
        $.each(jsondata, function (index, item) {
            if (index == 'templates') {
                $.each(item, function (index3, item3) {
                    $(select_temp).append('<option value="' + item3.id + '">' + item3.name + '</option>');
                });
            }
        })
    });
}

function assign_lab(element) {
    //var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    var groupname = $('#group_select_for_team>option:selected').text();
    var checked_team = element.closest('div.tab').find('tbody input[type=checkbox]:checked:enabled');
    if (checked_team.length === 0) {
        swal('You need to select at least one team to assign a lab template!', '', 'warning');
        return;
    }
    var assign_teams = [];
    var team_names = [];
    for (var i = 0; i < checked_team.length; i++) {
        var tr = checked_team[i].parentNode.parentNode;
        var team = {team_id: tr.children[1].textContent, team_name: tr.children[2].textContent,
            templates: tr.children[5].textContent };
        assign_teams.push(team);
        team_names.push(tr.children[2].textContent);
    }

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_new_assign_template').attr('title', 'Assign Template');

    $('<div>Select Lab: <select id="select_team_template"></select></div>').appendTo(dlg_form);
    $('<br><br><p>Assign the selected Lab to: <br><br><p>' + team_names.toString() + '</p></p>').appendTo(dlg_form);
    //$('<button />').attr('id', 'new_assign_template_button').text('Assign')
    //    .attr('onclick', 'submit_new_assign_template("' + table_id + '")').appendTo(dlg_form);

    $('#dlg_new_assign_template').dialog({
        modal: true,
        height: 250,
        overflow: "auto",
        width: 400,
        buttons: {
            "Assign": function() {
                var selected_temp_id = $('#select_team_template :selected').val();

                var selected_lab_id=($('#select_team_template :selected').attr('value1').length==0)?0:$('#select_team_template :selected').attr('value1');
                var selected_temp_name = $('#select_team_template :selected').text();
                var duplicate = [];
                var assign_to = [];
                for (var i=0; i < assign_teams.length; i++) {
                    if ($.inArray(selected_temp_name, assign_teams[i].templates.split(',')) >= 0) {
                        duplicate.push(assign_teams[i].team_name);
                    } else {
                        assign_to.push(assign_teams[i]);
                    }
                }
                if (duplicate.length > 0) {
                    $.jAlert({
                        'title': 'Warning', 'content': 'The selected template has been already assigned to the team: ' + duplicate,
                        'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                    });
                }
                $.post("labs/assignTemplateToTeams", {
                        "groupname": groupname,
                        "temp_id": selected_temp_id,
                        "teams": assign_to,
                        "lab_id": selected_lab_id,
                        "assign_at": new Date().toISOString().slice(0,19).replace('T', ' ')
                    },
                    function (data) {
                        if (data.failed.length > 0) {
                            $.jAlert({
                                'title': 'Warning', 'content': 'Assign the template ' + selected_temp_name + ' to ' + data.failed + ' failed.',
                                'theme': 'yellow', 'btns': {'text': 'close', 'theme': 'green'}
                            });
                        }
                    }).done(getTeamListTable('all'));
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });

    var table = $("#select_team_template");
    $.getJSON("labs/getOwnClassLabTemp", function (jsondata) {
        //var table = $("#select_team_template");
        //table.empty();
        table.append('<option disabled style="font-weight: bold">My Own Labs:</option>');
        $.each(jsondata, function (index, item) {
            table.append('<option value1="' + item.labid + '" value="' + item.tempid + '">&nbsp;&nbsp;&nbsp;' + item.coursename + '</option>');
        });
    });

    $.getJSON("labs/getOpenClassLabTemp", function (jsondata){
        //var table = $("seletct_team_template");
        table.append('<option disabled style="font-weight: bold">Open Labs:</option>');
        $.each(jsondata, function (index, item) {
            table.append('<option value1="' + item.labid + '" value="' + item.tempid + '">&nbsp;&nbsp;&nbsp;' + item.coursename + '</option>');
        });
    });

    //$.getJSON("cloud/getTemplate1", function (jsondata) {
    //    var select_temp = $("#select_team_template");
    //    $.each(jsondata, function (index, item) {
    //        if (index == 'templates') {
    //            $.each(item, function (index3, item3) {
    //                $(select_temp).append('<option value="' + item3.id + '">' + item3.name + '</option>');
    //            });
    //        }
    //    })
    //});
}

function update_team_members(team_id, team_name, team_members, element) {
    //var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    var groupname = $('#group_select_for_team>option:selected').text();
    var gId = $('#group_select_for_team>option:selected').val();
    //var team_name = $(element).attr('value');
    var teammembers =  team_members.split('<br>'); //$(element).attr('name').split(',');

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_subgroup_members').attr('title', 'Update Team Member for ' + team_name);

    var left_form = $(document.createElement('div')).css('float', 'left').appendTo(dlg_form);
    var right_list = $(document.createElement('div')).css('float', 'right').css('margin', '5px').appendTo(dlg_form);
    left_form.append('<div>' +
            '<br><label for="select_team_member_list">Team Members:</label><br><br>' +
            '<select id="select_team_member_list" MULTIPLE style="width: 170px"></select><br><br>');
    //$('<button />').attr('id', 'btn_update_team_members').attr('team_name', team_name).attr('team_id', team_id).text('Update')
    //    .attr('groupname', groupname).attr('onclick', 'submit_update_team_members($(this))').appendTo(left_form);
    $(document.createElement('button')).attr('id', 'btn_add_team_member')
        .css('position', 'absolute').css('left', '200px').css('top', '60px').text('<-').appendTo(dlg_form);
    $(document.createElement('button')).attr('id', 'btn_remove_team_member')
        .css('position', 'absolute').css('left', '200px').css('top', '90px').text('->').appendTo(dlg_form);

    var table = $(document.createElement('table')).addClass('data').attr('id','tbl_update_member_in_team').css('width','530px').appendTo(right_list);
    table.append('<thead><tr><th><input type="checkbox" name="team_update_member_checkbox_all" onclick="check_checkbox_group($(this))"></th>' +
    '<th class="hidden">UserId</th><th>Group User</th><th>Institute</th><th>Org ID (member id)</th><th>Role</th></tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    $.getJSON("/group/getGroupUser/" + gId, function (item) {
        $.each(item.users, function (index, user) {
            var roles = '';
            var isOwner = false;
            $.each(user.roles, function(idx, role) {
                roles += role.name + '<br>';
                if (role.name === 'group_owner') isOwner = true;
            });
            if ($.inArray(user.data.email, teammembers) >= 0) {
                $('<option value="' + user.data.id + '">' + user.data.email + '</option>')
                    .attr('institute', user.data.institute).attr('org_id', user.data.org_id).attr('role', roles.replace('<br>', ',').slice(0, -1))
                    .appendTo($('#select_team_member_list'));
            } else {
                if (!isOwner) {
                    tbody.append('<tr><td><input type="checkbox" name="team_update_member_checkbox"></td><td class="hidden">' + user.data.id + '</td><td>' +
                        user.data.email + '</td><td>' + user.data.institute + '</td><td>' + user.data.org_id + '</td><td>' + roles + '</td></tr>');
                }
            }
        });
        $('#btn_add_team_member').attr('title', 'Add team member').attr('onclick', 'change_team_member(1)');
        $('#btn_remove_team_member').attr('title', 'Remove team member').attr('onclick', 'change_team_member(0)');
    });

    $('#dlg_subgroup_members').dialog({
        modal: true,
        height: 300,
        overflow: "auto",
        width: 800,
        buttons: {
            "Update": function() {
                var members = [];
                var members_name = [];
                $('#select_team_member_list option').each(function () {
                    var userdata = {user_id: $(this).val(), user_name: $(this).text(), role: $(this).attr('role')};
                    members_name.push($(this).text());
                    members.push(userdata);
                });
                $.post("subgroup/update_subgroup_member", {
                        "team_name": team_name,
                        "team_id": team_id,
                        "members": members
                    },
                    function (data) {
                        if (data.status === 'Success') {
                            var members_names = (members_name.length === 0) ? " " : members_name.toString().split(',').join('<br />');
                            $(element).closest('tr').children().eq(4).html(members_names);
                        } else {
                            swal(data.message, '', 'warning');
                        }
                    },
                    'json'
                );
                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close')
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function change_team_member(type) {
    var select_members = $('#select_team_member_list');
    var member_table = $('#tbl_update_member_in_team tbody');

    if (type === 1) {  // add
        var checked = member_table.find('input[type=checkbox]:checked:enabled');
        for (var i = 0; i < checked.length; i++) {
            //var user = checked[i].closest('tr');
            var user = checked[i].parentNode.parentNode;
            var opt = $('<option />').text(user.children[2].textContent)
                .attr('value', user.children[1].textContent).attr('institute', user.children[3].textContent)
                .attr('org_id', user.children[4].textContent).attr('role', user.children[5].textContent);
            opt.appendTo(select_members);
            checked[i].parentNode.parentNode.remove();
        }
    } else if (type === 0) { // remove
        $('#select_team_member_list :selected').each(function(index, selected) {
            member_table.append('<tr><td><input type="checkbox" name="team_update_member_checkbox"></td><td class="hidden">' +
            $(selected).val() + '</td><td>' + $(selected).text() + '</td><td>' + $(selected).attr('institute') + '</td><td>' +
            $(selected).attr('org_id') + '</td><td>' + $(selected).attr('role') + '</td></tr>');
            $(selected).remove();
        });
    }
}

function lab_refresh(element) {
    var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
    getLabDeployListTable(groupname);
}

function gotousertimeline(element) {
    var tr = $(element).closest('tr');
    var row = $('#dataTableBuilder').DataTable().row(tr).data();
    var labid = row.user_id;
    //alert(row.id);
    // window.location.href = "/timeline/"+labid;
    document.getElementById('timelinebutton').setAttribute('onclick','loadusertimeline('+row.user_id+')');
    loadusertimeline(row.user_id);
    $('#modal-user-activity').modal('show');

}

function modal_edit_due_date(element) {
    var div_content = $('#div_update-contents').empty();

        var row = $('#dataTableBuilder').DataTable().row($(element).closest('tr')).data();
        $('.groupname-in-title').html(row.name);
        $('.update-labs-project').empty().html(row.name);
        var content_id = row.id;

        // $('#update_description').val(row.notes);

        // $('#contents_container_' + row.id).children('div[id^=content_]').each(function () {
            // var tag = $(this).attr('id').split('_')[1];
            // var content_id = tag.split('-')[1];
            // var content = $(this).find('span[id^=name_]').html();
            // var start = $(this).find('span[id^=start_at_]').html();
            // var due = $(this).find('span[id^=due_at_]').html();

            div_content.append('<div id="updatecontent_' + content_id + '"><div class="form-group">' +
                // '<label><b>Lab Content:</b></label>' +
                // '<input type="text" class="form-control" readonly id="labcontent_' + content_id + '"  />' +
                '<label><b>Labs Start At:</b></label>' +
                '<input type="text" class="form-control" id="labstart_' + content_id + '" />' +
                '</div>' +
                '<div class="form-group">' +
                '<label><b>Labs Due At:</b></label>' +
                '<input type="text" class="form-control" id="labdue_' + content_id + '" />' +
                '</div></div>');

            // $('#labcontent_' + content_id).empty().val(content);

            $('#labstart_' + content_id).empty().datetimepicker({
                //inline: true,
                minDate: new Date()
            });
            // $('#labstart_' + content_id).val(start);
            $('#labdue_' + content_id).empty().datetimepicker({
                //inline: true,
                minDate: new Date()
            });
            // $('#labdue_' + content_id).val(due);
        // });

    $('#modal-update-labs').modal('show');
}

function update_due_date() {


    var contents = [];
    $('#div_update-contents').children('div[id^=updatecontent_]').each(function () {
        var content_id = $(this).attr('id').split('_')[1];
        contents.push({content_id: content_id, start_at: $('#labstart_' + content_id).datetimepicker('getValue'),
            due_at: $('#labdue_' + content_id).datetimepicker('getValue')});
    });

    $.post('/labsdeploy/updateduedate', {
            'contents': contents
        }, function (data) {
            if (data.status === 'failed') {
                Swal.fire('Lab ' + data.projects.toString() + 'due date update failed!', '', 'error');
            } else {
                Swal.fire('Lab due date updated.', '', 'success');
            }
            $('#modal-update-labs').modal('hide');
            $('#dataTableBuilder').DataTable().draw();
        }
    );
}

function reopentask(element) {
    var projects = [];
    var table = $('#dataTableBuilder').DataTable();
    var rowData = table.row(element.closest('tr')).data();

    projects.push({id: rowData.id});


        Swal.fire({
            title: 'Reopen Tasks?',
            text: 'This action will reopen all tasks for student(s) and change tasks status to incomplete.',
            type: 'question',
            showCancelButton: true,
            confirmButton: 'Yes'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Tasks Reopened!', 'success');
                projects.forEach(function(project, index) {
                    setTimeout(function() {
                        $.post('/labsdeploy/reopentasks', {
                            'project': project
                        }, function (data) {

                        });
                    }, 500 * index);
                });
            }
        });

}