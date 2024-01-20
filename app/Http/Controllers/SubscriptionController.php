<?php

namespace App\Http\Controllers;

use App\Enums\Type;
use App\Helpers\CommonHelper;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\MySubscription;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;

class SubscriptionController extends AppBaseController
{
    /** @var  SubscriptionRepository */
    private $subscriptionRepository;
    public $view='subscriptions';
    public function __construct(SubscriptionRepository $subscriptionRepo)
    {
        $this->subscriptionRepository = $subscriptionRepo;
    }

    /**
     * Display a listing of the Subscription.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $subscriptions = $this->subscriptionRepository->paginate(10);

        return view('subscriptions.index')
            ->with('subscriptions', $subscriptions)->with('view',$this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Show the form for creating a new Subscription.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
         $subscriptions = $this->subscriptionRepository->paginate(10);
        return view('subscriptions.index') ->with('subscriptions', $subscriptions)->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Store a newly created Subscription in storage.
     *
     * @param CreateSubscriptionRequest $request
     *
     * @return Response
     */
    public function store(CreateSubscriptionRequest $request)
    {
        $input = $request->all();

        $input['package_type'] = 0;
        if($input['security_deposit'] == 0 && $input['bidding'] == 0){
            $input['package_type'] = 1;
        }
        $subscription = $this->subscriptionRepository->create($input);

        Flash::success('Subscription saved successfully.');

        return redirect(route('subscriptions.index'));
    }

    /**
     * Display the specified Subscription.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $subscription = $this->subscriptionRepository->find($id);

        if (empty($subscription)) {
            Flash::error('Subscription not found');

            return redirect(route('subscriptions.index'));
        }
         $subscriptions = $this->subscriptionRepository->paginate(10);
        return view('subscriptions.fields')->with('subscription', $subscription)->with('subscriptions', $subscriptions)->with('view',$this->view);
    }

    public function userSubscription()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $subscriptionsUsers=MySubscription::orderBy('id','desc')->paginate(10);
        $view = 'user_subscriptions';
//        return  [$subscriptionsUsers[0]->user,];
        $type=array_flip(Type::toArray());
        return view('subscriptions.show')->with("subscriptionsUsers",$subscriptionsUsers)->with('type',$type)->with('view',$view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }
    /**
     * Show the form for editing the specified Subscription.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $subscription = $this->subscriptionRepository->find($id);

        if (empty($subscription)) {
            Flash::error('Subscription not found');

            return redirect(route('subscriptions.index'));
        }
         $subscriptions = $this->subscriptionRepository->paginate(10);
        return view('subscriptions.index')->with('subscription', $subscription)->with("edit",0) ->with('subscriptions', $subscriptions)->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Update the specified Subscription in storage.
     *
     * @param int $id
     * @param UpdateSubscriptionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubscriptionRequest $request)
    {
        $subscription = $this->subscriptionRepository->find($id);

        if (empty($subscription)) {
            Flash::error('Subscription not found');

            return redirect(route('subscriptions.index'));
        }
        $input = $request->all();
        $input['package_type'] = 0;
        if($input['security_deposit'] == 0 && $input['bidding'] == 0){
            $input['package_type'] = 1;
        }

        $subscription = $this->subscriptionRepository->update($input, $id);

        Flash::success('Subscription updated successfully.');

        return redirect(route('subscriptions.index'));
    }

    /**
     * Remove the specified Subscription from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $subscription = $this->subscriptionRepository->find($id);

        if (empty($subscription)) {
            Flash::error('Subscription not found');

            return redirect(route('subscriptions.index'));
        }

        $this->subscriptionRepository->delete($id);

        Flash::success('Subscription deleted successfully.');

        return redirect(route('subscriptions.index'));
    }
    public function subscription_package_status_change($id)
    {
         $Subscription = Subscription::find($id);


            if (empty($Subscription)) {
                return ["status"=>0,"data"=>"User Not Found"];
            }
            $Subscription->status=$Subscription->status==1? 0:1;
             $Subscription->save();
            return ["status"=>1];
             return $Subscription;

    }

    public function get_subscriptions(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $subscriptions = Subscription::query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $subscriptions = $subscriptions->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $subscriptions = $subscriptions->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $subscriptions = $subscriptions->where(function ($mainQuery) use($request){
                $mainQuery->where('package_name','Like','%'.$request->search.'%')
                          ->orwhere('price','Like','%'.$request->search.'%');
            });
        }
        $subscriptions = $subscriptions->paginate($request->per_page);

        return view('subscriptions.sub_table',compact('subscriptions','preferd_cnt','preferd_req','prefered_ids'))->render();
    }

    public function get_user_subscriptions(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $subscriptionsUsers = MySubscription::orderBy('id','desc');

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $subscriptionsUsers = $subscriptionsUsers->where('start_date','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $subscriptionsUsers = $subscriptionsUsers->where('end_date',"<=",$endDate);
        }

        if(isset($request->user_type) && $request->user_type != ''){
            $subscriptionsUsers = $subscriptionsUsers->whereHas('user', function ($query) use ($request){
                                    $query->where('user_type',$request->user_type);
                                  });
        }

        if($request->search){
            $subscriptionsUsers = $subscriptionsUsers->where(function ($mainQuery) use($request){
                                $mainQuery->where('transaction_id','Like','%'.$request->search.'%')
                                           ->orwhereHas('user',function ($query) use($request){
                                               $query->where('name','Like','%'.$request->search.'%');
                                           })
                                           ->orwhereHas('subscription_package',function ($query) use($request){
                                               $query->where('package_name','Like','%'.$request->search.'%');
                                           });
                                });
        }
        $subscriptionsUsers = $subscriptionsUsers->paginate($request->per_page);

        $type = array_flip(Type::toArray());

        return view('subscriptions.sub_listtable',compact('subscriptionsUsers','type','prefered_ids','preferd_req','preferd_cnt'))->render();
    }
}
