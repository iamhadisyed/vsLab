@extends('adminlte::layouts.app')

{{--@section('head_css')--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('packages/vis/dist/vis.css') }}" />--}}
{{--@endsection--}}

@section('htmlheader_title')
    Workspace
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    User Timeline
@endsection

@section('contentheader_description')

@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">

            <div id="labenv" class="col-md-12 resizable">
                <div class="box box-solid" id="lab-environment">

                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#lab-env-topology-tab" data-toggle="tab" >Timeline</a></li>
                    </ul>
                    <div class="tab-content">
                        <div>
                            {{--<h3>A stacked timeline with hover, click, and scroll events</h3>--}}
                            <h3 id="startandend"></h3>
                            <div class="form-group">From <input type="date" id="timelinestarttime"> To <input type="date"  id="timelineendtime"> <button onclick="loadusertimeline({{ $userid }});">Reload</button></div>
                            <div id="timeline3"></div>
                            <div id="hoverRes">
                                <div class="coloredDiv"></div>
                                <div id="name"></div>
                                <div id="scrolled_date"></div>
                                <br/>
                                <H4 id="totaltime"></H4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-default fade" id="modal-update-labs" data-backdrop="static">
        <div class="modal-dialog" style="width: 370px;">
            <div class="modal-content">

            </div>
        </div>
    </div>




@endsection


@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.16/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-tip/0.6.7/d3-tip.min.js"></script>
    <script src="{{ URL::asset('js/d3-timeline.js') }}"></script>
    <script src="{{ URL::asset('js/timeline.js') }}"></script>
    <style type="text/css">
        .axis path,
        .axis line {
            fill: none;
            stroke: black;
            shape-rendering: crispEdges;
        }

        .axis text {
            font-family: sans-serif;
            font-size: 10px;
        }

        .timeline-label {
            font-family: sans-serif;
            font-size: 12px;
        }

        #timeline2 .axis {
            transform: translate(0px,40px);
            -ms-transform: translate(0px,40px); /* IE 9 */
            -webkit-transform: translate(0px,40px); /* Safari and Chrome */
            -o-transform: translate(0px,40px); /* Opera */
            -moz-transform: translate(0px,40px); /* Firefox */
        }

        .coloredDiv {
            height:20px; width:20px; float:left;
        }
    </style>
    <script type="text/javascript">
        window.onload = function() {
            loadusertimeline({{ $userid }});
        }

    </script>
@endsection