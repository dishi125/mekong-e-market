<style>
    .select2-container--default .select2-selection--multiple{
        background-color:#F3F3F5!important;
        border-radius:50px!important;
        cursor: text;
        border: unset!important;
    }
    .select2-dropdown{
        border: unset!important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        color: #555!important;
        font-size: smaller !important;
    }
    .select2-search__field{
        padding-left: 20px!important;
    }
    #select2-sub_category_id-results{
        border: 1px solid ghostwhite;
    }
</style>

<!-- Main Category Id Field -->
<div class="row">
    <?php
    $maincates=\App\Models\CreditSetting1::with('main_category')->where('status',1)->get();
    $new=[""=>"Select Main Category"];
    foreach($maincates as $min)
    {
        $new[$min->main_category_id]=$min->main_category->name;
    }
    ?>
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('main_category_id', 'Main Category') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            {!! Form::select('main_category_id', $new, null, ['class' => 'form-control','id'=>'main_category_id2']) !!}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('Spices', 'Spices') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            <select class="form-control" id="spices_category" name="spices_category">
                <option value="">Select spices</option>
                <option value="Hot" @if(isset($CreditSetting2->spices_category) && $CreditSetting2->spices_category == "Hot") {{'selected'}} @endif>Hot</option>
                <option value="Mid" @if(isset($CreditSetting2->spices_category) && $CreditSetting2->spices_category == "Mid") {{'selected'}} @endif>Mid</option>
                <option value="Low" @if(isset($CreditSetting2->spices_category) && $CreditSetting2->spices_category == "Low") {{'selected'}} @endif>Low</option>
            </select>
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<?php
$news=[];
$scates=[];
if(isset($CreditSetting2))
{
    $scates=\App\Models\SubCategory::where('main_category_id',$CreditSetting2->main_category_id)->get();
//        dd($scates);
}
if(old('main_category_id') )
{
    $scates=\App\Models\SubCategory::where('main_category_id',old('main_category_id'))->get();
//    dd($scates);sub_category_id
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
            @if(isset($CreditSetting2))
            <?php
                $selected = explode(",", $CreditSetting2->sub_categories);
                $all_subcats=\App\Models\SubCategory::where('main_category_id',$CreditSetting2->main_category_id)->where('status',1)->pluck('id')->toArray();
            ?>
            <select  name="sub_category_id" multiple="multiple" id="sub_category_id" class="form-control">
                @foreach($selected as $se)
                    <?php $sname=\App\Models\SubCategory::where('id',$se)->pluck('name')->first(); ?>
                    <option value="{{ $se }}" {{ (in_array($se, $all_subcats)) ? 'selected' : '' }}>{{ $sname }}</option>
                @endforeach
                <?php
                    $displaysubcat=\App\Models\SubCategory::where('main_category_id',$CreditSetting2->main_category_id)->where('can_display_in_credit_setting2',null)->where('status',1)->get();
                ?>
                @foreach($displaysubcat as $dsub)
                        <option value="{{ $dsub->id }}">{{ $dsub->name }}</option>
                @endforeach
            </select>
            @else
                {!! Form::select('sub_category_id',$news , null, ['class' => 'form-control','id'=>'sub_category_id','multiple'=>true]) !!}
                <i class="fa fa-caret-right"></i>
                <label for="sub_category_id" class="error"></label>
            @endif
        </div>
    </div>
</div>

<input type="hidden" name="sub_cat_ids">
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('save', ['class' => 'btn btn-submit']) !!}
    @if(isset($edit))
        <a href="{{ route('credit_setting2.index') }}" class="btn btn-cancel">cancel</a>
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>

<script type="text/javascript">
    $(document).ready(function () {
        @if(isset($edit))
        var ids="{{ $CreditSetting2->sub_categories }}";
        console.log(ids);
        $('input[name=sub_cat_ids]').val(ids);
        @endif
    })
</script>


