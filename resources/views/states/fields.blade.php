<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('name', 'Add State ') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('name', null, ['class' => 'form-control','placeholder'=>"Type State"]) !!}
        </div>
    </div>
</div>

<div class="form-group col-sm-12">
    @if(isset($edit))
        {!! Form::submit('Save', ['class' => 'btn btn-submit']) !!}
        <a href="{{ route('states.index') }}" class="btn btn-cancel">cancel</a>
    @else
        {!! Form::submit('Create', ['class' => 'btn btn-submit']) !!}
    @endif

</div>

<div class="clearfix"></div>
<br>
<hr>
