<?php

namespace App\Console\Commands;

use App\Models\CreditManagement;
use App\Models\SecurityDepositTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_payment:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() //24 hour ma pay ni kre to creddit deposit mathi 10% cut
    {
        $current_date = Carbon::now()->format('Y-m-d H');

        $credit_managements = CreditManagement::whereIn('transaction_status',[0,2])
                            ->where('security_deposit_transaction_id',0)
                            ->whereRaw('"'.$current_date.'" >= DATE_ADD(created_at, INTERVAL 86400 SECOND)') //duration is 1 day
                            ->get();

        foreach ($credit_managements as $credit_management){

            $penalty =  (float)($credit_management->bid_price) * 10 /100 ;

            $security_deposit_transaction = new SecurityDepositTransaction();
            $security_deposit_transaction->type = 1;
            $security_deposit_transaction->user_profile_id = $credit_management->buyer_id;
            $security_deposit_transaction->amount = $penalty;
            $security_deposit_transaction->is_debit_by_admin = 1;//debit by admin
            $security_deposit_transaction->save();

            $credit_management->security_deposit_transaction_id = $security_deposit_transaction->id;
            $credit_management->save();
        }
    }
}
