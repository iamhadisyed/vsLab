@extends('adminlte::layouts.app')

@section('htmlheader_title')
    My Sites
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    My Sites
@endsection

@section('contentheader_description')
    Resource Utilization in My Sites
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-mysite-group-default-rss" data-backdrop="static">
        <div class="modal-dialog" style="width: 650px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Site Settings - <span id="site-name-default-rss"></span>
                        <span id="site-id-default-rss" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Default Resource Allocation for Each Group</legend>
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of Labs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-default-rss-labs">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of VMs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-default-rss-vms">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of vCPU:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-default-rss-vcpus">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Memory Size (MB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-default-rss-ram">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Storage Size (GB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-default-rss-storage">
                                        </div>
                                    </div>
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-xs-6 control-label"><strong>Use until:</strong></label>--}}
                                        {{--<div class="col-xs-6">--}}
                                            {{--<input class="form-control" id="group-rss-expiration" name="group-rss-expiration">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="mysite_group_default_rss($(this))"><i class="fa fa-check"></i> Save</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script src="{{ URL::asset('js/mysite-management.js') }}"></script>
    <script>

    </script>
@endsection