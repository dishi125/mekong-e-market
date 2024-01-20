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

<?php  $i=1;  $i = ($userProfiles->currentpage()-1)* $userProfiles->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $userProfiles])
</div>
<table class="table table-horizontal-border-none " id="tblsort">
    <thead>
    <tr>
        <th>No.</th>
        <th>Image</th>
        <th class="sort">Name</th>
        <th class="sort">User type</th>
        <th class="sort">Contact</th>
        <th class="sort">Email</th>
        <th class="sort">State</th>
        <th class="sort">Join Date</th>
        <th>action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($userProfiles as $userProfile)
        <tr>
            <td class="text-center">{{ $i }}.</td>
            <td>
                @if($userProfile->profile_pic!="")
                    <img src="{{url($userProfile->profile_pic)}}" class="user-image-round" height="50px" width="50px">
                @else
                    <img src="{{url('')}}/public/logo/default_userimg.png" alt="" class="user-image-round" height="50px" width="50px">
                @endif
            </td>
            <td class="text-center">{{ $userProfile->name }}</td>
            <td class="text-center">{{isset($type[$userProfile->user_type]) ? $type[$userProfile->user_type] : 'Not defined'}}</td>
            <td class="text-center">{{ $userProfile->phone_no ? ltrim($userProfile->phone_no,"+60") : '-' }}</td>
            <td class="text-center">{{ $userProfile->email ? $userProfile->email : '-' }}</td>
            <td class="text-center">{{ isset($userProfile->state->name) ? $userProfile->state->name : '-' }}</td>
            <td class="text-center">{{ $userProfile->display_start_date ? $userProfile->display_start_date : '-' }}</td>
            <td class='text-center action-icon'>
{{--                <a href="{{ route('userProfiles.show', [$userProfile->id]) }}"><i class="fa  fa-eye"></i></a>--}}
               {{-- @if($userProfile->is_approved_status == 0)
                    <a class="status-change" onclick="confirm('Are You Sure To Approve User.')" title="Approve User" data-id="{{$userProfile->id}}"><i class="fa fa-ban"></i></a>
                @else
                    <a class="status-change" data-id="{{$userProfile->id}}" onclick="confirm('Are You Sure To Block User.')" title="Block User"><i class="fa fa-check"></i></a>
                @endif--}}
                <button type='button' class='btn btn-success accept' id="accept" data-id="{{ $userProfile->id }}">Approve</button> |
                <button type='button' class='btn btn-danger reject' id="reject" data-id="{{ $userProfile->id }}">Reject</button>
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $userProfiles])
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
<script>
    $(document).ready(function () {
        $( ".accept" ).click(function() {
            block_ui();
            var userid=$(this).attr('data-id');
            var status="accept";
            $.ajax({
                type: 'POST',
                url: "{{ url('set_preferred_approved_status') }}",
                data: {_token: "{{ csrf_token() }}", userid: userid, status:status },
                success: function (res) {
                    unblock_ui();
                    if (res.success == true) {
                        location.reload();
                    }
                    if (res.success == false) {
                        alert("something went wrong.");
                    }
                },
            });
        });

        $( ".reject" ).click(function() {
            block_ui();
            var userid=$(this).attr('data-id');
            var status="reject";
            $.ajax({
                type: 'POST',
                url: "{{ url('set_preferred_approved_status') }}",
                data: {_token: "{{ csrf_token() }}", userid: userid, status:status },
                success: function (res) {
                    unblock_ui();
                    if (res.success == true) {
                        location.reload();
                    }
                    if (res.success == false) {
                        console.log("set_preferred_approved_status failed.");
                    }
                },
            });
        });

        function block_ui()
        {
            $.blockUI({ message: '<img src="{{url('public/assets/loader1.gif')}}"/>',
                css: {
                    border: 'none',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    backgroundColor: 'unset',
                }});
        }

        function unblock_ui()
        {
            $.unblockUI();
        }

    });

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
