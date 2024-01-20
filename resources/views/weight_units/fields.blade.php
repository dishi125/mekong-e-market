<!-- Unit Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('unit', 'Unit') !!}
        </div>
        <div class="col-sm-7 form-group">
            {!! Form::text('unit', null, ['class' => 'form-control',"placeholder"=>"Unit"]) !!}
        </div>
    </div>

    {{--<div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('Credit Per Transaction', 'Credit Per Transaction') !!}
        </div>
        <div class="col-sm-7 form-group">
            {!! Form::text('credit_per_transaction', null, ['class' => 'form-control',"placeholder"=>"Credit Per Transaction"]) !!}
        </div>
    </div>--}}
</div>

<!-- Submit Field -->
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('save', ['class' => 'btn btn-submit']) !!}
    @if(isset($edit))
        <a href="{{ route('grades.index') }}" class="btn btn-cancel">cancel</a>
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>
