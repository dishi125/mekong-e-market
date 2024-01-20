@extends('layouts.app')
@section('title-main',"Subscription")
@section('content')
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        @include('subscriptions.listtable')
        
        <div class="clearfix"></div>
    </div>
@endsection

