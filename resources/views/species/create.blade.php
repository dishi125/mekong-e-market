<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'species.store']) !!}

                @include('species.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('species.table')
        </div>
    </div>
</div>

