@extends('layouts.app')
@section('title-main',"Contact Us Managements")
@section('content')

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        @include('contactuses.table')
    </div>
@endsection

