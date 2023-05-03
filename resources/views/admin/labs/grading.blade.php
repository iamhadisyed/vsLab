@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Grading Lab Reports
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Grading Lab Reports
@endsection

@section('contentheader_description')
    Avaiable Labs for grading
@endsection

@section('main-content')
{{--<table style="width: 100%; border: 1px solid lightblue;">--}}
    {{--<tr style="width: 100%; border: 1px solid lightblue;">--}}
        {{--<td >--}}
            {{--Row 1; Col 1;--}}
        {{--</td>--}}
        {{--<td>--}}
            {{--Row 1; Col 2;--}}
        {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td>--}}
            {{--Row 2; Col 1;--}}
        {{--</td>--}}
        {{--<td>--}}
            {{--Row 2; Col 2;--}}
        {{--</td>--}}
    {{--</tr>--}}
{{--</table>--}}
<div id="upperpage">
    <section class="col-md-12 container-fluid">
        <div class="box-header ui-sortable-handle" >


            <div id="body">
                <div id="chart"></div>
            </div>




        </div>
        <!-- /.box-header -->

        <div class="box-body">

            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
            <div class="panel panel-default">
                <div class="panel-body table-responsive">
                    Class:
                    <select id="classselector" name="classselector" >
                        <option>Please select...</option>
                        <option>Fall2018CSE468</option>


                    </select>
                    Lab:
                    <select id="labselector" name="labselector" ></select>
                    {{--<input type="checkbox" id="enabletask" name="enabletask" value="enabletask" > Enable task selection--}}
                    Task:
                    <select id="taskselector" name="taskselector" ></select>


                    <br/>
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </section>
</div>
<div id="lowerpage">
    <section class="col-lg-9 connectedSortable ui-sortable">
        <!-- Custom tabs (Charts with tabs)-->
        {{--<div class="box box-primary" style="position: relative; left: 0px; top: 0px;">--}}
            {{--<div class="box-header ui-sortable-handle" >--}}


                {{--<h3 class="box-title">Available Classes</h3>--}}


            {{--</div>--}}
            {{--<!-- /.box-header -->--}}
            {{--<div class="box-body">--}}
                {{--<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->--}}
                {{--<ul data-widget="tree">--}}
                    {{--<li class="treeview">--}}
                        {{--<a href="#">--}}
                            {{--<span>CSE 468</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                            {{--<li class="treeview">--}}
                                {{--<a href="#">--}}
                                    {{--<span><i class="fa fa-circle-o"></i> Lab 2 <input style="width: 60px" type="text" id="screenshottitle" maxlength="3"/></span>--}}
                                {{--</a>--}}
                                {{--<ul class="treeview-menu">--}}
                                    {{--<li>--}}
                                        {{--<a href="#">--}}
                                            {{--<span><i class="fa fa-circle-o"></i> Task 1 <input style="width: 60px" value="10" type="text" id="screenshottitle" maxlength="3"/></span>--}}
                                        {{--</a>--}}

                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
            {{--<!-- /.box-body -->--}}

        {{--</div>--}}

        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox">
            <div class="box-header ui-sortable-handle" >


            </div>
            <!-- /.box-header -->
            <div class="box-body" id="tobegrading">
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->

            </div>
            <!-- /.box-body -->

        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >


                <h3 class="box-title">Submission Grading</h3>


            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <h4>Username: <a id="gradingusername"></a></h4>
                <a id="gradingtaskid" style="display: none"></a>
                <a id="gradinguserid" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints" maxlength="3"/> out of <a id="totalpointoftask"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback"  rows="2"></textarea>

            </div>
            <div class="box-footer ">
                <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade()">Save</button>
            </div>
            <!-- /.box-body -->

        </div>

            <!-- /.box-header -->

            <!-- /.box-body -->



    </section>
</div>
    <script src="{{ URL::asset('js/labgrading.js') }}"></script>
<script src="https://d3js.org/d3.v3.min.js"></script>
<script src="{{ URL::asset('js/RadarChart.js') }}"></script>
<script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>
<script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
<script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>
<script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
        $( ".dt-buttons.btn-group" ).prepend( "<button class=\"btn  btn-default\" onclick=\"gradingpolicy()\">Grading Policy</button>&nbsp" );
        $(".buttons-csv").html('Export Grades to CSV File');
        var labs = {
            'Fall2018CSE468': ['','Iptables Firewall Setup','Secure Web Service','Penetration Test and Vulnerability Exploration'],

        };
        var tasks = {
            'Iptables Firewall Setup': ['','task1','task2','task3'],
            'Secure Web Service': ['','task1','task2'],
            'Penetration Test and Vulnerability Exploration':['','task1','task2']
        };


        $('#classselector').change(function () {

            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();

            var classselector = $(this).val(), lcns = labs[classselector] || [];

            var html = $.map(lcns, function(lcn){
                return '<option value="' + lcn + '">' + lcn + '</option>'
            }).join('');
            $('#labselector').html(html)
        });
        $('#labselector').change(function () {
            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
            $('#dataTableBuilder').DataTable().columns(6).search($('#labselector').val()).draw();
            var labselector = $(this).val(), lcns = tasks[labselector] || [];

            var html = $.map(lcns, function(lcn){
                return '<option value="' + lcn + '">' + lcn + '</option>'
            }).join('');
            $('#taskselector').html(html)
        });
        $('#taskselector').change(function () {
            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
            $('#dataTableBuilder').DataTable().columns(6).search($('#labselector').val()).draw();
            $('#dataTableBuilder').DataTable().columns(7).search($('#taskselector').val()).draw();

        });
        var w = 200,
            h = 200;

        var colorscale = d3.scale.category10();

//Legend titles


//Data
        var d = [
            [
                {axis:"Email",value:4},
                {axis:"Social Networks",value:3},
                {axis:"Internet Banking",value:2},
                {axis:"News Sportsites",value:1},
                {axis:"Search Engine",value:5}
            ]
        ];

//Options for the Radar chart, other than default
        var mycfg = {
            w: w,
            h: h,
            maxValue: 6,
            levels: 6,
            ExtraWidthX: 300
        }

//Call function to draw the Radar chart
//Will expect that data is in %'s
        RadarChart.draw("#chart", d, mycfg);

////////////////////////////////////////////
/////////// Initiate legend ////////////////
////////////////////////////////////////////

        var svg = d3.select('#body')
            .selectAll('svg')
            .append('svg')
            .attr("width", w+300)
            .attr("height", h)

//Create the title for the legend
        var text = svg.append("text")
            .attr("class", "title")
            .attr('transform', 'translate(90,0)')
            .attr("x", w - 70)
            .attr("y", 10)
            .attr("font-size", "12px")
            .attr("fill", "#404040")
            .text("What % of owners use a specific service in a week");

//Initiate Legend
        var legend = svg.append("g")
            .attr("class", "legend")
            .attr("height", 100)
            .attr("width", 200)
            .attr('transform', 'translate(90,20)')
        ;
        //Create colour squares
        legend.selectAll('rect')
            .data(LegendOptions)
            .enter()
            .append("rect")
            .attr("x", w - 65)
            .attr("y", function(d, i){ return i * 20;})
            .attr("width", 10)
            .attr("height", 10)
            .style("fill", function(d, i){ return colorscale(i);})
        ;
        //Create text next to squares
        legend.selectAll('text')
            .data(LegendOptions)
            .enter()
            .append("text")
            .attr("x", w - 52)
            .attr("y", function(d, i){ return i * 20 + 9;})
            .attr("font-size", "11px")
            .attr("fill", "#737373")
            .text(function(d) { return d; })
        ;


    });

</script>
@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script>
        {{--window.route_mass_crud_entries_destroy = '{{ route('admin.roles.mass_destroy') }}';--}}
    </script>
@endsection