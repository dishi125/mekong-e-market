<!-- Amount Field -->
<div class="col-sm-4">
    <div class="col-sm-4">
    {!! Form::label('amount', 'Add Amount(RM)') !!}
    </div>
        <div class="col-sm-7 form-group ">
    {!! Form::text('amount', null, ['class' => 'form-control',"placeholder"=>"RM"]) !!}
    </div>
</div>


<!-- Credit Field -->
<div class="col-sm-4">
    <div class="col-sm-3">
    {!! Form::label('credit', '= Credit') !!}
    </div>
        <div class="col-sm-7 form-group ">
    {!! Form::text('credit', null, ['class' => 'form-control',"placeholder"=>"credit"]) !!}
</div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    @if(isset($edit))
        {!! Form::submit('Save', ['class' => 'btn btn-submit']) !!}
        <a href="{{ route('creditPackages.index') }}" class="btn btn-cancel">cancel</a>
    @else
        {!! Form::submit('Create', ['class' => 'btn btn-submit']) !!}
    @endif

</div>
