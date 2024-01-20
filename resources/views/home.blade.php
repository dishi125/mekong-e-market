@extends('layouts.app')

@section('title-main',"Dashboard")
@section('css')
    <style>
        .col-md-20 {
            width: 18.5%;
            display: inline-block;
            padding: 10px;
        }

        .block-home {
            height: 130px;
            text-align: center;
            padding: 20px 10px;
            color: white;
            text-transform: capitalize;
        }

        .block-home p {
            line-height: .8;
            font-weight: bold;
            font-size: 18px;
        }

        .block-home h2 {
            font-weight: bold;
            font-size: 26px;
        }

        .top-row .block-home {
            background: #818286;
        }

        .seconds-row .block-home {
            background: #128442;
        }

        .thead-row .block-home {
            background: #14bd54;
        }
    </style>
@endsection
@section('content')

    <div class="container-fluid">
        <div class="col-md-12 top-row">

            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>User</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_user)}}</h2>
{{--                    <h2>{{$tot_user==0 ? 0 : (($tot_user%1000000==0) ? ($tot_user/1000000).'M' : ($tot_user%1000==0 ? ($tot_user/1000).'k' : number_format($tot_user)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Retailer</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_retailer)}}</h2>
                    {{--                    <h2>{{$tot_retailer==0 ? 0 : (($tot_retailer%1000000==0) ? ($tot_retailer/1000000).'M' : ($tot_retailer%1000==0 ? ($tot_retailer/1000).'k' : number_format($tot_retailer)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Wholesaler</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_wholesaler)}}</h2>
                    {{--                    <h2>{{$tot_wholesaler==0 ? 0 : (($tot_wholesaler%1000000==0) ? ($tot_wholesaler/1000000).'M' : ($tot_wholesaler%1000==0 ? ($tot_wholesaler/1000).'k' : number_format($tot_wholesaler)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Farmer</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_farmer)}}</h2>
{{--                    <h2>{{$tot_farmer==0 ? 0 : (($tot_farmer%1000000==0) ? ($tot_farmer/1000000).'M' : ($tot_farmer%1000==0 ? ($tot_farmer/1000).'k' : number_format($tot_farmer)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Buyer</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_buyer)}}</h2>
{{--                    <h2>{{$tot_buyer==0 ? 0 : (($tot_buyer%1000000==0) ? ($tot_buyer/1000000).'M' : ($tot_buyer%1000==0 ? ($tot_buyer/1000).'k' : number_format($tot_buyer)))}}</h2>--}}

                </div>
            </div>
        </div>
        <div class="col-md-12 seconds-row">
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Product Listing</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_product)}}</h2>
{{--                    <h2>{{$tot_product==0 ? 0 : (($tot_product%1000000==0) ? ($tot_product/1000000).'M' : ($tot_product%1000==0 ? ($tot_product/1000).'k' : number_format($tot_product)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Live Trade</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_livetrade)}}</h2>
{{--                    <h2>{{$tot_livetrade==0 ? 0 : (($tot_livetrade%1000000==0) ? ($tot_livetrade/1000000).'M' : ($tot_livetrade%1000==0 ? ($tot_livetrade/1000).'k' : number_format($tot_livetrade)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Upcoming Trade</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_upcomingtrade)}}</h2>
{{--                    <h2>{{$tot_upcomingtrade==0 ? 0 : (($tot_upcomingtrade%1000000==0) ? ($tot_upcomingtrade/1000000).'M' : ($tot_upcomingtrade%1000==0 ? ($tot_upcomingtrade/1000).'k' : number_format($tot_upcomingtrade)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Ended Trade</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_endedtrade)}}</h2>
{{--                    <h2>{{$tot_endedtrade==0 ? 0 : (($tot_endedtrade%1000000==0) ? ($tot_endedtrade/1000000).'M' : ($tot_endedtrade%1000==0 ? ($tot_endedtrade/1000).'k' : number_format($tot_endedtrade)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20 ">
                <div class="block-home ">

                    <p>Total</p>
                    <p>Successful Deal</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_success_deal)}}</h2>
{{--                    <h2>{{$tot_success_deal==0 ? 0 : (($tot_success_deal%1000000==0) ? ($tot_success_deal/1000000).'M' : ($tot_success_deal%1000==0 ? ($tot_success_deal/1000).'k' : number_format($tot_success_deal)))}}</h2>--}}

                </div>
            </div>
        </div>
        <div class="col-md-12 thead-row">
            <div class="col-md-20 ">
                <div class="block-home">
                    <p>Total</p>
                    <p>Top Up</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_topup)}}</h2>
{{--                    <h2>{{$tot_topup==0 ? 0 : (($tot_topup%1000000==0) ? ($tot_topup/1000000).'M' : ($tot_topup%1000==0 ? ($tot_topup/1000).'k' : number_format($tot_topup)))}}</h2>--}}

                </div>
            </div>
            <div class="col-md-20">
                <div class="block-home">
                    <p>---</p>
                    <p>--</p>
                    <h2>0</h2>
                </div>
            </div>
            <div class="col-md-20">
                <div class="block-home">
                    <p>---</p>
                    <p>--</p>
                    <h2>0</h2>
                </div>
            </div>
            <div class="col-md-20">
                <div class="block-home">
                    <p>---</p>
                    <p>--</p>
                    <h2>0</h2>
                </div>
            </div>
            <div class="col-md-20">
                <div class="block-home">
                    <p>Total Sales</p>
                    <p>(MYR)</p>
                    <h2>{{\App\Helpers\CommonHelper::number_format_short($tot_sales)}}</h2>
{{--                    <h2>{{$tot_sales==0 ? 0 : (($tot_sales%1000000==0) ? ($tot_sales/1000000).'M' : ($tot_sales%1000==0 ? ($tot_sales/1000).'k' : number_format($tot_sales)))}}</h2>--}}

                </div>
            </div>
        </div>
    </div>
@endsection
