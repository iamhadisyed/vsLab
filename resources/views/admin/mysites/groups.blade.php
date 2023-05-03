@extends('adminlte::layouts.app')

@section('htmlheader_title')
    My Site's Groups
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    My Site's Groups
@endsection

@section('contentheader_description')
    Resource Utilization in My Site's Groups
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="panel panel-default">
            <div class="panel-head table-responsive" style="margin-top: 15px">
                <div class="form-group">
                    <label for="site_selector" class="col-xs-1 control-label">Sites: </label>
                    <div class="col-xs-3">
                        <select id="site_selector" class="form-control">
                            <option value="0">... Select a site ...</option>
                            @foreach($sites->all() as $site)
                                <option value={{ $site->id }} >
                                    {{ $site->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <br><br>
                </div>
            </div>
            <div class="panel-body table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal modal-default fade" id="modal-mysite-group-rss" data-backdrop="static">
        <div class="modal-dialog" style="width: 650px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-group"></i> Manage Resources for Group <span id="group-name-rss"></span>
                        <span id="group-id-rss" style="display: none"></span></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Group Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Group Name:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="group-rss-name" type="text" readonly style="border: 0; background-color: transparent;" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Description:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="group-rss-description" readonly style="border: 0; background-color: transparent;" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Administrators:</strong></label>
                                <div class="col-md-8">
                                    <div class="form-control" id="group-rss-admins" style="border: 0; background-color: transparent;"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Resource Request:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="group-rss-requested" type="text" readonly style="border: 0; background-color: transparent;" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>Status:</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" id="group-rss-status" type="text" readonly style="border: 0; background-color: transparent;" />
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-border">
                            <legend class="form-border" style="margin-bottom: 5px;">Resource Allocation</legend>
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of Labs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-labs">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of VMs:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-vms">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Number of vCPU:</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-vcpus">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Memory Size (MB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-ram">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-8 control-label"><strong>Storage Size (GB):</strong></label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="group-rss-storage">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-6 control-label"><strong>Use until:</strong></label>
                                        <div class="col-xs-6">
                                            <input class="form-control" id="group-rss-expiration" name="group-rss-expiration">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="mysite_group_resource($(this))"><i class="fa fa-check"></i> Allocate</button>
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
        $(document).ready(function () {
            $('#site_selector').val('{{ $id }}');

            $('#site_selector').change(function () {
                window.location = '/mysites/groups/' + $('#site_selector').val();
            });

            $('#group-rss-expiration').datepicker();
        });
    </script>
@endsection