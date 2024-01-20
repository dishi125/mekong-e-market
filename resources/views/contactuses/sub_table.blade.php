<style>
    .tblsort .sort.asc:after {
        display: inline;
        content: '↓';
    }
    .tblsort .sort.desc:after {
        display: inline;
        content: '↑';
    }
</style>

<?php  $i=1;  $i = ($contactuses->currentpage()-1)* $contactuses->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $contactuses])
</div>
<table class="table table-horizontal-border-none tblsort" id="contactuses-table" width="100%">
    <thead>
    <tr>
        <th>No</th>
        <th class="sort">User Name</th>
        <th class="sort">Email</th>
        <th class="sort">Message</th>
        <th class="sort">Date Created</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($contactuses as $contactUs)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $contactUs->user->name }}</td>
            <td>{{ $contactUs->email }}</td>
            <td>{!! $contactUs->message !!}</td>
            <td>{{ $contactUs->create_date }}</td>
            <td class='text-center action-icon' >
                {!! Form::open(['route' => ['contactuses.destroy', $contactUs->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $contactuses])
</div>

<script>
    $(function () {
        $('table')
            .on('click', '.sort', function () {
                var index = $(this).index(),
                    rows = [],
                    thClass = $(this).hasClass('asc') ? 'desc' : 'asc';

                $('.tblsort .sort').removeClass('asc desc');
                $(this).addClass(thClass);

                $('.tblsort tbody tr').each(function (index, row) {
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
                    $('.tblsort tbody').append(row);
                });
            });
    });
</script>
