(function(window,$){
    window.LaravelDataTables = window.LaravelDataTables||{};
    window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable(
        {
            "serverSide":true,
            "processing":true,
            "ajax": {
                "url":"",
                "type":"GET",
                "data":function(data) {
                    for (var i = 0, len = data.columns.length; i < len; i++) {
                        if (!data.columns[i].search.value) delete data.columns[i].search;
                        if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                        if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                        if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                    }
                    delete data.search.regex;
                }
            },
            "columns":[
                {
                    "name":"id",
                    "data":"id",
                    "title":"Id",
                    "orderable":true,
                    "searchable":true
                },
                {
                    "name":"name",
                    "data":"name",
                    "title":"Name",
                    "orderable":true,
                    "searchable":true
                },
                {
                    "name":"description",
                    "data":"description",
                    "title":"Description",
                    "orderable":true,
                    "searchable":true
                },
                {
                    "name":"type",
                    "data":"type",
                    "title":"Type",
                    "orderable":true,
                    "searchable":true
                },
                {
                    "name":"created_at",
                    "data":"created_at",
                    "title":"Created At",
                    "orderable":true,
                    "searchable":true
                },
                {
                    "name":"updated_at",
                    "data":"updated_at",
                    "title":"Updated At",
                    "orderable":true,
                    "searchable":true
                },
                {
                    "defaultContent":"",
                    "data":"action",
                    "name":"action",
                    "title":"Action",
                    "render":null,
                    "orderable":false,
                    "searchable":false,
                    "width":"100%"
                }
            ],
            "dom":"Bfrtip",
            "order":[[0,"desc"]],
            "buttons":["create","export","print","reset","reload"]
        });
})(window,jQuery);
