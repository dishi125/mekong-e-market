<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')
            <div class="row">
                {!! Form::open(['route' => 'settingPages.store']) !!}

                @include('setting_pages.fields')

                {!! Form::close() !!}
            </div>
            <br>
            @include('setting_pages.table')
        </div>
    </div>
</div>
