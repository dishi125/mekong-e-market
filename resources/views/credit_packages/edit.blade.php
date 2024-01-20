<div class="content">
    <div class="row">
        <div class="col-md-12">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($creditPackage, ['route' => ['creditPackages.update', $creditPackage->id], 'method' => 'patch']) !!}

                    @include('credit_packages.fields')

               {!! Form::close() !!}
           </div>
            @include('credit_packages.table')
       </div>
   </div>
</div>

