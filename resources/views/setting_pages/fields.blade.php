<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('name', 'Name') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('name', null, ['class' => 'form-control',"placeholder"=>"Page Name",isset($edit) ? 'disabled' : '']) !!}
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-1" style="width: 12.333333%">
            {!! Form::label('description', 'Description') !!}
        </div>
        <div class="col-sm-10 form-group ">
            {!! Form::textarea('description', null, ['class' => 'form-control',"placeholder"=>"Page Description"]) !!}
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(isset($edit) ? 'Save' : 'Create', ['class' => 'btn btn-submit']) !!}
    @if(isset($edit))
        <a href="{{ route('settingPages.index') }}" class="btn btn-cancel">cancel</a>
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>


