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


<?php  $i=1;  $i = ($subscriptions->currentpage()-1)* $subscriptions->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $subscriptions])
</div>
<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="5%">No</th>
        <th width="20%" class="sort">Package Name</th>
        <th width="10%" class="sort">Price(RM)</th>
        <th class="sort">Description</th>
        <th width="10%" class="sort">Date Created</th>
        <th colspan="3" width="10%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($subscriptions as $subscription)
        <tr >
            <td class="text-center">{{ $i }}</td>
            <td>{{ $subscription->package_name }}</td>
            <td class="text-center">RM{{ $subscription->price }}</td>
            <td>{!! nl2br($subscription->description) !!}</td>
            <td class="text-center">{{ $subscription->display_start_date }}</td>
            <td class='text-center action-icon'>


                <a href="{{ route('subscriptions.edit', [$subscription->id]) }}"><i class="fa  fa-pencil"></i></a>
                @if($subscription->status==0)
                    <a class="status-change" title="Approve User" data-id="{{$subscription->id}}"><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change1" data-id="{{$subscription->id}}" title="Block User"><i class="fa fa-check"></i></a>
                @endif

            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $subscriptions])
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
