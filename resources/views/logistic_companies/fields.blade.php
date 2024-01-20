{{-- set hidden variable for edit--}}
{!! Form::hidden('logistic_company_id', isset($logisticCompany->id) ? $logisticCompany->id : null, ['class' => 'form-control']) !!}

<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('name', 'Logistic Company') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('name', null, ['class' => 'form-control',"placeholder"=>"logistic company"]) !!}
        </div>
    </div>
</div>


<!-- Reg No Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('reg_no', 'Reg No') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('reg_no', null, ['class' => 'form-control',"placeholder"=>"reg. no."]) !!}
        </div>
    </div>
</div>


<!-- Id No Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('id_no', 'Id No') !!}
        </div>
        <div class="col-sm-7 form-group ">
{{--            {!! Form::text('id_no', null, ['class' => 'form-control','value' => isset($lastid)?$lastid:'','readonly']) !!}--}}
            <input type="text" class="form-control" name="id_no" id="id_no" value="@php if(isset($lastid)) echo $lastid; if(isset($logisticCompany->id_no)) echo $logisticCompany->id_no; @endphp" readonly>
        </div>
    </div>
</div>


<!-- Contact Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('contact', 'Contact') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('contact', null, ['class' => 'form-control',"placeholder"=>"contact"]) !!}
        </div>
    </div>
</div>

<!-- Email Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('email', 'Email') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('email', null, ['class' => 'form-control',"placeholder"=>"email"]) !!}
        </div>
    </div>
</div>

<!-- State Id Field -->
<div class="row">
    <div class="form-group col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('state_id', 'State & Area') !!}
        </div>
        <?php
        $states=\App\Models\State::select('id','name')->get();
        $new=[""=>"Select State"];
        foreach($states as $state)
        {
            $new[$state->id]=$state->name;
        }
        ?>
        <div class="col-sm-3 form-group select-box">
                {!! Form::select('state_id',$new, null, ['class' => 'form-control']) !!}
                <i class="fa fa-caret-right"></i>
        </div>
        <?php
        $news=[""=>"Select Area"];
        $areas=[];
        if(isset($logisticCompany))
        {
            $areas=\App\Models\Area::where('state_id',$logisticCompany->state_id)->get();
//        dd($areas);
        }

        if(old('state_id') )
        {
            $areas=\App\Models\Area::where('state_id',old('state_id'))->get();
        }
        //dd($areas);
        foreach($areas as $min)
        {
            $news[$min->id]=$min->name;
        }

        ?>
        <div class="col-sm-3 form-group select-box">
            {!! Form::select('area_id',$news , null, ['class' => 'form-control']) !!}
           {{-- <select name="area_id" id="area_id" class="form-control">
            </select>--}}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<!-- Address Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('address', 'Address') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::textarea('address', null, ['class' => 'form-control border_radius','rows' => "4","placeholder"=>"address"]) !!}
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('description', 'Description') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::textarea('description', null, ['class' => 'form-control border_radius','rows' => "4","placeholder"=>"text here"]) !!}
        </div>
    </div>
</div>

<!-- Nursery Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('nursery', 'Nursery') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('nursery', null, ['class' => 'form-control',"placeholder"=>"Nursery details"]) !!}
        </div>
    </div>
</div>

<!-- Exporter Status Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('exporter_status', 'Exporter') !!}
        </div>
        <div class="col-sm-9">
            <div class="col-sm-3">
                <label class="form-group radio_button">
                    {!! Form::radio('exporter_status', '1', null) !!}
                    Yes
                </label>
            </div>
            <div class="col-sm-3">
                <label class="form-group radio_button">
                    {!! Form::radio('exporter_status', '0', null) !!}
                    NO
                </label>
            </div>
        </div>
    </div>
</div>

