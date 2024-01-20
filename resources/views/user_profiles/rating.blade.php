<style>
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
</style>


<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            @if($rating->isEmpty())
                <p>No ratings.</p>
            @else
                @foreach($rating as $rt)
            <div class="col-md-12 rating-block">

                    <div class="col-md-2">
                        @if($rt->user->profile_pic!="")
                            <img src="{{ url($rt->user->profile_pic) }}" class="user-display-image" width="70px" height="70px">
                        @else
                            <img src="{{url('')}}/public/logo/default_userimg.png" class="user-display-image" width="70px" height="70px">
                        @endif
                    </div>
                    <div class="col-md-9">
                        <p style="margin-bottom: 0px"><b>{{ $rt->user->name??'' }}</b></p>
                        <div class="rateyo" data-rateyo-rating="{{ $rt->rate }}" ></div>
                        <p style="margin-bottom: 0px">{{$rt->review}}</p>
{{--                            <p>{{ date("d.m.Y \a\\t h:i ",strtotime($rt->created_at)) }}</p>--}}
                        <p style="color: #999999">{{ \Carbon\Carbon::parse($rt->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d.m.Y \a\\t h.ia') }}</p>
                    </div>
                    <div class="col-md-1">
                        <i class="fa fa-ban"></i>
                    </div>

            </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
        $(function () {
            $(".rateyo").rateYo({
                readOnly: true,
                spacing: "5px",
                starWidth: "15px",
                numStars: 5,
                minValue: 0,
                maxValue: 5,
                ratedFill: 'black',
            });
        });
</script>
