@extends('layouts.app')
@section('title-main',"Product Managements")
@section('sub-title',"Detail")
@section('content')
    <style>
        .carousel .product-image-count, .carousel .product-stop-pause {
            position: absolute;
            font-size: 16px;
            color: white;
            font-weight: normal;
        }

        .product-image-count {
            bottom: 10px;
            right: 20px;
        }

        .product-stop-pause {
            top: 12px;
            right: 20px;
        }

        .preferred {
            vertical-align: middle;
        }

        .preferred:before {
            text-transform: uppercase;
            content: 'preferred';
            width: 70px;
            padding: 7px;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            background: linear-gradient(to right, #3aba1b, #4a9d44);
            color: #fff;
            font-size: 12px;

        }

        .product-description .fa {
            min-width: 40px;
        }

        .flip-clock-label {
            display: none;
        }

        .tableheader {
            background: #2C2A2B;
            color: white;
            font-weight: bold;
            padding: 5px 0px;
            border-radius: 10px 10px 0px 0px;
        }

        .drop-price-connect {
            padding: 3px 0px;
        }

        .underline-table {
            position: relative;
        }

        .underline-table:after {
            width: 90%;
            content: '';
            position: absolute;
            border-bottom: 1px solid #2C2A2B;
            bottom: 0;
            left: 5%;
        }


        .deatil-price-drop .last-drop, .color-green, .product-description .fa {
            color: green;
        }

        .deatil-price-drop .last-drop {
            font-weight: bold;
        }

        .deatil-price-drop {
            border: #2C2A2B solid 1px;
            border-radius: 0px 0px 10px 10px;
        }

        .deatil-price-drop .last-drop .fa {
            border-radius: 50%;
            border: 1px solid green;
            line-height: 0.6;
            font-size: 22px
        }

        .buyer-image {
            position: relative;
        }

        .buyer-image .reward-image {
            position: absolute;
            right: -20px;
            bottom: 10%;
            font-weight: bold;
        }

        .clock-builder-output {
            font-size: 18px;
            letter-spacing: 1.1px;
        }

        .clock-builder-output span {
            background: #2C2A2B;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 5px;
        }

        .rating-block .fa-ban
        {
            color: #00A652;
            font-size: 18px;
            margin-top: 40px;

        }
        .rating-block
        {
            padding: 7px;
            text-transform: capitalize;
            border: 1px solid green;
        }

        .seller-details .col-md-12 .col-md-12{
            padding: 0px;
        }

        .seller-details .rating, .seller-details .rating .rateyo{
            padding: 0px;
        }

        .reviews-user h5 {
            padding-left: 15px;
        }

        .seller-details .user-name-display{
            margin-bottom: unset;
        }

        h3{
            font-weight: bold;
        }

        h5, .end-in {
            color: #808080;
        }

        .tableheader , .deatil-price-drop{
            font-weight: bold;
        }

        .deatil-price-drop {
            color: #565555;
        }

        .padding-0{
            padding: 0px;
        }

        .deleted_at{
            text-transform: uppercase;
            width: 110px;
            padding: 9px;
            vertical-align: middle;
            text-align: center;
            background: linear-gradient(to right, #f02929, #a80808);
            color: #fff;
            font-size: 12px;
            margin: 0;
        }

        .buyer-detail-div.col-md-3 {
            width: 20%;
        }
    </style>
    <div class="container-fluid" id="live-detail">
        <br>
        <div class="row">
            <div class="col-md-5">
                <div id="carousel-product" class="carousel slide " data-ride="carousel">
                    <div class="carousel-inner " role="listbox">
                        @foreach($post->product->images as $key => $image)
                            @php
                                $active = '';
                                $preferred = '';
                                if($key == 0){
                                    $active = 'active';
                                }
                                if($post->product->user->is_approved_status){
                                    if($post->product->user->preferred_status){
                                        $preferred = 'preferred';
                                    }
                                }
                            @endphp
                            <div class="item {{$active}}">
                                <img src="{{$image->image}}" style="height: 350px; width: 650px;object-fit: cover;" alt="">
                            </div>
                        @endforeach
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-product" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-product" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>

                    <a class="product-image-count">
                        {{$post->product->images->count()}} <span class="fa fa-image" aria-hidden="true"></span>
                    </a>
                   {{-- <a class="product-stop-pause">
                        <span class="fa fa-pause" aria-hidden="true"></span> &nbsp;
                        <span class="fa fa-ban" aria-hidden="true"></span>
                    </a>--}}
                </div>
                <div class="row">
                    <div class="col-md-12" style="padding-top: 30px;">
                        <div class="col-md-7">
                            <h3 class="{{$preferred}} margin-top-0">{{ucwords($post->product->product_name)}}</h3>
                            <div class="col-md-12 product-description">
                                <h5>
                                    {{$post->product->description}}
                                </h5>
                                @if($post->product->species_id > 0)
                                    @if($post->product->species->name)
                                        <h5>
                                            <i class="fa fa-tag font-size-25"></i>
                                            {{isset($post->product->species->name) ? $post->product->species->name : ''}}
                                        </h5>
                                    @endif
                                @else
                                    @if($post->product->other_species)
                                        <h5>
                                            <i class="fa fa-tag font-size-25"></i>
                                            {{isset($post->product->other_species) ? $post->product->other_species : ''}}
                                        </h5>
                                    @endif
                                @endif
                                <h5>
                                    {{--                                    <i class="fa fa-balance-scale font-size-25"></i>--}}
                                    <img src="{{url('public/product_details/ic_5 kg.png')}}" style="margin-right: 14px">
                                    {{$post->weight}}
                                </h5>
                                <h5>
                                    <img src="{{url('public/product_details/ic_local.png')}}" style="margin-right: 14px">
                                    @if($post->product->imported==0)
                                        {{ "Local, Malaysia" }}
                                    @else
                                        {{ $post->product->other_imported_info }}
                                    @endif
                                </h5>
                                <h5>
                                    <img src="{{url('public/product_details/ic_grade.png')}}" style="margin-right: 14px">
                                    Grade: {{$post->product->grade}}
                                </h5>
                                <h5>
                                    <img src="{{url('public/product_details/ic_from_rm.png')}}" style="margin-right: 14px">
                                    From: RM {{$post->starting_price}}/{{$post->unit}}
                                </h5>
{{--                                @if($post->product->fast_buy==1)--}}
                                @if($post->product->fast_buy==1)
                                <h5>
                                        <img src="{{url('public/product_details/fast_buy.png')}}" style="margin-right: 14px">
                                        Fast Buy Price(RM): Rm {{$post->product->fast_buy_price}}
                                    </h5>
                                @endif
                                <h5>
                                    <img src="{{url('public/product_details/ic_pick.png')}}" style="margin-right: 14px">
                                    Pick Up point: {{$post->product->pickup_point}}
                                </h5>
                                @if($post->product->is_mygap==1)
                                    <h5>
                                        <img src="{{url('public/product_details/ic_my gap.png')}}" style="margin-right: 14px">
                                        MyGAP
                                    </h5>
                                @endif
                                @if($post->product->is_organic==1)
                                    <h5>
                                        <img src="{{url('public/product_details/ic_organic.png')}}" style="margin-right: 14px">
                                        Organic
                                    </h5>
                                @endif
                                <h5 style="padding-left:44px">
                                   {{$post->display_date_time}}
                                </h5>
                                <h5 style="padding-left:44px">
                                    ID: {{$post->product->product_id}}
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-5" style="padding-top: 245px;">

                            @if($post->post_type == 1)
                                @php
                                    $post_type = 'Start in';
                                @endphp
                                <span class="text-left col-md-4 end-in" style="padding-left: 0px;">{{$post_type}}</span>
                                <br>
                                <div class="clock-builder-output" style="padding-top: 5px"></div>
                            @endif
                        </div>
                    </div>
                    @if($post->post_type == 0 || $post->post_type == 2)
                        <br>
                        <div class="row text-center">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    @if($post->post_type == 2)
                                        <?php
                                        $creditmanagement=\App\Models\CreditManagement::where('post_id',$post->id)->get();
                                        ?>
                                        @if($creditmanagement->count()>0)
                                            <div class="col-md-12 tableheader ">
                                                Price Drop History
                                            </div>
                                            <div class="col-md-12 deatil-price-drop">
                                                @php
                                                    $is_highlight = 0;
                                                @endphp
                                                @foreach($post->price_drop as $key => $data)
                                                    @php
                                                        $lastDrop = '';
                                                        $lastDropContent = '';
                                                        $underlineTable = '';
                                                        $current_date_time = strtotime(\Carbon\Carbon::now(env('TIME_ZONE'))->format('Y-m-d H:i'));
                                                        $prev_key = $key;
                                                        if($key != 0){
                                                            $prev_key = $key - 1;
                                                        }
                                                        $post_start = strtotime(\Carbon\Carbon::now(env('TIME_ZONE'))->format('Y-m-d').' '.$post->price_drop[$prev_key]['time']);
                                                        $post_end = strtotime(\Carbon\Carbon::now(env('TIME_ZONE'))->format('Y-m-d').' '.$data['time']);

                                                        if($current_date_time > $post_start && $current_date_time <= $post_end){
                                                            $is_highlight = 1;
                                                            $lastDrop = 'last-drop';
                                                            $lastDropContent = '<i class="fa fa-angle-double-down"> </i>';
                                                        } else if($key == count($post->price_drop) - 1 && !$is_highlight){
                                                            $lastDrop = 'last-drop';
                                                            $lastDropContent = '<i class="fa fa-angle-double-down"> </i>';
                                                        }

                                                        if($key != count($post->price_drop) - 1) {
                                                            $underlineTable = 'underline-table';
                                                        }

                                                    @endphp
                                                    <div class="col-md-12 drop-price-connect {{$underlineTable}} {{$lastDrop}}">
                                                        <div class="col-md-6">{{$data['time']}}</div>
                                                        <div class="col-md-6"><span style="vertical-align: middle;">{!! $lastDropContent !!}</span>RM{{$data['price']}}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="col-md-12 tableheader ">
                                            Price Drop History
                                        </div>
                                        <div class="col-md-12 deatil-price-drop">
                                            @php
                                                $is_highlight = 0;
                                            @endphp
                                            @foreach($post->price_drop as $key => $data)
                                                @php
                                                    $lastDrop = '';
                                                    $lastDropContent = '';
                                                    $underlineTable = '';
                                                    $current_date_time = strtotime(\Carbon\Carbon::now(env('TIME_ZONE'))->format('Y-m-d H:i'));
                                                    $prev_key = $key;
                                                    if($key != 0){
                                                        $prev_key = $key - 1;
                                                    }
                                                    $post_start = strtotime(\Carbon\Carbon::now(env('TIME_ZONE'))->format('Y-m-d').' '.$post->price_drop[$prev_key]['time']);
                                                    $post_end = strtotime(\Carbon\Carbon::now(env('TIME_ZONE'))->format('Y-m-d').' '.$data['time']);

                                                    if($current_date_time > $post_start && $current_date_time <= $post_end){
                                                        $is_highlight = 1;
                                                        $lastDrop = 'last-drop';
                                                        $lastDropContent = '<i class="fa fa-angle-double-down"> </i>';
                                                    } else if($key == count($post->price_drop) - 1 && !$is_highlight){
                                                        $lastDrop = 'last-drop';
                                                        $lastDropContent = '<i class="fa fa-angle-double-down"> </i>';
                                                    }

                                                    if($key != count($post->price_drop) - 1) {
                                                        $underlineTable = 'underline-table';
                                                    }

                                                @endphp
                                                <div class="col-md-12 drop-price-connect {{$underlineTable}} {{$lastDrop}}">
                                                    <div class="col-md-6">{{$data['time']}}</div>
                                                    <div class="col-md-6"><span style="vertical-align: middle;">{!! $lastDropContent !!}</span>RM{{$data['price']}}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                @if($post->post_type == 0 )
                                    <div class="col-md-4">
                                        @php
                                            $post_type = 'End in';
                                        @endphp
                                        <span class="text-left col-md-4 end-in" style="padding-left: 0px;">{{$post_type}}</span>
                                        <div class="clock-builder-output" style="padding-top: 5px"></div>
                                        <h4 class="text-center color-green">
                                            <i class="fa  fa-eye"></i>
                                            <span id="live_viewer"></span> viewers
                                        </h4>
                                    </div>
                                @endif
                                @if($post->post_type != 0 && $post->post_type != 1)
                                    <div class="col-md-5">
                                        <span class="text-left col-md-4 end-in"><h5>Ended</h5></span>
{{--                                        @if($post->post_type == 2 )--}}
                                            @php
                                                $buyer_detail = $post->buyer_detail;
                                            @endphp
                                            <span><h5 class="deleted_at col-md-4">{{ isset($buyer_detail['created_at']) ? $post->getDisplayEndDateTime($buyer_detail['created_at']) : $post->getDisplayEndDateTime($post->end_date) }}</h5></span>
                                       {{-- @else
                                            <span><h5 class="deleted_at col-md-4">{{ $post->getDisplayEndDateTime($post->end_date)}}</h5></span>--}}
{{--                                        @endif--}}
                                        <br>
                                        <br>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12" style="padding-bottom: 30px;">
                        <div class="seller-details col-md-12">
                            <div class="col-md-12">
                                <h3>{{ucwords('Seller Details')}}</h3>
                                @if($post->product->user->profile_pic!="")
                                    <img src="{{$post->product->user->profile_pic}}" class="user-display-image" width="120px" height="120px">
                                @else
                                    <img src="{{url('')}}/public/logo/default_userimg.png" class="user-display-image" width="120px" height="120px">
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <h3 class="user-name-display">{{ucwords($post->product->user->name)}}</h3>
                                </div>
                                <div class="col-md-12">
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <span class="rating"><div class="rateyo" data-rateyo-rating="{{ $post->product->user->seller_rating }}" ></div></span>
                                            </td>
                                            <td>
                                                <span class="reviews-user"><h5>{{$post->product->user->seller_review}} Reviews</h5></span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 product-description">
                                    <h5>
                                        <img src="{{url('public/product_details/ic_farmer.png')}}" style="margin-right: 14px">
                                        {{\App\Enums\Type::coerce((int)$post->product->user->user_type)->key}}
                                        {{--@isset($post->product->user->maincategory)
                                        <img  style="color: #5E5E5E" src="{{ $post->product->user->maincategory->name=="Flowers" ? url('/public/assets/flower.png') : ($post->product->user->maincategory->name=="Plantations" ? url('/public/assets/plant.png') : ($post->product->user->maincategory->name=="Vegetables" ? url('/public/assets/vegetable.png') : ( url('/public/assets/fruit.png')))) }}">
                                         @endisset--}}
                                    </h5>
                                    <h5>
                                        <img src="{{url('public/product_details/ic_abc 123.png')}}" style="margin-right: 14px">
                                        {{$post->product->user->address}}
                                    </h5>
                                    <h5>
                                        <img src="{{url('public/product_details/ic_pick.png')}}" style="margin-right: 14px">
                                        {{$post->product->user->state->name}}
                                    </h5>
                                    {{--@if($post->product->user->job_description)
                                    <h5>
                                        <i class="fa  fa-circle-o font-size-25"></i>
                                        {{$post->product->user->job_description}}
                                    </h5>
                                    @endif--}}
                                </div>
                                @if($post->post_type == 2 )
                                    @php
                                        $buyer_detail = $post->buyer_detail;
                                    @endphp
                                    @if($buyer_detail)
                                        <div class="col-md-12">
                                            <br>
                                            <div class="col-md-3 padding-0 buyer-detail-div">
                                                <div class="buyer-image" style="width: 80px;height: 80px">
                                                    @if($buyer_detail['profile_pic']!="")
                                                        <img src="{{$buyer_detail['profile_pic']}}" class="user-display-image " width="80px" height="80px">
                                                    @else
                                                        <img src="{{url('')}}/public/logo/default_userimg.png" class="user-display-image" width="80px" height="80px">
                                                    @endif
                                                    <img src="{{ url('public/assets/reward.png') }}" class="reward-image ">
                                                </div>
                                            </div>
                                            <div class="col-md-8 padding-0">
                                                <h5>Buyer :</h5>
                                                <h3 class="color-green text-bold" style="margin-top: 10px;">{{ucwords($buyer_detail['buyer_name'])}}</h3>
                                                <span class="end-in">Trade Price</span> <span class="color-green" style="font-weight: bold;">Rm {{$buyer_detail['bid_price']}}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {
            $(".rateyo").rateYo({
                readOnly: true,
                spacing: "5px",
                starWidth: "25px",
                numStars: 5,
                minValue: 0,
                maxValue: 5,
                ratedFill: 'black',
            });
        });
        $(function () {
            var d = new Date();
            d.setTime({{ (strtotime($post->dateTime)) }} * 1000);
            $('.clock-builder-output').countdown(d, function (event) {
                var days = event.strftime('%D');
                if (days > 0) {
                    var hr = days * 24 + parseInt(event.strftime('%H'));
                    $(this).html(event.strftime('<span>' + hr + '</span>:<span>%M</span>:<span>%S</span>'));
                } else {
                    $(this).html(event.strftime('<span>%H</span>:<span>%M</span>:<span>%S</span>'));
                }
            });
        });
    </script>
    <script>
            @if($post->post_type == 0)
        let socket = io.connect("{{env('APP_NODE_URL')}}");
        $(function () {
            socket.emit('live_viewer_admin',JSON.stringify({
                post_id : {{$post->id}}
            }));

            socket.on('live_viewer_admin1',function (response) {
                response = JSON.parse(response);
                let live_viewer = response.data.live_viewer_count;
                $('#live_viewer').text(live_viewer);
            });
        });
        @endif
    </script>
@endsection
