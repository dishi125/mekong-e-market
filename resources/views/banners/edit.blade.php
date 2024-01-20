<div class="content">
    <div class="row">
        <div class="col-md-10">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($banner, ['route' => ['banners.update', $banner->id], 'method' => 'patch', 'enctype' => 'multipart/form-data']) !!}

                    @include('banners.fields')

               {!! Form::close() !!}
           </div>
            @include('banners.table')
       </div>
   </div>
</div>

