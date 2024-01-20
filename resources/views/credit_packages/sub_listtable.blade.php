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


<?php  $i=1;  $i = ($creditBalances->currentpage()-1)* $creditBalances->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $creditBalances])
</div>
<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th> No</th>
        <th>Photo</th>
        <th class="sort">Name</th>
        <th class="sort">User Type</th>
        <th class="sort">Amount (RM)</th>
        <th class="sort">Date & Time</th>
        <th class="sort">Balanced Credit</th>
        <th colspan="3" width="10%">Action</th>
    </tr>
    </thead>
    <tbody>

    @foreach($creditBalances as $creditBalance)
        <tr>
            <td class="text-center">{{ $i }}</td>
            <td class="text-center">
                @if($creditBalance->user->profile_pic!="")
                <img src="{{ url($creditBalance->user->profile_pic) }}" class="user-image-round" height="50px" width="50px">
                @else
                    <img src="{{url('')}}/public/logo/default_userimg.png" alt="" class="user-image-round" height="50px" width="50px">
                @endif
            </td>

            <td class="text-center">{{ $creditBalance->user->name }}</td>
            <td class="text-center">{{ $type[$creditBalance->user->user_type] }}</td>
            <td class="text-center">Top Up RM {{ $creditBalance->credit_package->amount }}</td>
            <td class="text-center">{{  $creditBalance->display_start_date }}</td>
            <td class="text-center">{{  $creditBalance->balance_top_up }}</td>
            <td class='text-center action-icon'>
                @if($creditBalance->transaction_status==0)
                    <a class="status-change" ><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change" ><i class="fa fa-check"></i></a>
                @endif

            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $creditBalances])
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

                $('#tblsort tbody tr').each(function (index, row) {
                    rows.push($(row).detach());
                });

                rows.sort(function (a, b) {
                    var aValue = $(a).find('td').eq(index).text(),
                        bValue = $(b).find('td').eq(index).text();

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
                    $('#tblsort tbody').append(row);
                });
            });
    });
</script>
