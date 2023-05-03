$(document).ready(function() {

    $("#project_list").change(function() {
        $.getJSON("/experiment/getservers/" + $("#project_list").val(), function(jsondata) {
            var server_list = $("#server_list");
            server_list.empty();

            if (jsondata.length == 0) {
                server_list.append("<p>You don't have any virtual machines in this project.</p>");
            } else {
                $.each(jsondata, function(id, item) {
                    var server_div =
                    '<div class="col-md-3 col-sm-6">' +
                        '<div class="panel panel-default text-center">' +
                            '<div class="panel-heading">' +
                                '<span class="fa-stack fa-5x">' +
                                    '<i class="fa fa-circle fa-stack-2x text-primary"></i>' +
                                    '<i class="fa fa-tree fa-stack-1x fa-inverse"></i>' +
                                '</span>' +
                            '</div>' +
                            '<div id="' + id + '" class="panel-body">' +
                                '<h4>' + item.name + '</h4>' +
                                '<p> Status:' + item.status + '</p>' +
                                '<a target="_blank" href="/experiment/getConsole/'
                                                    + $("#project_list").val() + '/' + item.id +
                                '" class="btn btn-primary"> Get Console</a>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                    server_list.append(server_div);
                });
                $("server_list").trigger("change");
            }

            //$.each(jsondata, function(item) {
            //    $servers.append('<option value="' + id +'">' + item.name + '</option>');
            //});
            //$("#server_list").trigger("change"); /* trigger next drop down list not in the example */
        });
    });

    $("form#form-request-project").on('submit', function() {
        $.post(
            $(this).prop('action'), {
                "_token": $(this).find('input[name=_token]').val(),
                "project_name": $('#kv_project_name').val(),
                "project_desc": $('#kv_project_desc').val()
            },
            function(data) {
                $("#create_project_result").empty().append(data.msg);
                if (data.status = "Success") {

                }
            },
            'json'
        )
        //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
        .fail(function(xhr, testStatus, errorThrown) { alert(xhr.responseText); })

        return false;
    });

    $("form#form-request-vm").on('submit', function() {
        $.post(
            $(this).prop('action'), {
                "_token": $(this).find('input[name=_token]').val(),
                "project_name": $('#project_name_vm').val(),
                "vm_name": $('#kv_instance_name').val(),
                "image": $('#image_list').val(),
                "flavor": $('#flavor_list').val(),
                "network": $('#network_list').val()
            },
            function(data) { $("#create_vm_result").empty().append(data.msg); },
            'json'
        )
            //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
            .fail(function(xhr, testStatus, errorThrown) { alert(xhr.responseText); })

        return false;
    });

    $("form#form-request-network").on('submit', function() {
        $.post(
            $(this).prop('action'), {
                "_token": $(this).find('input[name=_token]').val(),
                "project_name": $('#project_name_net').val(),
                "network_name": $('#kv_network_name').val(),
                "subnet_name": $('#kv_subnet_name').val(),
                "network_address": $('#kv_network_address').val()
            },
            function(data) { $("#create_network_result").empty().append(data.msg); },
            'json'
        )
            //.done(function(data) { $("#create_project_result").empty().append(data.msg); })
            .fail(function(xhr, testStatus, errorThrown) { alert(xhr.responseText); })

        return false;
    });

    $("#project_name_vm").change(function() {
        $.getJSON("/experiment/getImages/" + $("#project_name_vm").val(), function (jsondata) {
            var image_list = $("#image_list");
            image_list.empty();
            if (jsondata.length != 0) {
                $.each(jsondata, function (id, item) {
                    image_list.append("<option value='" + id + "'>" + item + "</option>");
                });
                $("image_list").trigger("change");
            }
        });

        $.getJSON("/experiment/getFlavors/" + $("#project_name_vm").val(), function (jsondata) {
            var flavor_list = $("#flavor_list");
            flavor_list.empty();

            if (jsondata.length != 0) {
                $.each(jsondata, function (id, item) {
                    flavor_list.append("<option value='" + id + "'>" + item + "</option>");
                });
                $("flavor_list").trigger("change");
            }
        });

        $.getJSON("/experiment/getNetworks/" + $("#project_name_vm").val(), function (jsondata) {
            var network_list = $("#network_list");
            network_list.empty();

            if (jsondata.length != 0) {
                $.each(jsondata, function (id, item) {
                    network_list.append("<option value='" + id + "'>" + item + "</option>");
                });
                $("network_list").trigger("change");
            }
        });
    });

});
