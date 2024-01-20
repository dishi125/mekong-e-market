<?php

namespace App\Http\Controllers;

use App\Enums\Type;
use App\Helpers\CommonHelper;
use App\Http\Requests\CreateUserProfilesRequest;
use App\Http\Requests\UpdateUserProfilesRequest;
use App\Models\CreditManagement;
use App\Models\Frame;
use App\Models\MySubscription;
use App\Models\Post;
use App\Models\Rating;
use App\Models\UserProfile;
use App\Repositories\UserProfilesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Carbon;
use Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestMail;

class UserProfilesController extends AppBaseController
{
    /** @var  UserProfilesRepository */
    private $userProfilesRepository;
    public $view = "user_profiles";

    public function __construct(UserProfilesRepository $userProfilesRepo)
    {
        $this->userProfilesRepository = $userProfilesRepo;
    }

    /**
     * Display a listing of the UserProfiles.
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
        $type = array_flip(Type::toArray());
        return view('user_profiles.index')
                ->with('view',$this->view)->with('type',$type)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }
    public function preferredreqUserProfiles(){
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
//        $subscriptionsUsers=MySubscription::orderBy('id','desc')->paginate(10);
        $view = 'user_preferredreq';
        $type=array_flip(Type::toArray());
        return view('user_profiles.preferredreq')->with('view',$view)->with('type',$type)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }
    public function get_user_preferredreq(Request $request){
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $userProfiles = UserProfile::where('parent_id','=','0')->where('preferred_status',1)->where('is_preferred_approved',null);

        if ($request->start_date) {
            $startDate = CommonHelper::LocalToUtcDate($request->start_date . " 00:00:00");
            $userProfiles = $userProfiles->where('created_at', '>=', $startDate);
        }

        if ($request->end_date) {
            $endDate = CommonHelper::LocalToUtcDate($request->end_date . " 23:59:59");
            $userProfiles = $userProfiles->where('created_at', "<=", $endDate);
        }

        if (isset($request->user_type) && $request->user_type != '') {
            $userProfiles = $userProfiles->where('user_type', $request->user_type);
        }

        if ($request->search) {
            $userProfiles = $userProfiles->where(function ($mainQuery) use ($request) {
                $mainQuery->where('name', 'Like', '%' . $request->search . '%')
                    ->orwhere('phone_no', 'Like', '%' . $request->search . '%')
                    ->orwhere('email', 'Like', '%' . $request->search . '%')
                    ->orwhereHas('state', function ($query) use ($request) {
                        $query->where('name', 'Like', '%' . $request->search . '%');
                    });
            });
        }
        $userProfiles = $userProfiles->paginate($request->per_page);
        $type = array_flip(Type::toArray());
        return view('user_profiles.sub_preferredreq_listtable',compact('userProfiles','type','prefered_ids','preferd_req','preferd_cnt'))->render();
    }
    public function set_preferred_approved_status(Request $request){
        $userid=$request->userid;
        $status=$request->status;
        if($status=="accept") {
            $update_preferred_approved_status = UserProfile::where('id', $userid)->update(['is_preferred_approved' => 1]);
            $emailid=UserProfile::where('id', $userid)->pluck('email');
            $details = [
                'title' => 'Mail  from vasundhara vision',
                'desc' => 'You are approved by admin for prefered.'
            ];
            try {
                Mail::to($emailid[0])->send(new RequestMail($details));
                return response()->json(['success' => true]);
            }
            catch (\Exception $ex) {
                return response()->json(['success' => false]);
            }
        }
        elseif ($status=="reject"){
            $update_preferred_approved_status = UserProfile::where('id', $userid)->update(['is_preferred_approved' => 0]);
            $emailid=UserProfile::where('id', $userid)->pluck('email');
            $details = [
                'title' => 'Mail  from vasundhara vision',
                'desc' => 'You are rejected by admin for prefered.'
            ];
            try {
                Mail::to($emailid[0])->send(new RequestMail($details));
                return response()->json(['success' => true]);
            }
            catch (\Exception $ex) {
                return response()->json(['success' => false]);
            }
        }
    }
    /**
     * Show the form for creating a new UserProfiles.
     *
     * @return Response
    /**
     * Store a newly created UserProfiles in storage.
     *
     * @param CreateUserProfilesRequest $request
     *
     * @return Response
     */

    /**
     * Display the specified UserProfiles.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id,Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $userProfile = UserProfile::find($id);
        $ratingdata=CommonHelper::ratings_reviews($userProfile->id);
       $review_count=$ratingdata['review_count'];
       $avg_rate=$ratingdata['avg_rate'];
        if(isset($userProfile->document)) {
            $infoPath = pathinfo(public_path($userProfile->document));
            $ext = $infoPath['extension'];
            $pic_ext = array("png", "jpg", "jpeg", "jpe", "bmp");
            $doc_ext = array("doc", "docm", "docx", "html", "htm", "odt", "pdf", "xls", "xlsx", "ods", "ppt", "pptx", "txt", "rtf", "wps", "xml", "xps", "csv", "xlsm", "wpd", "tex");
        }
        if (empty($userProfile)) {
            Flash::error('User Profiles not found');

            return redirect(route('userProfiles.index'));
        }
        return view('user_profiles.show',compact('userProfile','review_count', 'ext', 'pic_ext', 'doc_ext', 'avg_rate','preferd_cnt','preferd_req','prefered_ids'));
    }

    public function status_change($id)
    {
        $userProfile = UserProfile::find($id);
        if (empty($userProfile)) {
            return ["status"=>0,"data"=>"User Not Found"];
        }
        $userProfile->is_approved_status=$userProfile->is_approved_status==1? 0:1;
        $userProfile->save();
        return ["status"=>1];
    }
    /**
     * Show the form for editing the specified UserProfiles.
     *
     * @param int $id
     *
     * @return Response
     */

