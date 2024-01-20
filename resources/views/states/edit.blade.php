<div class="content">
    <div class="row">
        <div class="col-md-10">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($state, ['route' => ['states.update', $state->id], 'method' => 'patch']) !!}

                    @include('states.fields')

               {!! Form::close() !!}
           </div>
            @include('states.table')
       </div>
   </div>
</div>

