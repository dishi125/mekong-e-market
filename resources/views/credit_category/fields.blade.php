<!-- Main Category Id Field -->
<div class="row">
    <?php
    $maincates=\App\Models\MainCategory::select('id','name')->where('can_display_in_credit_setting1',null)->where('status',1)->get();
    $new=[""=>"Select Main Category"];
    if(isset($edit)){
        $current_maincat=\App\Models\MainCategory::where('id',$CreditSetting1->main_category_id)->first();
        $new[$current_maincat->id]=$current_maincat->name;
    }
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
            {!! Form::label('HotSpices', 'Hot Spices') !!}
        </div>
        <div class="col-sm-7 form-group">
            {!! Form::text('hot_species_credit', null, ['class' => 'form-control',"placeholder"=>"Credit Per Transaction"]) !!}
        </div>
    </div>
</div>

<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('MidSpices', 'Mid Spices') !!}
        </div>
        <div class="col-sm-7 form-group">
            {!! Form::text('mid_species_credit', null, ['class' => 'form-control',"placeholder"=>"Credit Per Transaction"]) !!}
        </div>
    </div>
</div>

<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('LowSpices', 'Low Spices') !!}
        </div>
        <div class="col-sm-7 form-group">
            {!! Form::text('low_species_credit', null, ['class' => 'form-control',"placeholder"=>"Credit Per Transaction"]) !!}
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('save', ['class' => 'btn btn-submit']) !!}
    @if(isset($edit))
        <a href="{{ route('credit_category.index') }}" class="btn btn-cancel">cancel</a>
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>


