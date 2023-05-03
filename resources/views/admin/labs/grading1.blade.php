@extends('adminlte::layouts.app')

@section('htmlheader_title')
    @if ($role === 0)
        Grading Lab Reports
    @elseif($role === 1)
        View Grading
    @endif

    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    @if ($role === 0)
        Grading Lab Reports
    @elseif($role === 1)
        View Grading
    @endif
@endsection

@section('contentheader_description')

    @if ($role === 0)
        Avaiable Labs for grading
    @elseif($role === 1)
        Please selece a Lab
    @endif
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






        </div>
        <!-- /.box-header -->

        <div class="box-body">

            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
            <div class="panel panel-default">
                <div class="panel-body table-responsive">
                    Class:
                    <select id="classselector" name="classselector">
                        <option value="0">... Select a group ...</option>
                        @foreach($groups->all() as $group)
                            <option value={{ $group->id }} >
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    {{--<select id="classselector" name="classselector" >--}}
                        {{--<option>Please select...</option>--}}
                        {{--<option>Fall2018CSE468</option>--}}


                    {{--</select>--}}
                    Lab:

                    <select id="labselector" name="labselector" >
                        <option value="0">... Select a lab ...</option>
                        @foreach($labs->all() as $lab)
                            <option value={{ $lab->id }} >
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                    {{--<input type="checkbox" id="enabletask" name="enabletask" value="enabletask" > Enable task selection--}}
                    Task:
                    <select id="taskselector" name="taskselector" >
                    <option value="0">... Select a task ...</option>
                        @if(count($tasks) > 0)
                            <option value="5">Show Grades by Lab</option>
                        @endif
                    @foreach($tasks->all() as $task)
                        <option value={{ $task->id }} >
                            {{ $task->name }}
                        </option>
                    @endforeach
                    </select>
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
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername"></a></h4>
                @endif

                <a id="gradingtaskid" style="display: none"></a>
                <a id="gradinguserid" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints" maxlength="3"/> out of <a id="totalpointoftask"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade()">Save</button>
                </div>
            @endif

            <!-- /.box-body -->

        </div>

            <!-- /.box-header -->

            <!-- /.box-body -->



    </section>
</div>
    @if ($role === 0)
        <script src="{{ URL::asset('js/labgrading.js') }}"></script>
    @elseif($role === 1)
        <script src="{{ URL::asset('js/viewgrading.js') }}"></script>
    @endif
{{--<script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>--}}
{{--<script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>--}}
{{--<script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>--}}
{{--<script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>--}}
<script type="text/javascript" nonce="4AEemasdGb0xJptoIGFP3Nd">
    $(document).ready(function(){
        $('#classselector').val( '{{ $id }}' );
        $('#labselector').val( '{{ $labid }}' );
        if('{{ $id }}'=='0'){
            $('.dataTables_empty').html('Please Select one Class and one Lab First.')
        }else if('{{ $labid }}'=='0'){
            $('.dataTables_empty').html('Please Select one Lab First.')
        }

        $('#classselector').change(function() {
            window.location = '/grade/' + $('#classselector').val()+'/0';
        });
        $('#labselector').change(function() {
            window.location = '/grade/' + $('#classselector').val()+'/'+$('#labselector').val();
        });
//        $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
        @if ($role === 0)
            $( ".dt-buttons.btn-group" ).prepend( "<button class=\"btn  btn-default\" onclick=\"gradingpolicy({{ $tasks }},{{ $id }},{{ $labid }})\">Grading Policy</button>&nbsp" );
            $(".buttons-csv").html('Export Grades to CSV File');
        @elseif($role === 1)
            $(".buttons-csv").remove();
             document.getElementById('givenpoints').readOnly = true;
             document.getElementById('taskgradingfeedback').readOnly = true;
        @endif

//        var labs = {
//            'Fall2018CSE468': ['','Iptables Firewall Setup','Secure Web Service','Penetration Test and Vulnerability Exploration'],
//
//        };
//        var tasks = {
//            'Iptables Firewall Setup': ['','task1','task2','task3'],
//            'Secure Web Service': ['','task1','task2'],
//            'Penetration Test and Vulnerability Exploration':['','task1','task2']
//        };


//        $('#classselector').change(function () {
//
//            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
//
//            var classselector = $(this).val(), lcns = labs[classselector] || [];
//
//            var html = $.map(lcns, function(lcn){
//                return '<option value="' + lcn + '">' + lcn + '</option>'
//            }).join('');
//            $('#labselector').html(html)
//        });
//        $('#labselector').change(function () {
//            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
//            $('#dataTableBuilder').DataTable().columns(6).search($('#labselector').val()).draw();
//            var labselector = $(this).val(), lcns = tasks[labselector] || [];
//
//            var html = $.map(lcns, function(lcn){
//                return '<option value="' + lcn + '">' + lcn + '</option>'
//            }).join('');
//            $('#taskselector').html(html)
//        });
        $('#taskselector').change(function () {
            var url = window.location.href;
            if($('#taskselector option:selected')[0].value==0){

            }else if($('#taskselector option:selected')[0].value==5){
                if(url.substring(window.location.href.lastIndexOf('/') + 1)=='all'){

                }else{
                    window.location.href = url+'/all';
                }
            }else if($('#taskselector option:selected')[0].value==6){

                    window.location.href = './';

            }else{
                $('#dataTableBuilder').DataTable().columns(8).search($('#taskselector option:selected')[0].text).draw();
            }
//            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
//            $('#dataTableBuilder').DataTable().columns(6).search($('#labselector').val()).draw();


        });



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