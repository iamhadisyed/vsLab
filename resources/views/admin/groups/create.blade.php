@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Create a Group
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Create a Group
@endsection

@section('contentheader_description')
    Create a New Group
@endsection

@section('main-content')
<div class="main-content">
    <div class='col-lg-12'>
        <div class="panel panel-default">
            <br>
            <div class="alert alert-success">
                <ul>
                    <li>Please fill the data in the required (<span style="color: red">*</span>) field.</li>
                </ul>
            </div>
            @if(count($errors))
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <br/>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('groups.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Group Name:</label>
                        <div class="col-md-8">
                            <input class="form-control" name="name" type="text" placeholder="Alphanumeric without space" required>
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <label class="col-md-3 control-label"><span style="color: red">*</span>Description:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="description" rows="3" style="resize: none;" placeholder="The purpose of your group." required></textarea>
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="isPublic"></label>
                        <div class="col-md-8 checkbox">
                            <input type="checkbox" id="isPublic" name="isPublic" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="request-resources"></label>
                        <div class="col-md-8 checkbox">
                            <input type="checkbox" id="resource_requested" name="resource_requested" />
                        </div>
                    </div>
                    <div id="form-request-resources" style="display: none">
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->has('site_select') ? 'has-error' : '' }}">
                                <label class="col-md-3 control-label"><span style="color: red">*</span>Site:</label>
                                <div class="col-md-3">
                                    <select class="form-control" name="site_select">
                                        <option value="-1">Select a site...</option>
                                        @if (count($sites) > 0)
                                            @foreach ($sites as $site)
                                                <option value="{{ $site->id }}" data-desc="{{ $site->description }}" > {{ $site->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group {{ $errors->has('labs') ? 'has-error' : '' }}">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Number of Labs:</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="labs">
                                            <span class="text-danger">{{ $errors->first('labs') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('vms') ? 'has-error' : '' }}">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Number of VM per Lab:</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="vms">
                                            <span class="text-danger">{{ $errors->first('vms') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('vcpus') ? 'has-error' : '' }}">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Number of vCPU per Lab:</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="vcpus">
                                            <span class="text-danger">{{ $errors->first('vcpus') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group {{ $errors->has('ram') ? 'has-error' : '' }}">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Memory size per Lab (MB):</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="ram">
                                            <span class="text-danger">{{ $errors->first('ram') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('storage') ? 'has-error' : '' }}">
                                        <label class="col-xs-8 control-label"><span style="color: red">*</span>Storage size per Lab (GB):</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="storage">
                                            <span class="text-danger">{{ $errors->first('storage') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('expiration') ? 'has-error' : '' }}">
                                        <label class="col-xs-6 control-label"><span style="color: red">*</span>Use until:</label>
                                        <div class="col-xs-6">
                                            <input class="form-control" id="expiration" name="expiration">
                                            <span class="text-danger">{{ $errors->first('expiration') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-8">
                            <input class="btn btn-primary" value="Create" type="submit">
                            <span></span>
                            <input class="btn btn-default" value="Cancel" onclick="{{ route('groups.index') }}" type="reset">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        $('#expiration').datepicker();

        $('#isPublic').bootstrapSwitch({
            state: true,
            onColor: 'success',
            onText: 'Yes',
            offText: 'No',
            labelText: 'Public Group?',
            labelWidth: 120
        });

        $('#resource_requested').bootstrapSwitch({
            state: false,
            onColor: 'success',
            onText: 'Yes',
            offText: 'No',
            labelText: 'Request Resources?',
            labelWidth: 120,
            onSwitchChange: function (event, state) {
//			       console.log('switch state:' + state);
                if (state) {
                    $('#form-request-resources').show();
//                    $('.alert-success').find('ul').append('<li>The password must contain <ul><li>more than 6 characters</li>' +
//                        '<li>at-least 1 Uppercase</li><li>at-least 1 Lowercase</li><li>at-least 1 Numeric</li>' +
//                        '<li>at-least 1 special character</li></ul></li>');
                } else {
                    $('#form-request-resources').hide();
//                    $('.alert-success').find('ul').find('li:last-child').remove();
                }
//                   event.preventDefault();
            }
        });
    })
</script>
@endsection