
<div class="table-display">
    <div class="search-area">

        <div class="row">
            <div class="col-md-1 pull-left text-left" style="margin-right: 116px">
                <select name="main_cat" id="main_cat" style="outline: none">
                    <option value="">All Main Categories</option>
                    @foreach($main_categories as $main_category)
                        <option value="{{$main_category->id}}">{{$main_category->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 pull-left text-left">
                <select name="sub_cat" id="sub_cat" style="outline: none">
                    <option value="">Select Sub Category</option>
                    {{--   @foreach($sub_categories as $sub_category)
                      <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                  @endforeach--}}
                </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4 pull-left" style="">
                <div class="row">
                    <div class="col-md-5" style="padding-right: 0px">
                        <div class="input-group">
                            <input type="text" class="form-control search datepicker" id="start-time"
                                   placeholder="start date">
                            <span class="input-group-addon"><label for="start-time"><i
                                        class="fa  fa-calculator"></i></label></span>
                        </div>
                    </div>
                    <div class="col-md-1 to-from-text">
                        <span>TO</span>
                    </div>
                    <div class="col-md-5" style="padding-right: 0px;">
                        <div class="input-group">
                            <input type="text" class="form-control search datepicker" placeholder="end date"
                                   id="end-time">
                            <span class="input-group-addon"><label for="end-time"><i
                                        class="fa  fa-calculator"></i></label></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 pull-right">
                @include('page_view')
                <div class="input-group md-3">
                    <input type="text" class="form-control search" id="search_text" placeholder="Search">
                    <span class="input-group-addon search_by_text"><i class="fa  fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="content_table">
{{--        @include('species.sub_table')--}}
    </div>
</div>

@section('scripts')
    <script !src="">
        var action='species';

        $("#main_cat").change(function () {
            var main_id = $(this).val();
            if(main_id) {
                $.ajax({
                    url: '{{url('subcategory')}}/' + main_id,
                    type: 'get',
                    data: {},
                    async: false,
                    success: function (data) {
                        if (data.status = 1) {
                            html = `<option value="">Select Sub Category</option>`;
                            $.each(data.data, function (k, v) {
                                html += `<option value="` + k + `">` + v + `</option>`;
                            });
                            $("#sub_cat").html(html);
                        } else {
                            alert(data.error);
                        }
                    }
                })
            }
            else{
                html = `<option value="">Select Sub Category</option>`;
                $("#sub_cat").html(html);
            }
        });
    </script>
    @include('layouts.status')
@endsection
