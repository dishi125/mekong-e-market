<?php

namespace App\Http\Controllers\API;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\CreditManagement;
use App\Models\Notifications_api;
use App\Models\Post;
use App\Models\Product;
use App\Models\Rating;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;

class RatingAPIController extends AppBaseController
{
    public function give_rating(Request $request){

        $messages = [
            'credit_management_id.required' => 'Please enter credit management id.',
            'rate' => 'Please enter rate.',
            'review' => 'Please enter your review.'
        ];

        $validator = Validator::make($request->all(), [
            'credit_management_id' => 'required',
            'rate' => 'required',
            'review' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $credit_management = CreditManagement::find($request->credit_management_id);
        if(!$credit_management){
            return $this->responseError("No Deal Found.");
        }

        $user_pr_id = $credit_management->posts->product->user_profile_id;
        $rating = new Rating;
        $rating->rate = $request->rate;
        $rating->review = $request->review;
        $rating->buyer_id = $credit_management->buyer_id;
        $rating->seller_id = $user_pr_id;
        $rating->credit_management_id = $request->credit_management_id;
        $rating->save();

        $users = $user_pr_id;
        $users=(array)$users;
        try {
            Log::error($users);
            $notifications=new Notifications_api();
            $notifications->from_user_id=$credit_management->buyer_id;
            $notifications->to_user_id=$user_pr_id;
            $notifications->rating_id=$rating->where('rate',$request->rate)->where('review',$request->review)->where('buyer_id',$credit_management->buyer_id)->where('seller_id',$user_pr_id)->where('credit_management_id',$request->credit_management_id)->pluck('id')->first();
            $notifications->post_id=$credit_management->post_id;
            $notifications->desc="rating";

            $notification_array = array();
            $notification_array['title'] = $notifications->desc;
            $unm=UserProfile::where('id',$notifications->from_user_id)->pluck('name')->first();
            $pro=Post::where('id',$notifications->post_id)->pluck('product_id')->first();
            $pronm=Product::where('id',$pro)->pluck('product_name')->first();
            $notification_array['message'] = $unm." give rate ".$request->rate." and review '".$request->review."' on ".$pronm;
            $notifications->save();
            CommonHelper::sendPushNotification($users,$notification_array);
//            CommonHelper::my_sendpushnotifications($users,$notification_array);
        } catch (\Exception $e) {
//                dd($e->getTraceAsString());
            Log::error($e->getTraceAsString());
        }
        return $this->sendSuccess("thank you for your rating.");
    }

    public function view_review_as_buyer_seller(Request $request){
        $messages = [
            'user_profile_id.required' => 'Please enter your id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }
        $response=array();
        $ratingbuyers = Rating::where('buyer_id',$request->user_profile_id)->orderBy('created_at','desc')->get();
        if(!$ratingbuyers){
            return $this->responseError("No ratings from buyers.");
        }
        $buyer=array();
        foreach ($ratingbuyers as $ratingbuyer){
            $temp=array();
            $temp['id']=$ratingbuyer->id;
            $temp['rate']=$ratingbuyer->rate;
            $temp['review']=$ratingbuyer->review;
            $temp['name']=$ratingbuyer->buyer->name;
            $temp['profile_pic']=$ratingbuyer->buyer->profile_pic ? url($ratingbuyer->buyer->profile_pic) : '';
            $temp['created_at']=\Carbon\Carbon::parse($ratingbuyer->created_at, "UTC")->setTimezone($request->timezone)->format('d.m.Y \a\\t h.ia');
            $temp['created_at_utc'] = CommonHelper::UTCDateTime($ratingbuyer->created_at)->format('Y-m-d \a\\t h.ia');
            array_push($buyer, $temp);
        }
        $ratingsellers = Rating::where('seller_id',$request->user_profile_id)->orderBy('created_at','desc')->get();
        if(!$ratingsellers){
            return $this->responseError("No ratings from sellers.");
        }
        $seller=array();
        foreach ($ratingsellers as $ratingseller){
            $temp=array();
            $temp['id']=$ratingseller->id;
            $temp['rate']=$ratingseller->rate;
            $temp['review']=$ratingseller->review;
            $temp['name']=$ratingseller->user->name;
            $temp['profile_pic']=$ratingseller->user->profile_pic ? url($ratingseller->user->profile_pic) : '';
            $temp['created_at']=\Carbon\Carbon::parse($ratingseller->created_at, "UTC")->setTimezone($request->timezone)->format('d.m.Y \a\\t h.ia');
            $temp['created_at_utc'] = CommonHelper::UTCDateTime($ratingseller->created_at)->format('Y-m-d \a\\t h.ia');
            array_push($seller, $temp);
        }
        $response['as_buyer']=$buyer;
        $response['as_seller']=$seller;
        return $this->responseWithData($response,"Ratings from buyers and sellers retrieved successfully.");
    }

    public function view_review_as_seller(Request $request){
        $messages = [
            'user_profile_id.required' => 'Please enter your id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }
        $ratingdata=Rating::where('buyer_id',$request->user_profile_id)->get();
        if(!$ratingdata){
            return $this->responseError("No ratings from sellers.");
        }
        return $this->responseWithData($ratingdata->toArray(),"Ratings from sellers retrieved successfully.");
    }

    public function get_top_seller(Request $request){

        $ratings = DB::table("ratings")->leftJoin('user_profiles','ratings.seller_id','=','user_profiles.id')
                    ->select('user_profiles.*',DB::raw("ROUND(avg(rate),2) as average_rate,seller_id,count('review') as total_reviews"))
                    ->orderBy("average_rate",'DESC')
                    ->groupBy(DB::raw("seller_id"));

        if(isset($request->rating)){
//            $ratings = $ratings->havingRaw('ROUND(avg(rate),2) BETWEEN ? AND ? ',[(int)$request->rating,(int)$request->rating + 1]);
            $ratings=$ratings->having('average_rate', '=' , $request->rating);
        }

        if($request->search){
            $ratings = $ratings->where('user_profiles.name','LIKE','%'.$request->search.'%');
        }

        $ratings = $ratings->get();

        $seller = array();

        foreach ($ratings as $rating){

            $temp=array();
            $temp['id']=$rating->seller_id;
            $temp['average_rate']=$rating->average_rate;
            $temp['total_reviews']=$rating->total_reviews;

            $temp['name'] = $rating->name;
            $temp['email'] = $rating->email;
            $temp['profile_pic'] = $rating->profile_pic ? url('public/' . $rating->profile_pic) : '';
            $temp['phone_no'] = $rating->phone_no;
            $temp['sub_category_id'] = $rating->sub_category_id ? $rating->sub_category_id : 0;
            $temp['user_type'] = $rating->user_type ? $rating->user_type : 0;
            $temp['main_category_id'] = $rating->main_category_id ? $rating->main_category_id : 0;
            $temp['company_name'] = $rating->company_name ? $rating->company_name : '';
            $temp['company_reg_no'] = $rating->company_reg_no ? $rating->company_reg_no : '';
            $temp['company_tel_no'] = $rating->company_tel_no ? $rating->company_tel_no : '';
            $temp['state_id'] = $rating->state_id ? $rating->state_id : 0;
            $temp['area_id'] = $rating->area_id ? $rating->area_id : 0;
            $temp['address'] = $rating->address ? $rating->address : '';
            $temp['company_email'] = $rating->company_email ? $rating->company_email : '';
            $temp['document'] = $rating->document ? url('public/' . $rating->document) : '';
            $temp['preferred_status'] = $rating->preferred_status;
            $temp['is_approved_status'] = $rating->is_approved_status;
            $temp['parent_id'] = $rating->parent_id;
            $temp['job_description'] = $rating->job_description ? $rating->job_description : '';
            $temp['package_id'] = $rating->package_id ? $rating->package_id : 0;

            array_push($seller, $temp);
        }

        $banners = CommonHelper::banners();
        $response['top_sellers'] = $seller;
        $response['banners'] = $banners;

        return $this->responseWithData($response,"Top sellers retrieved successfully.");
    }
}
