<div class="content">
    <div class="row">
        <div class="col-md-10">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($logisticCompany, ['route' => ['logisticCompanies.update', $logisticCompany->id], 'enctype' => 'multipart/form-data', 'id' => 'logisticCompanies']) !!}

                    @include('logistic_companies.fields')

               {!! Form::close() !!}
           </div>
            @include('logistic_companies.table')
            {{--<div class="">
              @include('adminlte-templates::common.paginate', ['records' => $logisticCompanies])
            </div>--}}
       </div>
   </div>
</div>

