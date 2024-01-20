@extends('layouts.app')
@section('title-main',"User Managements")
@section('content')
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')
        @section('sub-title',"Preferred Requests")

        @include('user_profiles.preferredreq_listtable')

        <div class="clearfix"></div>
    </div>
@endsection

