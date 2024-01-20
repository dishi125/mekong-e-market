@extends('layouts.app')
@section('title-main',"Product Managements")
@section('content')
    <section class="content-header">
        @include('flash::message')
        <div class="row">
            <div class="col-md-4">
                <div class="tab-buttons">
                    <a href="{{ route('live.trade') }}"
                       class="btn  btn-lg btn-tab {{ 'live'==$view ? 'active' : ''}}">
                        Live Trade
                    </a>
                    <a href="{{ route('upcoming.trade') }}"
                       class="btn  btn-lg btn-tab {{ 'upcoming'==$view ? 'active' : ''}}">
                        Upcoming Trade
                    </a>
                    <a href="{{ route('ended.trade') }}"
                       class="btn  btn-lg btn-tab {{ 'ended'==$view ? 'active' : ''}}">
                        Ended Trade
                    </a>

                </div>
            </div>
            <div class="col-md-8">
                {{Form::open(['route'=>'frame'])}}
                <div class="row" style="margin-top: 13px;">
                    <div class="col-sm-4">
                        <div class="col-sm-5">
                            {!! Form::label('frame', 'Time Frame ') !!}
                        </div>
                        @php
                            $frames = \App\Helpers\CommonHelper::frameCreation();
                        @endphp
                        <div class="col-sm-7 form-group select-box">
                            <select name="frame" id="frame" class="form-control">
                                @foreach($frames as $singleFrame)
                                    @php
                                        $selected = '';
                                        if($f->frame == $singleFrame['frame']){
                                            $selected = 'selected';
                                        }
                                    @endphp
                                    <option value="{{$singleFrame['frame']}}" {{$selected}}>{{$singleFrame['show_time']}}</option>
                                @endforeach
                            </select>
                            <i class="fa fa-caret-right"></i>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-4">
                            {!! Form::label('repost', 'Repost') !!}
                        </div>
                        <div class="col-sm-7 form-group">
                            {!! Form::number('repost', ($f->repost) ? $f->repost : null, ['class' => 'form-control',"placeholder"=>"Repost"]) !!}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        {!! Form::submit('set', ['class' => 'btn btn-submit']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="col-sm-5">
                            {!! Form::label('creditcard', 'Creditcard(%)') !!}
                        </div>
                        <div class="col-sm-7 form-group">
                            {!! Form::text('creditcard', ($f->creditcard) ? $f->creditcard : null, ['class' => 'form-control',"placeholder"=>"Creditcard"]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-4">
                            {!! Form::label('fpx', 'FPX(RM)') !!}
                        </div>
                        <div class="col-sm-7 form-group">
                            {!! Form::text('fpx', ($f->fpx) ? $f->fpx : null, ['class' => 'form-control',"placeholder"=>"FPX"]) !!}
                        </div>
                    </div>
                </div>
                {{Form::close()}}
                <br>
            </div>
        </div>

    </section>
    <div class="clearfix"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('products.'.$view.'table')
            </div>
        </div>
    </div>
@endsection
