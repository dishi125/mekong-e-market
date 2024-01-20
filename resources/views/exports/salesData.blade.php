<table border="1">
    <thead>

    <tr>
        <th style="text-align: center;width: 5px"><b>{{'#'}}</b></th>
        <th style="text-align: center;width: 20px"><b>{{'Trade'}}</b></th>
        <th style="text-align: center;width: 20px"><b>{{'Seller'}}</b></th>
        <th style="text-align: center;width: 20px"><b>{{'Buyer'}}</b></th>
        <th style="text-align: center;width: 20px"><b>{{'Date & Time'}}</b></th>
        <th style="text-align: center;width: 15px"><b>{{'Bid Price'}}</b></th>
        <th style="text-align: center;width: 20px"><b>{{'Unit'}}</b></th>
        <th style="text-align: center;width: 20px">
            <b>{{'Transaction Fees '}}</b>
            <br>
            <span><b>{{'(from Seller)'}}</b></span>
        </th>
        <th  style="text-align: center;width: 20px">
            <b>{{'Transaction Fees '}}</b>
            <br>
            <span><b>{{'(from Buyer)'}}</b></span>
        </th>
        <th style="text-align: center;width: 20px"><b>{{'Total Price'}}</b></th>
    </tr>

    </thead>
    <tbody>
    <?php $i = 1;?>
    @foreach($creditManagements as $creditManagement)
        <tr>
            <td style="text-align: center">{{  $i++ }}</td>
            <td>{{ $creditManagement->post->product->product_name }}</td>
            <td>{{ $creditManagement->post->product->user->name }}</td>
            <td>{!! $creditManagement->buyer->name.'<br> <span>('.(($creditManagement->transaction_status) ? 'Payment Done' : 'Payment Pending'). ') </span>' !!}
            </td>
            <td>{{ $creditManagement->display_start_date }}</td>
            <td>Rm{{ $creditManagement->bid_price }}</td>
            <td>{{ $creditManagement->post->qty.$creditManagement->post->unit }}</td>
            <td>{{ $creditManagement->post->credit_fee }} Credit</td>
            <td>{{ $creditManagement->buyer_fees }} Credit</td>
            <td>Rm{{ $creditManagement->total_amount }}</td>
        </tr>
    @endforeach
    @if($total_amount)
        <tr>
            <td colspan="8"></td>
            <td style="text-align: center;"><b>Grand Total</b></td>
            <td>Rm{{ $total_amount }}</td>
        </tr>
    @endif
    </tbody>
</table>
