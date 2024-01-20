<div class="content">
    <div class="row">
        <div class="col-md-12">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'subscriptions.store']) !!}

                @include('subscriptions.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('subscriptions.table')
            <div class="">
                @include('adminlte-templates::common.paginate', ['records' => $subscriptions])
            </div>
        </div>
    </div>
</div>

