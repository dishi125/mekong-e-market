<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::model($mainCategory, ['route' => ['mainCategories.update', $mainCategory->id], 'method' => 'patch']) !!}

                @include('main_categories.fields')

                {!! Form::close() !!}
            </div>
            @include('main_categories.table')
        </div>
    </div>

</div>
