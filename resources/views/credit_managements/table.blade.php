<div class="table-display">
    <div class="search-area">
        <div class="row">
            {{--<div class="col-md-10 pull-left">
                {{Form::open(['route'=>'set_credit'])}}
                <div class="col-sm-7">
                    <div class="col-sm-4">
                        {!! Form::label('credit_per_transaction', 'Credit Per Transaction') !!}
                    </div>
                    <div class="col-sm-7 form-group">
                        {!! Form::text('credit_per_transaction',($f->credit_per_transaction) ? $f->credit_per_transaction : null, ['class' => 'form-control',"placeholder"=>"Credit per transaction"]) !!}
                    </div>
                </div>
                <div class="col-sm-2">
                    {!! Form::submit('set', ['class' => 'btn btn-submit']) !!}
                </div>
                {!! Form::close() !!}
            </div>--}}
            {{Form::open(['route'=>'frame_setcreditrm'])}}
            <div class="col-sm-4">
                <div class="col-sm-2">
                    {!! Form::label('credit', 'Credit') !!}
                </div>
                <div class="col-sm-4 form-group">
                    {!! Form::text('credit',  ($f->credit) ? $f->credit : null, ['class' => 'form-control',"placeholder"=>"Credit"]) !!}
                </div>
                <div class="col-sm-2">
                    {!! Form::label('rm', '= RM') !!}
                </div>
                <div class="col-sm-4 form-group">
                    {!! Form::text('rm',  ($f->rm) ? $f->rm : null, ['class' => 'form-control',"placeholder"=>"RM"]) !!}
                </div>
            </div>
            <div class="col-sm-1">
                {!! Form::submit('set', ['class' => 'btn btn-submit']) !!}
            </div>
            {!! Form::close() !!}

            <div class="col-md-1 pull-left text-left" style="margin-right: 81px">
                <select id="buyer_filer" style="outline: none">
                    <option value="" disabled selected>Select with/without buyer</option>
                    <option value="with">With Buyer</option>
                    <option value="without">Without Buyer</option>
                </select>
            </div>
            <div class="col-md-1 pull-left text-left" style="margin-right: 63px">
                <select id="payment_filer" style="outline: none">
                    <option value="" disabled selected>Select payment status</option>
                    <option value="1">Payment Done</option>
                    <option value="-1">Payment Pending</option>
                </select>
            </div>
            <div class="col-md-1 pull-left text-left" style="margin-right: 10px">
                <select id="paymenttype_filer" style="outline: none">
                    <option value="" disabled selected>Select payment type</option>
                    <option value="2">FPX</option>
                    <option value="1">CreditCard</option>
                </select>
            </div>
            <div class="row pull-right col-md-2">
                <div class="col-md-1" style="padding-right: 30px">
                    {!! Form::open(['route' => ['salesReportExport'], 'method' => 'get']) !!}
                    {!! Form::hidden('search', null, ['id' => 'search']) !!}
                    {!! Form::hidden('start_date', null, ['id' => 'start_date']) !!}
                    {!! Form::hidden('end_date', null, ['id' => 'end_date']) !!}
                    {!! Form::button('<i class="fa fa-download" aria-hidden="true" style="color: #818286;font-size: 20px;"></i>
                        <span class="export">Export</span>', ['type' => 'submit','style' => 'padding: 0px;background: none;']) !!}
                </div>
                <div class="col-md-1  text-center" >
                    <select name="export_type" id="export_type" style="outline: none">
                        <option value="0">Excel</option>
                        <option value="1">PDF</option>
                    </select>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4 pull-left">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" class="form-control search datepicker" id="start-time"
                                   placeholder="start date">
                            <span class="input-group-addon"><label for="start-time"><i
                                        class="fa  fa-calculator"></i></label></span>
                        </div>
                    </div>
                    <div class="col-md-1 to-from-text">
                        <span>TO</span>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" class="form-control search datepicker" placeholder="end date"
                                   id="end-time">
                            <span class="input-group-addon"><label for="end-time"><i
                                        class="fa  fa-calculator"></i></label></span>
                        </div>
                    </div>
                   {{-- <div class="col-md-1">
                        {!! Form::open(['route' => ['salesReportExport'], 'method' => 'get']) !!}
                        {!! Form::hidden('search', null, ['id' => 'search']) !!}
                        {!! Form::hidden('start_date', null, ['id' => 'start_date']) !!}
                        {!! Form::hidden('end_date', null, ['id' => 'end_date']) !!}
                        {!! Form::button('<i class="fa fa-download" aria-hidden="true" style="color: #818286;font-size: 20px;"></i>
                            <span class="export">Export</span>', ['type' => 'submit','style' => 'padding: 0px;background: none;']) !!}
                    </div>--}}
                </div>
            </div>
           {{-- <div class="col-md-1  text-center" >
                <select name="export_type" id="export_type" style="outline: none">
                    <option value="0">Excel</option>
                    <option value="1">PDF</option>
                </select>
                {!! Form::close() !!}
            </div>--}}
            <div class="col-md-4 pull-right">
                @include('page_view')
                <div class="input-group md-3">
                    <input type="text" class="form-control search" id="search_text" placeholder="Search">
                    <span class="input-group-addon search_by_text"><i class="fa  fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class = "content_table">
        {{--            @include('credit_managements.sub_table')--}}
    </div>
</div>
