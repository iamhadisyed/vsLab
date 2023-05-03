/**
 * Created by James on 1/9/15.
 */

//$(document).ajaxStart(function () {
//    $('#desktop').addClass('wait');
//    //run_waitMe('ios')
//}).ajaxComplete(function () {
//    $('#desktop').removeClass('wait');
//    //$('body').waitMe('hide');
//});

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        async: true
    });

    //var min =300;
    //var max =3600;
    //$('#split-bar').mousedown(function (e){
    //    e.preventDefault();
    //    $(document).mousemove(function (e) {
    //        e.preventDefault();
    //        var x = e.pageX-$('#treepanelmain').offset().left;
    //        if (x> min && x< max && e.pageX<($(window).width()-200)){
    //            $('#treepanelmain').css("width",x);
    //            $('#labpanelmain').css("margin-left",x);
    //        }
    //    })
    //});

    document.domian = "thothlab.org";





});

var net_topology_data = {};
var net_topology = {};

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

function run_waitMe(selector, effect) {
    $(selector).waitMe({
        effect: effect,
        text: 'Please wait ...',
        bg: 'rgba(255,255,255,0.7)',
        color:'#000'
    });
}

function imageLoaded() {
    var w = $(this).width();
    var h = $(this).height();
    var parentW = $(this).parent().width();
    var parentH = $(this).parent().height();

    //console.log(w + '-' + h + '-' + parentW + '-' + parentH);

    //if (w >= parentW){ //always true because of CSS
    if (h > parentH){
        $(this).css('top', -(h-parentH)/2);
    } else if (h < parentH){
        $(this).css('height', parentH).css('width', 'auto');
        $(this).css('left', -($(this).width()-parentW)/2);
    }
    //}
}

function set_wallpaper(element, wallpaper) {
    var DIR = 'workspace-assets/images/misc/';
    element.closest('ul.menu').find('i.fa').remove();
    element.append('<i class="fa fa-check"></i>');
    var wallpaper_img = $('img.ws-wallpaper');
    switch (wallpaper) {
        case 'default':
            $(wallpaper_img).prop('src', DIR + 'wallpaper.jpg' + '?' + Math.random());
            break;
        case 'beach' :
            $(wallpaper_img).prop('src', DIR + 'beach.jpg' + '?' + Math.random());
            break;
        case 'mountain_lake' :
            $(wallpaper_img).prop('src', DIR + 'mountain_lake.jpg' + '?' + Math.random());
            break;
        case 'apple_mac_blue' :
            $(wallpaper_img).prop('src', DIR + 'apple_mac_blue.jpg' + '?' + Math.random());
            break;
        case 'toulouse' :
            $(wallpaper_img).prop('src', DIR + 'toulouse.jpg' + '?' + Math.random());
            break;
        case 'cinderella' :
            $(wallpaper_img).prop('src', DIR + 'cinderella.jpg' + '?' + Math.random());
            break;
        case 'city-building-1' :
            $(wallpaper_img).prop('src', DIR + 'city-building-1.jpg' + '?' + Math.random());
            break;
        case 'city-building-2' :
            $(wallpaper_img).prop('src', DIR + 'city-building-2.jpg' + '?' + Math.random());
            break;
        case 'city-nightview-1' :
            $(wallpaper_img).prop('src', DIR + 'city-nightview-1.jpg' + '?' + Math.random());
            break;
        case 'sunset-1' :
            $(wallpaper_img).prop('src', DIR + 'sunset-1.jpg' + '?' + Math.random());
            break;
        case 'sunset-2' :
            $(wallpaper_img).prop('src', DIR + 'sunset-2.jpg' + '?' + Math.random());
            break;
        case 'park' :
            $(wallpaper_img).prop('src', DIR + 'park.jpg' + '?' + Math.random());
            break;
        case 'palm-lake' :
            $(wallpaper_img).prop('src', DIR + 'palm-lake.jpg' + '?' + Math.random());
            break;
    }

    //$('img.wallpaper').each(function() {
    //    if (this.complete) {
    //        imageLoaded.call( this );
    //    } else {
    //        $(this).one('load', imageLoaded);
    //    }
    //});

    $.post("/cloud/setWallPaper", {
            //"_token": $(this).find('input[name=_token]').val(),
            "wall_paper": wallpaper
        },
        function (data) {  },
        'json'
    );
    return true;
}

