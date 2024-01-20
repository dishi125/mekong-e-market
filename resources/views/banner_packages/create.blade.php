<div class="content">
    <div class="row">
        <div class="col-md-10">
             @include('adminlte-templates::common.errors')
             <div class="row">
                {!! Form::open(['route' => 'bannerPackages.store']) !!}

                    @include('banner_packages.fields')

                {!! Form::close() !!}
             </div>
             <br>
             @include('banner_packages.table')
        </div>
    </div>
</div>

