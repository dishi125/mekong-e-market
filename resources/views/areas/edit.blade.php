<div class="content">
    <div class="row">
        <div class="col-md-10">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($area, ['route' => ['areas.update', $area->id], 'method' => 'patch']) !!}

                    @include('areas.fields')

               {!! Form::close() !!}
           </div>
            @include('areas.table')
       </div>
   </div>
</div>

