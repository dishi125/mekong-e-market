<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::model($specie, ['route' => ['species.update', $specie->id], 'method' => 'patch']) !!}

                @include('species.fields')

                {!! Form::close() !!}
            </div>
            @include('species.table')
        </div>
    </div>
</div>

