@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            credit setting2
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('credit_setting2.show_fields')
                    <a href="{{ route('credit_setting2.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
