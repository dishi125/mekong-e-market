<!-- Location Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('location', 'Location') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('location', null, ['class' => 'form-control',"placeholder"=>"Home Page"]) !!}
        </div>
    </div>
</div>

<!-- Price Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('price(RM)', 'Price') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::number('price', null, ['class' => 'form-control',"placeholder"=>"RM"]) !!}
        </div>
    </div>
</div>

<!-- Duration Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('duration', 'Duration') !!}
        </div>
        <div class="col-sm-3 form-group ">
            {!! Form::number('duration', (isset($bannerPackage->duration) ? $bannerPackage->display_duration : null), ['class' => 'form-control',"placeholder"=>"duration"]) !!}
        </div>
        <div class="col-sm-3 form-group select-box">
            <select class="form-control" id="duration_type" name="duration_type">
                <option value="minutes" @if(isset($bannerPackage->duration_type) && $bannerPackage->duration_type == "minutes") {{'selected'}} @endif>Minutes</option>
                <option value="hours" @if(isset($bannerPackage->duration_type) && $bannerPackage->duration_type == "hours") {{'selected'}} @endif>Hours</option>
                <option value="days" @if(isset($bannerPackage->duration_type) && $bannerPackage->duration_type == "days") {{'selected'}} @endif>Days</option>
                <option value="months" @if(isset($bannerPackage->duration_type) && $bannerPackage->duration_type == "months") {{'selected'}} @endif>Months</option>
            </select>
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(isset($edit) ? 'Save' : 'Create', ['class' => 'btn btn-submit']) !!}
    @if(isset($edit))
        <a href="{{ route('bannerPackages.index') }}" class="btn btn-cancel">cancel</a>
    @endif
</div>

<div class="clearfix"></div>
<br>
<hr>
