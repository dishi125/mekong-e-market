<?php

namespace App\Http\Controllers;

use App\Models\CreditManagement;
use App\Models\MyPackage;
use App\Models\Post;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_date=Carbon::now();
        $tot_user=UserProfile::where('parent_id','=','0')->where('deleted_at',null)->count();
        $tot_retailer=UserProfile::where('parent_id','=','0')->where('user_type',2)->where('deleted_at',null)->count();
        $tot_wholesaler=UserProfile::where('parent_id','=','0')->where('user_type',3)->where('deleted_at',null)->count();
        $tot_farmer=UserProfile::where('parent_id','=','0')->where('user_type',1)->where('deleted_at',null)->count();
        $tot_buyer=UserProfile::where('parent_id','=','0')->where('user_type',4)->where('deleted_at',null)->count();
        $tot_product=Post::where('is_pause',0)->where('deleted_at',null)->count();
        $tot_livetrade=Post::whereRaw('"'.$current_date.'" >= date_time AND "'.$current_date.'" < DATE_ADD(date_time, INTERVAL frame SECOND)')
                            ->where('is_pause',0)
                            ->where('can_show',1)
                            ->where('deleted_at',null)
                            ->count();
        $tot_upcomingtrade=Post::whereRaw('"'.$current_date.'"< date_time')
                                ->where('is_pause',0)
                                ->where('can_show',1)
                                ->where('deleted_at',null)
                                ->count();
        $tot_endedtrade=Post::whereRaw('"'.$current_date.'"> DATE_ADD(date_time, INTERVAL frame SECOND)')
                              ->where('is_pause',0)
                              ->count();
        $tot_success_deal=CreditManagement::where('transaction_status',1)->count();

        $tot_sales=CreditManagement::select(DB::raw("SUM(total_amount)"))->where('transaction_status',1)->pluck('SUM(total_amount)')->first();

        $tot_topup=MyPackage::with('credit_transaction')->where('transaction_status',2)->get();
        $tot_topup=$tot_topup->sum('credit_transaction.amount');

        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        return view('home')->with(compact('tot_user'))->with(compact('tot_retailer'))->with(compact('tot_wholesaler'))->with(compact('tot_farmer'))->with(compact('tot_buyer'))->with(compact('tot_product'))->with(compact('tot_livetrade'))->with(compact('tot_upcomingtrade'))->with(compact('tot_endedtrade'))->with(compact('tot_success_deal'))->with(compact('tot_sales'))->with(compact('tot_topup'))->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    public function set_allnotificationdata(Request $request){
        $output="";

//        $request->prefered_data=[8,9,13,16,17,18,19];
        $data=UserProfile::wherein('id',$request->prefered_data)->get();

        foreach ($data as $pdata){
            $output.='<li style="border-bottom: 1px solid #f4f4f4;padding-left: 10px"><h4 style="font-size: 14px">Request from '.$pdata->name.' for preferred</h4></li>';
        }
        return response()->json(['success' => true, 'message' => $output]);
    }

    public function change_nofification_status(Request $request){
        $data=UserProfile::wherein('id',$request->prefered_data)->update(['is_seen_preferred'=>1]);
        return response()->json(['success' => true]);
    }

}
