<div class="content">
    <div class="row">
        <div class="col-md-12">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'notifications.store']) !!}

                @include('notifications.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('notifications.table')
        </div>
    </div>
</div>

