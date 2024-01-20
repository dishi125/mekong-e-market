<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CreditManagement;
use App\Models\CreditPackage;
use App\Models\MyPackage;
use App\Models\SecurityDepositTransaction;
use App\Models\Setting;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Repositories\SubscriptionRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\MySubscription;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CreditTransaction;

class SubscriptionAPIController extends AppBaseController
{
    public function __construct( SubscriptionRepository $subscriptionrepo )
    {
        $this->SubscriptionRepository = $subscriptionrepo;
    }

    public function getSubscriptionPackages(Request $request)
    {
        $messages = [
            'user_profile_id.required' => 'Please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user_id = $request->user_profile_id;
        $user = UserProfile::find($user_id);
        if(!$user){
            return $this->responseError("User Not Found");
        }
//        if($user->is_approved_status == 0){
//            return $this->responseError("Sorry User has not Verified yet");
//        }

        $subscriptions = Subscription::with(['getMySubscription' => function ($query) use($user_id){
                                $query->where('user_profile_id',$user_id)
                                      ->where('is_running',1);
                            }])
                            ->where('status',1)
                            ->get();

        $main_response = array();
        $response = array();
        foreach ($subscriptions as $subscription){

            $temp = array();
            $temp['id'] = $subscription->id;
            $temp['package_name'] = $subscription->package_name;
            $temp['price'] = $subscription->price;
            $temp['description'] = $subscription->description;
            $temp['credit'] = $subscription->credit;
            $temp['security_deposit'] = $subscription->security_deposit;
            $temp['sub_user'] = $subscription->sub_user;
            $temp['bidding'] = $subscription->bidding;
            $temp['status'] = $subscription->status;
            $temp['package_type'] = $subscription->package_type;
            $temp['is_subscribed'] = isset($subscription->getMySubscription->is_running) ? $subscription->getMySubscription->is_running : 0;
            $temp['duration']=$subscription->duration." Months"; //dishita
            array_push($response,$temp);
        }

        $info = Setting::whereIn('name',array('info-buyer','info-buyer-seller'))->pluck('value');

        $main_response['packages'] = $response;
        $main_response['subscription_info'] = $info;

        if(empty($response)){
            $message='There are not available any subscription packages.';
            return $this->responseError($message);
        }

        return $this->responseWithData($main_response,'Subscription Packages retrieved successfully.');

    }

    public function subscribePackage(Request $request){

        $messages = [
            'subscription_id.required' => 'Please enter subscription id',
            'user_profile_id.required' => 'Please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required',
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if(!$user){
            return $this->responseError("User Not Found");
        }
//        if($user->is_approved_status == 0){
//            return $this->responseError("Sorry User has not Verified yet");
//        }

        $subscription = Subscription::find($request->subscription_id);
        if(!$subscription){
            return $this->responseError("Please Add Valid Subscription Pack");
        }

        //renew subscription
        $my_subscription = MySubscription::where('subscription_id',$subscription->id)
                                           ->where('user_profile_id',$user->id)
                                           ->where('is_running',1)
                                           ->where('status',2)
                                           ->first();

        if(!$my_subscription){
            //check plan order (for adding higher order plan you have to first add all lower order plans)
            //plan wise ordering
            $subscription_plans_ids = Subscription::orderBy('price')
                ->where('status',1)
                ->where('package_type',0)
                ->get()
                ->pluck('id')
                ->toArray();
            $my_subscription_plans_ids = MySubscription::where('user_profile_id',$user->id)
                ->where('is_running',1)
                ->where('status',2)
                ->pluck('subscription_id')
                ->toArray();

            $plans_ids = array_diff($subscription_plans_ids, $my_subscription_plans_ids);
            $plan_id = current($plans_ids);

            if($plan_id != $request->subscription_id && $subscription->package_type != 1){
                $require_plan = Subscription::find($plan_id);
                return $this->responseError("To Subscribe This Plan You Have to First Subscribe ".$require_plan->package_name);
            }
            $current_date = Carbon::now();
            $end_date = Carbon::now()->addMonths($subscription->duration)->subDay(); //dishita

        } else {
            //for plan renew
//            $current_date = Carbon::parse($my_subscription->start_date);
            $current_date = Carbon::parse($my_subscription->end_date)->addDay();
            $end_date = Carbon::parse($current_date)->addMonths($subscription->duration)->subDay();
        }

        $transaction_id = "MYSUBSCRIPTION123";

        //add new subscription
        //add data in security deposit transaction
        $security_deposit_transaction = new SecurityDepositTransaction();
        $security_deposit_transaction->type = 0;//for credit
        $security_deposit_transaction->user_profile_id = $user->id;
        $security_deposit_transaction->amount = $subscription->security_deposit;
        $security_deposit_transaction->save();

        /*dishita*/
        if($user->parent_id!=0){
            $main_user=UserProfile::where('id',$user->parent_id)->first();
            //add data in credit transaction
            $credit_transaction = new CreditTransaction();
            $credit_transaction->type = 0;//for credit
            $credit_transaction->user_profile_id = $main_user->id;
            $credit_transaction->amount = $subscription->credit;
            $credit_transaction->save();
        }
        /*dishita*/
        else{
            //add data in credit transaction
            $credit_transaction = new CreditTransaction();
            $credit_transaction->type = 0;//for credit
            $credit_transaction->user_profile_id = $user->id;
            $credit_transaction->amount = $subscription->credit;
            $credit_transaction->save();
        }

        $create_subscription = new MySubscription();
        $create_subscription->subscription_id = $subscription->id;
        $create_subscription->user_profile_id = $user->id;
        $create_subscription->start_date = $current_date;
        $create_subscription->end_date = $end_date;
        $create_subscription->transaction_id = $transaction_id;
        $create_subscription->security_deposit_transaction_id = $security_deposit_transaction->id;
        $create_subscription->credit_transaction_id = $credit_transaction->id;
        $create_subscription->status = 2;//transaction success
        $create_subscription->is_running = 1;//is currently running
        $create_subscription->save();

        if($my_subscription){
            //make old plan disable
            $my_subscription->delete();
        }

        $get_sub_admin_limit = MySubscription::select(DB::raw('Max(subscriptions.sub_user) as sub_admin_limit'))
            ->join('subscriptions','my_subscriptions.subscription_id','=','subscriptions.id')
            ->where('my_subscriptions.status',2)
            ->where('subscriptions.status',1)
            ->where('my_subscriptions.is_running',1)
            ->where('my_subscriptions.user_profile_id',$user->id)
            ->whereNull('subscriptions.deleted_at')
            ->value('sub_admin_limit');

        $get_sub_admin_limit = ($get_sub_admin_limit) ? (int)$get_sub_admin_limit : 0;

        //get sub-user from trash to restore sub-admin by plan limit
        UserProfile::where('parent_id',$user->id)->onlyTrashed()
            ->offset(0)->limit($get_sub_admin_limit)->update(['deleted_at' => null]);

        return $this->sendSuccess("Package subscribed successfully.");
    }

    public function mySubscriptions(Request $request){

        $messages = [
            'user_profile_id.required' => 'Please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user_id = $request->user_profile_id;

        $user = UserProfile::find($user_id);
        if(!$user){
            return $this->responseError("User Not Found");
        }
//        if($user->is_approved_status == 0){
//            return $this->responseError("Sorry User has not Verified yet");
//        }

        $response = $this->SubscriptionRepository->mySubscriptions($request);

        return $this->responseWithData($response,'Subscriptions retrieved successfully.');
    }

    public function myCredit(Request $request){

        $messages = [
            'user_profile_id.required' => 'Please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user_id = $request->user_profile_id;

        $user = UserProfile::find($user_id);
        if(!$user){
            return $this->responseError("User Not Found");
        }
//        if($user->is_approved_status == 0){
//            return $this->responseError("Sorry User has not Verified yet");
//        }


        if($user->parent_id==0){
            $response = $this->SubscriptionRepository->myCredit($user_id);
        }
        /*dishita*/
        else{
            $main_user=UserProfile::where('id',$user->parent_id)->first();
            $response = $this->SubscriptionRepository->myCredit($main_user->id);
        }
        /*dishita*/

        return $this->responseWithData($response,'Credits retrieved successfully.');
    }

    public function purchaseCredit(Request $request){

        $messages = [
            'credit_pack_id.required' => 'Please Add Valid Pack',
            'user_profile_id.required' => 'Please Enter Valid User.',
        ];

        $validator = Validator::make($request->all(), [
            'credit_pack_id' => 'required',
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user_id = $request->user_profile_id;
        $credit_pack_id = $request->credit_pack_id;
//        $transaction_id = $request->transaction_id;
        $transaction_id = "TRANSACTION123";

        $credit_pack = CreditPackage::find($credit_pack_id);
        if(!$credit_pack){
            return $this->responseError("No Credit Package Found !!");
        }

        $user = UserProfile::find($user_id);
        if(!$user){
            return $this->responseError("User Not Found");
        }
//        if($user->is_approved_status == 0){
//            return $this->responseError("Sorry User has not Verified yet");
//        }

        $subscribe_plan_exist = MySubscription::where('user_profile_id',$user->id)
                                                ->where('is_running',1)
                                                ->where('status',2)
                                                ->exists();

        if(!$subscribe_plan_exist) {
            return $this->responseError("To add Top-up you need to Subscribe first.");
        }

        /*dishita*/
        if($user->parent_id!=0){
            $main_user=UserProfile::where('id',$user->parent_id)->first();
            //add data in credit transaction
            $credit_transaction = new CreditTransaction();
            $credit_transaction->type = 0;//for credit
            $credit_transaction->user_profile_id = $main_user->id;
            $credit_transaction->amount = $credit_pack->amount;
            $credit_transaction->save();
        }
        /*dishita*/
        else {
            $credit_transaction = new CreditTransaction();
            $credit_transaction->type = 0;// fro Credit
            $credit_transaction->user_profile_id = $user_id;
            $credit_transaction->amount = $credit_pack->amount;
            $credit_transaction->save();
        }


        $my_packages = new MyPackage();
        $my_packages->user_profile_id = $user_id;
        $my_packages->credit_package_id = $credit_pack_id;
        $my_packages->transaction_id = $transaction_id;
        $my_packages->credit_transaction_id = $credit_transaction->id;
        $my_packages->transaction_status = 2;//success
        $my_packages->save();

        if($user->parent_id==0){
            $response = $this->SubscriptionRepository->myCredit($user_id);
        }
        /*dishita*/
        else{
            $main_user=UserProfile::where('id',$user->parent_id)->first();
            $response = $this->SubscriptionRepository->myCredit($main_user->id);
        }
        /*dishita*/
//        $response = $this->SubscriptionRepository->myCredit($request);

        return $this->responseWithData($response,'Credit Package Added successfully.');
    }
}
