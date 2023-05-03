@extends('adminlte::layouts.app')

@section('htmlheader_title')
    OAuth Settings
@endsection

@section('contentheader_title')
    OAuth Settings
@endsection

@section('contentheader_description')
    Manage Token for OAuth Authentication
@endsection

@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <passport-authorized-clients></passport-authorized-clients>
                <passport-clients></passport-clients>
                <passport-personal-access-tokens></passport-personal-access-tokens>
            </div>
        </div>
    </div>
@endsection