function upload_file(type) {

    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_uploader').attr('title', 'File Uploader');

    var form_html = '<span class="btn btn-success fileinput-button">' +
        '<i class="glyphicon glyphicon-plus"></i><span>Add files...</span>' +
        //'<input id="fileuploader" type="file" name="files[]" data-url="/fileUpload" multiple>' +
        '<input id="fileuploader" type="file" name="uploaded_file" data-url="/workspace/fileUpload">' +
        '</span>';
    $(form_html).appendTo(dlg_form);
    $('<div id="progress" class="progress"><div class="progress-bar progress-bar-success"></div></div>').appendTo(dlg_form);
    $('<div id="files" class="files"></div>').appendTo(dlg_form);

    $('#dlg_uploader').dialog({
        modal: true,
        width: 400,
        buttons: [
            {
                id: "dlg_btn_ok",
                text: "OK",
                click: function () {
                    $(this).dialog('close');
                }
            },
            {
                id: "dlg_btn_cancel",
                text: "Cancel",
                click: function () {
                    $(this).dialog('close');
                }
            }
        ],
        close: function (event, ui) {
            $(this).remove();
        }
    });

    $('#dlg_btn_ok').button('disable');

    //$(function () {
    //    'use strict';
        // Change this to the location of your server-side upload handler:
        var uploadButton = $('<button/>')
                .addClass('btn btn-primary')
                .prop('disabled', true)
                .text('Processing...')
                .on('click', function () {
                    var $this = $(this),
                        data = $this.data();
                    $this
                        .off('click')
                        .text('Abort')
                        .on('click', function () {
                            $this.remove();
                            data.abort();
                        });
                    data.submit().always(function () {
                        $this.remove();
                    });
                });

        $('#fileuploader').fileupload({
            url: '/workspace/fileUpload',
            method: 'post',
            dataType: 'json',
            autoUpload: false,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            maxFileSize: 5000000, // 5 MB
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true
        }).on('fileuploadadd', function (e, data) {
            data.context = $('<div/>').appendTo('#files');
            $.each(data.files, function (index, file) {
                var node = $('<p/>')
                    .append($('<span/>').text(file.name));
                if (!index) {
                    node
                        .append('<br>')
                        .append(uploadButton.clone(true).data(data));
                }
                node.appendTo(data.context);
            });
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                file = data.files[index],
                node = $(data.context.children()[index]);
            if (file.preview) {
                node
                    .prepend('<br>')
                    .prepend(file.preview);
            }
            if (file.error) {
                node
                    .append('<br>')
                    .append($('<span class="text-danger"/>').text(file.error));
            }
            if (index + 1 === data.files.length) {
                data.context.find('button')
                    .text('Upload')
                    .prop('disabled', !!data.files.error);
            }
        }).on('fileuploadprogressall', function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }).on('fileuploaddone', function (e, data) {
            //alert('upload done!');
            //$.each(data.result.files, function (index, file) {
            //    if (file.url) {
            //        var link = $('<a>')
            //            .attr('target', '_blank')
            //            .prop('href', file.url);
            //        $(data.context.children()[index])
            //            .wrap(link);
            //    } else if (file.error) {
            //        var error = $('<span class="text-danger"/>').text(file.error);
            //        $(data.context.children()[index])
            //            .append('<br>')
            //            .append(error);
            //    }
            //});
            //alert(JSON.stringify(data));
            //$.each(data.result), function (index, result) {
                if (data.result.success) {
                    var done = $('<span class="text-danger"/>').text('File upload done.');
                    $(data.context.children()[0])
                        .append('<br>')
                        .append(done);
                } else {
                    var error = $('<span class="text-danger"/>').text('File upload failed.');
                    $(data.context.children()[0])
                        .append('<br>')
                        .append(error);
                }
            //}
            $('#dlg_btn_ok').button('enable');

        }).on('fileuploadfail', function (e, data) {
            $.each(data.files, function (index) {
                var error = $('<span class="text-danger"/>').text('File upload failed.');
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            });
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    //});

    //$("#fileUploader").fileupload({
    //    dataType: 'json',
    //    add: function (e, data) {
    //        data.context = $('<button/>').text('Upload')
    //            .appendTo(dlg_form)
    //            .click(function () {
    //                data.context = $('<p/>').text('Uploading...').replaceAll($(this));
    //                data.submit();
    //            });
    //    },
    //    done: function (e, data) {
    //        data.context.text('Upload finished.');
    //    }
    //    //url:"fileUpload",
    //    //allowedTypes:"png,gif,jpg,jpeg",
    //    //fileName:"uploaded_img",
    //    //onSuccess:function(files, data, xhr)
    //    //{
    //    //    data= $.parseJSON(data); // yse parseJSON here
    //    //    if(data.error){
    //    //        alert('upload failed!');
    //    //        //there is an error
    //    //    } else {
    //    //        //there is no error
    //    //        //fileName = data['fileName'];
    //    //        //$('#file_input').val(fileName);
    //    //        alert('upload ok!');
    //    //    }
    //    //}
    //});
}

function load_window_content(winId) {
    var win_main = $(winId).find('.window_main').empty();

    if (winId == '#window_projectlist') {
        populate_content_myprojects(winId, win_main);
    } else if (winId.substring(0, '#window_project_'.length) == '#window_project_') {
        populate_content_vms(winId, win_main);
    } else if (winId.substring(0, '#window_vm_'.length) == '#window_vm_') {
        get_vm_console(winId, win_main);
    } else if (winId.substring(0, '#window_profile'.length) == '#window_profile') {
        profile_display(winId, win_main);
    } else if (winId.substring(0, '#window_webrtc'.length) == '#window_webrtc') {
        display_webrtc_window(winId, win_main);
    } else if (winId.substring(0, '#window_videowebrtc'.length) == '#window_videowebrtc') {
        display_videowebrtc_window(winId, win_main);
    } else if (winId.substring(0, '#window_lab_templates'.length) == '#window_lab_templates') {
        populate_lab_template(winId, win_main);
    } else if (winId.substring(0, '#window_group_project'.length) == '#window_group_project') {
        //profile_group_project_display(winId, win_main);
        team_management_window(winId, win_main);
    } else if (winId.substring(0, '#window_conceptmap'.length) == '#window_conceptmap') {
        populate_content_conceptmap(winId, win_main);
    } else if (winId.substring(0, '#window_temp_design'.length) == '#window_temp_design') {
        populate_lab_design(winId,win_main);
    } else if (winId.substring(0, '#window_owncloud'.length) == '#window_owncloud') {
        populate_owncloud(winId, win_main);
    } else if (winId.substring(0, '#window_wordpress'.length) == '#window_wordpress') {
        populate_wordpress(winId, win_main);
    }else if (winId.substring(0, '#window_mylabs'.length) == '#window_mylabs') {
        populate_content_mylabs(winId, win_main);
    }else if (winId.substring(0, '#window_edxstudio'.length) == '#window_edxstudio') {
        get_edxstudio_console(winId, win_main);
    }else if (winId.substring(0, '#window_lab_design'.length) == '#window_lab_design') {
        populate_content_labdesign(winId, win_main);
    }else if (winId.substring(0, '#window_working_lab_'.length) == '#window_working_lab_') {
        populate_content_sdncontroller(winId, win_main);
    }else if (winId.substring(0, '#window_lab_show_'.length) == '#window_lab_show_') {
        populate_content_myvms(winId, win_main);
    }else if (winId.substring(0, '#window_openlab_topo_'.length) == '#window_openlab_topo_') {
        populate_content_openlabtopo(winId, win_main);
    }else if (winId.substring(0, '#window_open_lab_'.length) == '#window_open_lab_') {
        populate_content_viewopenlab(winId, win_main);
    }else if (winId.substring(0, '#window_lab_new'.length) == '#window_lab_new') {
        populate_content_addlab(winId, win_main);
    }else if (winId.substring(0, '#window_edit_own_lab'.length) == '#window_edit_own_lab') {
        populate_content_editownlab(winId, win_main);
    }else if (winId.substring(0, '#window_edit_group_lab_'.length) == '#window_edit_group_lab_') {
        edit_group_lab(winId, win_main);
    }else if (winId.substring(0, '#window_labdesign'.length) == '#window_labdesign') {
        do_windows_labdesign_display(winId, win_main);
    }else if (winId.substring(0, '#window_labmanagement'.length) == '#window_labmanagement') {
        do_windows_labmanagement_display(winId, win_main);
    }else if (winId.substring(0, '#window_openlabs'.length) == '#window_openlabs') {
        do_windows_openlabs_display(winId, win_main);
    }else if (winId.substring(0, '#window_clients'.length) == '#window_clients') {
        //populate_lab_design(winId,win_main);
        var links = $(document.createElement('div')).appendTo(win_main);
        links.append(
        "Below is a list of available tutorials: " +
        "<br><div>" +
        "<a target='_blank' href='https://www.thothlab.org/downloads/ThoTh_Lab_Studio_Tutorial.pdf'>" +
        "<img src='https://www.thothlab.org/workspace-assets/images/icons/icon_64_pdf.png'>" +
        "ThoTh Lab Studio Tutorial" +
        "</a><br><a target='_blank' href='https://www.thothlab.org/downloads/ThoTh_Lab_Operation_Flows.pdf'>" +
        "<img src='https://www.thothlab.org/workspace-assets/images/icons/icon_64_pdf.png'>" +
        "ThoTh Lab Operation Flows" +
        "</a><br><a target='_blank' href='https://www.thothlab.org/downloads/Virtual_Machine_Password_List.txt'>" +
        "<img src='https://www.thothlab.org/workspace-assets/images/icons/icon_64_txt.png'>" +
        "Virtual Machine Password List" +
        "</a>" +
        "</div>"
        );
    } else if (winId.substring(0, '#window_demos'.length) == '#window_demos') {
        //populate_lab_design(winId,win_main);
        var links = $(document.createElement('div')).appendTo(win_main);
        links.append(
            "<div>" +
                "<br><legend style='font-weight: bold;font-size: larger; text-align:center'>Single Segment IP Lab from Mastering Networks: An Iternet Lab Manual (ISBN-13: 9780201781342):</legend> <div style='text-align:center;'><br><iframe id='ytplayer1' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/kPE4Rb-VBpk?autoplay=0' frameborder='0' allowfullscreen/><br>" +
            "</div><br><br><div style='text-align:center;font-size: medium'><br><legend style='font-weight: bold'>Open Lab demo: Firewall and IPtables (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer2' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/GoKa-ZJ4gTc?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Firewall and IPtables (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/X-DuYPdgRqM?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: OpenSSL and PKI (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/mHODJ6QlEOs?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: OpenSSL and PKI (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/izuIXf2TNqU?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: DoS Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/iOUWyvXr7jE?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Heart Bleed Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/yUX7tK7HCVA?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Snort IDS on DVWA Applicaiton (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/qU8kKR-HeM4?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: IPSec Tunneling (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/DQczMs1dbZg?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Smurf Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/DflMCPLTxHQ?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Wifi De-Auth Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/Tx0hTHc7uyM?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Implementation of VPN, NAT, ACL and VLAN (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/6rCvbfjqEoE?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Network Sniffing (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/VVGyvQxdn1k?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: CPABE Toolkit (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/D7lQE70m8lg?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: TCP Reset Attack on SSH and Telnet (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/8xKJqYVSTD0?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Force HTTPS lab (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/SllpwIm_vrU?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: TCP Session Hijacking (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/DFkilHmyEiI?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: TCP Syn Flood Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/P_y9MdFTgv0?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: MITM (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/_ovTwlpZ_Sw?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: SSH and Port Security (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/_ZM6pPElUlc?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: SSL Strip Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/VFEebiFiKyQ?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Session Hijacking (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/EmUqtG31NJ8?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: XSS Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/WFeImOa0M4c?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Pen Testing in Kali (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/g5rKCZfjT70?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: SDN Firewall (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/Ryzk4wmE0Y0?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Abusing ODL (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/c_-H5IXz0Vw?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Google Dorks (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/94mrwGnevI4?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: SDN based NIDS (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/h2D-VRWlFJA?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Phishing Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/TkfqwbVC7RQ?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: BGP Path Hijacking (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/OriVKP2lcFc?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: XML External Enity Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/c8PnqY14_S8?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: XSS and SQL Injection Attack (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/RsyC96plQBk?autoplay=0' frameborder='0' allowfullscreen/>" +
            "</div></div>"
        );
    }else if (winId.substring(0, '#window_tutorial'.length) == '#window_tutorial') {
        //populate_lab_design(winId,win_main);
        var links = $(document.createElement('div')).appendTo(win_main);
        links.append(
            "<div>" +
            "<br><legend style='font-weight: bold;font-size: larger; text-align:center'>Single Segment IP Lab from Mastering Networks: An Iternet Lab Manual (ISBN-13: 9780201781342):</legend> <div style='text-align:center;'><br><iframe id='ytplayer1' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/kPE4Rb-VBpk?autoplay=0' frameborder='0' allowfullscreen/><br>" +
            "</div><br><br><div style='text-align:center;font-size: medium'><br><legend style='font-weight: bold'>Open Lab demo: Firewall and IPtables (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer2' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/GoKa-ZJ4gTc?autoplay=0' frameborder='0' allowfullscreen/>" +
            "<br><br><br><legend style='font-weight: bold'>Open Lab demo: Firewall and IPtables (Provided by Network Security Sourse Project)</legend><br><iframe id='ytplayer5' type='text/html' width='480' height='300' src='https://www.youtube.com/embed/X-DuYPdgRqM?autoplay=0' frameborder='0' allowfullscreen/>" +
            "</div></div>"
        );
    }
    else if (winId.substring(0, '#window_studio'.length) == '#window_studio') {
        get_studio_console(winId, win_main);
    //} else if (winId.substring(0, '#window_help'.length) == '#window_help') {
    //    get_help_console(winId, win_main);
    } else if (winId.substring(0, '#window_useradmin'.length) == '#window_useradmin') {
        user_admin_window(winId, win_main);
    } else if (winId.substring(0, '#window_help'.length) == '#window_help') {
        windows_helpdesk_display(winId, win_main);
    } else if (winId.substring(0, '#window_securityanalyzer'.length) == '#window_securityanalyzer') {
        security_analyzer_window(winId, win_main);
    } else if (winId.substring(0, '#window_sysadmin'.length) == '#window_sysadmin') {
        system_admin_window(winId, win_main);
    } else if (winId.substring(0, '#window_siteadmin'.length) == '#window_siteadmin') {
        site_admin_window(winId, win_main);
    } else if (winId.substring(0, '#window_sysmonitor'.length) == '#window_sysmonitor') {
        system_monitor_window(winId, win_main);
    // } else if (winId.substring(0, '#window_groupeex2'.length) == '#window_group2') {
    //     windows_group_display2(winId, win_main);
    } else if (winId.substring(0, '#window_group'.length) == '#window_group') {
        group_admin_window(winId, win_main);
    }

    $('.tabs').tabs();
}

function create_a_window_for_table_item(element) {
    var winId = element.closest('.abs.window').attr('id');
    var tableId = element.closest('table.data').attr('id');
    if (winId == 'window_projectlist') {
        //if(tableId.substring(0, 'tbl_working_projects'.length) == 'tbl_working_projects'){
        //var title = $(element).find('td').eq(1).text();
        //return create_a_window('Project: ' + title, 'project_' + title, ICON_win_project, ICON_win_project);
        //}else if(tableId.substring(0, 'tbl_lab_templates'.length) == 'tbl_lab_templates'){
        //    var tempid = $(element).find('td').eq(3).text();
        //    return create_a_window('Edit Design Template', 'temp_design' +tempid, ICON_computer_sm, ICON_computer_sm);
        //}

    } else if (winId.substring(0, 'window_project_'.length) == 'window_project_') {
        if (tableId.substring(0, 'tbl_vm_list_'.length) == 'tbl_vm_list_') {
            var projectName = winId.substring('window_project_'.length);
            var title = $(element).find('td').eq(1).text();
            var vmId = $(element).find('td').eq(2).text();
            return create_a_window('Virtual Machine: ' + title, 'vm_' + projectName + '_' + vmId, ICON_win_profile, ICON_win_profile);
        }
    } else if (winId == 'window_group') {
        if (tableId == 'go_table') {
            var title = $(element).find('td').eq(0).text();
            return create_a_window('Lab Assignment : ' + title , 'group_project_'+title, ICON_win_profile, ICON_win_profile);
        } else if (tableId.substring(0, 'tbl_lab_templates'.length) == 'tbl_lab_templates'){
            var tempid = $(element).find('td').eq(3).text();
            return create_a_window('Edit Design Template', 'temp_design' +tempid, ICON_computer_sm, ICON_computer_sm);
        } else if (tableId == 'tbl_testing_lab') {
            var title = $(element).find('td').eq(1).text();
            return create_a_window('Project: ' + title, 'project_' + title, ICON_win_project, ICON_win_project);
        }
    }else if (winId == 'window_labs') {
        if (tableId.substring(0, 'tbl_working_labs'.length) == 'tbl_working_labs') {
            var title = $(element).find('td').eq(1).text();

        }
    }else if (winId == 'window_labdesign') {
         if (tableId.substring(0, 'tbl_lab_templates'.length) == 'tbl_lab_templates'){
            var tempid = $(element).find('td').eq(3).text();
            return create_a_window('Edit Design Template', 'temp_design' +tempid, ICON_computer_sm, ICON_computer_sm);
        } else if (tableId == 'tbl_testing_lab') {
            var title = $(element).find('td').eq(1).text();
            return create_a_window('Project: ' + title, 'project_' + title, ICON_win_project, ICON_win_project);
        }
    }
}

function create_a_window_for_dropdown(title,eid,icon_win,icon_dock){
    return create_a_window(title, eid, icon_win, icon_dock);
}

function load_workspace_help(topic) {
    var win_main = $('#window_workspace-help').find('.window_main').empty();
    var help_content = $('<div></div>').css('margin', '10pt').css('display', 'inline-block').appendTo(win_main);
    switch (topic) {
        case 'my-lab': $(help_content).html('My Labs'); break;
        case 'open-labs': $(help_content).html('Open Labs'); break;
        case 'my-settings': $(help_content).html('My Settings'); break;
    }
}

function create_a_window(title, eid, icon_win, icon_dock) {
    if ($('#window_' + eid).length <= 0) {
        var win = $(document.createElement('div')).appendTo('#desktop');
        win.attr("class", "abs window").attr("id", "window_" + eid).css("width","60%").css("height","70%");
        //var html = '<div id="window_' + eid + '" class="abs window">' +
        var win_inner =
            '<div class="abs window_inner">' +
            '<div class="window_top">' +
            '<span class="float_left">' +
            '<img src= "' + icon_win + '" />' + title + '</span>' +
            '<span class="float_right">' +
            '<a href="#" class="window_min"></a>' +
            '<a href="#" class="window_resize"></a>' +
            '<a href="#icon_dock_' + eid + '" class="window_close"></a>' +
            '</span>' +
            '</div>' +
            '<div class="abs window_content">' +
                //'<div class="window_aside">' + 'Hello. You look nice today!' + '</div>' +
            '<div class="window_main">' + '<h3>No Data</h3>' +
                //'<table class="data" id="tbl_' + eid + '"><thead></thead></table>' +
            '</div>' +
            '</div>' +
            '<div class="abs window_bottom"></div>' +
            '</div>';

        $(win_inner).appendTo(win);
        $('<span class="abs ui-resizable-handle ui-resizable-se"></span>').appendTo(win);

        var dock = '<li id="icon_dock_' + eid + '">' +
            '<a href="#window_' + eid + '"><img src= "' + icon_dock + '" />' + title + '</a></li>';

        $(dock).appendTo('#dock');
    }

    load_window_content('#window_' + eid);

    return {"win_div_id": "window_" + eid, "icon_dock_id": "icon_dock_" + eid};
}

function create_tabs(winId, win_main, tabs, buttons) {

    var tab_div = $(document.createElement('div')).appendTo(win_main);
    tab_div.addClass("tabs");
    var tab_head = $(document.createElement('ul')).appendTo(tab_div);
    tab_head.addClass("tab-links");

    for (var i = 0; i < tabs.tabId.length; i++) {
        var li = $(document.createElement('li')).appendTo(tab_head);
        $('<a class="tab" href="#' + tabs.tabId[i] + '">' + tabs.tabName[i] + '</a>').appendTo(li);
        if (i == 0)
            li.addClass("active");
    }

    var tab_content = $(document.createElement('div')).appendTo(tab_div);
    tab_content.addClass("tab-content");

    for (var i = 0; i < tabs.tabId.length; i++) {
        var newTab = $(document.createElement('div')).appendTo(tab_content);
        newTab.addClass("tab").attr("id", tabs.tabId[i]);
        if (i == 0)
            newTab.addClass("active");
    }

    if (buttons != null) {
        for (var i = 0; i < buttons.buttonId.length; i++) {
            var newButton = $(document.createElement('button')).appendTo(tab_head);
            newButton.attr("id", buttons.buttonId[i]).addClass('dialog-btn')
                .css('position', 'absolute').css('right', (160*i+20)+'px').css('color', '#1C94C4')
                .css('margin-top', '4px').text(buttons.buttonName[i]);
        }
    }
}

function create_tabs_intopo(topo, tabs, buttons) {

    var tab_div = $(document.createElement('div')).appendTo(topo);
    tab_div.addClass("tabs");
    var tab_head = $(document.createElement('ul')).appendTo(tab_div);
    tab_head.addClass("tab-links");

    for (var i = 0; i < tabs.tabId.length; i++) {
        var li = $(document.createElement('li')).appendTo(tab_head);
        $('<a class="tab" href="#' + tabs.tabId[i] + '">' + tabs.tabName[i] + '</a>').appendTo(li);
        if (i == 0)
            li.addClass("active");
    }

    var tab_content = $(document.createElement('div')).appendTo(tab_div);
    tab_content.addClass("tab-content");

    for (var i = 0; i < tabs.tabId.length; i++) {
        var newTab = $(document.createElement('div')).appendTo(tab_content);
        newTab.addClass("tab").attr("id", tabs.tabId[i]);
        if (i == 0)
            newTab.addClass("active");
    }

    if (buttons != null) {
        for (var i = 0; i < buttons.buttonId.length; i++) {
            var newButton = $(document.createElement('button')).appendTo(tab_head);
            newButton.attr("id", buttons.buttonId[i]).addClass('dialog-btn')
                .css('position', 'absolute').css('right', (160*i+20)+'px').css('color', '#1C94C4')
                .css('margin-top', '4px').text(buttons.buttonName[i]);
        }
    }
}

function populate_content_addlab(winId, win_main){

}


function populate_content_labtopo(winId, win_main) {
    var project ="CSE434Dijiang";

    //var topopanel =document.createElement('div');
    ////win_main.addClass('container');
    //topopanel.className ="panel panel-default";
    //topopanel.style.cssFloat = "left";
    //topopanel.style.clear ="left";
    //topopanel.style.width = "100%"
    //$(topopanel).appendTo(win_main);
    //var topopaneltitle = $(document.createElement('div')).appendTo(topopanel);
    //topopaneltitle.addClass("panel-heading").append('<div class="panel-title" style="font-size: large;"><a data-toggle="collapse" data-target="#collapseTopo" href="#collapseTopo">Lab Topology(Click to Show)</a></div>');
    var topopanelcontant=document.createElement('div');
    //topopanelcontant.id ="collapseTopo";
    //topopanelcontant.className ="panel-collapse collapse in";
    $(topopanelcontant).appendTo(win_main);
    var tabs = {
        tabId: ['net_topology_' + project,'vm_list_' + project, 'net_list_' + project ],
        tabName: ['Network Topology','Virtual Machines', 'Network List']};
    create_tabs_intopo(topopanelcontant, tabs, null);

    prepare_network_design('#net_topology_', project);

    var table = $(document.createElement('table')).appendTo($('#vm_list_' + project));
    table.attr("class", "data").attr("id", "tbl_vm_list_" + project).append('<thead><tr>' +
    '<th class="shrink">&nbsp;</th>' + '' +
    '<th>Name</th>' +
    '<th class="hidden">UUID</th>' +
    '<th>Image</th>' +
    '<th>Size</th>' +
    '<th>IP Address</th>' +
    '<th>Status</th>' +
    '<th>Task</th>' +
    '<th>Power State</th>' +
    '<th>Uptime</th>' +
    '<th>Actions</th></tr></thead>');

    var nodes = [];
    var edges = [];
    net_topology_data[project] = {nodes: nodes, edges: edges};

    var tbody = $(document.createElement('tbody')).appendTo(table);


    $.when(populate_net_list(project)).then(function () {
        run_waitMe(win_main, 'ios');
        $.getJSON("/cloud/getServers/" + project, function (jsondata) {
            var i = 0;
            $.each(jsondata, function (index, item) {
                var address_str = '<ul>';
                $.each(item.addresses, function (net, addresses) {
                    address_str += '<li>' + net + ':';
                    net_topology_data[project].edges.push({from: item.name, to: net, length: 50});

                    $.each(addresses, function (skey, address) {
                        address_str += address.addr + ' ';
                    });
                    address_str += '</li>';
                });
                address_str += '</ul>';

                var console_param1 = 'Virtual Machine: ' + item.name;
                var console_param2 = 'vm_' + project + '_' + item.id;
                var tooltip = address_str + 'Status: ' + item.status;
                if (item.status == 'ACTIVE') {
                    tooltip += '<br /><button class="btn_tooltip" name="' + console_param1 + '" value="' + console_param2 + '">Get Console</button>';
                }

                var icon = "";
                var group = "";
                var os_type = item.image.name.split("-")[0];
                //if (item.name.substring(0, 1) == 'R') {
                switch (os_type) {
                    case 'Quagga':
                        icon = "workspace-assets/images/icons/network_router.png";
                        group = 'quagga';
                        break;
                    case 'Ubuntu':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-ubuntu.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    case 'Windows':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-windows.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    case 'Redhat':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-redhat.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    case 'CentOS':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-centos.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    case 'Fedora':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-fedora.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    case 'Debian':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-debian.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    case 'Suse':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-suse.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    case 'NetBSD':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-netBSD.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    case 'OpenBSD':
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/terminal-openBSD.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                        break;
                    default:
                        if (item.status == 'ACTIVE')
                            icon = "workspace-assets/images/icons/network_terminal.png";
                        else icon = "workspace-assets/images/icons/network_terminal-red.png";
                        group = 'vm';
                }

                net_topology_data[project].nodes.push({
                    id: item.name, label: item.name, shape: 'image',
                    title: tooltip, group: group, image: icon
                }); //"workspace-assets/images/icons/Hardware-My-Computer-3-icon.png" } );

                $('<tr><td><img src="' + ICON_computer_sm + '"></img></td>' +
                '<td>' + item.name + '</td>' +
                '<td class="hidden">' + item.id + '</td>' +
                '<td>' + item.image.name + '</td>' +
                '<td>' + item.flavor.detail + '</td>' +
                '<td>' + address_str + '</td>' +
                '<td>' + item.status + '</td>' +
                '<td>' + item.taskStatus + '</td>' +
                '<td>' + powerState[item.powerStatus] + '</td>' +
                '<td>' + getDateTimeSince(new Date(item.updated)) + '</td>' +
                '<td class="dropdown"><a class="btn btn-default vm-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                '</tr>').appendTo(tbody);

                $(winId).closest('div.window').find('div.window_bottom')
                    .text(jsondata.length + ' VMs (Double-Click the selected VM to open VM Console)');
            });
            $(win_main).waitMe('hide');
            setTimeout(function() {
                network_topology('CSE434Dijiang');
                },1500);


        });
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'vm-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="vm-console">Console</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="vm-refresh">Refresh</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="vm-edit">Edit</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="vm-reboot" style="color:red">Reboot</a></li>').appendTo(contextMenu);
    // $('<li><a tabindex="-1" href="#" class="vm-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);

    $('.collapse').on('shown.bs.collapse',function(e) {

        network_topology('CSE434Dijiang');

    });

}

function loadleaf(courseId,nameId) {
    var panel = document.getElementById('collapseLab');

    $.getJSON("/cloud/getLeaf/" + courseId + "/" + nameId, function (jsondata) {
        console.log(jsondata);
        $('#collapseLab').empty();
        var desc = document.createElement('div');
        var desc1 = $(desc).appendTo(panel);

        $(jsondata).appendTo(desc1);
    })
}

function reloadiframe(url) {
    var panel = document.getElementById('collapseLabContent').src = "https://lab.mobicloud.asu.edu/DisplayContent.php?node="+url;
    //var panel = $('#collapseLabContent').load("https://lab.mobicloud.asu.edu");
   // return;
}


function populate_content_myprojects(winId, win_main) {
    var tabs = {tabId: ['working_projects', 'lab_templates'], //'lab_design'],
        tabName: ['Working Projects', 'Lab Templates']}; // 'Lab Design']};
    //var buttons = {buttonId: ['btn_dlg_create_proj'], buttonName: ['Create Project']};
    //var buttons = {buttonId: ['btn_win_create_temp'], buttonName: ['Create Lab Template']};
    create_tabs(winId, win_main, tabs, null);

    var table = $(document.createElement('table')).appendTo($('#working_projects'));
    table.addClass("data").attr("id", "tbl_working_projects").append('<thead><tr>' +
    '<th class="shrink">&nbsp;</th>' +
    //'<th>Group</th>' +
    '<th>Name</th>' +
    '<th>Description</th>' +
    '<th class="hidden">UUID</th>' +
    '<th>Members</th>' +
    '<th>Actions</th>' +
    //'<th>Notes</th>' +
    '</tr></thead>');
    var tbody = $(document.createElement('tbody')).appendTo(table);

    run_waitMe(win_main, 'ios');
    $.getJSON("/cloud/getProjects", function (jsondata) {
        $.each(jsondata, function (index, item) {
            $('<tr>' +
            '<td><img src="' + ICON_project + '"></img></td>' +
            //'<td>' + group + '</td>' +
            '<td>' + item.name + '</td>' +
            '<td>' + item.description + '</td>' +
            '<td class="hidden">' + item.id + '</td>' +
            '<td><button type="button" class="proj_member"><i class="fa fa-users"></i></button></td>' +  //"<td>" + item.users + "</td>" +
            '<td class="dropdown"><a class="btn btn-default proj-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
            //'<td>&mdash;</td>' +
            '</tr>').appendTo(tbody);
        });

        $(winId).find('div.window_bottom')
            .text(jsondata.length + ' Projects (Double-Click the selected project to open the project content)');
        $(win_main).waitMe('hide');
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'proj-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="proj-edit">Edit</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="proj-addMember">Add Member</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="proj-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);

    //prepare_lab_design('#lab_design');
    //setTimeout(function() {
    //    lab_design('');
    //},3500);
    //is_visit_lab_design = false;

    populate_lab_template(winId);
}

function populate_content_labdesign(winId, win_main) {
    var tabs = {tabId: ['lab_templates'], //'lab_design'],
        tabName: ['Lab Templates']}; // 'Lab Design']};
    //var buttons = {buttonId: ['btn_dlg_create_proj'], buttonName: ['Create Project']};
    //var buttons = {buttonId: ['btn_win_create_temp'], buttonName: ['Create Lab Template']};
    create_tabs(winId, win_main, tabs, null);

    populate_lab_template(winId);
}

function populate_content_mylabs(winId, win_main) {
    var tabs = {
        tabId: ['enrolledlabs'/*, 'lab_enroll'*/], //'lab_design'],
        tabName: ['Working Labs'/*, 'Lab Enrollment'*/]
    }; // 'Lab Design']};
    //var buttons = {buttonId: ['btn_dlg_create_proj'], buttonName: ['Create Project']};
    //var buttons = {buttonId: ['btn_win_create_temp'], buttonName: ['Create Lab Template']};
    create_tabs(winId, win_main, tabs, null);

    var table1 = $(document.createElement('table')).appendTo($('#enrolledlabs'));
    table1.addClass("data").attr("id", "tbl_working_labs").append('<thead><tr>' +
    '<th class="shrink">&nbsp;</th>' +
        //'<th>Group</th>' +
    '<th>Group</th>' +
    '<th>Team</th>' +
        //'<th class="hidden">UUID</th>' +
    '<th>Lab Name</th>' +
    '<th>Deployed at</th>' +
    '<th>Due at</th>' +
    '<th>Actions</th>' +
        //'<th>Notes</th>' +
    '</tr></thead>');
    var tbody1 = $(document.createElement('tbody')).appendTo(table1);

    run_waitMe(win_main, 'ios');

    $.getJSON("labs/getWorkingLabList", function (jsondata) {
        //var roles = $("#roleprofile");
        $.each(jsondata, function (index, item) {
            if(item.group === "No Working Lab"){
                //item.deployat = (new Date(item.deploy_at.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,"");
                $("<tr><td><img src=" + ICON_project + "></img></td>" +
                    "<td>" + item.group + "</td>" +
                    "<td>" + item.team + "</td>" +
                    "<td>" + item.lab + "</td>" +
                    "<td></td>" +
                    "<td hidden>" + item.project + "</td>" +
                    "<td></td>" +
                    "<td></td></tr>").appendTo(tbody1);
            } else {
                item.deployat = (new Date(item.deploy_at.slice(0,19).replace(' ','T') + ".000Z")).toString().replace(/ GMT.*/g,"");
                $("<tr><td><img src=" + ICON_project + "></img></td>" +
                    "<td>" + item.group + "</td>" +
                    "<td>" + item.team + "</td>" +
                    "<td>" + item.lab + "</td>" +
                    "<td>" + item.deploy_at + "</td>" +
                    "<td hidden>" + item.project + "</td>" +
                    "<td hidden>" + item.openlab + "</td>" +
                    "<td hidden>" + item.term  + "</td>" +
                    "<td>" + item.due_at + "</td>" +
                    "<td class='dropdown'><a class='btn btn-default workinglab-actionButton' data-toggle='dropdown' href='#'>More <i class='fa fa-sort-down'></i></a></td>" +
                    "</tr>").appendTo(tbody1);
            }
            $(win_main).waitMe('hide');
        });
        //$("#project_list").trigger("change");
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody1);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'workinglab-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="workinglab-content">Lab Content</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="workinglab_topo">Lab Environment</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="proj-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);

    //prepare_lab_design('#lab_design');
    //setTimeout(function() {
    //    lab_design('');
    //},3500);
    //is_visit_lab_design = false;
    //populate_lab_template(winId);
}

//var is_visit_lab_design = false;

function project_list_update(action, tenant) {
    //var tbody = $('#working_projects').closest('div.tabs').find('#tbl_' + tabId).find('tbody');
    var tbody = $('#working_projects').closest('tbody');
    switch (action) {
        case "new":
            $('<tr class="active">' +
            '<td><img src="' + ICON_project + '"></img></td>' +
            '<td>' + tenant.name + '</td>' +
            '<td>' + tenant.description + '</td>' +
            '<td class="hidden">' + tenant.id + '</td>' +
            '<td><button type="button" class="proj_member"><i class="fa fa-users"></i></button></td>' +  //"<td>" + item.users + "</td>" +
            '<td class="dropdown"><a class="btn btn-default proj-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                //'<td>&mdash;</td>' +
            '</tr>').prependTo(tbody);
            //'</tr>').appendTo(tbody);
            //tbody.closest('div.window_content')
            //    .animate({scrollTop: tbody.closest('div.window_content').get(0).scrollHeight}, 2000);
            break;
        case "update":
            $('tr.active').children().eq(1).html(tenant.name);
            $('tr.active').children().eq(2).html(tenant.description);
            break;
        case "delete":

    }
}

function project_action(element, action) {

    var project_name = element.closest('tr').children().eq(1).html();
    var project_desc = element.closest('tr').children().eq(2).html();
    var project_id = element.closest('tr').children().eq(3).html();

    if (action == 'update') {
        var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
        dlg_form.addClass('dialog').attr('id', 'dlg_update_project').attr('title', 'Edit Project');

        var form_html = '<form method="post" class="contact_form" id="form_edit_project" style="width:400px">' +
            '<ul><li><h2>Edit Project</h2>' +
            '<span class="required_notification">* Denotes Required Field</span></li>' +
            '<li><label for="project_name">Project Name:</label>' +
            '<input type="text" name="project_name" id="project_name" value="' + project_name + '" required="true" /></il>' +
            '<li><label for="project_desc">Project Description:</label>' +
            '<textarea name="project_desc" id="project_desc" cols="40" rows="6">' + project_desc +
            '</textarea></li>' +
            //'<li><button type="button" class="submit" id="submit_update_project">Submit Request</button></li>' +
            '</ul></form>' +
            '<div id="result_update_project"></div>';
        $(form_html).appendTo(dlg_form);
        $('#dlg_update_project').dialog({
            modal: true,
            width: 600,
            buttons: {
                "Update": function () {
                    $(this).dialog('close');
                    var proj_name = dlg_form.find('#project_name').val();
                    var proj_desc = dlg_form.find('#project_desc').val();
                    proj_desc = (!proj_desc) ? " " : proj_desc;
                    if (!proj_name.trim() || proj_name.indexOf(' ') >= 0) {
                        alert('Please enter the project name with a non-space string!');
                    }
                    else {
                        $.post("/cloud/updateProject", {
                                //$(this).prop('action'), {
                                //"_token": $(this).find('input[name=_token]').val(),
                                "project_id": project_id,
                                "project_name": proj_name,
                                "project_desc": proj_desc
                            },
                            function (data) {
                                if (data.status == "Success") {
                                    alert(data.msg);
                                    project_list_update('update', data.tenant);
                                }
                                else {
                                    alert(data.msg);
                                    //form[0].reset();
                                }
                            },
                            'json'
                        )
                            //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
                            .fail(function (xhr, testStatus, errorThrown) {
                                alert(xhr.responseText);
                            });
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
    } else if (action == 'delete') {
        var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
        dlg_form.addClass('dialog').attr('id', 'dlg_delete_project').attr('title', 'Delete Project');

        var form_html = '<span>Are you sure to delete the project?</span>';
        $(form_html).appendTo(dlg_form);
        $('#dlg_delete_project').dialog({
            modal: true,
            width: 400,
            buttons: {
                "Yes": function () {
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
    } else if (action == 'addMember') {

    }
}

//function populate_content_myvms(winId, win_main) {
//    var project = "CSE434Dijiang";
//
//
//        var tabs = {
//            tabId: ['net_topology_' + project, 'vm_list_' + project, 'net_list_' + project],
//            tabName: ['Network Topology', 'Virtual Machines', 'Network List' ]
//        };
//    //'Create Network', 'Create VM']};
//    //var buttons = {
//    //    buttonId: ['btn_dlg_create_net_' + project, 'btn_dlg_create_vm_' + project],
//    //    buttonName: ['Create Network', 'Create VM']
//    //};
//
//    create_tabs(winId, win_main, tabs, null);
//    $('#btn_dlg_create_net_' + project).css('right', '100px');
//
//    var table = $(document.createElement('table')).appendTo($('#vm_list_' + project));
//    table.attr("class", "data").attr("id", "tbl_vm_list_" + project).append('<thead><tr>' +
//    '<th class="shrink">&nbsp;</th>' + '' +
//    '<th>Name</th>' +
//    '<th class="hidden">UUID</th>' +
//    '<th>Image</th>' +
//    '<th>Size</th>' +
//    '<th>IP Address</th>' +
//    '<th>Status</th>' +
//    '<th>Task</th>' +
//    '<th>Power State</th>' +
//    '<th>Uptime</th>' +
//    '<th>Actions</th></tr></thead>');
//
//    var nodes = new vis.DataSet;
//    var edges = new vis.DataSet;
//    net_topology_data[project] = {nodes: nodes, edges: edges};
//
//    var tbody = $(document.createElement('tbody')).appendTo(table);
//
//    $.when(populate_net_list(project)).then(function () {
//        run_waitMe(win_main, 'ios');
//        $.getJSON("/cloud/getServers/" + project, function (jsondata) {
//            var i = 0;
//            $.each(jsondata, function (index, item) {
//                var address_str = '<ul>';
//                $.each(item.addresses, function (net, addresses) {
//                    address_str += '<li>' + net + ':';
//                    net_topology_data[project].edges.add({from: item.name, to: net, length: 50});
//
//                    $.each(addresses, function (skey, address) {
//                        address_str += address.addr + ' ';
//                    });
//                    address_str += '</li>';
//                });
//                address_str += '</ul>';
//
//                var vm_value = 'vm_' + project + '_' + item.id;
//                var tooltip = '<div id="canvas-tooltip_' + vm_value + '">';
//                tooltip += '<div class="canvas-tooltip-networking">Networks:' + address_str + '</div>';
//                tooltip += '<div class="canvas-tooltip-status">' + create_tooltip_vmstatus_buttons(item, vm_value)
//                        + '</div></div>';
//
//                var vm_icon = get_vm_icon(item);
//                var group = vm_icon.group;
//                var icon = vm_icon.icon;
//
//                net_topology_data[project].nodes.add({
//                    id: item.name, label: item.name, shape: 'image',
//                    title: tooltip, group: group, image: icon
//                }); //"workspace-assets/images/icons/Hardware-My-Computer-3-icon.png" } );
//
//                $('<tr><td><img src="' + ICON_computer_sm + '"></img></td>' +
//                '<td>' + item.name + '</td>' +
//                '<td class="hidden">' + item.id + '</td>' +
//                '<td>' + item.image.name + '</td>' +
//                '<td>' + item.flavor.detail + '</td>' +
//                '<td>' + address_str + '</td>' +
//                '<td>' + item.status + '</td>' +
//                '<td>' + item.taskStatus + '</td>' +
//                '<td>' + PowerStatusEnum.properties[item.powerStatus].name + '</td>' +
//                '<td>' + getDateTimeSince(new Date(item.updated)) + '</td>' +
//                '<td class="dropdown"><a class="btn btn-default vm-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
//                '</tr>').appendTo(tbody);
//
//                $(winId).closest('div.window').find('div.window_bottom')
//                    .text(jsondata.length + ' VMs (Double-Click the selected VM to open VM Console)');
//            });
//            $(win_main).waitMe('hide');
//            setTimeout(function() {
//                network_topology('CSE434Dijiang');
//            },1500);
//        });
//    });
//
//    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
//    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'vm-contextMenu')
//        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
//    $('<li><a tabindex="-1" href="#" class="vm-console">Console</a></li>').appendTo(contextMenu);
//    $('<li><a tabindex="-1" href="#" class="vm-refresh">Refresh</a></li>').appendTo(contextMenu);
//    //$('<li><a tabindex="-1" href="#" class="vm-edit">Edit</a></li>').appendTo(contextMenu);
//    $('<li><a tabindex="-1" href="#" class="vm-reboot" style="color:red">Reboot</a></li>').appendTo(contextMenu);
//    //$('<li><a tabindex="-1" href="#" class="vm-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);
//
//    prepare_network_topology('#net_topology_', project);
//}

function populate_content_vms(winId, win_main) {
    var project = winId.substring('#window_project_'.length);


    var tabs = {
        tabId: ['net_topology_' + project, 'vm_list_' + project, 'net_list_' + project],
        tabName: ['Network Topology', 'Virtual Machines', 'Network List' ]
    };
    //'Create Network', 'Create VM']};
    //var buttons = {
    //    buttonId: ['btn_dlg_create_net_' + project, 'btn_dlg_create_vm_' + project],
    //    buttonName: ['Create Network', 'Create VM']
    //};

    create_tabs(winId, win_main, tabs, null);
    $('#btn_dlg_create_net_' + project).css('right', '100px');

    var table = $(document.createElement('table')).appendTo($('#vm_list_' + project));
    table.attr("class", "data").attr("id", "tbl_vm_list_" + project).append('<thead><tr>' +
    '<th class="shrink">&nbsp;</th>' + '' +
    '<th>Name</th>' +
    '<th class="hidden">UUID</th>' +
    '<th>Image</th>' +
    '<th>Size</th>' +
    '<th>IP Address</th>' +
    '<th>Status</th>' +
    '<th>Task</th>' +
    '<th>Power State</th>' +
    '<th>Uptime</th>' +
    '<th>Actions</th></tr></thead>');

    var nodes = new vis.DataSet;
    var edges = new vis.DataSet;
    net_topology_data[project] = {nodes: nodes, edges: edges};

    var tbody = $(document.createElement('tbody')).appendTo(table);

    $.when(populate_net_list(project)).then(function () {
        //run_waitMe(win_main, 'ios');
        $.getJSON("/cloud/getServers/" + project, function (jsondata) {
            var i = 0;
            $.each(jsondata, function (index, item) {
                var address_str = '<ul>';
                $.each(item.addresses, function (net, addresses) {
                    address_str += '<li>' + net + ':';
                    net_topology_data[project].edges.add({from: item.name, to: net, length: 50});

                    $.each(addresses, function (skey, address) {
                        address_str += address.addr + ' ';
                    });
                    address_str += '</li>';
                });
                address_str += '</ul>';

                var vm_value = 'vm_' + project + '_' + item.id;
                var tooltip = '<div id="canvas-tooltip_' + vm_value + '">';
                tooltip += '<div class="canvas-tooltip-networking">Networks:' + address_str + '</div>';
                tooltip += '<div class="canvas-tooltip-status">' + create_tooltip_vmstatus_buttons(item, vm_value)
                + '</div></div>';

                var vm_icon = get_vm_icon(item);
                var group = vm_icon.group;
                var icon = vm_icon.icon;

                net_topology_data[project].nodes.add({
                    id: item.name, label: item.name, shape: 'image',
                    title: tooltip, group: group, image: icon
                }); //"workspace-assets/images/icons/Hardware-My-Computer-3-icon.png" } );

                $('<tr><td><img src="' + ICON_computer_sm + '"></img></td>' +
                '<td>' + item.name + '</td>' +
                '<td class="hidden">' + item.id + '</td>' +
                '<td>' + item.image.name + '</td>' +
                '<td>' + item.flavor.detail + '</td>' +
                '<td>' + address_str + '</td>' +
                '<td>' + item.status + '</td>' +
                '<td>' + item.taskState + '</td>' +
                '<td>' + PowerStatusEnum.properties[item.powerState].name + '</td>' +
                '<td>' + getDateTimeSince(new Date(item.updated)) + '</td>' +
                '<td class="dropdown"><a class="btn btn-default vm-actionButton" data-toggle="dropdown" href="#">More <i class="fa fa-sort-down"></i></a></td>' +
                '</tr>').appendTo(tbody);

                $(winId).closest('div.window').find('div.window_bottom')
                    .text(jsondata.length + ' VMs (Double-Click the selected VM to open VM Console)');
            });
            $(win_main).waitMe('hide');
            setTimeout(function() {
                network_topology(project);
            },1500);
        });
    });

    var contextMenu = $(document.createElement('ul')).appendTo(tbody);
    contextMenu.addClass('dropdown-menu').attr('role', 'menu').attr('id', 'vm-contextMenu')
        .css('top', '80%').css('font-size', '12px').css('left', '0px').css('left', 'auto').css('min-width', '100px');
    $('<li><a tabindex="-1" href="#" class="vm-console">Console</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="vm-refresh">Refresh</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="vm-edit">Edit</a></li>').appendTo(contextMenu);
    $('<li><a tabindex="-1" href="#" class="vm-reboot" style="color:red">Reboot</a></li>').appendTo(contextMenu);
    //$('<li><a tabindex="-1" href="#" class="vm-delete" style="color:red">Delete</a></li>').appendTo(contextMenu);

    prepare_network_topology('#net_topology_', project);
}

function prepare_network_topology(tab_div, project) {
    var popup_div = $(document.createElement('div')).appendTo($(tab_div + project));
    popup_div.addClass('addNode-popUp').attr('id', 'network_topo_popUp_' + project)
        .css('top', '90px').css('left', '20px').css('width', '400px').css('height', '520px')
        .css('text-align', 'left'); //.css('display','none');
    //$('<span id="network_topo_operation_' + project + '" style="font-size:20px;">node</span> <br>' +
    $('<div id="network_topo_config_' + project + '"></div>')
        .css('float', 'left').css('height', '500px').appendTo(popup_div);
        //.css('float', 'left').css('width', '400px').css('height', '600px').appendTo(popup_div);

    //$('<span id="operation_' + project + '" style="font-size:20px;">node</span> <br>' +
    //'<table style="margin:auto;">' +
    //'<tr><td>id</td><td><input id="node-id_' + project + '" value="new value"></td></tr>' +
    //'<tr><td>Type</td><td><select id="node-type_' + project + '">' + '<option value="0">Select a Resource...</option>' +
    //'<option value="vm">VM</option><option value="switch">Switch</option><option value="router">Router</option></select><br />' +
    //'<select style="display:none;" id="node-type-vm-image_' + project + '"><option value="0">Select a image...</option></select>' +
    //'<select style="display:none;" id="node-type-vm-flavor_' + project + '"><option value="0">Select a flavor...</option></select>' +
    //'<select style="display:none;" id="node-type-router_' + project + '"><option value="0">Select a router type...</option>' +
    //'<option value="quagga">Quagga</option><option value="vrouter">Virtual Router</option></select>' +
    //'</td></tr>' +
    //'<tr><td>label</td><td><input id="node-label_' + project + '" value="new value"> </td></tr></table>' +
    //'<input type="button" value="Save" id="saveButton_' + project + '"></button>' +
    //'<input type="button" value="Cancel" id="cancelButton_' + project + '"></button></div>').appendTo(popup_div);

    var table = $(document.createElement('table')).appendTo($(tab_div + project));
    table.attr("class", "data").css('width', '100%').append('<thead><tr><th></th></tr></thead>');
    var table_head = table.find('th');

    var btn_toggle_config = $(document.createElement('button')).appendTo(table_head);
    btn_toggle_config.addClass('toggle-button').attr('id', 'btn_toggle_options_' + project)
        .attr('onclick', 'toggle_net_topology_display_options("' + project + '")')
        .css('float', 'left').attr('title', 'open option panel')
        .html('<i class="fa fa-cog"></i> Options <span class="fa fa-caret-down"></span>');

    $('<p id="selection_' + project + '">Selection: None</p>').css('float', 'right').appendTo(table_head);
    //var newbtn1 = $(document.createElement('button')).appendTo(table_head);
    //newbtn1.addClass("dialog-btn").attr('id', 'btn_verify_design_' + project).css('float', 'right').text('Verify Design');
    //var newbtn2 = $(document.createElement('button')).appendTo(table_head);
    //newbtn2.addClass("dialog-btn").attr('id', 'btn_save_design_' + project).css('float', 'right').text('Save Design');

    var mynetwork_div = $(document.createElement('div')).appendTo($(tab_div + project));
    mynetwork_div.attr('id', 'net_topo_div_' + project).css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');

}

function toggle_net_topology_display_options(project) {
    $('#network_topo_popUp_' + project).slideToggle();
    var btn = $('#btn_toggle_options_' + project);
    var span_icon = btn.find('span');
    if (span_icon.hasClass('fa-caret-down')) {
        span_icon.toggleClass('fa-caret-up');
        btn.attr('title', 'close option panel');
    } else {
        span_icon.toggleClass('fa-caret-down');
        btn.attr('title', 'open option panel');
    }
    return true;
}

function populate_net_list(project_name) {
    var table_net = $(document.createElement('table')).appendTo($('#net_list_' + project_name));
    table_net.attr("class", "data").attr("id", "tbl_net_list_" + project_name)
        .append('<thead><tr>' +
        '<th class="shrink">&nbsp;</th>' + '' +
        '<th>Name</th>' +
        '<th class="hidden">UUID</th>' +
        '<th>Subnets</th>' +
        '<th>Ports</th>' +
        '<th>Status</th>' +
        '<th>Actions</th></tr></thead>');

    var tbody_net = $(document.createElement('tbody')).appendTo(table_net);
    // $.getJSON("/cloud/getNetworks/" + project_name, function (jsondata) {
    //     $.each(jsondata, function (index, item) {
    //         var subnet_str = '';
    //         $.each(item.subnets, function (idx, val) {
    //             subnet_str += val.name + ':' + val.cidr + '<br>';
    //         });
    //         net_topology_data[project_name].nodes.add({ id: item.name, label: item.name, shape: 'image',
    //             title: subnet_str, group: 'switch',
    //             image: "workspace-assets/images/icons/network_switch.png" });
    //
    //         var ports_str = '';
    //         $.each(item.ports, function (idx, val) {
    //             var ip_str = '';
    //             $.each(val.fixedIps, function (i, v) {
    //                 ip_str += v.ip_address;
    //             });
    //             ports_str += val.deviceOwner  + ':' + ip_str + '<br />';
    //             if (val.deviceOwner == 'network:router_interface_distributed' || val.deviceOwner == 'network:router_interface') {
    //                 net_topology_data[project_name].nodes.add({ id: 'ext-router', label: 'External Router', shape: 'image',
    //                     title: ip_str, group: 'vrouter',
    //                     image: "workspace-assets/images/icons/network_router-firewall.png" });
    //                 net_topology_data[project_name].nodes.add({ id: 'Internet', label: 'Internet', shape: 'image',
    //                     title: 'Internet', group: 'internet',
    //                     image: "workspace-assets/images/icons/System-Globe-icon.png" });
    //                 net_topology_data[project_name].edges.add({from: 'ext-router', to: 'Internet', length: 50 });
    //                 net_topology_data[project_name].edges.add({from: 'ext-router', to: item.name, length: 50 });
    //             }
    //         });
    //         //ports_str += '</ul>';
    //         //alert(ports_str);
    //         $('<tr><td><img src="' + ICON_network_switch + '"></img></td>' +
    //         '<td>' + item.name + '</td>' +
    //         '<td class="hidden">' + item.id + '</td>' +
    //         '<td>' + subnet_str + '</td>' +
    //         '<td>' + ports_str + '</td>' +
    //         '<td>' + item.status + '</td>' +
    //         '<td>' + '&mdash;' + '</td>').appendTo(tbody_net);
    //
    //     });
    // });
}

function workspace_window_resize(element) {
    if (element.attr('id').substring(0, 'window_temp_design'.length) == 'window_temp_design') {
        //lab_design('');
        var project = element.attr('id').substring('window_temp_design'.length);
        design_network[project].redraw();
    }
    else if (element.attr('id').substring(0, 'window_project_'.length) == 'window_project_') {
        var project = element.attr('id').substring('window_project_'.length);
        if (element.find('.tabs').tabs('option', 'active') == 2) {
            //network_topology(project);
            //var height = parseInt($('#net_topo_div_' + project).closest('div.window_inner').css('height'));
            //net_topology[project].setSize("100%", height - 163 + "px");
            net_topology[project].redraw();
        }
    }
    else if (element.attr('id').substring(0, 'window_conceptmap'.length) == 'window_conceptmap') {
        //var project = element.attr('id').substring('window_project_'.length);
        if (element.find('.tabs').tabs('option', 'active') == 1) {
            load_concept_map_search();
        }
    }
    else if (element.attr('id').substring(0, 'window_open_lab_'.length) == 'window_open_lab_') {
        var winheight = element.children().height();
        var project =element.attr('id').substring('window_open_lab_'.length);
        element.find($('.window_contant')).css('overflow','hidden');
        element.find($('#left_pane_'+project)).css('height',winheight-40);
        element.find($('.vsplitter')).css('height',winheight-40);
        element.find($('#right_pane_'+project)).css('height',winheight-40);

        element.find($('#left_pane_'+project)).css('width',$('#myContainer_'+project).find('.jstree-anchor').width()+50);
        element.find($('#right_pane_'+project)).css('width',$('#myContainer_'+project).width()-$('#left_pane_'+project).width()-10);
        element.find($('.vsplitter')).css('left',$('#myContainer_'+project).find('.jstree-anchor').width()+50);
    } else if (element.attr('id').substring(0, 'window_admin'.length) == 'window_admin') {
        var winheight = element.children().height();
        var winwidth = element.children().width();
        element.find($('#admin_system_monitor')).css('height',4000000/winwidth);
    } else if (element.attr('id').substring(0, 'window_videowebrtc'.length) == 'window_videowebrtc') {
        var winheight = element.children().height();
        var win1width = $('#one2one_text_diaply').width();
        $('#peerone').css('height', winheight - 130);
        $('#one2one_text_diaply').css('height', winheight - 100);
        $('#one2one_text_input').css('width', win1width - 50);
    }
}

function network_topology(project) {

    //net_topology[project] = null;

    var container = document.getElementById('net_topo_div_' + project);
    var canvas_height = parseInt($('#net_topo_div_' + project).closest('div.window_inner').css('height'));
    container.style.height = canvas_height - 163 + 'px';
    container.style.minHeight = '550px';
    var configure_container = document.getElementById('network_topo_config_' + project);
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
    net_topology[project] = new vis.Network(container, net_topology_data[project], options);

    // add event listeners
    net_topology[project].on('select', function (params) {
        document.getElementById('selection_' + project).innerHTML = 'Selection: ' + params.nodes;
    });

    net_topology[project].on("resize", function (params) {
        console.log(params.width, params.height);
    });
}

function vm_list_update(element, project, vmId) {
    if (element) {
        var tr = element.closest('tr');
        var v_status="";
        $.getJSON("/cloud/getVM/" + project + "/" + vmId, function (data) {
            //alert(JSON.stringify(data));
            v_status = data.vm.taskStatus;
            tr.children().eq(6).text(data.vm.status);
            (v_status === null) ? tr.children().eq(7).text(data.vm.taskStatus)
                : tr.children().eq(7).empty().append('<img src= "img/waiting.gif" width="50" height="20" />');
            tr.children().eq(8).text(PowerStatusEnum.properties[data.vm.powerStatus].name);
            tr.children().eq(9).text(getDateTimeSince(new Date(data.vm.updated)));
            if (v_status === null) return true;
            else {
                setTimeout(function () {
                    vm_list_update(element, project, vmId);
                }, 500);
            }
        });
    }
}

function network_list(tbody, project_name) {
    $.getJSON("/cloud/getNetworks/" + project_name, function (jsondata) {
        $.each(jsondata, function (index, item) {
            var subnet_str = '';
            $.each(item.subnet, function (idx, val) {
                subnet_str += val.name + ':' + val.cidr + '<br>';
            });
            $('<tr><td><img src="' + ICON_computer_sm + '"></img></td>' +
            '<td>' + item.name + '</td>' +
            '<td class="hidden">' + item.id + '</td>' +
            '<td>' + subnet_str + '</td>' +
            '<td>' + item.status + '</td>' +
            '<td>' + '&mdash;' + '</td>').appendTo(tbody);
        });
    });
}

function tab_click(element) {
    var win_bottom = element.closest('div.window').find('div.window_bottom');
    var project_name = element.closest('div.window').attr("id").substring('window_project_'.length);
    var groupname = element.closest('div.window').attr("id").substring('window_group.project_'.length);
    var tabId = element.attr('href').substring('#'.length);
    switch (tabId) {
        case 'lab_repo':
            lab_design_management();
            break;
        case 'lab_templates':
            populate_lab_template('0');
            break;
        case 'lab_deploy_list_all': // + groupname:
            getLabDeployListTable('all');
            break;
        case 'team_list_all': // + groupname:
            getTeamListTable('all');
            break;
        case 'concept_map_create':
            //load_concept_map_create();
            break;
        case 'concept_map_search':
            //load_concept_map_search();
            break;
        case 'working_projects':
            var proj_table = element.closest('div.window').find('#tbl_working_projects').find('tbody');
            win_bottom.text(proj_table.children().length-1 + ' Projects (Double-Click the selected project to open the project content)');
            break;
        //case 'lab_templates':
        //    populate_lab_template();
        //    break;
        case 'create_proj_' + project_name:
            break;
        case 'vm_list_' + project_name:
            var vm_table = element.closest('div.tabs').find('#tbl_' + tabId).find('tbody');
            win_bottom.text(vm_table.children().length-1 + ' VMs (Double-Click the selected VM to open VM Console)');
            break;
        case 'net_list_' + project_name:
            var net_table = element.closest('div.tabs').find('#tbl_' + tabId).find('tbody');
            win_bottom.text(net_table.children().length + ' nets and subnets');
            break;
        case 'create_vm_' + project_name:
            break;
        case 'net_topology_' + project_name:
            win_bottom.text('Using mouse wheel to zoom in and zoom out.');
            //toggole_net_topo_dispay_config(project_name, true);
            setTimeout(function() {
                network_topology(project_name);
            },500);
            break;
        //case 'net_topology_CSE434Dijiang':
        //    win_bottom.text('Using mouse wheel to zoom in and zoom out.');
        //    setTimeout(function() {
        //        network_topology('CSE434Dijiang');
        //    },500);
        //    break;
        case 'net_editing':
            setTimeout(function() {
                draw_editing();
            },500);
            break;
        //case 'lab_design':
        //    if (!is_visit_lab_design) {
        //        setTimeout(function () {
        //            lab_design('');
        //        }, 500);
        //        is_visit_lab_design = true;
        //    }
        //    break;
        case 'profile_view':
            display_tab();
            break;
        case 'owncloud_myfiles_merge_profile':
            display_owncloud_tab();
            break;
        case 'group_display_from_profile':
            from_profile_display_tab();
            break;
        case 'userprofile_update':
            display_user_profile_tab();
            break;
        case 'enroll_group':
            display_group_enroll();
            //$("#search_group_btn").click();
            break;
        case 'invite_group':
            display_group_invite();
            break;
        case 'pending_enroll':
            display_pending_enroll();
            break;
        case 'pending_invite':
            display_pending_invite();
            break;
        case 'group_member':
            display_group_member_table();
            break;
        case 'one_one':
            display_rtc_one2one();
            break;
        case 'previlige_update':
            display_privilige_table();
            break;
        case 'site_manage':
            //display_site_table();
            break;
        case 'lab_enroll':
            display_group_member_table();
            break;
        case 'group_owner':
            display_group_owner_table();
            break;
        case 'lab_management_2':
            display_group_owner_table2();
            break;
        case 'class_approval':
            display_pending_class_request();
            break;
        case 'activity_log':
            display_activity_log();
            break;
        case 'sys_project_manager':
            system_project_manager();
            break;
        case 'sys_role_manager':
            sys_admin_role_manager();
            break;
        case 'sys_permission_manager':
            sys_admin_permission_manager();
            break;
        case 'sys_site_manager':
            sys_admin_site_manager();
            break;
        case 'group_admin_my_groups':
            group_admin_my_groups();
            break;
        case 'group_admin_join_groups':
            group_admin_join_groups();
            break;
        // case 'user_admin_user_manager':
        //     user_admin_user_manager();
        //     break;
        default:
            win_bottom.text('');
    }
}

function button_click(element) {
    var eid = element.attr("id");
    //


    //if (eid.substring(0, 'dlg_btn_'.length) == 'dlg_btn_') {
    //    $('#'+ eid).dialog({modal: true, height: 300, width: 400});
    //}
    switch (eid) {
        case 'btn_approve_class_comments':
            btn_approve_class_comments(element);
            break;
        case 'btn_reject_class_comments':
            btn_reject_class_comments(element);
            break;
        case 'rename_subgroup_button':
            rename_subgroup_button(element);
            break;
        case 'deploy_template_button':
            deploy_template_button(element);
            break;
        case 'update_subgroup_member_button':
            update_subgroup_member_button(element);
            break;
        case 'share_button':
            share_button(element);
            break;
        case 'post_update_permission':
            post_update_permission(element);
            break;
        case 'copy_button':
            copy_button(element);
            break;
        //case 'submit_create_subgroup':
        //    submit_create_subgroup(element);
        //    break;
        case 'btn_mindmap_search':
            load_concept_map_search();
            break;
        case 'btn_win_create_temp':
            alert("haha");
            break;
        case 'save_design_to_DB':
            //var projecthtml= element.prev().prev().html();
            //var project=projecthtml.substring(10);
            //var lab_template = { "AWSTemplateFormatVersion": "2010-09-09", "Resources": lab_resources['new'] };
            lab_design_json= {nodes :lab_design_data['new'].nodes.get(),edges: lab_design_data['new'].edges.get()};
            var lab_design_vmcount =vmcount['new'];
            if($("input[name=temp_name]").val()=='' || $("textarea[id=save_design_des_text]").val() =='') {
                alert('Please Fill all the Fields First!');
                break;
            }else {


                $.post("/cloud/saveTemplate", {
                        "temp_name": $("input[name=temp_name]").val(),
                        "temp": JSON.stringify(lab_resources['new']),
                        "temp_des": $("textarea[id=save_design_des_text]").val(),
                        "temp_design": JSON.stringify(lab_design_json),
                        "temp_vmcount": lab_design_vmcount
                    },
                    function (data) {
                        //template_list_update('lab_templates', 'new');
                        //alert(data);
                        if (data != null) {
                            // alert('Not Found');
                        }
                    },
                    'json'
                ).fail(function (xhr, testStatus, errorThrown) {
                        //alert(xhr.responseText);
                    });
                element.closest('.dialog').dialog('close');
                setTimeout(function () {
                    template_list_update("#tbl_lab_templates", "new", 0);
                }, 500);

                $('#window_temp_designnew').remove();
                $('#icon_dock_temp_designnew').remove();
                break;
            }
        case 'submit_upload_avatar_file':
            submit_upload_avatar_file();
            break;
        //case 'btn_create_team':
        //    create_subgroup(element);
        //    break;
        //case 'submit_upload_avatar_file':
        //    submit_upload_avatar_file();
        //    break;
        case 'submit_upload_avatar':
            submit_upload_avatar2();
            break;
        case 'assign_template_button':
            assign_template_button(element);
            break;
        case 'assign_template':
            assign_template(element);
            break;
        case 'new_assign_template_button':
            new_assign_template_button(element);
            break;
        case 'new_assign_template':
            new_assign_template(element);
            break;
        case 'sub_group_deploy':
            sub_group_deploy();
            break;
        //case 'create_sub_group_for_each_member':
        //    create_sub_group_for_each_member(element);
        //    break;
        case 'update_subgroup_name_button':
            update_subgroup_name_button_old(element);
            break;
        case 'gm_submit_enroll_group':
            gm_submit_enroll_group();
            break;
        case 'go_create_group':
            go_create_group();
            break;
        case 'gm_submit_group_based_enroll':
            gm_submit_group_based_enroll();
            break;
        case 'gm_submit_enroll_group_search':
            gm_submit_enroll_group_search();
            break;
        case 'submit_create_group':
            submit_create_group(element);
            break;
        case 'downloadvpnconifg':
            downloadvpnconifg(element);
            break;
        case 'submit_create_class':
            submit_create_class(element);
            break;
        case 'submit_apply_superuserrole_class':
            submit_apply_superuserrole_class(element);
            break;
        case 'submit_update_user_profile':
            submit_update_user_profile(element);
            break;
        case 'doNotShowHelp':
            doNotShowHelp(element);
            break;
        case 'firsttime_submit_update_user_profile':
            firsttime_submit_update_user_profile(element);
            break;
        case 'firsttime_submit_update_both_pass_and_user_profile':
            firsttime_submit_update_both_pass_and_user_profile(element);
            break;
        case 'submit_user_role_change':
            submit_user_role_change(element);
            break;
        case 'submit_update_user_password':
            submit_update_user_password(element);
            break;
        case 'search_user_btn':
            search_user_btn();
            break;
        case 'share_temp_user_btn':
            share_user_btn_temp(element);
            break;
        case 'copy_temp_user_btn':
            copy_user_btn_temp(element);
            break;
        case 'search_group_btn':
            search_group_btn();
            break;
        case 'search_groupbasedenroll_btn':
            search_groupbasedenroll_btn();
            break;
        case 'submit_create_project':
            var form = element.closest('form');
            var project_name = form.find('#project_name').val();
            var project_desc = form.find('#project_desc').val();
            project_desc = (!project_desc) ? " " : project_desc;
            //alert(project_name.trim());
            if (!project_name.trim() || project_name.indexOf(' ') >= 0) {
                alert('Please enter the project name with a non-space string!');
            }
            else {
                $.post("/cloud/createProject", {
                        //$(this).prop('action'), {
                        //"_token": $(this).find('input[name=_token]').val(),
                        "project_name": form.find('#project_name').val(),
                        "project_desc": form.find('#project_desc').val()
                    },
                    function (data) {
                        if (data.status == "Success") {
                            alert(data.msg);
                            element.closest('.dialog').dialog('close');
                            form[0].reset();
                            //$('#project_list').closest('.tabs').tabs("option","active", 0);
                            project_list_update('new', data.tenant);
                        }
                        else {
                            alert(data.msg);
                            form[0].reset();
                        }
                    },
                    'json'
                )
                    //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
                    .fail(function (xhr, testStatus, errorThrown) {
                        alert(xhr.responseText);
                    });
            }
            break;
        case 'submit_update_project':
            form = element.closest('form');
            project_name = form.find('#project_name').val();
            project_desc = form.find('#project_desc').val();
            project_desc = (!project_desc) ? " " : project_desc;
            //alert(project_name.trim());
            if (!project_name.trim() || project_name.indexOf(' ') >= 0) {
                alert('Please enter the project name with a non-space string!');
            }
            else {
                $.post("/cloud/updateProject", {
                        //$(this).prop('action'), {
                        //"_token": $(this).find('input[name=_token]').val(),
                        "project_name": form.find('#project_name').val(),
                        "project_desc": form.find('#project_desc').val()
                    },
                    function (data) {
                        if (data.status == "Success") {
                            alert(data.msg);
                            element.closest('.dialog').dialog('close');
                            form[0].reset();
                            //$('#project_list').closest('.tabs').tabs("option","active", 0);
                            project_list_update('update', data.tenant);
                        }
                        else {
                            alert(data.msg);
                            form[0].reset();
                        }
                    },
                    'json'
                )
                    //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
                    .fail(function (xhr, testStatus, errorThrown) {
                        alert(xhr.responseText);
                    });
            }
            break;
        case 'submit_create_net':
            form = element.closest('form');
            //alert(form.find('#project_name').val());
            $.post("/cloud/createNetwork", {
                    //"_token": $(this).find('input[name=_token]').val(),
                    "project_name": form.find('#project_name').val(),
                    "network_name": form.find('#net_name').val(),
                    "subnet_name": form.find('#subnet_name').val(),
                    "network_address": form.find('#net_address').val()
                },
                function (data) {
                    if (data.status == "Success") {
                        alert(data.msg);
                        form[0].reset();
                        element.closest('.dialog').dialog('close');
                        //net_list_update('net_list', data.tenant);
                    }
                    else {
                        alert(data.msg);
                        form[0].reset();
                    }
                },
                'json'
            )
                //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
                .fail(function (xhr, testStatus, errorThrown) {
                    alert(xhr.responseText);
                });
            //$('#dlg_create_net').remove();
            break;
        case 'submit_create_vm':
            form = element.closest('form');
            $.post("/cloud/createVM", {
                    //        "_token": $(this).find('input[name=_token]').val(),
                    "project_name": form.find('#project_name').val(),
                    "vm_name": form.find('#vm_name').val(),
                    "image": form.find('#select_image').val(),
                    "flavor": form.find('#select_flavor').val(),
                    "network": form.find('#select_network').val()
                },
                function (data) {
                    $("#result_create_vm").empty().append(data.msg);
                    if (data.status == "Success") {
                        alert(data.msg);
                        form[0].reset();
                        element.closest('.dialog').dialog('close');
                        //vm_list_update('net_list', data.tenant);
                    }
                    else {
                        alert(data.msg);
                        form[0].reset();
                    }
                },
                'json'
            )
                //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
                .fail(function (xhr, testStatus, errorThrown) {
                    alert(xhr.responseText);
                });
            $('#dlg_create_net').remove();
            break;
        default:
            if (element.hasClass('subgroup_members')) {
                subgroup_members(element);
            }else if (element.hasClass('update_group_meta')) {
                update_group_meta(element);
            }else if (element.hasClass('show_member')) {
                show_member(element);
            }else if (element.hasClass('btn_assign_temp_to_lab')) {
                btn_assign_temp_to_lab(element);
            } else if (element.hasClass('btn_edit_subgroup2')) {
                btn_edit_subgroup2(element);
            } else if (element.hasClass('edit_subgroup_name')) {
                update_subgroup_name_button(element);
            } else if (element.hasClass('gm_leave_group')) {
                btn_leave_group(element);
            } else if (element.hasClass('gm_duplicate_leave_group')) {
                btn_duplicate_leave_group(element);
            } else if (element.hasClass('gm_duplicate_delete_group')) {
                btn_duplicate_delete_group(element);
            } else if (element.hasClass('gm_approve_class')) { //gm_reject_class
                btn_approve_class(element);
            } else if (element.hasClass('gm_reject_class')) { //
                btn_reject_class(element);
            } else if (element.hasClass('gm_cancel_sent')) {
                btn_cancel_sent(element);
            } else if (element.hasClass('go_add_member')) {
                dlg_invite_group(element);
            } else if (element.hasClass('edit_permission_button')) {
                edit_permission_button(element);
            } else if (element.hasClass('go_pending_enroll')) {
                dlg_pending_enroll(element);
            } else if (element.hasClass('btn-pending-invite')) {
                btn_pending_invite(element);
            } else if (element.hasClass('btn-groupbased-enroll')) {
                btn_groupbased_enroll(element);
            } else if (element.hasClass('btn-invite')) {
                btn_invite(element);
            } else if (element.hasClass('btn-webrtc-callone')) {
                callSomebody(element.parent().prev().html());
            } else if (element.hasClass('btn-webrtc-callone-tree')) {
                callSomebody(element.attr("peeremail"));
            } else if (element.hasClass('btn-invite-copy-for-role-update')) {
                btn_invite_copy(element);
            }else if (element.hasClass('btn-delete-from-group')) {
                btn_delete_from_group(element);
            } else if (element.hasClass('btn-pending-enroll')) {
                btn_pending_enroll(element);
            } else if (element.hasClass('btn-pendingsuperuser-enroll')) {
                btn_pendingsuperuser_enroll(element);
            } else if (element.hasClass('edit_role_json_button')) {
                btn_edit_role_json(element);
            } else if (element.hasClass('btn-enroll')) {
                btn_enroll(element);
            }else if (element.hasClass('btn_edit_lab')) {
                edit_lab(element);
            }else if (element.hasClass('temp_share')) {
                share_temp(element);
            }else if (element.hasClass('temp_deploy')) {
                deploy_temp(element);
            }else if (element.hasClass('temp_copy')) {
                copy_temp(element);
            }else if (element.hasClass('temp_delete')) {
                delete_temp(element);
            }else if (element.hasClass('btn-Share')) {
                btn_share(element);
            }else if (element.hasClass('btn-Copy')) {
                btn_copy(element);
            }else if (element.hasClass('btn-Delete')) {
                btn_delete(element);
            }else if (element.hasClass('btn-DeleteProject')) {
                btn_deleteproject(element);
            }else if (element.hasClass('btn-Deploy')) {
                btn_deploy(element);
            }else if (element.hasClass('proj_member')) {
                var projectId = element.parent().prev().html();
                //alert(projectId);
                $.getJSON("/cloud/getProjectMembers/" + projectId, function (jsondata) {
                    element.parent().html(jsondata);
                });
            } else if (element.hasClass('shared_member')) {
                var temp_id = element.parent().prev().html();
                //alert(projectId);
                $.getJSON("/cloud/getSharedMembers/" + temp_id, function (jsondata) {
                    element.parent().html(jsondata);
                });
            }
            else if (element.hasClass('dialog-btn')) {
                if (eid.substring(0, 'btn_dlg_create_proj'.length) == 'btn_dlg_create_proj') {
                    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
                    dlg_form.addClass('dialog').attr('id', 'dlg_create_proj').attr('title', 'Create a new project');

                    var form_html = '<form method="post" class="contact_form" id="form_create_project" style="width:400px">' +
                        '<ul><li><h2>Create a new Project</h2>' +
                        '<span class="required_notification">* Denotes Required Field</span></li>' +
                        '<li><label for="project_name">Project Name:</label>' +
                        '<input type="text" name="project_name" id="project_name" placeholder="myproject1" required="true" /></il>' +
                            //'<span class="form_valid">Require Non-empty string.</span></li>' +
                        '<li><label for="project_desc">Project Description:</label>' +
                        '<textarea name="project_desc" id="project_desc" cols="40" rows="6" placeholder="This is my first project."></textarea></li>' +
                        '<li><button type="button" class="submit" id="submit_create_project">Submit Request</button></li>' +
                        '</ul></form>' +
                        '<div id="result_create_project"></div>';
                    $(form_html).appendTo(dlg_form);
                    $('#dlg_create_proj').dialog({
                        modal: true,
                        //buttons: {
                        //    OK: function() {
                        //        $( this ).dialog( "close" );
                        //    }
                        //},
                        //beforeClose: function( event, ui ) {
                        //    if ( !$( "#terms" ).prop( "checked" ) ) {
                        //        event.preventDefault();
                        //        $( "[for=terms]" ).addClass( "invalid" );
                        //    }
                        //},
                        width: 600,
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
                }
                else if (eid.substring(0, 'btn_dlg_create_net_'.length) == 'btn_dlg_create_net_') {
                    var proj_name = eid.substring('btn_dlg_create_net_'.length);
                    var dlg_form_net = $(document.createElement('div')).appendTo($('#desktop'));
                    dlg_form_net.addClass('dialog').attr('id', 'dlg_create_net').attr('title', 'Create a Network');

                    var form_net_html = '<form method="post" class="contact_form" id="form_create_net">' +
                        '<ul><li><h2>Create a private network</h2>' +
                        '<span class="required_notification">* Denotes Required Field</span>' +
                        '<input type="hidden" id="project_name" value="' + proj_name + '"/></li>' +
                        '<li><label for="net_name">Network Name:</label>' +
                        '<input type="text" id="net_name" placeholder="my-network1" required /></li>' +
                        '<li><label for="subnet_name">Subnet Name:</label>' +
                        '<input type="text" id="subnet_name" placeholder="my-subnet1" required /></li>' +
                        '<li><label for="subnet_name">Network Address:</label>' +
                        '<input type="text" id="net_address" placeholder="192.168.1.0/24" required /></li>' +
                        '<li><button type="button" class="submit" id="submit_create_net">Submit Request</button></li>' +
                        '</ul></form>' +
                        '<div id="result_create_net"></div>';
                    $(form_net_html).appendTo(dlg_form_net);
                    $('#dlg_create_net').dialog({
                        modal: true, width: 600,
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
                }
                else if (eid.substring(0, 'btn_dlg_create_vm_'.length) == 'btn_dlg_create_vm_') {
                    proj_name = eid.substring('btn_dlg_create_vm_'.length);
                    var dlg_form_vm = $(document.createElement('div')).appendTo($('#desktop'));
                    dlg_form_vm.addClass('dialog').attr('id', 'dlg_create_vm').attr('title', 'Create a Virtual Machine');

                    var form_vm_html = '<form method="post" class="contact_form" id="form_create_vm">' +
                        '<ul><li><h2>Create a new virtual machine</h2>' +
                        '<span class="required_notification">* Denotes Required Field</span>' +
                        '<input type="hidden" id="project_name" value="' + proj_name + '"/></li>' +
                        '<li><label for="vm_name">Instance Name:</label>' +
                        '<input type="text" id="vm_name" placeholder="my-vm1" required /></li>' +
                        '<li><label for="select_image">Image:</label>' +
                        '<select id="select_image" required ></select></li>' +
                        '<li><label for="select_flavor">Flavor:</label>' +
                        '<select id="select_flavor" required></select><span id="flavor_detail"></span></li>' +
                        '<li><label for="select_network" >Network (Press Ctrl for multiple selection):</label>' +
                        '<select id="select_network" MULTIPLE required></select></li>' +
                        '<li><button type="button" class="submit" id="submit_create_vm">Submit Request</button></li>' +
                        '</ul></form>' +
                        '<div id="result_create_vm"></div>';
                    $(form_vm_html).appendTo(dlg_form_vm);

                    $.getJSON("/cloud/getImageList/" + proj_name, function (jsondata) {
                        var select_image = $('#select_image');
                        select_image.empty().append('<option value="0">Select a image</option>');
                        $.each(jsondata.images, function (index, item) {
                            select_image.append('<option value="' + item.id + '" >' + item.name + ' (' + sizeOf(item.size) + ')</option>')
                        });
                        var select_flavor = $('#select_flavor');
                        select_flavor.empty().append('<option value="0">Select a flavor</option>');
                        $.each(jsondata.flavors, function (index, item) {
                            select_flavor.append('<option value="' + item.id + '">' + item.name +
                            ' (vcpus:' + item.vcpus + ',ram:' + item.ram + 'MB,disk:' + item.disk +
                            'GB)</option>')
                        });
                        var select_network = $('#select_network');
                        select_network.empty();
                        $.each(jsondata.networks, function (index, item) {
                            select_network.append('<option value="' + item.id + '">' + item.name +
                            ' (' + item.subnet[0].name + ' ' + item.subnet[0].cidr + ')</option>');
                        });
                    });
                    $('#dlg_create_vm').dialog({
                        modal: true, width: 600,
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
                }
                else if (eid.substring(0, 'btn_verify_design_'.length) == 'btn_verify_design_') {
                    proj_name = eid.substring('btn_verify_design_'.length);
                    //alert('verify design in ' + proj_name); // + ' JSON:' + JSON.stringify(net_topology_data[proj_name]));
                    verify_lab_template(element);
                }
                else if (eid.substring(0, 'btn_save_design_'.length) == 'btn_save_design_') {
                    proj_name = eid.substring('btn_save_design_'.length);

                    if(proj_name == 'new') {
                        //alert('save design in ' + proj_name);
                        if(jQuery.isEmptyObject(lab_resources['new'])){
                            alert('You can not save an empty template!');
                            return;
                        }

                        var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
                        dlg_form.addClass('dialog').attr('id', 'save_design_yuli').attr('title', 'Save Design');

                        var form_html = '<p>Template Name:</p>' +
                            '<input type="text" name="temp_name" value="Design Name">*Required<br><br>' +
                            '<p>Template Description:</p>' +
                            '<textarea id="save_design_des_text">' + '</textarea>*Required' +
                            '<br><br>' +
                            '<button id="save_design_to_DB">Save</button>';
                        $(form_html).appendTo(dlg_form);

                        $('#save_design_yuli').dialog({
                            modal: true,
                            width: 600,
                            close: function (event, ui) {
                                $(this).remove();
                            }
                        });
                    }else{
                        update_lab_template(proj_name);
                    }
                }
            }
    }

}

function select_change(element) {
    var eid = element.attr('id');
    if (typeof eid == 'undefined') {
        return ;
    }
    if (eid == 'select_flavor') {
        var select = $('#' + eid + ' option:selected');
//        alert(select.val());
    } else if (eid.substring(0, 'node-type_'.length) == 'node-type_') {
        var project = eid.substring('node-type_'.length);
        var node_type = document.getElementById('node-type_' + project).value;
        var node_type_vm_image = document.getElementById('node-type-vm-image_' + project);
        //var node_type_vm_flavor = document.getElementById('node-type-vm-flavor_' + project);
        var node_type_router = document.getElementById('node-type-router_' + project);
        var node_type_sw_subnets = document.getElementById('node-type-sw-subnets_' + project);
        node_type_vm_image.style.display = 'none';
        //node_type_vm_flavor.style.display = 'none';
        node_type_router.style.display = 'none';
        node_type_sw_subnets.style.display = 'none';
        if (node_type == 'vm') {
            $.getJSON("/cloud/getImageList/dummy", function (jsondata) {
                $.each(jsondata.images, function (index, item) {
                    var opt = document.createElement('option');
                    opt.value = item.id;
                    opt.innerHTML = item.name + ' (' + sizeOf(item.size) + ')';
                    node_type_vm_image.add(opt);
                });
                $.each(jsondata.flavors, function (index, item) {
                    var opt = document.createElement('option');
                    opt.value = item.id;
                    opt.innerHTML = item.name + ' (vcpus:' + item.vcpus + ',ram:' + item.ram + 'MB,disk:' + item.disk + 'GB)';
                    //node_type_vm_flavor.add(opt);
                });
                node_type_vm_image.style.display = 'inline';
                //node_type_vm_flavor.style.display = 'inline';
            });
        } else if (node_type == 'switch') {
            node_type_sw_subnets.style.display = 'inline';

        } else if (node_type == 'router') {
            node_type_router.style.display = 'inline';
        }
    } else if (eid == 'group_select_for_team') {
        getTeamListTable('all');
        getLabDeployListTable('all');
    }
}

function dropdown_actions(element) {
    //get row ID
    var winId = element.closest('div.window').attr('id');
    if (element.hasClass('labDeploy-actionButton')) {
        var groupname = element.closest('div.window').attr('id').substring('window_group_project_'.length);
        var team_name = element.closest('tr').children().eq(2).html().trim();
        var temp_name = element.closest('tr').children().eq(4).html().trim();
        var project_name = element.closest('tr').children().eq(5).html().trim();
        var labname = element.closest('tr').children().eq(6).html().trim();
        var status = element.closest('tr').children().eq(10).html().trim();
        var newwin = "";
        if (labname.trim().length == 0) {
            newwin = groupname + ":" + team_name + ":" + temp_name;
        } else {
            newwin = groupname + ":" + labname;
        }
        //newwin = newwin.replace(/\s+/g,'-');
        element.after($('#labDeploy-contextMenu'));
        if (status == 'CREATE_COMPLETE') {
            $('#labDeploy-contextMenu').find(".lab-view").attr("href", "#").css('display', 'block')
                .attr('name', 'Lab : ' + newwin).attr('value', 'project_' + project_name);
            $('#labDeploy-contextMenu').find(".lab-deploy").attr("href", "#").css('display', 'none')
                .attr("onclick", "lab_deploy($(this))");
            $('#labDeploy-contextMenu').find(".lab-delete").attr("href", "#").css('display', 'block')
                .attr("onclick", "lab_delete($(this))");
            $('#labDeploy-contextMenu').find(".lab-rename").attr("href", "#").css('display', 'block')
                .attr("onclick", "lab_rename($(this))");
            $('#labDeploy-contextMenu').find(".lab-events").attr("href", "#").css('display', 'block').attr('value', 'project_' + project_name)
                .attr("onclick", "lab_events($(this))");
        } else if (status == 'Deploying' || status == 'CREATE_IN_PROCESS') {
            $('#labDeploy-contextMenu').find(".lab-view").attr("href", "#").css('display', 'none')
                .attr('name', 'Lab : ' + newwin).attr('value', 'project_' + project_name);
            $('#labDeploy-contextMenu').find(".lab-deploy").attr("href", "#").css('display', 'none')
                .attr("onclick", "lab_deploy($(this))");
            $('#labDeploy-contextMenu').find(".lab-delete").attr("href", "#").css('display', 'none')
                .attr("onclick", "lab_delete($(this))");
            $('#labDeploy-contextMenu').find(".lab-rename").attr("href", "#").css('display', 'block')
                .attr("onclick", "lab_rename($(this))");
            $('#labDeploy-contextMenu').find(".lab-events").attr("href", "#").attr('type', 'single').attr('value', 'project_' + project_name)
                .attr("onclick", "lab_events($(this))");
        } else {
            $('#labDeploy-contextMenu').find(".lab-view").attr("href", "#").css('display', 'none')
                .attr('name', 'Lab : ' + newwin).attr('value', 'project_' + project_name);
            $('#labDeploy-contextMenu').find(".lab-deploy").attr("href", "#").css('display', 'block')
                .attr("onclick", "lab_deploy($(this))");
            $('#labDeploy-contextMenu').find(".lab-delete").attr("href", "#").css('display', 'block')
                .attr("onclick", "lab_delete($(this))");
            $('#labDeploy-contextMenu').find(".lab-rename").attr("href", "#").css('display', 'block')
                .attr("onclick", "lab_rename($(this))");
            $('#labDeploy-contextMenu').find(".lab-events").attr("href", "#").css('display', 'block').attr('value', 'project_' + project_name)
                .attr("onclick", "lab_events($(this))");
        }
        $('#labDeploy-contextMenu').find(".lab-rename").attr("href", "#").attr('type', 'single')
            .attr("onclick", "lab_rename($(this))");
    }     else if (element.hasClass('group-actionButton')) {
        //var projId = element.closest('tr').children().eq(1).html();

        var groupname = element.closest('tr').children().eq(0).html();
        //move dropdown menu
        element.after($('#group-contextmenu'));

        //update links
        $('#group-contextmenu').find(".edit_group_lab").attr("href", "#").attr('name', 'Lab Management : ' + groupname).attr('value', 'edit_group_lab_' + groupname)
            .attr("onclick", "");
        $('#group-contextmenu').find(".update_group_meta").attr("href", "#")
            .attr("onclick", "update_group_meta($(this))");
        $('#group-contextmenu').find(".show_member").attr("href", "#")
            .attr("onclick", "show_member($(this))");
        $('#group-contextmenu').find(".go_pending_enroll").attr("href", "#")
            .attr("onclick", "dlg_pending_enroll($(this))");
        $('#group-contextmenu').find(".go_add_member").attr("href", "#")
            .attr("onclick", "dlg_invite_group($(this))");
        $('#group-contextmenu').find(".go_add2_member").attr("href", "#")
            .attr("onclick", "gm_submit_group_based_enroll($(this))");
        $('#group-contextmenu').find(".delete_group1").attr("href", "#")
            .attr("onclick", "delete_group1($(this))");
        //$('#group-contextmenu').find(".sub_group").attr("href", "#").attr('name', 'Team Management : ' + groupname).attr('value', 'group_project_' + groupname)
        //    .attr("onclick", "");
    }     else if (element.hasClass('group2-actionButton')) {
        //var projId = element.closest('tr').children().eq(1).html();

        var groupname = element.closest('tr').children().eq(0).html();
        //move dropdown menu
        element.after($('#group2-contextmenu'));

        //update links
        //$('#group2-contextmenu').find(".edit_group_lab").attr("href", "#").attr('name', 'Lab Management : ' + groupname).attr('value', 'edit_group_lab_' + groupname)
        //    .attr("onclick", "");
        //$('#group2-contextmenu').find(".show_member").attr("href", "#")
        //    .attr("onclick", "show_member($(this))");
        //$('#group2-contextmenu').find(".go_pending_enroll").attr("href", "#")
        //    .attr("onclick", "dlg_pending_enroll($(this))");
        //$('#group2-contextmenu').find(".go_add_member").attr("href", "#")
        //    .attr("onclick", "dlg_invite_group($(this))");
        //$('#group2-contextmenu').find(".go_add2_member").attr("href", "#")
        //    .attr("onclick", "gm_submit_group_based_enroll($(this))");
        //$('#group2-contextmenu').find(".delete_group1").attr("href", "#")
        //    .attr("onclick", "delete_group1($(this))");
        $('#group2-contextmenu').find(".sub_group").attr("href", "#").attr('name', 'Team Management : ' + groupname).attr('value', 'group_project_' + groupname)
            .attr("onclick", "");
    } else if (element.hasClass('proj-actionButton')) {
        //var projName = element.closest('tr').children().eq(1).html();
        //var projId = element.closest('tr').children().eq(3).html();
        //move dropdown menu
        element.after($('#proj-contextMenu'));

        //update links
        $('#proj-contextMenu').find(".proj-edit").attr("href", "#")
            .attr("onclick", "project_action($(this), 'update'); return false;");
        $('#proj-contextMenu').find(".proj-addMember").attr("href", "#")
            .attr("onclick", "project_action($(this), 'addMember'); return false;");
        $('#proj-contextMenu').find(".proj-delete").attr("href", "#")
            .attr("onclick", "project_action($(this), 'delete'); return false;");

    } else if (element.hasClass('vm-actionButton')) {
        var vmId = element.closest('tr').children().eq(2).html();
        var proj = element.closest('table').attr('id').substring('tbl_vm_list_'.length);

        //move dropdown menu
        element.after($('#vm-contextMenu'));

        //update links
        $('#vm-contextMenu').find(".vm-console").attr("target", "_blank")
            .attr("href", "/cloud/getConsole/" + proj + "/" + vmId);
        $('#vm-contextMenu').find(".vm-reboot").attr("href", "#")
            .attr("onclick", "vm_reboot($(this),'" + proj + "','" + vmId + "'); return false;");
        $('#vm-contextMenu').find(".vm-refresh").attr("href", "#")
            .attr("onclick", "vm_list_update($(this),'" + proj + "','" + vmId + "'); return false;");
        //$('#vm-contextMenu').find(".vm-edit").attr("href", "#")
        //    .attr("onclick", "alert('not implement yet');"); //vm_list_update($(this),'" + proj + "','" + vmId + "'); return false;");
        //$('#vm-contextMenu').find(".vm-delete").attr("href", "#")
        //    .attr("onclick", "vm_delete($(this),'" + proj + "','" + vmId + "'); return false;");

    } else if (element.hasClass('temp-actionButton')) {

        //move dropdown menu
        element.after($('#temp-contextMenu'));
        var tempid = element.closest('tr').children().eq(3).html();

        //update links
        $('#temp-contextMenu').find(".temp-deploy").attr("href", "#")
            .attr("onclick", "deploy_temp($(this)); return false;");
        $('#temp-contextMenu').find(".temp_edit").attr("href", "#").attr('name', 'Edit Design Template').attr('value', 'temp_design' + tempid)
            .attr("onclick", "");
        $('#temp-contextMenu').find(".temp-share").attr("href", "#")
            .attr("onclick", "share_temp($(this)); return false;");
        $('#temp-contextMenu').find(".temp-copy").attr("href", "#")
            .attr("onclick", "copy_temp($(this)); return false;");
        $('#temp-contextMenu').find(".temp-delete").attr("href", "#")
            .attr("onclick", "delete_temp(" + tempid + ",$(this)); return false;");
    } else if (element.hasClass('team-actionButton')) {
        //move dropdown menu
        element.after($('#team-contextMenu'));
        var team_id = element.closest('tr').children().eq(1).html();
        var team_name = element.closest('tr').children().eq(2).html();
        var team_members = element.closest('tr').children().eq(4).html();
        //update links
        $('#team-contextMenu').find(".team-members").attr("href", "#") //.attr("value", team_name).attr('name', team_members)
            .attr("onclick", "update_team_members(" + team_id + ",'" + team_name + "','" + team_members + "',$(this)); return false;");
        $('#team-contextMenu').find(".team-edit").attr("href", "#")
            .attr("onclick", "update_team_info($(this)); return false;");
        $('#team-contextMenu').find(".team-delete").attr("href", "#").attr("value", team_name)
            .attr("onclick", "delete_team(" + team_id + ",$(this)); return false;");
    } else if (element.hasClass('ownclass-actionButton')) {

        //move dropdown menu
        element.after($('#ownclass-contextMenu'));
        var url = element.closest('tr').children().eq(4).html();
        var coursename = element.closest('tr').children().eq(0).html();
        var parts=url.split("/");

        //update links
        $('#ownclass-contextMenu').find(".ownclass-view").attr("href", "#").attr('name', 'Lab Content').attr('value', 'open_lab_'+parts[1]+'_'+parts[2])
            .attr("onclick", "");
        $('#ownclass-contextMenu').find(".ownclass-edit").attr("href", "#").attr('name', 'Edit Lab : '+ coursename).attr('value', 'edit_own_lab_' +url.replace(/\//g,'-') )
            .attr("onclick", "");
        $('#ownclass-contextMenu').find(".ownclass-assign").attr("href", "#")
            .attr("onclick", "ownclass_assign($(this)); return false;");

    } else if (element.hasClass('workinglab-actionButton')) {

        //move dropdown menu
        element.after($('#workinglab-contextMenu'));

        var project = element.closest('tr').children().eq(5).html();
        var lab = element.closest('tr').children().eq(6).html()+'_'+element.closest('tr').children().eq(7).html()
        //update links
        $('#workinglab-contextMenu').find(".workinglab-content").attr("href", "#").attr('name', 'Lab Content').attr('value', 'open_lab_'+lab)
            .attr("onclick", "");
        $('#workinglab-contextMenu').find(".workinglab_topo").attr("href", "#").attr('name', 'Lab Environment').attr('value', 'project_'+project )
            .attr("onclick", "");

    } else if (element.hasClass('labDesign-actionButton')) {
        //var projName = element.closest('tr').children().eq(1).html();
        //var projId = element.closest('tr').children().eq(3).html();
        //move dropdown menu
        element.after($('#labDesign-contextMenu'));

        var url = element.closest('tr').children().eq(4).html();
        var url1 = element.closest('tr').children().eq(5).html();
        //update links
        $('#labDesign-contextMenu').find(".labDesign-view").attr("href", "#").attr('name', 'Lab Content').attr('value', 'open_lab_'+url+'_'+url1)
            .attr("onclick", "");
        //update links
        $('#labDesign-contextMenu').find(".labDesign-edit").attr("href", "#")
            .attr("onclick", "labDesign_edit($(this)); return false;");
        $('#labDesign-contextMenu').find(".labDesign-delete").attr("href", "#")
            .attr("onclick", "labDesign_delete($(this)); return false;");
    } else if (element.hasClass('sys-admin-proj-actionButton')) {
        // var projname = element.closest('tr').children().eq(1).html();
        // var projId = element.closest('tr').children().eq(3).html();
        //move dropdown menu
        element.after($('#sys-admin-proj-contextMenu'));

        $('#sys-admin-proj-contextMenu').find(".sys-proj-members").attr("href", "#")
            .attr("onclick", "sys_admin_project_members($(this)); return false;");
        $('#sys-admin-proj-contextMenu').find(".sys-proj-config").attr("href", "#")
            .attr("onclick", "sys_admin_project_config($(this)); return false;");
        $('#sys-admin-proj-contextMenu').find(".sys-proj-delete").attr("href", "#")
            .attr("onclick", "sys_admin_project_delete($(this)); return false;");
    } else if (element.hasClass('user-admin-users-actionButton')) {
        element.after($('#user-admin-users-contextMenu'));

        $('#user-admin-users-contextMenu').find(".user-admin-user-profile").attr("href", "#")
            .attr("onclick", "user_admin_user_profile($(this)); return false;");
        $('#user-admin-users-contextMenu').find(".user-admin-user-roles").attr("href", "#")
            .attr("onclick", "user_admin_user_roles($(this)); return false;");
        $('#user-admin-users-contextMenu').find(".user-admin-user-activity").attr("href", "#")
            .attr("onclick", "user_admin_user_activity($(this)); return false;");
        $('#user-admin-users-contextMenu').find(".user-admin-user-delete").attr("href", "#")
            .attr("onclick", "user_admin_user_delete($(this)); return false;");
    } else if (element.hasClass('group-admin-groups-actionButton')) {
        element.after($('#group-admin-groups-contextMenu'));

        $('#group-admin-groups-contextMenu').find(".group-admin-group-edit").attr("href", "#")
            .attr("onclick", "group_admin_group_detail($(this)); return false;");
        $('#group-admin-groups-contextMenu').find(".group-admin-group-member").attr("href", "#")
            .attr("onclick", "group_admin_group_members($(this)); return false;");
        $('#group-admin-groups-contextMenu').find(".group-admin-group-usage").attr("href", "#")
            .attr("onclick", "group_admin_group_usage($(this)); return false;");
        $('#group-admin-groups-contextMenu').find(".group-admin-group-delete").attr("href", "#")
            .attr("onclick", "group_admin_group_delete($(this)); return false;");
    }

    //show dropdown
    if (!element.parent().hasClass("open-menu")) {
        element.closest("table").find(".open-menu").removeClass("open-menu");
        element.parent().addClass("open-menu");
    }
    else {
        element.parent().removeClass("open-menu");
    }
}

function vm_reboot(element, project, vmId) {
    element.closest('tr').children().eq(7).empty().append('<img src= "img/waiting.gif" width="50" height="20" />');
    $.post("/cloud/rebootVM", {
            //"_token": $(this).find('input[name=_token]').val(),
            "project_name": project,
            "vmId": vmId
        },
        function (data) {
            //alert(JSON.stringify(data));
            //if (data.status == "Success") {
            //    alert(data.msg);
            vm_list_update(element, project, vmId);
            //}
            //else {
            //    alert(data.msg);
            //    form[0].reset();
            //}
        },
        'json'
    )
}

function vm_delete(element, project, vmId) {
    element.closest('tr').children().eq(7).empty().append('<img src= "img/waiting.gif" width="50" height="20" />');
    $.post("/cloud/deleteVM", {
            //"_token": $(this).find('input[name=_token]').val(),
            "project_name": project,
            "vmId": vmId
        },
        function (data) {
            element.closest('tr').remove();
        },
        'json'
    )
}

function btn_assign_temp_to_lab(element){
    var classid= $("#classid").html();
    var nameid= $("#nameid").html();
    var tempid=$("#own_temp_list").val();
    $.post("/cloud/assignTemplate", {
            "classid": classid,
            "tempid": tempid,
            "nameid": nameid

        },
        function (data) {
            //template_list_update('lab_templates', 'new');
            //alert(data);
            if (data != null) {
                // alert('Not Found');
            }
        },
        'json'
    ).fail(function (xhr, testStatus, errorThrown) {
            //alert(xhr.responseText);
        });
    element.closest('.dialog').dialog('close');
}

function create_ConfirmDialog(title, ask, okCallback, cancelCallback) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dialog_confirm').attr('title', title);

    var form_html = '<span>' + ask + '</span>';
    $(form_html).appendTo(dlg_form);
    $('#dialog_confirm').dialog({
        modal: true,
        width: 400,
        buttons: {
            "Yes": function () {
                $(this).dialog('close');
                okCallback();
            },
            "Cancel": function () {
                $(this).dialog('close');
                cancelCallback();
            }
        },
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function get_vm_console(winId, win_main) {
    win_main.closest('div.window').css('width','1000px').css('height','700px');
    var str = winId.split('_');
    //var vmId = eid.substring('#window_vm_'.length);
    var projectName = str[2];
    var vmId = str[3];
    var div_console = $(document.createElement('div')).appendTo(win_main);
    div_console.attr("id", "vm_console_" + vmId); //.attr("height","100%");
    var overlay_btn_screenshot = $(document.createElement('button')).appendTo(div_console);
    overlay_btn_screenshot.attr("id", "btn_console_screenshot_" + vmId).attr("title", "Take a Screenshot")
        .attr("onclick", "take_console_screeshot('vm_console_" + vmId + "')")
        .css("position", "absolute").css("top", "0px").css("left", "10px").css("z-index", "50")
        .css("opacity", "0.8").appendTo(div_console);
    overlay_btn_screenshot.append('<i class="fa fa-camera-retro fa-2x" aria-hidden="true"></i>');

    var iframe_console = $(document.createElement('iframe')).appendTo(div_console);
    iframe_console.attr("id", "vm_console_" + vmId).attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "/cloud/getConsole/" + projectName + "/" + vmId);
    $(winId).find('div.window_bottom').text('Click the grey bar on top of the window to respond the keyboard input.');
}

function get_studio_console(winId, win_main) {
    var title_one = '<fieldset><legend>Available Open Labs:</legend>' +  '</fieldset>';
    var div_console = $(document.createElement('div')).appendTo(win_main);
    //div_console.attr("id", "studio"); //.attr("height","100%");
    $(title_one).appendTo(div_console);
    var openlabs = '<ul><li data-jstree={"opened":true,"selected":true}>Open Labs'+
        '<ul>'+
        '<li>Security Labs'+
        '<ul>'+
        '<li>Software and Web Security'+
        '<ul>'+
        '<li>Buffer Overflow Vulnerability Lab'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_CSE552_2015_T1">View</button></li>'+
        '<li>Return-to-libc Attack Lab'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SS02_2015_T1">View</button></li>'+
        '<li>Set-UID Program Vulnerability Lab'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SS03_2015_T1">View</button></li>'+
        '<li>Race Condition Vulnerability Lab'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SS04_2015_T1">View</button></li>'+
        '<li>Cross-site Scripting Attack Labs'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SS07_2015_T1">View</button></li>'+
        '<li>Cross-site Request Forgery Attack Lab'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SS08_2015_T1">View</button></li>'+
        '</ul>'+
        '</li>'+
        '<li>System Security and Cryptography'+
        '<ul>'+
        '<li>Linux Capanility Exploitation Lab'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SS05_2015_T1">View</button></li>'+
        '<li>Role-Based Access Control(RBAC) Lab'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SS06_2015_T1">View</button></li>'+
        '</ul>'+
        '</li>'+
        '<li>Computer Network Security'+
        '</li>'+
        '</ul>'+
        '</li>'+
        '<li>Software Labs' +
        '<ul>'+
        '<li>Software Development Lab 1'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SOD1_2015_T1">View</button></li>'+
        '<li>Software Development Lab 2'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SOD2_2015_T1">View</button></li>'+
        '<li>Software Development Lab 3'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SOD3_2015_T1">View</button></li>'+
        '<li>Software Development Lab 4'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SOD4_2015_T1">View</button></li>'+
        '<li>Software Development Lab 5'+
        '<button type="button" class="btn_view_open_lab" name="Open Lab Content" value="open_lab_SOD5_2015_T1">View</button></li>'+
        '</ul>'+
        '</li>'+
        '<li>Network Labs(Coming Soon)</li>'+
        '</ul>'+
        '</li></ul>';
    var div_tree =document.createElement('div');
    div_tree.id = "jstree_openlab";
    div_tree.className = "jstree_openlab";
    $(div_tree).appendTo(win_main);

    $(openlabs).appendTo(div_tree);

    var group_member = '<fieldset>' +

        '<br/>Or You May ' +
        '<button type="button" class="btn_lab_studio submit" name="Your Labs" value="edxstudio">Create or Manage Your Own Labs</button>' +
        '</fieldset>';

    var div_console1 = $(document.createElement('div')).appendTo(win_main);
    //div_console.attr("id", "studio"); //.attr("height","100%");
    $(group_member).appendTo(div_console1);



    $('#jstree_openlab').jstree({
        'core': {
            'themes': {
                'name': 'proton',
                'responsive': false
            }
        },
        "state" : { "key" : "state_demo" },
        "plugins" : ["themes","state","ui"]
    }).bind("select_node.jstree", function (e, data) { return data.instance.toggle_node(data.node) });

}

function get_edxstudio_console(winId, win_main) {
    win_main.parent().css('overflow','hidden');

    var wnd = window.open("https://labstudio.thothlab.org/home");
    setTimeout(function() {
        wnd.close();
    }, 500);

    var div_console = $(document.createElement('div')).appendTo(win_main);
    //div_console.attr("id", "studio"); //.attr("height","100%");
   // div_console.text("The editing function will be up soon! ").attr("style","font-size: bigger;font-weight:bold");
    var iframe_console = $(document.createElement('iframe'));
    iframe_console.attr("name", "studio").attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://labstudio.thothlab.org/home").appendTo(div_console);
}

function get_help_console(winId, win_main) {
    win_main.parent().css('overflow','hidden');

    var wnd = window.open("https://labstudio.thothlab.org/home");
    setTimeout(function() {
        wnd.close();
    }, 500);

    var div_console = $(document.createElement('div')).appendTo(win_main);
    //div_console.attr("id", "studio"); //.attr("height","100%");
    // div_console.text("The editing function will be up soon! ").attr("style","font-size: bigger;font-weight:bold");
    var iframe_console = $(document.createElement('iframe'));
    iframe_console.attr("name", "studio").attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://labstudio.thothlab.org/home").appendTo(div_console);
}

function populate_content_editownlab(winId, win_main) {
    win_main.parent().css('overflow','hidden');
    var url=winId.substring('#window_edit_own_lab_'.length).replace(/-/g,'/');

    var wnd = window.open("https://labstudio.thothlab.org/course/"+url);
    setTimeout(function() {
        wnd.close();
    }, 1500);

    var div_console = $(document.createElement('div')).appendTo(win_main);
    //div_console.attr("id", "studio"); //.attr("height","100%");
    //div_console.text("The editing function will be up soon! ").attr("style","font-size: bigger;font-weight:bold");
    var iframe_console = $(document.createElement('iframe'));
    iframe_console.attr("name", "studio").attr("width", "100%").attr("style", "height: 100em")
        .attr("src", "https://labstudio.thothlab.org/course/"+url).appendTo(div_console);
}

function getImage(project_name, imageId) {
    $.getJSON("/cloud/getImage/" + project_name + "/" + imageId, function (jsondata) {
        alert(JSON.stringify(jsondata));
        return jsondata.name;
    })
}

function upload_wallpaper(element) {
    var dlg_form = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_form.addClass('dialog').attr('id', 'dlg_upload_wallpaper').attr('title', 'Upload a Wallpaper');

    var form_vm_html = '<form method="post" class="contact_form" id="form_create_vm">' +
        '<ul><li><h2>Create a new virtual machine</h2>' +
        '<span class="required_notification">* Denotes Required Field</span>' +
        '<input type="hidden" id="project_name" value="' + proj_name + '"/></li>' +
        '<li><label for="vm_name">Instance Name:</label>' +
        '<input type="text" id="vm_name" placeholder="my-vm1" required /></li>' +
        '<li><label for="select_image">Image:</label>' +
        '<select id="select_image" required ></select></li>' +
        '<li><label for="select_flavor">Flavor:</label>' +
        '<select id="select_flavor" required></select><span id="flavor_detail"></span></li>' +
        '<li><label for="select_network" >Network (Press Ctrl for multiple selection):</label>' +
        '<select id="select_network" MULTIPLE required></select></li>' +
        '<li><button type="button" class="submit" id="submit_create_vm">Submit Request</button></li>' +
        '</ul></form>' +
        '<div id="result_create_vm"></div>';
    $(form_vm_html).appendTo(dlg_form_vm);

    $.getJSON("/cloud/getResources/" + proj_name, function (jsondata) {
        var select_image = $('#select_image');
        select_image.empty().append('<option value="0">Select a image</option>');
        $.each(jsondata.images, function (index, item) {
            select_image.append('<option value="' + item.id + '" >' + item.name + ' (' + sizeOf(item.size) + ')</option>')
        });
        var select_flavor = $('#select_flavor');
        select_flavor.empty().append('<option value="0">Select a flavor</option>');
        $.each(jsondata.flavors, function (index, item) {
            select_flavor.append('<option value="' + item.id + '">' + item.name +
            ' (vcpus:' + item.vcpus + ',ram:' + item.ram + 'MB,disk:' + item.disk +
            'GB)</option>')
        });
        var select_network = $('#select_network');
        select_network.empty();
        $.each(jsondata.networks, function (index, item) {
            select_network.append('<option value="' + item.id + '">' + item.name +
            ' (' + item.subnet[0].name + ' ' + item.subnet[0].cidr + ')</option>');
        });
    });
    $('#dlg_create_vm').dialog({
        modal: true, width: 600,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}

function sizeOf(bytes) {
    if (bytes == 0) {
        return "0.00 B";
    }
    var e = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes / Math.pow(1024, e)).toFixed(2) + ' ' + ' KMGTP'.charAt(e) + 'B';
}

function getDaysInMonth(month, year) {
    if (typeof year == "undefined") year = 1999; // any non-leap-year works as default
    var currmon = new Date(year, month),
        nextmon = new Date(year, month + 1);
    return Math.floor((nextmon.getTime() - currmon.getTime()) / (24 * 3600 * 1000));
}

function getDateTimeSince(target) { // target should be a Date object
    var now = new Date(), yd, md, dd, hd, nd, sd, out = [];

    yd = now.getFullYear() - target.getFullYear();
    md = now.getMonth() - target.getMonth();
    dd = now.getDate() - target.getDate();
    hd = now.getHours() - target.getHours();
    nd = now.getMinutes() - target.getMinutes();
    sd = now.getSeconds() - target.getSeconds();

    if (md < 0) {
        yd--;
        md += 12;
    }
    if (dd < 0) {
        md--;
        dd += getDaysInMonth(now.getMonth() - 1, now.getFullYear());
    }
    if (hd < 0) {
        dd--;
        hd += 24;
    }
    if (nd < 0) {
        hd--;
        nd += 60;
    }
    if (sd < 0) {
        nd--;
        sd += 60;
    }

    if (yd > 0) out.push(yd + " year" + (yd == 1 ? "" : "s"));
    if (md > 0) out.push(md + " month" + (md == 1 ? "" : "s"));
    if (dd > 0) out.push(dd + " day" + (dd == 1 ? "" : "s"));
    if (hd > 0) out.push(hd + " hr" + (hd == 1 ? "" : "s"));
    if (nd > 0) out.push(nd + " min" + (nd == 1 ? "" : "s"));
    //if( sd > 0) out.push( sd+" second"+(sd == 1 ? "" : "s"));
    return out.join(" ");
}
