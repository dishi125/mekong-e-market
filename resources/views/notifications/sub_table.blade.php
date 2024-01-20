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

<?php  $i=1;  $i = ($notifications->currentpage()-1)* $notifications->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $notifications])
</div>
<?php
$user=\App\Enums\UserType::toArray();
$user= array_flip($user);
$type=\App\Enums\Type::toArray();
$type= array_flip($type);
?>
<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th>No</th>
        <th class="sort">User Type</th>
        <th class="sort">Type</th>
        <th class="sort">User</th>
        <th class="sort">Title</th>
        <th class="sort">Description</th>
        <th class="sort">Date</th>
        <th class="sort">Date Created</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($notifications as $notification)
        <tr>
            <td class="text-center" width="5%">{{ $i}}</td>
            <td class="text-center" width="10%">{{ $user[$notification->user_type] }}</td>

            <td class="text-center" width="10%">{{ $notification->user_type==1?$type[$notification->type_id]:'-' }}</td>
            <td class="text-center" width="10%">{{ $notification->user_type==2?$notification->user->name:'-' }}</td>
            <td>{{ $notification->title }}</td>
            <td>{{ $notification->description }}</td>
            <td class="text-center" width="10%">{{ $notification->display_date }}</td>
            <td class="text-center" width="10%">{{ $notification->display_start_date }}</td>
            <td class='text-center action-icon' width="10%">
                @if($notification->status==1)
                    <form method="post" action="{{ route("notifications.destroy",$notification->id) }}">
                        <a href="{{ route('notifications.edit', [$notification->id]) }}"><i class="fa  fa-pencil"></i></a>
                        @method("DELETE")
                        @csrf
                        <button {{--href="{{$notification->id}}"--}} style="border:0px;with:0px" onclick="confirm('Are Sure To Remove.')"><i class="fa fa-trash"></i></button>
                    </form>
                @endif
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $notifications])
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
