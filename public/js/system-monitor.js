/**
 * Created by James on 12/17/17.
 */

function system_monitor_window(winId, win_main) {
    var tabs = {
        tabId: ['system_monitor', 'log_search'],
        tabName: ['System monitor', 'Log Search']
    };

    create_tabs(winId, win_main, tabs, null);

    var iframe_console = $(document.createElement('iframe')).appendTo($('#system_monitor'));
    iframe_console//.attr("name", "vm_console_" + "owncloud")
        .attr("width", "100%")//.attr("height", "100%")
        .attr("id", "admin_system_monitor")
        .attr("style", "height: 300em")
        .attr("src", "https://monitor.thothlab.org/");
    //var winheight = $('#system_monitor').children().height();
    //iframe_console.attr("height", winheight-40);

    var html_log_search = log_search_html();
    $(html_log_search).appendTo($('#log_search'));

    var table = $(document.createElement('table')).appendTo($('#log_search'));
    table.addClass("data").addClass("tablesorter").attr("id", "tbl_es_log_search");
    registerLogSearchTabEventHandlers();

}

function log_search_html() {
    var html =
        "  <div class='es'>" +
        "    <div>" +
        "      <label class='es_first_label'>Index:</label>" +
        "      <span><select id='es_index'></select></span>" +
        "    </div>" +
        "    <div>" +
        "      <label class='es_first_label'>Document Type:</label>" +
        "      <span class='es'><select id='es_document'></select></span>" +
        "    </div>" +
        "    <div class='es_match'>" +
        "      <div class='es_match_row'>" +
        "        <label class='es_first_label'>Match Field:</label>" +
        "        <span class='es'><select class='es_match_row_select'></select></span>" +
        "        <label>Value:</label>" +
        "        <input type='text' name='es_match_row_1_val'>" +
        "        <!--button id='es_match_row_1_add_button'>Add</button-->" +
        "      </div>" +
        "      <div class='es_match_row'>" +
        "        <label class='es_first_label'>Match Field:</label>" +
        "        <span class='es'><select class='es_match_row_select'></select></span>" +
        "        <label>Value:</label>" +
        "        <input type='text' name='es_match_row_2_val'>" +
        "        <!--button id='es_match_row_1_add_button'>Add</button-->" +
        "      </div>" +
        "      <div class='es_match_row'>" +
        "        <label class='es_first_label'>Match Field:</label>" +
        "        <span class='es'><select class='es_match_row_select'></select></span>" +
        "        <label>Value:</label>" +
        "        <input type='text' name='es_match_row_3_val'>" +
        "        <!--button id='es_match_row_1_add_button'>Add</button-->" +
        "      </div>" +
        "    </div>" +
        "    <div id='es_sort'></div>" +
        "    <div id='es_source'></div>" +
        "    <div id='es_filter'>" +
        "      <div class='es_filter_row'>" +
        "        <label class='es_first_label'>Filter Field:</label>" +
        "        <span class='es'><select class='es_filter_row_select'></select></span>" +
        "        <label>After:</label>" +
        "        <input id='datepicker_after'>" +
        "        <label>Before:</label>" +
        "        <input id='datepicker_before'>" +
        "      </div>" +
        "      <button id='es_execute_search'>Search</button>" +
        "    </div>" +
        "  </div>";

    return html;
}


function registerLogSearchTabEventHandlers() {
    // Whenever Index selector is changed
    // Clear DocType selector options and reload based on selected Index
    // Trigger DocType selector change
    $("select#es_index").change(function() {
        $("select#es_document").empty();
        setDocTypes($("#es_index option:selected").text());
        //.forEach(addDocTypeOptions);
    });

    // Whenever DocType selector is changed
    // Clear match field selector's options and reload based on selected DocType
    $("select#es_document").change(function() {
        $("#es_match select").empty();
        $("#es_filter select").empty();
        setDocumentFields($("#es_index option:selected").text(), $("#es_document option:selected").text());
    });

    // Load Index selector values
    // Trigger change on DocType selector
    setIndices();

    $( "#datepicker_before" ).datepicker();
    $( "#datepicker_after" ).datepicker();

    $("#es_execute_search").on("click", searchDocuments);

}

function setIndices() {
    $.get("elasticsearch/getIndices", {},
        function (jsondata) {
            jsondata['response'].forEach(addIndexOptions);
            $("select#es_index option:eq(1)").selected = true;
            $("select#es_index").trigger('change');

        },
        'json'
    );
}

function setDocTypes(index) {
    $.get("elasticsearch/getDocumentTypes/" + index,
        function (jsondata) {
            jsondata['response'].forEach(addDocTypeOptions);
            $("select#es_document option:eq(1)").selected = true;
            $("select#es_document").trigger('change');
        },
        'json'
    );
}

