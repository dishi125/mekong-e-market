<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::model($grade, ['route' => ['grades.update', $grade->id], 'method' => 'patch']) !!}

                @include('grades.fields')

                {!! Form::close() !!}
            </div>
            @include('grades.table')
        </div>
    </div>
</div>
