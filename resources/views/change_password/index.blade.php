@extends('layouts.app')
@section('title-main',"User Settings")
@section('css')
    <style>
        .has-error
        {
            border-color: red;
        }
        .has-error input
        {
            box-shadow: 0px 0px 2px red !important;
        }
        .help-block
        {
            margin-left: 17px;
        }
    </style>
    @endsection
@section('content')
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')
        @include('change_password.create')
        <div class="clearfix"></div>
    </div>
@endsection

