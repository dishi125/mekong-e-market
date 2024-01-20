<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::model($weightUnit, ['route' => ['weightUnits.update', $weightUnit->id], 'method' => 'patch']) !!}

                @include('weight_units.fields')

                {!! Form::close() !!}
            </div>
            @include('weight_units.table')
        </div>
    </div>
</div>
