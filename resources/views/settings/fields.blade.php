<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('name', 'Name') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('name', null, ['class' => 'form-control',"placeholder"=>"Name",isset($edit) ? 'disabled' : '']) !!}
        </div>
    </div>
</div>

<!-- Value Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('value', 'Value') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('value', null, ['class' => 'form-control',"placeholder"=>"Value"]) !!}
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(isset($edit) ? 'Save' : 'Create', ['class' => 'btn btn-submit']) !!}
    @if(isset($edit))
        <a href="{{ route('settings.index') }}" class="btn btn-cancel">cancel</a>
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>


