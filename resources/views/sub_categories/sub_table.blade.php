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

<?php  $i=1;  $i = ($subCategories->currentpage()-1)* $subCategories->perpage() + 1; ?>
<div class="col-md-3 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $subCategories])
</div>
<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="10%">No</th>
        <th width="30%" class="sort">Sub Category</th>
        <th width="30%" class="sort">Main Category</th>
        <th width="10%" class="sort">Date Created</th>
        <th colspan="3" width="20%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($subCategories as $subCategory)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $subCategory->name }}</td>
            <td>{{ $subCategory->maincategory->name }}</td>
            <td class="text-center">{{ $subCategory->display_start_date }}</td>
            <td class="text-center action-icon">
                <a href="{{ route('subCategories.edit', [$subCategory->id]) }}"><i class="fa  fa-pencil"></i></a>
                @if($subCategory->status==0)
                    <a class="status-change" title="Active" data-id="{{$subCategory->id}}"><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change1" data-id="{{$subCategory->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                @endif
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $subCategories])
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
