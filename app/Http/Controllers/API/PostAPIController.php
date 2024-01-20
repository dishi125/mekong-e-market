<?php

namespace App\Http\Controllers\API;

use App\Enums\Type;
use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\CreditManagement;
use App\Models\CreditSetting2;
use App\Models\CreditTransaction;
use App\Models\Favourite;
use App\Models\Frame;
use App\Models\LogisticCompany;
use App\Models\MySubscription;
use App\Models\Notifications_api;
use App\Models\Rating;
use App\Models\Report;
use App\Models\UserProfile;
use App\Models\WeightUnit;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\AssignOp\Concat;
use PhpParser\Node\Expr\Cast\Object_;


class PostAPIController extends AppBaseController
{
    public function addPost(Request $request)
    {
        $messages = [
            'user_profile_id.required'=>'please enter user profile id.',
            'product_name.required'=>'please enter product name.',
            'product_image.required'=>'please enter product images.',
            'main_category_id.required' => 'Please select main category.',
            'sub_category_id.required' => 'Please select sub category.',
            'grade_id.required' => 'Please select grade.',
            'first_price.required' => 'Please enter 1st price.',
            'second_price.required' => 'Please enter 2nd price.',
            'third_price.required' => 'Please enter 3rd price.',
            'fourth_price.required' => 'Please enter 4th price.',
            'ended_price.required' => 'Please enter ended price.',
            'qty.required' => 'Please enter quantity.',
            'weight_unit_id.required' => 'Please select weight unit.',
            'state_id.required' => 'Please select state.',
            'area_id.required' => 'Please select area.',
            'date.required' => 'Please select date.',
            'time.required' => 'Please select time.',
            'pickup_point.required' => 'Please enter pick up point.',
            'description.required' => 'Please enter description.',
            'is_mygap.required' => 'Please choose MyGap.',
            'is_organic.required' => 'Please choose Organic.',
        ];

        $validator = Validator::make($request->all(), [
            'product_name'=>'required',
            'user_profile_id'=>'required',
            'product_image' => 'required',
            'main_category_id' => 'required',
            'sub_category_id'=>'required',
            'grade_id'=> 'required',
            'first_price'=> 'required',
            'second_price'=> 'required',
            'third_price'=> 'required',
            'fourth_price'=> 'required',
            'ended_price'=> 'required',
            'qty'=> 'required',
            'weight_unit_id'=> 'required',
            'state_id'=> 'required',
            'area_id'=> 'required',
            'date'=> 'required',
            'time'=> 'required',
            'pickup_point'=> 'required',
            'description'=> 'required',
            'is_mygap'=> 'required',
            'is_organic'=> 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        if(!isset($request->species_id) && !isset($request->other_species)){
            return $this->responseError("Species Id/Other Species is required");
        }

        if(!isset($request->imported) && !isset($request->other_imported_info)){
            return $this->responseError("Is Imported/Other Imported is required");
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }
        if($user->is_approved_status == 0){
            return $this->responseError("Sorry User has not Verified yet");
        }

        //if full company detail is there then only seller can add post
        $check_company_detail = CommonHelper::check_company_detail($user);
        if(!$check_company_detail){
            return $this->responseError("Please Enter Your Company Detail First");
        }

        $images = $request->file('product_image');
        if (empty($images)) {
            return $this->responseError('Kindly Enter Images..!');
        }

        //first check all image extension
        foreach ($images as $image) {
            $ext = $image->getClientOriginalExtension();
            $ext = strtolower($ext);
            // $all_ext = array("png","jpg", "jpeg", "jpe", "jif", "jfif", "jfi","tiff","tif","raw","arw","svg","svgz","bmp", "dib","mpg","mp2","mpeg","mpe");
            $all_ext = array("png", "jpg", "jpeg");
            if (!in_array($ext, $all_ext)) {
                return $this->responseError('Invalid type of image.');
            }
        }

        /*dishita*/
        if($user->parent_id==0){
            $credit_amount = CommonHelper::user_credit_balance($request->user_profile_id);
        }
        else{
            $main_user=UserProfile::where('id',$user->parent_id)->first();
            $credit_amount = CommonHelper::user_credit_balance($main_user->id);
        }
        /*dishita*/
//        $credit_amount = CommonHelper::user_credit_balance($request->user_profile_id);
        $credit_per_transaction=CreditSetting2::where('main_category_id',$request->main_category_id)->get();
        if (count($credit_per_transaction)==0){
            return $this->responseError('please select other main category.Admin not decided transaction fee for this.');
        }
        $transaction_fee='';
        foreach ($credit_per_transaction as $cpt){
            $exploded_subs=explode(",",$cpt->sub_categories);
            if(in_array($request->sub_category_id,$exploded_subs)) {
                foreach ($exploded_subs as $es) {
                    if ($es == $request->sub_category_id) {
                        $transaction_fee = ($cpt->credit_per_transaction) * ($request->qty);
                    }
                }
            }
        }
        if ($transaction_fee==''){
            return $this->responseError('please select other sub category.Admin not decided transaction fee for this.');
        }
        if((int)$credit_amount < (int)$transaction_fee){
            return $this->responseError('You have not enough credit to post.');
        }

        $frame = Frame::first();

        $date = trim($request->date);
//        $reqtime = date('H:i',strtotime(trim($request->time)));
        $time=trim($request->time);
//        $utc_start_date_time = CommonHelper::LocalToUtcDateTime($date.' '.$time);
        $utc_start_date_time = $date.' '.$time;
        $end_date = date('Y-m-d H:i:s',strtotime('+1 days +'.$frame->frame.' minutes',strtotime($utc_start_date_time)));
        $product= new Product;
        $product->product_id = round(microtime(true) * 1000);
        $product->user_profile_id = $request->user_profile_id;
        $product->product_name = $request->product_name;
        $product->main_category_id = $request->main_category_id;
        $product->sub_category_id = $request->sub_category_id;
        $species_id = $request->species_id;
        if(!isset($species_id)) {
            $species_id = 0;
        }
        $product->species_id = $species_id;
        $other_species = $request->other_species;
        if(!isset($other_species)) {
            $other_species = '';
        }
        $product->other_species = $other_species;
        $imported = $request->imported;
        if(!isset($imported)) {
            $imported = 0;
        }
        $product->imported = $imported;
        $other_imported_info = $request->other_imported_info;
        if(!isset($other_imported_info)) {
            $other_imported_info = '';
        }
        $product->other_imported_info = $other_imported_info;
        $product->grade_id = $request->grade_id;
        $product->state_id = $request->state_id;
        $product->area_id = $request->area_id;
        if(!$request->url) {
            $request->url = '';
        }
        $product->url = $request->url;
        $product->pickup_point = $request->pickup_point;
        $product->description = $request->description;
        $fast_buy_price = $request->fast_buy_price;
        $fast_buy = 1;
        if(!isset($fast_buy_price)) {
            $fast_buy_price = 0;
            $fast_buy = 0;
        }
        elseif($fast_buy_price==0){
            $fast_buy_price = 0;
            $fast_buy = 0;
        }
        $product->fast_buy_price = $fast_buy_price;
        $product->fast_buy = $fast_buy;
        $product->is_mygap = $request->is_mygap;
        $product->end_time = $end_date;
        $product->is_organic = $request->is_organic;
        $product->save();

        //image existence already checked upside
        foreach ($images as $image){

            $image_path = 'product_images/';
            $image_name = 'Product_' . time() . rand(11111,99999) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($image_path), $image_name);
            $imagePath = $image_path . $image_name;

            $product_image = new ProductImage;
            $product_image->image = $imagePath;
            $product_image->product_id = $product->id;
            $product_image->save();
        }

        $post = new Post;
        $post->product_id = $product->id;
        $post->starting_price = $request->first_price;
        $post->second_price = $request->second_price;
        $post->third_price = $request->third_price;
        $post->fourth_price = $request->fourth_price;
        $post->ended_price = $request->ended_price;
        $post->qty = $request->qty;
        $post->weight_unit_id = $request->weight_unit_id;
        $post->date_time = $utc_start_date_time;
        $post->frame = (int)$frame->frame * 60;

//        $credit_per_transaction=WeightUnit::where('id',$post->weight_unit_id)->get();
        $post->credit_fee=$transaction_fee;
        $post->save();


        /*dishita*/
        if($user->parent_id!=0){
            $main_user=UserProfile::where('id',$user->parent_id)->first();
            //add data in credit transaction
            $credit_transaction = new CreditTransaction();
            $credit_transaction->type = 1;//for debit
            $credit_transaction->user_profile_id = $main_user->id;
            $credit_transaction->amount = $post->credit_fee;
            $credit_transaction->save();
        }
        /*dishita*/
        else {
            //remove credit from user account at add post time
            $credit_transaction = new CreditTransaction();
            $credit_transaction->type = 1;//debit credit-points
            $credit_transaction->user_profile_id = $request->user_profile_id;
            $credit_transaction->amount = $post->credit_fee;
            $credit_transaction->save();
        }

//        $users=UserProfile::where('state_id',$request->state_id)->where('area_id',$request->area_id)->get();
//        $notification_array['message'] = $request->product_name." will add in your area at ".$request->date;

        return $this->sendSuccess("Successfully Post!");

    }

    public function viewPost(Request $request) {
        $messages = [
            'post_id.required'=>'please enter post id.'
        ];

        $validator = Validator::make($request->all(), [
            'post_id'=>'required'
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $post = Post::find($request->post_id);

        if (!$post) {
            return $this->responseError("Post Not Found");
        }
        if(isset($request->user_profile_id)){
            $user=UserProfile::find($request->user_profile_id);
            if(!$user){
                return $this->responseError("User Not Found");
            }
        }

        $response = array();
        $response['id'] = $post->id;
        $response['display_post_id'] = $post->product->product_id;
        $response['product_name'] = $post->product->product_name;
        $response['post_images'] = $post->product->app_post_images;
        $response['description'] = $post->product->description;
        $response['main_category_id'] = $post->product->main_category_id;
        $response['main_category'] = isset($post->product->maincategory->name) ? $post->product->maincategory->name : '';
        $response['sub_category_id'] = $post->product->sub_category_id;
        $response['sub_category'] = isset($post->product->subcategory->name) ? $post->product->subcategory->name : '';
        $response['species_id'] = $post->product->species_id;
        $response['species'] = isset($post->product->species->name) ? $post->product->species->name : '';
        $response['other_species'] = isset($post->product->other_species) ? $post->product->other_species : '';
        $response['is_imported'] = $post->product->imported;
        $response['other_imported_info'] = isset($post->product->other_imported_info) ? $post->product->other_imported_info : '';
        $response['url'] = $post->product->url;
        $response['state_id'] = $post->product->state_id;
        $response['area_id'] = $post->product->area_id;
        $response['address'] = $post->product->address;
        $response['grade_id'] = $post->product->grade_id;
        $response['grade'] = $post->product->grade;
        $response['pickup_point'] = $post->product->pickup_point;
        $response['starting_price'] = $post->starting_price;
        $response['second_price'] = $post->second_price;
        $response['third_price'] = $post->third_price;
        $response['fourth_price'] = $post->fourth_price;
        $response['ended_price'] = $post->ended_price;
        $response['fast_buy'] = $post->product->fast_buy;
        $response['fast_buy_price'] = $post->product->fast_buy_price;
        $response['qty']=$post->qty;
        $response['weight_unit_id'] = $post->weight_unit_id;
        $response['weight_unit'] = $post->unit;
        $response['weight'] = $post->weight;
        $response['is_mygap'] = $post->product->is_mygap;
        $response['is_organic'] = $post->product->is_organic;
        $response['is_fast_buy'] = $post->product->fast_buy;
        $response['post_start_date'] = $post->display_date_time;
        $response['post_end_date'] = CommonHelper::addSecondsToDate($post->date_time,$post->frame,'d-m-Y');
        $response['post_start_at'] = CommonHelper::UTCToLocalDateTime($post->date_time,$request->timezone)->format('Y-m-d h:i:s A');
        $response['post_end_at'] = CommonHelper::addSecondsToDate($post->date_time,$post->frame,'Y-m-d h:i:s A',$request->timezone);
        $response['post_utc_start_at'] = CommonHelper::UTCDateTime($post->date_time)->format('Y-m-d \a\\t h.ia');
        $response['post_utc_end_at'] = CommonHelper::addSecondsToUTCDate($post->date_time,$post->frame,'Y-m-d \a\\t h.ia');
        if(isset($request->user_profile_id)) {
            if ($user) {
                $fav_cnt = Favourite::where('user_profile_id', $user->id)->where('post_id', $post->id)->count();
                if ($fav_cnt > 0) {
                    $response['is_favourite'] = 1;
                } else {
                    $response['is_favourite'] = 0;
                }
                $response['is_preferred']=$user->preferred_status;
            }
            $seller=UserProfile::find($post->product->user_profile_id);
            $seller_detail=array();
            $seller_detail['id']=$seller->id;
            $seller_detail['name']=isset($seller->name)?$seller->name:'';
            $seller_detail['profile_pic']=isset($seller->profile_pic)?url($seller->profile_pic):'';
            $seller_detail['user_type']=(isset($seller->user_type)&&$seller->user_type>0) ? Type::getKey((int)$seller->user_type) : '';
            $seller_detail['main_category_id']=isset($seller->main_category_id)?$seller->main_category_id:'';
            $seller_detail['sub_category_id']=isset($seller->sub_category_id)?$seller->sub_category_id:'';
            $seller_detail['main_category_name']=isset($seller->main_category_id)?$seller->maincategory->name:'';
            $seller_detail['sub_category_name']=isset($seller->sub_category_id)?$seller->subcategory->name:'';
            $seller_detail['state']=isset($seller->state_id)?$seller->state->name:'';
            $seller_detail['area']=isset($seller->area_id)?$seller->area->name:'';
            $seller_detail['company_address']=isset($seller->address)?$seller->address:'';
            $seller_detail['seller_rating']=$seller->seller_rating;
            $seller_detail['seller_review']=$seller->seller_review;

            $as_buyer_review = Rating::where('buyer_id',$seller->id)->whereNotNull('review')->where('review','!=','')->count('review');
            $seller_detail['as_buyer_review']=$as_buyer_review;

            $response['seller_detail']=$seller_detail;

            $buyers_detail=$post->buyer_detail;
            if(!empty($buyers_detail)) {
                $buyers_detail['purchase_date'] = \Carbon\Carbon::parse($buyers_detail['purchase_date'], "UTC")->setTimezone($request->timezone)->format('d.m.Y \a\\t h.ia');
                $response['buyers_detail']=$buyers_detail;
            }
            else{
                $response['buyers_detail']=null;
            }
        }
        return $this->responseWithData($response,"Post Updated Successfully!");
    }

    public function updatePost(Request $request)
    {
        $messages = [
            'post_id.required'=>'please enter post id.',
            'product_name.required'=>'please enter product name.',
            'main_category_id.required' => 'Please select main category.',
            'sub_category_id.required' => 'Please select sub category.',
            'grade_id.required' => 'Please select grade_id.',
            'first_price.required' => 'Please enter 1st price.',
            'second_price.required' => 'Please enter 2nd price.',
            'third_price.required' => 'Please enter 3rd price.',
            'fourth_price.required' => 'Please enter 4th price.',
            'ended_price.required' => 'Please enter ended price.',
            'qty.required' => 'Please enter quantity.',
            'weight_unit_id.required' => 'Please select weight unit.',
            'state_id.required' => 'Please select state.',
            'area_id.required' => 'Please select area.',
            'date.required' => 'Please select date.',
            'time.required' => 'Please select time.',
            'pickup_point.required' => 'Please enter pick up point.',
            'description.required' => 'Please enter description.',
            'is_mygap.required' => 'Please choose MyGap.',
            'is_organic.required' => 'Please choose Organic.',
        ];

        $validator = Validator::make($request->all(), [
            'post_id'=>'required',
            'product_name'=>'required',
            'main_category_id' => 'required',
            'sub_category_id'=>'required',
            'grade_id'=> 'required',
            'first_price'=> 'required',
            'second_price'=> 'required',
            'third_price'=> 'required',
            'fourth_price'=> 'required',
            'ended_price'=> 'required',
            'qty'=> 'required',
            'weight_unit_id'=> 'required',
            'state_id'=> 'required',
            'area_id'=> 'required',
            'date'=> 'required',
            'time'=> 'required',
            'pickup_point'=> 'required',
            'description'=> 'required',
            'is_mygap'=> 'required',
            'is_organic'=> 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        if(!isset($request->species_id) && !isset($request->other_species)){
            return $this->responseError("Species Id/Other Species is required");
        }

        if(!isset($request->imported) && !isset($request->other_imported_info)){
            return $this->responseError("Is Imported/Other Imported is required");
        }

        $post = Post::find($request->post_id);
        if (!$post) {
            return $this->responseError("Post Not Found");
        }

        $user = UserProfile::find($post->product->user_profile_id);
        if($user->is_approved_status == 0){
            return $this->responseError("Sorry User has not Verified yet");
        }

        $frame = Frame::first();
        if(isset($request->is_repost) && $request->is_repost == 1){
            if((int)$post->product->repost >=  (int)$frame->repost){
                return $this->responseError("You can not Repost more than ".(int)$frame->repost);
            }

            /* $current_date = Carbon::now();
             if(strtotime($current_date) > strtotime($post->product->end_time)){
                 return $this->responseError("You can Repost within 24 hour after your post ended");
             }*/
        }

        //if full company detail is there then only seller can add post
        $check_company_detail = CommonHelper::check_company_detail($user);
        if(!$check_company_detail){
            return $this->responseError("Please Enter Your Company Detail First");
        }

        $credit_per_transaction=CreditSetting2::where('main_category_id',$request->main_category_id)->get();
        if (count($credit_per_transaction)==0){
            return $this->responseError('please select other main category.Admin not decided transaction fee for this.');
        }
        $transaction_fee='';
        foreach ($credit_per_transaction as $cpt){
            $exploded_subs=explode(",",$cpt->sub_categories);
            if(in_array($request->sub_category_id,$exploded_subs)) {
                foreach ($exploded_subs as $es) {
                    if ($es == $request->sub_category_id) {
                        $transaction_fee = ($cpt->credit_per_transaction) * ($request->qty);
                    }
                }
            }
        }
        if ($transaction_fee==''){
            return $this->responseError('please select other sub category.Admin not decided transaction fee for this.');
        }

        $images = $request->file('product_image');
        if (!empty($images)) {
            //first check all image extension
            foreach ($images as $image) {
                $ext = $image->getClientOriginalExtension();
                $ext = strtolower($ext);
                // $all_ext = array("png","jpg", "jpeg", "jpe", "jif", "jfif", "jfi","tiff","tif","raw","arw","svg","svgz","bmp", "dib","mpg","mp2","mpeg","mpe");
                $all_ext = array("png", "jpg", "jpeg");
                if (!in_array($ext, $all_ext)) {
                    return $this->responseError('Invalid type of image.');
                }
            }
        }

        if(isset($request->deleted_images) && isset($request->deleted_images[0])){
           // DB::connection()->enableQueryLog();
//            dd($request->deleted_images);
            $deleted_images = ProductImage::havingRaw('CONCAT("'.env('APP_URL').'",image) in ("'.implode('","',$request->deleted_images).'")')
                ->where('product_id',$post->product_id)->get();
//            $queries = DB::getQueryLog();
            //dd($queries);
            foreach ($deleted_images as $deleted_image){
                $image = public_path($deleted_image->local_image);
                if(file_exists($image)){
                    unlink($image);
                }
                $deleted_image->delete();
            }
        }

//        $credit_amount = CommonHelper::user_credit_balance($request->user_profile_id);
//        if((int)$credit_amount < (int)$request->qty){
//            return $this->responseError('You have not enough credit to post.');
//        }

        $date = trim($request->date);
        $time = trim($request->time);
//        $utc_start_date_time = CommonHelper::LocalToUtcDateTime($date.' '.$time);
        $utc_start_date_time = $date.' '.$time;
        $end_date = date('Y-m-d H:i:s',strtotime('+1 days +'.$frame->frame.' minutes',strtotime($utc_start_date_time)));

        //update post-product
        $product = $post->product;
        $product->product_name = $request->product_name;
        $product->main_category_id = $request->main_category_id;
        $product->sub_category_id = $request->sub_category_id;
        $species_id = $request->species_id;
        if(!isset($species_id)) {
            $species_id = 0;
        }
        $product->species_id = $species_id;
        $other_species = $request->other_species;
        if(!isset($other_species)) {
            $other_species = '';
        }
        $product->other_species = $other_species;
        $imported = $request->imported;
        if(!isset($imported)) {
            $imported = 0;
        }
        $product->imported = $imported;
        $other_imported_info = $request->other_imported_info;
        if(!isset($other_imported_info)) {
            $other_imported_info = '';
        }
        $product->other_imported_info = $other_imported_info;
        $product->grade_id = $request->grade_id;
        $product->state_id = $request->state_id;
        $product->area_id = $request->area_id;
        if(!$request->url) {
            $request->url = '';
        }
        $product->url = $request->url;
        $product->pickup_point = $request->pickup_point;
        $product->description = $request->description;
        $fast_buy_price = $request->fast_buy_price;
        $fast_buy = 1;
        if(!isset($fast_buy_price)) {
            $fast_buy_price = 0;
            $fast_buy = 0;
        }
        elseif($fast_buy_price==0){
            $fast_buy_price = 0;
            $fast_buy = 0;
        }
        $product->fast_buy_price = $fast_buy_price;
        $product->fast_buy = $fast_buy;
        $product->is_mygap = $request->is_mygap;
        $product->end_time = $end_date;
        $product->is_organic = $request->is_organic;
        if(isset($request->is_repost) && $request->is_repost == 1){
            $product->repost = (int)$product->repost + 1;
        }
        $product->save();

        if (!empty($images)) {
            //image existence already checked upside
            foreach ($images as $image){

                $image_path = 'product_images/';
                $image_name = 'Product_' . time() . rand(11111,99999) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($image_path), $image_name);
                $imagePath = $image_path . $image_name;

                $product_image = new ProductImage;
                $product_image->image = $imagePath;
                $product_image->product_id = $product->id;
                $product_image->save();
            }
        }

        //if is_repost then create new post else update it
        if(isset($request->is_repost) && $request->is_repost == 1){
            $post = new Post();
        }
//        $credit_per_transaction=WeightUnit::where('id',$request->weight_unit_id)->get();

        $post->product_id = $product->id;
        $post->starting_price = $request->first_price;
        $post->second_price = $request->second_price;
        $post->third_price = $request->third_price;
        $post->fourth_price = $request->fourth_price;
        $post->ended_price = $request->ended_price;
        $post->qty = $request->qty;
        $post->weight_unit_id = $request->weight_unit_id;
        $post->date_time = $utc_start_date_time;
        $post->frame = (int)$frame->frame * 60;
//        $post->credit_fee = $request->qty;
        /*dishita*/
        $credit_per_transaction=CreditSetting2::where('main_category_id',$request->main_category_id)->get();
        foreach ($credit_per_transaction as $cpt){
            $exploded_subs=explode(",",$cpt->sub_categories);
            foreach ($exploded_subs as $es){
                if($es==$request->sub_category_id){
                    $transaction_fee = ($cpt->credit_per_transaction)*($request->qty);
                }
            }
        }
        $post->credit_fee = $transaction_fee;
        /*dishita*/
        $post->save();

//        //remove credit from user account at add post time
//        $credit_transaction = new CreditTransaction();
//        $credit_transaction->type = 1;//debit credit-points
//        $credit_transaction->user_profile_id = $request->user_profile_id;
//        $credit_transaction->amount = $request->qty;
//        $credit_transaction->save();

        if(isset($request->is_repost) && $request->is_repost == 1){
            $remain_repost=($frame->repost)-($product->repost);
//            dd($remain_repost);
            return $this->sendSuccess("Repost Successfully. Now ".$remain_repost." time repost remaining.");
        }
        return $this->sendSuccess("Post Updated Successfully!");
    }

    public function upcomingTradePost(Request $request)
    {
//        dd(\Carbon\Carbon::parse($request->date.' '.$request->time, $request->timezone)->setTimezone("UTC")->format('Y-m-d H:i'));
        $messages = [
            'user_profile_id.required'=>'please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id'=>'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        $frame = Frame::find(1)->value('frame');

        //if up-coming date/time is not given(with next interval additional second)
        $local_time = Carbon::now($request->time_zone)->addSeconds((int)$frame*60)->format('H:i');
        $local_date = null;
        if(isset($request->date)){
            $local_date = Carbon::parse($request->date)->format('Y-m-d');
            //$local_time = Carbon::now()->format('H:i');
            $local_time = '00:00';
        }

        //request time should be after request date(to overwrite local time)
//        $request->time = isset($request->time) ? $request->time : Carbon::now()->format('H:i');
        if(isset($request->time)){
            $local_time = Carbon::parse($request->time)->format('H:i');
        }
        //dd($local_time);

        $time_array = CommonHelper::getTimeArray($request->time_zone,(int)$frame*60);//frame is 15 minute
        $dates = CommonHelper::getUpcomingTradeDates($time_array,$local_time,$local_date,$request);
        if($dates['message'] != ''){
            return $this->responseError($dates['message']);
        }
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        //get posts
        $startTime   = strtotime($startDate);
        $startDate1=date("Y-m-d",$startTime);
        $posts = Post::with(['product.product_image', 'product.user.getSellerRate', 'creditmanagement', 'allFavouritePost' => function($query) use($request){
            $query->where('user_profile_id',$request->user_profile_id)->select('id','post_id');
        }])
            ->where('posts.date_time','>=',$startDate)
//            ->whereRaw('"'.$startDate1.'"= DATE(posts.date_time)')
            ->where('is_pause',0)
            ->where('can_show',1)
            ->orderBy('date_time','asc'); /*dishita*/
//        $posts = $posts->get();
//        dd($posts);
        if (isset($request->time)){
            $posts = Post::with(['product.product_image', 'product.user.getSellerRate', 'creditmanagement', 'allFavouritePost' => function($query) use($request){
                $query->where('user_profile_id',$request->user_profile_id)->select('id','post_id');
            }])
                ->where('posts.date_time',$startDate)
                ->where('is_pause',0)
                ->where('can_show',1)
                ->orderBy('created_at','asc'); /*dishita*/
        }
        if(!isset($request->time) && isset($request->date)) {
            $curdate = Carbon::now($request->time_zone)->format('Y-m-d');
            if ($curdate != $request->date) {
                $posts = Post::with(['product.product_image', 'product.user.getSellerRate', 'creditmanagement', 'allFavouritePost' => function($query) use($request){
                    $query->where('user_profile_id',$request->user_profile_id)->select('id','post_id');
                }])
                    ->wherebetween('posts.date_time',array($startDate,$endDate))
                    ->where('is_pause',0)
                    ->where('can_show',1)
                    ->orderBy('date_time','asc'); /*dishita*/
            }
            if($curdate==$request->date){
                $posts= Post::with(['product.product_image', 'product.user.getSellerRate', 'creditmanagement', 'allFavouritePost' => function($query) use($request){
                    $query->where('user_profile_id',$request->user_profile_id)->select('id','post_id');
                }])
                    ->where('posts.date_time','>=',$startDate)
                     ->whereRaw('"'.$startDate1.'"= DATE(posts.date_time)')
                    ->where('is_pause',0)
                    ->where('can_show',1)
                    ->orderBy('date_time','asc'); /*dishita*/
            }
        }

//        dd($startDate,$posts->limit(10)->get()->toArray());

        if(isset($request->sub_category_ids)){
            $sub_category = explode(',',$request->sub_category_ids);
            $posts = $posts->whereHas('product',function ($query) use ($sub_category){
                $query->whereIn('sub_category_id',$sub_category);
            });
        }

        if($request->fast_buy){
            $posts = $posts->whereHas('product',function ($query){
                $query->where('fast_buy',1);
            });
        }

        if($request->is_preferred){
            $posts = $posts->whereHas('product.user',function ($query) use($request){
                $query->select(DB::raw('(Case WHEN is_approved_status = 1 THEN preferred_status ELSE 0 END) as is_preferred'))
                    ->HavingRaw('is_preferred = ?',[$request->is_preferred]);
            });
        }

        if($request->state_id){
            $posts = $posts->whereHas('product',function ($query) use($request){
                $query->where('state_id',$request->state_id);
            });
        }

        if($request->area_id){
            $posts = $posts->whereHas('product',function ($query) use($request){
                $query->where('area_id',$request->area_id);
            });
        }

        if($request->search){
            $posts = $posts->whereHas('product',function ($query) use($request){
                $query->where('product_name','LIKE','%'.$request->search.'%');
            });
        }
        $posts = $posts->get();

        $response = array();
        $upcomingPosts = $this->getPostsDetailList($posts,$request->time_zone,$request->fast_buy);

        $banners = CommonHelper::banners($request->time_zone);
        //sort array
        $date = array_column($time_array, 'date');
        array_multisort($date, SORT_ASC, $time_array);
        $filtered = array_filter($time_array, function ($time) use($request){
            $local_date  = Carbon::now($request->time_zone)->format('Y-m-d');
            if(strtotime($time['date']) == strtotime($local_date)){
                return $time;
            }
        });

        $response['time_array'] = $filtered;
        $response['date'] = CommonHelper::UTCToLocalDateTime($startDate, $request->time_zone)->format('Y-m-d');
        $response['time'] = CommonHelper::UTCToLocalDateTime($startDate, $request->time_zone)->format('H:i');
        $response['upcoming_posts'] = $upcomingPosts;
        $response['banners'] = $banners;

        return $this->responseWithData($response,"Upcoming Trade retrieved Successfully.");
    }

    public function buyNow(Request $request)
    {
        $messages = [
            'post_id.required'=>'please enter post id.',
            'user_profile_id.required'=>'Buyer is required',
            'bid_price.required'=>'Bid-price is required',
            'fast_buy.required'=>'please enter fast buy for fast buy or not',
        ];

        $validator = Validator::make($request->all(), [
            'post_id'=>'required',
            'user_profile_id'=>'required',
            'bid_price'=>'required',
            'fast_buy'=>'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $post = Post::find($request->post_id);
        if (!$post) {
            return $this->responseError("Post Not Found");
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }
        if($user->is_approved_status == 0){
            return $this->responseError("Sorry User has not Verified yet");
        }
        if($post->product->user_profile_id == $request->user_profile_id){
            return $this->responseError("you already posted this.");
        }
        if($user->parent_id==0){
            $subusers=UserProfile::where('parent_id',$user->id)->pluck('id');
            foreach ($subusers as $s){
                if($s==$post->product->user_profile_id){
                    return $this->responseError("Not allow to purchase item posted by main or sub admin.");
                }
            }
        }
        else{
            if($user->parent_id==$post->product->user_profile_id){
                return $this->responseError("Not allow to purchase item posted by main or sub admin.");
            }
        }

        $my_subscription = MySubscription::Join('subscriptions','my_subscriptions.subscription_id','=','subscriptions.id')
            ->where('user_profile_id',$user->id)
            ->where('subscriptions.package_type',0)
            ->where('my_subscriptions.is_running',1)
            ->where('my_subscriptions.status',2)
            ->where('subscriptions.status',1)
            ->whereNull('subscriptions.deleted_at')
            ->orderBy('subscriptions.bidding','DESC')->first(['subscriptions.bidding']);

        if(!$my_subscription){
            return $this->responseError("For bidding this post you have to subscribe first");
        }

        $total_amount = (float)$post->qty * (float)$request->bid_price;
        if((float)$total_amount > (float)$my_subscription['bidding']){
            return $this->responseError("you can bidding below RM".$my_subscription['bidding']);
        }

        /*dishita*/
        if($user->parent_id==0){
            $credit_amount = CommonHelper::user_credit_balance($request->user_profile_id);
        }
        else{
            $main_user=UserProfile::where('id',$user->parent_id)->first();
            $credit_amount = CommonHelper::user_credit_balance($main_user->id);
        }
        /*dishita*/
//        $credit_amount = CommonHelper::user_credit_balance($request->user_profile_id);
        $transaction_fee=$post->credit_fee;
        if((int)$credit_amount < (int)$transaction_fee){
            return $this->responseError('You have not enough credit to buy this post.');
        }

//        $credit_per_transaction=WeightUnit::where('id',$post->weight_unit_id)->get();
        /*dishita*/
        if($user->parent_id!=0){
            $main_user=UserProfile::where('id',$user->parent_id)->first();
            //add data in credit transaction
            $credit_transaction = new CreditTransaction();
            $credit_transaction->type = 1;//for debit
            $credit_transaction->user_profile_id = $main_user->id;
            $credit_transaction->amount = $transaction_fee;
            $credit_transaction->save();
        }
        /*dishita*/
        else {
            //remove credit from user account at add post time
            $credit_transaction = new CreditTransaction();
            $credit_transaction->type = 1;//debit credit-points
            $credit_transaction->user_profile_id = $request->user_profile_id;
            $credit_transaction->amount = $transaction_fee;
            $credit_transaction->save();
        }

        $credit_management = new CreditManagement();
        $credit_management->buyer_id = $request->user_profile_id;
        $credit_management->post_id = $request->post_id;
        $credit_management->bid_price = $request->bid_price;
        $credit_management->buyer_fees = $transaction_fee;
//        $credit_management->buyer_fees = ($post->qty)*($credit_per_transaction[0]->credit_per_transaction);
        $credit_management->credit_transaction_id = $credit_transaction->id;
        $credit_management->purchase_price = (float)$post->qty * (float)$request->bid_price;
        $credit_management->transaction_status = 0;//payment pending
        $credit_management->save();

        //once this api call post will hide from live trade
        $post->can_show = 0;
        $post->save();

        $users = $post->product->user_profile_id;
        $users=(array)$users;
        if($request->fast_buy==1){
            try {
//                Log::error($users);
                $notifications=new Notifications_api();
                $notifications->from_user_id=$request->user_profile_id;
                $notifications->to_user_id	=$post->product->user_profile_id;
                $notifications->post_id=$request->post_id;
                $notifications->bid_price=$request->bid_price;
                $notifications->fast_buy=$request->fast_buy;
                $notifications->desc="fast_buy";

                $notification_array = array();
                $notification_array['title'] = $notifications->desc;
                $fromname=UserProfile::where('id',$notifications->from_user_id)->pluck('name')->first();
                $productid=Post::where('id',$notifications->post_id)->pluck('product_id')->first();
                $pronm=Product::where('id',$productid)->pluck('product_name')->first();
                $notification_array['message'] = $fromname." fast buy ".$pronm." at price ".$notifications->bid_price;

                $notifications->save();
                CommonHelper::sendPushNotification($users,$notification_array);
//                CommonHelper::my_sendpushnotifications($users,$notification_array);
            } catch (\Exception $e) {
//                dd($e->getTraceAsString());
                Log::error($e->getTraceAsString());
            }
        }
        if($request->fast_buy==0){
            try {
//                Log::error($users);
                $notifications=new Notifications_api();
                $notifications->from_user_id=$request->user_profile_id;
                $notifications->to_user_id	=$post->product->user_profile_id;
                $notifications->post_id=$request->post_id;
                $notifications->bid_price=$request->bid_price;
                $notifications->fast_buy=$request->fast_buy;
                $notifications->desc="purchase";

                $notification_array = array();
                $notification_array['title'] = $notifications->desc;
                $fromname=UserProfile::where('id',$notifications->from_user_id)->pluck('name')->first();
                $productid=Post::where('id',$notifications->post_id)->pluck('product_id')->first();
                $pronm=Product::where('id',$productid)->pluck('product_name')->first();
                $notification_array['message'] = $fromname." purchase ".$pronm." at price ".$notifications->bid_price;

                $notifications->save();
                CommonHelper::sendPushNotification($users,$notification_array);
//                CommonHelper::my_sendpushnotifications($users,$notification_array);
            } catch (\Exception $e) {
                Log::error($e->getTraceAsString());
            }
        }

        $response = array();
        $response['product_detail'] = $this->purchase_detail($credit_management->id);

        $response['payment_methods'] = array(
            'COD'
        );
        return $this->responseWithData($response,"Post Updated Successfully!");
    }

    public function payOut(Request $request){

        $messages = [
            'credit_management_id.required'=>'please enter Credit Management id.',
            'payment_type.required'=>'please enter payment type'
        ];

        $validator = Validator::make($request->all(), [
            'credit_management_id'=>'required',
            'payment_type'=>'required'
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $credit_management = CreditManagement::find($request->credit_management_id);
        if(!$credit_management){
            return $this->responseError("Purchase Detail Not Found");
        }

        $user = UserProfile::find($credit_management->post->product->user_profile_id);
        if($user->is_approved_status == 0){
            return $this->responseError("Sorry User has not Verified yet");
        }

        $frame=Frame::find(1);
        if($request->payment_type==1){
            $service_fee=(($credit_management->purchase_price)*($frame->creditcard))/100;
        }
        elseif ($request->payment_type==2){
            $service_fee=$frame->fpx;
        }
        else{
            return $this->responseError("Invalid payment type");
        }
        $credit_management->payment_type = $request->payment_type;
        $transaction_id = "TRANS".$credit_management->post->product->product_id;
        //update Credit Management table (as payment done)
        $credit_management->transaction_id = $transaction_id;
        $credit_management->transaction_status = 1;//transaction success
        $credit_management->transaction_checked_at = Carbon::now();
        $credit_management->service_fee = $service_fee;
        $credit_management->total_amount = ($credit_management->purchase_price)+($service_fee);
        $credit_management->save();

        return $this->sendSuccess("Successful Deal!");
    }

    public function purchase_detail($credit_management_id){

        $credit_management = CreditManagement::find($credit_management_id);
        $response = array();
        if($credit_management){

            $post_product = $credit_management->post->product;
            $credit_management_post = $credit_management->post;

            $response['id'] = $credit_management->id;
            $response['post_id'] = $credit_management->post_id;
            $response['display_post_id'] = $post_product->product_id;
            $response['product_name'] = $post_product->product_name;
            $response['image'] = isset($post_product->product_image) ? $post_product->product_image->image : '';
            $response['description'] = isset($post_product->description) ? $post_product->description : '';
            $response['main_category'] = isset($post_product->maincategory->name) ? $post_product->maincategory->name : '';
            $response['sub_category'] = isset($post_product->subcategory->name) ? $post_product->subcategory->name : '';
            $response['species'] = isset($post_product->species->name) ? $post_product->species->name : '';
            $response['other_species'] = isset($post_product->other_species) ? $post_product->other_species : '';
            $response['is_imported'] = $post_product->imported;
            $response['other_imported_info'] = isset($post_product->other_imported_info) ? $post_product->other_imported_info : '';
            $response['url'] = $post_product->url;
            $response['pickup_point'] = $post_product->pickup_point;
            $response['address'] = $post_product->address;
            $response['weight'] = $credit_management_post->weight;
            $response['grade'] = $post_product->grade;
            $response['weight_unit'] = $credit_management_post->unit;
            $response['starting_price'] = $credit_management_post->starting_price;
            $response['second_price'] = $credit_management_post->second_price;
            $response['third_price'] = $credit_management_post->third_price;
            $response['fourth_price'] = $credit_management_post->fourth_price;
            $response['ended_price'] = $credit_management_post->ended_price;
            $response['fast_buy'] = $post_product->fast_buy;
            $response['fast_buy_price'] = $post_product->fast_buy_price;
            $response['is_mygap'] = $post_product->is_mygap;
            $response['is_organic'] = $post_product->is_organic;
            $response['post_start_date'] = $credit_management_post->display_date_time;
            $response['post_utc_start_date'] = CommonHelper::UTCDateTime($credit_management_post->date_time)->format('Y-m-d \a\\t h.ia');
            $response['transaction_fee'] = $credit_management->buyer_fees.' credit';
            $response['trade_price'] = 'RM'.$credit_management->bid_price;
            $response['purchase_price'] = 'RM '.$credit_management->purchase_price;
            $frame=Frame::find(1);
            $response['creditcard_percentage']=$frame->creditcard.'%';
            $response['creditcard_servicefee']='RM'.(($credit_management->purchase_price)*($frame->creditcard))/100;
            $response['fpx_servicefee']='RM'.$frame->fpx;
            $response['creditcard_total_payout']='RM '.($credit_management->purchase_price + ((($credit_management->purchase_price)*($frame->creditcard))/100));
            $response['fpx_total_payout']='RM '.($credit_management->purchase_price + $frame->fpx);
            $response['purchase_date'] = $credit_management->display_start_date;
            $response['purchase_utc_date'] = CommonHelper::UTCDateTime($credit_management->created_at)->format('Y-m-d \a\\t h.ia');
        }
        return $response;
    }

    public function ended_trade_posts(Request $request){
        $frame = Frame::find(1);
        $currdate= Carbon::now();
        $posts=Post::select('posts.*',DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND) as end_date'))
            ->where(function ($query) use($currdate){
                $query->whereRaw('"'.$currdate.'">= DATE_ADD(date_time, INTERVAL frame SECOND)')
                    ->orWhere('can_show',0);
            })
            ->where('is_pause',0);

        $duration=86400;
        if(isset($request->date) && isset($request->time)){
            $local_date = Carbon::parse(trim($request->date))->format('Y-m-d');
            $local_time = Carbon::parse(trim($request->time))->format('H:i');
            if($local_date > date($currdate)){
                return $this->responseError("Enter Past date-time to view ended trade posts.");
            }
            $start_date=CommonHelper::LocalToUtcDateTime($local_date.' '.$local_time,"UTC");
            $end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$duration.'seconds',strtotime($start_date)));

//            $posts=$posts ->wherebetween(DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND)'),array($start_date,$end_date))->get();
            $posts=$posts ->where(DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND)'),$start_date);

        }
        elseif (isset($request->date)){
            $local_date = Carbon::parse(trim($request->date))->format('Y-m-d');
            if($local_date > date($currdate)){
                return $this->responseError("Enter Past date to view ended trade posts.");
            }
            $start_date=CommonHelper::LocalToUtcDateTime($local_date,"UTC");
            $end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$duration.'seconds',strtotime($start_date)));
            $posts=$posts ->wherebetween(DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND)'),array($start_date,$end_date));
        }
        elseif (isset($request->time)){
            $local_time = Carbon::parse(trim($request->time))->format('H:i');

            $todaydate=Carbon::today();
            $local_date=CommonHelper::UTCToLocalDateTime($todaydate)->format('Y-m-d');
            $start_date=CommonHelper::LocalToUtcDateTime($local_date.' '.$local_time,"UTC");
            $end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$duration.'seconds',strtotime($start_date)));
            $posts=$posts ->wherebetween(DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND)'),array($start_date,$end_date));
        }

        if(isset($request->sub_category_ids)){
            $sub_category = explode(',',$request->sub_category_ids);
            $posts = $posts->whereHas('product',function ($query) use ($sub_category){
                $query->whereIn('sub_category_id',$sub_category);
            });
        }
        if($request->is_preferred){
            $posts = $posts->whereHas('product.user',function ($query) use($request){
                $query->select(DB::raw('(Case WHEN is_approved_status = 1 THEN preferred_status ELSE 0 END) as is_preferred'))
                    ->Having('is_preferred','=',$request->is_preferred);
            });
        }
//        dd($posts->limit(10)->get()->toArray());
//        dd($posts);
//        $posts=$posts->limit(10)->get();
//        dd($posts->toArray());
        if($request->state_id){
            $posts = $posts->whereHas('product',function ($query) use($request){
                $query->where('state_id',$request->state_id);
            });
        }

        if($request->area_id){
            $posts = $posts->whereHas('product',function ($query) use($request){
                $query->where('area_id',$request->area_id);
            });
        }
        if($request->search){
            $posts = $posts->whereHas('product',function ($query) use($request){
                $query->where('product_name','LIKE','%'.$request->search.'%');
            });
        }
        $posts= $posts->orderBy('date_time','DESC')->get();
        $response=array();
        $ended_posts=array();
        foreach ($posts as $post){
            $reviews = Rating::where('seller_id',$post->product->user->id)->count('review');
            $rateavg = Rating::where('seller_id',$post->product->user->id)->avg('rate');
            $as_buyer_review = Rating::where('buyer_id',$post->product->user->id)->whereNotNull('review')->where('review','!=','')->count('review');

            if(isset($post->creditmanagement)) {
                $post_review = Rating::where('credit_management_id', $post->creditmanagement->id)->count('review');
                $post_ratings = Rating::where('credit_management_id', $post->creditmanagement->id)->avg('rate');
                $post_end_date= Carbon::parse($post->creditmanagement->created_at, "UTC")->setTimezone($request->timezone)->format('d.m.Y \a\\t h.ia');
            }
            else{
                $post_review=0;
                $post_ratings=0;
                $post_end_date=CommonHelper::addSecondsToDate($post->date_time,$post->frame,'d.m.Y \a\\t h.ia',$request->timezone);
            }

            $temp=array();
            $seller=array();
            $temp['post_id'] = $post->id;
            if(isset($request->user_profile_id)){
                $fav=Favourite::where('user_profile_id',$request->user_profile_id)->where('post_id',$post->id)->count();
                if($fav==0)
                    $temp['is_favourite']=0;
                else
                    $temp['is_favourite']=1;
            }
            $temp['product_images']=$post->product->app_post_images;
            $temp['product_name'] = $post->product->product_name;
            $temp['is_preferred'] = $post->product->user->is_approved_status ? $post->product->user->preferred_status : 0;
            $temp['weight']=$post->weight;
            $temp['qty'] = $post->qty;
            $temp['unit'] = $post->unit;
            $temp['main_category'] = isset($post->product->maincategory->name) ? $post->product->maincategory->name : '';
            $temp['sub_category'] = isset($post->product->subcategory->name) ? $post->product->subcategory->name : '';
            $temp['species'] = isset($post->product->species->name) ? $post->product->species->name : '';
            $temp['other_species'] = $post->product->other_species;
            $temp['is_imported'] = $post->product->imported;
            $temp['other_imported_info'] = $post->product->other_imported_info;
            $temp['grade'] = ($post->product->grade_id>0)?$post->product->grade:'';
            $temp['state'] = $post->product->state->name ?? '';
            $temp['area'] = $post->product->area->name ?? '';
            $temp['url'] = $post->product->url;
            $temp['pickup_point'] = $post->product->pickup_point;
            $temp['description'] = $post->product->description;
            $temp['fast_buy'] = $post->product->fast_buy;
            $temp['fast_buy_price'] = $post->product->fast_buy_price;
            $temp['is_mygap'] = $post->product->is_mygap;
            $temp['is_organic'] = $post->product->is_organic;
            $temp['id'] = $post->product->product_id;
            $temp['post_start_date'] = \Carbon\Carbon::parse($post->date_time, "UTC")->setTimezone($request->timezone)->format('d.m.Y \a\\t h.ia');
            $temp['post_end_date'] = $post_end_date;
            $temp['post_utc_start_at'] = CommonHelper::UTCDateTime($post->date_time)->format('Y-m-d \a\\t h.ia');
            $temp['post_utc_end_at'] = CommonHelper::addSecondsToUTCDate($post->date_time,$post->frame,'Y-m-d \a\\t h.ia');
            $temp['post_review_count']=$post_review;
            $temp['post_ratings']=isset($post_ratings)?$post_ratings:0;
            $pricedropstime=CommonHelper::getPricedropArray($post->date_time, ((int)$post->frame/5), (int)$post->frame);
            $prices=array($post->starting_price,$post->second_price,$post->third_price,$post->fourth_price,$post->ended_price);
            $pricedrophistory=array();
            $i=0;
            foreach ($pricedropstime as $pricedroptime){
                $pricedrops=array();
                $pricedrops['time']=$pricedroptime;
                $pricedrops['price']=$prices[$i];
                array_push($pricedrophistory,$pricedrops);
                $i++;
            }
            $temp['price_drop_history']=$pricedrophistory;
            $seller['id']=$post->product->user_profile_id;
            $seller['name']=$post->product->user->name;
            $seller['email']=$post->product->user->email;
            $seller['profile_pic']=$post->product->user->profile_pic!="" ? url($post->product->user->profile_pic) : '';
            $seller['phone_no'] = $post->product->user->phone_no;
            $seller['user_type'] = isset($post->product->user->user_type) ? array_search($post->product->user->user_type,Type::toArray()) : '';
            $seller['main_category'] = isset($post->product->user->maincategory->name) ? $post->product->user->maincategory->name : '';
            $seller['main_category_id'] = isset($post->product->user->main_category_id) ? $post->product->user->main_category_id : 0;
            $seller['sub_category'] = isset($post->product->user->subcategory->name) ? $post->product->user->subcategory->name : '';
            $seller['sub_category_id'] = isset($post->product->user->sub_category_id) ? $post->product->user->sub_category_id : 0;
            $seller['company_name'] = isset($post->product->user->company_name) ? $post->product->user->company_name : '';
            $seller['company_reg_no'] = isset($post->product->user->company_reg_no) ? $post->product->user->company_reg_no : '';
            $seller['company_tel_no'] = isset($post->product->user->company_tel_no) ? $post->product->user->company_tel_no : '';
            $seller['state'] = isset($post->product->user->state->name) ? $post->product->user->state->name : '';
            $seller['area'] = isset($post->product->user->area->name) ? $post->product->user->area->name : '';
            $seller['address'] = isset($post->product->user->address) ? $post->product->user->address : '';
            $seller['company_email'] = isset($post->product->user->company_email) ? $post->product->user->company_email : '';
            $seller['document'] = isset($post->product->user->document) ? url('public/'.$post->product->user->document) : '';
            $seller['job_description'] = isset($post->product->user->job_description) ? $post->product->user->job_description : '';
            $seller['preferred_status'] = $post->product->user->preferred_status;
            $seller['review_count']=$reviews;
            $seller['rating']=isset($rateavg) ? $rateavg : 0;
            $seller['as_buyer_review'] = $as_buyer_review;

            $temp['seller_details']=$seller;
            $buyers_detail=$post->buyer_detail;
            if(!empty($buyers_detail)) {
                $buyers_detail['purchase_date'] = \Carbon\Carbon::parse($buyers_detail['purchase_date'], "UTC")->setTimezone($request->timezone)->format('d.m.Y \a\\t h.ia');
//                $temp['buyers_detail']=$buyers_detail;
            }
//            else{
//                $temp['buyers_detail']=null;
//            }
            $temp['buyers_details']= (object)$buyers_detail;

            array_push($ended_posts,$temp);
        }
        $banners = CommonHelper::banners();
        $response['ended_trade_posts']=$ended_posts;
        $response['banners']=$banners;
        return $this->responseWithData($response,"Ended trade posts retrieved successfully");
    }

    public function purchaseHistory(Request $request){

        $messages = [
            'user_profile_id.required'=>'Buyer is required',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id'=>'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        if(isset($request->post_id) && isset($request->user_profile_id) && isset($request->bid_price)){
            $post = Post::find($request->post_id);
            if (!$post) {
                return $this->responseError("Post Not Found");
            }
            if($user->is_approved_status == 0){
                return $this->responseError("Sorry User has not Verified yet");
            }
            if($post->product->user_profile_id == $request->user_profile_id){
                return $this->responseError("you already posted this.");
            }
            if($user->parent_id==0){
                $subusers=UserProfile::where('parent_id',$user->id)->pluck('id');
                foreach ($subusers as $s){
                    if($s==$post->product->user_profile_id){
                        return $this->responseError("Not allow to purchase item posted by main or sub admin.");
                    }
                }
            }
            else{
                if($user->parent_id==$post->product->user_profile_id){
                    return $this->responseError("Not allow to purchase item posted by main or sub admin.");
                }
            }

            $my_subscription = MySubscription::Join('subscriptions','my_subscriptions.subscription_id','=','subscriptions.id')
                ->where('user_profile_id',$user->id)
                ->where('subscriptions.package_type',0)
                ->where('my_subscriptions.is_running',1)
                ->where('my_subscriptions.status',2)
                ->where('subscriptions.status',1)
                ->whereNull('subscriptions.deleted_at')
                ->orderBy('subscriptions.bidding','DESC')->first(['subscriptions.bidding']);

            if(!$my_subscription){
                return $this->responseError("For bidding this post you have to subscribe first");
            }

            $total_amount = (float)$post->qty * (float)$request->bid_price;
            if((float)$total_amount > (float)$my_subscription['bidding']){
                return $this->responseError("you can bidding below RM".$my_subscription['bidding']);
            }

            /*dishita*/
            if($user->parent_id==0){
                $credit_amount = CommonHelper::user_credit_balance($request->user_profile_id);
            }
            else{
                $main_user=UserProfile::where('id',$user->parent_id)->first();
                $credit_amount = CommonHelper::user_credit_balance($main_user->id);
            }
            /*dishita*/
//        $credit_amount = CommonHelper::user_credit_balance($request->user_profile_id);
            $transaction_fee=$post->credit_fee;
            if((int)$credit_amount < (int)$transaction_fee){
                return $this->responseError('You have not enough credit to buy this post.');
            }
            $frame=Frame::find(1);
            $response=array();
            $response['transaction_fee']=$post->credit_fee;
            $response['creditcard_percentage']=$frame->creditcard.'%';
            $purchase_price = (float)$post->qty * (float)$request->bid_price;
            $response['creditcard_servicefee']='RM '.(($purchase_price)*($frame->creditcard))/100;
            $response['fpx_servicefee']='RM '.$frame->fpx;
            $response['creditcard_total_payout']='RM '.($purchase_price + ((($purchase_price)*($frame->creditcard))/100));
            $response['fpx_total_payout']='RM '.($purchase_price + $frame->fpx);
            return $this->responseWithData($response,'you are eligible for buy this product.');
        }

        $purchase_histories = CreditManagement::select('credit_managements.*')
            ->with('post.product.product_image','getRatings')
            ->where('buyer_id',$request->user_profile_id)
            ->orderby('created_at','DESC')
            ->get();

        $response = array();
        foreach ($purchase_histories as $purchase_history){

            $pay_now_show = 0;
            $rate_show = 0;
            if($purchase_history->transaction_status != 1){
                $pay_now_show = 1;
            } else {
                $rate_show = 1;
            }
            $rating = array();
            if($purchase_history->getRatings){
                $rate_show = 2;
                $rating['rate'] = $purchase_history->getRatings->rate;
                $rating['review'] = $purchase_history->getRatings->review;
            }
            if(empty($rating)){
                $rating=null;
//                $rating=gettype($rating);
//                $rating=json_decode($rating, true);
//                $rating=str_replace('"', '',$rating);
//                $rating=gettype($rating);
//                $rating=json_decode($rating);
            }

            $purchase_post = $purchase_history->post;
            $temp = array();
            $temp['id'] = $purchase_history->id;
            $temp['post_id'] = $purchase_history->post_id;
            $temp['display_post_id'] = $purchase_post->product->product_id;
            $temp['purchase_date'] = \Carbon\Carbon::parse($purchase_history->created_at, "UTC")->setTimezone($request->timezone)->format('d.m.Y \a\\t h.ia');
            $temp['product_name'] = $purchase_post->product->product_name;
            $temp['transaction_fee'] = $purchase_history->buyer_fees;
            $temp['trade_price'] = $purchase_history->bid_price;
            $temp['unit'] = $purchase_post->weight;
            $temp['total_payout'] = $purchase_history->total_amount;
            $temp['image'] = isset($purchase_post->product->product_image) ? $purchase_post->product->product_image->image : '';
            $temp['weight_unit'] = $purchase_post->unit;
            $temp['starting_price'] = $purchase_post->starting_price;
            $temp['second_price'] = $purchase_post->second_price;
            $temp['third_price'] = $purchase_post->third_price;
            $temp['fourth_price'] = $purchase_post->fourth_price;
            $temp['ended_price'] = $purchase_post->ended_price;
            $temp['fast_buy'] = $purchase_post->product->fast_buy;
            $temp['fast_buy_price'] = $purchase_post->product->fast_buy_price;
            $temp['bid_price'] = $purchase_history->bid_price;
            $temp['post_start_date'] = \Carbon\Carbon::parse($purchase_post->date_time, "UTC")->setTimezone($request->timezone)->format('d.m.Y \a\\t h.ia');
            $temp['post_ended_date'] = CommonHelper::addSecondsToDate($purchase_post->date_time,$purchase_post->frame,'d-m-Y');
            $temp['post_utc_start_at'] = CommonHelper::UTCDateTime($purchase_post->date_time)->format('Y-m-d \a\\t h.ia');
            $temp['post_utc_end_at'] = CommonHelper::addSecondsToUTCDate($purchase_post->date_time,$purchase_post->frame,'Y-m-d \a\\t h.ia');
            $temp['pay_now_show'] = $pay_now_show;
            $temp['rate_show'] = $rate_show;
            $temp['rating'] = $rating;
            array_push($response,$temp);
        }
        return $this->responseWithData($response,"Purchase-history retrieved successfully.");
    }

    public function allFavouritePosts(Request $request){

        $messages = [
            'user_profile_id.required'=>'please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id'=>'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        $response = array();
        $request->request->add(['is_up_coming' => '1']);
        $response['upcoming_post'] = $this->favouritePosts($request);
        $request->request->add(['is_up_coming' => '0']);
        $response['ended_post'] = $this->favouritePosts($request);

        return $this->responseWithData($response,"Favourite Post retrieved Successfully.");
    }

    public function favouritePosts(Request $request)
    {
        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        $frame = Frame::find(1)->value('frame');

        if($request->is_up_coming){
            //if up-coming date/time is not given(with next interval additional second)
            $is_upcoming = 1;
            $local_time = Carbon::now($request->time_zone)->addSeconds((int)$frame*60)->format('H:i');

            $time_array = CommonHelper::getTimeArray($request->time_zone,(int)$frame*60,$is_upcoming);//frame is 15 minute
            $dates = CommonHelper::getUpcomingEndedTradeDate($time_array,$local_time,$request); //local_time ne time_array ma jya set thtu hoy tya set krse pachi response apse

            if($dates['message'] != ''){
                return $this->responseError($dates['message']);
            }

            //default dates for upcoming event
            $startDate = $dates['startDate'];

        }else{
            //ended trade, here start date id compare with trade's ended date
            $is_upcoming = 0;
            $startDate = Carbon::now()->format('Y-m-d H:i');
        }


        //get posts
        $posts = Post::with(['product.product_image', 'product.user.getSellerRate','creditmanagement.buyer',
//                            'creditmanagement' => function($query){
//                                $query->where('transaction_status',1);
//                            },
            'allFavouritePost' => function($query) use($request){
                $query->where('user_profile_id',$request->user_profile_id);
            }])
            ->whereHas('allFavouritePost',function ($query) use($request){
                $query->where('user_profile_id',$request->user_profile_id);
            })
            ->where('is_pause',0);

        if($request->is_up_coming){
            $posts = $posts->where('posts.date_time', '>=',$startDate)
                ->where('can_show',1);
        }else{
            $posts = $posts->where(function ($query) use($startDate){
                $query->whereRaw('"'.$startDate.'" >= DATE_ADD(posts.date_time, INTERVAL frame SECOND)')
                    ->orWhere('can_show',0);
            })
                ->orderBy('posts.date_time','DESC');
        }
        $posts = $posts->get();

        $response = $this->getPostsDetailList($posts,$request->time_zone);
        return $response;
    }

    public function getPostsDetailList($posts,$time_zone,$fast_buy = null){

        $buyerPosts = array();
        foreach ($posts as $post){

            $post_seller = $post->product->user;
            $seller_detail = array();
            $seller_detail['id'] = $post_seller->id;
            $seller_detail['name'] = $post_seller->name;
            $seller_detail['profile_pic'] = $post_seller->profile_pic;
            $seller_detail['user_type'] = isset($post_seller->user_type) ? array_search($post_seller->user_type,Type::toArray()) : '';
            $seller_detail['main_category_id'] = isset($post_seller->main_category_id) ? $post_seller->main_category_id : 0;
            $seller_detail['sub_category_id'] = isset($post_seller->sub_category_id) ? $post_seller->sub_category_id : 0;
            $seller_detail['state'] = isset($post_seller->state->name) ? $post_seller->state->name : '';
            $seller_detail['area'] = isset($post_seller->area->name) ? $post_seller->area->name : '';
            $seller_detail['company_address'] = isset($post_seller->address) ? $post_seller->address : '';
            $seller_detail['rating'] = $post_seller->seller_rating;
            $seller_detail['review_count'] = $post_seller->seller_review;

            $local_date = CommonHelper::UTCToLocalDateTime($post->date_time, $time_zone)->format("Y-m-d H:i:s");
            $time_drop = CommonHelper::getPricedropArray($local_date,((int)$post->frame/5), (int)$post->frame);
            //create time array statically
            $price_array = array($post->starting_price, $post->second_price, $post->third_price, $post->fourth_price, $post->ended_price);
            $price_drop = array();

            foreach ($price_array as $key => $value){
                $price_drop[] = array(
                    'time' => $time_drop[$key],
                    'price' => $price_array[$key]
                );
            }

            $temp = array();
            $temp['id'] = $post->id;
            $temp['display_post_id'] = $post->product->product_id;
            $temp['product_name'] = $post->product->product_name;
            $temp['product_rating'] = $post->post_rate['rate'];
            $temp['product_review_count'] = $post->post_rate['review'];
            // $temp['image'] = isset($post->product->product_image) ? $post->product->product_image->image : '';
            $temp['post_images'] = $post->product->app_post_images;
            $temp['description'] = $post->product->description;
            //if it is approved by admin then only can show preferred status
            $temp['is_preferred'] = $post_seller->is_approved_status ? $post_seller->preferred_status : 0;
            $temp['main_category'] = isset($post->product->maincategory->name) ? $post->product->maincategory->name : '';
            $temp['sub_category'] = isset($post->product->subcategory->name) ? $post->product->subcategory->name : '';
            $temp['species'] = isset($post->product->species->name) ? $post->product->species->name : '';
            $temp['other_species'] = isset($post->product->other_species) ? $post->product->other_species : '';
            $temp['is_imported'] = $post->product->imported;
            $temp['other_imported_info'] = isset($post->product->other_imported_info) ? $post->product->other_imported_info : '';
            $temp['url'] = $post->product->url;
            $temp['address'] = $post->product->address;
            $temp['pickup_point'] = $post->product->pickup_point;
            $temp['grade_id'] = $post->product->grade_id;
            $temp['grade'] = $post->product->grade;
            $temp['starting_price'] = $post->starting_price;
            $temp['second_price'] = $post->second_price;
            $temp['third_price'] = $post->third_price;
            $temp['fourth_price'] = $post->fourth_price;
            $temp['ended_price'] = $post->ended_price;
            $temp['fast_buy'] = $post->product->fast_buy;
            $temp['fast_buy_price'] = $post->product->fast_buy_price;
            $temp['weight_unit_id'] = $post->weight_unit_id;
            $temp['weight_unit'] = $post->unit;
            $temp['weight'] = $post->weight_unit_id>0?$post->weight:'';
            $temp['is_mygap'] = $post->product->is_mygap;
            $temp['is_organic'] = $post->product->is_organic;
            $temp['is_fast_buy'] = $fast_buy ? $post->product->fast_buy : 0;
            $temp['is_favourite'] = isset($post->allFavouritePost[0]->id) ? 1 : 0;
            $temp['post_start_date'] = CommonHelper::UTCToLocalDateTime($post->date_time,$time_zone)->format('d.m.Y \a\\t h.ia');
            $temp['post_end_date'] = CommonHelper::addSecondsToDate($post->date_time,$post->frame,'d-m-Y',$time_zone);
            $temp['post_start_at'] = CommonHelper::UTCToLocalDateTime($post->date_time,$time_zone)->format('Y-m-d h:i:s A');
            $temp['post_end_at'] = CommonHelper::addSecondsToDate($post->date_time,$post->frame,'Y-m-d h:i:s A',$time_zone);
            $temp['post_utc_start_at'] = CommonHelper::UTCDateTime($post->date_time)->format('Y-m-d \a\\t h.ia');
            $temp['post_utc_end_at'] = CommonHelper::addSecondsToUTCDate($post->date_time,$post->frame,'Y-m-d \a\\t h.ia');
            $temp['price_drop'] = $price_drop;
            $temp['seller_detail'] = $seller_detail;
//            $temp['buyer_detail'] = (object)$post->buyer_detail;
            $buyers_detail=$post->buyer_detail;
            if(!empty($buyers_detail)) {
                $buyers_detail['purchase_date'] = \Carbon\Carbon::parse($buyers_detail['purchase_date'], "UTC")->setTimezone($time_zone)->format('d.m.Y \a\\t h.ia');
                $temp['buyers_detail']=$buyers_detail;
            }
            else{
                $temp['buyers_detail']=null;
            }
            array_push($buyerPosts,$temp);
        }

        return $buyerPosts;
    }

    public function allSellerPosts(Request $request){

        $messages = [
            'user_profile_id.required'=>'please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id'=>'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        $response = array();
        $request->request->add(['is_up_coming' => '1']);
        $response['upcoming_post'] = $this->sellerPosts($request);
        $request->request->add(['is_up_coming' => '0']);
        $response['ended_post'] = $this->sellerPosts($request);

        return $this->responseWithData($response,"Seller Post retrieved Successfully.");
    }

    public function getSellerUpcomingPosts(Request $request){

        $messages = [
            'user_profile_id.required'=>'please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id'=>'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        $request->request->add(['is_up_coming' => '1']);
        $response = $this->sellerPosts($request);

        return $this->responseWithData($response,"Seller Upcoming Post retrieved Successfully.");
    }

    public function getSellerEndedPosts(Request $request){

        $messages = [
            'user_profile_id.required'=>'please enter user profile id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id'=>'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        $request->request->add(['is_up_coming' => '0']);
        $response = $this->sellerPosts($request);

        return $this->responseWithData($response,"Seller Ended Post retrieved Successfully.");
    }

    //seller posts
    public function sellerPosts(Request $request)
    {

        $frame = Frame::find(1);
        if($request->is_up_coming){
            //if up-coming date/time is not given(with next interval additional second)
            $is_upcoming = 1;
            $local_time = Carbon::now($request->time_zone)->addSeconds((int)$frame['frame']*60)->format('H:i');

            $time_array = CommonHelper::getTimeArray($request->time_zone,(int)$frame['frame']*60,$is_upcoming);//frame is 15 minute
            $dates = CommonHelper::getUpcomingEndedTradeDate($time_array,$local_time,$request);

            if($dates['message'] != ''){
                return $this->responseError($dates['message']);
            }

            //default dates for upcoming event
            $startDate = $dates['startDate'];

        }else{
            //ended trade, here start date id compare with trade's ended date
            $is_upcoming = 0;
            $startDate = Carbon::now()->format('Y-m-d H:i');
        }

        //get posts
        $posts = Post::with(['product.product_image', 'product.user.getSellerRate','creditmanagement.buyer',
//                            'creditmanagement' => function($query){
//                                $query->where('transaction_status',1);
//                            },
            'allFavouritePost' => function($query) use($request){
                $query->where('user_profile_id',$request->user_profile_id);
            }])
            ->whereHas('product',function ($query) use($request){
                $query->where('user_profile_id',$request->user_profile_id);
            });

        if($request->is_up_coming){
            //get paused posts with ended trade(to play it)
            $posts = $posts->where(function($query) use($startDate){
                $query->where('posts.date_time', '>=',$startDate)
                    ->orwhere('posts.is_pause',1);
            })
                ->where('can_show',1);
        }else{
            $posts = $posts->where(function ($query) use($startDate){
                $query->whereRaw('"'.$startDate.'" >= DATE_ADD(posts.date_time, INTERVAL frame SECOND)')
                    ->orWhere('can_show',0);
            })
                ->where('posts.is_pause',0)
                ->orderBy('posts.date_time','DESC');
        }
        $posts = $posts->get();

        $response = $this->getSellerPostsDetailList($posts, $request->is_up_coming, $frame->repost, $request->time_zone);
        return $response;
    }

    public function getSellerPostsDetailList($posts, $is_upcoming, int $repost, $time_zone){

        $sellerPosts = array();

        foreach ($posts as $post){

            $can_re_post = 1;
            $post_seller = $post->product->user;
            $remain_repost = $repost - (int)$post->product->repost;
            if((int)$post->product->repost >=  $repost){
                $can_re_post = 0;
            }
            /*$currdate=Carbon::now();
            if(strtotime($currdate) > strtotime($post->product->end_time)){
                $can_re_post=0;
            }*/
            $temp = array();
            $temp['id'] = $post->id;
            $temp['display_post_id'] = $post->product->product_id;
            $temp['product_name'] = $post->product->product_name;
            $temp['product_rating'] = $post->post_rate['rate'];
            $temp['product_review_count'] = $post->post_rate['review'];
            $temp['image'] = isset($post->product->product_image) ? $post->product->product_image->image : '';
            $temp['post_images'] = $post->product->app_post_images;
            $temp['description'] = $post->product->description;
            //if it is approved by admin then only can show preferred status
            $temp['is_preferred'] = $post_seller->is_approved_status ? $post_seller->preferred_status : 0;
//            $temp['main_category'] = isset($post->product->maincategory->name) ? $post->product->maincategory->name : '';
//            $temp['sub_category'] = isset($post->product->subcategory->name) ? $post->product->subcategory->name : '';
//            $temp['species'] = isset($post->product->species->name) ? $post->product->species->name : '';
//            $temp['other_species'] = isset($post->product->other_species) ? $post->product->other_species : '';
//            $temp['is_imported'] = $post->product->imported;
//            $temp['other_imported_info'] = isset($post->product->other_imported_info) ? $post->product->other_imported_info : '';
//            $temp['url'] = $post->product->url;
//            $temp['address'] = $post->product->address;
//            $temp['pickup_point'] = $post->product->pickup_point;
//            $temp['grade'] = $post->product->grade;
            $temp['starting_price'] = $post->starting_price;
            $temp['second_price'] = $post->second_price;
            $temp['third_price'] = $post->third_price;
            $temp['fourth_price'] = $post->fourth_price;
            $temp['ended_price'] = $post->ended_price;
            $temp['fast_buy'] = $post->product->fast_buy;
            $temp['fast_buy_price'] = $post->product->fast_buy_price;
            $temp['weight_unit'] = $post->unit;
            $temp['weight'] = $post->weight;
//            $temp['is_mygap'] = $post->product->is_mygap;
//            $temp['is_organic'] = $post->product->is_organic;
//            $temp['is_fast_buy'] = $fast_buy ? $post->product->fast_buy : 0;
            $temp['is_favourite'] = isset($post->allFavouritePost[0]->id) ? 1 : 0;
            $temp['can_edit'] = $is_upcoming ? 1 : 0;
            $temp['can_delete'] = $is_upcoming ? 1 : 0;
            $temp['can_pause'] = $is_upcoming ? 1 : 0;
            $is_pause = $post->is_pause;//post is pause or not
            $temp['is_pause'] = $is_upcoming ? $is_pause : 0;
            $temp['pause_time'] = ($post->pause_time) ? CommonHelper::UTCToLocalDateTime($post->pause_time,$time_zone)->format('Y-m-d H:i:s') : '';
            $temp['remain_repost'] = $remain_repost;
            $temp['can_re_post'] = $is_upcoming ? 0 : $can_re_post;
            $temp['post_start_date'] = CommonHelper::UTCToLocalDateTime($post->date_time,$time_zone)->format('d.m.Y \a\\t h.ia');
            $temp['post_end_date'] = CommonHelper::addSecondsToDate($post->date_time,$post->frame,'d-m-Y',$time_zone);
            $temp['post_start_at'] = CommonHelper::UTCToLocalDateTime($post->date_time,$time_zone)->format('Y-m-d h:i:s A');
            $temp['post_end_at'] = CommonHelper::addSecondsToDate($post->date_time,$post->frame,'Y-m-d h:i:s A',$time_zone);
            $temp['post_utc_start_at'] = CommonHelper::UTCDateTime($post->date_time)->format('Y-m-d \a\\t h.ia');
            $temp['post_utc_end_at'] = CommonHelper::addSecondsToUTCDate($post->date_time,$post->frame,'Y-m-d \a\\t h.ia');
            $temp['buyer_detail'] = (object)$post->buyer_detail;

            array_push($sellerPosts,$temp);
        }

        return $sellerPosts;
    }

    public function deletePost(Request $request){

        $messages = [
            'post_id.required'=>'please enter post id.',
        ];

        $validator = Validator::make($request->all(), [
            'post_id'=>'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $post = Post::with('product')
            ->select('posts.*',DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND) as end_time'))
            ->where('id',$request->post_id)
            ->first();

        if (!$post) {
            return $this->responseError("Post Not Found");
        }

        $current_time = Carbon::now();

        if((strtotime($current_time) >= strtotime($post->date_time)) && (strtotime($current_time) < strtotime($post->end_time))){
            return $this->responseError("Live Trade Post can't be deleted");
        }
        if((strtotime($current_time) > strtotime($post->end_time))){
            return $this->responseError("Ended Trade Post can't be deleted");
        }

        $product_id = $post->product_id;
        //delete post
        $post->delete();

        //check if other post not exist then delete it
        $otherPost = Post::where('product_id',$product_id)->get();

        if($otherPost->count() == 0){

            $post->product->delete();
            $product_images = $post->product->images;
            foreach ($product_images as $product_image) {
                $path = public_path($product_image->local_image);
                if(file_exists($path)){
                    unlink($path);
                }
                $product_image->delete();
            }
        }
        return $this->sendSuccess("Post Deleted Successfully");
    }

    public function playPausePost(Request $request){

        $messages = [
            'post_id.required'=>'please enter post id.',
        ];

        $validation = [
            'post_id'=>'required',
        ];

        $validator = Validator::make($request->all(), $validation, $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $post = Post::select('posts.*',DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND) as end_time'))
            ->where('id',$request->post_id)
            ->first();

        if (!$post) {
            return $this->responseError("Post Not Found");
        }

        //if there is second related issue then get local-time from android side
        $current_date = Carbon::now()->format('Y-m-d H:i');
        if($request->date_time){
            $current_date = CommonHelper::LocalToUtcDateTime($request->date_time)->format('Y-m-d H:i:s');
        }

        //if post already paused then no need to check live post or ended post
        if((strtotime($current_date) >= strtotime($post->date_time)) && (strtotime($current_date) < strtotime($post->end_time)) && !$post->is_pause){
            return $this->responseError("Live Trade Post can't be Paused");
        }
        if((strtotime($current_date) > strtotime($post->end_time)) && !$post->is_pause){
            return $this->responseError("Ended Trade Post can't be Paused");
        }

        if($post->is_pause) {
            $extra_second = strtotime($current_date) - strtotime($post->pause_time);

            //update start-date, add extra-time in post
            $date_time = date('Y-m-d H:i:s',strtotime('+'.(int)$extra_second.' seconds',strtotime($post->date_time)));
            $post->date_time = $date_time;
            $post->is_pause = 0;

            //update end-date, add extra-time in product
            $end_time = date('Y-m-d H:i:s',strtotime('+'.(int)$extra_second.' seconds',strtotime($post->product->end_time)));
            $post->product->end_time = $end_time;
            $post->product->save();

            $message = 'Post Play Successfully';

        }else {
            $post->pause_time = $current_date;
            $post->is_pause = 1;
            $message = 'Post Pause Successfully';
        }
        $post->save();

        return $this->sendSuccess($message);
    }

    public function getTimeArray(Request $request){

        $frame = Frame::find(1)->value('frame');
        $time_array = CommonHelper::getTimeArray($request->time_zone,(int)$frame*60);//frame is 15 minute

        return $this->responseWithData($time_array,"Time Array retrieved Successfully.");
    }

    public function add_report(Request $request){
        $messages = [
            'user_id.required'=>'please enter user id.',
            'post_id.required'=>'please enter post id.',
            'message.required'=>'please enter report message.',
        ];

        $validation = [
            'user_id'=>'required',
            'post_id'=>'required',
            'message'=>'required',
        ];

        $validator = Validator::make($request->all(), $validation, $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }
        $user=UserProfile::find($request->user_id);
        if(!$user){
            return $this->responseError("User not found.");
        }
        $post=Post::find($request->post_id);
        if(!$post){
            return $this->responseError("Post not found.");
        }
        $report=new Report();
        $report->user_id=$request->user_id;
        $report->post_id=$request->post_id;
        $report->message=$request->message;
        $report->save();
        return $this->sendSuccess("Report added successfully.");
    }

    public function report_logistic_company(Request $request){
        $messages = [
            'user_id.required'=>'please enter user id.',
            'logisctic_company_id.required'=>'please enter post id.',
            'message.required'=>'please enter report message.',
        ];

        $validation = [
            'user_id'=>'required',
            'logisctic_company_id'=>'required',
            'message'=>'required',
        ];

        $validator = Validator::make($request->all(), $validation, $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }
        $user=UserProfile::find($request->user_id);
        if(!$user){
            return $this->responseError("User not found.");
        }
        $logistic_Company=LogisticCompany::find($request->logisctic_company_id);
        if(!$logistic_Company){
            return $this->responseError("Logistic Company not found.");
        }
        $report=new Report();
        $report->user_id=$request->user_id;
        $report->logisctic_company_id=$request->logisctic_company_id;
        $report->message=$request->message;
        $report->save();
        return $this->sendSuccess("Report added successfully.");
    }
}
