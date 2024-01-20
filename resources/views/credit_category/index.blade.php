@extends('layouts.app')
@section('title-main',"Hot/ Mid/ Low Spices")
@section('content')
    <section class="content-header">
        <div class="tab-buttons">
            <a href="{{ route('credit_category.index') }}" class="btn  btn-lg btn-tab {{ Request::is('credit_category*') ? 'active' : ''}}">
                Credit Setting 1
            </a>
            <a href="{{ route('credit_setting2.index') }}"  class="btn  btn-lg btn-tab {{ Request::is('credit_setting2*') ? 'active' : ''}}">
                Credit Setting 2
            </a>

        </div>
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
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
        $("#main_category_id2").change(function () {
            var main_id = $(this).val();
            $.ajax({
                url: '{{url('credit_setting2_subcats')}}/' + main_id,
                type: 'get',
                data: {},
                async: false,
                success: function (data) {
                    if (data.status = 1) {
                        html=`<option value="" disabled>Select Sub Category</option>`;
                        $.each(data.data, function (k, v) {
                            html+=`<option value="`+k+`">`+v+`</option>`;
                        });
                        $("#sub_category_id").html(html);
                        $('#sub_category_id').removeAttr("disabled");
                    } else {
                        alert(data.error);
                    }


                }
            })
        });

        $(function()
        {
            $("#sub_category_id").select2({
                placeholder: "Select Sub Category"
            });
        });

        $('#sub_category_id').change(function() {
            var ids = $(this).val();
            console.log("ids: "+ids);
            $('input[name=sub_cat_ids]').val(ids);
            //var selections = $(test).select2('data');
            var selections = ( JSON.stringify($(this).select2('data')) );
            //console.log('Selected IDs: ' + ids);
            console.log('Selected options: ' + selections);
            //$('#selectedIDs').text(ids);
            // $('#selectedText').text(selections);
        });
        $(document).ready(function () {
            $('#credit_setting1').validate({
                rules: {
                    main_category_id: {
                        required: true,
                    },
                    hot_species_credit: {
                        required: true,
                    },
                    mid_species_credit: {
                        required: true,
                    },
                    low_species_credit: {
                        required: true,
                    },
                }
            });

            $('#credit_setting2').validate({
                rules: {
                    main_category_id: {
                        required: true,
                    },
                    spices_category: {
                        required: true,
                    },
                    sub_category_id: {
                        required: true,
                    },
                },

            });
        })
    </script>
@endpush
