@extends('layout.landing-main')

@section('head_css')

@stop
@section('title')
    ThoTh Lab:System Status
@stop
@section('content')
    <div>
        <iframe src="https://monitor.mobicloud.asu.edu/monitor" width="100%" style="height: 100em"></iframe>
    </div>

    <script>
        if (window.top != window.self) {window.top.location = window.self.location;}
    </script>
@stop