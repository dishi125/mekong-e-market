@extends('layouts.app')
@section('title-main',"State & Area")
@section('content')
    <section class="content-header">
        <div class="tab-buttons">
            <a href="{{ route('states.index') }}" class="btn  btn-lg btn-tab {{ Request::is('states*') ? 'active' : ''}}">
                State
            </a>
            <a href="{{ route('areas.index') }}"  class="btn  btn-lg btn-tab {{ Request::is('areas*') ? 'active' : ''}}">
                Area
            </a>

        </div>
    </section>

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
