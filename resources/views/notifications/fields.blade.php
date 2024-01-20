<!-- User Type Field -->
<?php
$user=\App\Enums\UserType::toArray();
$user= array_flip($user);
?>
<div class="row">
    <div class="col-sm-8">
        <div class="col-sm-3">
            {!! Form::label('user_type', 'User Type') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            {!! Form::select('user_type',$user, null, ['class' => 'form-control']) !!}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<!-- Type Id Field -->
<?php
$type = \App\Enums\Type::toArray();
$type = array_flip($type);
$types = [''=>"Select User Type"] + $type;

$user_id = isset($user_id) ? $user_id : ['' => ''];
?>
<div class="row">
    <div class="col-sm-8">
        <div class="col-sm-3">
            {!! Form::label('type_id', 'Types') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            {!! Form::select('type_id',$types, null, ['class' => 'form-control']) !!}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<!-- User Id Field -->

<div class="row">
    <div class="col-sm-8">
        <div class="col-sm-3">
            {!! Form::label('user_id', 'User') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            {!! Form::select('user_id',$user_id , null, ['class' => 'form-control']) !!}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<!-- Title Field -->
<div class="row">
    <div class="col-sm-8">
        <div class="col-sm-3">
            {!! Form::label('title', 'Title') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('title', null, ['class' => 'form-control',"placeholder"=>"Title"]) !!}
        </div>
    </div>
</div>

    <!-- Description Field -->
<div class="row">
    <div class="col-sm-8">
        <div class="col-sm-3">
            {!! Form::label('description', 'Description') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::textarea('description', null, ['class' => 'form-control',"placeholder"=>"Description"]) !!}
        </div>
    </div>
</div>


    <!-- Date Field -->
<div class="row">
    <div class="col-sm-8">
        <div class="col-sm-3">
            {!! Form::label('date', 'Date Time') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('date', isset($notification->date) ? $notification->display_date : null, ['class' => 'form-control datetime',"placeholder"=>"Select Date Time"]) !!}
        </div>
    </div>
</div>

<div class="form-group col-sm-12">
    @if(isset($edit))
        {!! Form::submit('Save', ['class' => 'btn btn-submit']) !!}
        <a href="{{ route('notifications.index') }}" class="btn btn-cancel">cancel</a>
    @else
        {!! Form::submit('Create', ['class' => 'btn btn-submit']) !!}
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>

<div class="row">
    <div class="col-sm-8">
        <div class="col-sm-3">
            {!! Form::label('notification_text', 'Notification Text') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('notification_text', null, ['class' => 'form-control',"placeholder"=>"Enter Text To send Notification"]) !!}
        </div>
    </div>

</div>

<div class="form-group col-sm-12">
    <a class="btn btn-cancel test_notification" style="background: #2aabe2;">Test Notification</a>
</div>

<div class="clearfix"></div>
<br>
<hr>


@section('scripts')
    <script !src="">
        $('#user_type').on("change",function () {
            var value=$(this).val();
            if(value==0)
            {
                $('#type_id').val('');
                $('#user_id').val('');
                $('#type_id').attr('disabled','disabled');
                $('#user_id').attr('disabled','disabled');
            }
            else if(value==1)
            {
                $('#user_id').val('');
                $('#type_id').removeAttr('disabled');
                $('#type_id').prop('required',true);
                $('#user_id').attr('disabled','disabled');
            }
            else
            {
                $('#type_id').prop('required',true);
                $('#user_id').prop('required',true);

                $('#type_id').removeAttr('disabled');
                $('#user_id').removeAttr('disabled');
            }


        });
        $('#type_id').change(function () {
            var user_type=$('#user_type').val();
            if(user_type==2)
            {
                var id=$(this).val();
                var project_url="{{ url('') }}/";
               $.ajax({
                   url:project_url+"get_user",
                   type:"POST",
                   data:{_token:'{{csrf_token()}}',id:id},
                   success:function (data) {
                        if(data.status==1)
                        {
                            var html="<option value=''>Select User</option>";
                            $.each(data.data,function (k,v) {
                                html+="<option value='"+k+"'>"+v+"</option>";
                            })
                            $("#user_id").html(html);
                        }
                        else
                        {
                            alert(data.data);
                        }
                   }

               });
            }
        });
        $(document).ready(function () {
            $('#user_type').change();
            {{--$('#type_id').change();--}}
            {{--@if($notification->user_id)--}}
            {{--setTimeout(function(){ $('#user_id').val({{$notification->user_id}}); }, 500);--}}
            {{--@endif--}}
        });
        $(document).on('click', '.test_notification', function(event){
            var notification_text = $('#notification_text').val();
            $.ajax({
                url:'{{ route('notifications.test') }}',
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    notification_text: notification_text
                },
                success:function (data) {
                    // location.reload();
                    if(data.success==true){
                        location.reload();
                    }
                    else{
                        alert("something went wrong.");
                    }
                }
            });
        });
    </script>
@endsection
