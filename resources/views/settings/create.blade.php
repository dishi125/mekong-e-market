<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'settings.store']) !!}

                @include('settings.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('settings.table')
        </div>
    </div>
</div>
