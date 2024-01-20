<div class="content">
    <div class="row">
        <div class="col-md-10">
        @include('adminlte-templates::common.errors')
             <div class="row">
                {!! Form::open(['route' => 'areas.store','enctype' => 'multipart/form-data']) !!}

                    @include('areas.fields')

                {!! Form::close() !!}
            </div>
             <br>
             @include('areas.table')
        </div>
    </div>
</div>

