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

<?php  $i=1;  $i = ($bannerPackages->currentpage()-1)* $bannerPackages->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $bannerPackages])
</div>

<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="5%">No</th>
        <th class="sort">Banner Location</th>
        <th class="sort">Price(RM)</th>
        <th class="sort">Duration</th>
        <th class="sort">Date Created</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($bannerPackages as $bannerPackage)
        <tr>
            <td class="text-center">{{  $i }}</td>
            <td>{{ $bannerPackage->location }}</td>
            <td>RM{{ $bannerPackage->price }}</td>
            <td>{{ $bannerPackage->display_duration. ' ' . $bannerPackage->duration_type}}</td>
            <td>{{ $bannerPackage->display_date }}</td>
            <td class='text-center action-icon'>
                <a href="{{ route('bannerPackages.edit', [$bannerPackage->id]) }}" ><i class="fa  fa-pencil"></i></a>
                @if($bannerPackage->status==0)
                    <a class="status-change" title="Active" data-id="{{$bannerPackage->id}}"><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change1" data-id="{{$bannerPackage->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                @endif
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>

<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $bannerPackages])
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
