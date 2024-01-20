<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Sales Report</title>

        <meta http-equiv="Content-Type" content="text/html"/>
        <style>
            table {
                border-collapse: collapse;
            }
            th,td {
                padding: 5px;
                word-break: break-word;
            }
            .font-size-14 {
                font-size: 14px;
            }
             @font-face {
                font-family: SimHei;
                src: url({{storage_path('fonts/SimHei.ttf')}});
            }

            body {
                font-family: SimHei !important;
            }
        </style>
    </head>
    <body>
        <div class="row">
            <table border="1" width="100%" style="width: 100%">
            <thead>
            <tr>
                <th style="text-align: center;width: 5%"><b>{{'#'}}</b></th>
                <th style="text-align: center;width: 14%"><b>{{'Trade'}}</b></th>
                <th style="text-align: center;width: 13%"><b>{{'Seller'}}</b></th>
                <th style="text-align: center;width: 13%"><b>{{'Buyer'}}</b></th>
                <th style="text-align: center;width: 10%"><b>{{'Date & Time'}}</b></th>
                <th style="text-align: center;width: 7%"><b>{{'Bid Price (MYR)'}}</b></th>
                <th style="text-align: center;width: 7%"><b>{{'Unit'}}</b></th>
                <th style="text-align: center;width: 10%">
                    <b>{{'Transaction Fees'}}</b>
                    <br>
                    <span><b>{{'(from Seller)'}}</b></span>
                </th>
                <th  style="text-align: center;width: 10%">
                    <b>{{'Transaction Fees'}}</b>
                    <br>
                    <span><b>{{'(from Buyer)'}}</b></span>
                </th>
                <th style="text-align: center;width: 10%"><b>{{'Total Price (MYR)'}}</b></th>
            </tr>

            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($creditManagements as $creditManagement)
                <tr>
                    <td style="text-align: center">{{  $i++ }}</td>
                    <td>{{ $creditManagement->post->product->product_name }}</td>
                    <td>{{ $creditManagement->post->product->user->name }}</td>
                    <td>{!! $creditManagement->buyer->name.'<br> <span class="font-size-14">('.(($creditManagement->transaction_status) ? 'Payment Done' : 'Payment Pending'). ') </span>' !!}
                    </td>
                    <td>{{ $creditManagement->display_start_date }}</td>
                    <td>{{\App\Helpers\CommonHelper::number_format_short($creditManagement->bid_price,2)}}</td>
                    <td>{{ $creditManagement->post->qty.' '.$creditManagement->post->unit }}</td>
                    <td>{{ $creditManagement->post->credit_fee }} Credit</td>
                    <td>{{ $creditManagement->buyer_fees }} Credit</td>
                    <td>{{ \App\Helpers\CommonHelper::number_format_short($creditManagement->total_amount,2 )}}</td>
                </tr>
            @endforeach
            @if($total_amount)
                <tr>
                    <td colspan="8"></td>
                    <td style="text-align: center;"><b>{{'Grand Total  (MYR)'}}</b></td>
                    <td>{{ \App\Helpers\CommonHelper::number_format_short($total_amount,4 ) }}</td>
                </tr>
            @endif
            </tbody>
        </table>
        </div>
    </body>
</html>

