<style>
    .hide{
        display: none;
    }
    form label {
        font-weight: 100;
    }
</style>
<div class="col-sm-6">
    <!-- Name Field -->
    <div class="col-sm-12">
        <div class="col-sm-3">
            {!! Form::label('name', 'Name') !!}
        </div>
        <div class="col-sm-6 form-group ">
            {!! Form::text('name', (isset($banner->name) ? $banner->name : null), ['class' => 'form-control',"placeholder"=>"name"]) !!}
        </div>
    </div>


    <!-- Contact Field -->
    <div class="col-sm-12">
        <div class="col-sm-3">
            {!! Form::label('contact', 'Contact') !!}
        </div>
        <div class="col-sm-6 form-group ">
            {!! Form::text('contact', (isset($banner->contact) ? $banner->contact : null), ['class' => 'form-control',"placeholder"=>"Contact"]) !!}
        </div>
    </div>


    <!-- Email Field -->
    <div class="col-sm-12">
        <div class="col-sm-3">
            {!! Form::label('email', 'Email') !!}
        </div>
        <div class="col-sm-6 form-group ">
            {!! Form::text('email', (isset($banner->email) ? $banner->email : null), ['class' => 'form-control',"placeholder"=>"Email"]) !!}
        </div>
    </div>


    <!-- Start Date Field -->
    <div class="col-sm-12">
        <div class="col-sm-3">
            {!! Form::label('location', 'Banner Location') !!}
        </div>
        <div class="col-sm-6 form-group select-box">
            <select class="form-control" id="location" name="location">
                <option value="" selected="selected">Select Location</option>
                @foreach($bannerPackages as $bannerPackage)
                @php
                    $selected = "";
                    if(isset($banner->location) && ($banner->location == $bannerPackage->location)){
                        $selected = "selected";
                    }
                @endphp
                <option value="{{$bannerPackage->location}}" {{$selected}} data-price = "{{$bannerPackage->price}}" data-duration = "{{$bannerPackage->display_duration}}" data-duration_type = "{{$bannerPackage->duration_type}}" data-date = "{{$bannerPackage->display_date}}" >{{$bannerPackage->location}}</option>
                @endforeach
            </select>
            <i class="fa fa-caret-right"></i>
        </div>
    </div>

    <!-- Start Date Field -->
    <div class="col-sm-12">
        <div class="col-sm-3">
            {!! Form::label('price', 'Price') !!}
        </div>
        <div class="col-sm-6 form-group ">
            {!! Form::text('price', (isset($banner->price) ? $banner->price : null), ['class' => 'form-control',"placeholder"=>"price"]) !!}
        </div>
    </div>

<!-- Start Date Field -->
    <div class="col-sm-12">
        <div class="col-sm-3">
            {!! Form::label('duration', 'Duration') !!}
        </div>
        <div class="col-sm-3 form-group ">
            {!! Form::number('duration', (isset($banner->duration) ? $banner->display_duration : null), ['class' => 'form-control',"placeholder"=>"duration"]) !!}
        </div>
        <div class="col-sm-3 form-group select-box">
            <select class="form-control" id="duration_type" name="duration_type">
                <option value="minutes" @if(isset($banner->duration_type) && $banner->duration_type == "minutes") {{'selected'}} @endif>Minutes</option>
                <option value="hours" @if(isset($banner->duration_type) && $banner->duration_type == "hours") {{'selected'}} @endif>Hours</option>
                <option value="days" @if(isset($banner->duration_type) && $banner->duration_type == "days") {{'selected'}} @endif>Days</option>
                <option value="months" @if(isset($banner->duration_type) && $banner->duration_type == "months") {{'selected'}} @endif>Months</option>
            </select>
            <i class="fa fa-caret-right"></i>
        </div>
    </div>

