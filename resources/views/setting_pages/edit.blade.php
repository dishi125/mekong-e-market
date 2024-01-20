<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::model($settingPage, ['route' => ['settingPages.update', $settingPage->id], 'method' => 'patch']) !!}

                @include('setting_pages.fields')

                {!! Form::close() !!}
            </div>
            @include('setting_pages.table')
        </div>
    </div>
</div>