<!-- Profile Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('profile', 'Profile Photo') !!}
        </div>
        <div class="col-sm-7 form-group ">
            <div class="col-sm-12" style="padding: 0px;">
                <input class="form-control" placeholder="Browse" name="browse" type="button" value="Browse" style="text-align: left;">
                <i class="fa fa-upload" style="color: #04A34F;position: absolute;float: right;top: 10%;right: 6%;font-size: 20px;" aria-hidden="true"></i>
                <input type="file" id="profile" name="profile" style="visibility: hidden;" onchange="document.getElementById('uploadImage').src = window.URL.createObjectURL(this.files[0])"/>
                <input type="hidden" id="deleted_profile" name="deleted_profile"/>
            </div>
            @php
                $show = "hide";
                $url = "";
                $profile = "";
                if(isset($logisticCompany->profile)){
                    $show = '';
                    $url = url('/public/'.$logisticCompany->profile);
                    $profile = $logisticCompany->profile;
                }
            @endphp
            <div class="col-sm-6 form-group {{$show}}" style="padding: 15px" id="img_div">
                <div style="height: 100px;width: 100px;">
                    <i class="fa fa-times-circle-o fa-x close-image" data-src = "{{$profile}}" style="float: right;color: red;font-size: 20px;z-index: 9999;cursor: pointer;position: relative;" onclick='imgRemove(this)'></i>
                    <img src="{{$url}}" width="100" height="100" style="padding:3px;margin-top: -23px;" id="uploadImage" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photos Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('photos', 'Photos') !!}
        </div>
        <div class="col-sm-7 form-group ">
            <div class="col-sm-12" style="padding: 0px;">
                <input class="form-control" placeholder="Browse" name="browse_photo" type="button" value="Browse" style="text-align: left;">
                <i class="fa fa-upload" style="color: #04A34F;position: absolute;float: right;top: 10%;right: 6%;font-size: 20px;" aria-hidden="true"></i>
                <input type="file" id="photos" name="photos[]" multiple style="visibility: hidden;"/>
                <input type="hidden" id="deleted_photos" name="deleted_photos"/>
                <input type="hidden" id="final_photos" name="final_photos"/>
            </div>
            @php
                $show = "hide";
                if(!empty($logistic_photos) && isset($logistic_photos)){
                    $show = '';
                }
            @endphp
            <div class="col-sm-6 form-group {{$show}}" style="padding: 15px;display: flex;" id="multi_image">
                @isset($logistic_photos)
                    @foreach($logistic_photos as $logistic_photo)
                        <div style="height: 100px;width: 100px;"><i class="fa fa-times-circle-o fa-x close-image" data-index = "0" data-db_id ="{{$logistic_photo->id}}" style="float: right;color: red;font-size: 20px;z-index: 9999;cursor: pointer;position: relative;" onclick="multipleimgRemove(this)"></i>
                            <img src="{{url('/public/'.$logistic_photo->image)}}" width="100" height="100" style="padding:3px;margin-top: -23px;" id="uploadImage" />
                        </div>
                    @endforeach
                @endisset
            </div>
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(isset($edit) ? 'Save' : 'Create', ['class' => 'btn btn-submit']) !!}
    @if(isset($edit))
        <a href="{{ route('logisticCompanies.index') }}" class="btn btn-cancel">cancel</a>
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <script>

        var deleted_photos = [];
        var final_photos = [];

        //checked radiobutton
        var checked_value = {{isset($logisticCompany->exporter_status) ? (int)$logisticCompany->exporter_status : 0}}
        $("input[name=exporter_status][value=" + checked_value + "]").prop('checked', true);

        $("[name='browse']").click(function(){
            $('#profile').trigger('click');
        });

        $("[name='browse_photo']").click(function(){
            $('#photos').trigger('click');
        });

        $('#profile').change(function(){
            $('#img_div').removeClass('hide');
        });

        $('#photos').on('change', function() {
            loadImage();
        });

        function imgRemove(ths)
        {
            var deleteFile = confirm("Do you really want to Delete?");
            $('#img_div').addClass('hide');
            var img_name = $(ths).attr('data-src');
            if($("#profile").val() == ''){
                $('#deleted_profile').val(img_name);
            }else{
                clearFileInput("profile");
            }
        }

        function multipleimgRemove(ths)
        {
            var deleteFile = confirm("Do you really want to Delete?");
            $(ths).parent().remove();

            var file_id = $(ths).attr('data-file_id');
            if(file_id){
                final_photos = final_photos.filter(function(file){
                    return file.file_id != file_id;
                });
            }

            // Remove File from server (if already uploaded)
            var db_id = $(ths).attr('data-db_id');
            if(db_id){
                deleted_photos.push(db_id);
                $('#deleted_photos').val(JSON.stringify(deleted_photos));
            }
        }

        function loadImage() {

            var files = $("#photos")[0].files;

            for (var i = 0; i < files.length; i++) {

                var file = files[i];
                file_id = Math.random().toString(36).substring(7);
                file.file_id = file_id;

                final_photos.push(file);

                var picReader = new FileReader();
                picReader.file_id = file_id;
                var output = $("#multi_image");
                $('#multi_image').removeClass('hide');

                picReader.addEventListener('load', function (event) {
                    var html =  '<div style="height: 100px;width: 100px;"><i class="fa fa-times-circle-o fa-x close-image" data-file_id = "'+event.target.file_id+'" style="float: right;color: red;font-size: 20px;z-index: 9999;cursor: pointer;position: relative;" onclick="multipleimgRemove(this)"></i><img src="'+event.target.result+'" width="100" height="100" style="padding:3px;margin-top: -23px;" id="uploadImage" /></div>';
                    output.append(html);
                });

                picReader.readAsDataURL(file);
            }
        }

        $(document).ready(function() {
            $('#logisticCompanies').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    reg_no: {
                        required: true,
                    },
                    id_no: {
                        required: true,
                    },
                    contact: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    state_id: {
                        required: true,
                    },
                    area_id: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                },
                submitHandler: function () {
                    var form = new FormData($('#logisticCompanies')[0]);

                    var logisticUrl;
                    var method;
                    var logistic_company_id = $('[name=logistic_company_id]').val();

                    final_photos.forEach(function (image, i) {
                        form.append('final_photos[]', image);
                    });

                    if (logistic_company_id) {
                        logisticUrl = "{{ url('logisticCompanies')}}" + "/" + logistic_company_id;
                        method = "POST";
                        form.append('_token', '{{csrf_token()}}');
                        form.append('_method', 'patch');
                    }
                    else {
                        logisticUrl = "{{ url('logisticCompanies')}}";
                        method = "POST";
                    }

                    event.preventDefault();
                    $.ajax({
                        url: logisticUrl,
                        method: method,
                        data: form,
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            block_ui();
                        },
                        success: function (res) {
                            if (res == 'true') {
                                unblock_ui();
                                location.href = "{{URL::to('logisticCompanies')}}";
                            }
                        }
                    });
                }
            });


            /*$('#logisticCompanies').validate({
                submitHandler: function() {
                    var form = new FormData($('#logisticCompanies')[0]);

                    var logisticUrl;
                    var method;
                    var logistic_company_id = $('[name=logistic_company_id]').val();

                    final_photos.forEach(function (image, i) {
                        form.append('final_photos[]', image);
                    });

                    if (logistic_company_id) {
                        logisticUrl = "{{ url('logisticCompanies')}}" + "/" + logistic_company_id;
                        method = "POST";
                        form.append('_token', '{{csrf_token()}}');
                        form.append('_method', 'patch');
                    }
                    else {
                        logisticUrl = "{{ url('logisticCompanies')}}";
                        method = "POST";
                    }

                    event.preventDefault();
                    $.ajax({
                        url: logisticUrl,
                        method: method,
                        data: form,
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                        },
                        success: function (res) {
                            if (res == 'true') {
                                location.href = "{{URL::to('logisticCompanies')}}";
                            }
                        }
                    });
                }
            });*/
            function block_ui()
            {
                $.blockUI({ message: '',
                    css: {
                        border: 'none',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        backgroundColor: 'unset',
                    }});
            }
            function unblock_ui()
            {
                $.unblockUI();
            }
        });
        var action='logistic_companies';
    </script>
    @include('layouts.status')
@endsection
