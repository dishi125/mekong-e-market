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

<?php  $i=1;  $i = ($CreditSetting1->currentpage()-1)* $CreditSetting1->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $CreditSetting1])
</div>

<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="10%">No.</th>
        <th class="sort">Main Category</th>
        <th class="sort">Hot Spices Credit Per Transaction</th>
        <th class="sort">Mid Spices Credit Per Transaction</th>
        <th class="sort">Low Spices Credit Per Transaction</th>
        <th class="sort">Date Created</th>
        <th colspan="3" width="15%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($CreditSetting1 as $Credit_Setting1)
        <tr>
            <td class="text-center">{{ $i }}</td>
            <td>{{ $Credit_Setting1->main_category->name }}</td>
            <td>{{ $Credit_Setting1->hot_species_credit }}</td>
            <td>{{ $Credit_Setting1->mid_species_credit }}</td>
            <td>{{ $Credit_Setting1->low_species_credit }}</td>
            <td>{{  $Credit_Setting1->display_created_date}}</td>
            <td class='text-center action-icon'>
                <div class='btn-group'>
{{--                    <a href="{{ route('grades.show', [$grade->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>--}}
                    <a href="{{ route('credit_category.edit', [$Credit_Setting1->id]) }}"><i class="fa  fa-pencil"></i></a>
                    @if($Credit_Setting1->status==0)
                        <a class="status-change" title="Active" data-id="{{$Credit_Setting1->id}}"><i class="fa fa-ban"></i></a>
                    @else
                        <a class="status-change1" data-id="{{$Credit_Setting1->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                    @endif
                </div>
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $CreditSetting1])
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
