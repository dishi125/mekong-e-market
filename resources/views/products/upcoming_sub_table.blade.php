<style>
    #tblsort .sort.asc:after {
        display: inline;
        content: '↓';
    }
    #tblsort .sort.desc:after {
        display: inline;
        content: '↑';
    }
</style>


<?php  $i=1;  $i = ($posts->currentpage()-1)* $posts->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $posts])
</div>
<table class="table" id="tblsort">
    <thead>
    <tr >
        <th>No</th>
        <th width="20%" class="sort">Trade</th>
        <th class="sort">category</th>
        <th class="sort">seller</th>
        <th class="sort">Start Date & Time</th>
        <th class="sort">Start In</th>
        <th class="sort">Trade Price</th>
        <th class="sort">Unit</th>
        <th colspan="3" width="10%">Action</th>
    </tr>
    </thead>
    <tbody class="tblbody">
    @foreach($posts ?? [] as $post)
        <tr class="tbltr">
            <td class="text-center tbltd">{{ $i }}</td>
            <td class="tbltd">
                <table class="table table-horizontal-border-none sub-table">
                    <tbody>
                        <tr>
                            <td>
                                <img src="{{ $post->product->product_image->image }}" class="product-image-round" height="70px" width="70px !important">
                            </td>
                            <td>
                                <span class="product-name"><b>{{ $post->product->product_name }}</b></span><br>
                                <span class="text-gray-color">{{ 'ID : '.$post->product->product_id }}</span><br>
                                <span class="text-gray-color">{{ $post->display_created_dateTime }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td class="tbltd">{{ $post->product->maincategory->name }}</td>
            <td class="tbltd">{{ $post->product->user->name }}</td>
            <td class="tbltd">{{ $post->display_date_time }}</td>
            <td class="tbltd">{{ gmdate("H:i:s", (int)$post->sec_diff) }}</td>
            <td class="tbltd">{{ 'From RM'.$post->starting_price.'/'.$post->unit }}</td>
            <td class="tbltd">{{ $post->weight }}</td>
            <td class='text-center action-icon tbltd'>
                <a href="{{ url('upcomingdetail').'/'.$post->id }}"><i class="fa  fa-eye"></i></a>
                <a href="#"><i class="fa"><img src="{{url('/public/assets/pause.png')}}" height="18px" width="18px"></i></a>
                <a href="#"><i class="fa fa-ban"></i></a>
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $posts])
</div>

<script>
    $(function () {
        $('table')
            .on('click', '.sort', function () {
                var index = $(this).index(),
                    rows = [],
                    thClass = $(this).hasClass('asc') ? 'desc' : 'asc';

                $('#tblsort .sort').removeClass('asc desc');
                $(this).addClass(thClass);

                $('#tblsort .tblbody .tbltr').each(function (index, row) {
                    rows.push($(row).detach());
                });

                rows.sort(function (a, b) {
                    var aValue = $(a).find('.tbltd').eq(index).text(),
                        bValue = $(b).find('.tbltd').eq(index).text();

                    return aValue > bValue
                        ? 1
                        : aValue < bValue
                            ? -1
                            : 0;
                });

                if ($(this).hasClass('desc')) {
                    rows.reverse();
                }

                $.each(rows, function (index, row) {
                    $('#tblsort .tblbody').append(row);
                });
            });
    });
</script>
