<div class="content">
    <div class="row">
        <div class="col-md-12">
       @include('adminlte-templates::common.errors')

           <div class="row">
               {!! Form::model($notification, ['route' => ['notifications.update', $notification->id], 'method' => 'patch']) !!}

                    @include('notifications.fields')

               {!! Form::close() !!}
           </div>
            @include('notifications.table')
       </div>
   </div>
</div>

