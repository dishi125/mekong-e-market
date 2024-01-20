<!-- State Id Field -->
<?php
$maincates=\App\Models\State::select('id','name')->get();
$new=[""=>"Select State"];
foreach($maincates as $min)
{
    $new[$min->id]=$min->name;
}
?>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('state_id', 'State') !!}
        </div>
        <div class="col-sm-7 form-group select-box">
            {!! Form::select('state_id', $new, null, ['class' => 'form-control']) !!}
            <i class="fa fa-caret-right"></i>
        </div>
    </div>
</div>

<!-- Name Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-3">
            {!! Form::label('name', 'Area') !!}
        </div>
        <div class="col-sm-7 form-group ">
            {!! Form::text('name', null, ['class' => 'form-control',"placeholder"=>"Type Area"]) !!}
        </div>
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-2" style="padding-left: 183px">
    @if(isset($edit))
        {!! Form::submit('save', ['class' => 'btn btn-submit','name'=>'import' ,'value'=>'create']) !!}
        <a href="{{ route('areas.index') }}" class="btn btn-cancel">cancel</a>
    @else
        {!! Form::submit(__('Create'), ['class' => 'btn btn-submit','name'=>'import' ,'value'=>'Create']) !!}
    @endif
</div>
<div class="form-group col-sm-2" style="padding-left: 75px">
    <input type="button"  id="action" value="Import Excel" class="btn btn-submit" data-toggle="modal" data-target="#myModal">
</div>
<div class="clearfix"></div>
<br>
<hr>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Import Data</h4>
            </div>
            <div class="modal-body">
                <p>Import data from an external </p>
                <a href="{{ url('public/excel/myareas1.xlsx') }}"  download> Download The sample file to fill it with data.</a>
            <!-- <form class="form-horizontal " action="{{url('import')}}" method="post" name="upload_csv"
                  enctype="multipart/form-data"> -->
                <div class="col-md-6">
                    <input type="file" name="upload_csv" class="" >
                </div>
                {!! csrf_field() !!}


            </div>
            <div class="modal-footer">
                <button type="submit" id="submit" name="import" value="import" class="btn btn-primary button-loading "
                        data-loading-text="Loading..." style="margin-bottom: 5px">Import
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <!-- </form> -->
        </div>

    </div>
</div>
