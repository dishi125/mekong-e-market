<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::model($CreditSetting2, ['route' => ['credit_setting2.update', $CreditSetting2->id], 'method' => 'patch']) !!}

                @include('credit_setting2.fields')

                {!! Form::close() !!}
            </div>
            @include('credit_setting2.table')
        </div>
    </div>
</div>
