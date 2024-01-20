<!-- Package Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('package_name', 'Package Name') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('package_name', null, ['class' => 'form-control',"placeholder"=>"Package Name"]) !!}
        </div>
    </div>
</div>


<!-- Price Field -->
<div class="row">

    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('price', 'Price(RM)') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('price', null, ['class' => 'form-control',"placeholder"=>"RM"]) !!}
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
            {!! Form::textarea('description', null, ['class' => 'form-control',"placeholder"=>"Description"]) !!}
        </div>
    </div>
</div>


<!-- Credit Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('credit', 'Credit') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('credit', null, ['class' => 'form-control',"placeholder"=>"Credit"]) !!}
        </div>
    </div>
</div>


<!-- Security Deposit Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('security_deposit', 'Security Deposit') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('security_deposit', null, ['class' => 'form-control',"placeholder"=>"Security Deposit"]) !!}
        </div>
    </div>
</div>


<!-- Sub User Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('sub_user', 'Sub User') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('sub_user', null, ['class' => 'form-control',"placeholder"=>"Sub User"]) !!}
        </div>
    </div>
</div>


<!-- Bidding Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('bidding', 'Bidding') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('bidding', null, ['class' => 'form-control',"placeholder"=>"Max Bidding Amount(RM)"]) !!}
        </div>
    </div>
</div>

<!-- Duration Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('duration', 'Duration(Months)') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('duration', null, ['class' => 'form-control',"placeholder"=>"Duration in months"]) !!}
{{--            <input type="text" class="form-control" name="duration" id="duration" value="12" readonly>--}}
        </div>
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    @if(isset($edit))
        {!! Form::submit('Save', ['class' => 'btn btn-submit']) !!}
        <a href="{{ route('subscriptions.index') }}" class="btn btn-cancel">cancel</a>
    @else
        {!! Form::submit('Create', ['class' => 'btn btn-submit']) !!}
    @endif

</div>

<div class="clearfix"></div>
<br>
<hr>
