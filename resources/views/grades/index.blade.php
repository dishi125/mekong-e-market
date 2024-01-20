@extends('layouts.app')
@section('title-main',"Grade & Weight-Unit")
@section('content')
    <section class="content-header">
        <div class="tab-buttons">
            <a href="{{ route('grades.index') }}" class="btn  btn-lg btn-tab {{ Request::is('grades*') ? 'active' : ''}}">
                Grade
            </a>
            <a href="{{ route('weightUnits.index') }}"  class="btn  btn-lg btn-tab {{ Request::is('weightUnits*') ? 'active' : ''}}">
                Weight Unit
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
