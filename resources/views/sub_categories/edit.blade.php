<div class="content">
    <div class="row">
        <div class="col-md-10">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($subCategory, ['route' => ['subCategories.update', $subCategory->id], 'method' => 'patch']) !!}

                    @include('sub_categories.fields')

               {!! Form::close() !!}
           </div>
            @include('sub_categories.table')
       </div>
   </div>
</div>