<!-- Start Date Field -->
    <div class="col-sm-12">
        <div class="col-sm-3">
            {!! Form::label('start_date', 'Start Date') !!}
        </div>
        <div class="col-sm-6 form-group ">
            {!! Form::text('start_date', (isset($banner->start_date) ? $banner->start_date : null), ['class' => 'form-control datepicker',"placeholder"=>"Start Date",'data-date-format' => 'dd-mm-yyyy']) !!}
        </div>
    </div>


    <!-- Banner Link Field -->
    <div class="col-sm-12">
        <div class="col-sm-3">
            {!! Form::label('banner_link', 'Banner Link') !!}
        </div>
        <div class="col-sm-6 form-group ">
            {!! Form::text('banner_link', (isset($banner->banner_link) ? $banner->banner_link : null), ['class' => 'form-control',"placeholder"=>"Banner Link"]) !!}
        </div>
    </div>

    <!-- select photo/video Field -->
        <div class="col-sm-12">
            <div class="col-sm-3">
                {!! Form::label('photo_videolink', 'select any one') !!}
            </div>
            <div class="col-sm-9">
                <div class="col-sm-3">
                    <label class="form-group radio_button">
                        {!! Form::radio('type', '0', null) !!}
                        Photo
                    </label>
                </div>
                <div class="col-sm-3">
                    <label class="form-group radio_button">
                        {!! Form::radio('type', '1', null) !!}
                        Video
                    </label>
                </div>
            </div>
        </div>

    <!-- Banner Photo Field -->
    <div class="col-sm-12 bannerphoto" style="display: none">
        <div class="col-sm-3">
            {!! Form::label('banner_photo', 'Banner Photo') !!}
        </div>
        <div class="col-sm-6 form-group ">
            <input class="form-control" placeholder="Browse" name="browse" type="button" value="Browse" style="text-align: left;">
            <i class="fa fa-upload" style="color: #04A34F;position: absolute;float: right;top: 9%;right: 6%;font-size: 20px;" aria-hidden="true"></i>
            <input type="file" id="banner_photo" name="banner_photo" style="visibility: hidden;" onchange="document.getElementById('uploadImage').src = window.URL.createObjectURL(this.files[0])"/>
            <input type="hidden" id="deleted_photo" name="deleted_photo"/>
            @php
                $show = "hide";
                $url = "";
                $photo = "";
                if(isset($banner->banner_photo) && $banner->type == 0){
                    $show = '';
                    $url = url('/public/'.$banner->banner_photo);
                    $photo = $banner->banner_photo;
                }
            @endphp
            <div class="col-sm-6 form-group {{$show}}" style="padding: 15px" id="img_div">
                <div style="height: 100px;width: 100px;">
                    <i class="fa fa-times-circle-o fa-x close-image" data-src = "{{$photo}}" style="float: right;color: red;font-size: 20px;z-index: 9999;cursor: pointer;position: relative;" onclick='imgRemove(this)'></i>
                    <img src="{{$url}}" width="100" height="100" style="padding:3px;margin-top: -23px;" id="uploadImage" />
                </div>
            </div>
        </div>
    </div>

    <!-- Banner VideoLink Field -->
    <div class="col-sm-12 videolink" style="display: none">
        <div class="col-sm-3">
            {!! Form::label('video_link', 'Video Link') !!}
        </div>
        <div class="col-sm-6 form-group ">
            {!! Form::text('video_link', (isset($banner->banner_photo) && $banner->type == 1) ?  $banner->banner_photo : null, ['class' => 'form-control',"placeholder"=>"Banner Video Link"]) !!}
        </div>
    </div>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit(isset($edit) ? 'Save' : 'Create', ['class' => 'btn btn-submit']) !!}
        @if(isset($edit))
            <a href="{{ route('banners.index') }}" class="btn btn-cancel">cancel</a>
        @endif
    </div>
</div>

@section('scripts')
<script>
    $('input[type=radio]').change(function() {
        var rdbval=this.value;
        if(rdbval==0){
            $('.bannerphoto').css('display','unset');
            $('.videolink').css('display','none');
        }
        else if(rdbval==1){
            $('.videolink').css('display','unset');
            $('.bannerphoto').css('display','none');
        }
    });
    @if(isset($banner->type))
        var checked_value = {{isset($banner->type) ? (int)$banner->type : ''}};
        if(checked_value==0){
            $('.bannerphoto').css('display','unset');
            $('.videolink').css('display','none');
        }
        else if(checked_value==1){
            $('.videolink').css('display','unset');
            $('.bannerphoto').css('display','none');
        }
    @endif

    $('#location').change(function(){
       var price = $(this).find(':selected').attr('data-price');
       var duration = $(this).find(':selected').attr('data-duration');
       var duration_type = $(this).find(':selected').attr('data-duration_type');
       var date = $(this).find(':selected').attr('data-date');

       $('#price').val(price);
       $('#duration').val(duration);
       $('#duration_type').val(duration_type);
       $('#start_date').val(date);
    });

    $("[name='browse']").click(function(){
        $('#banner_photo').trigger('click');
    });

    $('#banner_photo').change(function(){
        $('#img_div').removeClass('hide');
    });

    function imgRemove(ths)
    {
        var deleteFile = confirm("Do you really want to Delete?");
        $('#img_div').addClass('hide');
        var img_name = $(ths).attr('data-src');
        if($("#banner_photo").val() == ''){
            $('#deleted_photo').val(img_name);
        }else{
            clearFileInput("banner_photo");
        }
    }
</script>
@endsection
