
<div class="content">
    <div class="row">
        <div class="col-md-12">
        @include('adminlte-templates::common.errors')
                 <div class="row">
                    {!! Form::open(['route' => 'logisticCompanies.store', 'enctype' => 'multipart/form-data', 'id' => 'logisticCompanies']) !!}

                        @include('logistic_companies.fields')

                    {!! Form::close() !!}
                    </div>
                     <br>
                     @include('logistic_companies.table')
                   {{-- <div class="">
                     @include('adminlte-templates::common.paginate', ['records' => $logisticCompanies])
                </div>--}}
            </div>
        </div>
    </div>

