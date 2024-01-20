<div class="content">
    <div class="row">
        <div class="col-md-12">
            @include('adminlte-templates::common.errors')
            @if(!isset($index))
                @section('sub-title',"Add Banner")
                <div class="row">
                    {!! Form::open(['route' => 'banners.store', 'enctype' => 'multipart/form-data']) !!}

                    @include('banners.fields')

                    {!! Form::close() !!}
                 </div>
                     <br>
                 @else
                     @include('banners.table')
                 @endif
            </div>
        </div>
</div>

