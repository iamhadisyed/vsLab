@extends('adminlte::layouts.app')

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            @foreach($TAs as $TA)

                <H3>
                    taskid(for reference):{{ $TA->task_id }}
                </H3>
                <div>
                    title:{{ $TA->title }}
                </div>

                <div>
                    image:<img src="{{ $TA->submission }}"/>
                </div>
                <div>description:{{ $TA->desc }}</div>
                <div>
                    taken from:{{ $TA->source }}
                </div>
        @endforeach
            <!-- /.box -->
        </div>

        {{--<div class="col-md-4">--}}
            {{--<div class="box box-success box-solid">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">Your Team Members</h3>--}}
                    {{--<div class="box-tools pull-right">--}}
                        {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                    {{--<!-- /.box-tools -->--}}
                {{--</div>--}}
                {{--<!-- /.box-header -->--}}
                {{--<div class="box-body">--}}
                    {{--<span class="info-box-text">Announcements</span>--}}
                {{--</div>--}}
                {{--<!-- /.box-body -->--}}
            {{--</div>--}}
            {{--<!-- /.box -->--}}
        {{--</div>--}}
    </div>

@endsection


@section('javascript')

@endsection