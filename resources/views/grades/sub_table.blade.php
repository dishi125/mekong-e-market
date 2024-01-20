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

<?php  $i=1;  $i = ($grades->currentpage()-1)* $grades->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $grades])
</div>

<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="10%">No</th>
        <th class="sort">Grade</th>
        <th colspan="3" width="15%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($grades as $grade)
        <tr>
            <td class="text-center">{{ $i }}</td>
            <td>{{ $grade->name }}</td>
            <td class='text-center action-icon'>
                <div class='btn-group'>
{{--                    <a href="{{ route('grades.show', [$grade->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>--}}
                    <a href="{{ route('grades.edit', [$grade->id]) }}"><i class="fa  fa-pencil"></i></a>
                    @if(isset($grade->deleted_at))
                        <a class="status-change" title="Active" data-id="{{$grade->id}}"><i class="fa fa-ban"></i></a>
                    @else
                        <a class="status-change1" data-id="{{$grade->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                    @endif
                </div>
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $grades])
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
