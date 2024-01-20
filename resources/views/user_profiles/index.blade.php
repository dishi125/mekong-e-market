@extends('layouts.app')
@section('title-main',"User Managements")
@section('content')

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

            @include('user_profiles.table')
        </div>
@endsection

