<?php

namespace App\Http\Controllers;

use App\Exports\CsvExportSalesReport;
use App\Helpers\CommonHelper;
use App\Models\CreditManagement;
use App\Models\Frame;
use App\Models\Post;
use App\Models\UserProfile;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function LiveTrade()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $f = Frame::find(1);
        return view("products.index",compact('f','preferd_cnt','prefered_ids','preferd_req'))->with('view','live');
    }
    public function UpcomingTrade()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $f = Frame::find(1);
        return view("products.index",compact('f','preferd_cnt','prefered_ids','preferd_req'))->with('view','upcoming');
    }
    public function EndedTrade()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $f = Frame::find(1);
        return view("products.index",compact('f','preferd_cnt','prefered_ids','preferd_req'))->with('view','ended');
    }

    public function frame(Request $request)
    {
        $request->validate(['frame'=>"required","repost"=>"required","creditcard"=>'required','fpx'=>'required']);
        $f=Frame::find(1);

        $current_date = Carbon::now()->format('Y-m-d H:i:s');
        $is_post_exist = Post::where(function ($query) use($current_date){
                            $query->where('date_time','>=',$current_date)
                                ->orwhere('is_pause',1);
                        })
            ->where('can_show',1)
            ->get();

        if($is_post_exist->count() > 0) {
            Flash::error('Posts are exist. You can not make change in frame');
        } else {
            $f->frame=$request->frame;
        }
        $f->repost=$request->repost;
        $f->creditcard=$request->creditcard;
        $f->fpx=$request->fpx;
        $f->save();

//        //set frame time in future posts or pause posts
//        $current_date = Carbon::now()->format('Y-m-d H:i:s');
//        Post::where(function ($query) use($current_date, $f){
//                            $query->where('date_time','>=',$current_date)
//                                    ->orwhere('is_pause',1);
//                        })
//                        ->where('can_show',1)
//                        ->update(['frame' => $f->frame * 60]);

        return redirect()->back();
    }

    public function live_detail($id){
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $post = Post::select('posts.*', DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND) as end_date'))->with('product.images','product.user')->find($id);
        $local_date = CommonHelper::UTCToLocalDateTime($post->date_time)->format("Y-m-d H:i:s");
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

        $post->price_drop = $price_drop;
        $post->dateTime = $post->end_date;//end-date
        $post->post_type = 0;//live-post
        return view('products.livedetail',compact('post','preferd_cnt','preferd_req','prefered_ids'));
    }

    public function upcoming_detail($id){
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $post = Post::select('posts.*', DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND) as end_date'))->with('product.images','product.user')->find($id);

        $post->dateTime = $post->end_date;//end-date
        $post->post_type = 1;//upcoming-post
        return view('products.livedetail',compact('post','preferd_cnt','preferd_req','prefered_ids'));
    }

    public function ended_detail($id){
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $post = Post::select('posts.*', DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND) as end_date'))
                        ->with('product.images','product.user','creditmanagement.buyer')
                        ->find($id);

        $local_date = CommonHelper::UTCToLocalDateTime($post->date_time)->format("Y-m-d H:i:s");
        $time_drop = CommonHelper::getPricedropArray($local_date,((int)$post->frame/5),(int)$post->frame);
        //create time array statically

        $creditmanagement=\App\Models\CreditManagement::where('post_id',$post->id)->get();
        if($creditmanagement->count()>0) {
            if ($post->starting_price == $creditmanagement[0]->bid_price) {
                $price_array = array($post->starting_price);
            } else if ($post->second_price == $creditmanagement[0]->bid_price) {
                $price_array = array($post->starting_price, $post->second_price);
            } else if ($post->third_price == $creditmanagement[0]->bid_price) {
                $price_array = array($post->starting_price, $post->second_price, $post->third_price);
            } else if ($post->fourth_price == $creditmanagement[0]->bid_price) {
                $price_array = array($post->starting_price, $post->second_price, $post->third_price, $post->fourth_price);
            } else {
                $price_array = array($post->starting_price, $post->second_price, $post->third_price, $post->fourth_price, $post->ended_price);
            }
            $price_drop = array();
            foreach ($price_array as $key => $value){
                $price_drop[] = array(
                    'time' => $time_drop[$key],
                    'price' => $price_array[$key]
                );
            }
            $post->price_drop = $price_drop;
        }

        $post->dateTime = $post->end_date;//end-date
        $post->post_type = 2;//upcoming-post
        $post->end_date = CommonHelper::UTCToLocalDateTime($post->end_date);//end-date

        return view('products.livedetail',compact('post','preferd_cnt','preferd_req','prefered_ids'));
    }

    public function get_live_products(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $local_date = Carbon::now(env('TIME_ZONE'))->format('Y-m-d');
        $local_time = Carbon::now(env('TIME_ZONE'))->format('H:i:s');

        $current_date = CommonHelper::LocalToUtcDateTime($local_date .' '.$local_time)->format('Y-m-d H:i:s');
        $posts = Post::select('posts.*', DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND) as end_date'), DB::raw('TIME_TO_SEC(TIMEDIFF(DATE_ADD(date_time, INTERVAL frame SECOND),"'.Carbon::now().'")) sec_diff'))
                            ->with('product.maincategory','product.user')
                            ->where('is_pause',0)
                            ->where('can_show',1)
                            ->whereRaw('"'.$current_date.'" >= date_time AND "'.$current_date.'" < DATE_ADD(date_time, INTERVAL frame SECOND)');
//        dd($posts->get()->toArray());
        if($request->seller_id){
            $posts = $posts->whereHas('product',function ($query) use($request){
                        $query->where('user_profile_id',$request->seller_id);
                     });
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

        $posts = $posts->paginate($request->per_page);

        if($request->seller_id){
            return view('user_profiles.live_sub_table',compact('posts'))->render();
        }
        return view('products.live_sub_table',compact('posts','preferd_cnt','preferd_req','prefered_ids'))->render();
    }

    public function get_upcoming_products(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $frame = Frame::find(1)->value('frame');
        //if up-coming date/time is not given(with next interval additional second)
        $is_upcoming = 1;
        $local_time = Carbon::now(env('TIME_ZONE'))->addSeconds((int)$frame*60)->format('H:i');

        $time_array = CommonHelper::getTimeArray(env('TIME_ZONE'),(int)$frame*60,$is_upcoming);//frame is 15 minute
        $dates = CommonHelper::getUpcomingEndedTradeDate($time_array,$local_time,$request);

        //default dates for upcoming event
        $startDate = $dates['startDate'];
        $getTime = explode(' ',$dates['localStartDate']);

        $start_date = null;
        $end_date = null;
        $time = null;

        if(isset($request->start_date)){
            $time = '00:00';
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            if(strtotime(Carbon::now()->format('Y-m-d')) == strtotime($start_date)){
                $time = $getTime[1];
            }
        }

        if(isset($request->end_date)){
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        }

        //request time should be after request date(to overwrite local time)
        if(isset($request->time)){
            $start_date = $start_date ? $start_date : $getTime[0];
            $time = Carbon::parse($request->time)->format('H:i');
        }

        if($start_date || $time){
            $new_start_date = CommonHelper::LocalToUtcDateTime($start_date .' '.$time);
        }

        if($end_date){
            $new_end_date = CommonHelper::LocalToUtcDateTime($end_date .' 23:59:59');
        }

        //get posts
        $posts = Post::select('posts.*',DB::raw('TIME_TO_SEC(TIMEDIFF(posts.date_time,"'.Carbon::now().'")) sec_diff'))
            ->with('product.product_image', 'product.user.getSellerRate','creditmanagement.buyer')
            ->where('is_pause',0)
            ->where('can_show',1)
            ->where('posts.date_time', '>=',$startDate);

        if($request->start_date) {
            $posts = $posts->where('posts.date_time', '>=',$new_start_date);
        }

        if($request->end_date) {
            $posts = $posts->where('posts.date_time', '<',$new_end_date);
        }

        if($request->seller_id){
            $posts = $posts->whereHas('product',function ($query) use($request){
                $query->where('user_profile_id',$request->seller_id);
            });
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

        if($request->seller_id){
            return view('user_profiles.upcoming_sub_table',compact('posts'))->render();
        }
        return view('products.upcoming_sub_table',compact('posts','preferd_cnt','preferd_req','prefered_ids'))->render();
    }

    public function get_ended_products(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $startDate = Carbon::now()->format('Y-m-d H:i');
        $getTime = explode(' ',$startDate);

        $start_date = null;
        $end_date = null;
        $time = null;

        if(isset($request->start_date)){
            $time = '00:00';
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            if(strtotime(Carbon::now()->format('Y-m-d')) == strtotime($start_date)){
                $time = $getTime[1];
            }
        }

        if(isset($request->end_date)){
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        }

        //request time should be after request date(to overwrite local time)
        if(isset($request->time)){
            $start_date = $start_date ? $start_date : $getTime[0];
            $time = Carbon::parse($request->time)->format('H:i');
        }

        if($start_date || $time){
            $new_start_date = CommonHelper::LocalToUtcDateTime($start_date .' '.$time);
        }

        if($end_date){
            $new_end_date = CommonHelper::LocalToUtcDateTime($end_date .' 23:59:59');
        }

        //get posts
        $posts = Post::select('posts.*', DB::raw('DATE_ADD(date_time, INTERVAL frame SECOND) as end_date'))
            ->with(['product.product_image', 'product.user.getSellerRate','creditmanagement.buyer'
                    /*,'creditmanagement' => function($query){
                        $query->where('transaction_status',1);
                    }*/])
            ->where('is_pause',0)
            ->where(function ($query) use($startDate){
                $query->whereRaw('"'.$startDate.'" >= DATE_ADD(posts.date_time, INTERVAL frame SECOND)')
                    ->orWhere('can_show',0);
            });

        if($request->start_date){
            $posts = $posts->whereRaw('"'.$new_start_date.'" <= DATE_ADD(posts.date_time, INTERVAL frame SECOND)');
        }

        if($request->end_date) {
            $posts = $posts->whereRaw('"'.$new_end_date.'" > DATE_ADD(posts.date_time, INTERVAL frame SECOND)');
        }

        if($request->seller_id){
            $posts = $posts->whereHas('product',function ($query) use($request){
                $query->where('user_profile_id',$request->seller_id);
            });
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

        $posts = $posts->orderBy('posts.date_time','DESC')->paginate($request->per_page);
        if($request->seller_id){
            return view('user_profiles.ended_sub_table',compact('posts'))->render();
        }
        return view('products.ended_sub_table',compact('posts','preferd_cnt','preferd_req','prefered_ids'))->render();
    }

    public function salesReportExport(Request $request) {
        $creditManagements = CreditManagement::Query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $creditManagements = $creditManagements->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $creditManagements = $creditManagements->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $creditManagements = $creditManagements->where(function ($mainQuery) use($request){
                $mainQuery->whereHas('buyer',function ($query) use($request){
                    $query->where('name','Like','%'.$request->search.'%');
                })
                    ->orwhereHas('post.product.user',function ($query) use($request){
                        $query->where('name','Like','%'.$request->search.'%');
                    });
            });
        }
        $total_amount = $creditManagements->sum('total_amount');
        $creditManagements = $creditManagements->get();

        if($request->export_type){
            // instantiate and use the dompdf class
            $pdf = PDF::loadView('pdfs.salesData',compact( 'creditManagements','total_amount'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('SalesReport.pdf');

        } else {
            return Excel::download(new CsvExportSalesReport($creditManagements,$total_amount), 'SalesReport.xlsx');
        }
    }

    public function frame_setcreditrm(Request $request)
    {
        $request->validate(['credit'=>"required","rm"=>"required"]);
        $f=Frame::find(1);

        $f->credit=$request->credit;
        $f->rm=$request->rm;
        $f->save();

        return redirect()->back();
    }
}
