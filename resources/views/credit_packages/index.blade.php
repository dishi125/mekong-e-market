@extends('layouts.app')
@section('title-main',"Credit Managements")
@section('sub-title',"Create Credit Package")
@section('content')
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        @if(isset($edit))

            @include($view.'.edit')
        @else
            @include($view.'.create')
        @endif
        <div class="clearfix"></div>
    </div>
@endsection

