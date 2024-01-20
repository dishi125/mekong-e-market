<div class="content">
    <div class="row">
        <div class="col-md-12">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($subscription, ['route' => ['subscriptions.update', $subscription->id], 'method' => 'patch']) !!}

                    @include('subscriptions.fields')

               {!! Form::close() !!}
           </div>
            @include('subscriptions.table')
            <div class="">
              @include('adminlte-templates::common.paginate', ['records' => $subscriptions])
            </div>
       </div>
   </div>
</div>

