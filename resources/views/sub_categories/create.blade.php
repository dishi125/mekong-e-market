
<div class="content">
    <div class="row">
        <div class="col-md-10">
        @include('adminlte-templates::common.errors')
                 <div class="row">
                    {!! Form::open(['route' => 'subCategories.store']) !!}

                        @include('sub_categories.fields')

                    {!! Form::close() !!}
                    </div>
                     <br>
                     @include('sub_categories.table')
            </div>
        </div>
    </div>

