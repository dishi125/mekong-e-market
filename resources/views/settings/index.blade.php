@extends('layouts.app')
@section('title-main',"Settings")
@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>


        @if(isset($edit))

            @include($view.'.edit')
        @else
            @include($view.'.create')
        @endif
        <div class="clearfix"></div>
    </div>
@endsection
@section('sub-title',"Add Settings")

