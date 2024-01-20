
<div class="content">
    <div class="row">
        <div class="col-md-12">
        @include('adminlte-templates::common.errors')
                 <div class="row">
                    {!! Form::open(['route' => 'creditPackages.store']) !!}

                        @include('credit_packages.fields')

                    {!! Form::close() !!}
                    </div>
                     <br>
                     @include('credit_packages.table')
            </div>
        </div>
    </div>

