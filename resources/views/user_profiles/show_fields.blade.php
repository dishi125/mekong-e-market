
<?php
$type = \App\Enums\Type::toArray();
$type = array_flip($type);
?>

<div class="row bg-gray1">
    <div class="col-md-12 ">
        <div class="col-md-1">
            @if($userProfile->profile_pic!="")
                <img src="{{ url($userProfile->profile_pic) }}" class="user-display-image" width="120px" height="120px">
            @else
                <img src="{{url('')}}/public/logo/default_userimg.png" class="user-display-image" width="120px" height="120px">
            @endif
        </div>
        <div class="col-md-3">
            <div class="col-md-12">
                <br>
                <h4 class="user-name-display" style="margin-bottom: 0px"><b>{{ $userProfile->name??'' }}</b></h4>
            </div>
            <div class="col-md-12">
                <span id="rateYo" data-rating="{{ $avg_rate }}" style="position:absolute;"></span>
                <span class="reviews-user" style="position:absolute;right: 180px;">{{$review_count}} Reviews</span>
            </div>
            <div class="col-md-12 user-date">
                <span style="position:absolute;top: 7px;">since {{ date("d.m.Y",strtotime($userProfile->created_at)) }}</span>
            </div>
        </div>
    </div>
</div>
<section class="content-header">
    <div class="tab-buttons">
        <a href="{{ route('userProfiles.show', [$userProfile->id]) }}"
           class="btn  btn-lg btn-tab {{ Request::is('userProfiles/*') ? 'active' : ''}}">
            Detail
        </a>
            <a href="{{ route('user.live.deal', [$userProfile->id]) }}"
           class="btn  btn-lg btn-tab {{ Request::is('Profile/deals*') ? 'active' : ''}}">
            Deal History
        </a>
        <a href="{{ route('user.purchase.deal', [$userProfile->id]) }}"
           class="btn  btn-lg btn-tab {{ Request::is('Profile/purchase*') ? 'active' : ''}}">
            Purchase History
        </a>
        <a href="{{ route('user.rating.buyer', [$userProfile->id]) }}"
           class="btn  btn-lg btn-tab {{ Request::is('Profile/rating*') ? 'active' : ''}}">
            Rating
        </a>
    </div>
</section>
<style>
    .sub-tab-bar a {
        color: #2C2A2B;
        font-size: 16px;
        font-weight: bold;
    }

    .sub-tab-bar a.active {
        color: green;
    }
    .vertical {
        border-left-width: 2px;
        border-left-style: outset;
        height: 35px;
        position: absolute;
        left: 63px;
        top: 15px;
    }
</style>
<script>
    var rating = $('#rateYo').data("rating");
    $("#rateYo").rateYo({
        rating: rating,
        spacing: "5px",
        starWidth: "15px",
        numStars: 5,
        minValue: 0,
        maxValue: 5,
        // normalFill: 'black',
        ratedFill: 'black',
        readOnly: true
    });
</script>

@if(Request::is('Profile/deals*'))
    @php
        //here 0,1,2,3 set from count query (trade-column)
        $tradeCount = \App\Helpers\CommonHelper::get_trade_count($userProfile->id);
        $liveTrade = isset($tradeCount[1]) ? $tradeCount[1] : 0;
        $upComingTrade = isset($tradeCount[3]) ? $tradeCount[3] : 0;
        $pausedTrade = isset($tradeCount[2]) ? $tradeCount[2] : 0;
        $endedTrade = isset($tradeCount[0]) ? $tradeCount[0] : 0;
    @endphp
    <div class="col-md-12">
        <div class="col-md-6 text-center sub-tab-bar">
            <br>
            <div class="col-md-2">
                <a class=" {{ Request::is('Profile/deals/live*') ? 'active' : ''}}"
                   href="{{ route('user.live.deal', [$userProfile->id]) }}">
                    Live({{$liveTrade}})
                </a>
            </div>
            <div class="col-md-2">
                <a class=" {{ Request::is('Profile/deals/upcoming*') ? 'active' : ''}}"
                   href="{{ route('user.upcoming.deal', [$userProfile->id]) }}">
                    Upcoming({{$upComingTrade}})
                </a>
            </div>
            <div class="col-md-2">

                <a class=" {{ Request::is('Profile/deals/ended*') ? 'active' : ''}}"
                   href="{{ route('user.ended.deal', [$userProfile->id]) }}">
                    Ended({{$endedTrade}})
                </a>

            </div>
            <div class="col-md-2">

                <a class=" {{ Request::is('Profile/deals/paused*') ? 'active' : ''}}"
                   href="{{ route('user.paused.deal', [$userProfile->id]) }}">
                    Paused({{$pausedTrade}})
                </a>

            </div>
            <br>
            <br>

        </div>
    </div>
@elseif(Request::is('Profile/rating*'))

    <div class="col-md-12">
        <div class="col-md-6 text-center sub-tab-bar">
            <br>
            <div class="col-md-2">
                <a class=" {{ Request::is('Profile/rating/buyer*') ? 'active' : ''}}"
                   href="{{ route('user.rating.buyer', [$userProfile->id]) }}">
                    As Buyer
                </a>
            </div>
            <div class="col-md-2">
                <a class=" {{ Request::is('Profile/rating/seller*') ? 'active' : ''}}"
                   href="{{ route('user.rating.seller', [$userProfile->id]) }}">
                    As Seller
                </a>
            </div>
            <br>
            <br>

        </div>
    </div>
@endif
@if(Request::is('userProfiles/*'))
    <section class="font">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <h3>{{$userProfile->email}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>+60 &nbsp;<div class= "vertical"></div> {{ltrim($userProfile->phone_no,"+60")}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>{{ isset($type[$userProfile->user_type]) ? $type[$userProfile->user_type] : 'Not defined'}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>{{ isset($userProfile->maincategory->name) ? $userProfile->maincategory->name : '-'}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>{{$userProfile->company_name}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>{{$userProfile->company_reg_no}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>{{$userProfile->company_tel_no}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>{{($userProfile->area->name ?? '')." , ". ($userProfile->state->name ?? '')}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>{{$userProfile->address}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>{{$userProfile->company_email}}</h3>
                </div>
                <div class="col-md-12">
                    <h3>
                        @if(@isset($userProfile->document))
                            @if(in_array($ext, $pic_ext))
                                <a href="{{url('public/'.$userProfile->document)}}"><img src="{{url('public/'.$userProfile->document)}}" alt="" srcset="" width="100px" height="80px" style="border-style: solid;border-color:rgb(175,176,180);border-width: 10px"></a>
                            @elseif(in_array($ext,$doc_ext))
                                <a href="{{url('public/'.$userProfile->document)}}">{{url('public/'.$userProfile->document)}}</a>
                            @endif
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    </section>
@elseif(Request::is('Profile/deals/live*'))
    <div class="col-md-12">
        @include('user_profiles.livetable')
    </div>
@elseif(Request::is('Profile/deals/ended*'))
    <div class="col-md-12">
        @include('user_profiles.endedtable')
    </div>
@elseif(Request::is('Profile/deals/upcoming*'))
    <div class="col-md-12">
        @include('user_profiles.upcomingtable')
    </div>
@elseif(Request::is('Profile/deals/paused*'))
    <div class="col-md-12">
        <br>
        @include('user_profiles.pausedtable')
    </div>
@elseif(Request::is('Profile/purchase*'))
    <div class="col-md-12">
        <br>
        @include('user_profiles.purchase')
    </div>
@elseif(Request::is('Profile/rating*'))
    @include('user_profiles.rating')
@endif

