<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use App\Models\CreditTransaction;
use App\Models\MyPackage;
use App\Models\MySubscription;
use App\Models\UserProfile;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan_expire:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changes in database related plan expire';

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
    public function handle() //subscribe krelo plan expire thay year na ante
    { //agar more than one plan subscribe krela hoy emathi amuk expire nathi thata ane also vdharani(credit package buy karya hoy e) credit pn che to
        //set is running status 0
        //not expire package yet (500 credit) + (500 extra credit) 1000
        //current balance(expire package+not expire package+extra credit) > 1000
        //1700 > 1000 = 700(minus by admin)
        Log::info("=====================Plan expire start=================");

        $current_date = Carbon::now()->format('Y-m-d');
        $subscriptions = MySubscription::where('is_running',1)
                                ->where('my_subscriptions.status',2)
                                ->whereRaw('"'.$current_date.'" >= DATE(end_date)') //duration is 1 year except renew
                                ->get();
        Log::info("subscription data: ",$subscriptions->toArray());
        foreach ($subscriptions as $subscription){

            $user_id = $subscription->user_profile_id;
            $subscription->is_running = 0;
            $subscription->save();

            $notExpireCredit = MySubscription::select(DB::raw('SUM(credit_transactions.amount) as remain_credit'))
                                        ->join('credit_transactions','credit_transactions.id','=','my_subscriptions.credit_transaction_id')
                                        ->where('my_subscriptions.user_profile_id',$user_id)
                                        ->where('is_running',1)
                                        ->where('my_subscriptions.status',2)
                                        ->whereNull('credit_transactions.deleted_at')
                                        ->value('remain_credit');

            $extraCredit = MyPackage::select(DB::raw('SUM(credit_transactions.amount) as extra_credit'))
                                        ->join('credit_transactions','credit_transactions.id','=','my_packages.credit_transaction_id')
                                        ->where('my_packages.user_profile_id',$user_id)
                                        ->where('transaction_status',2)
                                        ->whereNull('credit_transactions.deleted_at')
                                        ->value('extra_credit');

            $extra_total_amount = ($notExpireCredit ? (float)$notExpireCredit : 0) + ($extraCredit ? (float)$extraCredit : 0);

            /*dishita*/
            $user=UserProfile::find($user_id);
            if($user->parent_id!=0) {
                $main_user=UserProfile::where('id',$user->parent_id)->first();
                $user_credit = CommonHelper::user_credit_balance($main_user->id);
            }
            /*dishita*/
            else{
                $user_credit = CommonHelper::user_credit_balance($user_id);
            }

            if((float) $user_credit > (float) $extra_total_amount){

                //credit which should remove
                $removable_credit = (float) $user_credit - (float) $extra_total_amount;

                $user=UserProfile::find($user_id);
                /*dishita*/
                if($user->parent_id!=0) {
                    $main_user=UserProfile::where('id',$user->parent_id)->first();
                    $credit_transaction = new CreditTransaction();
                    $credit_transaction->type = 1;
                    $credit_transaction->user_profile_id = $main_user->id;
                    $credit_transaction->amount = $removable_credit;
                    $credit_transaction->is_debit_by_admin = 1;//debit by admin
                    $credit_transaction->save();
                }
                /*dishita*/
                else{
                    $credit_transaction = new CreditTransaction();
                    $credit_transaction->type = 1;
                    $credit_transaction->user_profile_id =$user_id;
                    $credit_transaction->amount = $removable_credit;
                    $credit_transaction->is_debit_by_admin = 1;//debit by admin
                    $credit_transaction->save();
                }

            }

            //remove extra sub-admin if plan expire
            $get_sub_admin_limit = MySubscription::select(DB::raw('Max(subscriptions.sub_user) as sub_admin_limit'))
                ->join('subscriptions','my_subscriptions.subscription_id','=','subscriptions.id')
                ->where('my_subscriptions.status',2)
                ->where('subscriptions.status',1)
                ->where('my_subscriptions.is_running',1)
                ->where('my_subscriptions.user_profile_id',$user_id)
                ->whereNull('subscriptions.deleted_at')
                ->value('sub_admin_limit');

            $get_sub_admin_limit = ($get_sub_admin_limit) ? (int)$get_sub_admin_limit : 0;

            //delete extra users
            UserProfile::where('parent_id',$user_id)
                        ->offset($get_sub_admin_limit)->delete();
            Log::info("=====================Plan expire end=================");

        }
    }
}
