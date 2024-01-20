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

<?php  $i=1;  $i = ($creditPackages->currentpage()-1)* $creditPackages->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $creditPackages])
</div>
<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="10%">No.</th>
        <th width="35%" class="sort">Amount(MYR)</th>
        <th width="35%" class="sort">Credit</th>
        <th width="10%" class="sort">Date Created</th>
        <th colspan="3" width="10%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($creditPackages as $creditPackage)
        <tr>
            <td class="text-center">{{ $i }}.</td>
            <td>RM &nbsp;{{ $creditPackage->amount }}</td>
            <td>{{ $creditPackage->credit }}</td>
            <td class="text-center">{{ $creditPackage->display_start_date }}</td>
            <td class='text-center action-icon'>


                <a href="{{ route('creditPackages.edit', [$creditPackage->id]) }}"><i class="fa  fa-pencil"></i></a>
                @if($creditPackage->status==0)
                    <a class="status-change" title="Approve User" data-id="{{$creditPackage->id}}"><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change1" data-id="{{$creditPackage->id}}" title="Block User"><i class="fa fa-check"></i></a>
                @endif

            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $creditPackages])
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
