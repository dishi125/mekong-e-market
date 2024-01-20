<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'weightUnits.store']) !!}

                @include('weight_units.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('weight_units.table')
        </div>
    </div>
</div>
