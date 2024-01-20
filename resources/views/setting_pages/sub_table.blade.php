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

<?php  $i=1;  $i = ($settingPages->currentpage()-1)* $settingPages->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $settingPages])
</div>

<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="5%">No</th>
        <th class="sort">Name</th>
        <th class="sort">Date Created</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($settingPages as $settingPage)
        <tr>
            <td class="text-center">{{  $i }}</td>
            <td>{{ $settingPage->name }}</td>
            <td>{{ $settingPage->display_start_date }}</td>
            <td class='text-center action-icon'>
                <div class='btn-group'>
                    <a href="{{ route('settingPages.show', [$settingPage->id]) }}"><i class="fa  fa-eye"></i></a>
                    <a href="{{ route('settingPages.edit', [$settingPage->id]) }}"><i class="fa  fa-pencil"></i></a>
                    @if(isset($settingPage->deleted_at))
                        <a class="status-change"  title="Active" data-id="{{$settingPage->id}}"><i class="fa fa-ban"></i></a>
                    @else
                        <a class="status-change1" data-id="{{$settingPage->id}}"  title="Deactive"><i class="fa fa-check"></i></a>
                    @endif
                </div>
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>

<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $settingPages])
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
