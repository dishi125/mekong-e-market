@extends('layouts.app')
@section('title-main',"User Managements")
@section('sub-title',"Details")
@section('content')
    <div class="content">
        <div class="row" style="padding-left: 14px">
            @include('user_profiles.show_fields')
        </div>
    </div>
@endsection
