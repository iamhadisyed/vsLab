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
    <div class="modal modal-default fade" id="modal-view-lab" data-backdrop="static">
        <div class="modal-dialog" style="width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> View Test Environment <span class="view-lab-team-name"></span>-
                        <span class="view-lab-name"></span></h4>
                </div>
                <div class="modal-body row">
                    <div class="container-fluid" id="lab-environment">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>
    <script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>
    <script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>
    <script src="{{ URL::asset('js/labgrading.js') }}"></script>
    <script src="{{ URL::asset('js/vis-canvas-design.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
//            $( ".dt-buttons.btn-group" ).prepend( "<button class=\"btn  btn-default\" onclick='window.location(\"\labenvdesign\")'>Create New Lab Environment</button>&nbsp" );
            $('div.main-toolbar').html('<span style="margin-left: 20px;">' +
                '<button type="button" class="btn btn-primary" style="margin-left: 5px" ' +
                'onclick="window.location = \'/labenvdesign\' ">Create New Lab Environment</button>');
            $('#taskselector').change(function () {
                if($('#taskselector').val()=='Task 1') {
                    viewresult(614, 101);
                }else if($('#taskselector').val()=='Task 2'){
                    viewresult(614, 102);
                }else if($('#taskselector').val()=='Task 3'){
                    viewresult(614, 103);
                }
            });

            $('.buttons-reload').click(function () {
                update_labs_status_in_labenv();
            });

            update_labs_status_in_labenv();
        });

    </script>
@endsection