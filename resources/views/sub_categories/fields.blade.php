<!-- Main Category Id Field -->
<div class="row">
    <?php
    $maincates=\App\Models\MainCategory::select('id','name')->get();
    $new=[""=>"Select Main Category"];
    foreach($maincates as $min)
    {
        $new[$min->id]=$min->name;
    }
    ?>
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('main_category_id', 'Main Category') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            {!! Form::select('main_category_id', $new, null, ['class' => 'form-control']) !!}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('name', 'Sub Category') !!}
        </div>
        <div class="col-sm-7 form-group">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}

        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">


    @if(isset($edit))
        {!! Form::submit('save', ['class' => 'btn btn-submit']) !!}
        <a href="{{ route('subCategories.index') }}" class="btn btn-cancel">cancel</a>
    @else
        {!! Form::submit(__('Create'), ['class' => 'btn btn-submit']) !!}
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>
