<div class="table-display">

        <div class="search-area">

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
        <div class = "content_table">
{{--            @include('main_categories.sub_table')--}}
        </div>
</div>
@section('scripts')
    <script !src="">
        var action='main_categories';
    </script>
    @include('layouts.status')
@endsection
