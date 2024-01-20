
<div class="content">
    <div class="row">
        <div class="col-md-10">
            @include('adminlte-templates::common.errors')

            <div class="row">
                {!! Form::open(['route' => 'mainCategories.store']) !!}

                @include('main_categories.fields')

                {!! Form::close() !!}
            </div>
            <br>
                @include('main_categories.table')
        </div>
    </div>

</div>

