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

<?php  $i=1;  $i = ($CreditSetting2->currentpage()-1)* $CreditSetting2->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $CreditSetting2])
</div>

<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="10%">No</th>
        <th class="sort">Main Category</th>
        <th class="sort">Spices</th>
        <th class="sort">Credit Per Transaction</th>
        <th class="sort">Sub Category</th>
        <th class="sort">Date Created</th>
        <th colspan="3" width="15%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($CreditSetting2 as $Credit_Setting2)
        <tr>
            <td class="text-center">{{ $i }}</td>
            <td>{{ $Credit_Setting2->main_category->name }}</td>
            <td>{{ $Credit_Setting2->spices_category }}</td>
            <td>{{ $Credit_Setting2->credit_per_transaction }}</td>
            @php
                $exploded_subcats=explode(",",$Credit_Setting2->sub_categories);
                $s=array();
                foreach ($exploded_subcats as $subcat){
                    $subcatname=\App\Models\SubCategory::where('id',$subcat)->pluck('name')->first();
                    array_push($s,$subcatname);
                }
                $imploded_s=implode(",",$s);
            @endphp
            <td>{{ $imploded_s }}</td>
            <td>{{ $Credit_Setting2->display_created_date }}</td>
            <td class='text-center action-icon'>
                <div class='btn-group'>
{{--                    <a href="{{ route('weightUnits.show', [$weightUnit->id]) }}"'><i class="fa  fa-eye"></i></a>--}}
                    <a href="{{ route('credit_setting2.edit', [$Credit_Setting2->id]) }}"><i class="fa  fa-pencil"></i></a>
                    @if($Credit_Setting2->status==0)
                        <a class="status-change" title="Active" data-id="{{$Credit_Setting2->id}}"><i class="fa fa-ban"></i></a>
                    @else
                        <a class="status-change1" data-id="{{$Credit_Setting2->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                    @endif
                </div>
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $CreditSetting2])
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
