@extends('layouts.app')
@section('title-main',"Logistic Company")
@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        @if(isset($edit))

            @include($view.'.edit')
        @else
            @include($view.'.create')
        @endif
        <div class="clearfix"></div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        $("#state_id").change(function () {
            var state_id = $(this).val();
            $.ajax({
                url: '{{url('area')}}/' + state_id,
                type: 'get',
                data: {},
                async: false,
                success: function (data) {
                    if (data.status = 1) {
                        html=`<option value="">Select Area</option>`;
                        $.each(data.message, function (k, v) {

                            html+=`<option value="`+k+`">`+v+`</option>`;
                        });
                        // alert(html);
                        // $("#area_id").html(html);
                        $("select[name='area_id']").html(html);
                    } else {
                        alert(data.error);
                    }
                }
            })
        });
    </script>
@endpush


