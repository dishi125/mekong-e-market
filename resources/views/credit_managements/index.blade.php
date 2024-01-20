@extends('layouts.app')

@section('title-main',"Credit Managements")
@section('content')

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        @include('credit_managements.table')
    </div>
@endsection