function setDocumentFields(index, docType) {
    $.get("elasticsearch/getDocumentFields/" + index + "/" + docType,
        function (jsondata) {
            for (var prop in jsondata['response']) {
                addDocFieldOptions(prop, jsondata['response'][prop]);
            }
            //$.each(jsondata['response'], addDocFieldOptions(k, jsondata['response'][k]));
            $("#es_filter select option:eq(1)").selected = true;
            $("#es_match select option:eq(1)").selected = true;
        },
        'json'
    )
}

function addIndexOptions(item, index) {
    $("#es_index")
        .append("<option value=" + "\"" + item + "\">" + item + "</option>");
}

function addDocTypeOptions(item, index) {
    $("#es_document")
        .append("<option value=" + "\"" + item + "\">" + item + "</option>");
}

function addDocFieldOptions(field, type) {
    if (type == "date") {
        $("#es_filter select")
            .append("<option value=" + "\"" + field + "\">" + field + "</option>");
    } else if (type == "text") {
        //$("#es_match select")
        $(".es_match_row_select")
            .append("<option value=" + "\"" + field + "\">" + field + "</option>");

    }
}

function searchDocuments() {
    //run_waitMe($('#tbl_es_log_search'), 'ios');
    var searchParams = {};
    searchParams["index"] = $.trim($("select#es_index option:selected").text());
    searchParams["documentType"] = $.trim($("select#es_document option:selected").text());
    //if ($.trim($("input[name=es_match_row_1_val]").val())) {
    //    searchParams["matchValue"] = $("input[name=es_match_row_1_val]").val();
    //    searchParams["matchField"] = $("#es_match_row_1_select option:selected").text();
    //}
    var matches = {};
    $(".es_match_row input").each( function (index, value) {
        var x = $.trim($(this).val());
        if (x) {
            matches[$.trim($(".es_match_row select:eq("+ index +") option:selected").text())] = x;
        }
    });
    searchParams['matches'] = matches;

    var offsetMinutes = new Date().getTimezoneOffset();
    if ($.trim($("#datepicker_after").val())) {
        searchParams["afterTime"] = Date.parse($("#datepicker_after").val()) / 1000 + (offsetMinutes * 60);
    }
    if ($.trim($("#datepicker_before").val())) {
        searchParams["beforeTime"] = Date.parse($("#datepicker_before").val()) / 1000 + (offsetMinutes * 60);
    }
    if (searchParams["beforeTime"] || searchParams["afterTime"]) {
        searchParams["dateField"] = $("#es_filter_row_1_select option:selected").text();
    }
    $.getJSON("elasticsearch/search", searchParams,
        function (jsondata) {
            //$('#tbl_es_log_search').waitMe('hide');

            var table = $('#tbl_es_log_search');
            table.empty();
            if ($.isArray(jsondata['response']) && (jsondata['response'].length > 0))  {
                table.append('<thead><tr>');
                for (var prop in jsondata['response'][0]) {
                    table.append('<th>' + prop + '</th>');
                }
                table.append('</tr></thead>');

                var tbody = $(document.createElement('tbody')).appendTo(table);

                $('#tbl_es_log_search').tablesorter({
                    widthFixed: true,
                    widgets: ['uitheme', 'zebra'],
                    //dateFormat: "mmddyyyy",
                    //sortInitialOrder: "asc",
                    headers: {
                        0: {sorter: false}
                    },
                    textExtraction: {
                        0: function (node) {
                            return $(node).text();
                        },
                        1: function (node) {
                            return $(node).text();
                        }
                    },
                    sortForce: null,
                    sortList: [],
                    sortAppend: null,
                    sortLocaleCompare: false,
                    sortReset: false,
                    sortRestart: false,
                    sortMultiSortKey: "shiftKey",
                    onRenderHeader: function () {
                        $(this).find('span').addClass('headerSpan');
                    },
                    selectorHeaders: 'thead th',
                    cssAsc: "headerSortUp",
                    cssChildRow: "expand-child",
                    cssDesc: "headerSortDown",
                    cssHeader: "header",
                    tableClass: 'tablesorter',
                    widgetColumns: {css: ["primary", "secondary", "tertiary"]},
                    widgetUitheme: {
                        css: [
                            "ui-icon-arrowthick-2-n-s", // Unsorted icon
                            "ui-icon-arrowthick-1-s",   // Sort up (down arrow)
                            "ui-icon-arrowthick-1-n"    // Sort down (up arrow)
                        ]
                    },
                    widgetZebra: {css: ["ui-widget-content", "ui-state-default"]},
                    cancelSelection: true,
                    debug: false
                });
            }
            $.each(jsondata['response'], function (index, item) {
                var row = '<tr>';
                for (var prop in item) {
                    row += '<td>' + item[prop] + '</td>';
                }
                row += '</tr>';
                $(row).appendTo(tbody);
            });

            //$(winId).find('div.window_bottom')
            //    .text(jsondata.length + ' logs)');
            //$(win_main).waitMe('hide');

        },
        'json');

}