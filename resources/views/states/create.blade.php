
<div class="content">
    <div class="row">
        <div class="col-md-10">
        @include('adminlte-templates::common.errors')
                 <div class="row">
                    {!! Form::open(['route' => 'states.store']) !!}

                        @include('states.fields')

                    {!! Form::close() !!}
                    </div>
                     <br>
                     @include('states.table')
            </div>
        </div>
    </div>

