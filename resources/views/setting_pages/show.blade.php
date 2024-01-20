@extends('layouts.app')
@section('title-main',"Setting Pages")
@section('content')
    <div class="content">
        <div class="box-body">
            <div class="row" style="padding-left: 20px">
                @include('setting_pages.show_fields')
                <a href="{{ route('settingPages.index') }}" class="btn btn-submit">Back</a>
            </div>
        </div>
    </div>
@endsection
@section('sub-title',"Page Detail View")
