<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'credit_category.store','id' => 'credit_setting1']) !!}

                @include('credit_category.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('credit_category.table')
        </div>
    </div>
</div>
