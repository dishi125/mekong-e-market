<!-- Main Category Id Field -->
<?php
$maincates=\App\Models\MainCategory::select('id','name')->get();
$new=[""=>"Select Main Category"];
foreach($maincates as $min)
{
    $new[$min->id]=$min->name;
}
?>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('main_category_id', 'Main Category') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            {!! Form::select('main_category_id',$new, null, ['class' => 'form-control']) !!}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>
<?php
$news=[""=>"Select Sub Category"];
$scates=[];
if(isset($specie))
    {
        $scates=\App\Models\SubCategory::where('main_category_id',$specie->main_category_id)->get();
//        dd($scates);
    }
if(old('main_category_id') )
{
    $scates=\App\Models\SubCategory::where('main_category_id',old('main_category_id'))->get();
//    dd($scates);
}
//dd($scates);
foreach($scates as $min)
{
    $news[$min->id]=$min->name;
}

?>
<!-- Sub Category Id Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('sub_category_id', 'Sub Category') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            {!! Form::select('sub_category_id',$news , null, ['class' => 'form-control']) !!}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>


<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('name', 'Specie') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('name', null, ['class' => 'form-control','placeholder'=>"Specie"]) !!}
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    @if(isset($edit))
        {!! Form::submit(__('Save'), ['class' => 'btn btn-submit']) !!}
        <a href="{{ route('species.index') }}" class="btn btn-cancel">cancel</a>
        @else
        {!! Form::submit(__('Create'), ['class' => 'btn btn-submit']) !!}
    @endif
</div>
<div class="clearfix"></div>
<br>
<hr>