    /**
     * Update the specified UserProfiles in storage.
     *
     * @param int $id
     * @param UpdateUserProfilesRequest $request
     *
     * @return Response
     */
    /**
     * Remove the specified UserProfiles from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $userProfiles = $this->userProfilesRepository->find($id);

        if (empty($userProfiles)) {
            Flash::error('User Profiles not found');

            return redirect(route('userProfiles.index'));
        }

        $this->userProfilesRepository->delete($id);

        Flash::success('User Profiles deleted successfully.');

        return redirect(route('userProfiles.index'));
    }
    public function get_user(Request $request)
    {
        if(!isset($request->id))
        {
            return ["status"=>0,"data"=>"Please select valid type."];
        }
        else
        {
            $user= UserProfile::where('user_type','=',$request->id)->get();
            $data = array();
            foreach ($user as $us)
            {
                $data[$us->id]=$us->name;
            }
            return ["status"=>1,"data"=>$data];

        }
    }

    public function get_user_profiles(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $userProfiles = UserProfile::where('parent_id','=','0');

        if ($request->start_date) {
            $startDate = CommonHelper::LocalToUtcDate($request->start_date . " 00:00:00");
            $userProfiles = $userProfiles->where('created_at', '>=', $startDate);
        }

        if ($request->end_date) {
            $endDate = CommonHelper::LocalToUtcDate($request->end_date . " 23:59:59");
            $userProfiles = $userProfiles->where('created_at', "<=", $endDate);
        }

        if (isset($request->user_type) && $request->user_type != '') {
            $userProfiles = $userProfiles->where('user_type', $request->user_type);
        }

        if ($request->search) {
            $userProfiles = $userProfiles->where(function ($mainQuery) use ($request) {
                $mainQuery->where('name', 'Like', '%' . $request->search . '%')
                    ->orwhere('phone_no', 'Like', '%' . $request->search . '%')
                    ->orwhere('email', 'Like', '%' . $request->search . '%')
                    ->orwhereHas('state', function ($query) use ($request) {
                        $query->where('name', 'Like', '%' . $request->search . '%');
                    });
            });
        }
        if($request->prefered){
            if($request->prefered==1) {
                $userProfiles = $userProfiles->where('is_preferred_approved', 1);
            }
            else{
                $userProfiles = $userProfiles->where('is_preferred_approved', 0)->orwhere('is_preferred_approved',null);
            }
        }
        $userProfiles = $userProfiles->paginate($request->per_page);

        $type = array_flip(Type::toArray());

        return view('user_profiles.sub_table', compact('userProfiles', 'type','preferd_cnt','prefered_ids','preferd_req'))->render();
    }

    public function live_deals($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $view = 'profile/live_deals';
        $userProfile = UserProfile::find($id);
        $ratingdata=CommonHelper::ratings_reviews($userProfile->id);
        $review_count=$ratingdata['review_count'];
        $avg_rate=$ratingdata['avg_rate'];

        return view('user_profiles.show',compact('userProfile','review_count', 'avg_rate', 'view','preferd_cnt','preferd_req','prefered_ids'))->with('seller_id', $id);
    }

    public function upcomig_deals($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $view = 'profile/upcomig_deals';
        $userProfile = UserProfile::find($id);
        $ratingdata=CommonHelper::ratings_reviews($userProfile->id);
        $review_count=$ratingdata['review_count'];
        $avg_rate=$ratingdata['avg_rate'];

        return view('user_profiles.show',compact('userProfile','review_count', 'avg_rate', 'view','preferd_cnt','prefered_ids','preferd_req'))->with('seller_id', $id);
    }

    public function paused_deals($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $view = 'profile/paused_deals';
        $userProfile = UserProfile::find($id);
        $ratingdata=CommonHelper::ratings_reviews($userProfile->id);
        $review_count=$ratingdata['review_count'];
        $avg_rate=$ratingdata['avg_rate'];

        return view('user_profiles.show',compact('userProfile','review_count', 'avg_rate', 'view','preferd_cnt','preferd_req','prefered_ids'))->with('seller_id', $id);
    }

    public function ended_deals($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $view = 'profile/ended_deals';
        $userProfile = UserProfile::find($id);
        $ratingdata=CommonHelper::ratings_reviews($userProfile->id);
        $review_count=$ratingdata['review_count'];
        $avg_rate=$ratingdata['avg_rate'];

        return view('user_profiles.show',compact('userProfile','review_count', 'avg_rate', 'view','preferd_cnt','prefered_ids','preferd_req'))->with('seller_id', $id);
    }

    public function purchase($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $view = 'profile/purchase_deals';
        $userProfile = UserProfile::find($id);
        $ratingdata=CommonHelper::ratings_reviews($userProfile->id);
        $review_count=$ratingdata['review_count'];
        $avg_rate=$ratingdata['avg_rate'];

        return view('user_profiles.show',compact('userProfile','review_count', 'avg_rate', 'view','preferd_cnt','preferd_req','prefered_ids'))->with('seller_id', $id);
    }

    public function get_purchase(Request $request){

        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $purchase_histories = CreditManagement::select('credit_managements.*')
            ->with('post.product.product_image','getRatings')
            ->where('buyer_id',$request->seller_id);
        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $purchase_histories = $purchase_histories->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $purchase_histories = $purchase_histories->where('created_at',"<=",$endDate);
        }
        if($request->search){
            $purchase_histories = $purchase_histories->where(function ($mainQuery) use($request){
                $mainQuery->whereHas('buyer',function ($query) use($request){
                    $query->where('name','Like','%'.$request->search.'%');
                })
                    ->orwhereHas('post.product.user',function ($query) use($request){
                        $query->where('name','Like','%'.$request->search.'%');
                    })
                    ->orwhereHas('post.product',function ($query) use($request){
                        $query->where('product_name','Like','%'.$request->search.'%');
                    });
            });
        }

        $purchase_histories = $purchase_histories->paginate($request->per_page);

        return view('user_profiles.purchase_sub_table',compact('purchase_histories','preferd_cnt','prefered_ids','preferd_req'));
    }

    public function get_paused(Request $request){

        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        //get posts
        $posts = Post::select('posts.*',DB::raw('TIME_TO_SEC(TIMEDIFF(posts.date_time,posts.pause_time)) sec_diff'))
            ->with('product.product_image', 'product.user.getSellerRate','creditmanagement.buyer')
            ->where('is_pause',1)
            ->whereHas('product',function ($query) use($request){
                $query->where('user_profile_id',$request->seller_id);
            });

        $time = '00:00';
        if(isset($request->time)){
            $request->start_date = $request->start_date ? $request->start_date : Carbon::now(env('TIME_ZONE'))->format('Y-m-d');
            $time = Carbon::parse($request->time)->format('H:i');
        }

        if($request->start_date) {
            $startDate = CommonHelper::LocalToUtcDate($request->start_date .' '.$time);
            $posts = $posts->where('posts.date_time', '>=',$startDate);
        }

        if($request->end_date) {
            $endDate = CommonHelper::LocalToUtcDate($request->end_date . " 23:59:59");
            $posts = $posts->where('posts.date_time', '<',$endDate);
        }

        if($request->search){
            $posts = $posts->where(function ($mainQuery) use($request){
                    $mainQuery->whereHas('product',function ($query) use($request){
                        $query->where('product_id','Like','%'.$request->search.'%')
                            ->orWhere('product_name','Like','%'.$request->search.'%');
                    })
                    ->orWhereHas('creditmanagement.buyer',function ($query) use($request){
                        $query->where('name','Like','%'.$request->search.'%');
                    })
                    ->orwhereHas('product.user',function ($query) use($request){
                        $query->where('name','Like','%'.$request->search.'%');
                    })
                    ->orwhereHas('product.maincategory',function ($query) use($request){
                        $query->where('name','Like','%'.$request->search.'%');
                    });
            });
        }

        if($request->is_fast_buy){
            $posts = $posts->where(function ($mainQuery) use($request){
                $mainQuery->whereHas('product',function ($query) use($request){
                    $query->where('fast_buy',$request->is_fast_buy);
                });
            });
        }

        $posts = $posts->paginate($request->per_page);

        return view('user_profiles.paused_sub_table',compact('posts','preferd_cnt','preferd_req','prefered_ids'))->render();

    }

    public function asbuyer($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $userProfile = UserProfile::find($id);
        $ratingdata=CommonHelper::ratings_reviews($userProfile->id);
        $review_count=$ratingdata['review_count'];
        $avg_rate=$ratingdata['avg_rate'];
        $rating=Rating::where('seller_id',$userProfile->id)->get();
        return view('user_profiles.show')->with('userProfile',$userProfile)->with(compact('review_count'))->with(compact('avg_rate'))->with(compact('rating'))->with(compact('preferd_cnt','prefered_ids','preferd_req'));
    }

    public function asseller($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $userProfile = UserProfile::find($id);
        $ratingdata=CommonHelper::ratings_reviews($userProfile->id);
        $review_count=$ratingdata['review_count'];
        $avg_rate=$ratingdata['avg_rate'];
        $rating=Rating::where('buyer_id',$userProfile->id)->get();
        return view('user_profiles.show')->with('userProfile',$userProfile)->with(compact('review_count'))->with(compact('avg_rate'))->with(compact('rating'))->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }
}
