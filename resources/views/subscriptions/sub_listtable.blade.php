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

<?php  $i=1;  $i = ($subscriptionsUsers->currentpage()-1)* $subscriptionsUsers->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $subscriptionsUsers])
</div>
<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th> No</th>
        <th>Photo</th>
        <th class="sort">name</th>
        <th class="sort">User Type</th>

        <th class="sort">Package</th>
        <th class="sort">Price</th>
        <th class="sort">Start Date</th>
        <th class="sort">End Date</th>
        <th colspan="3" width="10%">Action</th>
    </tr>
    </thead>
    <tbody>

    @foreach($subscriptionsUsers as $subscriptionsUser)
        <tr>
            <td class="text-center">{{ $i }}</td>
            <td class="text-center">
                @if($subscriptionsUser->user->profile_pic!="")
                <img src="{{ url($subscriptionsUser->user->profile_pic) }}" class="user-image-round" height="50px"
                                         width="50px">
                @else
                    <img src="{{url('')}}/public/logo/default_userimg.png" alt="" class="user-image-round" height="50px" width="50px">
                @endif
            </td>
            <td class="text-center">{{ $subscriptionsUser->user->name }}</td>
            <td class="text-center">{{ isset($type[$subscriptionsUser->user->user_type]) ? $type[$subscriptionsUser->user->user_type] : 'Not defined' }}</td>
            <td class="text-center">{{ $subscriptionsUser->subscription_package->package_name }}</td>
            <td class="text-center">{{ $subscriptionsUser->subscription_package->price }}RM</td>
            <td class="text-center">{{ date('d/m/Y',($subscriptionsUser->start_date)) }}</td>
            <td class="text-center">{{ date('d/m/Y',($subscriptionsUser->end_date)) }}</td>
            <td class='text-center action-icon'>
                @if($subscriptionsUser->status==0)
                    <a class="status-change" title="Approve User"><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change" title="Block User"><i class="fa fa-check"></i></a>
                @endif

            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $subscriptionsUsers])
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
