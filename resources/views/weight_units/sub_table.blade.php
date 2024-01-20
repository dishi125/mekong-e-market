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

<?php  $i=1;  $i = ($weightUnits->currentpage()-1)* $weightUnits->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $weightUnits])
</div>

<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="10%">No</th>
        <th class="sort">Unit</th>
{{--        <th class="sort">Credit Per Transaction</th>--}}
        <th colspan="3" width="15%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($weightUnits as $weightUnit)
        <tr>
            <td class="text-center">{{ $i }}</td>
            <td>{{ $weightUnit->unit }}</td>
{{--            <td>{{ isset($weightUnit->credit_per_transaction) ? '1 '.$weightUnit->unit.' = RM '.$weightUnit->credit_per_transaction : '-' }}</td>--}}
            <td class='text-center action-icon'>
                <div class='btn-group'>
{{--                    <a href="{{ route('weightUnits.show', [$weightUnit->id]) }}"'><i class="fa  fa-eye"></i></a>--}}
                    <a href="{{ route('weightUnits.edit', [$weightUnit->id]) }}"><i class="fa  fa-pencil"></i></a>
                    @if(isset($weightUnit->deleted_at))
                        <a class="status-change" title="Active" data-id="{{$weightUnit->id}}"><i class="fa fa-ban"></i></a>
                    @else
                        <a class="status-change1" data-id="{{$weightUnit->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                    @endif
                </div>
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $weightUnits])
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
