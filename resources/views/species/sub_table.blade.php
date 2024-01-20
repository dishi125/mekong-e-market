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

<?php  $i=1;  $i = ($species->currentpage()-1)* $species->perpage() + 1; ?>
<div class="col-md-2 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $species])
</div>
<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="5%">No</th>
        <th class="sort">Species</th>
        <th class="sort">Sub Category</th>
        <th class="sort">Main Category</th>
        <th width="15%" class="sort">Date Created</th>
        <th colspan="3" width="20%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($species as $specie)
        <tr>
            <td class="text-center">{{  $i }}</td>
            <td>{{ $specie->name }}</td>
            <td>{{ $specie->subcategory->name }}</td>
            <td>{{   $specie->subcategory->maincategory->name }}</td>
            <td class="text-center">{{ $specie->display_start_date }}</td>
            <td class='text-center action-icon'>
                <a href="{{ route('species.edit', [$specie->id]) }}"><i class="fa  fa-pencil"></i></a>
                @if($specie->status==0)
                    <a class="status-change" title="Active" data-id="{{$specie->id}}"><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change1" data-id="{{$specie->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                @endif
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $species])
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
