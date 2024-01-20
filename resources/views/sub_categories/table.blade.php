<style>
    .content_table .green-page {
        position: absolute;
        top: -46px;
        right: 28%;
    }
</style>
<div class="table-display">
    <div class="search-area">
        <div class="row">
            <div class="col-md-1 pull-left text-left">
                <select name="main_cat" id="main_cat" style="outline: none">
                    <option value="">All Main Categories</option>
                    @foreach($main_categories as $main_category)
                        <option value="{{$main_category->id}}">{{$main_category->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4 pull-left">
                <div class="row">
                    <div class="col-md-5">
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
                    <div class="col-md-5">
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
{{--        @include('sub_categories.sub_table')--}}
    </div>
</div>

@section('scripts')
    <script !src="">
        var action='sub_categories';
    </script>
    @include('layouts.status')
@endsection
