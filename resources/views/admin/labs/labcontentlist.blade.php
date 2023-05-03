@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Labs
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Labs
@endsection

@section('contentheader_description')
    Avaiable Labs
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
    <section class="col-lg-9 connectedSortable ui-sortable">

        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox">
            <div class="box-header ui-sortable-handle" >
                <h3>Task:
                    <select id="taskselector" name="taskselector" >
                        <option>Task 1</option>
                        <option>Task 2</option>
                        <option>Task 3</option>


                    </select>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="tobegrading">
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->

            </div>
            <!-- /.box-body -->

        </div>
    </section>

    <script src="{{ URL::asset('js/labgrading.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
//            $( ".dt-buttons.btn-group" ).prepend( "<button class=\"btn  btn-default\"><a href=\"editor\">Create New Lab Content</a></button>&nbsp" );
            $('div.main-toolbar').html('<span style="margin-left: 20px;">' +
                '<button type="button" class="btn btn-primary" style="margin-left: 5px" ' +
                'onclick="window.location = \'/editor\' ">Create New Lab Content</button>');
            $('#taskselector').change(function () {
                if($('#taskselector').val()=='Task 1') {
                    viewresult(614, 101);
                }else if($('#taskselector').val()=='Task 2'){
                    viewresult(614, 102);
                }else if($('#taskselector').val()=='Task 3'){
                    viewresult(614, 103);
                }
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