<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'grades.store']) !!}

                @include('grades.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('grades.table')
        </div>
    </div>
</div>
