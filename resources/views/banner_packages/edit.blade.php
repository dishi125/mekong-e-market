<div class="content">
    <div class="row">
        <div class="col-md-10">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($bannerPackage, ['route' => ['bannerPackages.update', $bannerPackage->id], 'method' => 'patch']) !!}

                    @include('banner_packages.fields')

               {!! Form::close() !!}
           </div>
            @include('banner_packages.table')
       </div>
   </div>
</div>

