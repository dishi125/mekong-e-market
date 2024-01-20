<?php

namespace App\Repositories;

use App\Helpers\CommonHelper;
use App\Models\CreditPackage;
use App\Models\CreditTransaction;
use App\Models\Frame;
use App\Models\MyPackage;
use App\Models\SecurityDepositTransaction;
use App\Models\Subscription;
use App\Models\UserProfile;
use App\Repositories\BaseRepository;
use App\Models\MySubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class SubscriptionRepository
 * @package App\Repositories
 * @version February 6, 2020, 4:33 am UTC
*/

class SubscriptionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'package_name',
        'price',
        'description',
        'credit',
        'security_deposit',
        'sub_user',
        'bidding',
        'package_type',
        'status'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Subscription::class;
    }

    public function myCredit($userid)
    {
        $response = array();
//        $sub_userid=UserProfile::where('parent_id',$request->user_profile_id)->get();

        $credit_package = CreditPackage::where('status',1)
            ->get(['id','amount','credit','status']);


        $user_credit_balance = CommonHelper::user_credit_balance($userid);

        $user_security_deposit = CommonHelper::user_security_deposit($userid);

        $my_packages = MyPackage::select('my_packages.*','credit_transactions.amount')
            ->join('credit_transactions','my_packages.credit_transaction_id','=','credit_transactions.id')
            ->where('my_packages.user_profile_id',$userid)
            ->where('my_packages.transaction_status',2)
            ->whereNull('credit_transactions.deleted_at')
            ->get();

        $histories = array();

        foreach ($my_packages as $my_package){
            $temp = array();
            $temp['user_profile_id'] = $my_package->user_profile_id;
            $temp['amount'] = $my_package->amount;
            $temp['created_at'] = $my_package->created_at->format('d.m.Y');
            $temp['created_at_utc'] = CommonHelper::UTCDateTime($my_package->created_at)->format('Y-m-d H:i:s');

            array_push($histories,$temp);
        }

        $credit_rm=Frame::find(1)->get(['credit','rm']);

        $response['balance_credit'] = $user_credit_balance;
        $response['security_deposit'] = $user_security_deposit;
        $response['credit_package'] = $credit_package;
        $response['histories'] = $histories;
        $response['credit_rm'] = $credit_rm;

        return $response;
    }

    public function mySubscriptions($request){

        $my_subscriptions = MySubscription::with('subscription_package')
                                            ->where('user_profile_id',$request->user_profile_id)
                                            ->where('status',2)
                                            ->get();

        $response = array();
        $mySubscription = array();

        foreach ($my_subscriptions as $my_subscription){

            $temp = array();
            $subscription_package = array();

            $temp['subscription_id'] = $my_subscription->subscription_id;
            $temp['user_profile_id'] = $my_subscription->user_profile_id;
            $temp['start_date'] = $my_subscription->displayDate($my_subscription->start_date);
            $temp['end_date'] = $my_subscription->displayDate($my_subscription->end_date);
            $temp['utc_start_date'] = CommonHelper::UTCDateTime($my_subscription->start_date)->format('Y-m-d \a\\t h.ia');
            $temp['utc_end_date'] = CommonHelper::UTCDateTime($my_subscription->end_date)->format('Y-m-d \a\\t h.ia');
            $temp['renew'] = $my_subscription->is_running;
            $add_account = $my_subscription->is_running;
            //when want to show add_account only user can add new sub-account
//            if($temp['renew'] != 0){
//                $add_account = $this->can_add_sub_admin($request->user_profile_id);
//            }
            $temp['add_account'] = $add_account;
            $temp['is_expired'] = 0;
            if($my_subscription->end_date < strtotime(Carbon::now())){
                $temp['is_expired'] = 1;
            }

            $subscription_package['id'] = $my_subscription->subscription_package->id;
            $subscription_package['package_name'] = $my_subscription->subscription_package->package_name;
            $subscription_package['description'] = $my_subscription->subscription_package->description;
            $subscription_package['credit'] = $my_subscription->subscription_package->credit;
            $subscription_package['security_deposit'] = $my_subscription->subscription_package->security_deposit;
            $subscription_package['sub_user'] = $my_subscription->subscription_package->sub_user;
            $subscription_package['bidding'] = $my_subscription->subscription_package->bidding;
            $subscription_package['package_type'] = $my_subscription->subscription_package->package_type;

            $temp['subscription_package'] = $subscription_package;

            array_push($mySubscription,$temp);
        }

        $sub_user_data = $this->getSubUser($request);
        $response['my_subscription'] = $mySubscription;
        $response['sub_admin'] = $sub_user_data;

        return $response;
    }

    public function getSubUser($request,$is_approved_status = 1){

        $response = array();
        $subUsers = UserProfile::where('parent_id',$request->user_profile_id)
                                ->where('parent_id','!=',0);
        if($is_approved_status){
            $subUsers = $subUsers->where('is_approved_status',$is_approved_status);
        }
        $subUsers = $subUsers->get();

        foreach ($subUsers as $subUser){

            $temp = array();
            $temp['id'] = $subUser->id;
            $temp['parent_id'] = $subUser->parent_id;
            $temp['name'] = $subUser->name;
            $temp['password'] = base64_decode($subUser->password);
            $temp['email'] = $subUser->email;
            $temp['profile_pic'] = $subUser->profile_pic;
            $temp['is_approved_status'] = $subUser->is_approved_status;
            $temp['phone_no'] = $subUser->phone_no;
            $temp['rating'] = 3;//coding is remaining
            $temp['review_count'] = 247;//coding is remaining

            array_push($response,$temp);
        }
        return $response;
    }

    public function can_add_sub_admin($user_id){

        $can_add_sub_admin = 0;

        $get_sub_admin = UserProfile::where('parent_id',$user_id)
//                                        ->where('is_approved_status',1)
                                        ->count();

        $get_sub_admin_limit = MySubscription::select(DB::raw('Max(subscriptions.sub_user) as sub_admin_limit'))
                                                ->join('subscriptions','my_subscriptions.subscription_id','=','subscriptions.id')
                                                ->where('my_subscriptions.status',2)
                                                ->where('subscriptions.status',1)
                                                ->where('my_subscriptions.is_running',1)
                                                ->whereNull('subscriptions.deleted_at')
                                                ->pluck('sub_admin_limit');

        if($get_sub_admin < $get_sub_admin_limit[0]){
            $can_add_sub_admin = 1;
        }

        return $can_add_sub_admin;
    }
}
