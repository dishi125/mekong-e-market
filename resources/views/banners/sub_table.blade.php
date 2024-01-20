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

<?php  $i=1;  $i = ($banners->currentpage()-1)* $banners->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $banners])
</div>

<table class="table table-horizontal-border-none" id="tblsort">
    <thead>
    <tr>
        <th width="5%">No</th>
        <th>Photo/Video</th>
        <th class="sort">Name</th>
        <th class="sort">Contact</th>
        <th class="sort">Location</th>
        <th class="sort">Price</th>
        <th class="sort">Start Date</th>
        <th class="sort">Duration</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @php($now=time())
    @foreach($banners as $banner)
        <tr>
            <td class="text-center">{{  $i }}</td>
            <td style="width: 80px">
                @if($banner->type==0)
                <img src="{{ url('/public/'.$banner->banner_photo) }}" style="width: 60px;height: 60px">
                @else
                <a href="{{ $banner->banner_photo }}" target="_blank">{{ $banner->banner_photo }}</a>
                @endif
            </td>
            <td>{{ $banner->name }}</td>
            <td>{{ $banner->contact }}</td>
            <td>{{$banner->location}}</td>
            <td>RM{{$banner->price}}</td>
            <td>{{$banner->display_start_date}}</td>
            <td>{{$banner->display_duration . ' ' . $banner->duration_type}}</td>
            <td class='text-center action-icon'>
                <a href="{{ route('banners.edit', [$banner->id]) }}" ><i class="fa  fa-pencil"></i></a>
                @if( $banner->status == 0 )
                        <a class="status-change" title="Active" data-id="{{$banner->id}}"><i class="fa fa-ban"></i></a>
                @else
                        <a class="status-change1" data-id="{{$banner->id}}" title="Deactive"><i class="fa fa-check"></i></a>
                @endif
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>

<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $banners])
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
