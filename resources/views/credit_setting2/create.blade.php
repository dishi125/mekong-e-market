<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'credit_setting2.store', 'id'=>'credit_setting2']) !!}

                @include('credit_setting2.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('credit_setting2.table')
        </div>
    </div>
</div>
