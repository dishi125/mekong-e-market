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

<?php  $i=1;  $i = ($purchase_histories->currentpage()-1)* $purchase_histories->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $purchase_histories])
</div>
<table class="table" id="tblsort">
        <thead>
        <tr>
            <th>No</th>
            <th width="20%" class="sort">Trade</th>
            <th class="sort">category</th>
            <th class="sort">Seller</th>
            <th class="sort"> Date & Time</th>
            <th class="sort">Bid Price</th>
            <th class="sort">Unit</th>
            <th class="sort">Total Price</th>
            <th class="sort">Transaction Fee</th>
            <th colspan="3" width="10%">Action</th>
        </tr>
        </thead>
            <tbody style="background-color: #f1f1f1" class="tblbody">
            @foreach($purchase_histories as $purchase_history)
                <tr class="tbltr">
                <td class="tbltd">{{ $i }}.</td>
                <td class="tbltd">
                    <table class="table table-horizontal-border-none sub-table">
                        <tbody>
                        <tr>
                            <td>
                                <img src="{{ $purchase_history->post->product->product_image->image }}" class="product-image-round" height="70px" width="70px !important">
                            </td>
                            <td>
                                <span class="product-name"><b>{{ $purchase_history->post->product->product_name }}</b></span><br>
                                <span class="text-gray-color">{{ 'ID : '.$purchase_history->post->product->product_id }}</span><br>
                                <span class="text-gray-color">{{ $purchase_history->post->display_date_time }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td class="tbltd">{{ $purchase_history->post->product->maincategory->name }}</td>
                <td class="tbltd">{{ $purchase_history->post->product->user->name }}</td>
                <td class="tbltd">{{ \Carbon\Carbon::parse($purchase_history->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d.m.Y H:ma') }}</td>
                <td class="tbltd">RM{{ $purchase_history->bid_price }}</td>
                <td class="tbltd">{{ $purchase_history->post->weight }}</td>
                <td class="tbltd">{{ isset($purchase_history->total_amount)?'RM'.$purchase_history->total_amount:'' }}</td>
                <td class="tbltd">{{ $purchase_history->buyer_fees }} Credit</td>
                <td class='text-center action-icon tbltd'>
                    <a href=""><i class="fa  fa-eye"></i></a>
{{--                    <a href=""><i class="fa  fa-pause"></i></a>--}}
                    <a href=""><i class="fa fa-ban"></i></a>
                </td>
            </tr>
            <?php $i++;?>
            @endforeach
            </tbody>

</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $purchase_histories])
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
