<!-- Name Field -->

<div class="col-sm-6">
    <div class="col-sm-3">
    {!! Form::label('name', 'Main Category') !!}
    </div>
    <div class="col-sm-7 form-group ">
    {!! Form::text('name', null, ['class' => 'form-control',"placeholder"=>"Main Category"]) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
        @if(isset($mainCategory))
        {!! Form::submit('Save', ['class' => 'btn btn-submit']) !!}
        <a href="{{ route('mainCategories.index') }}" class="btn btn-cancel">cancel</a>
        @else
        {!! Form::submit('Create', ['class' => 'btn btn-submit']) !!}
        @endif

</div>

<div class="clearfix"></div>
<br>
<hr>
