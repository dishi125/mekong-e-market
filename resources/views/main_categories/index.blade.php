@extends('layouts.app')

@section('title-main',"Category Management")
@section('content')
    <section class="content-header">
        <div class="tab-buttons">
            <a href="{{ route('mainCategories.index') }}" class="btn  btn-lg btn-tab {{ Request::is('mainCategories*') ? 'active' : ''}}">
                Main Category
                <input type="hidden" name="page_url" id="page_url" value="{{ url('ajax/main_category') }}">
            </a>
            <a href="{{ route('subCategories.index') }}"  class="btn  btn-lg btn-tab {{ Request::is('subCategories*') ? 'active' : ''}}">
                 Sub Category
                <input type="hidden" name="page_url" id="page_url" value="{{ url('ajax/sub_category') }}">
            </a>
            <a  href="{{ route('species.index') }}"  class="btn  btn-lg btn-tab {{ Request::is('species*') ? 'active' : ''}}">
                Species
                <input type="hidden" name="page_url" id="page_url" value="{{ url('ajax/species') }}">
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
    <script>
        $("#main_category_id").change(function () {
            var main_id = $(this).val();
            $.ajax({
                url: '{{url('subcategory')}}/' + main_id,
                type: 'get',
                data: {},
                async: false,
                success: function (data) {
                    if (data.status = 1) {
                        html=`<option value="">Select Sub Category</option>`;
                        $.each(data.data, function (k, v) {
                            html+=`<option value="`+k+`">`+v+`</option>`;
                        });
                        $("#sub_category_id").html(html);
                    } else {
                        alert(data.error);
                    }


                }
            })
        });
    </script>
@endpush

