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

<?php  $i=1;  $i = ($creditManagements->currentpage()-1)* $creditManagements->perpage() + 1; ?>
<div class="col-md-4 pull-left text-center  green-page">
    @include('adminlte-templates::common.paginate', ['records' => $creditManagements])
</div>
<div class="table-responsive">
    <table class="table tblsort" id="creditManagements-table">
        <thead>
        <tr>
            <th width="5%">No.</th>
            <th class="sort">Trade</th>
            <th class="sort">Seller</th>
            <th class="sort">Buyer</th>
            <th class="sort">Date & Time</th>
            <th class="sort">Trade Price</th>
            <th class="sort">Unit</th>
            <th class="sort">Transaction Fees <br>
                <span>(from Seller)</span>
            </th>
            <th class="sort">Transaction Fees <br>
                <span>(from Buyer)</span>
            </th>
            <th class="sort">Payment Type</th>
            <th class="sort">Purchase Price</th>
            <th class="sort">Service Fee</th>
            <th class="sort">Total Payout</th>
        </tr>
        </thead>
        <tbody>
        @foreach($creditManagements as $creditManagement)
            <tr class="tbltr">
                <td>{{ $creditManagement->id }}</td>
                <td class="text-center tbltd">{{  $i }}</td>
                <td class="tbltd">
                    <table class="table table-horizontal-border-none sub-table">
                        <tbody>
                        <tr>
                            <td>
                                <img src="{{ $creditManagement->product->product_image->image }}" class="product-image-round" height="70px" width="70px !important">
                            </td>
                            <td>
                                <span class="product-name"><b>{{ $creditManagement->product->product_name }}</b></span><br>
                                <span class="text-gray-color">{{ 'ID : '.$creditManagement->product->product_id }}</span><br>
                                <span class="text-gray-color">{{ $creditManagement->display_date_time }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td class="tbltd">{{ $creditManagement->product->user->name }}</td>
                @php
                    $transaction_status='';
                    if($creditManagement->creditmanagement!=null){
                        if (isset($creditManagement->creditmanagement->transaction_status)){
                            if($creditManagement->creditmanagement->transaction_status==0){
                                $transaction_status='Payment Pending';
                            }
                            elseif ($creditManagement->creditmanagement->transaction_status==1){
                                $transaction_status='Payment Done';
                            }
                            elseif ($creditManagement->creditmanagement->transaction_status==2){
                                $transaction_status='Payment Failed';
                            }
                        }
                    }
                @endphp
                <td class="tbltd">{{ isset($creditManagement->creditmanagement)?$creditManagement->creditmanagement->buyer->name:''}}<br><span class="text-gray-color">{{ isset($creditManagement->creditmanagement)?'('.$transaction_status.')':'' }}</span>
                </td>
                <td class="tbltd">{{ isset($creditManagement->creditmanagement)?$creditManagement->creditmanagement->display_start_date:'' }}</td>
                <td class="tbltd">{{ isset($creditManagement->creditmanagement) ? 'Rm'.$creditManagement->creditmanagement->bid_price:'' }}</td>
                <td class="tbltd">{{ $creditManagement->qty.$creditManagement->unit }}</td>
                <td class="tbltd">{{ $creditManagement->credit_fee }} Credit</td>
                <td class="tbltd">{{ isset($creditManagement->creditmanagement)?$creditManagement->creditmanagement->buyer_fees.' Credit':'' }} </td>
                @php
                    $payment_type='';
                    if(isset($creditManagement->creditmanagement)){
                        if($creditManagement->creditmanagement->payment_type==1){
                            $payment_type='CreditCard';
                        }
                        elseif ($creditManagement->creditmanagement->payment_type==2){
                            $payment_type='FPX';
                        }
                    }
                @endphp
                <td class="tbltd">{{ $payment_type }}</td>
                <td class="tbltd">{{ isset($creditManagement->creditmanagement)?'Rm'.$creditManagement->creditmanagement->purchase_price:'' }}</td>
                <td class="tbltd">{{ isset($creditManagement->creditmanagement->service_fee)?'Rm'.$creditManagement->creditmanagement->service_fee:'' }}</td>
                <td class="tbltd">{{ isset($creditManagement->creditmanagement->total_amount)?'Rm'.$creditManagement->creditmanagement->total_amount:'' }}</td>
            </tr>
            <?php $i++;?>
        @endforeach
        @if($grand_total_amount)
            <tr>
                <td colspan="11"></td>
                <td><b>Grand Total</b></td>
                <td>Rm{{ $grand_total_amount }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<div class="">
    @include('adminlte-templates::common.paginate', ['records' => $creditManagements])
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

                $('.tblsort tbody .tbltr').each(function (index, row) {
                    rows.push($(row).detach());
                });

                rows.sort(function (a, b) {
                    var aValue = $(a).find('.tbltd').eq(index).text(),
                        bValue = $(b).find('.tbltd').eq(index).text();

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

