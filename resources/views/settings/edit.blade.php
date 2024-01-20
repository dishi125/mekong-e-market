<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::model($setting, ['route' => ['settings.update', $setting->id], 'method' => 'patch']) !!}

                @include('settings.fields')

                {!! Form::close() !!}
            </div>
            @include('settings.table')
        </div>
    </div>
</div>

