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


<?php  $i=1;  $i = ($logisticCompanies->currentpage()-1)* $logisticCompanies->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $logisticCompanies])
</div>
<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="5%">No</th>
        <th>Profile</th>
        <th class="sort">Name</th>
        <th class="sort">Contact</th>
        <th class="sort">ID No.</th>
        <th class="sort">Email</th>
        <th class="sort">Exporter</th>
        <th class="sort">Date Join</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logisticCompanies as $logisticCompany)
        <tr>
            <td class="text-center">{{  $i }}</td>
            <td style="width: 80px"><img src="{{ url('/public/'.$logisticCompany->profile) }}" style="width: 60px;height: 60px"></td>
            <td>{{ $logisticCompany->name }}</td>
            <td>{{ $logisticCompany->contact }}</td>
            <td>{{ $logisticCompany->id_no }}</td>
            <td>{{ $logisticCompany->email }}</td>
            <td>{{ $logisticCompany->exporter_status ? "Yes" : "No" }}</td>
            <td>{{ $logisticCompany->display_join_date}}</td>
            <td class='text-center action-icon'>
                <a href="{{ route('logisticCompanies.edit', [$logisticCompany->id]) }}" ><i class="fa  fa-pencil"></i></a>
                @if($logisticCompany->status==0)
                    <a class="status-change" title="Active" data-id="{{$logisticCompany->id}}"><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change1" data-id="{{$logisticCompany->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                @endif
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $logisticCompanies])
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
