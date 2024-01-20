<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::model($CreditSetting1, ['route' => ['credit_category.update', $CreditSetting1->id], 'method' => 'patch']) !!}

                @include('credit_category.fields')

                {!! Form::close() !!}
            </div>
            @include('credit_category.table')
        </div>
    </div>
</div>
