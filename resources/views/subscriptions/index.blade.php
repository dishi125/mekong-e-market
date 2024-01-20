@extends('layouts.app')
@section('title-main',"Subscription")
@section('content')
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        @if(isset($edit))

            @include($view.'.edit')
            @section('sub-title',"Add Package")
        @else
            @include($view.'.create')
            @section('sub-title',"Add Package")
        @endif
        <div class="clearfix"></div>
    </div>
@endsection

