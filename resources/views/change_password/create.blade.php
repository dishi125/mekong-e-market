<div class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                {!! Form::open(["method"=>"post","route"=>'change.password']) !!}

                @include('change_password.fields')

                {!! Form::close() !!}
            </div>
            <br>


        </div>
    </div>
</div>

